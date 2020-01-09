<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidEMail implements ImplicitRule
{

    private $msg = "E-Mail jest niepoprawny!";

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
            $this->msg = "Podaj email!";
            return false;
        }
        
        if (strlen($value) < 5) {
            return false;
        }

        if (strlen($value) > 32) {
            return false;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
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
