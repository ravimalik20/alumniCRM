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
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));
        
        $id = $args['id'];
        
        $customer = $this->app->db->query("select * from customer where id_customer=$id");
        if ($customer->num_rows <= 0)
			return "404 Not found";
		
		$customer = $customer->fetch_assoc();

		$notes = $this->app->db->query("select * from notes where customer_id=$id order by created_at desc");
        
        $work= $this->app->db->query("select * from customer_work where customer_id = $id order by date_end desc");
          
		$response = $this->app->view->render($response, 'alumni-single.phtml', ['customer' => $customer, 'notes' => $notes, 'works' => $work]);

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
}

?>
