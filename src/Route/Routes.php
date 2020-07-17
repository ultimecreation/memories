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
            array('url' => '/blog', 'goto' =>  array('ArticlesController', 'list')),
            array('url' => '/blog/article/(\d+)', 'goto' =>  array('ArticlesController', 'getArticle')),
            array('url' => '/blog/categorie/(\d+)', 'goto' =>  array('ArticlesController', 'getArticlesByCategory')),
            array('url' => '/blog/recherche', 'goto' =>  array('ArticlesController', 'getArticlesBySearchQuery')),
           
            array('url' => '/inscription', 'goto' =>  array('AuthController', 'register')),
            array('url' => '/connexion', 'goto' =>  array('AuthController', 'login')),
            array('url' => '/deconnexion', 'goto' =>  array('AuthController', 'logout')),

            array('url' => '/admin/articles', 'goto' =>  array('AdminArticlesController', 'list')),
            array('url' => '/admin/articles/voir-details/(\d+)', 'goto' =>  array('AdminArticlesController', 'show')),
            array('url' => '/admin/articles/editer/(\d+)', 'goto' =>  array('AdminArticlesController', 'edit')),
            array('url' => '/admin/articles/creer', 'goto' =>  array('AdminArticlesController', 'create')),
            array('url' => '/admin/articles/supprimer', 'goto' =>  array('AdminArticlesController', 'delete')),

            array('url' => '/admin', 'goto' =>  array('AdminHomeController', 'index')),
            array('url' => '/db-setup', 'goto' =>  array('DbSetupController', 'index')),
          
            array('url' => '/', 'goto' =>  array('HomeController', 'index')),
        ];
    }
}
// @([0-9]+)/([a-z_-]+)@
