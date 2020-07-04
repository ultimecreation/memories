<?php
class AuthModel extends Model
{
    public function isEmailTaken($email)
    {
        $req = $this->bdd->prepare('select * from users WHERE email=?');
        $req->execute(array($email));
        return $req->fetch();
    }

    public function saveUser($user){
        try{
            $this->bdd->beginTransaction();

            $req = $this->bdd->prepare("INSERT INTO users SET first_name=?, last_name=?, email=?, password=?");
            $req->execute(array($user->first_name,$user->last_name,$user->email,$user->password));

            $user_id = $this->bdd->lastInsertId();

            $req = $this->bdd->prepare("INSERT INTO user_roles SET user_id=?, role_id=?");
            $req->execute(array($user_id,1));

            $this->bdd->commit();
            return true;

        } catch(Exception $e){
            $this->bdd->rollback();
            return $e->getMessage();
        }
    }
}
