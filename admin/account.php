<?php include('../functions.php'); if(!check_login()) { header('Location: ./login.php'); die; }

require_once( '../template/template.php' );
template::get_admin_header();

require_once('../db_connect.php');

//bearbeitete Daten in DB eintragen
if (isset($_POST['submit'])) {
	if(isset($_POST['firstname'], $_POST['lastname'], $_POST['email']) &&  $_POST['firstname']) {
		$new_firstname = str_replace(' ', '', $_POST['firstname']);
		$new_lastname = str_replace(' ', '', $_POST['lastname']);
		$new_email = str_replace(' ', '', $_POST['email']);
				
		if(validate_email($new_email) && validate_length(3, $new_firstname, $new_lastname, $new_email)) {

			if(isset($_POST['password1'], $_POST['password2']) && $_POST['password1']) {
				$new_password1 = str_replace(' ', '', $_POST['password1']);
				$new_password2 = str_replace(' ', '', $_POST['password2']);
				
				if($new_password1 == $new_password2 && validate_length(6, $new_password1)) {
					$new_password = md5($new_password1);
					update_user_pw(current_user(), $new_firstname, $new_lastname, $new_email, $new_password, $db_handle);
					echo '<script type="text/javascript">window.location = window.location.href.split("?")[0];</script>';
					return;
				} else {
					false;	
				}
			} else {
				update_user(current_user(), $new_firstname, $new_lastname, $new_email, $db_handle);
				echo '<script type="text/javascript">window.location = window.location.href.split("?")[0];</script>';
				return;
			}
		} else {
			false;
		}
	}
}

try {
	$sql = 'SELECT ID_user, username, email, firstname, lastname FROM users WHERE ID_user = :id LIMIT 1';
	$users = array();
	$stmt = $db_handle -> prepare($sql);
	$stmt -> bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
	$stmt -> bindColumn('username', $username);
	$stmt -> bindColumn('email', $email);
	$stmt -> bindColumn('firstname', $firstname);
	$stmt -> bindColumn('lastname', $lastname);
	$stmt -> execute();
	while( $stmt -> fetch() ) {
		$users[] = array('username' => $username, 'email' => $email, 'firstname' => $firstname, 'lastname' => $lastname);	
	}
	$stmt -> closeCursor;
?>
<div class="grid-12">
    <h1><span>Mein</span> Account</h1>
    <div class="description"><i class="fa fa-info-circle fa-lg"></i><span>Hier kannst du deine eigenen Daten bearbeiten. Lässt du die Passworteingabefelder leer, so wird dieses nicht geändert.</span></div>
</div>

<?php foreach($users as $user) { 
//Formular mit Daten füllen
?>
<form action="" method="post">
	<div class="grid-6">
        <input type="text" value="<?php echo $user['username'] ?>" disabled="disabled">
        <input type="text" name="firstname" value="<?php echo $user['firstname'] ?>" placeholder="Vorname">
        <input type="text" name="lastname" value="<?php echo $user['lastname'] ?>" placeholder="Nachname">
    </div>
    <div class="grid-6">
        <input type="email" name="email" value="<?php echo $user['email'] ?>" placeholder="E-Mail-Adresse">
        <input type="password" name="password1" placeholder="Neues Passwort">
        <input type="password" name="password2" placeholder="Neues Passwort wiederholen">
    </div>
    <div class="grid-12">
      	<input name="submit" type="submit" value="&Auml;ndern">
    </div>
</form>

<?php	 
}
} catch(PDOException $e) {
	print( $e -> getMessage() );
	print( $e -> getTraceAsString() );
	exit();
}

template::get_admin_footer() ?>