<?php 
/**
*
* File: howto/new_space.php
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

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Variables -------------------------------------------------------------------------------- */
$tabindex = 0;


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_space";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


// Check for user
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		if($inp_title == ""){
			$datetime = date("Y-m-d H:i:s");
			$inp_title = "Space without name $datetime";
		}
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_title_clean = clean($inp_title);
		$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);


		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");
		$datetime_saying = date("j. M Y H:i");

		$inp_category = $_POST['inp_category'];
		$inp_category = output_html($inp_category);
		$inp_category_mysql = quote_smart($link, $inp_category);
	
		// Me
		$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
		// Get my photo
		$query = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row;

		$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
		$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

		// Create general
		mysqli_query($link, "INSERT INTO $t_knowledge_spaces_index
		(space_id, space_title, space_title_clean, space_category_id, space_is_archived, space_unique_hits, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_title_clean_mysql, $inp_category_mysql, '0', '0', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql, '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT space_id FROM $t_knowledge_spaces_index WHERE space_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_space_id) = $row;
		

		// Member
		mysqli_query($link, "INSERT INTO $t_knowledge_spaces_members
		(member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_email, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image) 
		VALUES 
		(NULL, $get_current_space_id, 'admin', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_email_mysql, $inp_my_user_image_mysql, '', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
		or die(mysqli_error($link));
		
		// Search engine index
		$inp_index_title = "$inp_title | $l_spaces";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "knowledge/open_space.php?space_id=$get_current_space_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_language_mysql = quote_smart($link, $l);

		mysqli_query($link, "INSERT INTO $t_search_engine_index 
		(index_id, index_title, index_url, index_short_description, index_keywords, 
		index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
		index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
		index_unique_hits) 
		VALUES 
		(NULL, $inp_index_title_mysql, $inp_index_url_mysql, '', '', 
		'knowledge', 'spaces', '0', 'space_id', '$get_current_space_id', 
		0, 0, '$datetime', '$datetime_saying', $inp_index_language_mysql,
		0)")
		or die(mysqli_error($link));


		// Image
		// Dir
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/knowledge"))){
			mkdir("../_uploads/knowledge");
		}
		if(!(is_dir("../_uploads/knowledge/space_$get_current_space_id"))){
			mkdir("../_uploads/knowledge/space_$get_current_space_id");
		}
		$image = $_FILES['inp_image']['name'];
				
		$filename = stripslashes($_FILES['inp_image']['name']);
		$extension = get_extension($filename);
		$extension = strtolower($extension);

		if($image){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft = "warning";
				$fm = "unknown_file_format";
				$url = "open_space.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm"; 
				header("Location: $url");
				exit;
			}
			else{
				$size=filesize($_FILES['inp_image']['tmp_name']);

				if($extension=="jpg" || $extension=="jpeg" ){
					ini_set ('gd.jpeg_ignore_warning', 1);
					error_reporting(0);
					$uploadedfile = $_FILES['inp_image']['tmp_name'];
					$src = imagecreatefromjpeg($uploadedfile);
				}
				elseif($extension=="png"){
					$uploadedfile = $_FILES['inp_image']['tmp_name'];
					$src = @imagecreatefrompng($uploadedfile);
				}
				else{
					$src = @imagecreatefromgif($uploadedfile);
				}
				list($width,$height) = @getimagesize($uploadedfile);

				if($width == "" OR $height == ""){
	
					$ft = "warning";
					$fm = "photo_could_not_be_uploaded_please_check_file_size";
						
					$url = "open_space.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm"; 
					header("Location: $url");
					exit;

				}
				else{
					// Keep orginal
					if($width > 971){
						$newwidth=970;
					}
					else{
						$newwidth=$width;
					}
					$newheight=round(($height/$width)*$newwidth, 0);
					$tmp_org =imagecreatetruecolor($newwidth,$newheight);

					imagecopyresampled($tmp_org,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
					$datetime = date("ymdhis");
					$filename = "../_uploads/knowledge/space_$get_current_space_id/space_logo_". $get_current_space_id . "." . $extension;

					if($extension=="jpg" || $extension=="jpeg" ){
						imagejpeg($tmp_org,$filename,100);
					}
					elseif($extension=="png"){
						imagepng($tmp_org,$filename);
					}
					else{
						imagegif($tmp_org,$filename);
					}

					imagedestroy($tmp_org);

					// Update space
					$inp_space_image = "space_logo_" . $get_current_space_id . "." . $extension;
					$inp_space_image = output_html($inp_space_image);
					$inp_space_image_mysql = quote_smart($link, $inp_space_image);

					$inp_space_thumb_a = "space_logo_" . $get_current_space_id . "_thumb_32x32." . $extension;
					$inp_space_thumb_a = output_html($inp_space_thumb_a);
					$inp_space_thumb_a_mysql = quote_smart($link, $inp_space_thumb_a);

					$inp_space_thumb_b = "space_logo_" . $get_current_space_id . "_thumb_16x16." . $extension;
					$inp_space_thumb_b = output_html($inp_space_thumb_b);
					$inp_space_thumb_b_mysql = quote_smart($link, $inp_space_thumb_b);

					$result = mysqli_query($link, "UPDATE $t_knowledge_spaces_index SET space_image=$inp_space_image_mysql,
							space_thumb_32=$inp_space_thumb_a_mysql,
							space_thumb_16=$inp_space_thumb_b_mysql  WHERE space_id=$get_current_space_id");


					// Send feedback
					$ft = "success";
					$fm = "image_uploaded";
					$new_image = $get_my_user_id . "_" . $datetime . "." . $extension;
					$url = "open_space.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm&new_image=$new_image"; 
					header("Location: $url");
					exit;
				}  // if($width == "" OR $height == ""){
			}
		} // if($image){
		else{
			switch ($_FILES['inp_image']['error']) {
				case UPLOAD_ERR_OK:
				$fm = "photo_unknown_error";
				break;
				case UPLOAD_ERR_NO_FILE:
       					$fm = "no_file_selected";
					break;
				case UPLOAD_ERR_INI_SIZE:
           				$fm = "photo_exceeds_filesize";
					break;
				case UPLOAD_ERR_FORM_SIZE:
           				$fm_front = "photo_exceeds_filesize_form";
					break;
				default:
           				$fm_front = "unknown_upload_error";
					break;
			}
			if(isset($fm) && $fm != ""){
				$ft = "warning";
			}
						
			// Send feedback
			$url = "open_space.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm"; 
			header("Location: $url");
			exit;
		}


		// Header
		header("Location: open_space.php?space_id=$get_current_space_id&l=$l&ft=success&fm=space_created");
		exit;
	}

	echo"
	<h1>$l_new_space</h1>


	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	<!-- //Focus -->
				
	<!-- Form -->
		<form method=\"POST\" action=\"new_space.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>$l_title</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_image (260x260 png)</b><br />
		<input name=\"inp_image\" type=\"file\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>


		<p><b>$l_category</b> (<a href=\"space_categories.php?l=$l\" target=\"_blank\">$l_add_edit</a>)<br />
		<select name=\"inp_category\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT category_id, category_title FROM $t_knowledge_spaces_categories ORDER BY category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_category_id, $get_category_title) = $row;	
			echo"			<option value=\"$get_category_id\">$get_category_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"$l_create_space\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>
	<!-- //Form -->
	";

}
else{
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> New space - Please log in...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/knowledge/new_space.php\">
	";
} // not logged in


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>