<?php

loadController('AdminController');

require_once(BT_ROOT . "/private/libs/2checkout-php/lib/Twocheckout.php");

class AdminPurchaseController extends AdminController {

    public function indexAction(){

        $this->setVar("title","Purchase a Key");
        $this->render("admin/purchase/index");
    }

    public function buyAction(){

        //user id
        $mysql['user_id'] = DB::quote(getUserID());

        //setup the checkout parameters
        $args = array(
            'sid' => 1824492,
            'mode' => "2CO",
            'li_0_name' => $_POST['li_0_name'],
            'li_0_type' => $_POST['li_0_type'],
            'li_0_price' => $_POST['li_0_price'],
            'li_0_recurrence' => $_POST['li_0_recurrence'],
            'merchant_order_id' => $mysql['user_id']
        );

        //pass the buyer and the parameters to the checkout
        Twocheckout_Charge::redirect($args);

    }

    public function passbackAction(){

        //Assign the returned parameters to an array.
        $params = array();
        foreach ($_REQUEST as $k => $v) {
            $params[$k] = $v;
        }

        //Check the MD5 Hash to determine the validity of the sale.
        $passback = Twocheckout_Return::check($params, "tango", 'array');
        if($passback==null){
            $this->render("admin/purchase/failed");
            BTApp::end();
        }

        if ($passback['response_code'] == 'Success') {
            $id = $params['merchant_order_id'];
            $order_number = $params['order_number'];
            $invoice_id = $params['invoice_id'];
            $data = array(
                'active' => 1,
                'order_number' => $order_number,
                'last_invoice' => $invoice_id
            );
            //$this->ion_auth->update($id, $data);
            $this->setVar("message",$passback['response_message']);
            $this->render("admin/purchase/success");
        } else {
            $this->setVar("message",$passback['response_message']);
            $this->render("admin/purchase/failed");
        }
    }

    public function successAction(){
        $this->setVar("title","Success Transaction");
        $this->render("admin/purchase/success");
    }

    public function failedAction(){
        $this->setVar("title","Failed Transaction");
        $this->render("admin/purchase/failed");
    }

}