<?php
error_reporting(E_ALL);
session_start();
ini_set('arg_separator.output', '&amp;');
/**
*
* File: _admin/_inc/crypto_analyzer/graph/display_graphically.php
* Version 3.0.0
* Date 20:46 18.08.2021
* Copyright (c) 2008-2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Functions ------------------------------------------------------------------------ */
include("../../../_functions/output_html.php");
include("../../../_functions/clean.php");
include("../../../_functions/quote_smart.php");



/*- Make sure we are on the correct web site ----------------------------------------- */
if(file_exists("../../../_data/config/meta.php")){
	include("../../../_data/config/meta.php");

	// Page URL
	$page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$page_url = htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8');

	$page_url_substr = substr($page_url, 0, strlen($configControlPanelURLSav));

	if($configControlPanelURLSav != "$page_url_substr"){
		// Check for localhost
		$check_localhost = substr($page_url, 0, 16);
		if($check_localhost != "http://localhost"){
	
			echo"<p>Security error. Page url is not the same as configured. Please fix meta.php.
			</p>

			<p>
			<a href=\"$configControlPanelURLSav\">$configControlPanelURLSav</a> != $page_url_substr
			</p>
			";
			die;
		}
	}
}


/*- Check for admin ----------------------------------------------------------------- */
if(!(isset($_SESSION['admin_user_id']))){
	header("Location: ../../../login/index.php");
	die;
}
else{
	$current_user_id = $_SESSION['admin_user_id'];
}

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("../../../_data/$setup_finished_file"))){
	header("Location: ../../../setup/");
	exit;
}

/*- MySQL ----------------------------------------------------------------------------- */
$mysql_config_file = "../../../_data/mysql_" . $server_name . ".php";
if(file_exists($mysql_config_file)){
	include("$mysql_config_file");
	$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
	if (!$link) {
		echo "
		<div class=\"alert alert-danger\"><span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span><strong>MySQL connection error</strong>"; 
		echo PHP_EOL;
   		echo "<br />Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    		echo "<br />Debugging error: " . mysqli_connect_error() . PHP_EOL;
    		echo"
		</div>
		";
	}
}
else{
	echo"Db file not found";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_cran_liquidbase		= $mysqlPrefixSav . "cran_liquidbase";
$t_cran_transactions_index	= $mysqlPrefixSav . "cran_transactions_index";
$t_cran_transactions_inputs	= $mysqlPrefixSav . "cran_transactions_inputs";
$t_cran_transactions_outputs	= $mysqlPrefixSav . "cran_transactions_outputs";
$t_cran_wallets			= $mysqlPrefixSav . "cran_wallets";
$t_cran_blocks			= $mysqlPrefixSav . "cran_blocks";

$t_cran_graphs_elements 	= $mysqlPrefixSav . "cran_graphs_elements";
$t_cran_graphs_index 		= $mysqlPrefixSav . "cran_graphs_index";


/*- Open Graph ----------------------------------------------------------------------- */
if (isset($_GET['graph_id'])) {
	$graph_id = $_GET['graph_id'];
	$graph_id = stripslashes(strip_tags($graph_id));
	if(!(is_numeric($graph_id))){
		echo"graph id not numeric";
		die;
	}
}
else{
	echo"Missing graph id";
	die;
}
$graph_id_mysql = quote_smart($link, $graph_id);

// Get graph
$query = "SELECT graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying FROM $t_cran_graphs_index WHERE graph_id=$graph_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_graph_id, $get_current_graph_title, $get_current_graph_group_id, $get_current_graph_created_by_user_id, $get_current_graph_created_datetime, $get_current_graph_created_date_saying, $get_current_graph_updated_by_user_id, $get_current_graph_updated_datetime, $get_current_graph_updated_date_saying) = $row;
if($get_current_graph_id == ""){
	echo"Graph not found";
	die;
}


/*- Variables -------------------------------------------------------------------------------- */
$random = date("ymdhis");

/*- Header ----------------------------------------------------------------------------------- */
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>Crypto</title>

	<!-- Site CSS -->
		<link rel=\"stylesheet\" type=\"text/css\" href=\"layout/crypto_analyzer.css?rand=$random\" />
	<!-- //Site CSS -->

	<!-- Favicon -->
		<link rel=\"icon\" href=\"layout/favicon/16x16.png\" type=\"image/png\" sizes=\"16x16\" />
		<link rel=\"icon\" href=\"layout/favicon/32x32.png\" type=\"image/png\" sizes=\"32x32\" />
		<link rel=\"icon\" href=\"layout/favicon/260x260.png\" type=\"image/png\" sizes=\"260x260\" />
	<!-- //Favicon -->

	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>

	<!-- Drawer -->
		<script type=\"text/javascript\" src=\"scripts/drawer/drawer.js?rand=$random\"></script>
	<!-- //Drawer -->


</head>
<body>

<!-- Main -->
	<main>
		";
		include("pages/display_graph.php");
		echo"
	</main>
<!-- //Main -->

</body>
</html>";
?>