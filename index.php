<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require_once 'vendor/j4mie/idiorm/idiorm.php';

/* DB Configuration */
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$hostname = $config['db']['host']   = 'localhost';
$dbuser = $config['db']['user']   = 'alumnislim';
$dbpass = $config['db']['pass']   = 'alumnislim';
$dbname = $config['db']['dbname'] = 'alumnislim';

ORM::configure("mysql:host=$hostname;dbname=$dbname");
ORM::configure('username', $dbuser);
ORM::configure('password', $dbpass);

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

/* Register logger to container via. dependency injection. */
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['view'] = new \Slim\Views\PhpRenderer('templates/');

$container['DashboardController'] = function($c) {
    $view = $c->get("view"); // retrieve the 'view' from the container
    return new DashboardController($view);
};


$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];

	$this->logger->addInfo('Something interesting happened');

	$response = $this->view->render($response, 'hello.phtml', ['name' => $name]);

    return $response;
});

$app->get('/', function (Request $request, Response $response, array $args) {
	$response = $this->view->render($response, 'home.phtml');
	
	return $response;
});

$app->get('/name', function (Request $request, Response $response, array $args) {
	$person = ORM::for_table('test')->where('name', 'ravi')->find_one();
	
	$name = $person->name;
	
	$response = $this->view->render($response, 'hello.phtml', ["name" => $name]);
});

$app->run();

?>
