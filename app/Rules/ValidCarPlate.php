<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidCarPlate implements ImplicitRule
{

    private $msg = "Tablica rejestracyjna jest niepoprawna!";

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
            $this->msg = "Podaj tablicę rejestracyjną!";
            return false;
        }

        if (strlen($value) < 5) {
            return false;
        }

        if (strlen($value) > 12) {
            return false;
        }

        if (!preg_match("/^[A-Z]{2,3} [A-Z0-9]{3,5}+$/", $value)) {
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
