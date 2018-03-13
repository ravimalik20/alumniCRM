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
		
		return $this->insert_update($response, $data, null);
    }
    
    public function get($request, $response, $args)
    {
		$id = $args['id'];
		
		$sql = "SELECT * FROM customer where id_customer = $id";
		$obj = $this->app->db->query($sql); 
		
		if ($obj->num_rows > 0) {
			$obj = $obj->fetch_assoc();
			$email = $obj["email"];
		
			$sql = "SELECT * FROM customer where email = '$email' order by version_num_customer desc limit 1";
			$obj = $this->app->db->query($sql);
		
			$result = $obj->fetch_assoc();
			
			return $response->withJson($result, 200);
		}
		else {
			$result = array(
				"status" => "failure",
				"errors" => array("Does not exist.")
			);
			
			return $response->withJson($result, 200);
		}
    }
    
    public function update($request, $response, $args)
    {
		$id = $args['id'];
		$data = $request->getParsedBody();
		
		return $this->insert_update($response, $data, $id);
    }

	private function insert_update($response, $data, $id)
	{
		if ($id != null) {
			$prev_obj = $this->app->db->query("select * from customer where id_customer=$id");
			
			if ($prev_obj->num_rows <= 0) {
				$result = array(
					"status" => "failure",
					"errors" => array("Does not exist.")
				);
				
				return $response->withJson($result, 200);
			}
			
			$prev_obj = $prev_obj->fetch_assoc();
		}
	
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

		/* Increment version number on update. */
		if ($id != null) {
			$version = $prev_obj["version_num_customer"] + 1;
		}
		else {
			$version = 0;
		}
		
		if (count($errors) > 0) {
			$result = array(
				"status" => "failure",
				"errors" => $errors
			);
			
			return $response->withJson($result, 200);
		}
		
		$sql = "INSERT INTO customer (first_name, last_name, preferred_mail_name, salutation, home_street_1,".
			" home_street_2, country, city, state, zipcode, home_number, phone_number, email, gender, birthday,".
			" school, degree, major, major_year_start, major_year_end, version_num_customer)".
			" VALUES ('$first_name', '$last_name', '$preferred_mail_name', '$salutation', '$home_street_1',".
			"'$home_street_2', '$country', '$city', '$state', '$zipcode', '$home_number', '$phone_number', '$email', '$gender',".
			"'$birthday', '$school', '$degree', '$major', '$major_year_start', '$major_year_end', '$version'".
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
		
			return $response->withJSON($result, 200);
		}
	}
    
    public function import($request, $response, $args)
    {
		$uploadedFiles = $request->getUploadedFiles();
		$uploadedFile = $uploadedFiles['file'];
		
		if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
			$f = fopen($uploadedFile->file, "r");
		
			$this->importCSV($f, $response);
		
			fclose($f);
		
			$result = array(
				"status" => "success"
			);
				
			return $response->withJson($result, 200);
		}
		
		$result = array(
			"status" => "failure"
		);
			
		return $response->withJson($result, 200);
    }
    
    public function export($response, $data, $id)
    {
		$file_name = "storage/data.csv";
    
		$file = fopen($file_name, "w");
		
		$header = array("ID_NUMBER", "RECORD_TYPE", "PREF_ADDR_TYPE_CODE", "PREF_TELEPHONE_TYPE_CODE", "PREF_MAIL_NAME", "PREF_NAME_SORT",
			"RECORD_STATUS", "SALUTATION", "JNT_SALUTATION", "PREF_JNT_MAIL_NAME1", "PREF_JNT_MAIL_NAME2", "FIRST_NAME",
			"LAST_NAME", "HOME_STREET1",
			"HOME_STREET2", "HOME_STREET3", "HOME_FOREIGN_CITYZIP", "HOME_COUNTRY", "HOME_CITY", "HOME_STATE_CODE", "HOME_ZIP_CODE",
			"HOME_PHONE_NUMBER", "MOBILE_PHONE_NUMBER", "ACTIVE_SEASONAL_ADDR_IND", "WORK_TITLE", "WORK_COMPANY_NAME1",
			"WORK_COMPANY_NAME2", "WORK_STREET1", "WORK_STREET2", "WORK_STREET3", "WORK_FOREIGN_CITYZIP", "WORK_COUNTRY", "WORK_CITY",
			"WORK_STATE_CODE", "WORK_ZIP_CODE", "WORK_PHONE_NUMBER", "SCHOOL1", "DEGREE_CODE1", "DEGREE_YEAR1", "MAJOR1", "SCHOOL2",
			"DEGREE_CODE2", "DEGREE_YEAR2", "MAJOR2", "SCHOOL3", "DEGREE_CODE3", "DEGREE_YEAR3", "MAJOR3", "EMAIL_ADDRESS", "GENDER_CODE",
			"BIRTH_MONTH", "AGE", "LIFE_MEMBER", "ENGAGEMENT_SCORE", "ENTHUSIASM_SCORE", "RETIRED_IND"
		);
		
		fputcsv($file, $header);
		
		$data = $this->app->db->query("select * from customer where version_num_customer = 0");
		if ($data->num_rows > 0) while ($row = $data->fetch_assoc()){
			$d = array(
				$row['id_customer'],
				$row['type'],
				"", "",
				$row['preferred_mail_name'],
				"", "",
				$row['salutation'],
				"", "", "",
				$row['first_name'],
				$row['last_name'],
				$row['home_street_1'],
				$row['home_street_2'],
				$row['home_street_3'],
				"",
				$row['country'],
				$row['city'],
				$row['state'] ,
				$row['zipcode'],
				$row['home_number'],
				$row['phone_number'],
				"", "", "", "", "", "", "", "", "", "", "", "", "",
				$row['school'],
				$row['degree'],
				$row['major_year_start'],
				$row['major'],
				"", "", "", "", "", "", "", "",
				$row['email'],
				$row['gender'],
				$row['birthday'],
				"", "", "", "", ""
			);
			
			fputcsv($file, $d);
		}
		
		fclose($file);
		
		$response = $response->withHeader('Content-Type', 'application/octet-stream')
			->withHeader('Content-Disposition', 'attachment;filename="'.basename($file_name).'"')
			->withHeader('Expires', '0')
			->withHeader('Pragma', 'public')
			->withHeader('Content-Length', filesize($file_name));

		readfile($file_name);
		return $response;
    }
    
    private function importCSV($file, $response)
    {
		$header = fgetcsv($file);
		$h = $this->prepareHeader($header);
		
		while (($row = fgetcsv($file)) !== FALSE ) {
			$data = array();
		
			$email = $row[$h['EMAIL_ADDRESS']];
			$prev_obj = $this->app->db->query("select * from customer where email='$email'");
			
			if ($prev_obj->num_rows > 0) {
				$prev_obj = $prev_obj->fetch_assoc();
				$id = $prev_obj['id_customer'];
			}
			else {
				$id = NULL;
			}
		
			$data['first_name'] = $row[$h['FIRST_NAME']];
			$data['last_name'] = $row[$h['LAST_NAME']];
			$data['type'] = $row[$h['RECORD_TYPE']] == "Alumni" ? 'alumni' : 'current';
			$data['preferred_mail_name'] = $row[$h['PREF_MAIL_NAME']];
			$data['salutation'] = $row[$h['SALUTATION']];
			$data['active'] = $row[$h['RECORD_STATUS']] == "Active" ? 'true' : 'false';
			$data['home_street_1'] = $row[$h['HOME_STREET1']];
			$data['home_street_2'] = $row[$h['HOME_STREET2']];
			$data['home_street_3'] = $row[$h['HOME_STREET3']];
			$data['country'] = empty($row[$h['HOME_COUNTRY']]) ? "USA" : $row[$h['HOME_COUNTRY']];
			$data['city'] = $row[$h['HOME_CITY']];
			$data['state'] = $row[$h['HOME_STATE_CODE']];
			$data['zipcode'] = explode("-", $row[$h['HOME_ZIP_CODE']])[0];
			$data['home_number'] = '1234567890';
			$data['phone_number'] = '1234567890';
			$data['email'] = $row[$h['EMAIL_ADDRESS']];
			$data['gender'] = $row[$h['GENDER_CODE']] == 'M' ? 'male' : 'female';
			$data['birthday'] = '1992-12-20';
			$data['school'] = $row[$h['SCHOOL1']];
			$data['degree'] = $row[$h['DEGREE_CODE1']];
			$data['major'] = $row[$h['MAJOR1']];
			$data['major_year_start'] = '2017-01-01';
			$data['major_year_end'] = '2018-01-01';
			
			$this->insert_update($response, $data, $id);
		}
    }
    
    private function prepareHeader($row)
    {
		$header = array();
		
		for ($i = 0 ; $i < count($row) ; $i++) {
			$header[$row[$i]] = $i;
		}
		
		return $header;
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
