<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidPhoneNumber implements ImplicitRule
{

    private $msg = "Numer telefonu jest niepoprawny!";

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
            $this->msg = "Podaj numer telefonu!";
            return false;
        }

        if (!is_numeric($value)) {
            return false;
        }

        if (strlen($value) != 9) {
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
