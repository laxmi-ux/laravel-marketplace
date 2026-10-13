<?php

namespace App\Helpers;

use App\Models\Admin;
use App\Notifications\MarketplaceNotification;

class AdminNotificationHelper
{
    /**
     * Send a notification to all admins.
     */
    public static function send(
        string $title,
        string $message,
        string $type = 'system',
        ?string $url = null
    ): void {
        $admins = Admin::all();

        foreach ($admins as $admin) {
            $admin->notify(
                new MarketplaceNotification(
                    $title,
                    $message,
                    $type,
                    $url
                )
            );
        }
    }
}