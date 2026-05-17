<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bidan;
use App\Models\Dokter;
use App\Models\Pengguna;

class ForceUpdateAllOnlineStatus extends Command
{
    protected $signature = 'users:force-update-online-status';
    protected $description = 'Force update semua user online status berdasarkan last_seen';

    public function handle(): int
    {
        $threshold = now()->subMinutes(5);
        $now = now();

        // Update Bidan - set is_online berdasarkan last_seen
        Bidan::chunkById(100, function ($bidans) use ($threshold, $now) {
            foreach ($bidans as $bidan) {
                $shouldBeOnline = $bidan->last_seen && $bidan->last_seen->gte($threshold);
                if ($bidan->is_online != $shouldBeOnline) {
                    $bidan->update(['is_online' => $shouldBeOnline]);
                }
            }
        });

        // Update Dokter
        Dokter::chunkById(100, function ($dokters) use ($threshold, $now) {
            foreach ($dokters as $dokter) {
                $shouldBeOnline = $dokter->last_seen && $dokter->last_seen->gte($threshold);
                if ($dokter->is_online != $shouldBeOnline) {
                    $dokter->update(['is_online' => $shouldBeOnline]);
                }
            }
        });

        // Update Pengguna
        Pengguna::chunkById(100, function ($penggunas) use ($threshold, $now) {
            foreach ($penggunas as $pengguna) {
                $shouldBeOnline = $pengguna->last_seen && $pengguna->last_seen->gte($threshold);
                if ($pengguna->is_online != $shouldBeOnline) {
                    $pengguna->update(['is_online' => $shouldBeOnline]);
                }
            }
        });

        $this->info('All online statuses force updated successfully.');
        return Command::SUCCESS;
    }
}