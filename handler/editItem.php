<?php
/**
 * Created by PhpStorm.
 * User: ITSO
 * Date: 1/30/2017
 * Time: 4:59 PM
 */

require 'DBHandler.php';

$target_dir = "../../images/";

$db = new DbHandler();

session_start();

if (!isset($_SESSION["company"])) {
    header("Location: signin.php");
    exit();
} else {
    $company = $_SESSION["company"];
}

$category_id = $_POST['category_id'];
$item_id = $_POST['item_id_edit'];
$item_name = $_POST['item_name_edit'];
$item_description = $_POST['item_description_edit'];


if ($db->editItem($item_id, $category_id, $item_name, $item_description)) {
    $result['action'] = true;
    $result['item'] = $db->getItemsByCategory($category_id);
} else {
    $result['action'] = false;
}

echo json_encode($result);


?>
