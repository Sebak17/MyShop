<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidProductDescription implements Rule
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
        if (mb_strlen($value) < 4) {
            return false;
        }

        if (mb_strlen($value) > 400) {
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
        return 'Opis pruduktu jest niepoprawny! (4-400)';
    }
}