<?php
declare(strict_types=1);

class UserArticlesController extends Controller
{

    private $errors = [];

    /**
     * list
     *
     * @return void
     */
    public function list(): void
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // create pagination
        $page = getUriParts(3) ?? 1;
        $perPage = intval($_SESSION['per_page'] ?? 5);
        $totalNbOfArticles = count($this->getModel('ArticleModel')->getAllArticlesByAuthorId(getUserData('id')));
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

        // get articles by chunk according to pagination and bind them to $data
        $articles = $this->getModel('ArticleModel')->getAllArticlesByAuthorId(getUserData('id'), $start, $perPage);
        $data['articles'] = $articles;

        // display the view and $data
        $this->renderView("users/articles/list", $data);
        exit;
    }

    /**
     * create
     *
     * @return void
     */
    public function create(): void
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get categories and bind them to $data
        $categories = $this->getModel('CategoryModel')->getCategories();
        $data['categories'] = $categories;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // create empty object,bind incoming data to $article and validate them
            $article = new stdClass;
            $article = $this->bindArticle($_POST);
            $this->validateSubmittedArticle($article);

            // some errors are found
            if (!empty($this->errors)) {
                // bind errors and validated data to $data
                $data['article'] = $article;
                $data['errors'] = $this->errors;

                // display view and data
                $this->renderView('users/articles/create', $data);
                exit;
            }

            // no errors found, we can process the data
            if (empty($this->errors)) {
                // a file is submitted
                if (!empty($_FILES['img']['name'])) {
                    // bind the filename to $article and upload the file
                    $article->img = $_FILES['img']['name'];
                    $this->uploadFile($_FILES);
                } else {
                    // no file submitted, set the default image 
                    $article->img = 'default.png';
                }

                // get the author from session
                $article->author_id = intval(getUserData('id'));

                // save entry in db
                $lastInsertId = $this->getModel('ArticleModel')->save($article);

                if ($lastInsertId) {
                    // all is ok, set success message and redirect the user
                    setFlashMessage('success', "article créé avec succès");
                    redirectTo('/utilisateurs/mes-articles/page/1');
                    exit;
                } else {
                    // something went wrong, set error message and redirect the user
                    setFlashMessage('danger', "une erreur inattendue est survenue");
                    redirectTo('/utilisateurs/mes-articles/page/1');
                    exit;
                }
            }
        }

        // dispaly the view and data when request is GET
        $this->renderView('users/articles/create', $data);
        exit;
    }

    /**
     * edit
     *
     * @return void
     */
    public function edit(): void
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get article id from url param,fetch the article and categories from db
        $articleId = intval(getUriParts(3));
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $categories = $this->getModel('CategoryModel')->getCategories();

        // bind article and categories to $data
        $data['article'] = $article;
        $data['categories'] = $categories;


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // create empty object,bind incoming data and validate them
            $updatedArticle = new stdClass;
            $updatedArticle = $this->bindArticle($_POST, 'update');
            $this->validateSubmittedArticle($article);

            // some errors are found
            if (!empty($this->errors)) {
                // bind errors and validated data to $data
                $data['article'] = $article;
                $data['errors'] = $this->errors;

                // display the view and data
                $this->renderView('users/articles/edit', $data);
                exit;
            }

            // no errors found, we can process the data
            if (empty($this->errors)) {
                // a file is submitted
                if (!empty($_FILES['img']['name'])) {
                    // bind the filename to $updatedArticle and upload it
                    $updatedArticle->img = $_FILES['img']['name'];
                    $this->uploadFile($_FILES, $updatedArticle->old_img);
                }

                // no file submitted
                else {
                    // set the register filename as the current filename to save
                    $updatedArticle->img = $updatedArticle->old_img;
                }


                // save entry in db
                $success = $this->getModel('ArticleModel')->update($updatedArticle);

                if ($success) {
                    // all is ok, set success message and redirect the user
                    setFlashMessage('success', "article modifié avec succès");
                    redirectTo("/utilisateurs/mes-articles/voir-details/{$updatedArticle->article_id}");
                    exit;
                } else {
                    // something went wrong, set error message and redirect the user
                    setFlashMessage('danger', "une erreur inattendue est survenue");
                    redirectTo('/utilisateurs/mes-articles');
                    exit;
                }
            }
        }

        // display the view and data when request is GET
        $this->renderView('users/articles/edit', $data);
        exit;
    }

    /**
     * show
     *
     * @return void
     */
    public function show(): void
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get article id from url param,fetch article from db and bind it to $data
        $articleId = intval(getUriParts(3));
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $data['article'] = $article;

        // display the view and data
        $this->renderView('/users/articles/show', $data);
        exit;
    }
    /**
     * delete
     *
     * @return void
     */
    public function delete(): void
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // bind and sanitize incoming id and delete the article from db
        $idToDelete = intval($_POST['idToDelete']);

        // check if article exists in db
        $article_exists = $this->getModel('ArticleModel')->getArticleById($idToDelete);

        if($article_exists){
            unlink("assets/uploads/$article_exists->img");

            // remove article from db
            $success = $this->getModel('ArticleModel')->deleteArticle($idToDelete);

            if ($success) {

                // record deleted,set the success feedback and redirect
                setFlashMessage('success', "suppression réussie");
                redirectTo('/utilisateurs/mes-articles/page/1');
                exit;
            } else {

                // something wrong happened, set the error feedback and redirect
                setFlashMessage('danger', "une erreur inattendue s'est produite");
                redirectTo('/utilisateurs/mes-articles/page/1');
                exit;
            }
        }
    }

    /**
     * validateSubmittedArticle
     *
     * @param  object $article
     * @return array
     */
    public function validateSubmittedArticle(object $article): array
    {

        // check each field and set errors if any
        if (empty($article->title)) {
            $this->errors['title'] = "Le titre est requis";
        }
        if (empty($article->category_id)) {
            $this->errors['category_id'] = "La catégorie est requise";
        }
        if (empty($article->content)) {
            $this->errors['content'] = "Le contenu est requis";
        }
        return $this->errors;
    }
    /**
     * bindArticle
     *
     * @param  mixed $array
     * @param  mixed $context
     * @return object
     */
    public function bindArticle(array $data, string $context = null): object
    {

        // sanitize incomind data
        $title = htmlspecialchars(strip_tags($data['title'])) ?? '';
        $category_id = htmlspecialchars(strip_tags($data['category_id'])) ?? '';
        $content = $data['content'] ?? '';
        $to_be_published = !empty($data['to_be_published']) ? 0 : null;

        // create an empty object and bind data onto it
        $article = new StdClass();
        $article->title = $title;
        $article->category_id = $category_id;
        $article->content = $content;
        $article->published = $to_be_published;

        // we are updating the article
        if ($context === 'update') {
            // bind additionnal data
            $article->article_id = htmlspecialchars(strip_tags($data['article_id'])) ?? '';
            $article->old_img = htmlspecialchars(strip_tags($data['old_img'])) ?? 'default.png';
        }

        return $article;
    }
    /**
     * uploadFile
     *
     * @param  mixed $data
     * @param  mixed $old_img
     * @return bool
     */
    public function uploadFile(array $data, string $old_img = null): bool
    {
        if ($data['img']['name'] != 'default.png') {
            // bind data
            $fileToUpload = $data['img']['tmp_name'];
            $name = basename($data['img']['name']);

            // upload the file
            move_uploaded_file($fileToUpload, "assets/uploads/$name");
            if ($old_img != 'default.png') {
                // remove the old one if necessary
                unlink("assets/uploads/$old_img");
            }

            return true;
        }

        // bind data and upload the file
        $fileToUpload = $data['img']['tmp_name'];
        $name = basename($data['img']['name']);
        move_uploaded_file($fileToUpload, "assets/uploads/$name");
        return true;
    }
}
