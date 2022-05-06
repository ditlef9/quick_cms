<?php
/**
*
* File: _admin/_inc/hash_db/categories.php
* Version 1.0
* Date: 21:13 25.02.2020
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

if($action == ""){
	echo"
	<h1>Categories</h1>


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
		<a href=\"index.php?open=hash_db&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Hash DB</a>
		&gt;
		<a href=\"index.php?open=hash_db&amp;page=categories&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
		</p>
	<!-- //Where am I? -->

	<!-- Categories -->
		<p>
		<a href=\"index.php?open=$open&amp;page=category_new&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn\">New category</a>
		</p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">";
			if($order_by == "category_id" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=category_id&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>ID</b></a>";
			if($order_by == "category_id" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "category_id" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "category_title" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=category_title&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Title</b></a>";
			if($order_by == "category_title" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "category_title" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">
			<span><b>Actions</b></span>
		   </th>
		  </tr>
		</thead>
		<tbody>


	";
	$query = "SELECT category_id, category_title FROM $t_hash_db_categories";
	if($order_by == "category_id" OR $order_by == "category_title"){
		if($order_method == "asc"){
			$query = $query . " ORDER BY $order_by ASC";
		}
		else{
			$query = $query . " ORDER BY $order_by DESC";
		}
	}

	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_category_id, $get_category_title) = $row;

		// Style
		if(isset($style) && $style == ""){
			$style = "odd";
		}
		else{
			$style = "";
		}
	
		echo"
		 <tr>
		  <td class=\"$style\">
			<span>$get_category_id</span>
		  </td>
		  <td class=\"$style\">
			<span>
			$get_category_title
			</span>
		  </td>
		  <td class=\"$style\">
			<span>
			<a href=\"index.php?open=$open&amp;page=category_edit&amp;category_id=$get_category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l&amp;editor_language=$editor_language\">Edit</a>
			|
			<a href=\"index.php?open=$open&amp;page=category_delete&amp;category_id=$get_category_id&amp;order_by=$order_by&amp;order_method=$order_method&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
			</span>
		  </td>
		 </tr>
		";

	}
	echo"
	
		 </tbody>
		</table>

	";
}
?>