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
}

?>
