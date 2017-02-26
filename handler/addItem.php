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
$package_id = $_POST['package_id'];
$item_name = $_POST['item_name'];
$item_description = $_POST['item_description'];


if ($db->addItem($category_id, $item_name, $item_description)) {
    $result['successDb'] = true;
    $item_id = $db->getItemMaxId();
    $result['item_id'] = $item_id;
    $result['package_id'] = $package_id;
    $result['category_id'] = $category_id;
    $result['item_name'] = $item_name;
    $result['item_description'] = $item_description;
} else {
    $result['successDb'] = false;
}

echo json_encode($result);

?>
