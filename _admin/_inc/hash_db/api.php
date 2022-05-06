<?php
/**
*
* File: _admin/_inc/hash_db/api.php
* Version 1.0
* Date: 11:41 22.02.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
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
$t_hash_db_liquidbase	= $mysqlPrefixSav . "rss_news_liquidbase";

$t_hash_db_entries	= $mysqlPrefixSav . "hash_db_entries";
$t_hash_db_categories	= $mysqlPrefixSav . "hash_db_categories";


/*- Config ------------------------------------------------------------------------------- */
include("_data/hash_db.php");


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
		<a href=\"index.php?open=hash_db&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Hash Db</a>
		&gt;
		<a href=\"index.php?open=hash_db&amp;page=api&amp;editor_language=$editor_language&amp;l=$l\">API</a>
		</p>
	<!-- //Where am I? -->

	<!-- APIs hash_db_categories -->
		<h2>API 1) hash_db_categories</h2>

		<p><b>URL:</b> $configControlPanelURLSav/_inc/hash_db/api/hash_db_categories.php<br />
		<b>POST:</b> inp_api_password
		</p>

		<form method=\"post\" action=\"_inc/hash_db/api/hash_db_categories.php\" enctype=\"multipart/form-data\">
		


		<p>API Password:
		<input type=\"text\" name=\"inp_api_password\" value=\"$hashDbApiPasswordSav\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p>
		<input type=\"submit\" value=\"hash_db_categories\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>
	<!-- //APIs hash_db_categories -->

		<hr />




	<!-- APIs 2) hash_db_entries -->
		<h2>API 2) hash_db_entries_count_rows_in_entries_for_category</h2>

		<form method=\"post\" action=\"_inc/hash_db/api/hash_db_entries_count_rows_in_entries_for_category.php\" enctype=\"multipart/form-data\">
		

		<p><b>URL:</b> $configControlPanelURLSav/_inc/hash_db/api/hash_db_entries_count_rows_in_entries_for_category.php<br />
		<b>POST:</b> inp_api_password<br />
		<b>POST:</b> inp_category_id
		</p>


		<p>API Password:
		<input type=\"text\" name=\"inp_api_password\" value=\"$hashDbApiPasswordSav\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		<p>Category:
		<select name=\"inp_category_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">\n";
		$query = "SELECT category_id, category_title FROM $t_hash_db_categories ORDER BY category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_category_id, $get_category_title) = $row;
			echo"				<option value=\"$get_category_id\">$get_category_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"hash_db_entries_count_rows_in_entries_for_category\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>
	<!-- //APIs 2) hash_db_entries -->


		<hr />
	<!-- APIs 3) hash_db_entries -->
		<h2>API 3) hash_db_entries</h2>

		<form method=\"post\" action=\"_inc/hash_db/api/hash_db_entries.php\" enctype=\"multipart/form-data\">
		

		<p><b>URL:</b> $configControlPanelURLSav/_inc/hash_db/api/hash_db_entries.php<br />
		<b>POST:</b> inp_api_password<br />
		<b>POST:</b> inp_category_id<br />
		<b>POST:</b> inp_start<br />
		<b>POST:</b> inp_stop
		</p>


		<p>API Password:
		<input type=\"text\" name=\"inp_api_password\" value=\"$hashDbApiPasswordSav\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p>Category:
		<select name=\"inp_category_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">\n";
		$query = "SELECT category_id, category_title FROM $t_hash_db_categories ORDER BY category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_category_id, $get_category_title) = $row;
			echo"				<option value=\"$get_category_id\">$get_category_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p>Start
		<input type=\"text\" name=\"inp_start\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p>Stop
		<input type=\"text\" name=\"inp_stop\" value=\"100\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p>
		<input type=\"submit\" value=\"hash_db_entries\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>
	<!-- //APIs hash_db_categories -->
	";
}
?>