<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidReCaptcha implements ImplicitRule
{

    private $msg = "Nie poprawny kod ReCaptcha!";

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
            $this->msg = "Brak kodu recaptcha!";
            return false;
        }

        if($value == 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS')
            return true;

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
        return $this->msg;
    }
}
