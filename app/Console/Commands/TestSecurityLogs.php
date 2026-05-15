<?php

namespace App\Console\Commands;

use App\Services\SecurityLogger;
use Illuminate\Console\Command;

class TestSecurityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:test-logs {--count=5 : Number of test events to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate test security events for testing the security monitoring system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');
        $securityLogger = app(SecurityLogger::class);

        $this->info("Generating {$count} test security events...");

        $events = [
            ['type' => 'sql_injection_attempt', 'severity' => 'high', 'message' => 'SQL injection detected: UNION SELECT * FROM users'],
            ['type' => 'xss_attempt', 'severity' => 'medium', 'message' => 'XSS attempt detected: <script>alert("hacked")</script>'],
            ['type' => 'suspicious_request', 'severity' => 'low', 'message' => 'Suspicious request pattern detected'],
            ['type' => 'brute_force_attempt', 'severity' => 'high', 'message' => 'Multiple failed login attempts detected'],
            ['type' => 'unauthorized_access', 'severity' => 'medium', 'message' => 'Unauthorized access attempt to admin panel'],
        ];

        for ($i = 0; $i < $count; $i++) {
            $event = $events[$i % count($events)];
            $securityLogger->logEvent(
                $event['type'],
                $event['message'] . " (Test event #{$i})",
                ['test' => true, 'event_number' => $i],
                $event['severity']
            );
            $this->line("Logged: {$event['type']} - {$event['severity']}");
        }

        $this->info('Test security events generated successfully!');
        $this->info('You can now view them in the admin security panel.');
    }
}
