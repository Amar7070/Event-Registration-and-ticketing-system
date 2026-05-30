<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Waitlist;

class ExpireWaitlist extends Command
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
    protected $description = 'Expire waitlist entries that were not claimed in time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Expiring stale waitlist entries...');

        $expired = Waitlist::where('status', 'notified')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update([
                'status' => 'expired'
            ]);

        $this->info("Expired {$expired} waitlist entries.");
    }
}
