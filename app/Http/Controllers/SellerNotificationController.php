<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class SellerNotificationController extends Controller
{
    /**
     * Display seller notifications.
     */
    public function index()
    {
        $seller = Auth::user();

        $notifications = $seller->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $seller->unreadNotifications()->count();

        return view('seller.notifications.index', compact(
            'notifications',
            'unreadCount'
        ));
    }

    /**
     * Mark one notification as read.
     */
    public function read(DatabaseNotification $notification)
    {
        $seller = Auth::user();

        // Make sure this notification belongs to the logged-in seller.
        abort_unless(
            $notification->notifiable_type === get_class($seller)
            && (int) $notification->notifiable_id === (int) $seller->id,
            403
        );

        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        if ($url) {
            return redirect($url);
        }

        return redirect()->route('seller.notifications.index');
    }

    /**
     * Mark all notifications as read.
     */
    public function readAll()
    {
        $seller = Auth::user();

        $seller->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }

    /**
     * Delete one notification.
     */
    public function destroy(DatabaseNotification $notification)
    {
        $seller = Auth::user();

        // Security check.
        abort_unless(
            $notification->notifiable_type === get_class($seller)
            && (int) $notification->notifiable_id === (int) $seller->id,
            403
        );

        $notification->delete();

        return back()->with(
            'success',
            'Notification deleted.'
        );
    }
}