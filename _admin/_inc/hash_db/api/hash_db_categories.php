<?php 
/**
*
* File: api/hash_db_categories.php
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
if($inp_api_password != "$hashDbApiPasswordSav"){
	echo"Unknown api p";
	die;
}


if($hashDbApiActiveSav != "1"){
	echo"API inactive";
	die;
}

// Build array
$rows_array = array();


$query = "SELECT * FROM $t_hash_db_categories";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}

// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";


?>