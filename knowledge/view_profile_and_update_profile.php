<?php
/**
*
* File: knowledge/view_profile_and_update_profile.php
* Version 1
* Date 20:57 24.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "1";
$pageAllowCommentsSav = "0";

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

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_users.php");

/*- Variables --------------------------------------------------------------------------- */
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
	echo"
	<h1>Error</h1>
	
	<p>$l_user_profile_not_found</p>
	";
	die;
}
$user_id_mysql = quote_smart($link, $user_id);

$tabindex = 0;

/*- Content --------------------------------------------------------------------------- */


// Get user
$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_rank, user_points, user_points_rank, user_gender, user_dob, user_registered, user_registered_time, user_last_online, user_last_online_time FROM $t_users WHERE user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_user_id, $get_current_user_email, $get_current_user_name, $get_current_user_alias, $get_current_user_language, $get_current_user_rank, $get_current_user_points, $get_current_user_points_rank, $get_current_user_gender, $get_current_user_dob, $get_current_user_registered, $get_current_user_registered_time, $get_current_user_last_online, $get_current_user_last_online_time) = $row;

$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter, profile_views, profile_views_ip_block, profile_privacy FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_profile_id, $get_current_profile_user_id, $get_current_profile_first_name, $get_current_profile_middle_name, $get_current_profile_last_name, $get_current_profile_address_line_a, $get_current_profile_address_line_b, $get_current_profile_zip, $get_current_profile_city, $get_current_profile_country, $get_current_profile_phone, $get_current_profile_work, $get_current_profile_university, $get_current_profile_high_school, $get_current_profile_languages, $get_current_profile_website, $get_current_profile_interested_in, $get_current_profile_relationship, $get_current_profile_about, $get_current_profile_newsletter, $get_current_profile_views, $get_current_profile_views_ip_block, $get_current_profile_privacy) = $row;



