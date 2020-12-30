<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class DoesntExistRule implements Rule
{
    private $table;
    private $column;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
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
        return DB::table($this->table)
             ->where($this->column, $value)
             ->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute already exists';
    }
}
