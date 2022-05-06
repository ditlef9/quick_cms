<?php
/**
*
* File: _admin/_inc/pages/edit_page.php
* Version 1.0 
* Date 18:50 29.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['image_id'])) {
	$image_id = $_GET['image_id'];
	$image_id = strip_tags(stripslashes($image_id));
}
else{
	$image_id = "";
}
if(isset($_GET['image_path_id'])) {
	$image_path_id = $_GET['image_path_id'];
	$image_path_id = strip_tags(stripslashes($image_path_id));
}
else{
	$image_path_id = "";
}
// Select
$image_id_mysql = quote_smart($link, $image_id);
$query = "SELECT image_id, image_title, image_language, image_path, image_file_name, image_slug, image_created, image_created_by_user_id, image_updated, image_updated_by_user_id, image_uniqe_hits, image_uniqe_hits_ip_block FROM $t_images WHERE image_id=$image_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_image_id, $get_image_title, $get_image_language, $get_image_path, $get_image_file_name, $get_image_slug, $get_image_created, $get_image_created_by_user_id, $get_image_updated, $get_image_updated_by_user_id, $get_image_uniqe_hits, $get_image_uniqe_hits_ip_block) = $row;

if($get_image_id == ""){
	echo"
	<h1>Image not found</h1>

	<p>
	The image you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Back</a>
	</p>
	";
}
else{
	// Get path ID
	


	if($process == "1"){
		
		// Update
		$result = mysqli_query($link, "DELETE FROM $t_images WHERE image_id='$get_image_id'");

		// Delete file
		unlink("../$get_image_path/$get_image_file_name");
		
		
		// Header
		header("Location: index.php?open=$open&editor_language=$editor_language&image_path_id=$image_path_id&ft=success&fm=image_deleted");
		exit;

	} // process

	
	echo"
	<h1>$l_delete_image</h1>

	<p>
	$l_are_you_sure_you_want_to_delete_the_image
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;page=delete_image&amp;image_id=$image_id&amp;image_path_id=$image_path_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">$l_delete</a>
	
	<a href=\"index.php?open=$open&amp;image_path_id=$image_path_id&amp;editor_language=$editor_language\" class=\"btn\">$l_cancel</a>
	</p>

	";
} // page found
?>