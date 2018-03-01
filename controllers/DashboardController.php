<?php

class DashboardController
{
    protected $view;

    public function __construct(\Slim\Views\PhpRenderer $view) {
        $this->view = $view;
    }
    public function home($request, $response, $args) {
      
      $response = $this->view->render($response, 'dashboard.phtml');
      
      return $response;
    }
}

?>
