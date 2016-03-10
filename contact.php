<?php
include('./functions.php');
general::maintenance();
require_once('./template/template.php');
template::pagetitle('Kontakt');
template::get_header();


//Sendet E-Mail mit Inhalt des Kontakformulars
if (isset($_POST['submit'])) {
	if(isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['message']) && validate_length(3, $_POST['firstname'], $_POST['lastname'], $_POST['message']) && validate_email($_POST['email'])) {
		$header = array();
		$header[] = "From: " . mb_encode_mimeheader("Kontaktformular", "utf-8", "Q") . " <kontaktformular@hostingverzeichnis.org>";
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: text/plain; charset=utf-8";
		$header[] = "Content-transfer-encoding: 8bit";
		
		$mailtext = "Vorname:\n" . $_POST['firstname'] . "\n\nNachname:\n" . $_POST['lastname'] . "\n\nE-Mail:\n" . $_POST['email'] . "\n\nNachricht:\n" . $_POST['message'];
		
		mail('info@hostingverzeichnis.org', mb_encode_mimeheader('Kontaktformular', "utf-8", "Q"), $mailtext, implode("\n", $header)) or die("Die E-Mail konnte nicht versendet werden.");
		echo '<script type="text/javascript">window.location = window.location.href.split("?")[0];</script>';
		return;
	}
} else {
	false;
}

?>

<section class="contact intro_image server"></section>
<section id="contact" class="container-12 clearfix">
	<div class="grid-12">
		<h2><span>Kon</span>takt</h2>
		<p>
        Hier haben Sie die Möglichkeit, mit uns in Verbindung zu treten. Wir bemühen uns, Ihr Anliegen schnellstmöglich zu bearbeiten. Wir geben unser bestes, damit das Hostingverzeichnis möglichst fehlerfrei bleibt. Um dies auch in Zukunft zu gewährleisten, sind wir auf Ihre Hilfe angewiesen. Helfen Sie mit und schicken Sie uns Ihre Verbesserungsvorschläge und Korrekturen. Vielen Dank!
		</p>
		<hr>
        <h4>E-Mail</h4>
		<div id="email">
			<a href="mailto:<?php echo general::encode_email('info@hostingverzeichnis.org') ?>"><?php echo general::encode_email('info@hostingverzeichnis.org') ?></a>
		</div>
		<hr>
        <h4>Kontaktformular</h4>
	</div>
	<form action="" method="post">
		<div class="grid-6">
			<input type="text" name="firstname" placeholder="Vorname">
			<input type="text" name="lastname" placeholder="Nachname">
			<input type="email" name="email" placeholder="E-Mail">
			<input type="submit" name="submit" value="Abschicken">
		</div>
		<div class="grid-6">
			<textarea name="message"></textarea>
		</div>
	</form>
</section>

<?php template::get_footer() ?>
