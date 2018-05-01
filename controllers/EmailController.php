<?php

class EmailController
{
    protected $app;

    public function __construct(\Slim\Container $container)
    {
		$this->app = $container;
    }
    
    public function _list($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));

		$emails = $this->app->db->query("select id_email, c.email as email, subject, e.created_at as created_at from email as e, customer as c where e.customer_id = c.id_customer");
      
		$response = $this->app->view->render($response, 'email.phtml', ['emails' => $emails]);
      
		return $response;
    }
    
    public function send($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));
      
		$data = $request->getParsedBody();
		
		$recepients = explode(",", $this->test_input($data['to']));

		foreach ($recepients as $to) {
			$subject = $this->test_input($data['subject']);
			$message = $this->test_input($data['message']);
		  
			$customer = $this->app->db->query("select * from customer where email='$to'");
			if ($customer->num_rows > 0) {
				$customer = $customer->fetch_assoc();
				$customer_id = $customer['id_customer'];
		
				$user_id = Helper::user_id($this->app);

				mail($to, $subject, $message);
		
				$this->app->db->query("INSERT INTO email (customer_id, user_id, subject, content) VALUES ('$customer_id', $user_id, '$subject', '$message')");
			}
		}

		return $response->withRedirect(Helper::url("/email?success=true"));
    }
    
    public function compose($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));
      
		$response = $this->app->view->render($response, 'email-compose.phtml');
      
		return $response;
    }
    
    public function view($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));

		$id = $args['id'];

		$email = $this->app->db->query("select c.email as email, subject, content from email as e, customer as c where e.customer_id = c.id_customer and e.id_email=$id");
		if ($email->num_rows <= 0) {
			return $response->withRedirect(Helper::url("/email"));
		}
 
		$email = $email->fetch_assoc();
     
		$response = $this->app->view->render($response, 'email-view.phtml', ['email' => $email]);
      
		return $response;
    }
    
    private function test_input($data)
    {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		$data = addslashes($data);

		return $data;
	}
}

?>
