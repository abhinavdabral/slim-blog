<?php
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

use App\Models\Post;
use App\Models\User;

$app->group('', function() {
    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');

    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');
})->add(new GuestMiddleware($container));

$app->group('', function() {

    $this->get('/', 'HomeController:index')->setName('home');

    $this->group('/auth', function() {
        $this->get('/signout', 'AuthController:getSignOut')->setName('auth.signout');
        $this->get('/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
        $this->post('/password/change', 'PasswordController:postChangePassword');
    });

    $this->group('/post', function() {
        $this->get('/', 'PostController:index')->setName('post');  

        $this->get('/show/{post}', 'PostController:show')->setName('post.show');

        $this->get('/new', 'PostController:new')->setName('post.new');
        $this->post('/new', 'PostController:create');

        $this->get('/edit/{post}', 'PostController:edit')->setName('post.edit');
        $this->post('/edit/{post}', 'PostController:update');

        $this->get('/remove/{post}', 'PostController:remove')->setName('post.remove');
        $this->post('/remove', 'PostController:delete')->setName('post.delete');

    });

    $this->group('/category', function() {
        $this->get('/', 'CategoryController:index')->setName('category');  

        $this->get('/show/{category}', 'CategoryController:show')->setName('category.show');

        $this->get('/new', 'CategoryController:new')->setName('category.new');
        $this->post('/new', 'CategoryController:create');

        $this->get('/edit/{category}', 'CategoryController:edit')->setName('category.edit');
        $this->post('/edit/{category}', 'CategoryController:update');

        $this->get('/remove/{category}', 'CategoryController:remove')->setName('category.remove');
        $this->post('/remove', 'CategoryController:delete')->setName('category.delete');

    });

    

    $this->group('/user', function() {
        $this->get('/', 'UserController:index')->setName('user');  
        
        $this->get('/show/{user}', 'UserController:show')->setName('user.show');

        $this->get('/new', 'UserController:new')->setName('user.new');
        $this->post('/new', 'UserController:create');

        $this->get('/edit/{user}', 'UserController:edit')->setName('user.edit');
        $this->post('/edit/{user}', 'UserController:update');

        $this->get('/remove/{user}', 'UserController:remove')->setName('user.remove');
        $this->post('/remove', 'UserController:delete')->setName('user.delete');
    });        

    $this->group('/role', function() {
    });

})->add(new AuthMiddleware($container));

$app->get('/dump/{post}', function($req, $resp, $args){
    
    $post = Post::find($args['post']);
    if($post){
        $page = $this->ToHTML->fromPost($post);
        $pathToFile = $this->ToHTML->savePage($post->slug, $page);
        return $resp->withRedirect($pathToFile);
    }
    return "Not found";
});

$app->get('/test_user/{user}', function($req, $resp, $args){
    $posts = User::find($args['user'])->posts->all();
    foreach($posts as $post)
        print_r($post->title);
});

$app->get('/test_post/{post}', function($req, $resp, $args){
    $user = Post::find($args['post'])->user;
    // foreach($posts as $post)
    print_r($user->name);
});
    

