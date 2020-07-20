<?php

class ArticleModel extends Model{
    
    /**
     * getAllArticles
     *
     * @param  mixed $start
     * @param  mixed $perPage
     * @return object
     */
    public function getAllArticles($start=null,$perPage=null){
        if($start==null && $perPage==null){
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
        }else{
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
                ORDER BY articles.id DESC
                LIMIT ?,?
            ");
           $req->bindValue(1,$start,PDO::PARAM_INT);
           $req->bindValue(2,$perPage,PDO::PARAM_INT);
            $req->execute();
        }
        return $res = $req->fetchAll();
    }    
    /**
     * getArticleById
     *
     * @param  mixed $article_id
     * @return object
     */
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
    
    /**
     * getArticleByCategory
     *
     * @param  mixed $category_id
     * @return void
     */
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
    
    /**
     * getArticlesBySearchQuery
     *
     * @param  mixed $term
     * @return object
     */
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
    /**
     * deleteArticle
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteArticle($id){
        $req = $this->bdd->prepare('DELETE FROM articles WHERE id=?');
        $res = $req->execute(array($id));
        if($res){
            return true;
        }
        return false;
    }    
    /**
     * save
     *
     * @param  mixed $article
     * @return void
     */
    public function save($article){
        $req = $this->bdd->prepare("
            INSERT INTO articles
            SET  category_id=?, title=?, content=?
        ");
        $req->execute(array($article->author_id,$article->category_id,$article->title,$article->content));
        $lastInsertId = $this->bdd->lastInsertId();
        return $lastInsertId;
    }    
    /**
     * update
     *
     * @param  mixed $updatedArticle
     * @return void
     */
    public function update($updatedArticle){
        //locadebug($updatedArticle);die();
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