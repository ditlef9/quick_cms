<?php
/**
*
* File: _admin/_inc/muscles/open_sub_group.php
* Version 1.0.0
* Date 20:53 09.02.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");


/*- Tables ---------------------------------------------------------------------------- */
include("_tables_muslces.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['main_group_id'])){
	$main_group_id = $_GET['main_group_id'];
	$main_group_id = strip_tags(stripslashes($main_group_id));
}
else{
	$main_muscle_group_id = "";
}
if(isset($_GET['sub_group_id'])){
	$sub_group_id = $_GET['sub_group_id'];
	$sub_group_id = strip_tags(stripslashes($sub_group_id));
}
else{
	$sub_group_id = "";
}

if(isset($_GET['part_of_id'])){
	$part_of_id = $_GET['part_of_id'];
	$part_of_id = strip_tags(stripslashes($part_of_id));
}
else{
	$part_of_id = "";
}


/*- Scriptstart ---------------------------------------------------------------------- */


// Find sub
$sub_group_id_mysql = quote_smart($link, $sub_group_id);
$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id=$sub_group_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_sub_muscle_group_id, $get_current_sub_muscle_group_name, $get_current_sub_muscle_group_name_clean, $get_current_sub_muscle_group_parent_id, $get_current_sub_muscle_group_image_path, $get_current_sub_muscle_group_image_file) = $row;

// Find main
$main_group_id_mysql = quote_smart($link, $main_group_id);
$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id=$main_group_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_main_muscle_group_id, $get_current_main_muscle_group_name, $get_current_main_muscle_group_name_clean, $get_current_main_muscle_group_parent_id, $get_current_main_muscle_group_image_path, $get_current_main_muscle_group_image_file) = $row;

// Find part of
$part_of_id_mysql = quote_smart($link, $part_of_id);
$query = "SELECT muscle_part_of_id, muscle_part_of_latin_name, muscle_part_of_latin_name_clean, muscle_part_of_name, muscle_part_of_name_clean, muscle_part_of_muscle_group_id_main, muscle_part_of_muscle_group_id_sub, muscle_part_of_image_path, muscle_part_of_image_file FROM $t_muscle_part_of WHERE muscle_part_of_id=$part_of_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_muscle_part_of_id, $get_current_muscle_part_of_latin_name, $get_current_muscle_part_of_latin_name_clean, $get_current_muscle_part_of_name, $get_current_muscle_part_of_name_clean, $get_current_muscle_part_of_muscle_group_id_main, $get_current_muscle_part_of_muscle_group_id_sub, $get_current_muscle_part_of_image_path, $get_muscle_part_of_image_file) = $row;


