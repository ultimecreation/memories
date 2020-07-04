<?php
class HomeController extends Controller
{
    public function index()
    {
        $data = array(
            'template' => 'custom'
        );
        return $this->renderView('home/index');
    }
}
