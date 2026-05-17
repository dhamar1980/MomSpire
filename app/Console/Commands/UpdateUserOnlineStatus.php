<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bidan;
use App\Models\Dokter;
use App\Models\Pengguna;

class UpdateUserOnlineStatus extends Command
{
    protected $signature = 'users:update-online-status';
    protected $description = 'Set is_online = false untuk user yang inactive lebih dari 5 menit';

    public function handle(): int
    {
        $threshold = now()->subMinutes(5);

        // Update Bidan
        Bidan::where('is_online', true)
            ->where('last_seen', '<', $threshold)
            ->update(['is_online' => false]);

        // Update Dokter
        Dokter::where('is_online', true)
            ->where('last_seen', '<', $threshold)
            ->update(['is_online' => false]);

        // Update Pengguna
        Pengguna::where('is_online', true)
            ->where('last_seen', '<', $threshold)
            ->update(['is_online' => false]);

        $this->info('Online status updated successfully.');
        return Command::SUCCESS;
    }
}