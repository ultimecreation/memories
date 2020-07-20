<?php

class AdminUsersController extends Controller{
    public function list(){
        echo "admin users list";
    }

    public function show(){
        echo "admin users show ".getUriParts(3);
    }

    public function edit(){
        echo "admin users edit ".getUriParts(3);
    }

    public function delete(){
        echo "admin delete user";
    }

    public function bindUser(){
        echo "admin bind user";
    }
    public function validateSubmittedUser(){
        echo "admin validate submitted user";
    }
}