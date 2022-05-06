<?php
/**
*
* File: _admin/_inc/picture_it/api.php
* Version 1.0
* Date: 07:51 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if($order_by == ""){
	$order_by = "country_name";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
	if($order_method != "asc" && $order_method != "desc"){
		echo"Wrong order method";
		die;
	}
}
else{
	$order_method = "asc";
}


/*- Tables ---------------------------------------------------------------------------- */

/*- Config ------------------------------------------------------------------------------- */
include("_data/picture_it.php");


if($action == ""){
	echo"
	<h1>API</h1>


	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		if($fm == "user_deleted"){
			$fm = "$l_user_deleted";
		}
		else{
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=picture_it&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Picture It</a>
		&gt;
		<a href=\"index.php?open=picture_it&amp;page=api&amp;editor_language=$editor_language&amp;l=$l\">API</a>
		</p>
	<!-- //Where am I? -->

	<!-- APIs picture_it_categories -->
		<h2>API 1) picture_it_games_index</h2>

		<p><b>URL:</b> $configControlPanelURLSav/_inc/hash_db/api/hash_db_categories.php<br />
		<b>POST:</b> inp_api_password
		</p>

	<!-- //APIs hash_db_categories -->

	<hr />



	";
}
?>