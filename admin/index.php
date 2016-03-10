<?php
include('../functions.php');
if(!user::check_login()){header('Location: ./login.php'); die;}
require_once( '../template/template.php' );
template::pagetitle('Dashboard');
template::get_admin_header();
$user = user::current_user();

$user = user::current_user();
?>

<div class="row">
	<div class="large-12 columns">
        <h3>Dashboard</h3>
        <div data-alert class="alert-box secondary">
            Willkommen, <?php echo $user->firstname; ?>! Hier hast du alles im &Uuml;berblick.
        </div>
    </div>
</div>
<div class="row">
	<div class="large-6 columns">
		<div id="widgetIframe">
		</div>
	</div>
	<div class="large-6 columns">
		<div id="widgetIframe">
		</div>
		<div id="widgetIframe">
		</div>
	</div>
</div>

<?php template::get_admin_footer() ?>
