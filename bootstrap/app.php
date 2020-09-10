<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Config
 */
$configFile = __DIR__ . '/../config/sample.json';
$config = json_decode(file_get_contents($configFile), TRUE);

/**
 * @var App
 */
$app = new Slim\App([
    'settings' => $config['slim'],
]);

/**
 * @var \Slim\Container
 */
$container = $app->getContainer();

/**
 * @var \Illuminate\Database\Capsule\Manager
 */
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($config['database']);
$capsule->setAsGlobal();
$capsule->bootEloquent();


/**
 * @param \Slim\Container $container
 *
 * @return \Illuminate\Database\Capsule\Manager
 */
$container['db'] = function ($container) use ($capsule) {
  return $capsule;
};


/**
 * ############ Config ############
 */
$container['config'] = function () use ($config) {
    return $config;
};

/**
 * ############ Controllers ############
 */

/**
 * @param $container
 *
 * @return \App\Controllers\IndexController
 */
$container['IndexController'] = function ($container) {
  return new \App\Controllers\IndexController($container);
};

/**
 * @param $container
 *
 * @return \App\Controllers\StatisticsController
 */
$container['StatisticsController'] = function ($container) {
    return new \App\Controllers\StatisticsController($container);
};

/**
 * ############ Main Middleware ############
 */
$app->add(new \App\Middleware\RemoveTrailingSlashMiddleware());

/**
 * ############ Routes ############
 */
require_once __DIR__ . '/../app/routes.php';
