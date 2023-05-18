<?php
/**
*
* File: food/view_link.php
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
include("_tables.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['link_id'])){
	$link_id = $_GET['link_id'];
	$link_id = strip_tags(stripslashes($link_id));
}
else{
	$link_id = "";
}
$link_id_mysql = quote_smart($link, $link_id);


$query = "SELECT link_id, link_title, link_url, link_unique_click, link_unique_click_ipblock FROM $t_recipes_links WHERE link_id=$link_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_link_id, $get_link_title, $get_link_url, $get_link_unique_click, $get_link_unique_click_ipblock) = $row;
if($get_link_id != ""){

	// Unique hits
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);

	$ip_array = explode("\n", $get_link_unique_click_ipblock);
	$ip_array_size = sizeof($ip_array);

	$has_seen_this_before = 0;

	for($x=0;$x<$ip_array_size;$x++){
		if($ip_array[$x] == "$inp_ip"){
			$has_seen_this_before = 1;
			break;
		}
		if($x > 5){
			break;
		}
	}
	
	if($has_seen_this_before == 0){
		$inp_ip_block = $inp_ip . "\n" . $get_link_unique_click_ipblock;
		$inp_ip_block_mysql = quote_smart($link, $inp_ip_block);
		$inp_clicks = $get_link_unique_click + 1;
		$result = mysqli_query($link, "UPDATE $t_recipes_links SET link_unique_click='$inp_clicks', link_unique_click_ipblock=$inp_ip_block_mysql WHERE link_id=$link_id_mysql") or die(mysqli_error($link));
	}

	// Header
	header("Location: $get_link_url");
	exit;

}
else{
	echo"Link not found";
}

?>