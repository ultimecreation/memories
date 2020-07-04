<?php
class Routes
{
    public static function getRoutes()
    {
        /**
         * route format pattern|method|params|controller & method
         * url String (ie: '/',....)
         * method String (ie: GET,POST,....)
         * Controller/method/params (ie: 'HomeController','index',params[])
         * params Regex (ie: '(\d+)','(\W+)')
         * examples
         * ========
         * array('url' => '/test/(\d+)', 'method' => 'GET', 'goto' =>  array('TestController', 'index')),
         * array('url' => '/test/save', 'method' => 'POST', 'goto' =>  array('TestController', 'save')),
         * array('url' => '/retest/update', 'method' => 'PUT', 'goto' =>  array('RetestController', 'update')),
         * array('url' => '/retest/delete', 'method' => 'DELETE', 'goto' =>  array('RetestController', 'delete')),
         */
        return [
            array('url' => '/retest/index', 'method' => 'GET', 'goto' =>  array('RetestController', 'index')),
            array('url' => '/retest/create', 'method' => 'GET', 'goto' =>  array('RetestController', 'create')),
            array('url' => '/retest/save', 'method' => 'POST', 'goto' =>  array('RetestController', 'save')),
            array('url' => '/retest/edit', 'method' => 'GET', 'goto' =>  array('RetestController', 'index')),
            array('url' => '/retest/update', 'method' => 'PUT', 'goto' =>  array('RetestController', 'update')),
            array('url' => '/retest/delete', 'method' => 'DELETE', 'goto' =>  array('RetestController', 'delete')),

            array('url' => '/db-setup', 'method' => 'GET', 'goto' =>  array('DbSetupController', 'index')),
          
            array('url' => '/', 'method' => 'GET', 'goto' =>  array('HomeController', 'index')),
        ];
    }
}
// @([0-9]+)/([a-z_-]+)@
