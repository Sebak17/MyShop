<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidAddress implements ImplicitRule
{

    private $msg = "Adres jest niepoprawny!";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(config('site.debug.rules'))
            $this->msg .= " |" . $value . "|";
        
        if($value == '') {
            $this->msg = "Podaj adres!";
            return false;
        }

        if (mb_strlen($value) < 4 || mb_strlen($value) > 40) {
            return false;
        }

        if (!preg_match("/^([a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ0-9\-,. \/]+)$/", $value)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msg;
    }
}
