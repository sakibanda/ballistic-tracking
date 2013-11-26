<?php
//include ballistic.
//require_once(__DIR__ . '/private/index.php');

if(isset($_GET['license']) && $_GET['license'] != ""){
    $license = $_GET['license'];
    if($license=="abc123"){
        echo "true";
        return true;
    }else{
        echo "false";
        return false;
    }
}
echo "false";
return false;