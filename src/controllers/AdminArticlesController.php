<?php // $data = array('template' => 'custom' );
declare(strict_types=1);

class AdminArticlesController extends Controller
{
    private array $errors = [];



    /**
     * list
     * @desc display the list of articles with a pagination
     * @return void
     */
    public function list(): void
    {
        // redirect if no user not logged or does not have ADMIN role
        if (!isUserLogged() || !in_array('ADMIN', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // create the pagination
        $page = getUriParts(3) ?? 1;
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

        // get articles list from db
        $articles = $this->getModel('ArticleModel')->getAllArticles($start, $perPage);

        // bind pagination and articles list to $data for the frontend
        $data['pagination'] = $pagination;
        $data['articles'] = $articles;

        // render the view along with the data and exit
        $this->renderView('admin/articles/list', $data);
        exit;
    }

    /**
     * show
     * @desc display one article
     * @return void
     */
    public function show(): void
    {
        // redirect if no user not logged or does not have ADMIN role
        if (!isUserLogged() || !in_array('ADMIN', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // retrieve article id from url param
        $articleId = intval(getUriParts(3));

        // get article from db and bind it to $data
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $data['article'] = $article;

        // render the view with $data and exit the method
        $this->renderView('/admin/articles/show', $data);
        exit;
    }



    /**
     * create
     * @desc display the form with GET method
     * and handle form submit with POST method
     * @return void
     */
    public function create(): void
    {
        // redirect if no user not logged or does not have ADMIN role
        if (!isUserLogged() || !in_array('ADMIN', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get the stored categories from db and bind them to $data
        $categories = $this->getModel('CategoryModel')->getCategories();
        $data['categories'] = $categories;

        // the form has been submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // bind incoming data
            $article =  $this->bindArticle($_POST);

            // validate incoming data
            $this->validateSubmittedArticle($article);

            // there are some errors
            if (!empty($this->errors)) {

                // send validated article data and errors to the user
                $data['article'] = $article;
                $data['errors'] = $this->errors;

                // render the view with data 
                $this->renderView('admin/articles/create', $data);
                exit;
            }

            // no errors found, we can process the data
            if (empty($this->errors)) {

                // an image is submitted, bind img data and upload the file
                if (!empty($_FILES['img']['name'])) {

                    $article->img = $_FILES['img']['name'];
                    $this->uploadFile($_FILES);
                }
                // no image submitted, set the image to default image
                else {
                    $article->img = 'default.png';
                }
                // set published to true for the adminiustrator
                $article->published = 1;
                // set the author_id to the logged user id
                $article->author_id = intval(getUserData('id'));

                // save data to the db
                $lastInsertId = $this->getModel('ArticleModel')->save($article);


                if ($lastInsertId) {
                    // data are saved successfully, redirect with success feedback
                    setFlashMessage('success', "article créé avec succès");
                    redirectTo('/admin/articles/page/1');
                    exit;
                } else {
                    // something went wrong, redirect the user with an error message
                    setFlashMessage('danger', "une erreur inattendue est survenue");
                    redirectTo('/admin/articles/page/1');
                    exit;
                }
            }
        }

        // render view when GET request
        $this->renderView('admin/articles/create', $data);
        exit;
    }
    /**
     * edit
     *
     * @return void
     */
    public function edit(): void
    {
        // redirect if no user not logged or does not have ADMIN role
        if (!isUserLogged() || !in_array('ADMIN', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get id from url and fetch article and categories from db
        $articleId = intval(getUriParts(3));
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $categories = $this->getModel('CategoryModel')->getCategories();

        // bind article and categories to $data for the frontend
        $data['article'] = $article;
        $data['categories'] = $categories;

        // the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // bind incoming data and validate them
            $updatedArticle = $this->bindArticle($_POST, 'update');
            $this->validateSubmittedArticle($article);

            // there are errors
            if (!empty($this->errors)) {

                // bind errors and validated data to $data for the front
                $data['article'] = $article;
                $data['errors'] = $this->errors;

                // render the view along with $data
                $this->renderView('admin/articles/edit', $data);
                exit;
            }

            // no errors found,we can process the data
            if (empty($this->errors)) {


                if (!empty($_FILES['img']['name'])) {

                    // a file has been submitted, bind it,verify it and upload it
                    $updatedArticle->img = $_FILES['img']['name'];
                    $this->uploadFile($_FILES, $updatedArticle->old_img);
                } else {

                    // no file received, keep the old one
                    $updatedArticle->img = $updatedArticle->old_img;
                }

                // update db with incoming data
                $success = $this->getModel('ArticleModel')->update($updatedArticle);

                if ($success === true) {

                    // update is ok, set success feedback and redirect
                    setFlashMessage('success', "article modifié avec succès");
                    redirectTo("/admin/articles/voir-details/{$updatedArticle->article_id}");
                    exit;
                } else if ($success == false) {

                    // update did not succeed, set error feedback and redirect
                    setFlashMessage('danger', "Les fonds ne sont pas suffisants");
                    redirectTo("/admin/articles/voir-details/{$updatedArticle->article_id}");
                    exit;
                } else {

                    // something unexpected happened, set error feedback and redirect to admin
                    setFlashMessage('danger', "une erreur inattendue est survenue");
                    redirectTo("/admin");
                    exit;
                }
            }
        }

        // render the view and $data when GET request
        $this->renderView('admin/articles/edit', $data);
        exit;
    }
    /**
     * delete
     *
     * @return void
     */
    public function delete(): void
    {
        // redirect if no user not logged or does not have ADMIN role
        if (!isUserLogged() || !in_array('ADMIN', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // make sure the id is an int,bind it,and delete it from db
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
                redirectTo('/admin/articles/page/1');
                exit;
            } else {
    
                // something wrong happened, set the error feedback and redirect
                setFlashMessage('danger', "une erreur inattendue s'est produite");
                redirectTo('/admin/articles/page/1');
                exit;
            }
        }
    }
    /**
     * validateSubmittedArticle
     *
     * @param  mixed $article
     * @return array $this->errors
     */
    public function validateSubmittedArticle(object $article): array
    {
        // make sure data are not empty
        if (empty($article->title)) {
            $this->errors['title'] = "Le titre est requis";
        }
        if (empty($article->category_id)) {
            $this->errors['category_id'] = "La catégorie est requise";
        }
        if (empty($article->content)) {
            $this->errors['content'] = "Le contenu est requis";
        }

        // return errors if any
        return $this->errors;
    }

    /**
     * bindArticle
     *
     * @param  array $data
     * @param  string $context
     * @return object
     */
    public function bindArticle(array $data, string $context = null): object
    {

        // sanitize data
        $authors_id = intval($data['author_id']) ?? null;
        $title = htmlspecialchars(strip_tags($data['title'])) ?? '';
        $category_id = htmlspecialchars(strip_tags($data['category_id'])) ?? '';
        $content = $data['content'] ?? '';
        $published = !empty($data['published']) ? true : null;

        // create empty $article object and bind incoming data 
        $article = new StdClass();
        $article->author_id = $authors_id;
        $article->title = $title;
        $article->category_id = $category_id;
        $article->content = $content;
        $article->published = $published;

        // context is update, sanitize additionnal data
        if ($context === 'update') {
            $article->article_id = htmlspecialchars(strip_tags($data['article_id'])) ?? '';
            $article->old_img = htmlspecialchars(strip_tags($data['old_img'])) ?? 'default.png';
            $article->published = !empty($data['published']) == 'on' ? true : null;
        }

        return $article;
    }

    /**
     * uploadFile
     *
     * @param  array $data
     * @param  string $old_img
     * @return bool
     */
    public function uploadFile(array $data, string $old_img = null): bool
    {
        if ($data['img']['name'] != 'default.png') {

            // get and bind submitted file name if it is not the default image
            $fileToUpload = $data['img']['tmp_name'];
            $name = basename($data['img']['name']);

            // upload it
            move_uploaded_file($fileToUpload, "assets/uploads/$name");
            if ($old_img != 'default.png') {

                // remove the previous one if necessary
                unlink("assets/uploads/$old_img");
            }

            return true;
        }

        // get and bind submitted file name 
        $fileToUpload = $data['img']['tmp_name'];
        $name = basename($data['img']['name']);

        // upload the file
        move_uploaded_file($fileToUpload, "assets/uploads/$name");
        return true;
    }
}
