<?php
include('../functions.php');
if(!user::check_login()){header('Location: ./login.php'); die;}
require_once( '../template/template.php' );
template::pagetitle('Unternehmen');
template::get_admin_header();
$user = user::current_user();

if (isset($_POST['add'], $_POST['name'], $_POST['email'], $_POST['url'], $_POST['street'], $_POST['plz'], $_POST['city'], $_POST['country'])) {
	$name = trim($_POST['name']);
	$email = trim($_POST['email']);
	$url = trim($_POST['url']);
	$street = trim($_POST['street']);
	$plz = trim($_POST['plz']);
	$city = trim($_POST['city']);
	$country = trim($_POST['country']);
	
	$e_add = array();
	$display_s_add = false;
	$display_e_add = false;
	if(!validate::min_length($name, 3)) {
		$e_add[] = 'Name ung&uuml;ltig.';
	}
 	if(!validate::email($email)) {
		$e_add[] = 'E-Mail-Adresse ung&uuml;ltig.';
	} elseif(company::one_existing_email($email)) {
		$e_add[] = 'Ein Unternehmen ist bereits auf diese E-Mail-Adresse eingetragen.';
	}
	if(!validate::link($url)) {
		$e_add[] = 'URL ung&uuml;ltig.';
	}
	if(!validate::min_length($plz, 4)) {
		$e_add[] = 'Strasse ung&uuml;ltig.';
	}
	if(!validate::integ($plz) || !validate::min_length($plz, 4)) {
		$e_add[] = 'PLZ ung&uuml;ltig.';
	}
	if(!validate::text($city)) {
		$e_add[] = 'Ort ung&uuml;ltig.';
	}
	if(!validate::text($country) || !validate::min_length($country, 7)) {
		$e_add[] = 'Land ung&uuml;ltig.';
	}
	if(count($e_add)) {
		$display_e_add = true;
	} else {
		$company = new company();
		$company -> name = $email;
		$company -> email = $email;
		$company -> url = $url;
		$company -> street = $street;
		$company -> plz = $plz;
		$company -> city = $city;
		$company -> country = $country;
		$company -> create();
		$display_s_add = true;
	}
}

if (isset($_POST['edit'], $_POST['name'], $_POST['email'], $_POST['url'], $_POST['street'], $_POST['plz'], $_POST['city'], $_POST['country'])) {
	$e_id = trim($_GET['edit']);
	$company = company::from_id($e_id);
	$name = trim($_POST['name']);
	$email = trim($_POST['email']);
	$url = trim($_POST['url']);
	$street = trim($_POST['street']);
	$plz = trim($_POST['plz']);
	$city = trim($_POST['city']);
	$country = trim($_POST['country']);
			
	$e_edit = array();
	$display_e_edit = false;
	if(!validate::min_length($name, 3)) {
		$e_edit[] = 'Name ung&uuml;ltig.';
	}
 	if(!validate::email($email)) {
		$e_edit[] = 'E-Mail-Adresse ung&uuml;ltig.';
	} elseif(company::one_existing_email($email)) {
		$e_edit[] = 'Ein Unternehmen ist bereits auf diese E-Mail-Adresse eingetragen.';
	}
	if(!validate::link($url)) {
		$e_edit[] = 'URL ung&uuml;ltig.';
	}
	if(!validate::min_length($plz, 4)) {
		$e_edit[] = 'Strasse ung&uuml;ltig.';
	}
	if(!validate::integ($plz) || !validate::min_length($plz, 4)) {
		$e_edit[] = 'PLZ ung&uuml;ltig.';
	}
	if(!validate::text($city)) {
		$e_edit[] = 'Ort ung&uuml;ltig.';
	}
	if(!validate::text($country) || !validate::min_length($country, 7)) {
		$e_edit[] = 'Land ung&uuml;ltig.';
	}
	if(count($e_edit)) {
		$display_e_edit = true;
	} else {
		$company -> name = $name;
		$company -> email = $email;
		$company -> url = $url;
		$company -> street = $street;
		$company -> plz = $plz;
		$company -> city = $city;
		$company -> country = $country;
		$company -> save();
		header('Location: ./company.php?edit_success=1');
	}
}

