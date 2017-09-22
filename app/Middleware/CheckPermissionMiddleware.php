<?php

namespace App\Middleware;

class CheckPermissionMiddleware extends Middleware {

    protected $permission;

    public function __construct($container, $permission){
        parent::__construct($container);
        $this->permission = $permission;
    }

    public function __invoke($request, $response, $next){
        // print_r($this->container->auth->user()->name);
        if(!$this->container->auth->user()->hasPermission($this->permission)){
            $this->container->flash->addMessage('error', 'Access denied! You don\'t have permission to access that page');     
            return $response->withRedirect($this->container->router->pathFor('home'));  
        }
        $response = $next($request, $response);
        return $response;
    }
}