<?php
/**
*
* File: _admin/_inc/comments/social_media_edit.php
* Version 1
* Date 10:34 03.03.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ----------------------------------------------------------------------- */


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['social_media_id'])){
	$social_media_id = $_GET['social_media_id'];
	$social_media_id = output_html($social_media_id);
}
else{
	$social_media_id = "";
}
$tabindex = 0;


// Get social_media
$social_media_id_mysql = quote_smart($link, $social_media_id);
$query = "SELECT social_media_id, social_media_site_id, social_media_site_title, social_media_site_logo, social_media_language, social_media_link_title, social_media_link_url, social_media_placement, social_media_code, social_media_updated, social_media_active, social_media_hits, social_media_hits_unique, social_media_hits_ipblock FROM $t_social_media WHERE social_media_id=$social_media_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_social_media_id, $get_current_social_media_site_id, $get_current_social_media_site_title, $get_current_social_media_site_logo, $get_current_social_media_language, $get_current_social_media_link_title, $get_current_social_media_link_url, $get_current_social_media_placement, $get_current_social_media_code, $get_current_social_media_updated, $get_current_social_media_active, $get_current_social_media_hits, $get_current_social_media_hits_unique, $get_current_social_media_hits_ipblock) = $row;

if($get_current_social_media_id == ""){
	echo"
	<h1>Error</h1>

	<p>
	Not found.
	</p>
	";

}
else{
	if($process == "1"){
		$result = mysqli_query($link, " DELETE FROM $t_social_media WHERE social_media_id=$get_current_social_media_id");



		$url = "index.php?open=$open&page=social_media&editor_language=$editor_language&ft=success&fm=social_media_deleted";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>Delete social media $get_current_social_media_site_title</h1>

	<p>
	Are you sure?
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;social_media_id=$get_current_social_media_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn btn_warning\">Delete</a>
	</p>
	";
}
?>