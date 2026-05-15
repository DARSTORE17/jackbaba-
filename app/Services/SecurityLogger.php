<?php

namespace App\Services;

use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SecurityLogger
{
    public function logEvent(string $eventType, string $message, array $context = [], string $severity = "medium"): SecurityLog
    {
        $request = request();

        $logData = [
            "event_type" => $eventType,
            "severity" => $severity,
            "message" => $message,
            "context" => $context,
            "ip_address" => $request ? $request->ip() : null,
            "user_agent" => $request ? $request->userAgent() : null,
            "user_id" => auth()->id(),
            "username" => auth()->user()?->name,
            "url" => $request ? $request->fullUrl() : null,
            "method" => $request ? $request->method() : null,
            "request_data" => $this->sanitizeRequestData($request),
        ];

        $securityLog = SecurityLog::create($logData);

        // Log to Laravel log as well
        Log::channel("security")->info("Security Event [{$severity}]: {$eventType} - {$message}", $context);

        // Send alert for high-risk events
        if (in_array($severity, ["high", "critical"])) {
            $this->sendAlert($securityLog);
        }

        return $securityLog;
    }

    public function logFailedLogin(string $username, string $reason = "Invalid credentials"): SecurityLog
    {
        return $this->logEvent(
            "failed_login",
            "Failed login attempt for user: {$username}",
            ["username" => $username, "reason" => $reason],
            "medium"
        );
    }

    public function logSuccessfulLogin(): SecurityLog
    {
        return $this->logEvent(
            "successful_login",
            "User logged in successfully",
            [],
            "low"
        );
    }

    public function logSuspiciousActivity(string $activity, array $details = []): SecurityLog
    {
        return $this->logEvent(
            "suspicious_activity",
            "Suspicious activity detected: {$activity}",
            $details,
            "high"
        );
    }

    public function logSqlInjectionAttempt(string $query): SecurityLog
    {
        return $this->logEvent(
            "sql_injection_attempt",
            "Potential SQL injection attempt detected",
            ["query" => $query],
            "critical"
        );
    }

    public function logXssAttempt(string $input): SecurityLog
    {
        return $this->logEvent(
            "xss_attempt",
            "Potential XSS attempt detected",
            ["input" => $input],
            "high"
        );
    }

    public function logBruteForceAttempt(string $username): SecurityLog
    {
        return $this->logEvent(
            "brute_force_attempt",
            "Brute force attack detected for user: {$username}",
            ["username" => $username],
            "high"
        );
    }

    public function logUnauthorizedAccess(string $resource): SecurityLog
    {
        return $this->logEvent(
            "unauthorized_access",
            "Unauthorized access attempt to: {$resource}",
            ["resource" => $resource],
            "high"
        );
    }

    public function logFileUpload(string $filename, string $type): SecurityLog
    {
        return $this->logEvent(
            "file_upload",
            "File uploaded: {$filename}",
            ["filename" => $filename, "type" => $type],
            "low"
        );
    }

    public function logAdminAction(string $action, array $details = []): SecurityLog
    {
        return $this->logEvent(
            "admin_action",
            "Admin action performed: {$action}",
            $details,
            "medium"
        );
    }

    private function sanitizeRequestData(?Request $request): ?array
    {
        if (!$request) {
            return null;
        }

        $data = $request->all();

        // Remove sensitive fields
        $sensitiveFields = ["password", "password_confirmation", "token", "api_key", "secret"];
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = "[REDACTED]";
            }
        }

        return $data;
    }

    private function sendAlert(SecurityLog $log): void
    {
        // Mark as alert sent
        $log->update([
            "alert_sent" => true,
            "alert_sent_at" => now(),
        ]);

        // Here you could send email notifications, Slack messages, etc.
        // For now, we'll just log it
        Log::channel("security")->emergency("SECURITY ALERT: {$log->event_type} - {$log->message}", [
            "log_id" => $log->id,
            "severity" => $log->severity,
            "ip_address" => $log->ip_address,
            "user" => $log->username,
        ]);

        // TODO: Implement actual notification system (email, Slack, SMS, etc.)
    }

    public function detectBruteForce(string $username, int $maxAttempts = 5, int $timeWindow = 15): bool
    {
        $attempts = SecurityLog::where("event_type", "failed_login")
            ->where("username", $username)
            ->where("created_at", ">=", now()->subMinutes($timeWindow))
            ->count();

        return $attempts >= $maxAttempts;
    }

    public function detectSuspiciousPatterns(): array
    {
        $patterns = [];

        // Check for multiple failed logins from same IP
        $failedLogins = SecurityLog::where("event_type", "failed_login")
            ->where("created_at", ">=", now()->subHours(1))
            ->selectRaw("ip_address, COUNT(*) as attempts")
            ->groupBy("ip_address")
            ->having("attempts", ">=", 10)
            ->get();

        if ($failedLogins->isNotEmpty()) {
            $patterns[] = [
                "type" => "multiple_failed_logins",
                "description" => "Multiple failed login attempts from same IP",
                "data" => $failedLogins,
            ];
        }

        // Check for SQL injection patterns
        $sqlInjectionAttempts = SecurityLog::where("event_type", "sql_injection_attempt")
            ->where("created_at", ">=", now()->subHours(1))
            ->count();

        if ($sqlInjectionAttempts > 0) {
            $patterns[] = [
                "type" => "sql_injection_attempts",
                "description" => "SQL injection attempts detected",
                "count" => $sqlInjectionAttempts,
            ];
        }

        return $patterns;
    }
}