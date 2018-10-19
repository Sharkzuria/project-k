<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
/*header('Access-Control-Allow-Origin', 'http://localhost:8080');
header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization');
header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');*/

session_start();

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/Commons/Functions.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();


// Add the routes
$router->add('', ['controller' => 'User', 'action' => 'userDash']);
$router->add('/user/login', ['controller' => 'User', 'action' => 'login']);
$router->add('/user/register', ['controller' => 'User', 'action' => 'login']);
$router->add('logout', ['controller' => 'User', 'action' => 'logout']);
$router->add('user/dashboard', ['controller' => 'User', 'action' => 'userDash']);
$router->add('seed', ['controller' => 'User', 'action' => 'seed']);
$router->add('user/expense-and-income', ['controller' => 'User', 'action' => 'getUserFinance']);

// Audit routes
$router->add('user/confirmation', ['controller' => 'Audit', 'action' => 'confirmUser']);
$router->add('lease/pay', ['controller' => 'Audit', 'action' => 'leasePayments']);

$router->add('{controller}/{action}');
    // echo $_SERVER['QUERY_STRING'];
$router->dispatch($_SERVER['QUERY_STRING']);
