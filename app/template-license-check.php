<?php
/*
Template Name: License Check
*/

//License check file for testing.
if(isset($_GET['license']) && $_GET['license'] != ""){
    $license = $_GET['license'];
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM wp_woocommerce_downloadable_product_permissions WHERE order_key='".$license."';");
    /*
    echo '<h3>'.$_GET['license'].'</h3>';
    echo '<table>';
    foreach($results as $result){
        echo "<tr>";
        echo "<td>".$result->order_id."</td>";
        echo "<td>".$result->order_key."</td>";
        echo "</tr>";
    }
    echo '</table>';
    */
    if($results!=null){
        echo "true";
        return true;
    }else{
        echo "false";
        return false;
    }
}
echo "false";
return false;

?>