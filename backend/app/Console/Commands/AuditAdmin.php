<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Http\Request;
use App\Services\AnalyticsService;

class AuditAdmin extends Command
{
    protected $signature = 'audit:admin';
    protected $description = 'Audit all Admin functionalities';

    public function handle()
    {
        $this->info("Starting Admin Functionality Audit...");

        // 1. Authenticate as Admin
        $admin = User::role('admin')->first();
        if (!$admin) {
            $this->error("No admin user found in database. Cannot perform audit safely.");
            return;
        }
        Auth::login($admin);
        $this->info("Authenticated as Admin: {$admin->name}");

        $controller = app(AdminController::class);

        // 2. Test GET endpoints
        $endpoints = [
            'dashboard',
            'users',
            'categories',
            'events',
            'pendingOrganizers',
            'copyrightReports',
            'revenue',
            'reviews',
            'allCoupons',
            'promotionsIndex',
        ];

        foreach ($endpoints as $method) {
            try {
                $response = $controller->{$method}(new Request());
                if (method_exists($response, 'getStatusCode') && $response->getStatusCode() === 200) {
                    $this->info("✅ {$method} - OK");
                } else {
                    $this->warn("⚠️ {$method} - Returned non-200 status or unexpected response type.");
                }
            } catch (\Exception $e) {
                $this->error("❌ {$method} - Exception: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
            }
        }
        
        $this->info("Audit Complete.");
    }
}
