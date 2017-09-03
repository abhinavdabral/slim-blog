<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller {

    public function getSignOut($request, $response){
        $this->auth->logout();
        $this->flash->addMessage('info', 'You\'ve been logged out sucessfully.');                                        
        return $response->withRedirect($this->router->pathFor('home'));                    
    }

    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

    public function postSignIn($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email'     => v::noWhitespace()->notEmpty()->email(),
            'password'  => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', 'Validation failed while submitting the form. Please try again.');            
            return $response->withRedirect($this->router->pathFor('auth.signin'));                    
        }

        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if(!$auth){
            $this->flash->addMessage('error', 'Invalid login details. Please check your email and/or password and try again.');                        
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        $this->flash->addMessage('info', 'You\'ve been logged in.');                                
        return $response->withRedirect($this->router->pathFor('home'));
        
    }

    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response)
    {

        $validation = $this->validator->validate($request, [
            'email'     => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name'      => v::notEmpty()->alpha(),
            'password'  => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', 'Validation failed while submitting the form. Please try again.');            
            return $response->withRedirect($this->router->pathFor('auth.signup'));                    
        }

        $user = User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
        ]);

        $this->auth->attempt($user->email, $request->getParam('password'));

        $this->flash->addMessage('info', 'You\'ve signed up successfully and have been logged in.');                               
        return $response->withRedirect($this->router->pathFor('home'));        
    }
}