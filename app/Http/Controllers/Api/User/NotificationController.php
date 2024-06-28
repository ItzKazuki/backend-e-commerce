<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

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
        //
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
