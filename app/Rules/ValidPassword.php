<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidPassword implements ImplicitRule
{

    private $msg = "Hasło ma błędną długość! (4-30)";

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
            $this->msg = "Podaj hasło!";
            return false;
        }

        if (mb_strlen($value) < 4) {
            return false;
        }

        if (mb_strlen($value) > 30) {
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
