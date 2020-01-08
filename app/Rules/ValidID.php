<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidID implements ImplicitRule
{

    private $msg = "Bład identyfikatora!";

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
        
        if (!is_numeric($value)) {
            return false;
        }

        if (!preg_match("/^[0-9]+$/", $value)) {
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
