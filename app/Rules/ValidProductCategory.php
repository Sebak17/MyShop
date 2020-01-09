<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidProductCategory implements ImplicitRule
{

    private $msg = "Kategoria jest niepoprawna!";

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
            $this->msg = "Podaj kategorie!";
            return false;
        }

        if (!is_numeric($value)) {
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
