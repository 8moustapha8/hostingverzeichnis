<?php
include('../functions.php');
if(!user::check_login()){header('Location: ./login.php'); die;}
require_once( '../template/template.php' );
template::pagetitle('Benutzer');
template::get_admin_header();
$user = user::current_user();

$user = user::current_user();

if (isset($_POST['add'], $_POST['email'], $_POST['firstname'], $_POST['lastname'], $_POST['password'])) {
	$email = trim($_POST['email']);
	$firstname = trim($_POST['firstname']);
	$lastname = trim($_POST['lastname']);
	$password = trim($_POST['password']);
	
	$e_add = array();
	$display_s_add = false;
	$display_e_add = false;
 	if(!validate::email($email)) {
		$e_add[] = 'E-Mail-Adresse ung&uuml;ltig.';
	} elseif(validate::unused_email($email)) {
		$e_add[] = 'Ein Benutzer ist bereits auf diese E-Mail-Adresse eingetragen.';
	}
	if(!validate::text($firstname) || !validate::min_length($firstname,3)) {
		$e_add[] = 'Vorname ung&uuml;ltig.';
	}
	if(!validate::text($lastname) || !validate::min_length($lastname,3)) {
		$e_add[] = 'Nachname ung&uuml;ltig.';
	}
	if(!validate::min_length($password,8)) {
		$e_add[] = 'Das Passwort muss mindestens acht Zeichen lang sein.';
	}
	if(count($e_add)) {
		$display_e_add = true;
	} else {
		$user = new user();
		$user -> email = $email;
		$user -> password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
		$user -> firstname = $firstname;
		$user -> lastname = $lastname;
		$user -> registered = date('Y-m-d H:i:s');
		$user -> create();
		$display_s_add = true;
	}
}

if (isset($_POST['edit'], $_POST['email'], $_POST['firstname'], $_POST['lastname'], $_POST['password'])) {
	$e_id = trim($_GET['edit']);
	$user = user::from_id($e_id);
	$email = trim($_POST['email']);
	$firstname = trim($_POST['firstname']);
	$lastname = trim($_POST['lastname']);
	$password = trim($_POST['password']);
			
	$e_edit = array();
	$display_e_edit = false;
 	if(!validate::email($email)) {
		$e_edit[] = 'E-Mail-Adresse ung&uuml;ltig.';
	} elseif(validate::one_existing_email($email)) {
		$e_edit[] = 'Ein Benutzer ist bereits auf diese E-Mail-Adresse eingetragen.';
	}
	if(!validate::text($firstname) || !validate::min_length($firstname, 3)) {
		$e_edit[] = 'Vorname ung&uuml;ltig.';
	}
	if(!validate::text($lastname) || !validate::min_length($lastname, 3)) {
		$e_edit[] = 'Nachname ung&uuml;ltig.';
	}
	if($password != '') {
		if(!validate::min_length($password, 8)) {
			$e_edit[] = 'Das Passwort muss mindestens acht Zeichen lang sein.';
		}
	} else {
		$password = false;
	}
	if(count($e_edit)) {
		$display_e_edit = true;
	} else {
		$user -> email = $email;
		if($password != false) {
			$user -> password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
		}
		$user -> firstname = $firstname;
		$user -> lastname = $lastname;
		$user -> save();
		header('Location: ./user.php?edit_success=1');
	}
}

if(isset($_GET['delete'])){
	$d_id = trim($_GET['delete']);
	$user = user::current_user();
	
	$e_delete = array();
	$display_e_delete = false;
	if(!validate::integ($d_id)) {
		$e_delete[] = 'Der Parameter ist ung&uuml;ltig.';
	} else {
		if(!$user->check_validity($d_id)) {
			$e_delete[] = 'Der gew&uuml;nschte Benutzer ist nicht vorhanden.';
		}
	}
	if($d_id == user::current_user()->ID_user) {
		$e_delete[] = 'Der Account kann nicht gel&ouml;scht werden, da Sie zurzeit damit eingeloggt sind.';
	}
	if(count($e_delete)) {
		$display_e_delete = true;
	} else {
		$user = user::from_id($d_id);
		$user -> delete();
		header('Location: ./user.php?delete_success=1');
	}
}

if(isset($_GET['activate'])){
	$a_id = trim($_GET['activate']);
	
	$e_activate = array();
	$display_s_activate = false;
	$display_e_activate = false;
	if(!validate::integ($a_id)) {
		$e_activate[] = 'Der Parameter ist ung&uuml;ltig.';
	} else {
		if(!$user->check_validity($a_id)) {
			$e_activate[] = 'Der gew&uuml;nschte Benutzer ist nicht vorhanden.';
		}
		if(user::from_id($a_id)->status == 1) {
			$e_activate[] = 'Der gew&uuml;nschte Benutzer wurde bereits aktiviert.';
		}
	}
	if(count($e_activate)) {
		$display_e_activate = true;
	} else {
		$user = user::from_id($a_id);
		$user -> activate();
		$display_s_activate = true;
	}
}

