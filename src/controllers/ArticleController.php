<?php

class ArticleController extends Controller{

    public function list()
    {
        $articles = $this->getModel('ArticleModel')->getAllArticles();
        $data['articles'] = $articles;
        return $this->renderView('article/list',$data);
    }

    public function getArticle(){
        $articleId = intval( getUriParts(2));
        $article = $this->getModel('ArticleModel')->getArticleById($articleId);
        $data['article'] = $article;
       
        return $this->renderView('article/single',$data);
    }

    public function getArticlesByCategory(){
        $categoryId = intval( getUriParts(2));
        $articles = $this->getModel('ArticleModel')->getArticleByCategory($categoryId);
        $data['articles'] = $articles;
        $data['cat_name'] = $articles[0]->cat_name;
        return $this->renderView('article/category',$data);
    }
}