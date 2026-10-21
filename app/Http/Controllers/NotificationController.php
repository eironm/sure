<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Notifications index.
     *
     * Serves two consumers from one route:
     *
     *  - The bell dropdown polls this with X-Requested-With / Accept: json
     *    and needs a flat, capped list.
     *  - A browser visit needs the full, paginated page. This previously
     *    returned JSON unconditionally, so navigating to /notifications
     *    rendered raw JSON in the browser and the notifications view was
     *    never reachable.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'notifications' => $user->notifications()->latest()->limit(20)->get(),
                'unread_count'  => $user->unreadNotifications()->count(),
            ]);
        }

        return view('notifications.index', [
            // paginate() keeps $notifications->links() in the view working.
            'notifications' => $user->notifications()->latest()->paginate(15),
            'unreadCount'   => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark all notifications as read.
     *
     * The bell dropdown calls this over fetch and wants JSON; the
     * notifications page posts a real form and needs a redirect back,
     * otherwise the user lands on a bare JSON document.
     */
    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'All notifications marked as read']);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark a specific notification as read.
     */
    /**
     * Compatibility handler for stale dashboard links that used the POST
     * endpoint as their GET destination. Mark the owned notification as read,
     * then continue to its stored reservation destination.
     */
    public function follow(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        $destination = data_get($notification->data, 'action_url');
        if (! is_string($destination) || ! str_starts_with($destination, '/') || str_starts_with($destination, '//')) {
            $destination = route('guest.dashboard');
        }

        return redirect()->to($destination);
    }
    public function markAsRead(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Notification marked as read']);
        }

        return back()->with('success', 'Notification marked as read.');
    }
}

