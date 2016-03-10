<?php
include('../functions.php');
if(user::check_login()){header('Location: ./index.php'); die;}

if (isset($_POST['login'], $_POST['email'], $_POST['password'])) {
	$e_data = array();
	$display_e_data = false;
	$user = user::from_email($_POST["email"]);
	if (!($user->ID_user) || $user->ID_user == 0 || !$user->check_password($_POST['password'])) {
		$e_data[] = 'Ihre angegebenen Anmeldedaten sind leider fehlerhaft. Bitte versuchen Sie es erneut oder kontaktieren Sie den <a href="../contact.php" target="_blank" title="Kontakt">Administrator</a>.';
	}
	if(count($e_data)) {
		$display_e_data = true;
	} else {
		$user->login();
		header("Location: ./index.php");
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../template/admin/css/normalize.css" type="text/css">
  <link rel="stylesheet" href="../template/admin/css/foundation.css" type="text/css">
  <script src="../template/admin/js/vendor/modernizr.js"></script>
</head>

<body>
    <form method="post" action="" class="row">
        <div class="large-4 medium-6 medium-centered columns">
            <h1>Login</h1>
            <?php if($display_e_data) { ?>
            <div data-alert class="alert-box secondary">
				<?php echo implode(' ', $e_data) ?>
				<a href="#" class="close">&times;</a>
            </div>
            <?php } ?>
            <input name="email" type="email" placeholder="E-Mail-Adresse" autofocus="autofocus" required="required">
            <input name="password" type="password" placeholder="Password" ondragenter="event.dataTransfer.dropEffect='none'; event.stopPropagation(); event.preventDefault();" ondragover="event.dataTransfer.dropEffect='none';event.stopPropagation(); event.preventDefault();" ondrop="event.dataTransfer.dropEffect='none';event.stopPropagation(); event.preventDefault();" onCopy="return false" onDrag="return false" onPaste="return false" autocomplete="off" type="password" name="password" id="password" required="required">
            <input class="button tiny" name="login" type="submit" value="Anmelden">
        </div>
    </form>

	<script src="../template/admin/js/vendor/jquery.js" language="javascript" type="text/javascript"></script>
	<script src="../template/admin/js/foundation.min.js" language="javascript" type="text/javascript"></script>
	<script>
		$(document).foundation();
	</script>
</body>
</html>