<?php

class Helper
{
	public static function url($url)
	{
		global $app_hostname;
	
		if ($url[0] == '/')
			return "http://$app_hostname$url";
		else
			return "http://$app_hostname/$url";
	}
	
	public static function is_login()
	{
		if (! empty($_SESSION['auth_token']))
			return true;
		else
			return false;
	}

	public static function user_id($app)
	{
		if (empty($_SESSION['auth_token']))
			return 0;

		$user = $app->db->query("select * from users where login_token='".$_SESSION['auth_token']."';");
		if ($user->num_rows <= 0)
			return 0;

		$user = $user->fetch_assoc();

		return $user['id_admin_user'];
	}

	public static function user($app)
	{
		if (empty($_SESSION['auth_token']))
			return 0;

		$user = $app->db->query("select * from users where login_token='".$_SESSION['auth_token']."';");
		if ($user->num_rows <= 0)
			return 0;

		$user = $user->fetch_assoc();

		return $user;
	}
}

?>
