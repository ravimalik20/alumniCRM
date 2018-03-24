<?php

class DashboardController
{
    protected $app;

    public function __construct(\Slim\Container $container)
    {
		$this->app = $container;
    }
    
    public function home($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));
      
		return $response->withRedirect(Helper::url("/alumni"));
    }
}

?>
