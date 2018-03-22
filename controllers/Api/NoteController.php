<?php

namespace Api;

class NoteController
{
    protected $app;

    public function __construct(\Slim\Container $container)
	{
        $this->app = $container;
    }
    
    public function _list($request, $response, $args)
    {
		$id = $args['id'];
    
		$customer = $this->fetch_customer($id);
		if ($customer == null) {
			$result = array(
				"status" => "failure",
				"errors" => array("Does not exist.")
			);
			
			return $response->withJson($result, 200);
		}
		
		$notes = $this->app->db->query("select * from notes where customer_id = $id order by created_at desc");
		
		$result = $notes->fetch_all(MYSQLI_ASSOC);
		
		return $response->withJson($result, 200);
    }
    
    public function create($request, $response, $args)
    {
		
    }
    
    public function get($request, $response, $args)
    {
		
    }
    
    public function update($request, $response, $args)
    {
		
    }
    
    private function fetch_customer($id)
    {
		$customer = $this->app->db->query("select * from customer where id_customer=$id");
		if ($customer->num_rows > 0)
			return $customer->fetch_assoc();
		else
			return null;
    }
    
    private function test_input($data)
    {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);

		return $data;
	}
}

?>
