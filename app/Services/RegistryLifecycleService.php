<?php

namespace App\Services;

use App\Models\Registry;
use Carbon\Carbon;

class RegistryLifecycleService
{
    public function expirePendingRegistries(): int
    {
        $expiredCount = 0;

        Registry::query()
            ->with('arazi')
            ->where('status', 'pending')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', Carbon::today())
            ->each(function (Registry $registry) use (&$expiredCount) {
                $registry->forceFill([
                    'status' => 'cancelled',
                    'payment_status' => 'expired',
                    'lock_status' => 'unlock',
                ])->save();

                if ($registry->arazi) {
                    $registry->arazi->forceFill(['status' => 'available'])->save();
                }

                $expiredCount++;
            });

        return $expiredCount;
    }

    public function markRegistryPending(Registry $registry): void
    {
        $registry->forceFill([
            'status' => 'pending',
            'payment_status' => 'pending',
            'due_date' => $registry->due_date ?? Carbon::now()->addDays(15),
            'lock_status' => 'lock',
        ])->save();

        if ($registry->arazi) {
            $registry->arazi->forceFill(['status' => 'sold'])->save();
        }
    }

    public function markRegistryPaid(Registry $registry): void
    {
        $registry->forceFill([
            'status' => 'completed',
            'payment_status' => 'completed',
            'due_date' => $registry->due_date ?? Carbon::now()->addDays(15),
            'lock_status' => 'lock',
        ])->save();

        if ($registry->arazi) {
            $registry->arazi->forceFill(['status' => 'sold'])->save();
        }
    }
}
