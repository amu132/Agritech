<?php
session_start();
require('../sql.php'); // Includes SQL connection script

if (isset($_POST['add_to_cart'])) {
    $crop = mysqli_real_escape_string($conn, $_POST['crops']);
    $quantity = intval($_POST['quantity']);
    $tradeID = mysqli_real_escape_string($conn, $_POST['tradeid']);
    $price = floatval($_POST['price']);

    // Check if crop already exists in cart
    $query_check = "SELECT * FROM cart WHERE cropname = '$crop'";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Crop exists, update quantity and price
        $query_update = "UPDATE cart 
                         SET quantity = quantity + $quantity, 
                             price = price + ($price * $quantity) 
                         WHERE cropname = '$crop'";
        mysqli_query($conn, $query_update);
    } else {
        // Crop does not exist, insert new row
        $query_insert = "INSERT INTO cart (cropname, quantity, price) 
                         VALUES ('$crop', '$quantity', '$price')";
        mysqli_query($conn, $query_insert);
    }

    // Manage shopping cart session
    if (!isset($_SESSION["shopping_cart"])) {
        $_SESSION["shopping_cart"] = [];
    }

    $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
    if (!in_array($tradeID, $item_array_id)) {
        $item_array = array(
            'item_id' => $tradeID,
            'item_name' => $crop,
            'item_price' => $price,
            'item_quantity' => $quantity
        );
        $_SESSION["shopping_cart"][] = $item_array;
    } else {
        foreach ($_SESSION["shopping_cart"] as &$item) {
            if ($item["item_id"] == $tradeID) {
                $item["item_quantity"] += $quantity;
                break;
            }
        }
    }

    header("Location: cbuy_crops.php?action=add&id=$tradeID");
    exit();
}
?>
