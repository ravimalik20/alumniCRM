<?php

class DashboardController
{
    protected $app;

    public function __construct(\Slim\Container $container) {
        $this->app = $container;
    }
    
    public function home($request, $response, $args) {
      
      $response = $this->app->view->render($response, 'dashboard.phtml');
      
      return $response;
    }
}

?>
