<?php

/*- Root dir -------------------------------------------------------------------------- */
if(!(isset($root))){
	if(file_exists("_admin/index.php")){
		$root = ".";
	}
	elseif(file_exists("../_admin/index.php")){
		$root = "..";
	}
	elseif(file_exists("../../_admin/index.php")){
		$root = "../..";
	}
	elseif(file_exists("../../../_admin/index.php")){
		$root = "../../..";
	}
	elseif(file_exists("../../../../_admin/index.php")){
		$root = "../../../..";
	}
	else{
		$root = "../../..";
	}
}


/*- Website config -------------------------------------------------------------------- */
if(!(isset($server_name))){
	include("$root/_admin/website_config.php");
}



/*- MySQL Tables -------------------------------------------------------------------- */
$t_searches = $mysqlPrefixSav . "searches";


/*- Table exists? -------------------------------------------------------------------- */
$query = "SELECT * FROM $t_searches LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){		
}
else{
	mysqli_query($link, "CREATE TABLE $t_searches(
	 search_id INT NOT NULL AUTO_INCREMENT,
	 PRIMARY KEY(search_id), 
	 search_value VARCHAR(350),
	 search_value_urlencoded VARCHAR(350),
	 search_language VARCHAR(20),
	 search_times BIGINT,
	 search_last_use DATETIME,
	 search_hidden INT)")
	 or die(mysql_error());
}




/*- Query --------------------------------------------------------------------------- */
if(isset($_GET['q']) OR isset($_POST['q'])){
	if(isset($_GET['q'])) {
		$inp_search_value = $_GET['q'];
	}
	else{
		$inp_search_value = $_POST['q'];
	}
	$inp_search_value = output_html($inp_search_value);
	$inp_search_value_mysql = quote_smart($link,  $inp_search_value);

	// Lang
	$l_mysql = quote_smart($link, $l);
	
	// Date
	$datetime = date("Y-m-d H:i:s");

	// URL Encoded
	$inp_search_value_urlencoded = urlencode($inp_search_value);
	$inp_search_value_urlencoded_mysql = quote_smart($link,  $inp_search_value_urlencoded);

	// Check for it
	$q = "SELECT search_id, search_value, search_value_urlencoded, search_language, search_times, search_last_use, search_hidden FROM $t_searches WHERE search_value=$inp_search_value_mysql AND search_language=$l_mysql AND search_hidden=0";
	$r = mysqli_query($link, $q);
	$rowb = mysqli_fetch_row($r);
	list($get_search_id, $get_search_value, $get_search_value_urlencoded, $get_search_language, $get_search_times, $get_search_last_use, $get_search_hidden) = $rowb;
	if($get_search_id == ""){
		// Create entry
		mysqli_query($link, "INSERT INTO $t_searches 
		(search_id, search_value, search_value_urlencoded, search_language, search_times, search_last_use, search_hidden) 
		VALUES 
		(NULL, $inp_search_value_mysql, $inp_search_value_urlencoded_mysql, $l_mysql, 1, '$datetime', 0)")
		or die(mysqli_error($link));
	}
	else{
		// Update entry
		$inp_search_times = $get_search_times+1;
		mysqli_query($link, "UPDATE $t_searches SET search_times=$inp_search_times WHERE search_id=$get_search_id") or die(mysqli_error($link));
	}

	// Header
	$url = "https://duckduckgo.com/?ia=web&q=$inp_search_value_urlencoded";
	header("Location: $url");
	exit;
}

?>