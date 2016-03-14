<?php
/**
 * @copyright	Copyright (C) 2016.
 * @description	 Koneksi dengan banyak database menggunakan PDO.
 * Database yang didukung:
 * sqlsrv	-> Microsoft SQL Server (driver berfungsi pada semua versi SQL Server [max. versi 2008]) - lulus TES
 * mssql 	-> Microsoft SQL Server (driver berfungsi hanya pada versi SQL Server 2000) - lulus TES
 * mysql 	-> driver MySQL (status lulus TES).
*/

class eQuery
{
	/* Variable $tipe_database dengan semua driver database yang didukung */
	private $tipe_databases = array("sqlsrv", "mssql", "mysql");
	/* Variable $host, alamat databse server */
	private $host;
	/* Variable $database, Nama database */
	private $database;
	/* Variable $user, User database */
	private $user;
	/* Variable $pass, Password database */
	private $pass;
	/* Variable $tipe_database, ekplisit tipe */
	private $tipe_database;

	/* Variable $sql, eksekusi query */
	private $sql;
	/* Variable $con, objek koneksi ke database */
	private $con;
	/* Variable $err_msg, pesan error */
	private $err_msg = "";

	/* Konstruk koneksi ke database */
	public function __construct($tipe_database, $host, $database, $user, $pass)
	{
		$this->tipe_database = strtolower($tipe_database);
		$this->host = $host;
		$this->database = $database;
		$this->user = $user;
		$this->pass = $pass;
	}

	/* Fungsi koneksi ke database berdasarkan driver database yang digunakan */
	public function connect()
	{
		if (in_array($this->tipe_database, $this->tipe_databases)) {
			try {
				switch ($this->tipe_database) {
					case 'mssql':
						$this->con = new PDO("mssql:host=".$this->host.";database=".$this->database, $this->user, $this->pass);
						break;
					case 'sqlsrv':
						$this->con = new PDO("sqlsrv:server=".$this->host.";database=".$this->database, $this->user, $this->pass);
						break;
					case 'mysql':
						$this->con = new PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->user, $this->pass);
						break;					
					default:
						$this->con = null;
						break;
				}

				$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $this->con;
			} catch (PDOException $e) {
				$this->err_msg = "Ooooppss: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Oooopss: Gagal melakukan koneksi ke database ( Driver database tidak didukung )";
			return false;
		}
	}

	/* fungsi singkat untuk eksekusi perintah query */
	public function query($sql_statement)
	{
		$this->err_msg = "";
		if ($this->con!=null) {
			try {
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);
			} catch (PDOException $e) {
				$this->err_msg = "Oooopss: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = " Oooopss: Gagal melakukan koneksi ke database";
			return false;
		}
	}

	/* Fungsi eksekusi query string aman dari sql injection */
	public function query_secure($sql_statement, $params, $fetch_rows=false, $unnamed=false, $delimiter="|")
	{
		$this->err_msg = "";
		if (!isset($unnamed)) $unnamed = false;
		if (trim((string)$delimiter)=="") {
			$this->err_msg = "Oooopss: Delimiter kosong.";
			return false;
		}
		if ($this->con!=null) {
			$obj = $this->con->prepare($sql_statement);
			if (!$unnamed) {
				for ($i=0; $i < count($params); $i++) { 
					$params_split = explode($delimiter, $params[$i]);
					(trim($params_split[2])=="INT") ? $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_INT) : $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_STR);
				}
				try {
					$obj->execute();
				} catch (PDOException $e) {
					$this->err_msg = "Oooopss: ". $e->getMessage();
					return false;
				}
			}else{
				try {
					$obj->execute($params);
				} catch (PDOException $e) {
					$this->err_msg = "Oooopss: ". $e->getMessage();
					return false;
				}
			}
			if ($fetch_rows)
				return $obj->fetchAll(PDO::FETCH_ASSOC);
			if (is_numeric($this->con->lastInsertId()))
				return $this->con->lastInsertId();
			return true;
		}else{
			$this->err_msg = "Oooopss: Gagal melakukan koneksi ke database";
			return false;
		}
	}

	/* Fungsi eksekusi insert/input data ke database */
	public function insert($table, $data)
	{
		$this->err_msg = "";
		if ($this->con!=null) {
			try{
				$txt_fields = "";
				$txt_values = "";
				$data_column = explode(",", $data);
				for ($i=0; $i < count($data_column) ; $i++) { 
					list($field, $value) = explode("=", $data_column[$x]);
					$txt_fields.= ($x==0) ? $field : ",".$field;
					$txt_values.= ($x==0) ? $value : ",".$value;
				}
				$this->con->exec("INSERT INTO ".$table." (".$txt_fields.") VALUES(".$txt_values.");");
				return $this->con->lastInsertId();
			}catch(PDOException $e){
				$this->err_msg = "Oooopss: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Oooopss: Gagal melakukan koneksi ke database.";
			return false;
		}
	}

	/* fungsi mendapatkan pesan error */
	public function getError()
	{
		return trim($this->err_msg)!="" ? $this->err_msg : "";
	}

	/* fungsi mengakhiri koneksi */
	public function disconnect()
	{
		$this->err_msg = "";
		if ($this->con) {
			$this->con = null;
			return true;
		}else{
			$this->err_msg = "Oooopss: Gagal melakukan koneksi ke database";
			return false;
		}
	}
}