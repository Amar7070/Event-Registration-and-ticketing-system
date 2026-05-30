<?php

namespace App\Console\Commands;

use App\Services\WaitlistService;
use Illuminate\Console\Command;

class ExpireReservationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waitlist:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire waitlist entries that exceeded the booking window and notify the next in line.';

    /**
     * Execute the console command.
     */
    public function handle(WaitlistService $waitlistService)
    {
        $this->info('Processing expired waitlist entries...');
        
        $count = $waitlistService->processExpiredEntries();
        
        $this->info("Successfully processed {$count} expired entries.");
    }
}
