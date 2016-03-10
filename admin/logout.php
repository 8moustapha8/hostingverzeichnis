<?php
include('../functions.php');
if(!user::check_login()){header('Location: ./login.php'); die;}

$user = user::current_user();
$user->logout();
?>