<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MarketplaceNotification extends Notification
{
    use Queueable;

    public string $title;
    public string $message;
    public string $type;
    public ?string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        string $title,
        string $message,
        string $type = 'system',
        ?string $url = null
    ) {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->url = $url;
    }

    /**
     * Notification delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Store notification in database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'type'    => $this->type,
            'url'     => $this->url,
        ];
    }
}