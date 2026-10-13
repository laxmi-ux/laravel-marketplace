<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications for the logged-in Buyer/Seller.
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = $user
            ->notifications()
            ->latest()
            ->paginate(15);

        $unreadCount = $user
            ->unreadNotifications()
            ->count();

        return view('notifications.index', compact(
            'notifications',
            'unreadCount'
        ));
    }

    /**
     * Mark one notification as read.
     */
    public function read(string $id)
    {
        $user = Auth::user();

        $notification = $user
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        if ($url) {
            return redirect($url);
        }

        return redirect()
            ->route('notifications.index');
    }

    /**
     * Mark all notifications as read.
     */
    public function readAll()
    {
        $user = Auth::user();

        $user->unreadNotifications->markAsRead();

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }

    /**
     * Delete one notification.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        $notification = $user
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return back()->with(
            'success',
            'Notification deleted.'
        );
    }
}