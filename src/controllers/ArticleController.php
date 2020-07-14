<?php

class ArticleController extends Controller{

    public function list()
    {
        $articles = $this->getModel('ArticleModel')->getAllArticles();
        $data['articles'] = $articles;
        debug($articles);
        return $this->renderView('article/list',$data);
    }

    public function getArticle(){
        $articleId = intval( getUriParts(2));
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $data['article'] = $article;
       
        return $this->renderView('article/single',$data);
    }
}