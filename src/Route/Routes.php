<?php
declare(strict_types=1);

class Routes
{    
    /**
     * getRoutes
     *
     * @return array
     */
    public static function getRoutes() :array
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
            array('url' => '/blog/page/(\d+)', 'goto' =>  array('ArticlesController', 'list')),
            array('url' => '/blog/article/(\d+)/note', 'goto' =>  array('ArticlesController', 'saveArticleNote')),
            array('url' => '/blog/article/(\d+)', 'goto' =>  array('ArticlesController', 'getArticle')),
            array('url' => '/blog/categorie/(\d+)', 'goto' =>  array('ArticlesController', 'getArticlesByCategory')),
            array('url' => '/blog/recherche', 'goto' =>  array('ArticlesController', 'getArticlesBySearchQuery')),
           
            array('url' => '/inscription', 'goto' =>  array('AuthController', 'register')),
            array('url' => '/connexion', 'goto' =>  array('AuthController', 'login')),
            array('url' => '/deconnexion', 'goto' =>  array('AuthController', 'logout')),
            
            array('url' => '/utilisateurs/mon-compte', 'goto' =>  array('UserDashboardController', 'index')),
            array('url' => '/utilisateurs/editer-mes-informations', 'goto' =>  array('UserDashboardController', 'editMyData')),
            array('url' => '/utilisateurs/changement-mot-de-passe', 'goto' =>  array('UserDashboardController', 'changePassword')),
            array('url' => '/utilisateurs/supprimer-mon-compte', 'goto' =>  array('UserDashboardController', 'deleteMyAccount')),
            array('url' => '/utilisateurs/mon-activite/page/(\d+)/utilisateur/(\d+)', 'goto' =>  array('UserDashboardController', 'myActivity')),

            array('url' => '/utilisateurs/mes-articles/page/(\d+)', 'goto' =>  array('UserArticlesController', 'list')),
            array('url' => '/utilisateurs/mes-articles/voir-details/(\d+)', 'goto' =>  array('UserArticlesController', 'show')),
            array('url' => '/utilisateurs/mes-articles/editer/(\d+)', 'goto' =>  array('UserArticlesController', 'edit')),
            array('url' => '/utilisateurs/mes-articles/creer', 'goto' =>  array('UserArticlesController', 'create')),
            array('url' => '/utilisateurs/mes-articles/supprimer', 'goto' =>  array('UserArticlesController', 'delete')),
            
            array('url' => '/achats/sauvegarder', 'goto' =>  array('PurchasesController', 'save')),

            array('url' => '/admin/articles/page/(\d+)', 'goto' =>  array('AdminArticlesController', 'list')),
            array('url' => '/admin/articles/voir-details/(\d+)', 'goto' =>  array('AdminArticlesController', 'show')),
            array('url' => '/admin/articles/editer/(\d+)', 'goto' =>  array('AdminArticlesController', 'edit')),
            array('url' => '/admin/articles/creer', 'goto' =>  array('AdminArticlesController', 'create')),
            array('url' => '/admin/articles/supprimer', 'goto' =>  array('AdminArticlesController', 'delete')),
            
            array('url' => '/admin/categories', 'goto' =>  array('AdminCategoriesController', 'list')),
            array('url' => '/admin/categories/creer', 'goto' =>  array('AdminCategoriesController', 'create')),
            array('url' => '/admin/categories/voir-details/(\d+)', 'goto' =>  array('AdminCategoriesController', 'show')),
            array('url' => '/admin/categories/editer/(\d+)', 'goto' =>  array('AdminCategoriesController', 'edit')),
            array('url' => '/admin/categories/supprimer', 'goto' =>  array('AdminCategoriesController', 'delete')),
            
            array('url' => '/admin/utilisateurs/page/(\d+)', 'goto' =>  array('AdminUsersController', 'list')),
            array('url' => '/admin/utilisateurs/voir-details/(\d+)', 'goto' =>  array('AdminUsersController', 'show')),
            array('url' => '/admin/utilisateurs/editer/(\d+)', 'goto' =>  array('AdminUsersController', 'edit')),
            array('url' => '/admin/utilisateurs/supprimer', 'goto' =>  array('AdminUsersController', 'delete')),

            array('url' => '/admin', 'goto' =>  array('AdminHomeController', 'index')),
            array('url' => '/db-setup', 'goto' =>  array('DbSetupController', 'index')),
          
            array('url' => '/', 'goto' =>  array('HomeController', 'index')),
        ];
    }
}
// @([0-9]+)/([a-z_-]+)@
