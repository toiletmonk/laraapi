<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class PurgeExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-expired-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = PersonalAccessToken::where('expires_at', '<', Carbon::now())->delete();

        $this->info("Purged {$count} expired tokens.");

        return 0;
    }
}
