<?php
declare(strict_types=1);

class AdminHomeController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        // redirect if no user not logged or does not have ADMIN role
        if (!isUserLogged() || !in_array('ADMIN', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get article awaiting to be published and bind them to $sdata
        $unpublishedArticles = $this->getModel('ArticleModel')->getUnpublishedArticles();
        $data['unpublishedArticles'] = $unpublishedArticles;


        // get the most popular articles 
        $MostActiveArticles = $this->getModel('ArticleModel')->getArticlesSortedBy('note_number', 'DESC', 3);
        $data['mostActiveArticles'] = $MostActiveArticles;

        // get the best rated articles
        $HighestRatedArticles = $this->getModel('ArticleModel')->getArticlesSortedBy('avg_rating', 'DESC', 3);
        $data['highestRatedArticles'] = $HighestRatedArticles;


        // display the view and data
        $this->renderView('admin/index', $data);
        exit;
    }
}