if(isset($_GET['delete'])){
	$d_id = trim($_GET['delete']);
	
	$e_delete = array();
	$display_e_delete = false;
	if(!validate::integ($d_id)) {
		$e_delete[] = 'Der Parameter ist ung&uuml;ltig.';
	} else {
		$company = new company();
		if(!$company->check_validity($d_id)) {
			$e_delete[] = 'Das gew&uuml;nschte Unternehmen ist nicht vorhanden.';
		}
	}
	if(count($e_delete)) {
		$display_e_delete = true;
	} else {
		$company = company::from_id($d_id);
		$company -> delete();
		header('Location: ./company.php?delete_success=1');
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
		$company = new company();
		if(!$company->check_validity($a_id)) {
			$e_activate[] = 'Das gew&uuml;nschte Unternehmen ist nicht vorhanden.';
		}
		if(company::from_id($a_id)->status == 1) {
			$e_activate[] = 'Das gew&uuml;nschte Unternehmen wurde bereits aktiviert.';
		}
	}
	if(count($e_activate)) {
		$display_e_activate = true;
	} else {
		$company = company::from_id($a_id);
		$company -> activate();
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
		$company = company::from_id($e_id);
		if(!$company->check_validity($e_id)) {
			$e_get_edit[] = 'Das gew&uuml;nschte Unternehmen ist nicht vorhanden.';
		}
	}
	if(count($e_get_edit)) {
		$display_e_get_edit = true;
	} else {
		$display_edit = true;
	}
}

