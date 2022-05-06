<?php
/**
*
* File: _admin/_inc/webdesign/front_page.php
* Version 19:08 06.05.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_front_page_boxes 	= $mysqlPrefixSav . "front_page_boxes";

/*- Check for setup ------------------------------------------------------------------- */
$query = "SELECT * FROM $t_front_page_boxes LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){

	echo"
	<h1>Front page</h1>



	";
} // setup ok
?>