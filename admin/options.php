<?php
include('../functions.php');
if(!user::check_login()){header('Location: ./login.php'); die;}
require_once( '../template/template.php' );
template::pagetitle('Einstellungen');
template::get_admin_header();
$user = user::current_user();

if(isset($_POST['save'], $_POST['setting'])) {
	foreach($_POST['setting'] as $id => $value) {
		$setting = null;
		$setting = setting::from_id($id);
		$setting->value = $value;
		$setting->save();
	}
}
?>

<div class="row">
	<div class="large-12 columns">
		<h3>Einstellungen</h3>
		<div data-alert class="alert-box secondary">
			Hier k&ouml;nnen Sie die globalen Einstellungen der Applikation bearbeiten.
		</div>
  	</div>
</div>

<form action="" method="post">
<?php
$setting = null;
foreach(setting::all() as $row) {
	$setting = new setting($row);
	switch($setting->type) {
		case 'boolean':
			?>
			<div class="row">
				<div class="large-4 medium-6 columns">
					<div class="switch tiny left" style="margin-right:10px;">
						<input name="setting[<?php echo $setting->ID_setting ?>]" value="0" type="hidden">
						<input id="setting_<?php echo $setting->ID_setting ?>" name="setting[<?php echo $setting->ID_setting ?>]" value="1" type="checkbox"<?php echo $setting->value ? ' checked="checked"' : '' ?>>
						<label for="setting_<?php echo $setting->ID_setting ?>"></label>
					</div>
					<label for="setting_<?php echo $setting->ID_setting ?>"><b><?php echo $setting->name ?></b></label>
				</div>
				<div class="large-8 medium-6 columns">
					<em style="line-height:140%;"><?php echo $setting->description ?></em>
				</div>
				<div class="column"><hr></div>
			</div>
			<?php
			break;
		case 'int':
			?>
			<div class="row">
				<div class="large-4 medium-6 columns">
					<label for="setting_<?php echo $setting->ID_setting ?>" style="margin-bottom:5px;"><b><?php echo $setting->name ?></b></label>
					<input id="setting_<?php echo $setting->ID_setting ?>" name="setting[<?php echo $setting->ID_setting ?>]" type="number" value="<?php echo $setting->value ?>">
				</div>
				<div class="large-8 medium-6 columns">
					<em style="line-height:140%;"><?php echo $setting->description ?></em>
				</div>
				<div class="column"><hr></div>
			</div>
			<?php
			break;
		case 'text':
			?>
			<div class="row">
				<div class="column" style="border-bottom:1px dotted black;"></div>
				<div class="large-4 medium-6 columns">
					<label for="setting_<?php echo $setting->ID_setting ?>" style="margin-bottom:5px;"><b><?php echo $setting->name ?></b></label>
					<input id="setting_<?php echo $setting->ID_setting ?>" name="setting[<?php echo $setting->ID_setting ?>]" type="text" value="<?php echo $setting->value ?>">
				</div>
				<div class="large-8 medium-6 columns">
					<em style="line-height:140%;"><?php echo $setting->description ?></em>
				</div>
				<div class="column"><hr></div>
			</div>
			<?php
			break;
	}
}
?>
<div class="row">
	<div class="column">
		<input class="button tiny" name="save" type="submit" value="Speichern">
	</div>
</div>
</form>

<?php
template::get_admin_footer()
?>