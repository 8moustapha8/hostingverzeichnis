<?php
include('../functions.php');
if(!user::check_login()){header('Location: ./login.php'); die;}
require_once( '../template/template.php' );
template::pagetitle('Produkte');
template::get_admin_header();
$user = user::current_user();

switch ($_GET['type']) {
    case 'shared':
		$server_type = 'shared';
        break;
    case 'virtual':
		$server_type = 'virtual';
        break;
    case 'root':
		$server_type = 'root';
        break;
    case 'game':
		$server_type = 'game';
        break;
}
echo $server_type;

template::get_admin_footer() ?>