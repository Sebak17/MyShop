<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class ValidProductParams implements ImplicitRule
{

    private $msg = "Parametry nie są poprawne!";

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

        $value = json_decode($value, true);
        if(json_last_error() != JSON_ERROR_NONE)
            return false;

        foreach ($value as $param) {
            if(!isset($param['name']) || !preg_match('/^[a-zA-Z0-9 -żźćńółęąśŻŹĆĄŚĘŁÓŃ]+$/', $param['name']) || mb_strlen($param['name']) < 2 || mb_strlen($param['name']) > 32)
                return false;
            if(!isset($param['value']) || !preg_match('/^[a-zA-Z0-9 -żźćńółęąśŻŹĆĄŚĘŁÓŃ]+$/', $param['value']) || mb_strlen($param['value']) < 2 || mb_strlen($param['value']) > 32)
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
