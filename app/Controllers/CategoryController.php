<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Category;
use Respect\Validation\Validator as v;

class CategoryController extends Controller{
    public function index($request, $response){

        // Dashboard
        $categories = Category::orderBy('name')->get();

        $this->view->getEnvironment()->addGlobal('categories', $categories);    
        return $this->view->render($response, 'category\index.twig');      
    }

    public function new($request, $response){
                   
        return $this->view->render($response, 'category\new.twig');        
    }
    public function create($request, $response){

        $validation = $this->validator->validate($request, [
            'name'      => v::notEmpty()->alnum('-')->categoryAvailable(),
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', $validation->getMessages());            
            return $response->withRedirect($this->router->pathFor('category.new'));                    
        }

        $category = Category::create([
            'name'          => $request->getParam('name')
        ]);
        
        $this->flash->addMessage('info', 'Category added.');            
        return $response->withRedirect($this->router->pathFor('category'));  

    }

    public function edit($request, $response, $args){
        $category = Category::find($args['category']);
        
        if($category==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $this->view->getEnvironment()->addGlobal('category', $category);
        return $this->view->render($response, 'category\edit.twig');
    }
    public function update($request, $response){
        $category = Category::find($request->getParam('id'));
        
        if($category==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $validation = $this->validator->validate($request, [
            'name'      => v::notEmpty()->alnum('-')->categoryAvailable(),
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', '');            
            return $response->withRedirect($this->router->pathFor('category.edit', ['category'=>$request->getParam('id')]));                    
        }

        Category::where('id', $request->getParam('id'))
                    ->update([
                        'name' => $request->getParam('name')
                    ]);

        $category = Category::find($request->getParam('id'));
        
        $this->flash->addMessage('info', 'Category updated.');                    
        return $response->withRedirect($this->router->pathFor('category'));
    }

    public function remove($request, $response, $args){
        $category = Category::find($args['category']);
        
        if($category==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $this->view->getEnvironment()->addGlobal('category', $category);
        return $this->view->render($response, 'category\remove.twig');
    }
    public function delete($request, $response){
        Category::destroy($request->getParam('id'));        
        $this->flash->addMessage('info', 'Category deleted.');            
        return $response->withRedirect($this->router->pathFor('category'));  
    }
        
}