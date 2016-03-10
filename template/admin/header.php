<?php $user = user::current_user(); ?>
<!DOCTYPE html>
<html lang="de">

<head>
	<base href="<?php echo template::$root_url ?>admin/" target="_self">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo template::$page_title ?></title>
	<link rel="stylesheet" href="../template/admin/css/normalize.css" type="text/css">
	<link rel="stylesheet" href="../template/admin/css/foundation.css" type="text/css">
	<link rel="stylesheet" href="../template/admin/css/responsive-tables.css" type="text/css">
	<script src="../template/admin/js/vendor/modernizr.js" type="text/javascript" language="javascript"></script>
	<script src="../template/admin/js/scripts.js" type="text/javascript" language="javascript"></script>
</head>

<body>
<nav class="top-bar" data-topbar>
	<ul class="title-area">
		<li class="name">
			<h1><a href="./index.php"><?php echo $user->firstname . ' ' . $user->lastname; ?></a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<ul class="right">
			<?php
			$full_name = $_SERVER['PHP_SELF'];
			$name_array = explode('/',$full_name);
			$count = count($name_array);
			$page_name = $name_array[$count-1];
			?>
			<li<?php echo ($page_name=='index.php')?' class="active"':'';?>><a href="./index.php">Dashboard</a></li>
			<li class="has-dropdown<?php echo ($page_name=='products.php')?' active':'';?>">
				<a href="#">Produkte</a>
				<ul class="dropdown">
					<li><a href="./products.php?type=shared">Shared Server</a></li>
					<li><a href="./products.php?type=virtual">Virtual Server</a></li>
					<li><a href="./products.php?type=root">Root Server</a></li>
					<li><a href="./products.php?type=game">Game Server</a></li>
				</ul>
			</li>
			<li<?php echo ($page_name=='company.php')?' class="active"':'';?>><a href="./company.php">Unternehmen</a></li>
			<li<?php echo ($page_name=='user.php')?' class="active"':'';?>><a href="./user.php">Benutzer</a></li>
			<li<?php echo ($page_name=='options.php')?' class="active"':'';?>><a href="./options.php">Optionen</a></li>
			<li class="has-form"> <a href="./logout.php" class="button">Logout</a> </li>
		</ul>
		<ul class="left">
			<li class="has-form"><a href="<?php echo template::$root_url ?>" class="button">Seite ansehen</a></li>
		</ul>
	</section>
</nav>
