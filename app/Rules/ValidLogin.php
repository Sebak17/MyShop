<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidLogin implements ImplicitRule
{

    private $msg = "Login jest niepoprawny!";

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
            $this->msg = "Podaj login!";
            return false;
        }

    	if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) {
            return false;
        }

        if (mb_strlen($value) < 4) {
            return false;
        }

        if (mb_strlen($value) > 20) {
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
