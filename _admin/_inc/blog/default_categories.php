<?php
/**
*
* File: _admin/_inc/blog/default_categories
* Version 1
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_blog_info 		= $mysqlPrefixSav . "blog_info";
$t_blog_categories	= $mysqlPrefixSav . "blog_categories";
$t_blog_posts 		= $mysqlPrefixSav . "blog_posts";
$t_blog_posts_tags 	= $mysqlPrefixSav . "blog_posts_tags";
$t_blog_images 		= $mysqlPrefixSav . "blog_images";
$t_blog_logos		= $mysqlPrefixSav . "blog_logos";

$t_blog_links_index		= $mysqlPrefixSav . "blog_links_index";
$t_blog_links_categories	= $mysqlPrefixSav . "blog_links_categories";

$t_blog_ping_list_per_blog	= $mysqlPrefixSav . "blog_ping_list_per_blog";


$t_blog_stats_most_used_categories	= $mysqlPrefixSav . "blog_stats_most_used_categories";

$t_blog_default_categories = $mysqlPrefixSav . "blog_default_categories";

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['default_category_id'])) {
	$default_category_id = $_GET['default_category_id'];
	$default_category_id = strip_tags(stripslashes($default_category_id));
}
else{
	$default_category_id = "";
}

if($action == ""){
	echo"
	<h1>Default categories</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=blog&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Blog</a>
		&gt;
		<a href=\"index.php?open=blog&amp;page=default_categories&amp;editor_language=$editor_language&amp;l=$l\">Default categories</a>
		</p>
	<!-- //Where am I? -->


	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->

	<!-- Navigation + Search -->
		<table>
		 <tr>
		  <td>
			<!-- Navigation -->
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New</a>
				</p>
			<!-- //Navigation -->
		  </td>
		  <td style=\"padding-left: 6px;\">
			<!-- Language -->
				<p>";

				$x = 0;
				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

					if($x != "0"){
						echo"&middot;";
					}
					echo"
					<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\""; if($get_language_active_iso_two == "$editor_language"){ echo" style=\"font-weight:bold;\""; } echo">$get_language_active_iso_two</a>
					";


					$x = $x+1;
				}
				echo"</p>
			<!-- //Language -->
		  </td>
		 </tr>
		</table>
	<!-- //Navigation + Search -->


	<!-- Default categories -->

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" style=\"width: 40%;\">
			<span>Name</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>

		";
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT default_category_id, default_category_title FROM $t_blog_default_categories WHERE default_category_language=$editor_language_mysql";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_default_category_id, $get_default_category_title) = $row;
			
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
				<span>$get_default_category_title</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;default_category_id=$get_default_category_id&amp;l=$l&amp;editor_language=$editor_language\">Edit</a>
				|
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;default_category_id=$get_default_category_id&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
				</span>
			  </td>
			 </tr>";
		} // while
		
		echo"
		 </tbody>
		</table>
		<table class=\"hor-zebra\" id=\"autosearch_search_results_show\">
		</table>
	<!-- //Case codes -->
	";
} // action == ""
elseif($action == "new"){
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		mysqli_query($link, "INSERT INTO $t_blog_default_categories
		(default_category_id, default_category_language, default_category_title) 
		VALUES 
		(NULL, $inp_language_mysql, $inp_title_mysql)
		") or die(mysqli_error($link));

		$url = "index.php?open=$open&page=$page&action=new&editor_language=$inp_language&l=$l&ft=success&fm=created_$inp_title";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New default category</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Blog</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Default categories</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language&amp;l=$l\">New</a>
		</p>
	<!-- //Where am I? -->

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->

	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- New form -->";
		
		echo"
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		

		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p>Language:<br />
		<select name=\"inp_language\">";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

			echo"			<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$editor_language"){ echo" selected=\"selected\""; } echo">$get_language_active_iso_two</option>\n";

		}
		echo"
		</select>
		</p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" /></p>

		</form>
	<!-- //New form -->

	";

} // new
elseif($action == "edit"){
	// Find
	$default_category_id_mysql = quote_smart($link, $default_category_id);
	$query = "SELECT default_category_id, default_category_language, default_category_title FROM $t_blog_default_categories WHERE default_category_id=$default_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_default_category_id, $get_current_default_category_language, $get_current_default_category_title) = $row;
	
	if($get_current_default_category_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{

	

		if($process == "1"){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);


			$result = mysqli_query($link, "UPDATE $t_blog_default_categories SET 
					default_category_language=$inp_language_mysql, 
					default_category_title=$inp_title_mysql
					 WHERE default_category_id=$get_current_default_category_id");



			$url = "index.php?open=$open&page=$page&action=$action&default_category_id=$get_current_default_category_id&editor_language=$inp_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_default_category_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Blog</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Default categories</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;default_category_id=$get_current_default_category_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			</p>
		<!-- //Where am I? -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Edit form -->";
		
			echo"
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;default_category_id=$get_current_default_category_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_default_category_title\" size=\"25\" />
			</p>

			<p>Language:<br />
			<select name=\"inp_language\">";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

				echo"			<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_current_default_category_language"){ echo" selected=\"selected\""; } echo">$get_language_active_iso_two</option>\n";

			}
			echo"
			</select>
			</p>

			<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" />
			</p>
	
			</form>
		<!-- //New form -->

		";
	} // case found
} // open_code
elseif($action == "delete"){
	// Find
	$default_category_id_mysql = quote_smart($link, $default_category_id);
	$query = "SELECT default_category_id, default_category_language, default_category_title FROM $t_blog_default_categories WHERE default_category_id=$default_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_default_category_id, $get_current_default_category_language, $get_current_default_category_title) = $row;
	
	if($get_current_default_category_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{

	

		if($process == "1"){



			$result = mysqli_query($link, "DELETE FROM $t_blog_default_categories WHERE default_category_id=$get_current_default_category_id") or die(mysqli_error($link));



			$url = "index.php?open=$open&page=$page&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l&ft=success&fm=deleted";
			header("Location: $url");
			exit;

		}
		echo"
		<h1>$get_current_default_category_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Blog</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Default categories</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;default_category_id=$get_current_default_category_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
			</p>
		<!-- //Where am I? -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Delete -->
			<p>Are you sure you want to delete?</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;default_category_id=$get_current_default_category_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>
		<!-- //Delete -->

		";
	} // case found
} // delete
?>