<?php
/**
*
* File: food/view_food_go_a.php
* Version 1.0.0
* Date 23:07 09.07.2017
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_food.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['a_id'])){
	$a_id = $_GET['a_id'];
	$a_id = strip_tags(stripslashes($a_id));
	$a_id_mysql = quote_smart($link, $a_id);
}
else{
	$a_id = "";
}


$query = "SELECT ad_id, ad_url, ad_food_unique_clicks, ad_food_unique_clicks_ip_block FROM $t_food_index_ads WHERE ad_id=$a_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_ad_id, $get_ad_url, $get_ad_food_unique_clicks, $get_ad_food_unique_clicks_ip_block) = $row;
if($get_ad_id != ""){

	// Unique hits
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);

	$ip_array = explode("\n", $get_ad_food_unique_clicks_ip_block);
	$ip_array_size = sizeof($ip_array);

	$has_seen_this_ad_before = 0;

	for($x=0;$x<$ip_array_size;$x++){
		if($ip_array[$x] == "$inp_ip"){
			$has_seen_this_ad_before = 1;
			break;
		}
		if($x > 5){
			break;
		}
	}
	
	if($has_seen_this_ad_before == 0){
		$inp_ip_block = $inp_ip . "\n" . $get_ad_food_unique_clicks_ip_block;
		$inp_ip_block_mysql = quote_smart($link, $inp_ip_block);
		$inp_clicks = $get_ad_food_unique_clicks + 1;
		$result = mysqli_query($link, "UPDATE $t_food_index_ads SET ad_food_unique_clicks='$inp_clicks', ad_food_unique_clicks_ip_block=$inp_ip_block_mysql WHERE ad_id=$a_id_mysql") or die(mysqli_error($link));
	}

	// Header
	header("Location: $get_ad_url");
	exit;

}
else{
	echo"Ad not found";
}

?>