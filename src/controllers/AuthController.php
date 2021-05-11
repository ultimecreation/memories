<?php
declare(strict_types=1);

class AuthController extends Controller
{
    public $errors = [];

    /**
     * register
     *
     * @return void
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            // bind incoming data to user and validate them
            $user = $this->bindUser($_POST,'register');
            $this->validateRegisterForm($user);
           
            // some errors was found
            if (!empty($this->errors)) 
            {
                // bind errors and validated data to $data
                $data['errors'] = $this->errors;
                $data['user'] = $user;

                // display view and $data
                $this->renderView('auth/register', $data);
                exit;
            }

            // no errors found, we can process the data
            if (empty($this->errors)) 
            {
                // encrypt password
                $user->password = password_hash($user->password_confirm, PASSWORD_ARGON2I, ['cost_time' => 4]);

                // save user
                if ($this->getModel('AuthModel')->saveUser($user) === true) 
                {
                    // set success message and redirect the user
                    setFlashMessage('success', "Inscription réussie");
                    redirectTo("/");
                    exit;
                }
            }
        }

        // display the view when request is GET
        $this->renderView('auth/register');
        exit;
    }

    /**
     * login
     *
     * @return void
     */
    public function login(): void
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            // bind incoming data to user and validate them
            $user = $this->bindUser($_POST);
            $this->validateLoginForm($user);

            // some errors found
            if (!empty($this->errors)) 
            {
                // bind errors to $data and display the view
                $data['errors'] = $this->errors;
                $this->renderView('auth/login', $data);
                exit;
            }

            // no errors found, we can process the data
            if (empty($this->errors)) 
            {
                // get the stored user 
                $storedUser = $this->getModel("AuthModel")->getUserByEmail($user->email);

                // check the password
                if (!password_verify($user->password, $storedUser->password)) 
                {
                    // set success message and redirect the user
                    setFlashMessage('danger', "Les identifiants ne correspondent pas à un utilisateur");
                    $this->renderView('auth/login');
                    exit;
                } 
                if (empty($storedUser->roles)) 
                {
                    // the user has no roles because no access are granted anymore, 
                    // set feedback message and redirect the user
                    setFlashMessage('danger', "Compte inactivé");
                    redirectTo("/");
                    exit;
                } 
                else 
                {
                    // all is ok, register the las login time
                    $this->getModel('AuthModel')->saveLastLoginAt($storedUser->id);

                    // get user points and bind them onto the user
                    $data = $this->getModel('UserModel')->getUserPoints($storedUser->id);
                    $storedUser->total_points = $data->total_points;

                    // unset the password for security reasons and save the data in session
                    unset($storedUser->password);
                    setUserData($storedUser);

                    // set success message and redirect the user
                    setFlashMessage('success', "Connexion réussie");
                    redirectTo("/");
                    exit;
                }
            }
        }
        // display the login form when request is GET
        $this->renderView('auth/login');
        exit;
    }
    /**
     * logout
     *
     * @return void
     */
    public function logout(): void
    {
        // unset user data from session,set success message and redirect the user
        userLogoutRequest();
        $_SESSION['flash']['success'] = "Déconnexion réussie";
        redirectTo("/");
        exit;
    }

    /**
     * validateRegisterForm
     *
     * @param  object $user
     * @return array
     */
    public function validateRegisterForm(object $user): array
    {
        // check fields and set errors if any
        if (empty($user->first_name)) {
            $this->errors['first_name'] = "Le prénom est requis";
        }
        if (empty($user->last_name)) {
            $this->errors['last_name'] = "Le nom est requis";
        }
        if (empty($user->email)) {
            $this->errors['email'] = "L'email est requis";
        }
        if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $isEmailTaken = $this->getModel('AuthModel')->isEmailTaken($user->email);
            if ($isEmailTaken) {
                $this->errors['email'] = "L'email est déjà prit";
            }
        } else {
            $this->errors['email'] = "L'email n'est pas valide";
        }
        if (empty($user->password)) {
            $this->errors['password'] = "Le mot de passe est requis";
        }
        if (empty($user->password_confirm)) {
            $this->errors['password_confirm'] = "La confirmation du mot de passe est requise";
        } else {
            if ($user->password !== $user->password_confirm) {
                $this->errors['password'] = "Les mots de passe ne correspondent pas";
            }
        }
        return  $this->errors;
    }

    /**
     * validateLoginForm
     *
     * @param  object $user
     * @return array
     */
    public function validateLoginForm(object $user): array
    {
        // check fields and set errors if any
        if (empty($user->email)) {
            $this->errors['email'] = "L'email est requis";
        }
        if (empty($user->password)) {
            $this->errors['password'] = "Le mot de passe est requis";
        }
        return $this->errors;
    }

    /**
     * bindUser
     *
     * @param  array $incomingData
     * @param  string $context
     * @return object
     */
    public function bindUser(array $incomingData, string $context = null): object
    {
        // create $user object
        $user = new stdClass();

        // if registration ,process extra data
        if ($context == 'register') {
            $firstName = isset($incomingData['first_name']) ? htmlspecialchars(strip_tags($incomingData['first_name'])) : '';
            $lastName = isset($incomingData['last_name']) ? htmlspecialchars(strip_tags($incomingData['last_name'])) : '';

            $password_confirm = isset($incomingData['password_confirm']) ? htmlspecialchars(strip_tags($incomingData['password_confirm'])) : '';

            // fill user with data
            $user->first_name = $firstName;
            $user->last_name = $lastName;

            $user->password_confirm = $password_confirm;
        }

        // if login process login data
        $email = isset($incomingData['email']) ? htmlspecialchars(strip_tags($incomingData['email'])) : '';
        $password = isset($incomingData['password']) ? htmlspecialchars(strip_tags($incomingData['password'])) : '';
        $user->email = $email;
        $user->password = $password;

        // return $user object
        return $user;
    }
}
