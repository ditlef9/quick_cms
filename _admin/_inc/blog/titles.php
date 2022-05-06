<?php
/**
*
* File: _admin/_inc/blog/titles.php
* Version 1
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */

$t_blog_titles 		= $mysqlPrefixSav . "blog_titles";
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
if($process == "1"){

	$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

		// Update
		$inp_value = $_POST["inp_value_$get_language_active_iso_two"];
		$inp_value = output_html($inp_value);
		$inp_value_mysql = quote_smart($link, $inp_value);

		
		$language_mysql = quote_smart($link, $get_language_active_iso_two);
		

		mysqli_query($link, "UPDATE $t_blog_titles SET title_value=$inp_value_mysql WHERE title_language=$language_mysql") or die(mysqli_error($link));
	}

	$url = "index.php?open=blog&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
	header("Location: $url");
	exit;
}

echo"
<h1>Titles</h1>


<!-- Where am I? -->
	<p><b>You are here:</b><br />
	<a href=\"index.php?open=blog&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Blog</a>
	&gt;
	<a href=\"index.php?open=blog&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Titles</a>
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

<!-- Titles -->

	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		

	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th>
		<span>Language</span>
	   </th>
	   <th>
		<span>Title</span>
	   </th>
	  </tr>
	 </thead>
	
	 <tbody>\n";
	$x = 0;
	$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

		// Find
		$language_mysql = quote_smart($link, $get_language_active_iso_two);
		$query_t = "SELECT title_id, title_language, title_value FROM $t_blog_titles WHERE title_language=$language_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_title_id, $get_title_language, $get_title_value) = $row_t;
		if($get_title_id == ""){

			mysqli_query($link, "INSERT INTO $t_blog_titles
			(title_id, title_language, title_value) 
			VALUES 
			(NULL, $language_mysql, 'Blog')
			") or die(mysqli_error($link));
			$get_title_value = "Blog";
		}

		echo"

		  <tr>
		   <td>
			<span>$get_language_active_name</span>
		   </td>
		   <td>
			<span><input type=\"text\" name=\"inp_value_$get_language_active_iso_two\" value=\"$get_title_value\" size=\"25\" /></span>
		   </td>
		  </tr>
		";
	}
	echo"
	 </tbody>
	</table>

	<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" />
	</p>
	</form>
<!-- //Titles -->

	
";
?>