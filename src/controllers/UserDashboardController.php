<?php
declare(strict_types=1);

class UserDashboardController extends Controller
{
    private $errors = [];

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get user id from session to fetch data from db
        $user_id = getUserData('id');

        // get the user info,activity, and the product to sell
        $user =$this->getModel('UserModel')->getUserById(getUserData('id'));
        $activities = $this->getModel('UserModel')->getUserActions($user_id, 5);
        $products = $this->getModel('ProductModel')->getAllByCategory('BuyPoint');

        // bind data coming from db to $data
        $data['user'] = $user;
        $data['activities'] = $activities;
        $data['products'] = $products;

        // display the view and data
        $this->renderView("users/index", $data);
        exit;
    }

    /**
     * myActivity
     *
     * @return void
     */
    public function myActivity(): void
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        // get the user id, and create the pagination
        $userId = getUserData('id');
        $stored_items = $this->getModel('UserModel')->getUserActions($userId);
        $total_count = count($stored_items);
        $per_page = 5;
        $pages_count = ceil($total_count / $per_page);
        $current_page = getUriParts(3) ?? 1;
        $offset = ($current_page - 1) * $per_page;

        // bind the pagination to $data
        $data['prev_page'] = ($current_page - 1 > 0) ?  $current_page - 1 : null;
        $data['next_page'] = ($current_page  < $pages_count) ?  $current_page + 1 : null;
        $data['pages_count'] = $pages_count;
        $data['current_page'] = $current_page;

        // retrieve actions from db and boind them to $data
        $activities = $this->getModel('UserModel')->getUserActions(getUserData('id'), $per_page, $offset);
        $data['activities'] = $activities;

        // display the view and data
        $this->renderView("users/my-activity", $data);
        exit;
    }

    /**
     * editMyData
     *
     * @return void
     */
    public function editMyData(): void
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }
        // get the user from session,get related data from db, and bind them to $data
        $user = $this->getModel('UserModel')->getUserById(getUserData('id'));
        $data['user'] = $user;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // bind and sanitize incoming data
            $first_name = htmlspecialchars(strip_tags(trim($_POST['first_name']))) ?? '';
            $last_name = htmlspecialchars(strip_tags(trim($_POST['last_name']))) ?? '';

            // check for errors if any
            if (empty($first_name)) $this->errors['first_name'] = "Le prénom est requis";
            if (empty($last_name)) $this->errors['last_name'] = "Le nom est requis";

            // some errors are found
            if (!empty($this->errors)) {
                // bind errors to $data and display the view and errors
                $data['errors'] = $this->errors;
                $this->renderView("users/edit-my-data", $data);
                exit;
            }

            // no errors found
            if (empty($this->errors)) {
                // update user data and update the stored data in session
                $this->getModel('UserModel')->updateUser($first_name, $last_name, getUserData('id'));

                // update session data about the current user
                $sessionUser = (object) getUserData();
                $sessionUser->username = "$first_name $last_name";
                setUserData($sessionUser);

                // set success message and redirect the user
                setFlashMessage("success", "Informations enregistrées avec succès");
                redirectTo('/utilisateurs/mon-compte');
                exit;
            }
        }

        // display the view and data when request is GET
        $this->renderView("users/edit-my-data", $data);
        exit;
    }

    /**
     * changePassword
     *
     * @return void
     */
    public function changePassword(): void
    {

        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // sanitize and bind data 
            $password = htmlspecialchars(strip_tags(trim($_POST['password']))) ?? '';
            $confirm_password = htmlspecialchars(strip_tags(trim($_POST['confirm_password']))) ?? '';

            // checxk for errors if any
            if (empty($password)) $this->errors['password'] = "Le mot de passe est requis";
            if (empty($confirm_password)) $this->errors['confirm_password'] = "La confirmation du mot de passe est requise";
            else if ($confirm_password != $password) $this->errors['password'] = "Les mots de passe ne correspondent pas";

            // some errors are found
            if (!empty($this->errors)) {
                // display the view and errors
                $data['errors'] = $this->errors;
                $this->renderView("users/change-password", $data);
                exit;
            }

            // no errors,we can process the data
            if (empty($this->errors)) {
                // hash the password to store and update db
                $password = password_hash($confirm_password, PASSWORD_ARGON2I, array('time_cost' => 4));
                $this->getModel('UserModel')->updatePassword($password, getUserData('id'));

                // set success message and redirect the user
                setFlashMessage("success", "mot de passe enregistrées avec succès");
                redirectTo('/utilisateurs/mon-compte');
                exit;
            }
        }

        // display the view when request is GET
        $this->renderView("users/change-password");
        exit;
    }
    
    /**
     * deleteMyAccount
     *
     * @return void
     */
    public function deleteMyAccount() :void
    {
        // redirect if no user not logged or does not have USER role
        if (!isUserLogged() || !in_array('USER', getUserData('roles'))) {
            redirectTo('/');
            exit;
        }
        // get user from session and delete its data from db
        $userId = getUserData('id');
        $this->getModel('UserModel')->deleteUser($userId);
        userLogoutRequest();

        // set success message and redirect the user
        setFlashMessage("success", "Votre compte est supprimé");
        redirectTo('/');
        exit;
    }
}
