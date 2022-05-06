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
if(isset($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
	$page_id = strip_tags(stripslashes($page_id));
}
else{
	$page_id = "";
}

// Select
$page_id_mysql = quote_smart($link, $page_id);
$query = "SELECT page_id, page_title, page_language, page_path, page_file_name, page_slug, page_parent_id, page_content, page_created, page_created_by_user_id, page_updated, page_updated_by_user_id, page_allow_comments, page_no_of_comments, page_uniqe_hits FROM $t_pages WHERE page_id=$page_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_page_id, $get_page_title, $get_page_language, $get_page_path, $get_page_file_name, $get_page_slug, $get_page_parent_id, $get_page_content, $get_page_created, $get_page_created_by_user_id, $get_page_updated, $get_page_updated_by_user_id, $get_page_allow_comments, $get_page_no_of_comments, $get_page_uniqe_hits) = $row;

if($get_page_id == ""){
	echo"
	<h1>Page not found</h1>

	<p>
	The page you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Back</a>
	</p>
	";
}
else{
	if($process == "1"){
		
		// Update
		$result = mysqli_query($link, "DELETE FROM $t_pages  WHERE page_id='$get_page_id'");

		// Delete old file
		if($inp_page_path != "$get_page_path" OR $get_page_file_name != "$inp_page_file_name"){
			unlink("../$get_page_path/$inp_page_file_name");
		}
		
		// Header
		header("Location: index.php?open=$open&editor_language=$editor_language&ft=success&fm=page_deleted");
		exit;

	} // process

	
	echo"
	<h1>$l_delete_page</h1>

	<p>
	$l_are_you_sure_you_want_to_delete_the_page
	</p>

	<p>
	<a href=\"index.php?open=pages&amp;page=delete_page&amp;page_id=$page_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">$l_delete</a>
	
	<a href=\"index.php?open=pages&amp;editor_language=$editor_language\" class=\"btn\">$l_cancel</a>
	</p>

	";
} // page found
?>