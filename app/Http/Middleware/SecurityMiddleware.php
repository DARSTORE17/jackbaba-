<?php

namespace App\Http\Middleware;

use App\Services\SecurityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    protected SecurityLogger $securityLogger;

    public function __construct(SecurityLogger $securityLogger)
    {
        $this->securityLogger = $securityLogger;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for SQL injection attempts
        $this->detectSqlInjection($request);

        // Check for XSS attempts
        $this->detectXss($request);

        // Check for suspicious patterns
        $this->detectSuspiciousPatterns($request);

        // Rate limiting for sensitive endpoints
        $this->checkRateLimits($request);

        $response = $next($request);

        // Log admin actions
        if ($request->is('admin/*') && auth()->check()) {
            $this->logAdminAction($request);
        }

        return $response;
    }

    private function detectSqlInjection(Request $request): void
    {
        $sqlPatterns = [
            '/\b(union|select|insert|update|delete|drop|create|alter)\b.*\b(select|from|where|into)\b/i',
            '/\b(or|and)\b.*(=|<|>)/i',
            '/--|#|\/\*|\*\//',
            '/\bscript\b/i',
            '/\bexec\b|\bxp_\w+\b/i',
            '/\b(char|varchar|nvarchar)\s*\(/i',
            '/;\s*(union|select|insert|update|delete)/i',
        ];

        $inputData = $this->getAllInput($request);

        foreach ($inputData as $key => $value) {
            if (is_string($value)) {
                foreach ($sqlPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->securityLogger->logSqlInjectionAttempt($value);
                        break 2;
                    }
                }
            }
        }
    }

    private function detectXss(Request $request): void
    {
        $xssPatterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',
            '/onmouseover\s*=/i',
            '/<iframe[^>]*>/i',
            '/<object[^>]*>/i',
            '/<embed[^>]*>/i',
            '/<form[^>]*>/i',
            '/<input[^>]*>/i',
            '/<meta[^>]*>/i',
        ];

        $inputData = $this->getAllInput($request);

        foreach ($inputData as $key => $value) {
            if (is_string($value)) {
                foreach ($xssPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->securityLogger->logXssAttempt($value);
                        break 2;
                    }
                }
            }
        }
    }

    private function detectSuspiciousPatterns(Request $request): void
    {
        $userAgent = $request->userAgent();

        // Check for suspicious user agents
        $suspiciousAgents = [
            'sqlmap',
            'nmap',
            'nikto',
            'dirbuster',
            'gobuster',
            'wpscan',
            'joomlavs',
            'nessus',
            'acunetix',
            'openvas',
        ];

        if ($userAgent) {
            foreach ($suspiciousAgents as $agent) {
                if (stripos($userAgent, $agent) !== false) {
                    $this->securityLogger->logSuspiciousActivity(
                        "Suspicious user agent detected: {$agent}",
                        ['user_agent' => $userAgent]
                    );
                    break;
                }
            }
        }

        // Check for directory traversal attempts
        $url = $request->fullUrl();
        if (preg_match('/\.\.\//', $url) || preg_match('/\.\.\\\\/', $url)) {
            $this->securityLogger->logSuspiciousActivity(
                "Directory traversal attempt detected",
                ['url' => $url]
            );
        }

        // Check for unusual request methods
        $method = $request->method();
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'HEAD', 'OPTIONS'];
        if (!in_array($method, $allowedMethods)) {
            $this->securityLogger->logSuspiciousActivity(
                "Unusual HTTP method: {$method}",
                ['method' => $method, 'url' => $url]
            );
        }
    }

    private function checkRateLimits(Request $request): void
    {
        // Rate limiting for login attempts
        if ($request->is('login') || $request->is('admin/login')) {
            $key = 'login:' . $request->ip();

            if (RateLimiter::tooManyAttempts($key, 5)) {
                $this->securityLogger->logBruteForceAttempt(
                    $request->input('email') ?: 'unknown'
                );
            }

            RateLimiter::hit($key, 60); // 5 attempts per minute
        }

        // Rate limiting for admin actions
        if ($request->is('admin/*')) {
            $key = 'admin:' . $request->ip();

            if (RateLimiter::tooManyAttempts($key, 30)) {
                $this->securityLogger->logSuspiciousActivity(
                    "Rate limit exceeded for admin actions",
                    ['ip' => $request->ip()]
                );
            }

            RateLimiter::hit($key, 60); // 30 requests per minute
        }
    }

    private function logAdminAction(Request $request): void
    {
        $action = $this->getActionName($request);
        $details = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_id' => auth()->id(),
        ];

        $this->securityLogger->logAdminAction($action, $details);
    }

    private function getActionName(Request $request): string
    {
        $path = $request->path();
        $method = $request->method();

        if (str_contains($path, 'users')) {
            return $method === 'GET' ? 'viewed_users' :
                   ($method === 'POST' ? 'created_user' :
                   ($method === 'PUT' ? 'updated_user' : 'deleted_user'));
        }

        if (str_contains($path, 'products')) {
            return $method === 'GET' ? 'viewed_products' :
                   ($method === 'POST' ? 'created_product' :
                   ($method === 'PUT' ? 'updated_product' : 'deleted_product'));
        }

        if (str_contains($path, 'categories')) {
            return $method === 'GET' ? 'viewed_categories' :
                   ($method === 'POST' ? 'created_category' :
                   ($method === 'PUT' ? 'updated_category' : 'deleted_category'));
        }

        if (str_contains($path, 'settings')) {
            return 'modified_settings';
        }

        if (str_contains($path, 'database')) {
            return 'accessed_database_management';
        }

        return 'performed_admin_action';
    }

    private function getAllInput(Request $request): array
    {
        return array_merge(
            $request->all(),
            $request->query(),
            $request->route() ? $request->route()->parameters() : []
        );
    }
}
