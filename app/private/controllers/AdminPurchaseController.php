<?php

loadController('AdminController');

class AdminPurchaseController extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->loadModel('SettingsModel');
    }

    public function indexAction(){
        $this->setVar("title","Purchase an Api Key");
        $this->render("admin/purchase/index");
    }

    public function buyAction(){

        //user id
        $mysql['user_id'] = DB::quote(getUserID());

        //setup the checkout parameters
        $args = array(
            'sid' => 1824492,
            'mode' => "2CO",
            'li_0_name' => "Advance Redirect",
            'li_0_type' => "Professional",
            'li_0_price' => "5000",
            'li_0_recurrence' => "1 Month",
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

        $key = generateKey();

        $error = array();
        $settings = new SettingsModel();
        $settings->useRuleSet('new');
        $settings->pass_key = $key;
        $settings->domain = $_POST['domain'];
        $settings->type = "Advance Redirect";
        $settings->recurrence = 1;

        if($settings->save()) {
            sendEmailGenerateKey("barcelona23@gmail.com",$key,$params);
        }else {
            $error = $settings->getErrors();
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

        $this->setVar("error",$error);
    }

    public function successAction(){
        $this->setVar("title","Success Transaction");
        /*
        $to = "me@adriancr.com";
        $subject = "Ballistic Tracking API KEY";
        $message = "<p>Key: 111111111</p>";

        //$from = "ballistictracking@".$_SERVER['SERVER_NAME'];
        $from = "adrian.buy@gmail.com";

        $header = "From: Ballistic Tracking<" . $from . "> \r\n";
        $header .= "Reply-To: ".$from." \r\n";
        $header .=  "To: " . $to . " \r\n";
        $header .= "Content-Type: text/html; charset=\"iso-8859-1\" \r\n";
        $header .= "Content-Transfer-Encoding: 8bit \r\n";
        $header .= "MIME-Version: 1.0 \r\n";

        mail($to,$subject,$message,$header);
        */

        $headers = "From: cv@adriancr.com" . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $emailbody = "<p>You have recieved a new message from the enquiries form on your website.</p>
                  <p><strong>Name: </strong>  </p>
                  <p><strong>Email Address: </strong>  </p>
                  <p><strong>Message: </strong>  </p>
                  <p>This message was sent from the IP Address: </p>";
        mail("cv@adriancr.com","Contact-Me Form",$emailbody,$headers);

        $this->setVar("message","enviado.");
        $this->render("admin/purchase/success");
    }

    public function failedAction(){
        $this->setVar("title","Failed Transaction");
        $this->render("admin/purchase/failed");
    }

}