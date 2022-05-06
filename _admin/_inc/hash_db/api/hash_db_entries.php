<?php 
/**
*
* File: api/hash_db_entries.php
* Version 1.0
* Date 14:37 17.03.2020
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
include("../../../website_config.php");
include("../../../_data/hash_db.php");



/*- Functions ------------------------------------------------------------------------- */
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_hash_db_entries	= $mysqlPrefixSav . "hash_db_entries";
$t_hash_db_categories	= $mysqlPrefixSav . "hash_db_categories";


/*- Variables -------------------------------------------------------------------------- */
if(isset($_POST['inp_api_password'])) {
	$inp_api_password = $_POST['inp_api_password'];
	$inp_api_password = output_html($inp_api_password);
}
else{
	$inp_api_password = "";
}
if(isset($_POST['inp_category_id'])) {
	$inp_category_id = $_POST['inp_category_id'];
	$inp_category_id = output_html($inp_category_id);
	if(!(is_numeric($inp_category_id))){
		echo"Category not numeric";
		die;
	}
}
else{
	$inp_category_id = "";
	echo"Missing category";
	die;
}


if(isset($_POST['inp_start'])) {
	$inp_start = $_POST['inp_start'];
	$inp_start = output_html($inp_start);
	if(!(is_numeric($inp_start))){
		echo"Start not numeric";
		die;
	}
}
else{
	$inp_start = "";
	echo"Missing start";
	die;
}


if(isset($_POST['inp_stop'])) {
	$inp_stop = $_POST['inp_stop'];
	$inp_stop = output_html($inp_stop);
	if(!(is_numeric($inp_stop))){
		echo"Stop not numeric";
		die;
	}
}
else{
	$inp_stop = "";
	echo"Missing stop";
	die;
}


if($inp_api_password != "$hashDbApiPasswordSav"){
	echo"Unknown api p";
	die;
}

if($hashDbApiActiveSav != "1"){
	echo"API inactive";
	die;
}

// Find category
$category_id_mysql = quote_smart($link, $inp_category_id);
$query = "SELECT category_id, category_title, category_bg_color, category_border_color, category_text_color, category_is_illegal, category_is_interesting FROM $t_hash_db_categories WHERE category_id=$category_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_category_id, $get_current_category_title, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_text_color, $get_current_category_is_illegal, $get_current_category_is_interesting) = $row;
if($get_current_category_id == ""){
	echo"Category not found";
	die;
}


// Build array
$rows_array = array();


$query = "SELECT * FROM $t_hash_db_entries WHERE entry_category_id=$get_current_category_id LIMIT $inp_start,$inp_stop";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}

// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";


?>