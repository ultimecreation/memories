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
            ORDER BY articles.id DESC
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

    public function getArticlesBySearchQuery($term){
        $req = $this->bdd->prepare("
            SELECT 
                articles.id AS article_id,title,content,articles.created_at AS article_created_at,
                categories.name AS cat_name,
                CONCAT(first_name,' ',last_name) AS author_name,
                email
            FROM articles
            JOIN categories ON categories.id=articles.category_id
            JOIN users ON users.id=articles.author_id
                WHERE articles.title LIKE :needle OR articles.content LIKE :needle
        ");
       
        $req->execute(array(':needle' => "%$term%"));
        return $res = $req->fetchAll();
    }
    public function deleteArticle($id){
        $req = $this->bdd->prepare('DELETE FROM articles WHERE id=?');
        $res = $req->execute(array($id));
        if($res){
            return true;
        }
        return false;
    }
    public function save($article){
        $req = $this->bdd->prepare("
            INSERT INTO articles
            SET  category_id=?, title=?, content=?
        ");
        $req->execute(array($article->author_id,$article->category_id,$article->title,$article->content));
        $lastInsertId = $this->bdd->lastInsertId();
        return $lastInsertId;
    }
    public function update($updatedArticle){
        //debug($updatedArticle);die();
        $req = $this->bdd->prepare("
        UPDATE articles
        SET category_id=?, title=?, content=?
        WHERE id=?
        ");
        $res = $req->execute(array(
            $updatedArticle->category_id,
            $updatedArticle->title,
            $updatedArticle->content,
            $updatedArticle->article_id
        ));
        return $res;
    }
}