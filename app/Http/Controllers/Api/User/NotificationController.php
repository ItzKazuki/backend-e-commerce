<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Notifications\UserRegistered;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications;
        $unreadCount = $request->user()->unreadNotifications->count();

        $notifications = $notifications->map(fn ($notification) => [
            'id' => $notification->id,
            'data' => $notification->data,
            'readAt' => $notification->readAt, // in frontend check this, is null so label to unred message
            'created_at' => $notification->created_at,
            'updated_at' => $notification->updated_at,
        ]);

        // show all notifications
        return $this->sendRes([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // send basic notifications

        $user = $request->user();

        $user->notify(new UserRegistered($user));

        return $this->sendRes([
            'message' => 'Notification sent successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // find notification by id

        $notification = DatabaseNotification::findOrFail($id);
        $notification->markAsRead();

        return $this->sendRes([
            'notification' => [
                'id' => $notification->id,
                'data' => $notification->data,
                'readAt' => $notification->readAt,
                'created_at' => $notification->created_at,
                'updated_at' => $notification->updated_at,
            ],
        ]);

    }
}
