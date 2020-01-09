<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidOrderStatus implements ImplicitRule
{

    private $msg = "Status jest niepoprawny!";

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
            $this->msg = "Podaj status zamówienia!";
            return false;
        }

        if(!in_array($value, ['CREATED','UNPAID','PROCESSING','PAID','REALIZE','SENT','RECEIVE','CANCELED']))
            return false;
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
