<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidProductName implements Rule
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

        if (mb_strlen($value) > 40) {
            return false;
        }

        if (!preg_match("/^[a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ0-9- ]+$/", $value)) {
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
        return 'Nazwa pruduktu jest niepoprawna! (4-40)';
    }
}
