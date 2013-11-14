<?php

function generateKey(){
    return md5(microtime().rand());
}

function sendEmailGenerateKey($emailTo,$key){
    //now email the user the script to reset their email
    $to = $emailTo;
    $subject = "Ballistic Tracking KEY";
    $message = "
		<p>Use this key.</p>
		<p>Key: ".$key."</p>";

    $from = "ballistictracking@".$_SERVER['SERVER_NAME'];

    $header = "From: Ballistic Tracking<" . $from . "> \r\n";
    $header .= "Reply-To: ".$from." \r\n";
    $header .=  "To: " . $to . " \r\n";
    $header .= "Content-Type: text/html; charset=\"iso-8859-1\" \r\n";
    $header .= "Content-Transfer-Encoding: 8bit \r\n";
    $header .= "MIME-Version: 1.0 \r\n";

    mail($to,$subject,$message,$header);

}