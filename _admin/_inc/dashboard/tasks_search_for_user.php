<?php
error_reporting(E_ALL & ~E_STRICT);
session_start();
ini_set('arg_separator.output', '&amp;');


/*- Functions ------------------------------------------------------------------------ */
include("../../_functions/output_html.php");
include("../../_functions/clean.php");
include("../../_functions/quote_smart.php");
include("../../_functions/resize_crop_image.php");


/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("../../_data/$setup_finished_file"))){
	header("Location: ../../setup/index.php");
	exit;
}


/*- Check for admin ----------------------------------------------------------------- */
if(!(isset($_SESSION['admin_user_id']))){
	header("Location: ../../login/index.php");
	// echo"<meta http-equiv=refresh content=\"1; url=login/index.php\">";
	die;
}
else{
	$current_user_id = $_SESSION['admin_user_id'];
}



/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_data/mysql_" . $server_name . ".php";
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

	/*- MySQL Tables -------------------------------------------------- */

	/* Users */
	$t_users			= $mysqlPrefixSav . "users";


}

/*- Query --------------------------------------------------------------------------- */
if(isset($_GET['inp_search_query']) OR isset($_POST['inp_search_query'])){

	// Header
	echo"
	<div class=\"vertical\">
		<ul class=\"user_select\">\n";

	if(isset($_GET['inp_search_query'])) {
		$inp_search_query = $_GET['inp_search_query'];
	}
	else{
		$inp_search_query = $_POST['inp_search_query'];
	}
	$inp_search_query = strip_tags(stripslashes($inp_search_query));
	$inp_search_query = trim($inp_search_query);
	$inp_search_query = strtolower($inp_search_query);
	$inp_search_query = $inp_search_query . "%";
	$part_mysql = quote_smart($link, $inp_search_query);


	$output_data = "";
	$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_alias LIKE $part_mysql AND (user_rank='admin' OR user_rank='moderator' OR user_rank='editor')";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias) = $row;

		$output_data = $output_data . "
		<li><a href=\"#\">$get_user_alias</a></li>
		";
		
	}
	echo $output_data;

	// Footer
	echo"
		</ul>
	</div>

	<script language=\"javascript\" type=\"text/javascript\">
		\$('.user_select a').click(function() {
			var value = \$(this).text();
			var input = \$('#assigned_to_user_alias_search_query');
			input.val(value);
			\$(\"#assigned_to_user_alias_search_results\").html(''); 
			return false;
		});
	</script>

	";
}

?>