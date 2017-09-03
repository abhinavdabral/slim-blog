<?php

// Starting Session
session_start();

// Importing Classes
use Respect\Validation\Validator as v;

// Including Autoloader
require __DIR__.'/../vendor/autoload.php';

// Initializing Dotenv to load data from .env file in root
$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

// Initializing Slim Application with configuration
$app = new \Slim\App(require 'config.php');

// Getting handle of Container of Slim Application
$container = $app->getContainer();

// Booting Eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();


// Singleton
$container['db'] = function($container) use ($capsule){
    return $capsule;
};

$container['auth']=function($container){
    return new \App\Auth\Auth;
};

$container['flash'] = function($container){
    return new Slim\Flash\Messages;
};

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__.'/../resources/views', [
        'cache' => false,
        'debug' => true
    ]);

    // helps with base_url() method, to resolve an absolute path
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    // Authentication global variables to be available in Twig Views
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user'  => $container->auth->user()
    ]);

    $view->getEnvironment()->addGlobal('baseURL', $container->request->getUri()->getBaseUrl());
    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};

$container['validator'] = function($container){
    return new \App\Validation\Validator;
};

$container['HomeController'] = function($container){
    return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container){
    return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container){
    return new \App\Controllers\Auth\PasswordController($container);
};

$container['PostController'] = function($container){
    return new \App\Controllers\PostController($container);
};

$container['ToHTML'] = function($container){
    return new \App\ToHTML\ToHTML($container);
};

$container['csrf'] = function($container){
    return new \Slim\Csrf\Guard;
};

$container['baseURL']=function($container){
    return $container->request->getUri()->getBaseUrl();
};

// Middlewares
$app->add(new \App\Middleware\ValidationErrorMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

// Including Custom Rules to Validation instance
v::with('App\\Validation\\Rules\\');

// Including Routes
require __DIR__.'/../app/routes.php';

// Booting the application
$app->run();