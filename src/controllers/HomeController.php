<?php

// $data = array(
//     'template' => 'custom'
// );


class HomeController extends Controller
{
    public function index()
    {
       
        return $this->renderView('pages/index');
    }
}
