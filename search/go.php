<?php 
/**
*
* File: search/go.php 
* Version 1.0
* Date 14:01 24.01.2020
* Copyright (c) 2020 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Language --------------------------------------------------------------------------- */
// include("$root/_admin/_translations/site/$l/edb/ts_edb.php");


/*- Tables Search Engine ---------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['index_id'])) {
	$index_id = $_GET['index_id'];
	$index_id = strip_tags(stripslashes($index_id));
	if(!(is_numeric($index_id))){
		echo"Index id not numberic"; die;
	}
}
else{
	// go to index
	header("Location: index.php?l=$l");
	exit;
}
$index_id_mysql = quote_smart($link, $index_id);

// Find index id
$query = "SELECT index_id, index_title, index_url, index_short_description, index_keywords, index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_updated_datetime, index_updated_datetime_print, index_language, index_unique_hits, index_hits_ipblock FROM $t_search_engine_index WHERE index_id=$index_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_index_id, $get_current_index_title, $get_current_index_url, $get_current_index_short_description, $get_current_index_keywords, $get_current_index_module_name, $get_current_index_module_part_name, $get_current_index_module_part_id, $get_current_index_reference_name, $get_current_index_reference_id, $get_current_index_has_access_control, $get_current_index_is_ad, $get_current_index_created_datetime, $get_current_index_created_datetime_print, $get_current_index_updated_datetime, $get_current_index_updated_datetime_print, $get_current_index_language, $get_current_index_unique_hits, $get_current_index_hits_ipblock) = $row;
if($get_current_index_id == ""){
	echo"Server error 404";
}
else{
	// IP
	$my_ip = $_SERVER['REMOTE_ADDR'];
	$my_ip = output_html($my_ip);

	$array = explode("\n", $get_current_index_hits_ipblock);
	$size = sizeof($array);
	$found_my_ip = "false";
	for($x=0;$x<$size;$x++){
		$temp = $array[$x];
		if($temp == "$my_ip"){
			$found_my_ip = "true";
		}
	}
	
	if($found_my_ip == "false"){
		$inp_unique_hits = $get_current_index_unique_hits+1;

		$inp_hits_ipblock = $my_ip . "\n" . $get_current_index_hits_ipblock;
		$inp_hits_ipblock = substr($inp_hits_ipblock, 0, 450);
		$inp_hits_ipblock_mysql = quote_smart($link, $inp_hits_ipblock);

		
		$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
						index_unique_hits=$inp_unique_hits,
						index_hits_ipblock=$inp_hits_ipblock_mysql
						WHERE index_id='$get_current_index_id'");
	}


	// header
	$get_current_index_url = str_replace("&amp;", "&", $get_current_index_url);
	header("Location: $root/$get_current_index_url");
	exit;
}


?>