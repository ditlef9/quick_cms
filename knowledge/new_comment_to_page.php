<?php 
/**
*
* File: howto/new_comment_to_page.php
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

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");


/*- Variables -------------------------------------------------------------------------------- */
$tabindex = 0;
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
	$query = "SELECT page_id, page_space_id, page_title, page_title_clean, page_description, page_text, page_parent_id, page_no_of_children, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_unique_hits_ip_block, page_unique_hits_user_id_block, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_email, page_created_user_image, page_created_subscribe_to_comments, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_email, page_updated_user_image, page_updated_subscribe_to_comments, page_updated_info, page_version FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_page_id, $get_current_page_space_id, $get_current_page_title, $get_current_page_title_clean, $get_current_page_description, $get_current_page_text, $get_current_page_parent_id, $get_current_page_no_of_children, $get_current_page_weight, $get_current_page_allow_comments, $get_current_page_no_of_comments, $get_current_page_unique_hits, $get_current_page_unique_hits_ip_block, $get_current_page_unique_hits_user_id_block, $get_current_page_created_datetime, $get_current_page_created_date_saying, $get_current_page_created_user_id, $get_current_page_created_user_alias, $get_current_page_created_user_email, $get_current_page_created_user_image, $get_current_page_created_subscribe_to_comments, $get_current_page_updated_datetime, $get_current_page_updated_date_saying, $get_current_page_updated_user_id, $get_current_page_updated_user_alias, $get_current_page_updated_user_email, $get_current_page_updated_user_image, $get_current_page_updated_subscribe_to_comments, $get_current_page_updated_info, $get_current_page_version) = $row;

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
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_current_space_title - $get_current_page_title - $l_new_comment";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");


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


				// Check for IP block
				$ip_block = 0;

				$query = "SELECT comment_id, comment_datetime, comment_time FROM $t_knowledge_pages_comments WHERE comment_user_id=$my_user_id_mysql ORDER BY comment_id DESC LIMIT 0,1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_comment_id, $get_comment_datetime, $get_comment_time) = $row;
				if($get_comment_id != ""){
					$time_now = time();
					$diff_seconds = $time_now-$get_comment_time;
					if($diff_seconds < 180){
						$ip_block = 1;

						echo"
						<h1>$l_anti_spam</h1>

						<p>
						$l_please_wait_three_minutes_before_posting_a_new_comment
						</p>

						<p>
						<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\">$get_current_page_title</a>
						</p>
						";
					}
				}

				if($ip_block == 0){
					if($process == "1"){
					$inp_title = $_POST['inp_title'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					$inp_text = $_POST['inp_text'];
					$inp_text = output_html($inp_text);
					$inp_text_mysql = quote_smart($link, $inp_text);

					if($inp_text == ""){
						$url = "new_comment_to_page.php?space_id=$space_id&page_id=$get_current_page_id&l=$l&ft=error&fm=missing_comment";
						header("Location: $url");
						exit;
					}

					$inp_subscribe_to_new_comments = $_POST['inp_subscribe_to_new_comments'];
					$inp_subscribe_to_new_comments = output_html($inp_subscribe_to_new_comments);
					$inp_subscribe_to_new_comments_mysql = quote_smart($link, $inp_subscribe_to_new_comments);

					// Preselected value on or off Auto
					$query = "SELECT preselected_id, preselected_user_id, preselected_subscribe FROM $t_knowledge_preselected_subscribe WHERE preselected_user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_preselected_id, $get_preselected_user_id, $get_preselected_subscribe) = $row;
					if($get_preselected_id == ""){
						mysqli_query($link, "INSERT INTO $t_knowledge_preselected_subscribe 
						(preselected_id, preselected_user_id, preselected_subscribe) 
						VALUES 
						(NULL, $my_user_id_mysql, $inp_subscribe_to_new_comments_mysql)")
						or die(mysqli_error($link));
					}
					else{
						$result = mysqli_query($link, "UPDATE $t_knowledge_preselected_subscribe  SET 
										preselected_subscribe=$inp_subscribe_to_new_comments_mysql WHERE 
										preselected_user_id=$my_user_id_mysql");

					}
	
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

					// IP
					$my_ip = $_SERVER['REMOTE_ADDR'];
					$my_ip = output_html($my_ip);
					$my_ip_mysql = quote_smart($link, $my_ip);

					$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
					$my_hostname = output_html($my_hostname);
					$my_hostname_mysql = quote_smart($link, $my_hostname);

					$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
					$my_user_agent = output_html($my_user_agent);
					$my_user_agent_mysql = quote_smart($link, $my_user_agent);


					
					
					$datetime = date("Y-m-d H:i:s");
					$date_saying = date("j M Y");
					$time = time();
					
					// Save comment
					mysqli_query($link, "INSERT INTO $t_knowledge_pages_comments
					(comment_id, comment_page_id, comment_parent_comment_id, comment_title, comment_text, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_email, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_subscribe_to_new_comments, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment, comment_read_by_page_author) 
					VALUES 
					(NULL, $get_current_page_id, 0, $inp_title_mysql, $inp_text_mysql, '1', '$datetime', '$time', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_email_mysql, $inp_my_user_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, $inp_subscribe_to_new_comments_mysql, '0', '0', '0', '0', '0', '', '0')")
					or die(mysqli_error($link));

					// Get comment ID
					$query = "SELECT comment_id FROM $t_knowledge_pages_comments WHERE comment_datetime='$datetime'";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_comment_id) = $row;
					

					// Email page creator and updater
					$subject = "$l_new_comment_to_page $get_current_space_title $l_at_lowercase $date_saying";
					$message = "$l_there_is_a_new_comment_to_the_page $get_current_space_title. \n";
					$message = "$l_you_can_read_the_comment_by_following_the_link\n";
					$message = "$configSiteURLSav/view_page.php?space_id=$space_id&page_id=$get_current_page_id#comment$get_current_comment_id\n\n";
					$message = $message . "$l_comment_id: $get_current_comment_id\n";
					$message = $message . "$l_author: $get_my_user_alias\n";
					$message = $message . "$l_email: $get_my_user_email\n";
					$message = $message . "$l_date: $date_saying\n";
					$message = $message . "$l_email: $get_my_user_email\n";
					$message = $message . "$l_comment: $inp_text\n\n";
					$message = $message . "--\n";
					$message = $message . "$l_regards\n";
					$message = $message . "$configFromNameSav\n";
					$message = $message . "$configWebsiteTitleSav\n";
					$message = $message . "$configFromEmailSav\n\n";
					$message = $message . "$l_dont_want_any_more_emails_then_unsubscribe_by_follow_this_link\n";

					$headers = "From: $configFromEmailSav" . "\r\n" .
					    "Reply-To: $configFromEmailSav" . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();

					// Case 1: Created user id == updated user id
					// Case 2: Created user id != updated user id
					if($get_current_page_created_user_id == "$get_current_page_updated_user_id"){
						if($get_current_page_created_user_id != "$my_user_id" && $get_current_page_created_subscribe_to_comments == "1"){
							$unsubscribe_link = $configSiteURLSav . "/knowledge/unsubscribe_from_emails.php?user_id=$get_current_page_created_user_id&user_email=$get_current_page_created_user_email";
							$send_message = "$l_hello $get_current_page_created_user_alias,\n" . $message . $unsubscribe_link;
							mail($get_current_page_created_user_email, $subject, $send_message, $headers);
						}
					}
					else{
						if($get_current_page_created_user_id != "$my_user_id" && $get_current_page_created_subscribe_to_comments == "1"){
							$unsubscribe_link = $configSiteURLSav . "/knowledge/unsubscribe_from_emails.php?user_id=$get_current_page_created_user_id&user_email=$get_current_page_created_user_email";
							$send_message = "$l_hello $get_current_page_created_user_alias,\n" . $message . $unsubscribe_link;
							mail($get_current_page_created_user_email, $subject, $send_message, $headers);
						}
						if($get_current_page_updated_user_id != "$my_user_id" && $get_current_page_updated_subscribe_to_comments == "1"){
							$unsubscribe_link = $configSiteURLSav . "/knowledge/unsubscribe_from_emails.php?user_id=$get_current_page_updated_user_id&user_email=$get_current_page_updated_user_email";
							$send_message = "$l_hello $get_current_page_created_user_alias,\n" . $message . $unsubscribe_link;
							mail($get_current_page_updated_user_email, $subject, $send_message, $headers);
						}
					}

					// Email to all other comments
					$previous_email_address = "";
					$query = "SELECT comment_id, comment_user_id, comment_user_alias, comment_user_email FROM $t_knowledge_pages_comments WHERE comment_page_id=$get_current_page_id AND comment_subscribe_to_new_comments='1' ORDER BY comment_user_id ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_comment_id, $get_comment_user_id, $get_comment_user_alias, $get_comment_user_email) = $row;
						
						if($get_comment_user_email != "$previous_email_address" && $get_comment_user_email != "$get_current_page_created_user_email" && $get_comment_user_email != "$get_current_page_updated_user_email"){
							$unsubscribe_link = $configSiteURLSav . "/knowledge/unsubscribe_from_emails.php?user_id=$get_comment_user_id&user_email=$get_comment_user_email";
							$send_message = "$l_hello $get_comment_user_alias,\n" . $message . $unsubscribe_link;
							mail($get_current_page_updated_user_email, $subject, $send_message, $headers);
						}
						$previous_email_address = "$get_comment_user_email";
						
					}
	
					// header
					$url = "view_page.php?space_id=$space_id&page_id=$get_current_page_id&ft=success&fm=comment_saved#comment$get_current_comment_id";
					header("Location: $url");
					exit;
					} // process
	
					echo"
					<h1>$l_new_comment</h1>
	
					<!-- Where am I ? -->
						<p><b>$l_you_are_here:</b><br />";

			
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
						<a href=\"new_comment_to_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\">$l_new_comment</a>
				
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

					<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_title\"]').focus();
					});
					</script>
					<!-- //Focus -->
				
					<!-- New comment Form -->
					<form method=\"POST\" action=\"new_comment_to_page.php?space_id=$space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

					<p><b>$l_title</b><br />
					<input type=\"text\" name=\"inp_title\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
					</p>

					<p><b>$l_comment</b><br />
					<textarea name=\"inp_text\" id=\"inp_text\" cols=\"20\" style=\"width: 100%;min-height:200px\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
					</p>


					<p><b>$l_subscribe_to_new_comments</b><br />";
					// Preselected value on or off Auto
					$query = "SELECT preselected_id, preselected_user_id, preselected_subscribe FROM $t_knowledge_preselected_subscribe WHERE preselected_user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_preselected_id, $get_preselected_user_id, $get_preselected_subscribe) = $row;
					echo"
					<input type=\"radio\" name=\"inp_subscribe_to_new_comments\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_preselected_subscribe == "1" OR $get_preselected_subscribe == ""){ echo" checked=\"checked\""; } echo" /> $l_yes
					&nbsp;
					<input type=\"radio\" name=\"inp_subscribe_to_new_comments\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_preselected_subscribe == "0"){ echo" checked=\"checked\""; } echo" /> $l_no
					</p>

					<p>
					<input type=\"submit\" value=\"$l_post_comment\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
					</form>
				
					<!-- //New comment Form -->
					";
				} // ip block
		
			} // is member of space
		} // logged in
		else{
			$url = "$root/users/login.php?l=$l&amp;referer=$root/knowledge/new_comment_to_page.php?space_id=$get_current_page_space_id" . "amp;page_id=$get_current_page_id";
			echo"
			<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $l_new_comment - Please log in...</h1>
		
			<p><a href=\"$url\">$url</a></p>

			<meta http-equiv=\"refresh\" content=\"1;url=$url\">
			";
			
		}
	} // page found
} // space found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>