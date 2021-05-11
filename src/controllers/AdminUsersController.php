<?php
declare(strict_types=1);

class AdminUsersController extends Controller
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

        // get all users from db to create the pagination
        $users = $this->getModel('UserModel')->getAllUsers();
        // create the pagination
        $page = getUriParts(3) ?? 1;
        $perPage = intval($_SESSION['per_page'] ?? 5);
        $totalNbOfUsers = count($this->getModel('UserModel')->getAllUsers());
        $nbOfPages = ceil($totalNbOfUsers / $perPage);
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
        // bind the pagination to $data
        $data['pagination'] = $pagination;

        // get users by chunk and bind them to $data
        $users =  $this->getModel('UserModel')->getAllUsers($start, $perPage);
        $data['users'] = $users;

        // display the view with data
        $this->renderView('admin/users/list', $data);
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

        // get user id from url param, get the user from db, and bind it to $data
        $userId = htmlspecialchars(strip_tags(getUriParts(3)));
        $user = $this->getModel('UserModel')->getUserById($userId);
        $data['user'] = $user;

        // display the view and data
        $this->renderView('admin/users/show', $data);
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

        // get the user id from url, and get it from db
        $userId = htmlspecialchars(strip_tags(getUriParts(3)));
        $user = $this->getModel('UserModel')->getUserById($userId);
        $data['user'] = $user;

        // get all roles from db, and bind them to $data
        $roles = $this->getModel('RoleModel')->getAll();
        $data['roles'] = $roles;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // bind incoming data and validate them
            $user = $this->bindUser($_POST);
            $this->validateSubmittedUser($user);

            if (!empty($this->errors)) {
                // TO BE DONE
                echo "pas bon";
                debug($this->errors);
            }
            if (empty($this->errors)) {
                // no errors found, we can process the data
                $this->getModel('UserModel')->updateByAdmin($user);

                // set success message and redirect user
                setFlashMessage("success", "L'utilisateur a été modifié avec succès");
                redirectTo("/admin/utilisateurs/voir-details/$user->id");
                exit;
            }
        }

        // display the form when the request is GET
        $this->renderView('admin/users/edit', $data);
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

        // bind, sanitize incoming data and delete the record from db
        $idToDelete = htmlspecialchars(strip_tags($_POST['idToDelete']));
        $success = $this->getModel('UserModel')->deleteUser($idToDelete);
        if ($success) {
            // set success feedback and redirect the user
            setFlashMessage("success", "Utilisateur supprimé avec succès");
            redirectTo('/admin/utilisateurs/page/1');
            exit;
        }
    }


    /**
     * bindUser
     *
     * @param  array $data
     * @return object
     */
    public function bindUser(array $data): object
    {
        // create empty user object and bind data onto it
        $user = new StdClass;
        $user->id = intval($data['user_id']);
        $user->first_name = htmlspecialchars(strip_tags(trim($data['first_name']))) ?? '';
        $user->last_name = htmlspecialchars(strip_tags(trim($data['last_name']))) ?? '';
        $user->email = htmlspecialchars(strip_tags(trim($data['email']))) ?? '';

        // create empty role array
        $user->roles = [];
        foreach ($data['roles'] as $role) {
            // add roles to the array
            $roleId = intval($role);
            array_push($user->roles, $roleId);
        }

        return $user;
    }
    /**
     * validateSubmittedUser
     *
     * @param  object $user
     * @return array
     */
    public function validateSubmittedUser(object $user): array
    {
        // if empty data, set error message
        if (empty($user->id)) $this->errors['user_id'] = "Aucun utilisateur n'est défini";
        if (empty($user->first_name)) $this->errors['first_name'] = "Le prénom est requis";
        if (empty($user->last_name)) $this->errors['last_name'] = "Le nom est requis";
        if (empty($user->email)) $this->errors['email'] = "L'email est requis";
        else if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) $this->errors['email'] = "L'email n'est pas valide";
        return $this->errors;
    }
}
