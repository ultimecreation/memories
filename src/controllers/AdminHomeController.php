<?php

// $data = array(
//     'template' => 'custom'
// );


class AdminHomeController extends Controller
{
    public function index()
    {
       if(!in_array('ADMIN',getUserData('roles'))){
           return redirectTo('/');
       }
        return $this->renderView('admin/index');
    }
}
