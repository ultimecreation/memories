<?php
declare(strict_types=1);

class ArticlesController extends Controller
{

    /**
     * list
     *
     * @return void
     */
    public function list(): void
    {
        // get current page from url param and set pagination
        $page = getUriParts(2) ?? 1;
        $perPage = intval($_SESSION['per_page'] ?? 5);
        $totalNbOfArticles = count($this->getModel('ArticleModel')->getAllArticles());
        $nbOfPages = ceil($totalNbOfArticles / $perPage);
        $start = intval(($page - 1) * $perPage);
        $pagination = new StdClass();
        if ($page > 1) {
            $pagination->previous_page = $page - 1 ?? '';
        }
        if ($page < $nbOfPages) {
            $pagination->next_page = $page + 1 ?? '';
        }
        $pagination->total_pages = $nbOfPages;
        $pagination->current_page = $page;

        // bind pagination to $data
        $data['pagination'] = $pagination;

        // get article chunk for the requested page and bind it to $data
        $articles = $this->getModel('ArticleModel')->getAllArticles($start, $perPage);
        $data['articles'] = $articles;

        // render view and articles
        $this->renderView('articles/list', $data);
        exit;
    }

    /**
     * getArticle
     *
     * @return void
     */
    public function getArticle(): void
    {
        // get article id,extract data from db and bind them to $data
        $articleId = intval(getUriParts(2));
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $data['article'] = $article;

        // get related notes from db and bind them to $data
        $notes = $this->getModel("ArticleModel")->getNotesByArticleId($articleId);
        $data['notes'] = $notes;

        // display view and $data
        $this->renderView('articles/single', $data);
        exit;
    }

    /**
     * getArticlesByCategory
     *
     * @return void
     */
    public function getArticlesByCategory(): void
    {
        // get category id form url param,retrieve record in db and bind it to $data
        $categoryId = intval(getUriParts(2));
        $articles = $this->getModel('ArticleModel')->getArticleByCategory($categoryId);
        $data['articles'] = $articles;

        // bind category name to $data
        $data['cat_name'] = $articles[0]->cat_name;


        // display view and $data
        $this->renderView('articles/category', $data);
        exit;
    }

    /**
     * getArticlesBySearchQuery
     *
     * @return void
     */
    public function getArticlesBySearchQuery(): void
    {
        // bind url param,fetch data from db and bind them to $data
        $term = htmlspecialchars(strip_tags($_POST['terme']));
        $articles = $this->getModel('ArticleModel')->getArticlesBySearchQuery($term);
        $data['articles'] = $articles;
        $data['term'] = $term;
        $data['count'] = count($articles);

        // display view and data
        $this->renderView('articles/search', $data);
        exit;
    }
    /**
     * saveArticleNote
     *
     * @return void
     */
    public function saveArticleNote(): void
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // create empty note object and bind data onto it
            $tmpNote = new stdClass;
            $tmpNote->user_id = (int) getUserData('id');
            $tmpNote->article_id = htmlspecialchars(strip_tags($_POST['article_id']));
            $tmpNote->note = htmlspecialchars(strip_tags($_POST['rating']));

            $success = $this->getModel('ArticleModel')->saveArticleNote($tmpNote);
            if ($success) {
                // record saved, set success message and redirect the user
                setFlashMessage('success', "Note sauvegardée avec succès");
                redirectTo("/blog/article/$tmpNote->article_id");
                exit;
            } else {
                // something went wrong, set error message and redirect the user
                setFlashMessage('danger', "Vous avez déjà noté cet article");
                redirectTo("/blog/article/$tmpNote->article_id");
                exit;
            }
        }
    }
}
