<?php

class AuthController
{
    protected $app;

    public function __construct(\Slim\Container $container) {
        $this->app = $container;
    }
    
    public function login($request, $response, $args)
    {      
		$response = $this->app->view->render($response, 'login.phtml');

		return $response;
    }
    
    public function register($request, $response, $args)
    {
		if (! Helper::is_login())
			return $response->withRedirect(Helper::url("/auth/login"));
      
		$response = $this->app->view->render($response, 'register.phtml');

		return $response;
    }
}

?>
