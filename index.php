<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

/* DB Configuration */
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

include('config.php');

$hostname = $config['db']['host']   = $db_host;
$dbuser = $config['db']['user']   = $db_user;
$dbpass = $config['db']['pass']   = $db_pass;
$dbname = $config['db']['dbname'] = $db_name;

// Create connection


$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

/* Register logger to container via. dependency injection. */
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['db'] = function($c) use($db_host, $db_pass, $db_user, $db_name) {
	$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    
    return $conn;
};

$container['view'] = new \Slim\Views\PhpRenderer('templates/');

/* Routes */

$app->get('/', function (Request $request, Response $response, array $args) {
	$response = $this->view->render($response, 'home.phtml');
	
	return $response;
});

$app->get('/dashboard', DashboardController::class . ':home');

$app->group('/api/v1/', function () {
	$this->post('login', \Api\AuthController::class . ':login');
});

$app->get('/pwd/{str}', function (Request $request, Response $response, array $args) {
	$str = $args['str'];
	
	$pwd_str = password_hash($str, PASSWORD_DEFAULT);
	
	echo($pwd_str);
});

$app->run();

?>
