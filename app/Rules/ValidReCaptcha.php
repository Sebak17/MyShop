<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidReCaptcha implements Rule
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
        $recToCheck = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . config('recaptcha.private_key') . '&response=' . $value);
        $recAnswer  = json_decode($recToCheck);

        return $recAnswer->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nie poprawny kod ReCaptcha!';
    }
}
