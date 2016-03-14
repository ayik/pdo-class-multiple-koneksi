<?php 
require 'database_new.php';

$db = new eQuery("mysql", "127.0.0.1", "test","root", "admin123");
$con = $db->connect();
if($con==false) die("Ooopss: Gagal melakukan koneksi ke database, cek settingan koneksi anda.");
echo $db->getError();

/* Input data dengan "NAMED PLACEHOLDERS": */
$params = array(":nama|awanputih|STR",
	":alamat|medan|STR",
	":hobi|berenang|STR",
	":skill|freelancer|STR",
	":sex|L|STR");
$result = $db->query_secure("INSERT INTO tbl_users (nama,alamat,hobi,skill,sex) VALUE(:nama,:alamat,:hobi,:skill,:sex);", $params, false, false);

/* Input data dengan "UNNAMED PLACEHOLDERS": */
$params = array("Budi Setiawan",
	"Kisaran",
	"Nonton Movie",
	"Designer",
	"L");
$result = $db->query_secure("INSERT INTO tbl_users (nama,
	alamat,
	hobi,
	skill,
	sex) VALUES(?,?,?,?,?);", $params, false, true);
$db->disconnect();