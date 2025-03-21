<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('admin.notification.index', compact('notifications'));
    }


    /**
     * Show the form for creating a new resource.
     */

    public function markAsRead(Request $request, Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }


    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
                            ->where('is_read', false)
                            ->count();

        return response()->json(['count' => $count]);
    }

    public function markAsReadBulk(Request $request)
    {
        $notificationIds = $request->input('notifications');

        if ($notificationIds) {
            Notification::whereIn('id', $notificationIds)->update(['is_read' => true]);
        }

        return redirect()->route('notification.index');
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
