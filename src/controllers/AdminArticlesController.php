<?php

// $data = array(
//     'template' => 'custom'
// );


class AdminArticlesController extends Controller
{
    
    public function list()
    {
       if(!in_array('ADMIN',getUserData('roles'))){
           return redirectTo('/');
       }

       $articles = $this->getModel('ArticleModel')->getAllArticles();
       $data['articles'] = $articles;
       
        return $this->renderView('admin/articles/list',$data);
    }

    public function show(){
        $articleId = intval( getUriParts(3));
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $data['article'] = $article;
       
        return $this->renderView('/admin/articles/show',$data);
    }

    public function create()
    {
       if(!in_array('ADMIN',getUserData('roles'))){
           return redirectTo('/');
       }
        return $this->renderView('admin/articles/create');
    }
    public function edit()
    {
       if(!in_array('ADMIN',getUserData('roles'))){
           return redirectTo('/');
       }
       $articleId = intval( getUriParts(3));
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $data['article'] = $article;
        debug($article);die();
        return $this->renderView('admin/articles/edit');
    }
    public function delete()
    {
       if(!in_array('ADMIN',getUserData('roles'))){
           return redirectTo('/');
       }
       $idToDelete = intval($_POST['idToDelete']);
       debug($idToDelete);die();
        return $this->renderView('admin/articles/delete');
    }
}
