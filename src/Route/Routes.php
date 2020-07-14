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
            array('url' => '/blog', 'goto' =>  array('ArticleController', 'list')),
            array('url' => '/blog/article/(\d+)', 'goto' =>  array('ArticleController', 'getArticle')),
            array('url' => '/blog/categorie/(\d+)', 'goto' =>  array('ArticleController', 'getArticlesByCategory')),
           
            array('url' => '/inscription', 'goto' =>  array('AuthController', 'register')),
            array('url' => '/connexion', 'goto' =>  array('AuthController', 'login')),
            array('url' => '/deconnexion', 'goto' =>  array('AuthController', 'logout')),

            array('url' => '/db-setup', 'goto' =>  array('DbSetupController', 'index')),
          
            array('url' => '/', 'goto' =>  array('HomeController', 'index')),
        ];
    }
}
// @([0-9]+)/([a-z_-]+)@
