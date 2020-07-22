<?php

class UserModel extends Model{

    public function getAllUsers($start=null, $perPage=null){
       if($start==null && $perPage==null){
            $req = $this->bdd->prepare("
                SELECT * FROM users
                ORDER BY id DESC;
            ");
       }
       else{
            $req = $this->bdd->prepare("
            SELECT * FROM users 
            ORDER BY id DESC
            LIMIT ?,? ;
            ");
            $req->bindValue(1,$start,PDO::PARAM_INT);
            $req->bindValue(2,$perPage,PDO::PARAM_INT);
       }

        $req->execute();
        $users = $req->fetchAll();
        foreach($users as $user){
            $req = $this->bdd->prepare("
                SELECT roles.name
                FROM roles
                LEFT JOIN user_roles ON user_roles.role_id=roles.id
                WHERE user_roles.user_id=?
            ");
            $req->execute(array($user->id));
            $roles = $req->fetchAll();
            $user->roles = $roles;
        }
       
        return $users;

    }

    public function getUserById(){

    }
     
    public function updateUser(){

    }

    public function deleteUser(){

    }
}