<?php
/**
 * Created by PhpStorm.
 * User: ITSO
 * Date: 1/30/2017
 * Time: 4:59 PM
 */

require 'DBHandler.php';

$username = $_POST['username'];
$password = $_POST['password'];

$db = new DbHandler();

$login = $db->loginCompany($username, $password);

if($login){
    session_start();
    $_SESSION["company"] = $db;
    header("Location: ../pages/overview.php");
    exit();
} else {
    echo "Invalid Credentials";
}




?>