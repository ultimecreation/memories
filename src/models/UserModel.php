<?php
declare(strict_types=1);

class UserModel extends Model{
    
    /**
     * getAllUsers
     *
     * @param  int $start
     * @param  int $perPage
     * @return array
     */
    public function getAllUsers(int $start=null, int $perPage=null) :array
    {
        // no pagination need ,retrieve all data
       if($start==null && $perPage==null){
            $req = $this->bdd->prepare("
                SELECT * FROM users
                ORDER BY id DESC;
            ");
       }

       // a pagination is requested,retrieve a chunk of data
       else
       {
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


        foreach($users as $user)
        {
            // retrieve user roles and bind them 
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
     
    /**
     * getUserById
     *
     * @param  string $userId
     * @return object
     */
    public function getUserById(string $userId) :object
    {
        
        $req = $this->bdd->prepare("
            SELECT * from users 
            WHERE id=?;
        ");
        $req->execute(array($userId));
        $user = $req->fetch();
  
        // user is found
        if($user)
        {
            // retrieve his/her roles
            $req = $this->bdd->prepare("
                SELECT * FROM user_roles
                JOIN roles ON user_roles.role_id=roles.id
                WHERE user_id=?
            ");
            $req->execute(array($user->id));
            $roles = $req->fetchAll();
            $user->roles = $roles;
        }
      
        return $user;

    }    
    /**
     * updateByAdmin
     *
     * @param  object $user
     * @return bool
     */
    public function updateByAdmin(object $user) :bool
    {
        try{
            // start tarnsaction
            $this->bdd->beginTransaction();

                // update the user
                $req = $this->bdd->prepare("
                    UPDATE users 
                    SET first_name=?, last_name=?, email=?
                    WHERE id=?
                ");
                $req->execute(array($user->first_name,$user->last_name,$user->email,$user->id));

                // delete all roles related to the user before setting up the new role(s)
                $req = $this->bdd->prepare("DELETE FROM user_roles WHERE user_id=? ");
                $req->execute(array($user->id));

                foreach($user->roles as $role)
                {
                    // insert one by one the new roles
                    $req = $this->bdd->prepare("
                            INSERT INTO user_roles 
                            SET user_id=?,role_id=?
                        ");
                    $req->execute(array($user->id,$role));
                }

                // all is ok, commit the transaction
                $this->bdd->commit();
                return true;

        }
        catch(Exception $e)
        {
            //something went wrong, roll back the changes
            $this->bdd->rollback();
            echo $e->getMessage();
            return false;
        }
    }
    
    /**
     * updateUser
     *
     * @param  string $first_name
     * @param  string $last_name
     * @param  string $id
     * @return bool
     */
    public function updateUser(string $first_name,string $last_name,string $id) :bool
    {
        $req = $this->bdd->prepare("
            UPDATE users 
            SET first_name=?, last_name=?
            WHERE id=?
        ");
        $req->execute(array($first_name,$last_name,$id));
        return true;
    }
    
    /**
     * updatePassword
     *
     * @param  string $password
     * @param  string $id
     * @return bool
     */
    public function updatePassword(string $password,string $id) :bool
    {
        $req = $this->bdd->prepare("
            UPDATE users 
            SET password=?
            WHERE id=?
        ");
        $req->execute(array($password,$id));
        return true;
    }    

    /**
     * deleteUser
     *
     * @param  int $idToDelete
     * @return bool
     */
    public function deleteUser(string $idToDelete) :bool
    {
        $req = $this->bdd->prepare("DELETE FROM users WHERE id=?");
        $req->execute(array($idToDelete));
        return true;
    }
    
    /**
     * getMostActiveUsers
     *
     * @param  int $limit
     * @return array
     */
    public function getMostActiveUsers(int $limit) :array
    {
        $req = $this->bdd->prepare("
            SELECT 
                CONCAT(users.first_name,' ',users.last_name) AS username, 
                COUNT(user_id) AS vote_number
            FROM user_notes
            LEFT JOIN users ON user_notes.user_id=users.id
            GROUP BY user_id
            ORDER BY vote_number DESC
            LIMIT ?
        ");
        $req->bindParam(1,$limit,PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll();
    }
    
    /**
     * getUserActions
     *
     * @param  string $user_id
     * @param  int $max
     * @param  int $offset
     * @return array
     */
    public function getUserActions(string $user_id=null,int $max=null,int $offset=0) :array
    {
        // call the stored procedure
        $req= $this->bdd->prepare("call getUserActions(?,?,?)");
        $req->bindValue(1,$user_id,PDO::PARAM_STR);
        $req->bindValue(2,$max,PDO::PARAM_INT);
        $req->bindValue(3,$offset,PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll();
    }
    
    /**
     * getUserPoints
     *
     * @param  string $userId
     * @return object
     */
    public function getUserPoints(string $userId) :object
    {
        $req= $this->bdd->prepare("
            SELECT total_points FROM user_points
            WHERE user_id=?
        ");
        $req->execute(array($userId));
        return $req->fetch();
    }
}