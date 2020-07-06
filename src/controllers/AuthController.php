<?php
class AuthController extends Controller
{
     public $errors = [];

    public function register()
    {
       
        /* if($_SESSION['register_errors'] != null){
            $data['errors'] = $_SESSION['register_errors'];
            $_SESSION['register_errors']=null;
            return $this->renderView('auth/register',$data);
        } */
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $this->validateRegisterForm();
            if(!empty($this->errors)){
               
            }
            if(empty($this->errors)){
                $user = new StdClass();
               
                // encrypt password
                $user->password = password_hash($password_confirm,PASSWORD_BCRYPT,['cost'=>13]);
                debug($user);die();
                // fill user with data
                $user->first_name = $firstName;
                $user->last_name = $lastName;
                $user->email = $email;
    
                if($this->getModel('AuthModel')->saveUser($user) === true) {
                    redirectTo("/");
                }
            }  
        }
        
       return $this->renderView('auth/register');
    }
    public function login()
    {
        if($_SESSION['login_errors'] != null){
            $data['errors'] = $_SESSION['login_errors'];
            $_SESSION['login_errors']=null;
            return $this->renderView('auth/login',$data);
        }
       return $this->renderView('auth/login');
    }
    public function validateRegisterForm(){
        $firstName = isset($_POST['first_name'])?htmlspecialchars(strip_tags($_POST['first_name'])):'';
        $lastName = isset($_POST['last_name'])?htmlspecialchars(strip_tags($_POST['last_name'])):'';
        $email = isset($_POST['email'])?htmlspecialchars(strip_tags($_POST['email'])):'';
        $password = isset($_POST['password'])?htmlspecialchars(strip_tags($_POST['password'])):'';
        $password_confirm = isset($_POST['password_confirm'])?htmlspecialchars(strip_tags($_POST['password_confirm'])):'';

        if(empty($firstName)){
            $this->errors['first_name'] = "Le prénom est requis";
        }
        if(empty($lastName)){
            $this->errors['last_name'] = "Le nom est requis";
        }
        if(empty($email)){
            $this->errors['email'] ="L'email est requis";
        }
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
             $isEmailTaken = $this->getModel('AuthModel')->isEmailTaken($email);
            if( $isEmailTaken){
                $this->errors['email'] = "L'email est déjà prit";
            }
        }
        else{
            $this->errors['email'] = "L'email n'est pas valide";
           
        }
        if(empty($password)){
            $this->errors['password'] ="Le mot de passe est requis";
        }
        if(empty($password_confirm)){
            $this->errors['password_confirm'] ="La confirmation du mot de passe est requise";
        }
        else{
            if($password !== $password_confirm){
                $this->errors['password'] ="Les mots de passe ne correspondent pas";
            }
        }

        return  $this->errors;
        
        
    }
    public function validateLoginForm(){
        $email = isset($_POST['email'])?htmlspecialchars(strip_tags($_POST['email'])):'';
        $password = isset($_POST['password'])?htmlspecialchars(strip_tags($_POST['password'])):'';

        
        if(empty($email)){
            $this->errors['email'] ="L'email est requis";
        }
       else{
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $this->errors['email'] = "L'email n'est pas valide";
            }
       }
        if(empty($password)){
            $this->errors['password'] ="Le mot de passe est requis";
        }
        if(!empty($this->errors)){
            $_SESSION['login_errors']=$this->errors;
            return  redirectTo("/connexion");
        }
        if(empty($this->errors)){

            $user = $this->getModel("AuthModel")->getUserByEmail($email);

            if($user){
                
                if(password_verify($password,$user->password)){
                   unset($user->password);
                   setUserData($user);
                 redirectTo("/");
                }
            } 
            else{
                return  redirectTo("/connexion");
            } 
            
        }
    }
}
