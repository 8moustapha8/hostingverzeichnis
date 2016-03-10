<?php
include('./functions.php');
general::maintenance();
require_once('./template/template.php');
template::pagetitle('Home');
template::get_header();
?>

<section class="intro_image home"></section>
<section id="slogan" class="clearfix">
	<div class="container-12">
		<div class="grid-12">
			<p>Das kostenlose Hosting-Vergleichsportal.</p>
		</div>
	</div>
</section>
<section id="features" class="container-12 clearfix">
	<div class="grid-3">
		<div id="eagle" class="icon"></div>
		<h3><span>Un</span>abh&auml;ngig</h3>
		<!--Vektor: Faria Malik--> 
	</div>
	<div class="grid-3">
		<div id="fist" class="icon"></div>
		<h3><span>Com</span>munity</h3>
		<!--Vektor: Alexis--> 
	</div>
	<div class="grid-3">
		<div id="tick" class="icon"></div>
		<h3><span>Ein</span>fach</h3>
		<!--Vektor: Oksana Khristenko--> 
	</div>
	<div class="grid-3">
		<div id="coins" class="icon"></div>
		<h3><span>Kosten</span>los</h3>
		<!--Vektor: Freepik--> 
	</div>
</section>
<section id="products" class="slider_wrapper">
	<div class="slider container-12">
		<div class="slide">
			<h3><span>Shared</span> Server</h3>
			<div class="sep"></div>
			<div class="description">Ein Shared Server wird meist von über hundert Kunden geteilt. Dies führt zu günstigen Preisen, aber auch einer bescheidenen Leistung.</div>
			<div class="sep"></div>
			<a href="./server.php?type=shared">Shared Server vergleichen &raquo;</a> </div>
		<div class="slide">
			<h3><span>Virtual</span> Server</h3>
			<div class="sep"></div>
			<div class="description">Es befinden sich mehrere virtuelle Maschinen auf einem vServer. Der Kunde hat Rootzugriff, teilt sich die Harware aber mit anderen Klienten.</div>
			<div class="sep"></div>
			<a href="./server.php?type=virtual">Virtual Server vergleichen &raquo;</a> </div>
		<div class="slide">
			<h3><span>Root</span> Server</h3>
			<div class="sep"></div>
			<div class="description">Der Kunde hat vollen Zugriff auf die gesamte Hardware. Dieser Typ ist vorallem für ressourcenhungrige Projekte geeignet.</div>
			<div class="sep"></div>
			<a href="./server.php?type=root">Root Server vergleichen &raquo;</a> </div>
		<div class="slide">
			<h3><span>Game</span> Server</h3>
			<div class="sep"></div>
			<div class="description">Ein Game Server wird meist mit über hundert Kunden geteilt, wurde aber im Gegensatz zu üblichen Webhosting-Angeboten für Games optimiert.</div>
			<div class="sep"></div>
			<a href="./server.php?type=game">Game Server vergleichen &raquo;</a> </div>
	</div>
</section>
<section id="bubbles" class="container-12 clearfix">
	<div id="bubble_1" class="bubbles grid-6"> Genau was ich gesucht habe: &Uuml;bersichtlich aufgebaut und viele Filtereinstellungen. Das Hostingverzeichnis kann ich wirklich nur weiterempfehlen!
		<div class="author">&ndash; Sergio Vercelli</div>
	</div>
	<div id="bubble_2" class="bubbles grid-6"> Bester Hostingvergleich im Netz. Und das ganz ohne störende Werbung. Well done.
		<div class="author">&ndash; Chiara Eckert</div>
	</div>
</section>
<script language="javascript" type="text/javascript">
	$(".slider").owlCarousel({
		  navigation : true,
		  pagination : true,
		  slideSpeed : 300,
		  paginationSpeed : 400,
		  singleItem:true
	});
</script>
<?php template::get_footer() ?>
