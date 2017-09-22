<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Role;
use Respect\Validation\Validator as v;

class RoleController extends Controller{
    public function index($request, $response){

        // Dashboard
        $roles = Role::orderBy('name')->get();

        $this->view->getEnvironment()->addGlobal('roles', $roles);    
        return $this->view->render($response, 'role\index.twig');      
    }

    public function new($request, $response){
                   
        return $this->view->render($response, 'role\new.twig');        
    }
    public function create($request, $response){

        $validation = $this->validator->validate($request, [
            'name'      => v::notEmpty()->alnum('-'),
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', $validation->getMessages());            
            return $response->withRedirect($this->router->pathFor('role.new'));                    
        }

        $role = Role::create([
            'name'          => $request->getParam('name')
        ]);
        
        $this->flash->addMessage('info', 'Role added.');            
        return $response->withRedirect($this->router->pathFor('role'));  

    }

    public function edit($request, $response, $args){
        $role = Role::find($args['role']);
        
        if($role==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $this->view->getEnvironment()->addGlobal('role', $role);
        return $this->view->render($response, 'role\edit.twig');
    }
    public function update($request, $response){
        $role = Role::find($request->getParam('id'));
        
        if($role==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $validation = $this->validator->validate($request, [
            'name'      => v::notEmpty()->alnum('-'),
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', '');            
            return $response->withRedirect($this->router->pathFor('role.edit', ['role'=>$request->getParam('id')]));                    
        }

        Role::where('id', $request->getParam('id'))
                    ->update([
                        'name' => $request->getParam('name')
                    ]);

        $role = Role::find($request->getParam('id'));
        
        $this->flash->addMessage('info', 'Role updated.');                    
        return $response->withRedirect($this->router->pathFor('role'));
    }

    public function remove($request, $response, $args){
        $role = Role::find($args['role']);
        
        if($role==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $this->view->getEnvironment()->addGlobal('role', $role);
        return $this->view->render($response, 'role\remove.twig');
    }
    public function delete($request, $response){
        Role::destroy($request->getParam('id'));        
        $this->flash->addMessage('info', 'Role deleted.');            
        return $response->withRedirect($this->router->pathFor('role'));  
    }
        
}