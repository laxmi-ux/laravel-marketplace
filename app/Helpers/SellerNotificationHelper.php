<?php

namespace App\Helpers;

use App\Models\Seller;
use App\Notifications\MarketplaceNotification;

class SellerNotificationHelper
{
    public static function send(
        int $sellerId,
        string $title,
        string $message,
        string $type = 'system',
        ?string $url = null
    ): void {

        $seller = Seller::find($sellerId);

        if (!$seller) {
            return;
        }

        $user = $seller->user;

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