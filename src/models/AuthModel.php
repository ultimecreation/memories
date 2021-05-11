<?php

declare(strict_types=1);

class AuthModel extends Model
{

    /**
     * isEmailTaken
     *
     * @param  string $email
     */
    public function isEmailTaken(string $email)
    {
        $req = $this->bdd->prepare('select * from users WHERE email=?');
        $req->execute(array($email));
        return $req->fetch();
    }

    /**
     * saveUser
     *
     * @param  object $user
     * @return bool
     */
    public function saveUser(object $user): bool
    {
        try {
            // strat transaction
            $this->bdd->beginTransaction();

            // insert the new user, and get the last inserted id
            $req = $this->bdd->prepare("INSERT INTO users SET first_name=?, last_name=?, email=?, password=?");
            $req->execute(array($user->first_name, $user->last_name, $user->email, $user->password));
            $user_id = $this->bdd->lastInsertId();

            // save the user role 1 is equal to role USER
            $req = $this->bdd->prepare("INSERT INTO user_roles SET user_id=?, role_id=?");
            $req->execute(array($user_id, 1));

            // all is ok, commit the transaction
            $this->bdd->commit();
            return true;

        } catch (Exception $e) {
            // something went wrong, roll back and display error message
            $this->bdd->rollback();
            echo $e->getMessage();
            return false;
        }
    }


    /**
     * getUserByEmail
     *
     * @param  string $email
     * @return object
     */
    public function getUserByEmail(string $email): object
    {
        // get user and his/her points
        $req = $this->bdd->prepare("
                SELECT 
                    id,CONCAT(first_name,' ',last_name) AS username,
                    email,password,total_points
                FROM users 
                JOIN user_points ON users.id=user_points.user_id
                WHERE email=?");
        $req->execute(array($email));
        $user = $req->fetch();

        // get his/her role
        $req = $this->bdd->prepare("
            SELECT name FROM user_roles
            JOIN roles ON user_roles.role_id=roles.id
            WHERE user_roles.user_id=?
            
        ");
        $req->execute(array($user->id));
        $roles = $req->fetchAll();
        // create empty role array to fill
        $user->roles = [];
        foreach ($roles as $role) {
            // push roles onto the roles array
            array_push($user->roles, $role->name);
        }

        return $user;
    }

    /**
     * saveLastLoginAt
     *
     * @param  string $id
     * @return void
     */
    public function saveLastLoginAt(string $id): void
    {
        $req = $this->bdd->prepare("
            UPDATE users 
            SET last_login_at=NOW()
            WHERE id=?
        ");
        $req->execute(array($id));
    }
}
