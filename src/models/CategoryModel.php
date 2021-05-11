<?php
declare(strict_types=1);

class CategoryModel extends Model{
    
    /**
     * getCategories
     *
     * @return array
     */
    public function getCategories() :array
    {
        $req = $this->bdd->query('SELECT * FROM categories');
        $req->execute();
        return $req->fetchAll();
    }
    
    /**
     * getCategoryById
     *
     * @param  int $cat_id
     * @return object
     */
    public function getCategoryById(int $cat_id) :object
    {
        $req = $this->bdd->prepare("SELECT * FROM categories WHERE id=?");
        $req->execute(array($cat_id));
        return $req->fetch();
    }
    
    /**
     * save
     *
     * @param  object $category
     * @return string
     */
    public function save(object $category) :string
    {
        $req = $this->bdd->prepare("
            INSERT INTO categories
            SET name = ?            
        ");
        $req->execute(array($category->name));

        return $this->bdd->lastInsertId();
    }
    
    /**
     * update
     *
     * @param  object $updatedCategory
     * @return bool
     */
    public function update(object $updatedCategory) :bool
    {
        $req = $this->bdd->prepare("
            UPDATE categories SET name=? WHERE id=?
        ");
        $req->execute(array($updatedCategory->name,$updatedCategory->id));
        return true;
    }    
    /**
     * delete
     *
     * @param  int $idToDelete
     * @return bool
     */
    public function delete(int $idToDelete) :bool
    {
        $req = $this->bdd->prepare("DELETE FROM categories WHERE id=?");
        $req->execute(array($idToDelete));
        return true;
    }
}