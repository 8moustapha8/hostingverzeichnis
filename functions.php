<?php
include_once('firelogger.php');

class database {

	private $host = 'localhost';
	private $user = '';
	private $pass = '';
	private $dbname = '';
	private $dbh;
	private $error;
	private $stmt;

	public function __construct() {
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);
		try {
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		} catch(PDOException $e) {
			$this->error = $e->getMessage();
		}
	}

	public function query($query) {
		$this->stmt = $this->dbh->prepare($query);
	}

	public function bind($param, $value, $type = null) {
		if(is_null($type)) {
			switch(true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}

	public function execute() {
		return $this->stmt->execute();
	}

	public function resultset() {
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function single() {
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function rowCount() {
		return $this->stmt->rowCount();
	}

	public function lastInsertId() {
		return $this->dbh->lastInsertId();
	}

	public function beginTransaction() {
		return $this->dbh->beginTransaction();
	}

	public function endTransaction() {
		return $this->dbh->commit();
	}

	public function cancelTransaction() {
		return $this->dbh->rollBack();
	}

	public function debugDumpParams() {
		return $this->stmt->debugDumpParams();
	}

}

class general {

	static function maintenance() {
		$maintenance_mode = setting::from_name('maintenance_mode');
		if($maintenance_mode == 1 && !user::check_login()) {
			header('Location: ./admin/login.php');
		}
	}

	static function enum_values($table, $column) {
		$database = new Database();
		$database->query('SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :table AND COLUMN_NAME = :column');
		$database->bind(':table', $table);
		$database->bind(':column', $column);
		$row  = $database->single();
		$enum = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
		return $enum;
	}

	static function encode_email($e) {
		for($i = 0; $i < strlen($e); $i++) {
			$output .= '&#' . ord($e[$i]) . ';';
		}
		return $output;
	}

}

class user {

	public $ID_user;
	public $status;
	public $email;
	public $password;
	public $firstname;
	public $lastname;
	public $last_login;
	public $registered;

	function __construct($m = null) {
		if(isset($m) && count($m)) {
			$this->populate($m);
		}
	}

	function populate(array $m) {
		$this->ID_user    = (int) $m["ID_user"];
		$this->status    = (int) $m["status"];
		$this->email      = (string) $m["email"];
		$this->password   = (string) $m["password"];
		$this->firstname  = (string) $m["firstname"];
		$this->lastname   = (string) $m["lastname"];
		$this->last_login = (string) $m["last_login"];
		$this->registered = (string) $m["registered"];
	}

	public static function current_user() {
		if(isset($_SESSION['ID_user'])) {
			$user = self::from_id((int)$_SESSION['ID_user']);
			return $user;
		} else {
			return new self();
		}
	}

	public static function from_id($id) {
		$user     = null;
		$database = new Database();
		$database->query('SELECT ID_user, status, email, password, firstname, lastname, last_login, registered FROM users WHERE ID_user = :id');
		$database->bind(':id', $id);
		$row  = $database->single();
		$user = new self($row);
		return $user;
	}

	public static function from_email($email) {
		$user = null;
		$database = new Database();
		$database->query('SELECT ID_user, status, email, password, firstname, lastname, last_login, registered FROM users WHERE email = :email');
		$database->bind(':email', $email);
		$row  = $database->single();
		$user = new self($row);
		return $user;
	}

	public static function all() {
		$database = new Database();
		$database->query('SELECT ID_user, status, email, password, firstname, lastname, last_login, registered FROM users');
		$rows = $database->resultset();
		return $rows;
	}

	public static function all_status($status) {
		$database = new Database();
		$database->query('SELECT ID_user, status, email, password, firstname, lastname, last_login, registered FROM users WHERE status = :status');
		$database->bind(':status', $status);
		$rows = $database->resultset();
		return $rows;
	}

	function create(){
		$database = new Database();
		$database->query('INSERT INTO users(email, password, firstname, lastname, registered) VALUES (:email, :password, :role, :company, :firstname, :lastname, :registered)');
		$database->bind(':email', $this->email);
		$database->bind(':password', $this->password);
		$database->bind(':firstname', $this->firstname);
		$database->bind(':lastname', $this->lastname);
		$database->bind(':registered', $this->registered);
		$database->execute();
	}

	function save(){
		$database = new Database();
		$database->query('UPDATE users SET email = :email, password = :password, firstname = :firstname, lastname = :lastname, registered = :registered WHERE ID_user = :id');
		$database->bind(':id', $this->ID_user);
		$database->bind(':email', $this->email);
		$database->bind(':password', $this->password);
		$database->bind(':firstname', $this->firstname);
		$database->bind(':lastname', $this->lastname);
		$database->bind(':registered', $this->registered);
		$database->execute();
	}

	function activate(){
		$database = new Database();
		$database->query('UPDATE users SET status = 1 WHERE ID_user = :id');
		$database->bind(':id', $this->ID_user);
		$database->execute();
	}

	function delete() {
		$database = new Database();
		$database->query('DELETE FROM users WHERE ID_user = :id');
		$database->bind(':id', $this->ID_user);
		$database->execute();
	}

	function check_validity($id) {
		$database = new Database();
		$database->query('SELECT ID_user FROM users WHERE ID_user = :id');
		$database->bind(':id', $id);
		$database->execute();
		return isset($id) && $id > 0 && $database->rowCount() > 0;
	}

	function check_password($password) {
		return password_verify($password, $this->password);
	}

	function login() {
		$_SESSION['ID_user'] = $this->ID_user;
		$_SESSION['logged']  = true;
		session_start();
		$this->last_login();
	}

	public static function check_login() {
		session_start();
		if(isset($_SESSION['logged'])) {
			return true;
		} else {
			false;
		}
	}

	function last_login() {
		$database = new Database();
		$database->query('UPDATE users SET last_login = :last_login WHERE ID_user = :id');
		$database->bind(':last_login', date('Y-m-d H:i:s'));
		$database->bind(':id', $this->ID_user);
		$database->execute();
	}

	function logout() {
		$_SESSION['ID_user'] = null;
		$_SESSION['logged']  = false;
		session_start();
		session_destroy();
		header('Location: ./login.php');
	}

}

class company {

	public $ID_company;
	public $status;
	public $name;
	public $email;
	public $url;
	public $street;
	public $plz;
	public $city;
	public $country;

	function __construct($m = null) {
		if(isset($m) && count($m)) {
			$this->populate($m);
		}
	}

	function populate(array $m) {
		$this->ID_company = (int) $m["ID_company"];
		$this->status     = (string) $m["status"];
		$this->name       = (string) $m["name"];
		$this->email      = (string) $m["email"];
		$this->url        = (string) $m["url"];
		$this->street     = (string) $m["street"];
		$this->plz        = (string) $m["plz"];
		$this->city       = (string) $m["city"];
		$this->country    = (string) $m["country"];
	}

	public static function from_id($id) {
		$company  = null;
		$database = new Database();
		$database->query('SELECT ID_company, status, name, email, url, street, plz, city, country FROM companies WHERE ID_company = :id');
		$database->bind(':id', $id);
		$row     = $database->single();
		$company = new self($row);
		return $company;
	}

	public static function all() {
		$database = new Database();
		$database->query('SELECT ID_company, status, name, email, url, street, plz, city, country FROM companies');
		$rows = $database->resultset();
		return $rows;
	}

	public static function all_status($status) {
		$database = new Database();
		$database->query('SELECT ID_company, status, name, email, url, street, plz, city, country FROM companies WHERE status = :status');
		$database->bind(':status', $status);
		$rows = $database->resultset();
		return $rows;
	}

	function create(){
		$database = new Database();
		$database->query('INSERT INTO companies(name, email, url, street, plz, city, country) VALUES (:name, :email, :url, :street, :plz, :city, :country)');
		$database->bind(':name', $this->name);
		$database->bind(':email', $this->email);
		$database->bind(':url', $this->url);
		$database->bind(':street', $this->street);
		$database->bind(':plz', $this->plz);
		$database->bind(':city', $this->city);
		$database->bind(':country', $this->country);
		$database->execute();
	}

	function save(){
		$database = new Database();
		$database->query('UPDATE companies SET name = :name, email = :email, url = :url, street = :street, plz = :plz, city = :city, country = :country WHERE ID_company = :id');
		$database->bind(':id', $this->ID_company);
		$database->bind(':name', $this->name);
		$database->bind(':email', $this->email);
		$database->bind(':url', $this->url);
		$database->bind(':street', $this->street);
		$database->bind(':plz', $this->plz);
		$database->bind(':city', $this->city);
		$database->bind(':country', $this->country);
		$database->execute();
	}

	function check_validity($id) {
		$database = new Database();
		$database->query('SELECT ID_company FROM companies WHERE ID_company = :id');
		$database->bind(':id', $id);
		$database->execute();
		return isset($id) && $id > 0 && $database->rowCount() > 0;
	}

	function delete() {
		$database = new Database();
		$database->query('DELETE FROM companies WHERE ID_company = :id');
		$database->bind(':id', $this->ID_company);
		$database->execute();
	}

	function activate(){
		$database = new Database();
		$database->query('UPDATE companies SET status = 1 WHERE ID_company = :id');
		$database->bind(':id', $this->ID_company);
		$database->execute();
	}

	static function one_existing_email($email) {
		$database = new Database();
		$database->query('SELECT email FROM companies WHERE email = :email');
		$database->bind(':email', $email);
		$database->execute();
		return $database->rowCount() > 1 ? true : false;
	}

}

class product {

	public $ID_product;
	public $status;
	public $type;
	public $name;
	public $url;
	public $location;
	public $company;
	public $price;
	public $setupcost;
	public $currency;
	public $space;
	public $space_type;
	public $traffic;
	public $db;
	public $db_type;
	public $email;
	public $ftp;
	public $domain;
	public $subdomain;
	public $has_ssl;
	public $cpu_core;
	public $ram;
	public $ram_dyn;
	public $platform;
	public $os;

	function __construct($m = null) {
		if(isset($m) && count($m)) {
			$this->populate($m);
		}
	}

	function populate(array $m) {
		$this->ID_product = (int) $m["ID_product"];
		$this->status = (int) $m["status"];
		$this->type = (string) $m["type"];
		$this->name = (string) $m["name"];
		$this->url = (string) $m["url"];
		$this->location = (string) $m["location"];
		$this->company = (int) $m["company"];
		$this->price = (int) $m["price"];
		$this->setupcost = (int) $m["setupcost"];
		$this->currency = (string) $m["currency"];
		$this->space = (int) $m["space"];
		$this->space_type = (string) $m["space_type"];
		$this->traffic = (int) $m["traffic"];
		$this->db = (int) $m["db"];
		$this->db_type = (string) $m["db_type"];
		$this->email = (int) $m["email"];
		$this->ftp = (int) $m["ftp"];
		$this->domain = (int) $m["domain"];
		$this->subdomain = (int) $m["subdomain"];
		$this->has_ssl = (int) $m["has_ssl"];
		$this->cpu_core = (int) $m["cpu_core"];
		$this->ram = (int) $m["ram"];
		$this->ram_dyn = (int) $m["ram_dyn"];
		$this->platform = (int) $m["platform"];
		$this->os = (string) $m["os"];
	}

	public static function from_id($id) {
		$company  = null;
		$database = new Database();
		$database->query('SELECT ID_product, status, type, name, url, location, company, price, setupcost, currency, space, space_type, traffic, db, db_type, email, ftp, domain, subdomain, has_ssl, cpu_core, ram, ram_dyn, platform, os FROM products WHERE ID_product = :id');
		$database->bind(':id', $id);
		$row     = $database->single();
		$company = new self($row);
		return $company;
	}

	public static function all() {
		$database = new Database();
		$database->query('SELECT ID_product, status, type, name, url, location, company, price, setupcost, currency, space, space_type, traffic, db, db_type, email, ftp, domain, subdomain, has_ssl, cpu_core, ram, ram_dyn, platform, os FROM products');
		$rows = $database->resultset();
		return $rows;
	}

	public static function all_status($status) {
		$database = new Database();
		$database->query('SELECT ID_product, status, type, name, url, location, company, price, setupcost, currency, space, space_type, traffic, db, db_type, email, ftp, domain, subdomain, has_ssl, cpu_core, ram, ram_dyn, platform, os FROM products WHERE status = :status');
		$database->bind(':status', $status);
		$rows = $database->resultset();
		return $rows;
	}

	public static function all_status_type($status, $type) {
		$database = new Database();
		$database->query('SELECT ID_product, status, type, name, url, location, company, price, setupcost, currency, space, space_type, traffic, db, db_type, email, ftp, domain, subdomain, has_ssl, cpu_core, ram, ram_dyn, platform, os FROM products WHERE status = :status AND type = :type');
		$database->bind(':status', $status);
		$database->bind(':type', $type);
		$rows = $database->resultset();
		$count = $database->rowCount();
		return array($rows, $count);
	}

	public static function random_from_company($company, $current_product, $limit) {
		$database = new Database();
		$database->query('SELECT ID_product, status, type, name, url, location, company, price, setupcost, currency, space, space_type, traffic, db, db_type, email, ftp, domain, subdomain, has_ssl, cpu_core, ram, ram_dyn, platform, os FROM products WHERE ID_product <> :current_product AND company = :company ORDER BY RAND() LIMIT 0, :limit');
		$database->bind(':current_product', $current_product);
		$database->bind(':company', $company);
		$database->bind(':limit', $limit);
		$rows = $database->resultset();
		return $rows;
	}

}

class validate {

	static function min_length($var, $length) {
		return strlen($var) >= $length ? true : false;
	}

	static function max_length($var, $length) {
		return strlen($var) <= $length ? true : false;
	}

	static function text($text) {
		return ctype_alpha($text) ? true : false;
	}

	static function text_space($text) {
		return ctype_alpha(str_replace(' ', '', $text)) ? true : false;
	}

	static function integ($int) {
		return filter_var($int, FILTER_VALIDATE_INT) ? true : false;
	}

	static function number($number) {
		return is_numeric($number) ? true : false;
	}

	static function link($url) {
		return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
	}

	static function unused_email($email) {
		$database = new Database();
		$database->query('SELECT email FROM users WHERE email = :email');
		$database->bind(':email', $email);
		$database->execute();
		return $database->rowCount() > 0 ? true : false;
	}

	static function one_existing_email($email) {
		$database = new Database();
		$database->query('SELECT email FROM users WHERE email = :email');
		$database->bind(':email', $email);
		$database->execute();
		return $database->rowCount() > 1 ? true : false;
	}

	static function email($email) {
		$domain = substr(strrchr($email, "@"), 1);
		return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
	}

	static function url($url) {
		return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
	}

}

class setting {

	public $ID_setting;
	public $name;
	public $value;
	public $type;
	public $description;

	function __construct($m = null) {
		if(isset($m) && count($m)) {
			$this->populate($m);
		}
	}

	function populate(array $m) {
		$this->ID_setting = (int) $m["ID_setting"];
		$this->name = (string) $m["name"];
		$this->value = (string) $m["value"];
		$this->type = (string) $m["type"];
		$this->description = (string) $m["description"];
	}

	public static function all() {
		$database = new Database();
		$database->query('SELECT ID_setting, name, value, type, description FROM settings');
		$rows = $database->resultset();
		return $rows;
	}

	public static function from_id($id) {
		$setting  = null;
		$database = new Database();
		$database->query('SELECT ID_setting, name, value, type, description FROM settings WHERE ID_setting = :id');
		$database->bind(':id', $id);
		$row = $database->single();
		$setting = new self($row);
		return $setting;
	}

	public static function from_name($name) {
		$setting  = null;
		$database = new Database();
		$database->query('SELECT ID_setting, name, value, type, description FROM settings WHERE name = :name');
		$database->bind(':name', $name);
		$row = $database->single();
		$setting = new self($row);
		return $setting;
	}

	function save(){
		$database = new Database();
		$database->query('UPDATE settings SET ID_setting = :id, name = :name, value = :value, type = :type, description = :description WHERE ID_setting = :id');
		$database->bind(':id', $this->ID_setting);
		$database->bind(':name', $this->name);
		$database->bind(':value', $this->value);
		$database->bind(':type', $this->type);
		$database->bind(':description', $this->description);
		$database->execute();
	}

}

?>