if($get_current_user_id == ""){
	echo"
	<h1>Error</h1>
	
	<p>$l_user_profile_not_found</p>
	";
	
}
else{
	if($get_current_profile_id == ""){
		echo"<div class=\"info\"><p>Profile not found for user $get_current_user_id. Creating a profile.</div>";
		mysqli_query($link, "INSERT INTO $t_users_profile
		(profile_id, profile_user_id, profile_country, profile_newsletter, profile_views, profile_privacy) 
		VALUES 
		(NULL, '$get_current_user_id', '', '0', '0', 'public')")
		or die(mysqli_error($link));
	}

	// User photo
	$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_title, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200, photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments, photo_x_offset, photo_y_offset, photo_text FROM $t_users_profile_photo WHERE photo_user_id=$get_current_user_id AND photo_profile_image='1'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_photo_id, $get_current_photo_user_id, $get_current_photo_profile_image, $get_current_photo_title, $get_current_photo_destination, $get_current_photo_thumb_40, $get_current_photo_thumb_50, $get_current_photo_thumb_60, $get_current_photo_thumb_200, $get_current_photo_uploaded, $get_current_photo_uploaded_ip, $get_current_photo_views, $get_current_photo_views_ip_block, $get_current_photo_likes, $get_current_photo_comments, $get_current_photo_x_offset, $get_current_photo_y_offset, $get_current_photo_text) = $row;
	
	// Create thumb
	if($get_current_photo_destination != "" && file_exists("$root/_uploads/users/images/$get_current_photo_user_id/$get_current_photo_destination")){
		
		// Thumb 60
		if($get_current_photo_thumb_60 == ""){
			// Update name
			$ext = get_extension($get_current_photo_destination);
			$inp_thumb_name = str_replace($ext, "", $get_current_photo_destination);
			$inp_thumb_name = $inp_thumb_name . "_60." . $ext;
			$inp_thumb_name_mysql = quote_smart($link, $inp_thumb_name);
			
			$result_update = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_thumb_60=$inp_thumb_name_mysql  WHERE photo_id=$get_current_photo_id") or die(mysqli_error($link));

			// Get name
			$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_title, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200, photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments, photo_x_offset, photo_y_offset, photo_text FROM $t_users_profile_photo WHERE photo_user_id=$get_current_user_id AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_photo_id, $get_current_photo_user_id, $get_current_photo_profile_image, $get_current_photo_title, $get_current_photo_destination, $get_current_photo_thumb_40, $get_current_photo_thumb_50, $get_current_photo_thumb_60, $get_current_photo_thumb_200, $get_current_photo_uploaded, $get_current_photo_uploaded_ip, $get_current_photo_views, $get_current_photo_views_ip_block, $get_current_photo_likes, $get_current_photo_comments, $get_current_photo_x_offset, $get_current_photo_y_offset, $get_current_photo_text) = $row;
	

		}
		
		if(!(file_exists("$root/_uploads/users/images/$get_current_photo_user_id/$get_current_photo_thumb_60"))){
			// Make thumb
			$inp_new_x = 60; // 950
			$inp_new_y = 60; // 640
			resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_current_photo_user_id/$get_current_photo_destination", "$root/_uploads/users/images/$get_current_photo_user_id/$get_current_photo_thumb_60");
	
		}
	

		// Thumb 200
		if($get_current_photo_thumb_200 == ""){
			// Update name
			$ext = get_extension($get_current_photo_destination);
			$inp_thumb_name = str_replace($ext, "", $get_current_photo_destination);
			$inp_thumb_name = $inp_thumb_name . "_200." . $ext;
			$inp_thumb_name_mysql = quote_smart($link, $inp_thumb_name);
			
			$result_update = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_thumb_200=$inp_thumb_name_mysql  WHERE photo_id=$get_current_photo_id") or die(mysqli_error($link));

			// Get name
			$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_title, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200, photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments, photo_x_offset, photo_y_offset, photo_text FROM $t_users_profile_photo WHERE photo_user_id=$get_current_user_id AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_photo_id, $get_current_photo_user_id, $get_current_photo_profile_image, $get_current_photo_title, $get_current_photo_destination, $get_current_photo_thumb_40, $get_current_photo_thumb_50, $get_current_photo_thumb_60, $get_current_photo_thumb_200, $get_current_photo_uploaded, $get_current_photo_uploaded_ip, $get_current_photo_views, $get_current_photo_views_ip_block, $get_current_photo_likes, $get_current_photo_comments, $get_current_photo_x_offset, $get_current_photo_y_offset, $get_current_photo_text) = $row;
	

		}
		
		if(!(file_exists("$root/_uploads/users/images/$get_current_photo_user_id/$get_current_photo_thumb_60"))){
			// Make thumb
			$inp_new_x = 200; // 950
			$inp_new_y = 200; // 640
			resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_current_photo_user_id/$get_current_photo_destination", "$root/_uploads/users/images/$get_current_photo_user_id/$get_current_photo_thumb_200");
	
		}
	}





	// User professional
	$query = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position FROM $t_users_professional WHERE professional_user_id=$get_current_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_professional_id, $get_current_professional_user_id, $get_current_professional_company, $get_current_professional_company_location, $get_current_professional_department, $get_current_professional_work_email, $get_current_professional_position) = $row;
	
	// Input
	$inp_user_name_mysql = quote_smart($link, $get_current_user_name);
	$inp_user_alias_mysql = quote_smart($link, $get_current_user_alias);
	$inp_user_email_mysql = quote_smart($link, $get_current_user_email);

	$inp_user_image_path = "_uploads/users/images/$get_current_user_id";
	$inp_user_image_path_mysql = quote_smart($link, $inp_user_image_path);

	$inp_user_image_file_mysql = quote_smart($link, $get_current_photo_destination);

	$inp_user_image_thumb_a_mysql = quote_smart($link, $get_current_photo_thumb_40);
	$inp_user_image_thumb_b_mysql = quote_smart($link, $get_current_photo_thumb_50);
	$inp_user_image_thumb_c_mysql = quote_smart($link, $get_current_photo_thumb_60);
	$inp_user_image_thumb_d_mysql = quote_smart($link, $get_current_photo_thumb_200);

	$inp_user_first_name_mysql = quote_smart($link, $get_current_profile_first_name);
	$inp_user_middle_name_mysql = quote_smart($link, $get_current_profile_middle_name);
	$inp_user_last_name_mysql = quote_smart($link, $get_current_profile_last_name);
		
	$inp_user_phone_mysql = quote_smart($link, $get_current_profile_phone);

	$inp_user_position_mysql = quote_smart($link, $get_current_professional_position);
	$inp_user_department_mysql = quote_smart($link, $get_current_professional_department);
	$inp_user_location_mysql = quote_smart($link, $get_current_professional_company_location);
	$inp_user_about_mysql = quote_smart($link, $get_current_profile_about);


	// Update member
	$result = mysqli_query($link, "UPDATE $t_knowledge_spaces_members SET 
					member_user_alias=$inp_user_alias_mysql, 
					member_user_email=$inp_user_email_mysql, 
					member_user_image=$inp_user_image_thumb_d_mysql
					 WHERE member_id=$get_current_user_id") or die(mysqli_error($link));


	// View profile
	$url = "$root/users/view_profile.php?user_id=$get_current_user_id&l=$l";
	header("Location: $url");
	exit;

} // user found


/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>