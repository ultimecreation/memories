<?php

class ArticleModel extends Model{

    public function getAllArticles(){
        $req = $this->bdd->query("
            SELECT 
                articles.id AS article_id,title,content,articles.created_at AS article_created_at,
                categories.id as category_id,
                categories.name AS cat_name,
                CONCAT(first_name,' ',last_name) AS author_name,
                email
            FROM articles
            JOIN categories ON categories.id=articles.category_id
            JOIN users ON users.id=articles.author_id
        ");
        $req->execute();
        return $res = $req->fetchAll();
    }
    public function getArticleById($article_id){
        $req = $this->bdd->prepare("
            SELECT 
                articles.id AS article_id,title,content,articles.created_at AS article_created_at,
                categories.id as category_id,
                categories.name AS cat_name,
                CONCAT(first_name,' ',last_name) AS author_name,
                email
            FROM articles
            JOIN categories ON categories.id=articles.category_id
            JOIN users ON users.id=articles.author_id
                WHERE articles.id=?
        ");
        $req->execute(array($article_id));
        return $res = $req->fetch();
    }

    public function getArticleByCategory($category_id){
        $req = $this->bdd->prepare("
            SELECT 
                articles.id AS article_id,title,content,articles.created_at AS article_created_at,
                categories.name AS cat_name,
                CONCAT(first_name,' ',last_name) AS author_name,
                email
            FROM articles
            JOIN categories ON categories.id=articles.category_id
            JOIN users ON users.id=articles.author_id
                WHERE categories.id=?
        ");
        $req->execute(array($category_id));
        return $res = $req->fetchAll();
    }
}