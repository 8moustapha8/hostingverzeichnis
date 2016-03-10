<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->
<html>

<head>

<base href="<?php echo template::$root_url ?>" target="_self">

<meta name="description" content="<?php echo template::$head_description?>">
<meta name="keywords" content="<?php echo template::$head_keywords?>">
<meta name="author" content="<?php echo template::$head_author?>">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">

<link rel="stylesheet" type="text/css" href="./template/css/reset.css" media="all">
<link rel="stylesheet" type="text/css" href="./template/css/rwdgrid.css" media="all">
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" media="all">
<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,800,900' media="all">
<link rel="stylesheet" type="text/css" href="./template/css/font-awesome.min.css" media="all">
<link rel="stylesheet" type="text/css" href="./template/css/owl.carousel.css" media="all">
<link rel='stylesheet' type='text/css' href='./template/css/jquery.kwicks.css' media="all">
<link rel="stylesheet" type="text/css" href="./template/css/jquery.nouislider.min.css" media="all">
<link rel="stylesheet" type="text/css" href="./template/css/styles.css" media="all">

<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="./template/js/owl.carousel.js"></script>
<script language="javascript" type="text/javascript" src="./template/js/jquery.nouislider.min.js"></script>
<script language="javascript" type="text/javascript" src="./template/js/jquery.kwicks.js"></script>
<script language="javascript" type="text/javascript" src="./template/js/scripts.js"></script>

<!-- html5.js for IE less than 9 -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- css3-mediaqueries.js for IE less than 9 -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->

<title><?php echo template::$page_title?></title>

</head>

<body>
<section id="above_header" class="clearfix">
	<div class="container-12">
		<div class="grid-12">
			<?php if(user::check_login()) { ?>
			<a class="left" href="./admin/index.php">Backend</a>
			<a class="left" href="./admin/logout.php">Logout</a>
			<?php } ?>
			<ul>
				<li><a href="./login">Business</a></li>
				<li><a href="./kontakt">Kontakt</a></li>
				<li><a href="http://www.robinbisping.ch/impressum" target="_blank">Impressum</a></li>
			</ul>
		</div>
	</div>
</section>
<header class="clearfix">
	<div class="container-12">
		<div id="logo" class="grid-4">
			<a href="<?php echo template::$root_url ?>">
				<div class="slogan">Das kostenlose Vergleichsportal</div>
				<h1><span>Hosting</span>verzeichnis</h1>
			</a>
		</div>
		<div class="grid-8">
			<nav>
				<a href="./shared-server"><span data-hover="Shared Server">Shared Server</span></a>
				<a href="./virtual-server"><span data-hover="Virtual Server">Virtual Server</span></a>
				<a href="./root-server"><span data-hover="Root Server">Root Server</span></a>
				<a href="./game-server"><span data-hover="Game Server">Game Server</span></a>
			</nav>
		</div>
	</div>
</header>
