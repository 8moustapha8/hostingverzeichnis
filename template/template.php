<?php

class template{
	public static $site_name = 'Hostingverzeichnis';

	public static $head_description = 'Description';
	public static $head_author = 'Robin Bisping';
	public static $head_keywords = 'Tags';

	public static $page_title = 'Hostingverzeichnis';

	public static $root_url = '';
	public static $error = './404.php';

	public static function pagetitle($pagetitle){
		self::$page_title = $pagetitle . ' – ' . self::$site_name;
	}

	public static function get_header(){
		include_once('header.php');
	}
	public static function get_footer(){
		include_once('footer.php');
	}

	public static function get_admin_header(){
		include_once('admin/header.php');
	}
	public static function get_admin_footer(){
		include_once('admin/footer.php');
	}
}

?>
