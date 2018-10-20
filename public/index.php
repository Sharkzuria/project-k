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
$router->add('', ['controller' => 'Home', 'action' => 'userDash']);
$router->add('user/login', ['controller' => 'Home', 'action' => 'login']);
$router->add('user/register', ['controller' => 'Home', 'action' => 'register']);
$router->add('logout', ['controller' => 'Home', 'action' => 'logout']);
$router->add('user/dashboard', ['controller' => 'User', 'action' => 'userDash']);
$router->add('seed', ['controller' => 'User', 'action' => 'seed']);
$router->add('user/expense-and-income', ['controller' => 'User', 'action' => 'getUserFinance']);
$router->add('user/payments', ['controller' => 'User', 'action' => 'userPayments']);

//user
$router->add('/user/add-bank-account', ['controller' => 'User', 'action' => 'addBankAcc']);

//ADMIN
$router->add('admin/login', ['controller' => 'Admin', 'action' => 'login']);
$router->add('admin/dashboard', ['controller' => 'Admin', 'action' => 'adminDash']);
$router->add('admin/view-payments', ['controller' => 'Admin', 'action' => 'allPayments']);
$router->add('admin/view-users', ['controller' => 'Admin', 'action' => 'allUsers']);


// Audit routes
$router->add('user/confirmation', ['controller' => 'Audit', 'action' => 'confirmUser']);
$router->add('lease/pay', ['controller' => 'Audit', 'action' => 'leasePayments']);

$router->add('{controller}/{action}');
    // echo $_SERVER['QUERY_STRING'];
$router->dispatch($_SERVER['QUERY_STRING']);
