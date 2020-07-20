<?php

class CategoryModel extends Model{
    
    /**
     * getCategories
     *
     * @return void
     */
    public function getCategories(){
        $req = $this->bdd->query('SELECT * FROM categories');
        $req->execute();
        $res = $req->fetchAll();
        return $res;
    }
}