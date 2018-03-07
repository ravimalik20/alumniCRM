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
		
		$email = $this->test_input($data['email']);
		$password = $this->test_input($data['password']);
		
		$valid = false;
		$obj = $this->app->db->query("select * from users where email = '$email'");
		
		if ($obj->num_rows > 0) {
			$obj = $obj->fetch_assoc();
			
			if (password_verify($password, $obj['password'])) {
				$valid = true;
			}
		}
		
		if ($valid == true) {
			$token = $this->generateRandomString(64);
			
			$stmt = $this->app->db
				->query("UPDATE users set login_token='$token', last_login=CURRENT_TIMESTAMP() where id_admin_user=".$obj["id_admin_user"]);
			
			$_SESSION["auth_token"] = $token;
			
			$result = array(
				"result" => "success",
				"token" => $token
			);
			
			return $response->withJson($result, 200);
		}
		
		$result = array(
			"result" => "failure"
		);
		
		return $response->withJson($result, 200);
    }

	public function logout($request, $response, $args)
	{
		session_unset();
		
		session_destroy();
		
		$result = array(
			"result" => "success"
		);
		
		return $response->withJson($result, 200);
	}
    
    public function register($request, $response, $args)
    {
		$data = $request->getParsedBody();
		
		$name = $this->test_input($data['name']);
		$email = $this->test_input($data['email']);
		$password = $this->test_input($data['password']);
		$password_re = $this->test_input($data['password_re']);
		
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
    
    private function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  
	  return $data;
	}
	
	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

?>
