<?php
require "connection.php";
$customerID = $_SESSION['user_id'];
$itemID = $_GET['item_id'];
$url = "add_to_cart.php?customer_id=$customerID&item_id=$itemID";

$q = "SELECT item_price FROM item WHERE item_id='$itemID'";
$res = DB()->query($q);
$r = $res->fetch_assoc();
$itemPrice = $r['item_price'];

$q3 = "SELECT transaction_id,transaction_total FROM transaction WHERE customer_id='$customerID' AND transaction_date = 'NULL'";
$res3 = DB()->query($q3);
if ($res3->num_rows > 0) {
    $r3 = $res3->fetch_assoc();
    $itemPrice = $itemPrice + $r3['transaction_total'];
    $transactionID = $r3['transaction_id'];

    $sql2 = "INSERT INTO transaction_item (transaction_id, item_id)
    VALUES ('$transactionID', '$itemID')";

    if (DB()->query("$sql2") === true) {

        $UpdateTotalSQL = "UPDATE transaction
        SET transaction_total = '$itemPrice'
        WHERE transaction_id = '$transactionID';";

        if (DB()->query($UpdateTotalSQL)) {
            
        } else {
            
        }

        echo '<script>';
        echo "alert('Item added to cart successfully')";
        echo '</script>';
    } else {
        echo '<script>';
        echo "alert('Item already in cart')";
        echo '</script>';
    }
} else {
    $sql = "INSERT INTO transaction (transaction_date, transaction_total, customer_id)
    VALUES ( 'NULL', '$itemPrice', '$customerID')";
    DB()->query("$sql");
    
    $q4 = "SELECT transaction_id FROM transaction WHERE customer_id='$customerID' AND transaction_date = 'NULL'";
    $res4 = DB()->query($q4);
    $r4 = $res4->fetch_assoc();
    $transID = $r4['transaction_id'];
    $sql2 = "INSERT INTO transaction_item (transaction_id, item_id)
    VALUES ('$transID', '$itemID')";


    if (DB()->query("$sql2") === true) {
        echo '<script>';
        echo "alert('Item added to cart successfully')";
        echo '</script>';
    } else {
        echo '<script>';
        echo "alert('Item already in cart')";
        echo '</script>';
    }
}



header("refresh: 0 url=" . $_SERVER['HTTP_REFERER']);
exit();

?>