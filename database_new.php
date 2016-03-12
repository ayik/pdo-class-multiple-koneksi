<?php
/**
 * @copyright	Copyright (C) 2016.
*/

class eQuery
{
	/* Variable $tipe_database dengan semua driver database yang didukung */
	private $tipe_database = array("sqlsrv", "mssql", "mysql");
	/* Variable $host, alamat databse server */
	private $host;
	/* Variable $database, Nama database */
	private $database;
	/* Variable $user, User database */
	private $user;
	/* Variable $pass, Password database */
	private $pass;
	/* Variable $tipe_database, ekplisit tipe */
	private $tipe_database

	/* Variable $sql, eksekusi query */
	private $sql;
	/* Variable $con, objek koneksi ke database */
	private $con;
	/* Variable $err_msg, pesan error */
	private $err_msg = "";

	/* Konstruk koneksi ke database */
	public function __construct($tipe_database, $host, $database, $user, $Password)
	{
		$this->tipe_database = strtolower($tipe_database);
		$this->host = $host;
		$this->database = $database;
		$this->user = $user;
		$this->pass = $pass;
	}

	/* Fungsi koneksi ke database */
	public function koneksi()
	{
		# code...
	}
}