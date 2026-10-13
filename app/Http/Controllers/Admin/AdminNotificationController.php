<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    /**
     * Display all admin notifications.
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        abort_unless($admin, 403);

        $notifications = $admin
            ->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $admin
            ->unreadNotifications()
            ->count();

        return view(
            'admin.notifications.index',
            compact('notifications', 'unreadCount')
        );
    }

    /**
     * Mark a single notification as read
     * and redirect to its related page.
     */
    public function read(DatabaseNotification $notification)
    {
        $admin = Auth::guard('admin')->user();

        abort_unless($admin, 403);

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        | Make sure this notification actually belongs
        | to the currently logged-in admin.
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $notification->notifiable_type === get_class($admin)
            && (int) $notification->notifiable_id === (int) $admin->id,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Mark as read
        |--------------------------------------------------------------------------
        */

        $notification->markAsRead();

        /*
        |--------------------------------------------------------------------------
        | Redirect to related page
        |--------------------------------------------------------------------------
        */

        $url = $notification->data['url'] ?? null;

        if ($url) {
            return redirect()->to($url);
        }

        return redirect()->route('admin.notifications.index');
    }

    /**
     * Mark all admin notifications as read.
     */
    public function readAll()
    {
        $admin = Auth::guard('admin')->user();

        abort_unless($admin, 403);

        $admin->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }

    /**
     * Delete a single notification.
     */
    public function destroy(DatabaseNotification $notification)
    {
        $admin = Auth::guard('admin')->user();

        abort_unless($admin, 403);

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $notification->notifiable_type === get_class($admin)
            && (int) $notification->notifiable_id === (int) $admin->id,
            403
        );

        $notification->delete();

        return back()->with(
            'success',
            'Notification deleted.'
        );
    }

    /**
     * Mark a single notification as read
     * without redirecting.
     *
     * Useful for AJAX requests.
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        $admin = Auth::guard('admin')->user();

        abort_unless($admin, 403);

        abort_unless(
            $notification->notifiable_type === get_class($admin)
            && (int) $notification->notifiable_id === (int) $admin->id,
            403
        );

        $notification->markAsRead();

        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }
}
