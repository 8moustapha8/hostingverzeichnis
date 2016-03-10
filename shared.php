<?php
include('./functions.php');
general::maintenance();
require_once('./template/template.php');
template::pagetitle('Business');
template::get_header();

//Checkt, ob "Shared", "Virtual", "Root" oder "Game"
echo 'list';
echo $_GET['type'];
die;

//DB-Verbindung herstellen
include('./db_connect.php');

//Wenn "Shared"
if($server_type == 'shared') {
	//Liest Sortierung aus
	if($_GET['order'] == "server_name" || $_GET['order'] == "server_price" || $_GET['order'] == "server_space"){
		$order = $_GET['order'];
	} else {
		$order = "server_name";
	}
	//"Shared" total Ergebnisse in der DB ($total), wird für Seitennavigation benötigt
	try {
		$sql = 'SELECT ID_server FROM servers WHERE server_type = "shared" AND server_status = 1';
		$stmt = $db_handle -> prepare($sql);
		$stmt -> execute();
		$total = $stmt -> rowCount();
		$stmt -> closeCursor;
	} catch(PDOException $e) {
		print( $e -> getMessage() );
		print( $e -> getTraceAsString() );
		exit();
	}
?>
<section class="shared intro_image server"></section>

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
		<div class="filter">
            <h4>SSL m&ouml;glich</h4>
            <input id="ssl" name="ssl" type="checkbox" value="ssl">
            <label for="ssl">SSL m&ouml;glich</label>
        </div>
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
		<input id="filter_shared" type="button" value="Suchen">
        <a class="reset" href="./server.php?type=shared">Reset</a>
	</form>

	<div id="server_list" class="grid-9">
    	<h2><span>Shared</span> Server</h2>
        <div class="server info">Ein Shared Server wird meist von über hundert Kunden geteilt. Dies führt zu günstigen Preisen, aber auch einer bescheidenen Leistung.</div>     

<div class="clearfix">
	<div id="order">
        Sortiere nach:
        <select onchange="setGetParameter('order', this.value);">
            <option value="server_name" <?php if($order == "server_name") echo 'selected="selected"' ?>>Alphabet</option>
            <option value="server_price" <?php if($order == "server_price") echo 'selected="selected"' ?>>Preis</option>
            <option value="server_space" <?php if($order == "server_space") echo 'selected="selected"' ?>>Speicherplatz</option>
        </select>
    </div>
</div>
<?php
try {
	//Ergebnisse pro Seite
	$ppp = 5;
	//Aktuelle Seite bestimmen
	if(isset($_GET['page']) && is_numeric($_GET['page'])){
		$page = $ppp * $_GET['page'] - $ppp;
		if($page < 0) {
			$page = 0;
		}
	} else {
		$page = 0;
	}
	//Checkt, ob SSL im Filter aktiviert wurde
	if(isset($_GET['ssl']) && $_GET['ssl'] == 1){
		$ssl_sql = " AND server_ssl = 1";
	} else {
		$ssl_sql = "";
	}
	//Serverstandort aus Filter (bzw. URL) auslesen
	$lang = split("-", $_GET['lang']);
	if(count($lang) != 1) {
		$sql_lang = " AND server_location = (";
		for($i = 0; $i < count($lang) - 1; $i++) {
			if($i == count($lang) - 2) {
				$sql_lang = $sql_lang . "'" . $lang[$i] . "'";
			} else {
				$sql_lang = $sql_lang . "'" . $lang[$i] . "' OR ";
			}
		}
		$sql_lang = $sql_lang . ")";
	} else {
		$sql_lang = "";
	}
	//DB-Abfrage mit Filtern, Sortierung
	$sql = 'SELECT * FROM servers, companies WHERE server_type = "shared" AND server_status = 1 AND server_company = ID_company AND :cost_from <= server_price AND server_price <= :cost_to AND :space_from <= server_space AND server_space <= :space_to' . $ssl_sql . $sql_lang . ' ORDER BY ' . $order . ' ASC LIMIT :page, :ppp';
	$servers = array();
	$stmt = $db_handle -> prepare($sql);
	if(isset($_GET['cost_from']) && validate_number($_GET['cost_from'])){
		$cost_from = 100 * $_GET['cost_from'];
		$stmt -> bindParam(':cost_from', $cost_from, PDO::PARAM_INT);
	} else {
		$cost_from = 100;
		$stmt -> bindParam(':cost_from', $cost_from, PDO::PARAM_INT);
	}
	if(isset($_GET['cost_to']) && validate_number($_GET['cost_to'])){
		$cost_to = 100 * $_GET['cost_to'];
		$stmt -> bindParam(':cost_to', $cost_to, PDO::PARAM_INT);
	} else {
		$cost_to = 5000;
		$stmt -> bindParam(':cost_to', $cost_to, PDO::PARAM_INT);
	}
	if(isset($_GET['space_from']) && validate_number($_GET['space_from'])){
		$space_from = $_GET['space_from'];
		$stmt -> bindParam(':space_from', $space_from, PDO::PARAM_INT);
	} else {
		$space_from = 1;
		$stmt -> bindParam(':space_from', $space_from, PDO::PARAM_INT);
	}
	if(isset($_GET['space_to']) && validate_number($_GET['space_to'])){
		$space_to = $_GET['space_to'];
		$stmt -> bindParam(':space_to', $space_to, PDO::PARAM_INT);
	} else {
		$space_to = 100;
		$stmt -> bindParam(':space_to', $space_to, PDO::PARAM_INT);
	}
	$stmt -> bindParam(':page', $page, PDO::PARAM_INT);
	$stmt -> bindParam(':ppp', $ppp, PDO::PARAM_INT);
	$stmt -> bindColumn('ID_server', $id);
	$stmt -> bindColumn('server_name', $name);
	$stmt -> bindColumn('company_name', $company_name);
	$stmt -> bindColumn('server_price', $price);
	$stmt -> bindColumn('server_currency', $currency);
	$stmt -> bindColumn('server_setupcost', $setupcost);
	$stmt -> bindColumn('server_space', $space);
	$stmt -> bindColumn('server_database', $database);
	$stmt -> bindColumn('server_database_type', $database_type);
	$stmt -> bindColumn('server_email', $email);
	$stmt -> bindColumn('server_location', $location);
	$stmt -> execute();
	$count = $stmt -> rowCount();
	if($stmt -> rowCount() > 0) {
	while( $stmt -> fetch() ) {
		$servers[] = array('id' => $id, 'name' => $name, 'company_name' => $company_name, 'price' => $price, 'setupcost' => $setupcost, 'currency' => $currency, 'space' => $space, 'database' => $database, 'database_type' => $database_type, 'email' => $email, 'location' => $location);
	}
	$stmt -> closeCursor;
?>

<ul class="server list">
<?php
	//Ausgabe
	foreach($servers as $server) {
?>

<li class="clearfix">
	<a href="./product.php?type=shared&id=<?php echo $server['id'] ?>">
        <div class="left">
            <h3><?php echo $server['name'] ?></h3>
            <?php echo $server['company_name'] ?>
        </div>
        <div class="right">
            <div class="left margin_right">
                <i class="fa fa-hdd-o"></i> <b><?php if($server['space'] == 999) echo "&infin;"; else echo $server['space'] ?></b> GB Speicherplatz<br>
                <i class="fa fa-database"></i> <b><?php if($server['database'] == 999) echo "&infin;"; else echo $server['database'] ?></b> <?php echo $server['database_type'] ?>-Datenbanken<br>
            </div>
            <div class="left margin_right outer">
                <i class="fa fa-envelope"></i> <b><?php if($server['email'] == 999) echo "&infin;"; else echo $server['email'] ?></b> E-Mail-Accounts<br>
              <i class="fa fa-map-marker"></i> Serverstandort: <b><?php if($server['location'] == "de") echo "Deutschland"; elseif($server['location'] == "au") echo "&Ouml;sterreich"; elseif($server['location'] == "ch") echo "Schweiz"; elseif($server['location'] == "li") echo "Liechtenstein" ?></b>
            </div>
            <div class="left align_right">
                <div class="price"><?php echo strtoupper($server['currency']) ?> <?php echo sprintf('%.2f', $server['price'] / 100); ?> <span>/Monat</span></div>
                Setupkosten: <?php echo strtoupper($server['currency']) ?> <?php echo sprintf('%.2f', $server['setupcost'] / 100); ?>
            </div>
        </div>
    </a>
</li>
<?php
}
?>
</ul>
<div class="clearfix" id="navigation">
<?php
//Seiten Navigation
if(isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 1) {
?>	
    <a href="#" class="left" onclick="setGetParameter('page', <?php echo $_GET['page'] - 1 ?>);">&laquo; Vorherige Seite</a>
<?php
}
if(isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] < floor($count / $ppp) + 1) {
?>	
	<a href="#" class="right" onclick="setGetParameter('page', <?php echo $_GET['page'] + 1 ?>);">N&auml;chste Seite &raquo;</a>
<?php
} elseif(empty($_GET['page']) && $total > $ppp) {
?>	
	<a href="#" class="right" onclick="setGetParameter('page', 2);">N&auml;chste Seite &raquo;</a></div>
<?php
}
} else {
?>
Keine Ergebnisse gefunden.
<?php
}

} catch(PDOException $e) {
	print( $e -> getMessage() );
	print( $e -> getTraceAsString() );
	exit();
}
?>        
    </div>
</section>
<script language="javascript" type="text/javascript" src="./template/js/filter_shared.js"></script>

<?php }
template::get_footer() ?>