if($display_edit) {
?>
<div class="row">
	<div class="large-12 columns">
        <h3>Unternehmen bearbeiten</h3>
		<div data-alert class="alert-box secondary">
			Hier k&ouml;nnen Sie die Daten des Unternehmens bearbeiten.
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
            <input name="name" type="text" placeholder="Name" autocomplete="off" value="<?php echo $company->name ?>">
        </div>
        <div class="medium-4 columns">
            <input name="email" type="email" placeholder="E-Mail-Adresse" autocomplete="off" value="<?php echo $company->email ?>">
        </div>
        <div class="medium-4 columns">
            <input name="url" type="url" placeholder="Url" autocomplete="off" value="<?php echo $company->url ?>">
        </div>
    </div>
    <div class="row">
        <div class="medium-4 columns">
            <input name="street" type="text" placeholder="Strasse" autocomplete="off" value="<?php echo $company->street ?>">
        </div>
        <div class="medium-4 columns">
            <input name="plz" type="number" placeholder="Postleitzahl" autocomplete="off" value="<?php echo $company->plz ?>">
        </div>
        <div class="medium-4 columns">
            <input name="city" type="text" placeholder="Ort" autocomplete="off" value="<?php echo $company->city ?>">
        </div>
    </div>
    <div class="row">
        <div class="medium-4 columns end">
            <select name="country">
            <?php
			foreach(general::enum_values('companies', 'country') as $option) {
				echo '<option value="' . $option . '"'; if($option == $company->country){echo ' selected="selected"';}; echo '>';
				echo ucfirst($option);
				echo '</option>';
			}
			?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <input class="button tiny" name="edit" type="submit" value="&Auml;ndern">
            <small style="margin-left:10px;"><a href="./company.php" class="abort">Abbrechen</a></small>
        </div>
    </div>
</form>

<?php
} else {
?>

<div class="row">
	<div class="large-12 columns">
        <h3>Unternehmen hinzuf&uuml;gen</h3>
		<div data-alert class="alert-box secondary">
			Fügen Sie ein Unternehmen hinzu. Alle Eingabefelder müssen ausgefüllt sein.
		</div>
		<?php if($display_s_add == true) { ?>
		<div data-alert class="alert-box success">
			Unternehmen erfolgreich hinzugef&uuml;gt.
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
            <input name="name" type="text" placeholder="Name" autocomplete="off"<?php if($display_e_add){echo ' value="' . $name . '"';}?>>
        </div>
        <div class="medium-4 columns">
            <input name="email" type="email" placeholder="E-Mail-Adresse" autocomplete="off"<?php if($display_e_add){echo ' value="' . $email . '"';}?>>
        </div>
        <div class="medium-4 columns">
            <input name="url" type="url" placeholder="Url" autocomplete="off"<?php if($display_e_add){echo ' value="' . $url . '"';}?>>
        </div>
    </div>
    <div class="row">
        <div class="medium-4 columns">
            <input name="street" type="text" placeholder="Strasse" autocomplete="off"<?php if($display_e_add){echo ' value="' . $street . '"';}?>>
        </div>
        <div class="medium-4 columns">
            <input name="plz" type="number" placeholder="Postleitzahl" autocomplete="off"<?php if($display_e_add){echo ' value="' . $plz . '"';}?>>
        </div>
        <div class="medium-4 columns">
            <input name="city" type="text" placeholder="Ort" autocomplete="off"<?php if($display_e_add){echo ' value="' . $city . '"';}?>>
        </div>
    </div>
    <div class="row">
        <div class="medium-4 columns end">
            <select name="country">
            <?php
			foreach(general::enum_values('companies', 'country') as $option) {
				echo '<option value="' . $option . '"'; if($display_e_add && $option == $country){echo ' selected="selected"';}; echo '>';
				echo ucfirst($option);
				echo '</option>';
			}
			?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <input class="button tiny" name="add" type="submit" value="Hinzuf&uuml;gen">
        </div>
    </div>
</form>

<div class="row">
	<div class="large-12 columns">
        <h3>Unternehmen aktivieren</h3>
		<div data-alert class="alert-box secondary">
			Aktivieren Sie einen neu eingetragene Unternehmen, damit diese im Frontend angezeigt werden.
		</div>
		<?php if($display_s_activate == true) { ?>
		<div data-alert class="alert-box success">
			Unternehmen erfolgreich aktiviert.
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
    <div class="large-12 columns">
        <table class="responsive" style="width:100%;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>E-Mail-Adresse</th>
                    <th>Url</th>
                    <th>Strasse</th>
                    <th>PLZ</th>
                    <th>Ort</th>
                    <th>Land</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
			<?php
			$company = null;
			foreach(company::all_status(0) as $row) {
				$company = new company($row);
            ?>
                    <td><?php echo $company->name ?></td>
                    <td><?php echo $company->email ?></td>
                    <td><?php echo $company->url ?></td>
                    <td><?php echo $company->street ?></td>
                    <td><?php echo $company->plz ?></td>
                    <td><?php echo $company->city ?></td>
                    <td><?php echo $company->country ?></td>
                    <td>
						<a href="#" data-dropdown="options_activate">Optionen</a>
						<ul id="options_activate" class="f-dropdown" data-dropdown-content>
							<li><a href="./company.php?activate=<?php echo $company->ID_company ?>">Aktivieren</a></li>
							<li><a href="./company.php?edit=<?php echo $company->ID_company ?>">Bearbeiten</a></li>
							<li><a href="./company.php?delete=<?php echo $company->ID_company ?>" onclick="return check_delete()">L&ouml;schen</a></li>
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
	<div class="large-12 columns">
        <h3>Unternehmen verwalten</h3>
		<div data-alert class="alert-box secondary">
			Verwalte die eingetragenen Unternehmen. Durch einen Klick auf <em>bearbeiten</em> kannst du die Daten des Unternehmens bearbeiten, mithilfe eines Klicks auf das <em>l&ouml;schen</em> kannst du das Unternehmen entfernen.
		</div>
		<?php if(isset($_GET['delete_success']) && $_GET['delete_success'] == 1) { ?>
		<div data-alert class="alert-box success">
			Unternehmen erfolgreich entfernt.
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
			Unternehmen erfolgreich bearbeitet.
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
    <div class="large-12 columns">
        <table class="responsive" style="width:100%;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>E-Mail-Adresse</th>
                    <th>Url</th>
                    <th>Strasse</th>
                    <th>PLZ</th>
                    <th>Ort</th>
                    <th>Land</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
			<?php
			$company = null;
			foreach(company::all_status(1) as $row) {
				$company = new company($row);
            ?>
                <tr>
                    <td><?php echo $company->name ?></td>
                    <td><?php echo $company->email ?></td>
                    <td><?php echo $company->url ?></td>
                    <td><?php echo $company->street ?></td>
                    <td><?php echo $company->plz ?></td>
                    <td><?php echo $company->city ?></td>
                    <td><?php echo $company->country ?></td>
                    <td>
						<a href="#" data-dropdown="options_activate">Optionen</a>
						<ul id="options_activate" class="f-dropdown" data-dropdown-content>
							<li><a href="./company.php?edit=<?php echo $company->ID_company ?>">Bearbeiten</a></li>
							<li><a href="#">Deaktivieren</a></li>
							<li><a href="./company.php?delete=<?php echo $company->ID_company ?>" onclick="return check_delete()">L&ouml;schen</a></li>
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