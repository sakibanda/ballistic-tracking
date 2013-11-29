<?php

//License check file for testing.
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