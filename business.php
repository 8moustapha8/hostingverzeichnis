<?php
include('./functions.php');
general::maintenance();
require_once('./template/template.php');
template::pagetitle('Business');
template::get_header();
?>

<div class="business intro_image server"></div>
<section class="container-12">
	<h2><span>Busi</span>ness</h2>
	<ul class="kwicks kwicks-horizontal">
		<li id="panel-1">
			<div>
				<h4>Produkt hinzuf&uuml;gen</h4>
				<p> Tragen Sie Ihr neues Produkt ein und verknüpfen Sie es mit Ihrem Unternehmen. </p>
				<a href="">Produkt hinzuf&uuml;gen &raquo;</a>
			</div>
		</li>
		<li id="panel-2">
			<div>
				<h4>Unternehmen eintragen</h4>
				<p> Tragen Sie Ihr Unternehmen ein, damit Ihre zuk&uuml;nftigen Kunden Sie finden. </p>
				<a href="">Unternehmen eintragen &raquo;</a>
			</div>
		</li>
		<li id="panel-3">
			<div>
				<h4></h4>
				<p> Erreichen Sie Ihre Wunschzielgruppe mithilfe nur weniger Klicks. </p>
				<a href="">Werbung schalten &raquo;</a>
			</div>
		</li>
	</ul>
</section>
<script language="javascript" type="text/javascript">
	if (screen.width < 960) {
		$('.kwicks').kwicks({
			maxSize: '75%',
			behavior: 'menu',
			spacing: 0,
			isVertical: 1
		});
	} else {
		$('.kwicks').kwicks({
			maxSize: '75%',
			behavior: 'menu',
			spacing: 0,
			isVertical: 0
		});
	}
</script>
<?php template::get_footer() ?>
