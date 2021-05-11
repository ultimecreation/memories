<?php
declare(strict_types=1);

class PurchasesController extends Controller
{

    /**
     * save
     *
     * @return void
     */
    public function save(): void
    {
        // get incoming data
        $data = json_decode(file_get_contents('php://input'));

        // create emty purchase object and fill it with incoming data
        $purchase = new stdClass;
        $purchase->user_id = $data->user_id;
        $purchase->product_id = $data->purchase_units[0]->reference_id;
        $purchase->transaction_id = $data->id;
        $purchase->status = $data->purchase_units[0]->payments->captures[0]->status;
        $purchase->amount = $data->purchase_units[0]->amount->value;
        $purchase->created_at = $data->purchase_units[0]->payments->captures[0]->create_time;

        // save purchase
        $success = $this->getModel('PurchaseModel')->save($purchase);
        if ($success === true) {
            $user = getUserData();
            $user->total_points = $data->total_points;
            // all is ok, retrieve user points and update them
            $data = $this->getModel('UserModel')->getUserPoints(getUserData('id'));
            setUserData((object) $user);

            // echo success feedback
            echo json_encode(array("success", $purchase, $data->total_points));
        } else {
            // something went wrong, echo error message
            echo json_encode(array('message' => "Une erreur inattendue est survenue"));
        }

        exit;
    }
}
