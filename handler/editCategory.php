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
$category_name = $_POST['category_name_edit'];
$category_description = $_POST['category_description_edit'];


if ($db->editCategory($category_id, $package_id, $category_name, $category_description)) {
    $_SESSION['action'] = true;
    header("Location: ../pages/categories.php");
    exit();
} else {
    $_SESSION['action'] = false;
}


?>
