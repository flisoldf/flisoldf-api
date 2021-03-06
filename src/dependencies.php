<?php
// DIC configuration

//use Controllers\TalkPostController;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Configurações da integração Slim+Swagger-PHP e do Swagger-PHP
// $app é a variavel que contem a instancia da aplicação
$container[\JunioDeAlmeida\Slim\SlimSwaggerRouteJav::class] = function ($c) use ($app) {
    return new \JunioDeAlmeida\Slim\SlimSwaggerRouteJav($app);
};
// Seteando as rotas /docs/view e /docs/json
$container[\JunioDeAlmeida\Slim\SlimSwaggerRouteJav::class]->setRouters();

$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container[Controllers\TalksPostController::class] = function ($c)  {
    return new Controllers\TalksPostController($c);
};

$container[Controllers\CollaboratorsPostController::class] = function ($c)  {
    return new Controllers\CollaboratorsPostController($c);
};