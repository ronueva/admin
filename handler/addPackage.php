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

$package_name = $_POST['package_name'];
$package_type = $_POST['package_type'];
$package_price = $_POST['package_price'];
$target_file = $target_dir . preg_replace('/\s+/', '',basename($_FILES['package_image']["name"]));

if (move_uploaded_file($_FILES["package_image"]["tmp_name"], $target_file)) {
    $_SESSION['upload'] = true;
    if ($db->addPackage($package_name, $package_type, $package_price, $company->company_id, preg_replace('/\s+/', '', $_FILES['package_image']["name"]))) {
        $_SESSION['action'] = true;
        header("Location: ../pages/packages.php");
        exit();
    } else {
        $_SESSION['action'] = false;
    }
} else {
    $_SESSION['upload'] = false;
}


?>