if(isset($_GET['edit'])){
	$e_id = trim($_GET['edit']);
	
	$display_edit = false;
	$e_get_edit = array();
	$display_e_get_edit = false;
	if(!validate::integ($e_id)) {
		$e_get_edit[] = 'Der Parameter ist ung&uuml;ltig.';
	} else {
		if(!$user->check_validity($e_id)) {
			$e_get_edit[] = 'Der gew&uuml;nschte Benutzer ist nicht vorhanden.';
		}
	}
	if(count($e_get_edit)) {
		$display_e_get_edit = true;
	} else {
		$display_edit = true;
	}
}

if($display_edit) {
	$user = user::from_id($e_id);
?>
<div class="row">
	<div class="medium-12 columns">
        <h3>Nutzer bearbeiten</h3>
		<div data-alert class="alert-box secondary">
			Hier k&ouml;nnen Sie die Daten des Nutzers bearbeiten. Lassen Sie das Passworteingabefelder leer, so wird dieses nicht geändert.
		</div>
		<?php if($display_e_edit == true) { ?>
		<div data-alert class="alert-box alert">
			<?php echo mb_strtoupper('Bearbeiten: ', 'UTF-8') . implode(' ', $e_edit) ?>
			<a href="#" class="close">&times;</a>
		</div>
		<?php } ?>
    </div>
</div>
<form action="" method="post">
    <div class="row">
        <div class="medium-4 columns">
            <input type="text" name="email" value="<?php echo $user->email ?>" placeholder="E-Mail-Adresse" autocomplete="off">
        </div>
        <div class="medium-4 columns">
            <input type="text" name="firstname" value="<?php echo $user->firstname ?>" placeholder="Vorname" autocomplete="off">
        </div>
        <div class="medium-4 columns">
            <input type="text" name="lastname" value="<?php echo $user->lastname ?>" placeholder="Nachname" autocomplete="off">
        </div>
    </div>
    <div class="row">
        <div class="medium-4 medium-end columns">
            <input type="password" name="password" placeholder="Passwort" autocomplete="off">
        </div>
    </div>
    <div class="row">
        <div class="medium-12 columns">
			<input class="button tiny" name="edit" type="submit" value="&Auml;ndern">
            <small style="margin-left:10px;"><a href="./user.php" class="abort">Abbrechen</a></small>
        </div>
    </div>
</form>

<?php
} else {
?>

<div class="row">
	<div class="medium-12 columns">
        <h3>Nutzer hinzuf&uuml;gen</h3>
		<div data-alert class="alert-box secondary">
			Füge einen Nutzer hinzu. Alle Eingabefelder müssen ausgefüllt sein.
		</div>
		<?php if($display_s_add == true) { ?>
		<div data-alert class="alert-box success">
			Benutzer erfolgreich hinzugef&uuml;gt.
			<a href="#" class="close">&times;</a>
		</div>
		<?php } elseif($display_e_add == true) { ?>
		<div data-alert class="alert-box alert">
			<?php echo mb_strtoupper('Hinzufügen: ', 'UTF-8') . implode(' ', $e_add) ?>
			<a href="#" class="close">&times;</a>
		</div>
		<?php } ?>
	</div>
</div>
<form action="" method="post">
    <div class="row">
        <div class="medium-4 columns">
            <input name="email" type="email" placeholder="E-Mail-Adresse" autocomplete="off"<?php if($display_e_add){echo ' value="' . $email . '"';}?>>
        </div>
        <div class="medium-4 columns">
            <input name="firstname" type="text" placeholder="Vorname" autocomplete="off"<?php if($display_e_add){echo ' value="' . $firstname . '"';}?>>
        </div>
        <div class="medium-4 columns">
            <input name="lastname" type="text" placeholder="Nachname" autocomplete="off"<?php if($display_e_add){echo ' value="' . $lastname . '"';}?>>
        </div>
    </div>
    <div class="row">
        <div class="medium-4 medium-end columns">
            <input name="password" type="password" placeholder="Passwort" autocomplete="off">
        </div>
    </div>
    <div class="row">
        <div class="medium-12 columns">
            <input class="button tiny" name="add" type="submit" value="Hinzuf&uuml;gen">
        </div>
    </div>
</form>

<div class="row">
	<div class="medium-12 columns">
        <h3>Nutzer aktivieren</h3>
		<div data-alert class="alert-box secondary">
			Aktivieren Sie einen neu registrieren Nutzer manuell, damit dieser Zugriff auf die freigegebenen Funktionen hat.
		</div>
		<?php if($display_s_activate == true) { ?>
		<div data-alert class="alert-box success">
			Nutzer erfolgreich aktiviert.
			<a href="#" class="close">&times;</a>
		</div>
		<?php } elseif($display_e_activate == true) { ?>
		<div data-alert class="alert-box alert">
			<?php echo mb_strtoupper('Aktivieren: ', 'UTF-8') . implode(' ', $e_activate) ?>
			<a href="#" class="close">&times;</a>
		</div>
		<?php } ?>
    </div>
</div>

<div class="row">
    <div class="medium-12 columns">
        <table class="responsive" style="width:100%;">
            <thead>
                <tr>
                    <th>E-Mail</th>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>Registriert</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
			<?php
			$user = null;
			foreach(user::all_status(0) as $row) {
				$user = new user($row);
            ?>
                <tr>
                    <td><?php echo $user->email ?></td>
                    <td><?php echo $user->firstname ?></td>
                    <td><?php echo $user->lastname ?></td>
                    <td><?php echo $user->registered ?></td>
                    <td>
						<a href="#" data-dropdown="options_activate">Optionen</a>
						<ul id="options_activate" class="f-dropdown" data-dropdown-content>
							<li><a href="#">Account</a></li>
							<li><a href="./user.php?activate=<?php echo $user->ID_user ?>">Aktivieren</a></li>
							<li><a href="./user.php?edit=<?php echo $user->ID_user ?>">Bearbeiten</a></li>
							<li><a href="./user.php?delete=<?php echo $user->ID_user ?>" onclick="return check_delete()">L&ouml;schen</a></li>
						</ul>					
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
	<div class="medium-12 columns">
        <h3>Nutzer verwalten</h3>
		<div data-alert class="alert-box secondary">
			Verwalte die registrierten Nutzer. Durch einen Klick auf das Stift-Symbol kannst du die Daten de Accounts bearbeiten, mithilfe eines Klicks auf das M&uuml;lleimer-Symbol kannst du den Nutzer entfernen. Den eigenen Account kannst du nicht entfernen.
		</div>
		<?php if(isset($_GET['delete_success']) && $_GET['delete_success'] == 1) { ?>
		<div data-alert class="alert-box success">
			Benutzer erfolgreich entfernt.
			<a href="#" class="close">&times;</a>
		</div>
		<?php } elseif($display_e_delete == true) { ?>
		<div data-alert class="alert-box alert">
			<?php echo mb_strtoupper('Löschen: ', 'UTF-8') . implode(' ', $e_delete) ?>
			<a href="#" class="close">&times;</a>
		</div>
		<?php } ?>
		<?php if(isset($_GET['edit_success']) && $_GET['edit_success'] == 1) { ?>
		<div data-alert class="alert-box success">
			Benutzer erfolgreich bearbeitet.
			<a href="#" class="close">&times;</a>
		</div>
		<?php } elseif($display_e_get_edit == true) { ?>
		<div data-alert class="alert-box alert">
			<?php echo mb_strtoupper('Bearbeiten: ', 'UTF-8') . implode(' ', $e_get_edit) ?>
			<a href="#" class="close">&times;</a>
		</div>
		<?php } ?>
    </div>
</div>    

<div class="row">
    <div class="medium-12 columns">
        <table class="responsive" style="width:100%;">
            <thead>
                <tr>
                    <th>E-Mail</th>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>Letztes Login</th>
                    <th>Registriert</th>
                    <th>Optionen</th>
                </tr>
            </thead>
            <tbody>
			<?php
			$user = null;
			foreach(user::all_status(1) as $row) {
				$user = new user($row);
            ?>
                <tr>
                    <td><?php echo $user->email ?></td>
                    <td><?php echo $user->firstname ?></td>
                    <td><?php echo $user->lastname ?></td>
                    <td><?php echo $user->last_login ?></td>
                    <td><?php echo $user->registered ?></td>
                    <td>
						<a href="#" data-dropdown="options_manage">Optionen</a>
						<ul id="options_manage" class="f-dropdown" data-dropdown-content>
							<li><a href="#">Account</a></li>
							<li><a href="./user.php?edit=<?php echo $user->ID_user ?>">Bearbeiten</a></li>
							<li><a href="./user.php?delete=<?php echo $user->ID_user ?>" onclick="return check_delete()">L&ouml;schen</a></li>
							<li><a href="#">Deaktivieren</a></li>
						</ul>					
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php	 
}
template::get_admin_footer() ?>