<?php
/**
 * Created by PhpStorm.
 * User: ITSO
 * Date: 1/30/2017
 * Time: 4:59 PM
 */

require 'DBHandler.php';

$db = new DbHandler();

session_start();

if (!isset($_SESSION["company"])) {
    header("Location: signin.php");
    exit();
} else {
    $company = $_SESSION["company"];
}

$category = $db->getCategoryById($_POST['category_id']);

echo json_encode($category);



?>
