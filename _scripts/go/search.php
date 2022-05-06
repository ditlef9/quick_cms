<?php
/**
*
* File: search.php
* Version 1.0.0
* Date 19:32 11.11.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



/*- Functions ------------------------------------------------------------------------ */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/quote_smart.php");
include("../../_admin/_functions/clean.php");

/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name_clean = clean($server_name);


/*- MySQL ---------------------------------------------------------------------------- */
$mysql_config_file = "../../_admin/_data/mysql_" . $server_name_clean . ".php";
if(file_exists($mysql_config_file)){
	include("$mysql_config_file");
	$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
}
else{
	echo"Please run setup. MySQL settings not found"; die;
}

/*- Table exists? -------------------------------------------------------------------- */
$query = "SELECT * FROM queries LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){		
}
else{
	mysqli_query($link, "CREATE TABLE queries(
	 query_id INT NOT NULL AUTO_INCREMENT,
	 PRIMARY KEY(query_id), 
	 query_name VARCHAR(90) NOT NULL,
	 query_times BIGINT,
	 query_last_use DATETIME,
	 query_hidden INT)")
	 or die(mysql_error());
}



if(isset($_GET['q'])) {
	$q = $_GET['q'];
	$q = strtolower($q);


	// Check query
	$inp_datetime = date("Y-m-d H:i:s");
	$inp_q = output_html($q);
	$inp_q_mysql = quote_smart($link, $inp_q);




	$query = "SELECT query_name, query_times FROM queries WHERE query_name=$inp_q_mysql";
	$res = mysqli_query($link, $query);
	$row = mysqli_fetch_row($res);
	$get_query_name = $row[0];
	$get_query_times = $row[1];

	if($get_query_name == ""){
		// Insert
		$insert_error = "0";
		mysqli_query($link, "INSERT INTO queries
		(query_name, query_times, query_last_use) 
		VALUES
		($inp_q_mysql, '1', '$inp_datetime') ")
		or $insert_error = 1;

	}
	else{
		$inp_query_times = $get_query_times+1;

		$result = mysqli_query($link, "UPDATE queries SET query_times='$inp_query_times', query_last_use='$inp_datetime' WHERE query_name=$inp_q_mysql");
	}
		

	// Encode
	$q = $q . " $server_name";
	$q = urlencode($q);
	
	$url = "https://www.google.no/search?q=" . $q;

	header("Location: $url");
	die;
}
else{
	echo"Unknown query";die;
}
?>