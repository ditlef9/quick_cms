<?php
/**
*
* File: _admin/_inc/muscles/muscle.php
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

if(isset($_GET['muscle_id'])){
	$muscle_id = $_GET['muscle_id'];
	$muscle_id = strip_tags(stripslashes($muscle_id));
}
else{
	$muscle_id = "";
}
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

// Find muscle
$muscle_id_mysql = quote_smart($link, $muscle_id);
$query = "SELECT muscle_id, muscle_user_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_group_id_main, muscle_group_id_sub, muscle_text, muscle_image_path, muscle_image_file, muscle_video_path, muscle_video_file, muscle_unique_hits, muscle_unique_hits_ip_block FROM $t_muscles WHERE muscle_id='$muscle_id_mysql'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_muscle_id, $get_current_muscle_user_id, $get_current_muscle_latin_name, $get_current_muscle_latin_name_clean, $get_current_muscle_simple_name, $get_current_muscle_group_id_main, $get_current_muscle_group_id_sub, $get_current_muscle_text, $get_current_muscle_image_path, $get_current_muscle_image_file, $get_current_muscle_video_path, $get_current_muscle_video_file, $get_current_muscle_unique_hits, $get_current_muscle_unique_hits_ip_block) = $row;

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


// Part of?
if($part_of_id != ""){
	$part_of_id_mysql = quote_smart($link, $part_of_id);
	$query = "SELECT muscle_part_of_id, muscle_part_of_latin_name, muscle_part_of_latin_name_clean, muscle_part_of_name, muscle_part_of_name_clean, muscle_part_of_muscle_group_id_main, muscle_part_of_muscle_group_id_sub, muscle_part_of_image_path, muscle_part_of_image_file FROM $t_muscle_part_of WHERE muscle_part_of_id=$part_of_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_muscle_part_of_id, $get_current_muscle_part_of_latin_name, $get_current_muscle_part_of_latin_name_clean, $get_current_muscle_part_of_name, $get_current_muscle_part_of_name_clean, $get_current_muscle_part_of_muscle_group_id_main, $get_current_muscle_part_of_muscle_group_id_sub, $get_current_muscle_part_of_image_path, $get_muscle_part_of_image_file) = $row;

	if($get_current_muscle_part_of_id != ""){
		// Translation
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

			echo"<div id=\"info\"><p>Created translation.</p></div>";
		}
		else{
			if($get_current_muscle_part_of_translation_name == ""){
				echo"æsj";
			}
		}

	}
}

if($get_current_muscle_id == "" OR $get_current_main_muscle_group_id == "" OR $get_current_sub_muscle_group_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $l_muscles";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"
	<p>Muscle not found.</p>
	";
	include("$root/_webdesign/footer.php");

}
else {
	// Muscle Translation
	$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_text FROM $t_muscles_translations WHERE muscle_translation_muscle_id=$muscle_id_mysql AND muscle_translation_language=$l_mysql";
	$result_translation = mysqli_query($link, $query_translation);
	$row_translation = mysqli_fetch_row($result_translation);
	list($get_current_muscle_translation_id, $get_current_muscle_translation_simple_name, $get_current_muscle_translation_text) = $row_translation;
	if($get_current_muscle_translation_id == ""){
		mysqli_query($link, "INSERT INTO $t_muscles_translations
		(muscle_translation_id, muscle_translation_muscle_id, muscle_translation_language, muscle_translation_simple_name, muscle_translation_text) 
		VALUES 
		(NULL, 	'$get_current_muscle_id', $editor_language_mysql, '$get_current_muscle_simple_name', '')")
		or die(mysqli_error($link));
		echo"<div id=\"info\"><p>Created translation.</p></div>";
	}

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
	$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_current_sub_muscle_group_id AND muscle_group_translation_language=$l_mysql";
	$result_translation = mysqli_query($link, $query_translation);
	$row_translation = mysqli_fetch_row($result_translation);
	list($get_current_sub_muscle_group_translation_id, $get_current_sub_muscle_group_translation_name) = $row_translation;
	if($get_current_sub_muscle_group_translation_id == ""){
		mysqli_query($link, "INSERT INTO $t_muscle_groups_translations
		(muscle_group_translation_id, muscle_group_translation_muscle_group_id, muscle_group_translation_language, muscle_group_translation_name) 
		VALUES 
		(NULL, 	'$get_current_sub_muscle_group_id', $l_mysql, '$get_current_sub_muscle_group_name', '')")
		or die(mysqli_error($link));
	}

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_sub_muscle_group_translation_name - $get_current_main_muscle_group_translation_name  - $l_muscles";
	if(isset($get_current_muscle_part_of_translation_name)){
		$website_title = "$get_current_muscle_part_of_translation_name - " . $website_title;
	}
	$website_title = "$get_current_muscle_translation_simple_name - " . $website_title;
	include("$root/_webdesign/header.php");

	echo"

	<!-- Headline -->
		<h1>$get_current_muscle_simple_name</h1> 
	<!-- //Headline -->


	<!-- Where am I? -->
		<p>
		<b>$l_you_are_here:</b><br />
		<a href=\"$root/muscles/index.php?l=$l\">$l_muscles</a>
		&gt;
		<a href=\"$root/muscles/open_main_group.php?main_group_id=$get_current_main_muscle_group_id&amp;l=$l\">$get_current_main_muscle_group_translation_name</a>
		&gt;
		<a href=\"$root/muscles/open_sub_group.php?main_group_id=$get_current_main_muscle_group_id&amp;sub_group_id=$get_current_sub_muscle_group_id&amp;l=$l\">$get_current_sub_muscle_group_translation_name</a>
		&gt;";
		if(isset($get_current_muscle_part_of_translation_name)){
			echo"
			<a href=\"$root/muscles/open_muscle_part_of.php?main_group_id=$get_current_main_muscle_group_id&amp;sub_group_id=$get_current_sub_muscle_group_id&amp;part_of_id=$get_current_muscle_part_of_id&amp;l=$l\">$get_current_muscle_part_of_translation_name</a>
			&gt;
			";
		}
		echo"
		<a href=\"$root/muscles/muscle.php?main_group_id=$get_current_main_muscle_group_id&amp;sub_group_id=$get_current_sub_muscle_group_id&amp;muscle_id=$get_current_muscle_id&amp;l=$l\">$get_current_muscle_translation_simple_name</a>
		</p>
	<!-- //Where am I? -->
	

	<!-- Image -->
		<p>
		";
		if($get_current_muscle_image_file != "" && file_exists("$root/$get_current_muscle_image_path/$get_current_muscle_image_file")){
			echo"<img src=\"$root/$get_current_muscle_image_path/$get_current_muscle_image_file\" alt=\"img\" />";
		}
		else{
			echo"<p>Missing image..</p>";
		}
		echo"
		</p>
	<!-- //Image -->

	<!-- Text -->
		$get_current_muscle_translation_text
	<!-- //Text -->
		


	";
} // muscle not found




/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>