<?php
declare(strict_types=1);

class AdminCategoriesController extends Controller
{
    private $errors = [];

    /**
     * list
     *
     * @return void
     */
    public function list(): void
    {
        // redirect if no user not logged or does not have ADMIN role
        if (!isUserLogged() || !in_array('ADMIN', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get categories from db and bind them $data
        $categories = $this->getModel("CategoryModel")->getCategories();
        $data['categories'] = $categories;

        $this->renderView("admin/categories/list", $data);
        exit;
    }

    /**
     * create
     *
     * @return void
     */
    public function create(): void
    {
        // redirect if no user not logged or does not have ADMIN role
        if (!isUserLogged() || !in_array('ADMIN', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // bind submitted values and validate them
            $category = $this->bindCategory($_POST);
            $this->validateSubmittedCategory($category);

            // some errors are found,bind the $category and the errors to $data 
            if (!empty($this->errors)) {
                $data['category'] = $category;
                $data['errors'] = $this->errors;

                // render view and data
                $this->renderView('admin/categories/create', $data);
                exit;
            }

            // no errors found,we can process the data
            if (empty($this->errors)) {

                // get the newly created id
                $lastInsertId = $this->getModel('CategoryModel')->save($category);

                if ($lastInsertId) {
                    // all is ok, send success feedback and redirect the user
                    setFlashMessage('success', "catégorie créé avec succès");
                    redirectTo('/admin/categories');
                    exit;
                } else {

                    // something went wrong, send error feedback and redirect the user
                    setFlashMessage('danger', "une erreur inattendue est survenue");
                    redirectTo('/admin/categories');
                    exit;
                }
            }
        }

        // display the form when the request method is GET
        $this->renderView("admin/categories/create");
        exit;
    }

    /**
     * show
     *
     * @return void
     */
    public function show(): void
    {
        // redirect if no user not logged or does not have ADMIN role
        if (!isUserLogged() || !in_array('ADMIN', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get url param and sanitize it 
        $cat_id = intval(getUriParts(3));

        // retrieve data from id having with the $cat_id and bind it to $data
        $category = $this->getModel("CategoryModel")->getCategoryById($cat_id);
        $data['category'] = $category;

        // display the view and data
        $this->renderView("admin/categories/show", $data);
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // bind incoming data and validate them
            $updatedCategory = $this->bindCategory($_POST, 'update');
            $this->validateSubmittedCategory($updatedCategory);

            // some errors are found, bind validated data and errors
            if (!empty($this->errors)) {
                $data['category'] = $updatedCategory;
                $data['errors'] = $this->errors;

                // send the data and errors to the frontend
                $this->renderView('admin/categories/edit', $data);
                exit;
            }

            // no errors found, we can process the data
            if (empty($this->errors)) {

                // run the update query
                $success = $this->getModel('CategoryModel')->update($updatedCategory);

                if ($success) {

                    // everything is ok, send success feedback and redirect the user
                    setFlashMessage('success', "catégorie modifié avec succès");
                    redirectTo("/admin/categories/voir-details/{$updatedCategory->id}");
                    exit;
                } else {

                    // something went wrong, send error feedback and redirect the user
                    setFlashMessage('danger', "une erreur inattendue est survenue");
                    redirectTo('/admin/categories');
                    exit;
                }
            }
        }

        // bind url param, retrieve data from db using $cat_id and bind them to $data
        $cat_id = intval(getUriParts(3));
        $category = $this->getModel("CategoryModel")->getCategoryById($cat_id);
        $data['category'] = $category;

        // display the view and data
        $this->renderView("admin/categories/edit", $data);
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
        // bind incoming data and retrieve the entry from db using the id
        $idToDelete = intval($_POST['idToDelete']);
        $categoryFound = $this->getModel('CategoryModel')->getCategoryById($idToDelete);

        if ($categoryFound) {
            // data are found, we can delete them
            $this->getModel("CategoryModel")->delete($idToDelete);

            // set success message and redirect the user
            setFlashMessage('success', "catégorie supprimée avec succès");
            redirectTo("/admin/categories");
            exit;
        } else {

            // something went wrong, send error message and redirect the user
            setFlashMessage('danger', "une erreur inattendue est survenue");
            redirectTo('/admin/categories');
            exit;
        }
    }

    /**
     * bindCategory
     *
     * @param  array $data
     * @param  string $context
     * @return object
     */
    public function bindCategory(array $data, string $context = null): object
    {
        // create an empty object to fill and bind incoming data onto it
        $category = new StdClass;
        $category->name =  htmlspecialchars(strip_tags(trim($data['name']))) ?? '';

        if ($context == 'update') {

            // we are updating the entry, bind the category id
            $category->id = htmlspecialchars(strip_tags($data['idToUpdate'])) ?? '';
        }

        return $category;
    }
    /**
     * validateSubmittedCategory
     *
     * @param  object $category
     * @return array
     */
    public function validateSubmittedCategory(object $category): array
    {

        if (empty($category->name)) {
            // the input is empty,set the error message
            $this->errors['name'] = "Le nom est requis";
        }
        return $this->errors;
    }
}
