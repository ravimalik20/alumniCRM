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

/* Dependencies */

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

$app->group('/auth', function () {
	$this->get('/login', AuthController::class . ':login');
	$this->get('/register', AuthController::class . ':register');
});

$app->group('/alumni', function () {
	$this->get('', AlumniController::class . ':home');
	$this->get('/create', AlumniController::class . ':create');
	$this->get('/{id}', AlumniController::class . ':get');
});

$app->group('/api/v1/', function () {
	$this->post('login', \Api\AuthController::class . ':login');
	$this->post('register', \Api\AuthController::class . ':register');
	
	$this->group('customer', function () {
		$this->get('', \Api\CustomerController::class . ':search');
		$this->post('', \Api\CustomerController::class . ':create');
		$this->get('/{id}', \Api\CustomerController::class . ':get');
		$this->post('/{id}', \Api\CustomerController::class . ':update');
	});
});


/* Launch */

$app->run();

?>
