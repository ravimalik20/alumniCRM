<?php

namespace Api;

class CustomerWorkController
{
    protected $app;

    public function __construct(\Slim\Container $container)
	{
        $this->app = $container;
    }
    
    public function create($request, $response, $args)
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
    
		$data = $request->getParsedBody();
		
		$title = $this->test_input($data['title']);
		$address_line1 = $this->test_input($data['address_line1']);
		$address_line2 = $this->test_input($data['address_line2']);
		$city = $this->test_input($data['city']);
		$state = $this->test_input($data['state']);
		$country = $this->test_input($data['country']);
		$zipcode = $this->test_input($data['zipcode']);
		$field = $this->test_input($data['field']);
		$date_start = $this->test_input($data['date_start']);
		$date_end = $this->test_input($data['date_end']);
		
		$errors = array();
		
		if (empty($title))
			array_push($errors, "title must not be empty");
		
		if (empty($address_line1))
			array_push($errors, "address_line1 must not be empty");
			
		if (empty($address_line2))
			array_push($errors, "address_line2 must not be empty");
		
		if (empty($country))
			array_push($errors, "country must not be empty");
		
		if (empty($state))
			array_push($errors, "state must not be empty");
		
		if (empty($city))
			array_push($errors, "city must not be empty");
			
		if (empty($city))
			array_push($errors, "field must not be empty");
		
		if(!preg_match("/^[0-9]{5}$/i", $zipcode))
			array_push($errors, "Zipcode format incorrect.");
			
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date_start))
			array_push($errors, "Birthday format should be YYYY-MM-DD");
			
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date_end))
			array_push($errors, "Birthday format should be YYYY-MM-DD");
			
		if (count($errors) > 0) {
			$result = array(
				"status" => "failure",
				"errors" => $errors
			);
			
			return $response->withJson($result, 200);
		}
		
		$sql = "INSERT INTO customer_work (title, address_line1, address_line2, city, state, country, zipcode, field, customer_id, date_start, date_end)".
			" VALUES ('$title', '$address_line1', '$address_line2', '$city', '$state', '$country', '$zipcode', '$field', $id, '$date_start', '$date_end')";

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
				"errors" => array("Internal server error.")
			);
		
			return $response->withJSON($result, 200);
		}
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
