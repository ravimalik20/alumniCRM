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
				"errors" => array("Customer does not exist.")
			);
			
			return $response->withJson($result, 200);
		}
		
		$notes = $this->app->db->query("select * from notes where customer_id = $id order by created_at desc");
		
		$result = $notes->fetch_all(MYSQLI_ASSOC);
		
		return $response->withJson($result, 200);
    }
    
    public function create($request, $response, $args)
    {
		$id = $args['id'];
    
		$customer = $this->fetch_customer($id);
		if ($customer == null) {
			$result = array(
				"status" => "failure",
				"errors" => array("Customer does not exist.")
			);
			
			return $response->withJson($result, 200);
		}
		
		$data = $request->getParsedBody();
		
		$customer_id = $id;
		$user_id = $this->test_input($data['user_id']);
		$note = $this->test_input($data['note']);
		
		$errors = array();

		if (empty($note))
			array_push($errors, "note must not be empty");
			
		$user = $this->app->db->query("select * from users where id_admin_user = $user_id");
		if ($user->num_rows <= 0)
			array_push($errors, "User does not exist");
			
		if (count($errors) > 0) {
			$result = array(
				"status" => "failure",
				"errors" => $errors
			);
			
			return $response->withJson($result, 200);
		}
		
		$sql = "INSERT INTO notes (customer_id, user_id, note) VALUES ('$customer_id', '$user_id', '$note')";
		$status = $this->app->db->query($sql);
		if ($status) {
			$ins_id = $this->app->db->insert_id;

			$note = $this->app->db->query("select * from notes where customer_id = $customer_id order by id_note desc");
			$note = $note->fetch_assoc();

			$created_by = \Helper::user($this->app)['name'];

			$result = array(
				"status" => "success",
				"data" => $note,
				"created_by" => $created_by,
				"delete_url" => \Helper::url("/api/v1/customer/$customer_id/note/$ins_id/delete"),
				"redirect_url" => \Helper::url('/alumni/'.$id)
			);
			
			return $response->withJson($result, 200);
		}
		else {
			$result = array(
				"status" => "failure",
				"errors" => array("Internal server error.")
			);
		
			return $response->withJSON($result, 200);
		}
    }
    
    public function get($request, $response, $args)
    {
		
    }

	public function delete($request, $response, $args)
    {
		$id = $args['id'];
    
		$customer = $this->fetch_customer($id);
		if ($customer == null) {
			$result = array(
				"status" => "failure",
				"errors" => array("Customer does not exist.")
			);
			
			return $response->withJson($result, 200);
		}

		$id_note = $args['id_note'];

		$status = $this->app->db->query("delete from notes where id_note = $id_note;");

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
				"errors" => array("Internal server error.")
			);
		
			return $response->withJSON($result, 200);
		} 
    }
    
    private function fetch_customer($id)
    {
		$customer = $this->app->db->query("SELECT * from customer WHERE id_customer=$id");
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
		$data = addslashes($data);

		return $data;
	}
}

?>
