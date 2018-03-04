<?php

class AlumniController
{
    protected $app;

    public function __construct(\Slim\Container $container) {
        $this->app = $container;
    }
    
    public function home($request, $response, $args)
    {  
		$response = $this->app->view->render($response, 'alumni.phtml');

		return $response;
    }
    
    public function get($request, $response, $args)
    {      
		$response = $this->app->view->render($response, 'alumni-single.phtml');

		return $response;
    }
    
    public function create($request, $response, $args)
    {
		$response = $this->app->view->render($response, 'alumni-create.phtml');

		return $response;
    }
}

?>
