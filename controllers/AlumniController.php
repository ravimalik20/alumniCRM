<?php

class AlumniController
{
    protected $app;

    public function __construct(\Slim\Container $container) {
        $this->app = $container;
    }
    
    public function home($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect("/auth/login");
      
		$response = $this->app->view->render($response, 'alumni.phtml');

		return $response;
    }
    
    public function get($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect("/auth/login");
          
		$response = $this->app->view->render($response, 'alumni-single.phtml');

		return $response;
    }
    
    public function create($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect("/auth/login");
    
		$response = $this->app->view->render($response, 'alumni-create.phtml');

		return $response;
    }
    
    public function export($request, $response, $args)
    {
		return "";
    }
    
    public function import($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect("/auth/login");
    
		$response = $this->app->view->render($response, 'alumni-import.phtml');

		return $response;
    }
}

?>
