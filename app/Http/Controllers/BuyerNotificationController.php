<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class BuyerNotificationController extends Controller
{
    /**
     * Display buyer notifications.
     */
    public function index()
    {
        $buyer = Auth::user();

        $notifications = $buyer->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $buyer->unreadNotifications()->count();

        return view(
            'buyer.notifications.index',
            compact('notifications', 'unreadCount')
        );
    }

    /**
     * Mark a notification as read and open its destination.
     */

public function read(DatabaseNotification $notification)
{
    $buyer = Auth::user();

    // Ensure the notification belongs to the logged-in user.
    abort_unless(
        $notification->notifiable_type === get_class($buyer)
        && (int) $notification->notifiable_id === (int) $buyer->id,
        403
    );

    $notification->markAsRead();

    $url = $notification->data['url'] ?? null;

    if (is_string($url) && $url !== '') {
        $path = parse_url($url, PHP_URL_PATH);

        // Open report details directly.
        if (
            is_string($path)
            && preg_match('~/buyer/reports/(\d+)/?$~', $path, $matches)
        ) {
            return redirect()->route('buyer.reports.show', [
                'report' => (int) $matches[1],
            ]);
        }

        // Redirect to a local application path.
        if (
            str_starts_with($url, '/')
            && !str_starts_with($url, '//')
        ) {
            return redirect()->to($url);
        }

        // Allow absolute URLs only for the same application host.
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $destinationHost = parse_url($url, PHP_URL_HOST);
            $applicationHost = parse_url(config('app.url'), PHP_URL_HOST);

            if (
                $destinationHost
                && $applicationHost
                && strcasecmp($destinationHost, $applicationHost) === 0
            ) {
                return redirect()->to($url);
            }
        }
    }

    return redirect()
        ->route('buyer.notifications.index')
        ->with('success', 'Notification marked as read.');
}

    /**
     * Mark all notifications as read.
     */
    public function readAll()
    {
        Auth::user()->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return redirect()
            ->route('buyer.notifications.index')
            ->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification belonging to this buyer.
     */
    public function destroy(DatabaseNotification $notification)
    {
        $buyer = Auth::user();

        abort_unless(
            $notification->notifiable_type === get_class($buyer)
            && (int) $notification->notifiable_id === (int) $buyer->id,
            403
        );

        $notification->delete();

        return redirect()
            ->route('buyer.notifications.index')
            ->with('success', 'Notification deleted.');
    }
}