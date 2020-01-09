<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidCategoryName implements ImplicitRule
{

    private $msg = "Nazwa kategorii jest niepoprawna!";

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
            $this->msg = "Podaj nazwę kategorii!";
            return false;
        }
        
        if (!preg_match('/^[a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+$/', $value)) {
            return false;
        }

        if (strlen($value) <= 0 || strlen($value) > 20) {
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
