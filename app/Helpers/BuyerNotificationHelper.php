<?php

namespace App\Helpers;

use App\Notifications\MarketplaceNotification;

class BuyerNotificationHelper
{
    public static function send(
        int $buyerId,
        string $title,
        string $message,
        string $type = 'system',
        ?string $url = null
    ): void {

        $user = \App\Models\User::find($buyerId);

        if (!$user) {
            return;
        }

        $user->notify(
            new MarketplaceNotification(
                $title,
                $message,
                $type,
                $url
            )
        );
    }
}