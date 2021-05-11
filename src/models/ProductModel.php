<?php
declare(strict_types=1);

class ProductModel extends Model{
    
    /**
     * getAll
     *
     * @return array
     */
    public function getAll() :array
    {
        $req = $this->bdd->query("SELECT * FROM products");
        return  $req->fetchAll();
    }    
    /**
     * getAllByCategory
     *
     * @param  string $category
     * @return array
     */
    public function getAllByCategory(string $category) :array
    {
        $req = $this->bdd->prepare("
            SELECT 
                products.id,products.name,products.value,products.price
            FROM products
            JOIN product_types ON products.product_type_id=product_types.id
            WHERE product_types.name=?;
        ");
        $req->execute(array($category));
        return  $req->fetchAll();
    }
}