<?php

namespace Api;

class NoContactController
{
    protected $app;

    public function __construct(\Slim\Container $container)
	{
        $this->app = $container;
    }
    
    public function add($request, $response, $args)
    {
		$id = $args['id'];
		
		$sql = "SELECT * FROM customer where id_customer = $id";
		$obj = $this->app->db->query($sql);
		
		if ($obj->num_rows <= 0) {
			$result = array(
				"status" => "failure",
				"errors" => array("Customer does not exist.")
			);
			
			return $response->withJson($result, 200);
		}
		
		$admin_id = \Helper::user_id($this->app);

		$sql = "INSERT INTO no_contact_list (customer_id, user_id) VALUES ($id, $admin_id)";
		$status = $this->app->db->query($sql);
		
		if ($status) {
			$result = array(
				"status" => "success",
				"redirect_url" => \Helper::url('/alumni/'.$id)
			);
			
			return $response->withJson($result, 200);
		}
		else {
			$result = array(
				"status" => "failure",
				"errors" => array("Already exists in no contact list.")
			);
		
			return $response->withJSON($result, 200);
		}
    }
    
    public function remove($request, $response, $args)
    {
		$id = $args['id'];
		
		$sql = "SELECT * FROM customer where id_customer = $id";
		$obj = $this->app->db->query($sql);
		
		if ($obj->num_rows <= 0) {
			$result = array(
				"status" => "failure",
				"errors" => array("Customer does not exist.")
			);
			
			return $response->withJson($result, 200);
		}
		
		$sql = "DELETE FROM no_contact_list WHERE customer_id = $id";
		$status = $this->app->db->query($sql);
		
		if ($status) {
			$result = array(
				"status" => "success",
				"redirect_url" => \Helper::url('/alumni/'.$id)
			);
			
			return $response->withJson($result, 200);
		}
		else {
			$result = array(
				"status" => "failure",
				"errors" => array("Does not exist in no contact list.")
			);
		
			return $response->withJSON($result, 200);
		}
    }
}

?>
