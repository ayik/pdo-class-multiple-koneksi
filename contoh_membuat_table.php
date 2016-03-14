<?php 
require 'database_new.php';

$db = new eQuery("mysql", "127.0.0.1", "test","root", "admin123");
$con = $db->connect();
if($con==false) die("Ooopss: Gagal melakukan koneksi ke database, cek settingan koneksi anda.");
echo $db->getError();

// Hapus table jika sudah ada 
$db->query('DROP TABLE IF EXISTS tbl_users;');
$query_buat_table = <<< EOD
CREATE TABLE tbl_users (
	id int(5) NOT NULL AUTO_INCREMENT,
	nama varchar(100) NOT NULL,
	alamat varchar(100) NOT NULL,
	hobi varchar(100) NOT NULL, 
	skill varchar(100) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `nama` (`nama`)
);
EOD;

$db->query($query_buat_table);
$db->query("ALTER TABLE tbl_users ADD sex CHAR(1);");
$db->disconnect();