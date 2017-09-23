<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Role;
use Respect\Validation\Validator as v;

class UserController extends Controller{
    public function index($request, $response){

        // Dashboard
        $users = User::with('roles')->orderBy('id')->get();

        $this->view->getEnvironment()->addGlobal('users', $users);    
        return $this->view->render($response, 'user\index.twig');      
    }

    public function show($request, $response, $args){
        $user = User::find($args['user']);
        // $roles = $user->roles;
        // foreach($roles as $role)
        //     foreach($role->permissions as $p)
        //         print_r($p->name);
        // print_r($user->getPermissions());
        // dd();
        if($user==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }
        
        $this->view->getEnvironment()->addGlobal('user', $user);
        return $this->view->render($response, 'user\show.twig');      
    }

    public function new($request, $response){
        $roles = Role::orderBy('name')->get();
        $this->view->getEnvironment()->addGlobal('roles', $roles); 
        return $this->view->render($response, 'user\new.twig');        
    }
    public function create($request, $response){

        $validation = $this->validator->validate($request, [
            'email'     => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name'      => v::notEmpty()->alpha(),
            'password'  => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', 'Validation failed while submitting the form. Please try again.');            
            return $response->withRedirect($this->router->pathFor('user.new'));                    
        }

        $roles = [];
        
        foreach($request->getParams() as $key=>$value){
            if(strcmp(substr($key, 0, 1), 'r')===0){
                array_push($roles, str_replace('r', '', $key));
            }
        }

        if(sizeof($roles)<=0){
            $this->flash->addMessage('error', "Users must have at least one role assigned to them.");            
            return $response->withRedirect($this->router->pathFor('user.new'));                    
        }

        $user = User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
        ]);

        $user->setRoles($roles);
        

        $this->flash->addMessage('info', 'User saved successfully.');            
        return $response->withRedirect($this->router->pathFor('user'));  

    }

    public function edit($request, $response, $args){
        $user = User::with('roles')->find($args['user']);
        
        if($user==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $roles = Role::orderBy('name')->get();
        $this->view->getEnvironment()->addGlobal('roles', $roles);
        $this->view->getEnvironment()->addGlobal('user', $user);
        return $this->view->render($response, 'user\edit.twig');
    }
    public function update($request, $response){
        $user = User::find($request->getParam('id'));
        
        if($user==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $validation = $this->validator->validate($request, [
            'email'     => v::noWhitespace()->notEmpty()->email()->emailAvailable($user->email),
            'name'      => v::notEmpty()->alpha(),
            'password'  => v::noWhitespace(),
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', '');            
            return $response->withRedirect($this->router->pathFor('user.edit', ['user'=>$request->getParam('id')]));                    
        }

        $roles = [];
        
        foreach($request->getParams() as $key=>$value){
            if(strcmp(substr($key, 0, 1), 'r')===0){
                array_push($roles, str_replace('r', '', $key));
            }
        }

        if(sizeof($roles)<=0){
            $this->flash->addMessage('error', "Users must have at least one role assigned to them.");            
            return $response->withRedirect($this->router->pathFor('user.edit', ['user'=>$request->getParam('id')]));           
        }

        $newPassword = $user->password;
        if($request->getParam('password'))
            $newPassword = password_hash($request->getParam('password'), PASSWORD_DEFAULT);

        User::where('id', $request->getParam('id'))
            ->update([
                'email' => $request->getParam('email'),
                'name' => $request->getParam('name'),
                'password' =>  $newPassword
        ]);

        $user = User::find($request->getParam('id'));
        $user->setRoles($roles);       
        
        $this->flash->addMessage('info', 'User updated.');                    
        return $response->withRedirect($this->router->pathFor('user'));
    }

    public function remove($request, $response, $args){
        $user = User::find($args['user']);
        
        if($user==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $this->view->getEnvironment()->addGlobal('user', $user);
        return $this->view->render($response, 'user\remove.twig');
    }
    public function delete($request, $response){
        $user = User::find($request->getParam('id'));
        $user->detachAndDelete();      
        $this->flash->addMessage('info', 'User deleted.');            
        return $response->withRedirect($this->router->pathFor('user'));  
    }
        
}