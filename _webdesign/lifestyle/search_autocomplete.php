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
		$inp_search_query = $_GET['q'];
	}
	else{
		$inp_search_query = $_POST['q'];
	}
	$inp_search_query = strip_tags(stripslashes($inp_search_query));
	$inp_search_query = trim($inp_search_query);
	$inp_search_query = strtolower($inp_search_query);
	$inp_search_query = $inp_search_query . "%";
	$part_mysql = quote_smart($link, $inp_search_query);

	
	echo"
		<ul>
	";
	$query = "SELECT search_id, search_value FROM $t_searches WHERE search_value LIKE $part_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_search_id, $get_search_value) = $row;

		echo"
		<li><a href=\"_webdesign/$webdesignSav/search_go.php?q=$get_search_value&amp;l=$l\">$get_search_value</a></li>
		";
		
	}
	
	echo"
		</ul>
	";
	
}

?>