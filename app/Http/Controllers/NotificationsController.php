<?php

namespace App\Http\Controllers;

use App\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Notification;

class NotificationsController extends Controller
{
    public function index()
    {
        $userLog = Auth()->user()->id;
        $postNotifications = Notifications::where('destiny_id', $userLog)->where('readed', 0)->whereNull('read_at')->orderBy('id','asc')->get();
        return view('admin.notifications.notifications', compact('postNotifications'));
    }

    public function getNotificationsData() {
        $userLog = Auth()->user()->id;
        $notifications = Notifications::where('destiny_id', $userLog)->where('readed', 0)->whereNull('read_at')->take(5)->get();
        return json_decode($notifications);
    }

    public function getNotificationsDataAll() {
        $userLog = Auth()->user()->id;
        $notifications = Notifications::where('destiny_id', $userLog)->where('readed', 0)->whereNull('read_at')->orderBy('id','asc')->count();
        return json_decode($notifications);
    }

    public function update(Request $request)
    {
        // dd($request);
       $userLog = Auth()->user()->id;
        Notifications::where('id', $request->id)->where('destiny_id', $userLog)->update(['readed' => $request->readed]);
        return redirect()->route('notifications.index')->with('info', 'Notificación Marcada como leída');
    }

    public function markAsRead(Request $request)
    {
        // dd($request);
        $userLog = Auth()->user()->id;
        Notifications::where('destiny_id', $userLog)->update(['readed' => $request->readed]);
        return redirect()->route('notifications.index')->with('info', 'Notificación Marcada como leída');
    }

    public function markNotification(Request $request)
    {
        auth()->user()->unreadNotifications
                ->when($request->input('id'), function($query) use ($request){
                    return $query->where('id', $request->input('id'));
                })->markAsRead();
        return response()->noContent();
    }

    public function notificationView(Request $request)
    {
        // dd($request);
        // $userLog = Auth()->user()->id;
        $notify = Notifications::where('id', $request->id)
        ->where('destiny_id', $request->destiny_id)
        ->update(['readed' => $request->readed]);
        return redirect($request->url);
    }
}
