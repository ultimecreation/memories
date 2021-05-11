<?php
declare(strict_types=1);

class PurchaseModel extends Model{
    
    /**
     * save
     *
     * @param  object $purchase
     * @return bool
     */
    public function save(object $purchase) :bool
    {
        $req = $this->bdd->prepare("
            INSERT INTO user_purchases
            SET user_id=?, product_id=?,
                transaction_id=?, transaction_status=?,
                amount=?, created_at=?
        ");
        $req->execute(array(
            $purchase->user_id,$purchase->product_id,
            $purchase->transaction_id,$purchase->status,
            $purchase->amount, $purchase->created_at
        ));
        if($this->bdd->lastInsertId()){
            return true;
        }
        return false;
    }
}