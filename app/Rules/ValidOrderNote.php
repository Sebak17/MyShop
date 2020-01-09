<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidOrderNote implements ImplicitRule
{

    private $msg = "Komentarz jest nieprawidłowy! (4-1000 znaków)";

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
            $this->msg = "Podaj komentarz!";
            return false;
        }

        if (mb_strlen($value) < 4) {
            return false;
        }

        if (mb_strlen($value) > 1000) {
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
