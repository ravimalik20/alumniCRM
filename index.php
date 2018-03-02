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

$app->group('/api/v1/', function () {
	$this->post('login', function (Request $request, Response $response, array $args) {
		$data = $request->getParsedBody();
		
		$email = filter_var($data['email'], FILTER_SANITIZE_STRING);
		$password = filter_var($data['password'], FILTER_SANITIZE_STRING);
		
		$valid = false;
		$obj = $this->db->query("select * from users where email = '$email'");
		
		if ($obj->num_rows > 0) {
			$obj = $obj->fetch_assoc();
			
			if (password_verify($password, $obj['password'])) {
				$valid = true;
			}
		}
		
		if ($valid == true) {
			$token = bin2hex(random_bytes(30));
			
			$stmt = $this->db
				->query("UPDATE users set login_token='$token', last_login=CURRENT_TIMESTAMP() where id_admin_user=".$obj["id_admin_user"]);
			
			$result = array(
				"result" => "success",
				"token" => $token
			);
			
			return $response->withJson($result, 200);
		}
		
		$result = array(
			"result" => "failure"
		);
		
		return $response->withJson($result, 400);
	});
});

$app->get('/pwd/{str}', function (Request $request, Response $response, array $args) {
	$str = $args['str'];
	
	$pwd_str = password_hash($str, PASSWORD_DEFAULT);
	
	echo($pwd_str);
});

$app->run();

?>
