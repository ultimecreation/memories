<?php
class HomeController extends Controller
{
    public function index()
    {

        echo SiteUrl('assets/css/style.css');
        echo "<br>";
        debug(currentUrl());

        // $data = array(
        //     'template' => 'custom',
        //     'users' => $this->getModel('homeModel')->getAllUsers(),
        // );
        // return $this->renderView('home/home', $data);
    }
}
