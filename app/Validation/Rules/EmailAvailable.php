<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\User;

class EmailAvailable extends AbstractRule {
    protected $prevEmail;
    
    public function __construct($previousEmail=NULL){
        if(isset($previousEmail))
            return $this->prevEmail = $previousEmail;
    }
    public function validate($input){
        if(isset($this->prevEmail))
            if(!strcmp($input, $this->prevEmail))  
                return true;
                
        return User::where('email', $input)->count()===0;
    }
}