<?php

require_once('../src/Route/Routes.php');
class Router
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];
    protected $routes = [];

    public function __construct()
    {
        $this->routes = Routes::getRoutes();

        $url = $this->getUrl();

        // initialise the trigger if no route found
        $routeFound = false;
        // filter routes by server method
        /* $routesByMethod = array_filter($this->routes, function ($route) {
            return $route['method'] == $_SERVER['REQUEST_METHOD'];
        }); */

        // loop through route looking for a match
        foreach ($this->routes as $route) {
            $urlToTest = $route['url'];

            // check if first match is an exact match
            if (preg_match("~$urlToTest~", "/$url", $matches) && $matches[0] === "/$url") {
                $routeFound = true;
                $this->controller = $route['goto'][0];
                $this->method = $route['goto'][1];
                $this->params = isset($route['goto'][2]) ? $route['goto'][2] : [];

                //exit the loop
                break;
            }
        }
        // if no route found, force the error controller
        if ($routeFound === false) {
            $this->controller = 'ErrorController';
            $this->method = 'pageNotFound';
            $this->params =  [];
        }

        // if all is ok, initialize the controller,method and params
        if (file_exists("../src/controllers/{$this->controller}.php")) {

            require_once("../src/controllers/{$this->controller}.php");

            $this->controller = new $this->controller;
        }

        if (method_exists($this->controller, $this->method)) {
            $this->method = $this->method;
        }
        call_user_func_array(array($this->controller, $this->method), $this->params);
    }
    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
            $url = filter_var($url, FILTER_SANITIZE_URL);

            return $url;
        }
    }
}
