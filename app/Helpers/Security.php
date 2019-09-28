<?php

namespace App\Helpers;

class Security
{

	public static function generatePassword($lenght = 10)
    {
        $alph     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $alphLen  = strlen($alph) - 1;

        for ($i = 0; $i < $lenght; $i++) {
            $random     = rand(0, $alphLen);
            $password[] = $alph[$random];
        }

        return implode($password);
    }

    public static function generateChecksum(...$ar)
    {
        $r = "";

        foreach ($ar as $value) {
            $r .= $value;
        }

        return hash("sha256", $r);
    }


}
