<?php 
require 'database_new.php';

$db = new eQuery("mysql", "127.0.0.1", "test","root", "admin123");
$con = $db->connect();
if($con==false) die("Ooopss: Gagal melakukan koneksi ke database, cek settingan koneksi anda.");
echo $db->getError();

$db->