<?php

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






/*- Variables ------------------------------------------------------------------------ */
/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['reference_id'])) {
	$reference_id = $_GET['reference_id'];
	$reference_id = strip_tags(stripslashes($reference_id));
}
else{
	$reference_id = "";
}
if(isset($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
	$group_id = strip_tags(stripslashes($group_id));
}
else{
	$group_id = "";
}
if(isset($_GET['guide_id'])) {
	$guide_id = $_GET['guide_id'];
	$guide_id = strip_tags(stripslashes($guide_id));
}
else{
	$guide_id = "";
}
if(isset($_GET['comment_id'])) {
	$comment_id = $_GET['comment_id'];
	$comment_id = strip_tags(stripslashes($comment_id));
}
else{
	$comment_id = "";
}


if(isset($_SESSION['user_id'])){
	// Search for guide
	$reference_id_mysql = quote_smart($link, $reference_id);
	$group_id_mysql = quote_smart($link, $group_id);
	$guide_id_mysql = quote_smart($link, $guide_id);
	$query = "SELECT guide_id, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_number, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_comments FROM $t_references_index_guides WHERE guide_id=$guide_id_mysql AND guide_group_id=$group_id_mysql AND guide_reference_id=$reference_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_guide_id, $get_current_guide_title, $get_current_guide_title_clean, $get_current_guide_title_short, $get_current_guide_title_length, $get_current_guide_number, $get_current_guide_group_id, $get_current_guide_group_title, $get_current_guide_reference_id, $get_current_guide_reference_title, $get_current_guide_read_times, $get_current_guide_read_ipblock, $get_current_guide_created, $get_current_guide_updated, $get_current_guide_comments) = $row;

	if($get_current_guide_id != ""){
		// Get reference
		$query = "SELECT reference_id, reference_title, reference_title_clean, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index WHERE reference_id=$get_current_guide_reference_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_reference_id, $get_current_reference_title, $get_current_reference_title_clean, $get_current_reference_is_active, $get_current_reference_front_page_intro, $get_current_reference_description, $get_current_reference_language, $get_current_reference_main_category_id, $get_current_reference_main_category_title, $get_current_reference_sub_category_id, $get_current_reference_sub_category_title, $get_current_reference_image_file, $get_current_reference_image_thumb, $get_current_reference_icon_16, $get_current_reference_icon_32, $get_current_reference_icon_48, $get_current_reference_icon_64, $get_current_reference_icon_96, $get_current_reference_icon_260, $get_current_reference_groups_count, $get_current_reference_guides_count, $get_current_reference_read_times, $get_current_reference_read_times_ip_block, $get_current_reference_created, $get_current_reference_updated) = $row;

		// Get group
		$query = "SELECT group_id, group_title, group_title_clean, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime FROM $t_references_index_groups WHERE group_id=$get_current_guide_group_id AND group_reference_id=$get_current_guide_reference_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_number, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime) = $row;

		// Search for comment
		$comment_id_mysql = quote_smart($link, $comment_id);
		$query = "SELECT comment_id, comment_reference_id, comment_reference_title, comment_group_id, comment_group_title, comment_guide_id, comment_guide_title, comment_language, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_image_path, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, comment_text, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment FROM $t_references_index_guides_comments WHERE comment_id=$comment_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_comment_id, $get_current_comment_reference_id, $get_current_comment_reference_title, $get_current_comment_group_id, $get_current_comment_group_title, $get_current_comment_guide_id, $get_current_comment_guide_title, $get_current_comment_language, $get_current_comment_approved, $get_current_comment_datetime, $get_current_comment_time, $get_current_comment_date_print, $get_current_comment_user_id, $get_current_comment_user_alias, $get_current_comment_user_image_path, $get_current_comment_user_image_file, $get_current_comment_user_ip, $get_current_comment_user_hostname, $get_current_comment_user_agent, $get_current_comment_title, $get_current_comment_text, $get_current_comment_rating, $get_current_comment_helpful_clicks, $get_current_comment_useless_clicks, $get_current_comment_marked_as_spam, $get_current_comment_spam_checked, $get_current_comment_spam_checked_comment) = $row;

		if($get_current_comment_id != ""){


			/*- Header ----------------------------------------------------------- */
			$website_title = "$get_current_guide_title - $l_report_comment";
			if(file_exists("./favicon.ico")){ $root = "."; }
			elseif(file_exists("../favicon.ico")){ $root = ".."; }
			elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
			elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
			include("$root/_webdesign/header.php");


			// Check access to comment
			// Get my user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

			if($get_current_comment_marked_as_spam == "1" OR $get_current_comment_spam_checked == "1"){
				echo"<p>Already reported</p>";
			} // alreaddy reported
			else{

				if($process == "1"){
					$inp_text = $_POST['inp_text'];
					$inp_text = output_html($inp_text);
					
					if(empty($inp_text)){
						$url = "comment_guide_report.php?comment_id=$get_current_comment_id&reference_id=$get_current_reference_id&group_id=$get_current_group_id&guide_id=$get_current_guide_id&l=$l&ft=error&fm=missing_text";
						header("Location: $url");
						exit;
					}


					// Report
					$datetime = date("Y-m-d H:i:s");

					$inp_ip = $_SERVER['REMOTE_ADDR'];
					$inp_ip = output_html($inp_ip);
					$inp_ip_mysql = quote_smart($link, $inp_ip);

					$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
					$inp_hostname = output_html($inp_hostname);
					$inp_hostname_mysql = quote_smart($link, $inp_hostname);

					$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
					$inp_user_agent = output_html($user_agent);
					$inp_user_agent_mysql = quote_smart($link, $user_agent);



					$result = mysqli_query($link, "UPDATE $t_references_index_guides_comments SET 
comment_marked_as_spam='1',
comment_spam_checked='0', 
comment_spam_checked_comment=''
 WHERE comment_id=$get_current_comment_id") or die(mysqli_error($link));



				

					// Email to moderators
					$read_comment_url = "$configSiteURLSav/$get_current_reference_title_clean/$get_current_group_title_clean/$get_current_guide_title_clean.php?reference_id=$get_current_reference_id&group_id=$get_current_group_id&guide_id=$get_current_guide_id&l=$l&ft=success&fm=report_sent#comment$get_current_comment_id#comment$get_comment_id";

					$query = "SELECT user_id, user_email, user_name, user_alias, user_language FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_mod_user_id, $get_mod_user_email, $get_mod_user_name, $get_mod_user_alias, $get_user_language) = $row;
						$subject = "Course comment reported ";
						

						$message = "<html>\n";
						$message = $message. "<head>\n";
						$message = $message. "  <title>$subject</title>\n";
						$message = $message. " </head>\n";
						$message = $message. "<body>\n";

						$message = $message. "<p>$l_hello,</p>\n";




						$message = $message. "<p>\n";
						$message = $message. "A comment has been reported.<br />\n";
						$message = $message. "$l_follow_the_url_to_read_the_comment<br />\n";
						$message = $message. "<a href=\"$read_comment_url\">$read_comment_url</a>\n";
						$message = $message. "</p>\n";

						$message = $message. "Report reason: $inp_text";

						$message = $message. "<h2>Comment</h2>
<table>
 <tr>
  <td>
	<span>Comment ID:</span>
  </td>
  <td>
	<span>$get_current_comment_id</span>
  </td>
 </tr>

 <tr>
  <td>
	<span>Reference:</span>
  </td>
  <td>
	<span>$get_current_reference_id &middot; $get_current_reference_title</span>
  </td>
 </tr>


 <tr>
  <td>
	<span>Group:</span>
  </td>
  <td>
	<span> $get_current_group_id &middot; $get_current_group_title</span>
  </td>
 </tr>

 <tr>
  <td>
	<span>Guide:</span>
  </td>
  <td>
	<span>$get_current_guide_id &middot; $get_current_guide_title</span>
  </td>
 </tr>

 <tr>
  <td>
	<span>Language:</span>
  </td>
  <td>
	<span>$get_current_comment_language</span>
  </td>
 </tr>

 <tr>
  <td>
	<span>Approved:</span>
  </td>
  <td>
	<span>$get_current_comment_approved</span>
  </td>
 </tr>

 <tr>
  <td>
	<span>Datetime:</span>
  </td>
  <td>
	<span>$get_current_comment_datetime</span>
  </td>
 </tr>

 <tr>
  <td>
	<span>Unix time:</span>
  </td>
  <td>
	<span>$get_current_comment_time</span>
  </td>
 </tr>

 <tr>
  <td>
	<span>Datetime print:</span>
  </td>
  <td>
	<span>$get_current_comment_date_print</span>
  </td>
 </tr>

 <tr>
  <td>
	<span>User ID:</span>
  </td>
  <td>
	<span>$get_current_comment_user_id</span>
  </td>
 </tr>

 <tr>
  <td>
	<span>User Alias:</span>
  </td>
  <td>
	<span>$get_current_comment_user_alias</span>
  </td>
 </tr>


 <tr>
  <td>
	<span>User image:</span>
  </td>
  <td>
	<span>$get_current_comment_user_image_path/$get_current_comment_user_image_file</span>
  </td>
 </tr>


 <tr>
  <td>
	<span>User IP:</span>
  </td>
  <td>
	<span>$get_current_comment_user_ip</span>
  </td>
 </tr>


 <tr>
  <td>
	<span>Hostname:</span>
  </td>
  <td>
	<span>$get_current_comment_user_hostname</span>
  </td>
 </tr>


 <tr>
  <td>
	<span>Agent:</span>
  </td>
  <td>
	<span>$get_current_comment_user_agent</span>
  </td>
 </tr>


 <tr>
  <td>
	<span>Title:</span>
  </td>
  <td>
	<span>$get_current_comment_title</span>
  </td>
 </tr>


 <tr>
  <td>
	<span>Text:</span>
  </td>
  <td>
	<span>$get_current_comment_text</span>
  </td>
 </tr>
</table>
";

						$message = $message. "<p>\n";
						$message = $message. "--<br />\n";
						$message = $message. "$l_regards<br />\n";
						$message = $message. "$configFromNameSav<br />\n";
						$message = $message. "$l_email: $configFromEmailSav<br />\n";
						$message = $message. "$l_web: $configWebsiteTitleSav\n";
						$message = $message. "</p>";

						$message = $message. "</body>\n";
						$message = $message. "</html>\n";


						$headers_mail_mod = array();
						$headers_mail_mod[] = 'MIME-Version: 1.0';
						$headers_mail_mod[] = 'Content-type: text/html; charset=utf-8';
						$headers_mail_mod[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";

						mail($get_mod_user_email, $subject, $message, implode("\r\n", $headers_mail_mod));
					} // while e-mail





					// Header
					$url = "$root/$get_current_reference_title_clean/$get_current_group_title_clean/$get_current_guide_title_clean.php?reference_id=$get_current_reference_id&group_id=$get_current_group_id&guide_id=$get_current_guide_id&l=$l&ft=success&fm=report_sent#comment$get_current_comment_id";
					header("Location: $url");
					exit;

				} // process

				echo"
				<h1>$l_report_comment</h1>

			
				<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = str_replace("_", " ", $fm);
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
				<!-- //Feedback -->

				<!-- You are here -->
					<p><b>$l_you_are_here</b><br />
					<a href=\"$root/$get_current_reference_title_clean/$get_current_group_title_clean/$get_current_guide_title_clean.php?reference_id=$get_current_reference_id&amp;group_id=$get_current_group_id&amp;guide_id=$get_current_guide_id&amp;l=$l\">$get_current_guide_title</a>
					&gt;
					<a href=\"comment_guide_report.php?comment_id=$get_current_comment_id&amp;reference_id=$get_current_reference_id&amp;group_id=$get_current_group_id&amp;guide_id=$get_current_guide_id&amp;l=$l\">$l_report_comment</a>
					</p>
				<!-- //You are here -->


				<!-- Report comment form -->

					<form method=\"post\" action=\"comment_guide_report.php?comment_id=$get_current_comment_id&amp;reference_id=$get_current_reference_id&amp;group_id=$get_current_group_id&amp;guide_id=$get_current_guide_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
					<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_text\"]').focus();
						});
						</script>
					<!-- //Focus -->

					<p><b>$l_reason:</b><br />
					<textarea name=\"inp_text\" rows=\"8\" cols=\"30\" class=\"comment_textarea\"></textarea>
					</p>

					<p>
					<input type=\"submit\" value=\"$l_send\" class=\"btn_default\" />
					</p>
					</form>
				<!-- //Report comment form -->
				";
			} // already reported

		} // comment found
		else{

			/*- Header ----------------------------------------------------------- */
			$website_title = "$get_current_content_title - Server error 404";
			if(file_exists("./favicon.ico")){ $root = "."; }
			elseif(file_exists("../favicon.ico")){ $root = ".."; }
			elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
			elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
			include("$root/_webdesign/header.php");

			echo"Comment not found";
		} // comment not found
	} // content found
	else{
		/*- Header ----------------------------------------------------------- */
		$website_title = "Server error 404";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");
		echo"Content not found";
	
	} // Content not found
} // logged in
else{
	/*- Header ----------------------------------------------------------- */
	$website_title = "Server error 403";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/recipes/edit_comment.php?comment_id=$comment_id\">
	";

} // not logged in

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>