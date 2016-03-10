<?php
include('./functions.php');
general::maintenance();
require_once('./template/template.php');
template::pagetitle('Business');
template::get_header();

switch ($_GET['type']) {
    case 'shared':
        $t = 's';
		$type = 'shared';
        break;
    case 'virtual':
        $t = 'v';
		$type = 'virtual';
        break;
    case 'root':
        $t = 'r';
		$type = 'root';
        break;
    case 'game':
        $t = 'g';
		$type = 'game';
        break;
}
?>
<section class="<?php echo $type; $type!=' company'?' server':''; ?> intro_image server"></section>

<section class="container-12 clearfix">

	<form id="filter" class="grid-3">
    	<h3><span>Fil</span>ter</h3>
		<hr>        
		<div class="filter clearfix">
            <h4>Monatskosten</h4>
            <div class="cost range"></div>
            <div class="cost value_left"></div>
            <div class="cost value_right"></div>
        </div>
		<div class="filter clearfix">
            <h4>Speicherplatz</h4>
            <div class="space range"></div>
            <div class="space value_left"></div>
            <div class="space value_right"></div>
        </div>
		<?php
		switch ($t) {
			case 's':
				?>
				<div class="filter">
					<h4>SSL m&ouml;glich</h4>
					<input id="ssl" name="ssl" type="checkbox" value="ssl">
					<label for="ssl">SSL m&ouml;glich</label>
				</div>
				<?php
				break;
			case 'v':
			case 'r':
				?>
				<div class="filter">
					<h4>Festplatte</h4>
					<input id="hdd" name="disk" type="radio" value="hdd">
					<label for="hdd">HDD</label>
					<input id="ssd" name="disk" type="radio" value="ssd">
					<label for="ssd">SSD</label>
				</div>
				<div class="filter clearfix">
					<h4>Arbeitsspeicher</h4>
					<div class="ram range"></div>
					<div class="ram value_left"></div>
					<div class="ram value_right"></div>
				</div>
				<div class="filter">
					<h4>Plattform</h4>
					<input id="linux" name="platform" type="radio" value="linux">
					<label for="linux">Linux</label>
					<input id="windows" name="platform" type="radio" value="windows">
					<label for="windows">Windows</label>
				</div>
				<?php
				break;
			case 'g':
				?>
				<div class="filter clearfix">
					<h4>Slots</h4>
					<div class="slot range"></div>
					<div class="slot value_left"></div>
					<div class="slot value_right"></div>
				</div>
				<?php
				break;
		}
		?>
		<div class="filter">
            <h4>Serverstandort</h4>
            <div>
                <input id="de" name="location" type="checkbox" value="Deutschland">
                <label for="de">Deutschland</label>
            </div>
            <div>
                <input id="au" name="location" type="checkbox" value="&Ouml;sterreich">
                <label for="au">&Ouml;sterreich</label>
            </div>
            <div>
                <input id="ch" name="location" type="checkbox" value="Schweiz">
                <label for="ch">Schweiz</label>
            </div>
            <div>
                <input id="li" name="location" type="checkbox" value="Liechtenstein">
                <label for="li">Liechtenstein</label>
            </div>
        </div>
        <hr>
		<input name="<?php echo 'filter_' . $type ?>" type="button" value="Suchen">
        <a class="reset" href="./<?php echo $type . '-server' ?>">Reset</a>
	</form>

	<div id="server_list" class="grid-9">
    	<h2><span><?php echo ucfirst($type) ?></span> Server</h2>
		<?php
		switch ($t) {
			case 's':
				?>
				<div class="server info">Ein Shared Server wird meist von über hundert Kunden geteilt. Dies führt zu günstigen Preisen, aber auch einer bescheidenen Leistung.</div>     
				<?php
				break;
			case 'v':
				?>
				<div class="server info">Es befinden sich mehrere virtuelle Maschinen auf einem vServer. Der Kunde hat Rootzugriff, teilt sich die Harware aber mit anderen Klienten.</div>     
				<?php
				break;
			case 'r':
				?>
				<div class="server info">Der Kunde hat vollen Zugriff auf die gesamte Hardware. Dieser Typ ist vorallem für ressourcenhungrige Projekte geeignet.</div>     
				<?php
				break;
			case 'g':
				?>
				<div class="server info">Ein Game Server wird meist mit über hundert Kunden geteilt, wurde aber im Gegensatz zu üblichen Webhosting-Angeboten für Games optimiert.</div>     
				<?php
				break;
		}
		?>

<div class="clearfix">
	<?php
	$temp = product::all_status_type(1, $type);
	$count = $temp[1];
	$temp = $temp[0];
	$pages = ceil($count / setting::from_name('products_per_page')->value);
	echo $count == 1 ? '1 Ergebnis' : $count . ' Ergebnisse';
	?>
	<div id="order">
        Sortiere nach:
        <select>
            <option value="name">Alphabet</option>
            <option value="price">Preis</option>
            <option value="space">Speicherplatz</option>
        </select>
    </div>
</div>
<ul class="server list">
	<?php
	$product = null;
	foreach($temp as $row) {
	$product = new product($row);
	?>
	<li class="clearfix">
		<a href="./<?php echo $type ?>-server/<?php echo strtolower(str_replace(' ','-',company::from_id($product->company)->name)) . '/' . strtolower(str_replace(' ','-',$product->name)) . '/' . $product->ID_product?>">
			<div class="left">
				<h3><?php echo $product->name ?></h3>
				<?php echo company::from_id($product->company)->name ?>
			</div>
			<div class="right">
				<div class="left margin_right">
					<i class="fa fa-hdd-o"></i>
					<b><?php echo $product->space == 999 ? "&infin;" : $product->space ?></b> GB Speicherplatz<br>
					<i class="fa fa-database"></i>
					<b><?php echo $product->db == 999 ? "&infin;" : $product->db ?></b> <?php echo $product->db_type ?>-Datenbanken
				</div>
				<div class="left margin_right outer">
					<i class="fa fa-envelope"></i>
					<b><?php echo $product->email == 999 ? "&infin;" : $product->email ?></b> E-Mail-Accounts<br>
					<i class="fa fa-code-fork"></i>
					<b><?php echo $product->ftp == 999 ? "&infin;" : $product->ftp ?></b> FTP-Accounts<br>
				</div>
				<div class="left align_right">
					<div class="price">
						<?php
						echo strtoupper($product->currency);
						echo sprintf('%.2f', $product->price / 100);
						?>
						<span>/Monat</span>
					</div>
					Setupkosten:
					<?php
					echo strtoupper($product->currency);
					echo sprintf('%.2f', $product->setupcost / 100);
					?>
				</div>
			</div>
		</a>
	</li>
	<?php
	}
	?>	
</ul>
<div class="clearfix" id="navigation">
    <a href="#" class="left">&laquo; Vorherige Seite</a>
	<a href="#" class="right">N&auml;chste Seite &raquo;</a>
</div>
</section>
<script language="javascript" type="text/javascript" src="./template/js/filter_shared.js"></script>
<?php
template::get_footer() ?>