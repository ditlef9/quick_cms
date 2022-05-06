<?php 
/**
*
* File: howto/favorite_space.php
* Version 1.0
* Date 14:55 30.06.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Variables -------------------------------------------------------------------------------- */
if (isset($_GET['space_id'])) {
	$space_id = $_GET['space_id'];
	$space_id = stripslashes(strip_tags($space_id));
}
else{
	$space_id = "";
}
$space_id_mysql = quote_smart($link, $space_id);

// Find space
$query = "SELECT space_id, space_title, space_title_clean, space_description, space_image, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_unique_hits_user_id_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_space_id, $get_current_space_title, $get_current_space_title_clean, $get_current_space_description, $get_current_space_image, $get_current_space_is_archived, $get_current_space_unique_hits, $get_current_space_unique_hits_ip_block, $get_current_space_unique_hits_user_id_block, $get_current_space_created_datetime, $get_current_space_created_date_saying, $get_current_space_created_user_id, $get_current_space_created_user_alias, $get_current_space_created_user_image, $get_current_space_updated_datetime, $get_current_space_updated_date_saying, $get_current_space_updated_user_id, $get_current_space_updated_user_alias, $get_current_space_updated_user_image) = $row;

if($get_current_space_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "404 server error";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"
	<h1>Server error 404</h1>

	<p>Space not found.</p>
	";
}
else{
	// Get user
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		// Add or remove?
		$query = "SELECT favorite_id, favorite_space_id, favorite_user_id, favorite_category_id, favorite_space_title, favorite_space_description FROM $t_knowledge_spaces_favorites WHERE favorite_space_id=$get_current_space_id AND favorite_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_favorite_id, $get_favorite_space_id, $get_favorite_user_id, $get_favorite_category_id, $get_favorite_space_title, $get_favorite_space_description) = $row;
		if($get_favorite_id == ""){

			$inp_space_title_mysql = quote_smart($link, $get_current_space_title);

			$inp_space_description = substr($inp_space_description, 0, 198);
			$inp_space_description_mysql = quote_smart($link, $inp_space_description);

			mysqli_query($link, "INSERT INTO $t_knowledge_spaces_favorites 
			(favorite_id, favorite_space_id, favorite_user_id, favorite_category_id, favorite_space_title, favorite_space_description) 
			VALUES 
			(NULL, $get_current_space_id, $my_user_id_mysql, '1', $inp_space_title_mysql, $inp_space_description_mysql)")
			or die(mysqli_error($link));
	
			$url = "open_space.php?space_id=$get_current_space_id&ft=success&fm=space_favorited";
			header("Location: $url");
			exit;
		}
		else{
			$result = mysqli_query($link, "DELETE FROM $t_knowledge_spaces_favorites WHERE favorite_id=$get_favorite_id");

			$url = "open_space.php?space_id=$get_current_space_id&ft=success&fm=page_removed_from_favorites";
			header("Location: $url");
			exit;

		}
			
	} // logged in
	else{
		$url = "$root/users/login.php?l=$l&amp;referer=$root/knowledge/favorite_space.php?space_id=$get_current_space_id" . "";
		header("Location: $url");
		exit;
		
	}
} // space found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>