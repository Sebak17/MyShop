<?php

namespace App\Helpers;

use App\UserHistory;

class UserHelper
{

    public static function addToHistory($user, $type, $msg)
    {

        if ($user == null || $type == null || $msg == null) {
            return;
        }

        UserHistory::create([
            'user_id' => $user->id,
            'type'    => $type,
            'data'    => $msg,
            'ip'      => $_SERVER['REMOTE_ADDR'],
        ]);

    }

}
