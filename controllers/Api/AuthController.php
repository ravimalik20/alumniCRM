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
}

?>
