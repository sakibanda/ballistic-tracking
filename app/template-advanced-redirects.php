<?php
/*
Template Name: Advanced Redirects
*/

//Advanced Redirects file for testing.
if(isset($_GET['license']) && $_GET['license'] != ""){
    $license = $_GET['license'];
    global $wpdb;
    $sql = "SELECT *
    FROM wp_woocommerce_downloadable_product_permissions as d
    LEFT JOIN wp_woocommerce_order_items o ON d.order_id = o.order_id
    LEFT JOIN wp_woocommerce_order_itemmeta m ON o.order_item_id = m.order_item_id
    WHERE d.order_key='".$license."' AND m.meta_key='_subscription_status' AND m.meta_value='active'
    AND o.order_item_name='Advanced Redirects'";
    $results = $wpdb->get_results($sql);
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