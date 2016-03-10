<?php
include('./functions.php');
general::maintenance();
require_once('./template/template.php');
template::pagetitle('Produkte');
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

$id = $_GET['id'];
$product = null;
$product = product::from_id($id);
$company = null;
$company = company::from_id($product->company);

if($_GET['company'] != strtolower(str_replace(' ','-',$company->name)) || $_GET['product'] != strtolower(str_replace(' ','-',$product->name))) {
	header('Location: /');
}
?>
	<section class="<?php echo $type; $type!=' company'?' server':''; ?> intro_image server"></section>

	<section class="container-12 clearfix">
		<div class="grid-7">
			<h3><span><?php echo ucfirst($type) ?> </span>Server</h3>
            <ul>      
				<li>Name: <?php echo $product->name ?></li>
				<li>URL: <?php echo $product->url ?></li>
				<li>Serverstandort: <?php if($product->location == "de") echo "Deutschland"; elseif($product->location == "au") echo "&Ouml;sterreich"; elseif($product->location == "ch") echo "Schweiz"; elseif($product->location == "li") echo "Liechtenstein" ?></li>
				<li>Monatspreis: <?php echo strtoupper($product->currency) ?><?php echo sprintf('%.2f', $product->price / 100); ?></li>
				<li>Setupkosten: <?php echo strtoupper($product->currency) ?><?php echo sprintf('%.2f', $product->setupcost / 100); ?></li>
				<li>Speicherplatz: <?php echo $product->space == 999 ? "&infin;" : $product->space ?> GB</li>
				<li>Traffic: <?php echo $product->traffic == 999 ? "&infin;" : $product->traffic ?> TB</li>
				<li>Datenbanken: <?php echo $product->db == 999 ? "&infin;" : $product->db; echo ' ' . $product->db_type ?></li>
				<li>E-Mail-Accounts<?php echo $product->email == 999 ? "&infin;" : $product->email ?></li>
				<li>FTP-Accounts<?php echo $product->ftp == 999 ? "&infin;" : $product->ftp ?></li>
				<li>Domains: <?php echo $product->domain == 999 ? "&infin;" : $product->domain ?></li>
				<li>Subdomains: <?php echo $product->subdomain == 999 ? "&infin;" : $product->subdomain ?></li>
				<li>SSL-Zertifikat: <?php echo $product->space == 1 ? "Ja" : "Nein" ?></li>
            </ul>
		</div>
		<div id="company" class="grid-5">
            <iframe width="100%" height="200" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=<?php echo rawurlencode($company->street . ', ' . $company->plz . ' ' . $company->city . ', ' . $company->country) ?>&key=AIzaSyCxMNOW2Obaxjz5l6dEvntaJ12xwepvMyo"></iframe>
            <div class="content">
            	<h3><span>Unter</span>nehmen</h3>
            	<div class="margin_top clearfix">
                    <div class="left">
                        <h4><?php echo $company->name ?></h4>
                        <p>
                            <?php
                            echo $company->street . '<br>';
                            echo $company->plz . ' ' . $company->city . '<br>';
                            echo $company->country;
                            ?>
                        </p>
                    </div>
                    <div class="right">
                        <a href="<?php echo $company->url ?>" title="<?php echo $company->name ?>" target="_blank"><i class="fa fa-lg fa-globe"></i></a>
                        <a href="mailto:<?php echo $company->email ?>" title="<?php echo $company->email ?>"><i class="fa fa-lg fa-envelope"></i></a>
                    </div>
                </div>
                <hr>
                <h4>Andere Produkte des Unternehmens:</h4>
				<ul>
					<?php foreach(product::random_from_company($product->company, $product->ID_product, (int)setting::from_name('other_products')->value) as $row) {
						$product = new product($row);
						$company = company::from_id($product->company);
					?>
					<li>
						<a href="./<?php echo $product->type ?>-server/<?php echo strtolower(str_replace(' ','-',$company->name)) . '/' . strtolower(str_replace(' ','-',$product->name)) . '/' . $product->ID_product?>" title="<?php echo $company->name . ' – ' . $product->name . ' – ' . ucfirst($product->type) . ' Server' ?>">
							<?php echo ucfirst($product->type) . ' Server, ' . $product->name . ', ' . $company->name ?>
						</a>
					</li>
					<?php } ?>
				</ul>
            </div>
       	</div>
	</section>

<?php template::get_footer() ?>