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
       $categories = $this->getModel('CategoryModel')->getCategories();

       $data['categories'] = $categories;
       
        return $this->renderView('admin/articles/create',$data);
    }
    public function edit()
    {
       if(!in_array('ADMIN',getUserData('roles'))){
           return redirectTo('/');
       }
       $articleId = intval( getUriParts(3));
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $categories = $this->getModel('CategoryModel')->getCategories();
       
        $data['article'] = $article;
        $data['categories'] = $categories;
       
        return $this->renderView('admin/articles/edit',$data);
    }
    public function delete()
    {
       if(!in_array('ADMIN',getUserData('roles'))){
           return redirectTo('/');
       }
       $idToDelete = intval($_POST['idToDelete']);
       $success = $this->getModel('ArticleModel')->deleteArticle( $idToDelete );

       if($success){
           setFlashMessage('success',"suppression réussie");
           return redirectTo('/admin/articles');
       }else{
            setFlashMessage('danger',"uen erreur inattendue s'est produite");
           return redirectTo('/admin/articles');
       }
       debug($idToDelete);die();
        return $this->renderView('admin/articles/delete');
    }
}
