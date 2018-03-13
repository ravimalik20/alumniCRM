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
      
		$response = $this->app->view->render($response, 'dashboard.phtml');
      
		return $response;
    }
}

?>
