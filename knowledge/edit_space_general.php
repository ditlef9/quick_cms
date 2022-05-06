<?php 
/**
*
* File: howto/edit_space_general.php
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

/*- Translation ------------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/knowledge/ts_new_page.php");
include("$root/_admin/_translations/site/$l/knowledge/ts_view_page.php");
include("$root/_admin/_translations/site/$l/knowledge/ts_edit_space.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

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
$query = "SELECT space_id, space_title, space_title_clean, space_category_id, space_description, space_text, space_image, space_thumb_32, space_thumb_16, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_unique_hits_user_id_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_space_id, $get_current_space_title, $get_current_space_category_id, $get_current_space_title_clean, $get_current_space_description, $get_current_space_text, $get_current_space_image, $get_current_space_thumb_32, $get_current_space_thumb_16, $get_current_space_is_archived, $get_current_space_unique_hits, $get_current_space_unique_hits_ip_block, $get_current_space_unique_hits_user_id_block, $get_current_space_created_datetime, $get_current_space_created_date_saying, $get_current_space_created_user_id, $get_current_space_created_user_alias, $get_current_space_created_user_image, $get_current_space_updated_datetime, $get_current_space_updated_date_saying, $get_current_space_updated_user_id, $get_current_space_updated_user_alias, $get_current_space_updated_user_image) = $row;

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


	// Check if I am admin, second in commander0
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
		// Access?
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		// Get my user
		$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
	
		$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND member_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_member_id, $get_current_member_space_id, $get_current_member_rank, $get_current_member_user_id, $get_current_member_user_alias, $get_current_member_user_image, $get_current_member_user_about, $get_current_member_added_datetime, $get_current_member_added_date_saying, $get_current_member_added_by_user_id, $get_current_member_added_by_user_alias, $get_current_member_added_by_user_image) = $row;
		
		if($get_current_member_id == ""){

			if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
				// If im admin, then add me
	
				$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
				$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
				$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

				mysqli_query($link, "INSERT INTO $t_knowledge_spaces_members
				(member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image) 
				VALUES 
				(NULL, $get_current_space_id, 'admin', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql, '', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$url = "open_space.php?space_id=$get_current_space_id&ft=error&fm=your_not_a_space_member_and_thus_cannot_edit_the_space";
				header("Location: $url");
				exit;
			}	
		}
		else{
			// Im registered member.
			// Can edit members: admin, moderator
			// Can edit space:   admin, moderator, editor
			if($get_current_member_rank == "admin" OR $get_current_member_rank == "moderator" OR $get_current_member_rank == "editor"){

			}
			else{
				$url = "open_space.php?space_id=$get_current_space_id&ft=error&fm=your_dont_have_access_to_edit_this_space__please_contact_the_admin_for_access";
				header("Location: $url");
				exit;
			}
		}


		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_current_space_title - $l_edit";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");
		
		// Sessions for diagrams
		$_SESSION['space_id'] = "$get_current_space_id";

		// Begin space edit
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

			$inp_text = $_POST['inp_text'];

			$inp_description = substr($inp_text, 0, 200);
			$inp_description = output_html($inp_description);
			$inp_description = str_replace("&lt;br&gt;", "
", $inp_description);
			$inp_description = str_replace("&lt;div&gt;", "", $inp_description);
			$inp_description = str_replace("&lt;/div&gt;", "
", $inp_description);
			$inp_description = str_replace("&lt;h1&gt;", "", $inp_description);
			$inp_description = str_replace("&lt;/h1&gt;", "
", $inp_description);
			$inp_description = str_replace("&lt;h2&gt;", "", $inp_description);
			$inp_description = str_replace("&lt;/h2&gt;", "
", $inp_description);
			$inp_description = str_replace("&lt;h3&gt;", "", $inp_description);
			$inp_description = str_replace("&lt;/h3&gt;", "
", $inp_description);
			$inp_description = str_replace("&lt;p&gt;", "", $inp_description);
			$inp_description = str_replace("&lt;/p&gt;", "
", $inp_description);
			$inp_description_mysql = quote_smart($link, $inp_description);

			$datetime = date("Y-m-d H:i:s");
			$date_saying = date("j M Y");
			$datetime_saying = date("j. M Y H:i");
	
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

			// Update general
			$result = mysqli_query($link, "UPDATE $t_knowledge_spaces_index SET 
							space_title=$inp_title_mysql, 
							space_title_clean=$inp_title_clean_mysql, 
							space_description=$inp_description_mysql, 
							space_updated_datetime='$datetime', 
							space_updated_date_saying='$date_saying', 
							space_updated_user_id='$get_my_user_id', 
							space_updated_user_alias=$inp_my_user_alias_mysql, 
							space_updated_user_image=$inp_my_user_image_mysql
							 WHERE space_id=$get_current_space_id");
			// Text
			require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
			$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);

			if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator" OR $get_my_user_rank == "editor"){
			}
			elseif($get_my_user_rank == "trusted"){
			}
			else{
				// p, ul, li, b
				$config->set('HTML.Allowed', 'p,b,strong,a[href],i,ul,li');
				$inp_text = $purifier->purify($inp_text);
			}

			$inp_text = encode_national_letters($inp_text);

			// Inp text fix HTML
			$inp_text = str_replace("<p><br />", "<p>", $inp_text);
			$inp_text = str_replace("<br /><br /></p>", "</p>", $inp_text);


			$sql = "UPDATE $t_knowledge_spaces_index SET space_text=? WHERE space_id=$get_current_space_id";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_text);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}



			// Search engine
			$inp_index_title = "$inp_title | $l_spaces";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_short_description = output_html($inp_text);
			$inp_index_short_description = substr($inp_index_short_description, 0, 200);
			$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

			$inp_index_url = "knowledge/open_space.php?space_id=$get_current_space_id";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);

			$inp_index_language_mysql = quote_smart($link, $l);

			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='knowledge' AND index_reference_name='space_id' AND index_reference_id=$get_current_space_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id == ""){
				// Insert
				echo"<span>Insert $inp_index_title<br /></span>\n";
				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, '', 
				'knowledge', 'space', 0, 'space_id', $get_current_space_id,
				'0', 0, '$datetime', '$datetime_saying', $inp_index_language_mysql,
				0)")
				or die(mysqli_error($link));
			}
			else{
				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
							index_title=$inp_index_title_mysql, 
							index_short_description=$inp_index_short_description_mysql 
							 WHERE index_id=$get_index_id");
			}



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
					$url = "edit_space_general.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm"; 
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
						
						$url = "edit_space_general.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;

					}
					else{
						// Keep orginal
						if($width > 261){
							$newwidth=260;
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

						$result = mysqli_query($link, "UPDATE $t_knowledge_spaces_index SET 
							space_image=$inp_space_image_mysql,
							space_thumb_32=$inp_space_thumb_a_mysql,
							space_thumb_16=$inp_space_thumb_b_mysql 
							WHERE space_id=$get_current_space_id");


						// Delete thumbs
						if(file_exists("$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_thumb_32") && $get_current_space_thumb_32 != ""){
							unlink("$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_thumb_32");
						}
						if(file_exists("$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_thumb_16") && $get_current_space_thumb_16 != ""){
							unlink("$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_thumb_16");
						}


						// Send feedback
						$ft = "success";
						$fm = "image_uploaded";
						$new_image = $get_my_user_id . "_" . $datetime . "." . $extension;
						$url = "edit_space_general.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm&new_image=$new_image"; 
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
						$ft = "warning";
						$url = "edit_space_general.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;

					case UPLOAD_ERR_FORM_SIZE:
           					$fm_front = "photo_exceeds_filesize_form";
						$ft = "warning";
						$url = "edit_space_general.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;
					default:
           					$fm_front = "unknown_upload_error";
						$ft = "warning";
						$url = "edit_space_general.php?space_id=$get_current_space_id&l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;
				}

			}


			// Send feedback
			$url = "edit_space_general.php?space_id=$get_current_space_id&l=$l&ft=success&fm=changes_saved"; 
			header("Location: $url");
			exit;

		} // process

		echo"
		<h1>$l_edit_space</h1>

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\">$get_current_space_title</a>
			&gt;
			<a href=\"edit_space.php?space_id=$get_current_space_id&amp;l=$l\">$l_edit</a>
			&gt;
			<a href=\"edit_space_general.php?space_id=$get_current_space_id&amp;l=$l\">$l_general</a>
			</p>
		<!-- Where am I ? -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Nice Edit -->";
			/*
			echo"
			<script type=\"text/javascript\" src=\"_js/nicedit/nicedit_new_space_edit_space.js\"></script>
			<script type=\"text/javascript\">
			bkLib.onDomLoaded(function() {
        			new nicEditor({iconsPath : '_js/nicedit/niceditoricons.png'}).panelInstance('inp_text');
			});
			</script>
			*/
			echo"
		<!-- //Nice Edit -->

					<!-- TinyMCE -->
			
						<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/tinymce/tinymce.min.js\"></script>
						<script>
						tinymce.init({
							selector: 'textarea.editor',
							plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
							toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
							image_advtab: true,
							content_css: [
								'$root/_admin/_javascripts/tinymce_includes/fonts/lato/lato_300_300i_400_400i.css',
								'$root/_admin/_javascripts/tinymce_includes/codepen.min.css'
							],
							link_list: [
								{ title: 'My page 1', value: 'http://www.tinymce.com' },
								{ title: 'My page 2', value: 'http://www.moxiecode.com' }
							],
							image_list: [
								{ title: 'My page 1', value: 'http://www.tinymce.com' },
								{ title: 'My page 2', value: 'http://www.moxiecode.com' }
							],
								image_class_list: [
								{ title: 'None', value: '' },
								{ title: 'Some class', value: 'class-name' }
							],
							importcss_append: true,
							height: 400,
							table_default_styles: {
								'width': '100%'
							},
							table_class_list: [
								{title: 'None', value: ''},
								{title: 'hor_zebra', value: 'hor_zebra'}
							],
							table_row_class_list: [
								{title: 'None', value: ''},
								{title: 'important', value: 'important'},
								{title: 'danger', value: 'danger'}
							],
							file_picker_callback: function (callback, value, meta) {
								/* Provide file and text for the link dialog */
								if (meta.filetype === 'file') {
									callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
								}
								/* Provide image and alt text for the image dialog */
								if (meta.filetype === 'image') {
									callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
								}
								/* Provide alternative source and posted for the media dialog */
								if (meta.filetype === 'media') {
									callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
								}
							}
						});
						</script>
					<!-- //TinyMCE -->
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			<!-- //Focus -->
				
			<!-- Form -->
				<form method=\"POST\" action=\"edit_space_general.php?space_id=$get_current_space_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>$l_title</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_current_space_title\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>$l_body</b> 
				(<a href=\"media.php?space_id=$get_current_space_id&amp;page_id=$get_current_page_id&amp;l=$l\" target=\"_blank\" class=\"small\">$l_media</a>
				&middot;
				<a href=\"diagrams.php?space_id=$get_current_space_id&amp;page_id=$get_current_page_id&amp;l=$l\" target=\"_blank\" class=\"small\">$l_diagrams</a>)<br />
				<textarea name=\"inp_text\" id=\"inp_text\" class=\"editor\" cols=\"40\" style=\"width: 100%;min-height:500px\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_space_text</textarea>
				</p>

				<!-- Image -->
					";
					if(file_exists("$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_image") && $get_current_space_image != ""){
						echo"
						<p><b>$l_existing_image</b><br />
						<img src=\"$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_image\" alt=\"$get_current_space_image\" />
						</p>
						";
					}
					echo"
				<!-- //Image -->

				<p><b>$l_new_image (260x260 png)</b><br />
				<input name=\"inp_image\" type=\"file\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>


				<p><b>$l_category</b> (<a href=\"space_categories.php?l=$l\" target=\"_blank\">$l_add_edit</a>)<br />
				<select name=\"inp_category\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
				$query = "SELECT category_id, category_title FROM $t_knowledge_spaces_categories ORDER BY category_title ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_category_id, $get_category_title) = $row;	
					echo"			<option value=\"$get_category_id\""; if($get_current_space_category_id == "$get_category_id"){ echo" selected=\"selected\""; } echo">$get_category_title</option>\n";
				}
				echo"
				</select>
				</p>
				<p>
				<input type=\"submit\" value=\"$l_save_changes\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
				</form>
			<!-- //Form -->

			<!-- Back -->
				<p>
				<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\">$l_view_space</a>
				</p>
			<!-- //Back -->

		";
			


	} // logged in
	else{
		$url = "$root/users/login.php?l=$l&amp;referer=$root/knowledge/edit_space.php?space_id=$get_current_space_id";
		header("Location: $url");
		exit;
	} // not logged in
}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>