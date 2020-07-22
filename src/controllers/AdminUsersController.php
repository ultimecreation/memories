<?php

class AdminUsersController extends Controller{
    public function list(){
        $users = $this->getModel('UserModel')->getAllUsers();

        $page = getUriParts(3) ?? 1;
       $perPage = intval($_SESSION['per_page'] ?? 5);
       $totalNbOfUsers = count($this->getModel('UserModel')->getAllUsers());
       $nbOfPages = ceil($totalNbOfUsers/$perPage) ;
       $start = intval(($page-1)*$perPage);
       $pagination = new StdClass();
       if($page>1){
           $pagination->previous_page = $page-1??'';
       }
       if($page<$nbOfPages){
           $pagination->next_page = $page+1??'';
       }
       $pagination->total_pages = $nbOfPages;
       $pagination->current_page = $page;
       $data['pagination'] = $pagination;
    
       $users =  $this->getModel('UserModel')->getAllUsers($start,$perPage);



       $data['users'] = $users;
        return $this->renderView('admin/users/list',$data);
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