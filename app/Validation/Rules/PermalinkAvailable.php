<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Post;

class PermalinkAvailable extends AbstractRule {

    protected $prevSlug;

    public function __construct($previousSlug=NULL){
        if(isset($previousSlug))
            return $this->prevSlug = $previousSlug;
    }
    
    public function validate($input){   
        if(isset($this->prevSlug))
            if(!strcmp($input, $this->prevSlug))  
                return true;          
        
        return Post::where('slug', $input)->count()===0;
    }
}