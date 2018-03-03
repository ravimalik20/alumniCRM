<?php

namespace Api;

class CustomerController
{
    protected $app;

    public function __construct(\Slim\Container $container)
	{
        $this->app = $container;
    }
    
    public function search($request, $response, $args)
    {
		
    }
    
    public function create($request, $response, $args)
    {
		$data = $request->getParsedBody();
		
		$first_name = $this->test_input($data['first_name']);
		$last_name = $this->test_input($data['last_name']);
		$type = $this->test_input($data['type']); //
		$preferred_mail_name = $this->test_input($data['preferred_mail_name']);
		$salutation = $this->test_input($data['salutation']);
		$active = $this->test_input($data['active']); //
		$home_street_1 = $this->test_input($data['home_street_1']);
		$home_street_2 = $this->test_input($data['home_street_2']);
		$country = $this->test_input($data['country']);
		$city = $this->test_input($data['city']);
		$state = $this->test_input($data['state']);
		$zipcode = $this->test_input($data['zipcode']); //
		$home_number = $this->test_input($data['home_number']); //
		$phone_number = $this->test_input($data['phone_number']); //
		$email = $this->test_input($data['email']); //
		$gender = $this->test_input($data['gender']); //
		$birthday = $this->test_input($data['birthday']); //
		$school = $this->test_input($data['school']);
		$degree = $this->test_input($data['degree']);
		$major = $this->test_input($data['major']);
		$major_year_start = $this->test_input($data['major_year_start']); //
		$major_year_end = $this->test_input($data['major_year_end']); //
		$version_num_customer = $this->test_input($data['version_num_customer']);
		
		$errors = array();

		if (empty($first_name))
			array_push($errors, "first_name must not be empty");
			
		if (empty($last_name))
			array_push($errors, "last_name must not be empty");

		if (empty($preferred_mail_name))
			array_push($errors, "preferred_mail_name must not be empty");
		
		if (empty($salutation))
			array_push($errors, "salutation must not be empty");
		
		if (empty($home_street_1))
			array_push($errors, "home_street_1 must not be empty");
		
		if (empty($country))
			array_push($errors, "country must not be empty");
		
		if (empty($state))
			array_push($errors, "state must not be empty");
		
		if (empty($city))
			array_push($errors, "city must not be empty");
		
		if (empty($school))
			array_push($errors, "school must not be empty");
		
		if (empty($degree))
			array_push($errors, "degree must not be empty");
		
		if (empty($major))
			array_push($errors, "major must not be empty");

		$valid = false;
		foreach (array('current', 'alumni') as $val) {
			if ($type == $val)
				$valid = true;
		}
		if (!$valid)
			array_push($errors, "Type must be either 'current' or 'alumni'");
			
		$valid = false;
		foreach (array('true', 'false') as $val) {
			if ($active == $val)
				$valid = true;
		}
		if (!$valid)
			array_push($errors, "Active must be either 'true' or 'false'");
		$active = $active == 'true' ? 1 : 0;
			
		if(!preg_match("/^[0-9]{5}$/i", $zipcode))
			array_push($errors, "Zipcode format incorrect.");
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
			array_push($errors, "Email format incorrect.");
		
		if(!empty($home_number) && !preg_match("/^[1-9][0-9]{9}$/", $home_number))
			array_push($errors, "Home number format incorrect.");
			
		if(!preg_match("/^[1-9][0-9]{9}$/", $phone_number))
			array_push($errors, "Phone number format incorrect.");
		
		$valid = false;
		foreach (array('male', 'female', 'other') as $val) {
			if ($gender == $val)
				$valid = true;
		}
		if (!$valid)
			array_push($errors, "Gender must be either 'male', 'female' or 'other'");
		
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $birthday))
			array_push($errors, "Birthday format should be YYYY-MM-DD");
			
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $major_year_start))
			array_push($errors, "major_year_start format should be YYYY-MM-DD");
		
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $major_year_end))
			array_push($errors, "major_year_end format should be YYYY-MM-DD");
		
		if ($major_year_end <= $major_year_start) {
			array_push($errors, "major_year_end should be greater than major_year_start");
		}
		
		if (count($errors) > 0) {
			$result = array(
				"status" => "failure",
				"errors" => $errors
			);
			
			return $response->withJson($result, 400);
		}
		
		$sql = "INSERT INTO customer (first_name, last_name, preferred_mail_name, salutation, home_street_1,".
			" home_street_2, country, city, state, zipcode, home_number, phone_number, email, gender, birthday,".
			" school, degree, major, major_year_start, major_year_end, version_num_customer)".
			" VALUES ('$first_name', '$last_name', '$preferred_mail_name', '$salutation', '$home_street_1',".
			"'$home_street_2', '$country', '$city', '$state', '$zipcode', '$home_number', '$phone_number', '$email', '$gender',".
			"'$birthday', '$school', '$degree', '$major', '$major_year_start', '$major_year_end', '0'".
			")";

		$status = $this->app->db->query($sql);
		if ($status) {
			$result = array(
				"status" => "success"
			);
			
			return $response->withJson($result, 200);
		}
		else {
			$result = array(
				"status" => "failure",
				"errors" => array("Internal server error.")
			);
		
			return $response->withJSON($result, 500);
		}
    }
    
    public function get($request, $response, $args)
    {
		$id = $args['id'];
		
		$sql = "SELECT * FROM customer where id_customer = $id";
		$obj = $this->app->db->query($sql);
		
		if ($obj->num_rows > 0) {
			$result = $obj->fetch_assoc();
			
			return $response->withJson($result, 200);
		}
		else {
			$result = array(
				"status" => "failure",
				"errors" => array("Does not exist.")
			);
			
			return $response->withJson($result, 404);
		}
    }
    
    public function update($request, $response, $args)
    {
		
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
