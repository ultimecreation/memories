<?php

// $data = array(
//     'template' => 'custom'
// );


class AdminArticlesController extends Controller
{
    private $errors = [];
    
    public function list()
    {
       if(!in_array('ADMIN',getUserData('roles'))){
           return redirectTo('/');
       }
       $page = getUriParts(3) ?? 1;
       $perPage = intval($_SESSION['per_page'] ?? 5);
       $totalNbOfArticles = count($this->getModel('ArticleModel')->getAllArticles());
       $nbOfPages = ceil($totalNbOfArticles/$perPage) ;
       $start = intval(($page-1)*$perPage);
       $pagination = new StdClass();
       if($page>1){
           $pagination->previous_page = $page-1??'';
       }
       if($page<$nbOfPages){
           $pagination->next_page = $page+1??'';
       }
       $pagination->current_page = $page;
       $data['pagination'] = $pagination;
       debug(array(
        'page'=>$page,
        'perPage' => $perPage,
        'totalNbOfArticles'=> $totalNbOfArticles,
        'nbOfPages'=> $nbOfPages
       ));
      // debug(array($start,$perPage));die();
       $articles = $this->getModel('ArticleModel')->getAllArticles($start,$perPage);
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

       if($_SERVER['REQUEST_METHOD'] ==='POST'){

            $article = $this->bindArticle($_POST);
            $this->validateSubmittedArticle($article);
            if(!empty($this->errors)){
                $data['article'] = $article;
                $data['errors'] = $this->errors; debug($this->errors);die();
                return $this->renderView('admin/articles/create',$data);
            }
            if(empty($this->errors)){ 
               
                $article->author_id = intval(getUserData('id'));
                
                $lastInsertId = $this->getModel('ArticleModel')->save($article);
                if($lastInsertId){
                   setFlashMessage('success',"article créé avec succès");
                    return redirectTo('/admin/articles');
                    
                }else{
                    setFlashMessage('danger',"une erreur inattendue est survenue");
                    return redirectTo('/admin/articles');
                }
               
            }
       }
    
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

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $updatedArticle = $this->bindArticle($_POST,'update');
            $this->validateSubmittedArticle($article);
          
            if(!empty($this->errors)){
                $data['article'] = $article;
                $data['errors'] = $this->errors;
                return $this->renderView('admin/articles/edit',$data);
            }
            if(empty($this->errors)){ 

                $success = $this->getModel('ArticleModel')->update($updatedArticle);

                if($success){
                    setFlashMessage('success',"article modifié avec succès");
                    return redirectTo("/admin/articles/voir-details/{$updatedArticle->article_id}");
                    
                }else{
                    setFlashMessage('danger',"une erreur inattendue est survenue");
                    return redirectTo('/admin/articles');
                }               
            }
        }
       
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
            setFlashMessage('danger',"une erreur inattendue s'est produite");
           return redirectTo('/admin/articles');
       }
       debug($idToDelete);die();
        return $this->renderView('admin/articles/delete');
    }
    public function validateSubmittedArticle($article){
       

        if(empty($article->title)){
            $this->errors['title'] = "Le titre est requis";
        }
        if(empty($article->category_id)){
            $this->errors['category_id'] = "La catégorie est requise";
        }
        if(empty($article->content)){
            $this->errors['content'] = "Le contenu est requis";
        }
        return $this->errors;
    }
    public function bindArticle($array,$context=null){
        
        $title = htmlspecialchars(strip_tags($_POST['title'])) ?? '';
        $category_id = htmlspecialchars(strip_tags($_POST['category_id'])) ?? '';
        $content = htmlspecialchars(strip_tags($_POST['content'])) ?? '';

        $article = new StdClass();
        $article->title = $title;
        $article->category_id = $category_id;
        $article->content = $content;
        if($context==='update'){
            $article->article_id = htmlspecialchars(strip_tags($_POST['article_id'])) ?? '';
        }
        return $article;
    }
}