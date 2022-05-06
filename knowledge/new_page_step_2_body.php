<?php 
/**
*
* File: howto/new_page_step_2_body.php
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

/*- Translations ----------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/knowledge/ts_new_page.php");
include("$root/_admin/_translations/site/$l/knowledge/ts_edit_page.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Tables ------------------------------------------------------------------------------------ */
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
if (isset($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
	$page_id = stripslashes(strip_tags($page_id));
}
else{
	$page_id = "";
}
$page_id_mysql = quote_smart($link, $page_id);

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
	// Find page
	$query = "SELECT page_id, page_space_id, page_title, page_title_clean, page_description, page_text, page_parent_id, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_unique_hits_ip_block, page_unique_hits_user_id_block, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_image, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_image, page_version FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_page_id, $get_current_page_space_id, $get_current_page_title, $get_current_page_title_clean, $get_current_page_description, $get_current_page_text, $get_current_page_parent_id, $get_current_page_weight, $get_current_page_allow_comments, $get_current_page_no_of_comments, $get_current_page_unique_hits, $get_current_page_unique_hits_ip_block, $get_current_page_unique_hits_user_id_block, $get_current_page_created_datetime, $get_current_page_created_date_saying, $get_current_page_created_user_id, $get_current_page_created_user_alias, $get_current_page_created_user_image, $get_current_page_updated_datetime, $get_current_page_updated_date_saying, $get_current_page_updated_user_id, $get_current_page_updated_user_alias, $get_current_page_updated_user_image, $get_current_page_version) = $row;

	if($get_current_page_id == ""){
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "404 server error";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		echo"
		<h1>Server error 404</h1>

		<p>Page not found.</p>
		";
	}
	else{

		// Check if I have access
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Access?
			$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$space_id_mysql AND member_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_image, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row;
			if($get_member_id == ""){
				echo"
				<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Server error 403</h1>
		
				<meta http-equiv=\"refresh\" content=\"1;url=view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;ft=warning&amp;fm=your_not_a_member_of_this_space\">
				";
			}
			else{
				// Rank has to be admin, moderator or editor to edit pages
				if($get_member_rank == "admin" OR $get_member_rank == "moderator" OR $get_member_rank == "editor"){
					
					/*- Headers ---------------------------------------------------------------------------------- */
					$website_title = "$get_current_space_title - $get_current_page_title - $l_body";
					if(file_exists("./favicon.ico")){ $root = "."; }
					elseif(file_exists("../favicon.ico")){ $root = ".."; }
					elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
					elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
					include("$root/_webdesign/header.php");
		
					if($process == "1"){
						
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

						// page ver
						$inp_page_version = $get_current_page_version+1;

						// Update page
						$result = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET 
							page_version=$inp_page_version,
							page_description=$inp_description_mysql
							 WHERE page_id=$get_current_page_id") or die(mysqli_error($link));

						// Page content
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

						$sql = "UPDATE $t_knowledge_pages_index SET page_text=? WHERE page_id=$get_current_page_id";
						$stmt = $link->prepare($sql);
						$stmt->bind_param("s", $inp_text);
						$stmt->execute();
						if ($stmt->errno) {
							echo "FAILURE!!! " . $stmt->error; die;
						}



						// Search engine
						$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='knowledge' AND index_reference_name='page_id' AND index_reference_id=$get_current_page_id";
						$result_exists = mysqli_query($link, $query_exists);
						$row_exists = mysqli_fetch_row($result_exists);
						list($get_index_id) = $row_exists;
						if($get_index_id != ""){
							$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
											index_short_description=$inp_description_mysql,
											index_updated_datetime='$datetime', 
											index_updated_datetime_print='$datetime_saying'
											 WHERE index_id=$get_index_id") or die(mysqli_error($link));
							
						}

						// Create history
						$inp_title_mysql = quote_smart($link, $get_current_page_title);
						$inp_title_clean_mysql = quote_smart($link, $get_current_page_title_clean);
						$inp_description_mysql = quote_smart($link, $get_current_page_description);
						$inp_parent_mysql = quote_smart($link, $get_current_page_parent_id);
						

						$my_ip = $_SERVER['REMOTE_ADDR'];
						$my_ip = output_html($inp_ip);
						$my_ip_mysql = quote_smart($link, $inp_ip);

						$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
						$my_hostname = output_html($inp_hostname);
						$my_hostname_mysql = quote_smart($link, $inp_hostname);

						$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
						$my_user_agent = output_html($user_agent);
						$my_user_agent_mysql = quote_smart($link, $user_agent);

						$year = date("Y");
						$inp_can_be_deleted_year = $year+2;
						$inp_can_be_deleted_year_mysql = quote_smart($link, $inp_can_be_deleted_year);

						mysqli_query($link, "INSERT INTO $t_knowledge_pages_edit_history
						(history_id, history_page_id, history_page_version, history_page_is_deleted, history_page_title, history_page_title_clean, 
						history_page_description, history_page_text, history_page_parent_id, history_weight, history_allow_comments, 	
						history_page_no_of_comments, history_page_updated_datetime, history_page_updated_date_saying, history_page_updated_user_id, history_page_updated_user_alias, 
						history_page_updated_user_image, history_page_ip, history_page_hostname, history_page_user_agent, history_can_be_deleted_year) 
						VALUES 
						(NULL, $get_current_page_id, $inp_page_version, '0', $inp_title_mysql, $inp_title_clean_mysql, 
						$inp_description_mysql, '', $inp_parent_mysql, $get_current_page_weight, $get_current_page_allow_comments, 
						$get_current_page_no_of_comments, '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, 
						$inp_my_user_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, $inp_can_be_deleted_year_mysql)")
						or die(mysqli_error($link));
						
						$sql = "UPDATE $t_knowledge_pages_edit_history SET history_page_text=? WHERE history_page_id=$get_current_page_id AND history_page_version=$inp_page_version";
						$stmt = $link->prepare($sql);
						$stmt->bind_param("s", $inp_text);
						$stmt->execute();
						if ($stmt->errno) {
							echo "FAILURE!!! " . $stmt->error; die;
						}



						$url = "edit_page.php?space_id=$space_id&page_id=$get_current_page_id&l=$l&ft=success&fm=changes_saved";
						header("Location: $url");
						exit;

					} // process
	
					echo"
					<h1>$get_current_page_title</h1>
	
					<!-- Where am I ? -->
						<p><b>$l_you_are_here:</b><br />
						";
						if($get_current_page_parent_id != "0"){
							// Find parent
							$query = "SELECT page_id, page_space_id, page_title, page_parent_id FROM $t_knowledge_pages_index WHERE page_id=$get_current_page_parent_id AND page_space_id=$get_current_space_id";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_parent_a_page_id, $get_parent_a_page_space_id, $get_parent_a_page_title, $get_parent_a_page_parent_id) = $row;


							if($get_parent_a_page_parent_id != "0" && $get_parent_a_page_parent_id != ""){
								// Find parent
								$query = "SELECT page_id, page_space_id, page_title, page_parent_id FROM $t_knowledge_pages_index WHERE page_id=$get_parent_a_page_parent_id AND page_space_id=$get_current_space_id";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_parent_b_page_id, $get_parent_b_page_space_id, $get_parent_b_page_title, $get_parent_b_page_parent_id) = $row;


								if($get_parent_b_page_parent_id != "0"){
									// Find parent
									$query = "SELECT page_id, page_space_id, page_title, page_parent_id FROM $t_knowledge_pages_index WHERE page_id=$get_parent_b_page_parent_id AND page_space_id=$get_current_space_id";
									$result = mysqli_query($link, $query);
									$row = mysqli_fetch_row($result);
									list($get_parent_c_page_id, $get_parent_c_page_space_id, $get_parent_c_page_title, $get_parent_c_page_parent_id) = $row;

									echo"
									<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_parent_c_page_id&amp;l=$l\">$get_parent_c_page_title</a>
									&gt; ";
								}
								echo"
								<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_parent_b_page_id&amp;l=$l\">$get_parent_b_page_title</a>
								&gt; ";
							}
							echo"
							<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_parent_a_page_id&amp;l=$l\">$get_parent_a_page_title</a>
							&gt; ";
						}
						echo"
				
						<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\">$get_current_page_title</a>
						&gt;
						<a href=\"new_page_step_2_body.php?space_id=$space_id&amp;page_id=$get_current_page_id&amp;l=$l\">$l_body</a>
						</p>
					<!-- //Where am I ? -->
	
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
						$_SESSION['space_id'] = "$get_current_space_id";
						$_SESSION['page_id'] = "$get_current_page_id";

						echo"
						<script type=\"text/javascript\" src=\"_js/nicedit/nicedit_new_page_edit_page.js\"></script>
						<script type=\"text/javascript\">
						bkLib.onDomLoaded(function() {
        						new nicEditor({iconsPath : '_js/nicedit/niceditoricons.png'}).panelInstance('inp_text');
						});
						</script>";
						*/
						echo"
					<!-- //Nice Edit -->

					<!-- TinyMCE -->
			
						<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/tinymce/tinymce.min.js\"></script>
						<script>
						tinymce.init({
							selector: 'textarea.editor',
							plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help code',
							toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image code media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
							image_advtab: true,
							content_css: [
								'$root/_admin/_javascripts/tinymce_includes/fonts/lato/lato_300_300i_400_400i.css',
								'$root/_admin/_javascripts/tinymce_includes/codepen.min.css'
							],
							link_list: [\n";

								$x = 0;
								$file_picker_callback_file_fullpath = "https://www.google.com/logos/google.jpg";
								$file_picker_callback_file_alt = "My alt text";
								$file_picker_callback_video_fullpath = "https://www.google.com/logos/google.jpg";
								$file_picker_callback_video_alt = "My alt text";
								$query = "SELECT media_id, media_type, media_title, media_file_path, media_file_name, media_file_thumb_100 FROM $t_knowledge_pages_media WHERE media_space_id=$get_current_space_id AND media_page_id=$get_current_page_id AND media_type != 'image' ORDER BY media_title ASC";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_media_id, $get_media_type, $get_media_title, $get_media_file_path, $get_media_file_name, $get_media_file_thumb_100) = $row;


									if($x != "0"){
										echo",
";
									}
									echo"								";
									echo"{ title: '$get_media_title', value: '../$get_media_file_path/$get_media_file_name' }";
								
									// Transfer values to callback
									$file_picker_callback_file_fullpath = "../$get_media_file_path/$get_media_file_name";
									$file_picker_callback_file_alt = "$get_media_title";

									$x++;
								} // while

								echo"
							],
							image_list: [\n";

								$x = 0;
								$file_picker_callback_image_fullpath = "https://www.google.com/logos/google.jpg";
								$file_picker_callback_image_alt = "My alt text";
								$query = "SELECT media_id, media_type, media_title, media_file_path, media_file_name, media_file_thumb_100 FROM $t_knowledge_pages_media WHERE media_space_id=$get_current_space_id AND media_page_id=$get_current_page_id AND media_type='image' ORDER BY media_title ASC";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_media_id, $get_media_type, $get_media_title, $get_media_file_path, $get_media_file_name, $get_media_file_thumb_100) = $row;


									if($x != "0"){
										echo",
";
									}
									echo"								";
									echo"{ title: '$get_media_title', value: '../$get_media_file_path/$get_media_file_name' }";
								
									// Transfer values to callback
									$file_picker_callback_image_fullpath = "../$get_media_file_path/$get_media_file_name";
									$file_picker_callback_image_alt = "$get_media_title";

									$x++;
								} // while

								echo"
							],
								image_class_list: [
								{ title: 'None', value: '' },
								{ title: 'Some class', value: 'class-name' }
							],

							/* without images_upload_url set, Upload tab won't show up*/
							images_upload_url: 'new_page_step_2_body_upload_image.php?space_id=$space_id&page_id=$page_id&process=1',

							importcss_append: true,
							height: 700,
							file_picker_callback: function (callback, value, meta) {
								/* Provide file and text for the link dialog */
								if (meta.filetype === 'file') {
									callback('$file_picker_callback_file_fullpath', { text: '$file_picker_callback_file_alt' });
								}
								/* Provide image and alt text for the image dialog */
								if (meta.filetype === 'image') {
									callback('$file_picker_callback_image_fullpath', { alt: '$file_picker_callback_image_alt' });
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
							\$('[name=\"inp_text\"]').focus();
						});
						</script>
					<!-- //Focus -->
				
					<!-- Edit page Form -->";
						$tabindex = 0;
						echo"
						<form method=\"POST\" action=\"new_page_step_2_body.php?space_id=$space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		
						<p><b>$l_body</b> (<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_current_page_id&amp;l=$l\" target=\"_blank\" class=\"small\">$l_media</a>
						&middot;
						<a href=\"diagrams.php?space_id=$get_current_space_id&amp;page_id=$get_current_page_id&amp;l=$l\" target=\"_blank\" class=\"small\">$l_diagrams</a>)<br />
						<textarea name=\"inp_text\" id=\"inp_text\" class=\"editor\" cols=\"40\" style=\"width: 100%;min-height:700px\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_page_text</textarea>
						</p>

						<p>
						<input type=\"submit\" value=\"$l_save\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>

						</form>
					<!-- //Edit page Form -->
					";
				} // member can edit
				else{
					echo"
					<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Server error 403</h1>
		
					<meta http-equiv=\"refresh\" content=\"1;url=view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;ft=warning&amp;fm=your_user_cant_edit_pages\">

					";
				}
		
			} // is member of space
		} // logged in
		else{
			echo"
			<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> New page - Please log in...</h1>
		
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/knowledge/move_page_down.php?space_id=$get_current_page_space_id" . "amp;page_id=$get_current_page_id\">
			";
			
		}
	} // page found
} // space found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>