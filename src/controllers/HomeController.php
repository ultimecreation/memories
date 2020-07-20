<?php

// $data = array(
//     'template' => 'custom'
// );


class HomeController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
       
        return $this->renderView('pages/index');
    }
}
