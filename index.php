<?php

session_start();

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

$container['app_hostname'] = function($c) use ($app_hostname) {
	return $app_hostname;
};

/* Routes */

$app->get('/', function (Request $request, Response $response, array $args) {
	return $response->withRedirect("/dashboard");
});

$app->get('/dashboard', DashboardController::class . ':home');

$app->group('/auth', function () {
	$this->get('/login', AuthController::class . ':login');
	$this->get('/register', AuthController::class . ':register');
});

$app->group('/alumni', function () {
	$this->get('', AlumniController::class . ':home');
	$this->get('/create', AlumniController::class . ':create');
	$this->get('/import', AlumniController::class . ':import');
	$this->get('/update/{token}', AlumniController::class . ':update');
	$this->get('/{id}', AlumniController::class . ':get');
});

$app->group('/email', function () {
	$this->get('', EmailController::class . ':_list');
	$this->post('', EmailController::class . ':send');
	$this->get('/compose', EmailController::class . ':compose');
	$this->get('/token/{id}', EmailController::class . ':email_token');
	$this->get('/{id}', EmailController::class . ':view');
});

$app->group('/api/v1/', function () {
	$this->post('login', \Api\AuthController::class . ':login');
	$this->get('logout', \Api\AuthController::class . ':logout');
	$this->post('register', \Api\AuthController::class . ':register');
	
	$this->group('customer', function () {
		$this->get('', \Api\CustomerController::class . ':search');
		$this->post('', \Api\CustomerController::class . ':create');
		$this->get('/export', \Api\CustomerController::class . ':export');
		$this->post('/import', \Api\CustomerController::class . ':import');
		$this->get('/{id}', \Api\CustomerController::class . ':get');
		$this->post('/{id}', \Api\CustomerController::class . ':update');
		$this->post('/{id}/token', \Api\CustomerController::class . ':token');
	});
	
	$this->group('customer/{id}/work', function () {
		$this->post('', \Api\CustomerWorkController::class . ':create');
		$this->get('/{id_work}/delete', \Api\CustomerWorkController::class . ':delete');
	});
	
	$this->group('customer/{id}/note', function () {
		$this->get('', \Api\NoteController::class . ':_list');
		$this->post('', \Api\NoteController::class . ':create');
		
		$this->get('/{id_note}', \Api\NoteController::class . ':get');
		$this->get('/{id_note}/delete', \Api\NoteController::class . ':delete');
	});
	
	$this->group('customer/{id}/nocontact', function () {
		$this->get('/add', \Api\NoContactController::class . ':add');
		$this->get('/remove', \Api\NoContactController::class . ':remove');
	});
});


/* Launch */

$app->run();

?>
