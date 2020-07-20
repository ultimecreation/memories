<?php 

class AdminCategoriesController extends Controller{
    public function list(){
        echo "admin categories list";
    }

    public function create(){
        echo "admin category create";
    }

    public function edit(){
        echo "admin category edit ".getUriParts(3);
    }

    public function delete(){
        echo "admin category delete";
    }

    public function bindCategory(){

    }
    public function validateSubmittedCategory(){

    }
}