<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Post;
use Respect\Validation\Validator as v;

class PostController extends Controller{
    public function index($request, $response){

        // Dashboard
        $posts = Post::orderBy('created_at', 'desc')->get();

        $this->view->getEnvironment()->addGlobal('posts', $posts);    
        return $this->view->render($response, 'post\index.twig');      
    }

    public function show($request, $response, $args){
        $post = Post::find($args['post']);
        
        if($post==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }
        
        $this->view->getEnvironment()->addGlobal('post', $post);
        return $this->view->render($response, 'post\show.twig');      
    }

    public function new($request, $response){
        return $this->view->render($response, 'post\new.twig');        
    }
    public function create($request, $response){

        $validation = $this->validator->validate($request, [
            'title'     => v::notEmpty(),
            'slug'      => v::noWhitespace()->notEmpty()->slug()->permalinkAvailable(),
            'content'   => v::notEmpty()
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', $validation->getMessages());            
            return $response->withRedirect($this->router->pathFor('post.new'));                    
        }

        $post = Post::create([
            'title'         => $request->getParam('title'),
            'slug'          => mb_strtolower($request->getParam('slug')),
            'content'       => filter_var(strip_tags(htmlentities($request->getParam('content'))), FILTER_SANITIZE_STRING),
            'category_id'   => 1,
            'published'     => $request->getParam('published')?1:0,
            'user_id'       => $this->auth->user()->id,
        ]);
        
        $this->flash->addMessage('info', 'Post published.');            
        return $response->withRedirect($this->router->pathFor('post'));  

    }

    public function edit($request, $response, $args){
        $post = Post::find($args['post']);
        
        if($post==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $this->view->getEnvironment()->addGlobal('post', $post);
        return $this->view->render($response, 'post\edit.twig');
    }
    public function update($request, $response){
        $post = Post::find($request->getParam('id'));
        
        if($post==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $validation = $this->validator->validate($request, [
            'title'     => v::notEmpty(),
            'slug'      => v::noWhitespace()->notEmpty()->slug()->permalinkAvailable($post->slug),
            'content'   => v::notEmpty()
        ]);

        if($validation->failed()){
            // $this->flash->addMessage('error', '');            
            return $response->withRedirect($this->router->pathFor('post.edit', ['post'=>$request->getParam('id')]));                    
        }

        Post::where('id', $request->getParam('id'))
            ->update([
            'title'         => $request->getParam('title'),
            'slug'          => mb_strtolower($request->getParam('slug')),
            'published'     => $request->getParam('published')?1:0,            
            'content'       => filter_var(strip_tags(htmlentities($request->getParam('content'))), FILTER_SANITIZE_STRING)
        ]);

        $post = Post::find($request->getParam('id'));
        
        $this->flash->addMessage('info', 'Post updated.');                    
        return $response->withRedirect($this->router->pathFor('post.edit', ['post'=>$request->getParam('id')]));
    }

    public function remove($request, $response, $args){
        $post = Post::find($args['post'])->with('user');
        
        if($post==NULL){
            $notFoundHandler = $this->container->get('notFoundHandler');
            return $notFoundHandler($request, $response);
        }

        $this->view->getEnvironment()->addGlobal('post', $post);
        return $this->view->render($response, 'post\remove.twig');
    }
    public function delete($request, $response){
        Post::destroy($request->getParam('id'));        
        $this->flash->addMessage('info', 'Post deleted.');            
        return $response->withRedirect($this->router->pathFor('post'));  
    }
        
}