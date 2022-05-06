<?php
/**
*
* File: _admin/_inc/pages/delete_slide.php
* Version 1.0.0
* Date 12:13 12.11.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables --------------------------------------------------------------------------- */
$t_slides = $mysqlPrefixSav . "slides";


/*- Language ------------------------------------------------------------------------- */
// include("_translations/admin/$l/webdesign/slides_delete.php");


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['slide_id'])) {
	$slide_id = $_GET['slide_id'];
	$slide_id = strip_tags(stripslashes($slide_id));
}
else{
	$slide_id = "";
}

// Select
$slide_id_mysql = quote_smart($link, $slide_id);
$query = "SELECT slide_id, slide_language, slide_active, slide_active_from_datetime, slide_active_to_datetime, slide_active_on_page, slide_weight, slide_headline, slide_image, slide_text, slide_url, slide_link_name FROM $t_slides WHERE slide_id=$slide_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_slide_id, $get_slide_language, $get_slide_active, $get_slide_active_from, $get_slide_active_to, $get_slide_active_on_page, $get_slide_weight, $get_slide_headline, $get_slide_image, $get_slide_text, $get_slide_url, $get_slide_link_name) = $row;

if($get_slide_id == ""){
	echo"
	<h1>Slide not found</h1>

	<p>
	The slide you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Back</a>
	</p>
	";
}
else{
	if($process == "1"){
		
		// Update
		$result = mysqli_query($link, "DELETE FROM $t_slides WHERE slide_id='$get_slide_id'");

		// Delete old file
		if(file_exists("../_uploads/slides/$get_slide_language/imgs/$get_slide_image")){
			unlink("../_uploads/slides/$get_slide_language/imgs/$get_slide_image");
		}
		
		// Header
		header("Location: index.php?open=$open&page=slides&editor_language=$editor_language&ft=success&fm=slide_deleted");
		exit;

	} // process

	
	echo"
	<h1>$l_delete_slide</h1>

	<p>
	$l_are_you_sure_you_want_to_delete_the_slide
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;slide_id=$slide_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">$l_delete</a>
	
	<a href=\"index.php?open=$open&amp;page=slides&amp;editor_language=$editor_language\" class=\"btn\">$l_cancel</a>
	</p>

	";
} // page found
?>