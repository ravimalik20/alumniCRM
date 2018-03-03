<?php

namespace Api;

class AuthController
{
    protected $app;

    public function __construct(\Slim\Container $container)
	{
        $this->app = $container;
    }
    
    public function login($request, $response, $args)
    {
		$data = $request->getParsedBody();
		
		$email = filter_var($data['email'], FILTER_SANITIZE_STRING);
		$password = filter_var($data['password'], FILTER_SANITIZE_STRING);
		
		$valid = false;
		$obj = $this->app->db->query("select * from users where email = '$email'");
		
		if ($obj->num_rows > 0) {
			$obj = $obj->fetch_assoc();
			
			if (password_verify($password, $obj['password'])) {
				$valid = true;
			}
		}
		
		if ($valid == true) {
			$token = bin2hex(random_bytes(30));
			
			$stmt = $this->app->db
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
    }
    
    public function register($request, $response, $args)
    {
		$data = $request->getParsedBody();
		
		$name = filter_var($data['name'], FILTER_SANITIZE_STRING);
		$email = filter_var($data['email'], FILTER_SANITIZE_STRING);
		$password = filter_var($data['password'], FILTER_SANITIZE_STRING);
		$password_re = filter_var($data['password_re'], FILTER_SANITIZE_STRING);
		
		$errors = array();
		
		$exists = $this->app->db->query("select * from users where email = '$email'");
		if ($exists->num_rows > 0)
			array_push($errors, "Email already exists.");
		
		if (strcmp($password, $password_re) != 0) {
			array_push($errors, "Password and repeat password do not match.");
		}
		
		if (count($errors) > 0) {
			$result = array(
				"status" => "failure",
				"errors" => $errors
			);
			
			return $response->withJson($result, 400);
		}
		
		$password = password_hash($password, PASSWORD_DEFAULT);
		
		$this->app->db->query("INSERT INTO users(name, email, password, active) values('$name', '$email', '$password', 1)");
		$user_obj = $this->app->db->query("SELECT email from users where email = '$email'");
		
		if ($user_obj->num_rows <= 0) {
			$result = array(
				"status" => "failure",
				"errors" => array("Internal server error.")
			);
		
			return $response->withJSON($result, 500);
		}
		
		$result = array(
			"status" => "success"
		);
		
		return $response->withJson($result, 200);
    }
}

?>
