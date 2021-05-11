<?php
declare(strict_types=1);

class ArticleModel extends Model
{    
    /**
     * getAllArticles
     *
     * @param  int $start can be null
     * @param  int $perPage can be null
     * @return array
     */
    public function getAllArticles(?int $start = null,?int $perPage = null) :array
    {
        // no params received, return all data
        if ($start == null && $perPage == null) {
            $req = $this->bdd->query("
            SELECT 
                articles.id AS article_id,title,content,
                articles.created_at AS article_created_at,
                articles.published,
                categories.id as category_id,
                categories.name AS cat_name,
                CONCAT(first_name,' ',last_name) AS author_name,
                email
            FROM articles
            JOIN categories ON categories.id=articles.category_id
            JOIN users ON users.id=articles.author_id
            WHERE published=true 
            ORDER BY articles.id DESC
            ");

            $req->execute();
        } 

        // $start and $perpage are set, return a chunk of data
        else 
        {
            $req = $this->bdd->prepare("
                SELECT 
                    articles.id AS article_id,title,content,
                    articles.created_at AS article_created_at,
                    articles.published,
                    categories.id as category_id,
                    categories.name AS cat_name,
                    CONCAT(first_name,' ',last_name) AS author_name,
                    email
                FROM articles
                JOIN categories ON categories.id=articles.category_id
                JOIN users ON users.id=articles.author_id
                WHERE published=true
                ORDER BY articles.id DESC
                LIMIT ?,?
            ");

            // bind params
            $req->bindValue(1, $start, PDO::PARAM_INT);
            $req->bindValue(2, $perPage, PDO::PARAM_INT);
            $req->execute();
        }

        // fetch data
        $articles = $req->fetchAll();
        foreach ($articles as $article) 
        {
            // foreach article,retrieve average note value for each article
            $article->notes = $this->getNotesByArticleId((int) $article->article_id);
        }
        return $articles;
    }
    
    /**
     * getNotesByArticleId
     *
     * @param  int $article_id
     * @return object
     */
    public function getNotesByArticleId(int $article_id) :object
    {
        $req = $this->bdd->prepare("
            SELECT 
                COUNT(article_id) AS note_number,
                ROUND(AVG(note)) AS avg_rating
            FROM user_notes
            WHERE article_id=?
        ");
        $req->execute(array($article_id));
        $res = $req->fetch();

        return $res;
    }    
    /**
     * getAllArticlesByAuthorId
     *
     * @param  string $authorId
     * @param  int $start can be null
     * @param  int $perPage can be null
     * @return array
     */
    public function getAllArticlesByAuthorId(string $authorId,?int $start=null,?int $perPage=null) :array
    {
        // $start and $perpage are set, return a chunk of articles
        if ($start == null && $perPage == null ) 
        {
            $req = $this->bdd->prepare("
            SELECT 
                articles.id AS article_id,title,content,
                articles.created_at AS article_created_at,articles.published,
                categories.id as category_id,
                categories.name AS cat_name,
                CONCAT(first_name,' ',last_name) AS author_name,
                email
            FROM articles
            JOIN categories ON categories.id=articles.category_id
            JOIN users ON users.id=articles.author_id
            WHERE articles.author_id=?
            ORDER BY articles.id DESC
            ");

            // bind value and execute the request
            $req->bindValue(1, $authorId, PDO::PARAM_INT);
            $req->execute();
        } 

        // no pagination needed, return all results
        else 
        {
            $req = $this->bdd->prepare("
                SELECT 
                    articles.id AS article_id,title,content,
                    articles.created_at AS article_created_at,articles.published,
                    categories.id as category_id,
                    categories.name AS cat_name,
                    CONCAT(first_name,' ',last_name) AS author_name,
                    email
                FROM articles
                JOIN categories ON categories.id=articles.category_id
                JOIN users ON users.id=articles.author_id
                WHERE articles.author_id=? 
                ORDER BY articles.id DESC
                LIMIT ?,?
            ");

            // bind values and execute the request
            $req->bindValue(1, $authorId, PDO::PARAM_INT);
            $req->bindValue(2, $start, PDO::PARAM_INT);
            $req->bindValue(3, $perPage, PDO::PARAM_INT);
            $req->execute();
        }

        
        $articles = $req->fetchAll();
        foreach ($articles as $article) 
        {
            // foreach article, get the averag note
            $article->notes = $this->getNotesByArticleId((int) $article->article_id);
        }
        return $articles;

    }
    
    /**
     * getUnpublishedArticles
     *
     * @return array
     */
    public function getUnpublishedArticles() :array
    {
        $req = $this->bdd->prepare("
            SELECT * FROM articles 
            WHERE published=?
            
        ");
        // get records with 0 which is equal to awaiting for approval
        $req->execute(array(0));
        return $req->fetchAll();
    }  

    /**
     * getLastArticles
     *
     * @param  int $articleNumber
     * @return array
     */
    public function getLastArticles(int $articleNumber) :array
    {
        // call the stored procedure, bind value, execute and get the results
        $req = $this->bdd->prepare("call getLastArticles(?)");
        $req->bindParam(1, $articleNumber, PDO::PARAM_INT);
        $req->execute();
        return  $req->fetchAll();
    }
      
    /**
     * getArticleById
     *
     * @param  int $article_id
     * @return object
     */
    public function getArticleById(int $article_id) :object
    {
        $req = $this->bdd->prepare("
            SELECT 
                articles.id AS article_id,title,content,
                articles.created_at AS article_created_at,
                articles.published,articles.author_id,
                categories.id as category_id,
                categories.name AS cat_name,
                CONCAT(first_name,' ',last_name) AS author_name,
                img,
                email
            FROM articles
            JOIN categories ON categories.id=articles.category_id
            JOIN users ON users.id=articles.author_id
                WHERE articles.id=?
        ");
        $req->execute(array($article_id));
        return $req->fetch();
       
    }

    
    
        
    /**
     * getArticlesSortedBy
     *
     * @param  string $orderBy
     * @param  string $highLow
     * @param  int $limit
     * @return array
     */
    public function getArticlesSortedBy(string $orderBy,string $highLow,int $limit) :array
    {
        $sql = sprintf(
            "
            SELECT 
                DISTINCT user_notes.article_id,
                COUNT(user_notes.article_id) AS note_number,
                ROUND(AVG(user_notes.note)) AS avg_rating,
                articles.title,
                articles.content,
                articles.img
            FROM user_notes
            JOIN articles ON user_notes.article_id=articles.id
            WHERE published=true
            GROUP BY article_id
            ORDER BY %s %s
            LIMIT %d
        ",
            $orderBy,
            $highLow,
            $limit
        );
        $req = $this->bdd->prepare($sql);

        $req->execute();
        return  $req->fetchAll();
    }

    /**
     * getArticleByCategory
     *
     * @param  int $category_id
     * @return array
     */
    public function getArticleByCategory(int $category_id) :array
    {
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
        return  $req->fetchAll();
    }
    
    /**
     * saveArticleNote
     *
     * @param  object $tmpNote
     * @return bool
     */
    public function saveArticleNote(object $tmpNote) :bool
    {
        // check a note has already been submitted for the current user
        $req = $this->bdd->prepare("
            SELECT * FROM user_notes WHERE user_id=? AND article_id=?
        ");
        $req->execute(array($tmpNote->user_id, $tmpNote->article_id));
        $noteExists = $req->fetch();

        // a note is already stored
        if ($noteExists) 
        {
            // the user is not allowed to register multiple notes for the same article
            return false;
        } 

        // no note registered for this article
        else 
        {
            // save the note
            $req = $this->bdd->prepare("
            INSERT INTO user_notes SET user_id=?,article_id=?,note=?
            ");
            $req->execute(array($tmpNote->user_id, $tmpNote->article_id, $tmpNote->note));
            return true;
        }
    }

        
    /**
     * getArticlesBySearchQuery
     *
     * @param  string $term
     * @return array
     */
    public function getArticlesBySearchQuery(string $term) :array
    {
        $req = $this->bdd->prepare("
            SELECT 
                articles.id AS article_id,title,content,articles.created_at AS article_created_at,
                categories.name AS cat_name,
                CONCAT(first_name,' ',last_name) AS author_name,
                email
            FROM articles
            JOIN categories ON categories.id=articles.category_id
            JOIN users ON users.id=articles.author_id
            WHERE published=true
                AND articles.title LIKE :needle 
                OR articles.content LIKE :needle
        ");
        // use the wildcard for LIKE here
        $req->execute(array(':needle' => "%$term%"));
        return  $req->fetchAll();
    }

    /**
     * deleteArticle
     *
     * @param  int $id
     * @return bool
     */
    public function deleteArticle(int $id) :bool
    {
        $req = $this->bdd->prepare('DELETE FROM articles WHERE id=?');
        $res = $req->execute(array($id));
        if ($res) 
        {
            return true;
        }
        return false;
    }
    /**
     * save
     *
     * @param  object $article
     * @return string
     */
    public function save(object $article) :string
    {
        $req = $this->bdd->prepare("
            INSERT INTO articles
            SET  author_id=?,category_id=?, title=?, content=?,img=?,published=?
        ");
        $req->execute(array(
            $article->author_id,
            $article->category_id,
            $article->title,
            $article->content,
            $article->img,
            $article->published
        ));
       
        return $this->bdd->lastInsertId();
    }
        
    /**
     * update
     *
     * @param  object $updatedArticle
     * @return bool
     */
    public function update(object $updatedArticle) :bool
    {
        // an article has been approved with the flag 1
        if($updatedArticle->published == 1)
        {
            // start the transaction
            $this->bdd->beginTransaction();

            // get points for this user
            $req = $this->bdd->prepare("
                SELECT * FROM user_points
                WHERE user_id=?
            ");
            $req->execute(array($updatedArticle->author_id));
            $user = $req->fetch();

            // this user has enough points
            if(($user->total_points - 100) > 0)
            {
                // set new total_points
                $new_total_points = $user->total_points - 100;

                // update user total points count
                $req = $this->bdd->prepare("
                    UPDATE user_points
                    SET total_points=?
                    WHERE user_id=?
                ");
                $req->execute(array($new_total_points,$updatedArticle->author_id));

                //register this in user_actions table
                $req = $this->bdd->prepare("
                    INSERT INTO user_actions
                    SET user_id=?, article_id=?, action=?
                ");
                $req->execute(array(
                    $updatedArticle->author_id,
                    $updatedArticle->article_id,
                    "publication d'un article"
                ));

                // update article and its status to published
                $req = $this->bdd->prepare("
                    UPDATE articles
                    SET category_id=?, title=?, content=?,img=?,published=?
                    WHERE id=?
                    ");
                        $req->execute(array(
                        $updatedArticle->category_id,
                        $updatedArticle->title,
                        $updatedArticle->content,
                        $updatedArticle->img,
                        $updatedArticle->published,
                        $updatedArticle->article_id
                    ));
                
                    // commit the transaction
                $this->bdd->commit();
                return true;

            } 
            else
            {
                // the user does not have enough points, roll back
                $this->bdd->rollback();
                return false;
            }     
        }

        // the user does request the article to be published 
        else if($updatedArticle->published !== true)
        {
            // just save it
            $req = $this->bdd->prepare("
            UPDATE articles
            SET category_id=?, title=?, content=?,img=?,published=?
            WHERE id=?
            ");
             $req->execute(array(
                $updatedArticle->category_id,
                $updatedArticle->title,
                $updatedArticle->content,
                $updatedArticle->img,
                $updatedArticle->published,
                $updatedArticle->article_id
            ));
            return true;
        }
       
    }
}
