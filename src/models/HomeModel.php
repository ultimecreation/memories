<?php
declare(strict_types=1);

class HomeModel extends Model
{    
    /**
     * getAllUsers
     *
     * @return array
     */
    public function getAllUsers() :array
    {
        $req = $this->bdd->prepare('select * from users');
        $req->execute();
        return $req->fetchAll();
    }
}
