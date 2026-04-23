<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'destiny_id',
        'sender_id',
        'type',
        'data',
        'url',
        'readed',
        'read_at',
        'column8',
    ];

    // Send Notification
    public static function sendNotification(string $msg, array $users, string $url, string $type)
    {
        $notiUsers = [];
        foreach ($users as $user) {
            self::updateOrCreate(
                [
                    'destiny_id' => intval($user),
                    'url' => $url,
                    'type' => $type,
                ],
                [
                    'sender_id' => Auth()->user()->id,
                    'data' => $msg,
                    'readed' => 0,
                ]
            );
            array_push($notiUsers, intval($user));
        }

        $not['description'] = $msg;
        $not['url'] = $url;
        $not['userId'] = $notiUsers;
        // TODO: port OrderNotificationsEvent from legacy
        // event(new OrderNotificationsEvent($not));
    }
}
