<?php

class CategoryModel extends Model{

    public function getCategories(){
        $req = $this->bdd->query('SELECT * FROM categories');
        $req->execute();
        $res = $req->fetchAll();
        return $res;
    }
}