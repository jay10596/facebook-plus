<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Notification as NotificationResource;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        return [
            'all' => NotificationResource::collection(auth()->user()->notifications),
            'read' => NotificationResource::collection(auth()->user()->readNotifications),
            'unread' => NotificationResource::collection(auth()->user()->unreadNotifications)
        ];
    }

    public function markAsRead(Request $request)
    {
        auth()->user()->notifications->where('id', $request->id)->markAsRead();
    }

    public function hideFriendButtons(Request $request)
    {
        $notification = auth()->user()->notifications->where('id', $request->id)->first();
        $data = $notification->data;
        $notification->update(array('data' => array_merge($data, ['content' => $request->content, 'message' => $request->message])));
    }
}
