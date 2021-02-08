<?php

namespace App\Rules\ClientContact;

use Illuminate\Contracts\Validation\Rule;

use App\Models\ClientContact;

class ValidKeyValueContactRule implements Rule
{
    private $data = [];
    private $contact = null;
    private $message = 'Invalid contact value.';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($data, $contact = null) {
        $this->data = $data;
        $this->contact = $contact;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {   

        $data = $this->data;

        if($data['key'] == 'email') {
            $email = filter_var($data['value'], FILTER_SANITIZE_EMAIL);

            if(filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
                return false;
            }
        }

        if(is_null($this->contact)) {
            $exists = ClientContact::where([
                "client_id" => $data['client_id'],
                "key" => $data['key'],
                "value" => $data['value']
            ])->first();
    
            if(!is_null($exists)) {
                $this->message = "Contact is already existing for this client.";
                return false;
            }      
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
        return $this->message;
    }
}