if($get_current_main_muscle_group_id == "" OR $get_current_sub_muscle_group_id == "" OR $get_current_muscle_part_of_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $l_muscles";
	include("$root/_webdesign/header.php");

	echo"
	<p>Not found.</p>
	";
	include("$root/_webdesign/footer.php");

}
else {
	// Main group translation
	$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_current_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
	$result_translation = mysqli_query($link, $query_translation);
	$row_translation = mysqli_fetch_row($result_translation);
	list($get_current_main_muscle_group_translation_id, $get_current_main_muscle_group_translation_name) = $row_translation;
	if($get_current_main_muscle_group_translation_id == ""){
		mysqli_query($link, "INSERT INTO $t_muscle_groups_translations
		(muscle_group_translation_id, muscle_group_translation_muscle_group_id, muscle_group_translation_language, muscle_group_translation_name) 
		VALUES 
		(NULL, 	'$get_current_main_muscle_group_id', $l_mysql, '$get_current_main_muscle_group_name', '')")
		or die(mysqli_error($link));
	}

	// Sub group translation
	$query_translation = "SELECT muscle_group_translation_id, muscle_group_translation_name, muscle_group_translation_text FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_current_sub_muscle_group_id AND muscle_group_translation_language=$l_mysql";
	$result_translation = mysqli_query($link, $query_translation);
	$row_translation = mysqli_fetch_row($result_translation);
	list($get_current_sub_muscle_group_translation_id, $get_current_sub_muscle_group_translation_name, $get_current_muscle_group_translation_text) = $row_translation;
	if($get_current_sub_muscle_group_translation_id == ""){
		mysqli_query($link, "INSERT INTO $t_muscle_groups_translations
		(muscle_group_translation_id, muscle_group_translation_muscle_group_id, muscle_group_translation_language, muscle_group_translation_name) 
		VALUES 
		(NULL, 	'$get_current_sub_muscle_group_id', $l_mysql, '$get_current_sub_muscle_group_name', '')")
		or die(mysqli_error($link));
	}
	
	// Part of translation
	$query_translation = "SELECT muscle_part_of_translation_id, muscle_part_of_translation_name, muscle_part_of_translation_text FROM $t_muscle_part_of_translations WHERE muscle_part_of_translation_muscle_part_of_id='$get_current_muscle_part_of_id' AND muscle_part_of_translation_language=$l_mysql";
	$result_translation = mysqli_query($link, $query_translation);
	$row_translation = mysqli_fetch_row($result_translation);
	list($get_current_muscle_part_of_translation_id, $get_current_muscle_part_of_translation_name, $get_current_muscle_part_of_translation_text) = $row_translation;
	if($get_current_muscle_part_of_translation_id == ""){
		mysqli_query($link, "INSERT INTO $t_muscle_part_of_translations
		(muscle_part_of_translation_id, muscle_part_of_translation_muscle_part_of_id, muscle_part_of_translation_language, muscle_part_of_translation_name, muscle_part_of_translation_text) 
		VALUES 
		(NULL, 	'$get_current_muscle_part_of_id', $l_mysql, '$get_current_muscle_part_of_name', '')")
		or die(mysqli_error($link));
	}
	else{
		if($get_current_muscle_part_of_translation_name == ""){
			echo"�sj";
		}
	}

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_sub_muscle_group_translation_name - $get_current_main_muscle_group_translation_name - $l_muscles";
	include("$root/_webdesign/header.php");

	echo"

	<!-- Headline -->
		<h1>$get_current_muscle_part_of_translation_name</h1> 
	<!-- //Headline -->


	<!-- Where am I? -->
		<p>
		<b>$l_you_are_here:</b><br />
		<a href=\"$root/muscles/index.php?l=$l\">$l_muscles</a>
		&gt;
		<a href=\"$root/muscles/open_main_group.php?main_group_id=$get_current_main_muscle_group_id&amp;l=$l\">$get_current_main_muscle_group_translation_name</a>
		&gt;
		<a href=\"$root/muscles/open_sub_group.php?main_group_id=$get_current_main_muscle_group_id&amp;sub_group_id=$get_current_sub_muscle_group_id&amp;l=$l\">$get_current_sub_muscle_group_translation_name</a>
		&gt;
		<a href=\"$root/muscles/open_muscle_part_of.php?main_group_id=$get_current_main_muscle_group_id&amp;sub_group_id=$get_current_sub_muscle_group_id&amp;part_of_id=$get_current_muscle_part_of_id&amp;l=$l\">$get_current_muscle_part_of_translation_name</a>
		</p>
	<!-- //Where am I? -->
	
	<!-- Image -->
		";
		if($get_muscle_part_of_image_file != "" && file_exists("$root/$get_current_muscle_part_of_image_path/$get_muscle_part_of_image_file")){
			echo"<p><img src=\"$root/$get_current_muscle_part_of_image_path/$get_muscle_part_of_image_file\" alt=\"$get_muscle_part_of_image_file\" /></p>";
		} 
		echo"
	<!-- //Image -->
	
	<!-- Muscles in sub group -->
		<p><b>$l_muscles</b><br />
		<a href=\"$root/muscles/open_muscle_part_of.php?main_group_id=$get_main_muscle_group_id&amp;sub_group_id=$get_sub_muscle_group_id&amp;part_of_id=$get_current_muscle_part_of_id&amp;l=$l\">$get_current_muscle_part_of_translation_name</a>
		(";

		$muscles_count_inside = 0;
		$query_m = "SELECT muscle_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_short_name, muscle_image_path, muscle_image_file FROM $t_muscles WHERE muscle_part_of_id = '$get_current_muscle_part_of_id' ORDER BY muscle_latin_name ASC";
		$result_m = mysqli_query($link, $query_m);
		while($row_m = mysqli_fetch_row($result_m)) {
			list($get_muscle_id, $get_muscle_latin_name, $get_muscle_latin_name_clean, $get_muscle_simple_name, $get_muscle_short_name, $get_muscle_image_path, $get_muscle_image_file) = $row_m;

			// Translation
			$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_muscle_id' AND muscle_translation_language=$l_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					
			if($get_muscle_translation_id == ""){
				mysqli_query($link, "INSERT INTO $t_muscles_translations
				(muscle_translation_id, muscle_translation_muscle_id, muscle_translation_language, muscle_translation_simple_name, muscle_translation_short_name, muscle_translation_text) 
				VALUES 
				(NULL, 	'$get_muscle_id', $l_mysql, '$get_muscle_simple_name', '$get_muscle_short_name', '')")
				or die(mysqli_error($link));
			}
			if($muscles_count_inside != 0){
				echo" &middot;";
			}
					
			echo"
			<a href=\"$root/muscles/muscle.php?main_group_id=$get_current_main_muscle_group_id&amp;sub_group_id=$get_current_sub_muscle_group_id&amp;part_of_id=$get_current_muscle_part_of_id&amp;muscle_id=$get_muscle_id&amp;l=$l\">";
			if($get_muscle_translation_short_name == ""){
				if($get_muscle_short_name == ""){
					echo"$get_muscle_simple_name";
				}
				else{
					echo"$get_muscle_short_name";
				}
			}
			else{
				echo"$get_muscle_translation_short_name";
			}
			echo"</a>
			";
			$muscles_count_inside++;
		}
		echo"
	
		)</p>
	<!-- //Muscles in sub group -->


	<!-- Text -->
		$get_current_muscle_part_of_translation_text
	<!-- //Text -->


	<!-- Add a comment -->
		<div class=\"clear\" style=\"height:20px;\"></div>
		";
		$object         = "muscle_part_of";
		$object_id      = "$get_current_muscle_part_of_id";
		$object_user_id = "1";
		$refer          = "../muscles/open_muscle_part_of.php?part_of_id=$get_current_muscle_part_of_id";
		include("../comments/_includes/add_comment_form.php");

	echo"
	<!-- //Add a comment -->

	<!-- View comments -->";
		include("../comments/_includes/view_comments.php");

		// Number of comments correct?
		//if($get_current_blog_post_comments != "$number_of_comments"){
		//	$result = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_comments=$number_of_comments WHERE blog_post_id=$post_id_mysql") or die(mysqli_error($link));
		//}
		echo" 
	<!-- //View comments -->
	";
} // muscle not found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>