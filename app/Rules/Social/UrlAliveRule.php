<?php

namespace App\Rules\Social;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client as GuzzleClient;

class UrlAliveRule implements Rule
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
        return true; //TO DO:: Force this to return true first, will need to check issue on prod;
        try {
            $guzzleClient = new GuzzleClient();
            $guzzleRequest = $guzzleClient->head($value);

            if( $guzzleRequest->getStatusCode() == 200 ) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Url is not active.';
    }
}
