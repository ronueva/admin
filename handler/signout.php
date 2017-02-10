<?php
/**
 * Created by PhpStorm.
 * User: ITSO
 * Date: 1/30/2017
 * Time: 4:59 PM
 */

session_start();
session_unset();
session_destroy();
header("Location: ../pages/signin.php");
exit();

?>