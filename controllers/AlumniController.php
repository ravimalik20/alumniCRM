<?php

class AlumniController
{
    protected $app;

    public function __construct(\Slim\Container $container) {
        $this->app = $container;
    }
    
    public function home($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));
      
		$customers = $this->app->db->query("SELECT * from customer where version_num_customer = 0");
      
		$response = $this->app->view->render($response, 'alumni.phtml', ['customers' => $customers]);

		return $response;
    }
    
    public function get($request, $response, $args)
    {
		if (! Helper::is_login() && empty($_SESSION['update_token']))
			return $response->withRedirect(Helper::url("/auth/login"));
        
        $id = $args['id'];
        
        $customer = $this->app->db->query("select c.*, n.user_id as no_contact from customer as c left join no_contact_list as n on n.customer_id = c.id_customer where id_customer=$id");
        if ($customer->num_rows <= 0)
			return "404 Not found";
		
		$customer = $customer->fetch_assoc();

		$customer_latest = $this->app->db->query("select * from customer where email = '".$customer['email']."' order by version_num_customer desc");
		$customer_latest = $customer_latest->fetch_assoc();

		$notes = $this->app->db->query("select notes.*, users.name as admin_name from notes join users on users.id_admin_user = notes.user_id where customer_id=$id order by created_at desc");
        
        $work= $this->app->db->query("select * from customer_work where customer_id = $id order by date_end desc");
        
		$admin_user_id = Helper::user_id($this->app);
  
		$response = $this->app->view->render($response, 'alumni-single.phtml', ['customer' => $customer, 'customer_latest' => $customer_latest, 'notes' => $notes, 'works' => $work, 'admin_user_id' => $admin_user_id]);

		return $response;
    }
    
    public function create($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));
    
		$response = $this->app->view->render($response, 'alumni-create.phtml');

		return $response;
    }
    
    public function import($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));
    
		$response = $this->app->view->render($response, 'alumni-import.phtml');

		return $response;
    }
    
    public function update($request, $response, $args)
    {
		$token = $args['token'];
		
		$customer = $this->app->db->query("select * from customer where token = '$token'");
		if ($customer->num_rows > 0) {
			$customer = $customer->fetch_assoc();
		
			$_SESSION["update_token"] = $token;
			
			return $response->withRedirect(Helper::url("/alumni/".$customer['id_customer']));
		}
		
		return $response->withRedirect(Helper::url("/auth/login"));
    }
}

?>
