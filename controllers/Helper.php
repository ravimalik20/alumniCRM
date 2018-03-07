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
}

?>
