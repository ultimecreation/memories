<?php
declare(strict_types=1);

class RoleModel extends Model{
    
    /**
     * getAll
     *
     * @return array
     */
    public function getAll() :array
    {
        $req = $this->bdd->query("SELECT * FROM roles");
        return $req->fetchAll();
    }
}