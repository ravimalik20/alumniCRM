<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

/* DB Configuration */
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'alumnislim';
$config['db']['pass']   = 'alumnislim';
$config['db']['dbname'] = 'alumnislim';


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

$app->run();

?>
