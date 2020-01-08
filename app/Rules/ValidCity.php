<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidCity implements ImplicitRule
{

    private $msg = "Nazwa miasta jest niepoprawna!";

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
        if($value == '') {
            return false;
        }
        
        if (mb_strlen($value) < 2 || mb_strlen($value) > 32) {
            return false;
        }

        if (!preg_match("/^[a-zA-Z żźćńółęąśŻŹĆĄŚĘŁÓŃ]+$/", $value)) {
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
