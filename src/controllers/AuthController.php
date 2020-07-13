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
            // todo set $user
            $user = $this->bindUser($_POST);
            $this->validateRegisterForm($user);
          
            if(!empty($this->errors)){
               $data['errors'] = $this->errors;
               return $this->renderView('auth/register',$data);
            }
            if(empty($this->errors)){
                
                 // encrypt password
                 $user->password = password_hash($password_confirm,PASSWORD_BCRYPT,['cost'=>13]);
                
                if($this->getModel('AuthModel')->saveUser($user) === true) {
                    setFlashMessage('success',"Inscription réussie");
                    redirectTo("/");
                }
            }  
        }
        
       return $this->renderView('auth/register');
    }
    public function login()
    {
    
    if($_SERVER['REQUEST_METHOD']==='POST'){
        // todo set $user
        $user = $this->bindUser($_POST);
        $this->validateLoginForm($user);
      
        if(!empty($this->errors)){
           $data['errors'] = $this->errors;
           return $this->renderView('auth/login',$data);
        }
        if(empty($this->errors)){
        
            $storedUser = $this->getModel("AuthModel")->getUserByEmail($user->email);  

            if(!password_verify($user->password,$storedUser->password)){
                setFlashMessage('danger',"Les identifiants ne correspondent pas à un utilisateur");
                return $this->renderView('auth/login',);
            }
            else{
                setUserData($user);
                $_SESSION['flash']['success']="Connexion réussie";
                return redirectTo("/"); 
            }
              
        }  
    }
    
   return $this->renderView('auth/login');
    }
    public function validateRegisterForm($user){
       
        if(empty($user->first_name)){
            $this->errors['first_name'] = "Le prénom est requis";
        }
        if(empty($user->last_name)){
            $this->errors['last_name'] = "Le nom est requis";
        }
        if(empty($user->email)){
            $this->errors['email'] ="L'email est requis";
        }
        if(filter_var($user->email,FILTER_VALIDATE_EMAIL)){
             $isEmailTaken = $this->getModel('AuthModel')->isEmailTaken($user->email);
            if( $isEmailTaken){
                $this->errors['email'] = "L'email est déjà prit";
            }
        }
        else{
            $this->errors['email'] = "L'email n'est pas valide";
           
        }
        if(empty($user->password)){
            $this->errors['password'] ="Le mot de passe est requis";
        }
        if(empty($user->password_confirm)){
            $this->errors['password_confirm'] ="La confirmation du mot de passe est requise";
        }
        else{
            if($user->password !== $user->password_confirm){
                $this->errors['password'] ="Les mots de passe ne correspondent pas";
            }
        }
        return  $this->errors;
        
        
    }
    
    public function validateLoginForm($user){
        if(empty($user->email)){
            $this->errors['email'] ="L'email est requis";
        }
        if(empty($user->password)){
            $this->errors['password'] ="Le mot de passe est requis";
        }
        return $this->errors;
    }
   /**
    * 
    */
    public function bindUser($incomingData,$context=null){ 
        // create $user object
        $user = new stdClass();

        // if registration ,process estra data
        if($context='register'){
            $firstName = isset($_POST['first_name'])?htmlspecialchars(strip_tags($_POST['first_name'])):'';
            $lastName = isset($_POST['last_name'])?htmlspecialchars(strip_tags($_POST['last_name'])):'';
            
            $password_confirm = isset($_POST['password_confirm'])?htmlspecialchars(strip_tags($_POST['password_confirm'])):'';

            // fill user with data
            $user->first_name = $firstName;
            $user->last_name = $lastName;
           
            $user->password_confirm = $password_confirm;
        }

        // if login process login data
        $email = isset($_POST['email'])?htmlspecialchars(strip_tags($_POST['email'])):'';
        $password = isset($_POST['password'])?htmlspecialchars(strip_tags($_POST['password'])):'';
        $user->email = $email;
        $user->password = $password;

        // return $user object
        return $user;
    } 
}
