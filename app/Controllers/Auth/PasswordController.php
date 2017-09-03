<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;

class PasswordController extends Controller {

    public function getChangePassword($request, $response)
    {
        return $this->view->render($response, 'auth/password/change.twig');
    }

    public function postChangePassword($request, $response)
    {
        if (strcmp($request->getParam('password'), $request->getParam('password_confirmation'))!==0)
        {
            $this->flash->addMessage('error', 'Confirmation password does not matches new password.');     
            return $response->withRedirect($this->router->pathFor('auth.password.change'));            
        }

        $validation = $this->validator->validate($request, [
            'password_old'          => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
            'password'              => v::noWhitespace()->notEmpty(),
            'password_confirmation' => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', 'Validation failed while submitting the form. Please try again.');            
            return $response->withRedirect($this->router->pathFor('auth.password.change'));
        }

        $this->auth->user()->setPassword($request->getParam('password'));

        $this->flash->addMessage('info', 'Password successfully changed');                                
        return $response->withRedirect($this->router->pathFor('home'));
        
    }
}