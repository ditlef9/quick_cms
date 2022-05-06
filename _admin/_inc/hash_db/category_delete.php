<?php
/**
*
* File: _admin/_inc/rss_news/category_delete.php
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
if(isset($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	if(!(is_numeric($category_id))){
		echo"category_id not numeric"; die;
	}
}
else{
	$category_id = "";
}
$category_id_mysql = quote_smart($link, $category_id);
/*- Tables ---------------------------------------------------------------------------- */
$t_hash_db_liquidbase	= $mysqlPrefixSav . "rss_news_liquidbase";

$t_hash_db_entries	= $mysqlPrefixSav . "hash_db_entries";
$t_hash_db_categories	= $mysqlPrefixSav . "hash_db_categories";

if($action == ""){
	$query = "SELECT category_id, category_title FROM $t_hash_db_categories WHERE category_id=$category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_category_id, $get_current_category_title) = $row;

	if($get_current_category_id == ""){
		echo"<p>Category not found</p>";
	}
	else{
	

		if($process == "1"){

			// Delete
			mysqli_query($link, "DELETE FROM $t_hash_db_categories WHERE category_id=$get_current_category_id")
						or die(mysqli_error($link)); 

			$url = "index.php?open=$open&page=categories&order_by=$order_by&order_method=$order_method&l=$l&editor_language=$editor_language&ft=success&fm=category_deleted";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>Delete category $get_current_category_title</h1>


		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "user_deleted"){
					$fm = "$l_user_deleted";
				}
				else{
					$fm = ucfirst($ft);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=hash_db&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Hash Db</a>
			&gt;
			<a href=\"index.php?open=hash_db&amp;page=categories&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
			&gt;
			<a href=\"index.php?open=hash_db&amp;page=$page&amp;category_id=$get_current_category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\">$get_current_category_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Delete category form -->
			<p>Are you sure you want to delete?</p>

			<p>
			<a href=\"index.php?open=hash_db&amp;page=$page&amp;category_id=$get_current_category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Confirm</a>
			</p>

		<!-- //Delete category form -->
		";

	} // category found
} // action == ""
?>