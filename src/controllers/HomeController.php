<?php
declare(strict_types=1);

class HomeController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        // get last articles from db and bind them to $data
        $lastArticles = $this->getModel('ArticleModel')->getLastArticles(2);
        $data['lastArticles'] = $lastArticles;

        // get most active users from db and bind them to $data
        $mostActiveUsers = $this->getModel('UserModel')->getMostActiveUsers(5);
        $data['mostActiveUsers'] = $mostActiveUsers;

        // get most popular articles and bind them to data
        $MostActiveArticles = $this->getModel('ArticleModel')->getArticlesSortedBy('note_number', 'DESC', 2);
        $data['mostActiveArticles'] = $MostActiveArticles;

        // get users actions and bind them to $data
        $usersActions = $this->getModel('UserModel')->getUserActions(null, 5);
        $data['usersActions'] = $usersActions;

        // get best rated articles and bind them to $data
        $HighestRatedArticles = $this->getModel('ArticleModel')->getArticlesSortedBy('avg_rating', 'DESC', 2);
        $data['highestRatedArticles'] = $HighestRatedArticles;


        // display the view and data
        $this->renderView('pages/index', $data);
        exit;
    }
}
