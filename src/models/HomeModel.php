<?php
class HomeModel extends Model
{
    public function getAllUsers()
    {
        $req = $this->bdd->prepare('select * from users');
        $req->execute();
        return $req->fetchAll();
    }
}
