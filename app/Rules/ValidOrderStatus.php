<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidOrderStatus implements Rule
{
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
        if(!in_array($value, ['CREATED','UNPAID','PROCESSING','PAID','REALIZE','SENT','RECEIVE']))
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
        return 'Status jest niepoprawny!';
    }
}