<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidLockerName implements ImplicitRule
{

    private $msg = "Nazwa paczkomatu jest nieprawidłowa!";

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
            $this->msg = "Podaj nazwę paczkomatu!";
            return false;
        }

        if (mb_strlen($value) > 14) {
            return false;
        }

        if (mb_strlen($value) < 3) {
            return false;
        }

        if (!preg_match("/^[a-zA-Z0-9-]+$/", $value)) {
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
