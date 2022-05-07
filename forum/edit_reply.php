<?php 
/**
*
* File: forum/edit_reply.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");
include("$root/_admin/_translations/site/$l/forum/ts_new_topic.php");

/*- Forum config ------------------------------------------------------------------------ */
include("_include_tables.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['topic_id'])){
	$topic_id = $_GET['topic_id'];
	$topic_id = output_html($topic_id);
}
else{
	$topic_id = "";
}
if(isset($_GET['reply_id'])){
	$reply_id = $_GET['reply_id'];
	$reply_id = output_html($reply_id);
}
else{
	$reply_id = "";
}
if(isset($_GET['show'])) {
	$show = $_GET['show'];
	$show = strip_tags(stripslashes($show));
}
else{
	$show = "";
}

// Get topic
$topic_id_mysql = quote_smart($link, $topic_id);
$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_updated, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip, topic_reported, topic_reported_by_user_id, topic_reported_reason, topic_reported_checked FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_updated, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip, $get_current_topic_reported, $get_current_topic_reported_by_user_id, $get_current_topic_reported_reason, $get_current_topic_reported_checked) = $row;

if($get_current_topic_id == ""){
	echo"<p>Topic post not found.</p>";
	
}
else{
	// Get reply
	$reply_id_mysql = quote_smart($link, $reply_id);
	$query = "SELECT reply_id, reply_user_id, reply_user_alias, reply_user_image, reply_topic_id, reply_text, reply_created, reply_updated, reply_updated_translated, reply_selected_answer, reply_likes, reply_dislikes, reply_rating, reply_likes_ip_block, reply_user_ip, reply_reported, reply_reported_by_user_id, reply_reported_reason, reply_reported_checked FROM $t_forum_replies WHERE reply_id=$reply_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_reply_id, $get_current_reply_user_id, $get_current_reply_user_alias, $get_current_reply_user_image, $get_current_reply_topic_id, $get_current_reply_text, $get_current_reply_created, $get_current_reply_updated, $get_current_reply_updated_translated, $get_current_reply_selected_answer, $get_current_reply_likes, $get_current_reply_dislikes, $get_current_reply_rating, $get_current_reply_likes_ip_block, $get_current_reply_user_ip, $get_current_reply_reported, $get_current_reply_reported_by_user_id, $get_current_reply_reported_reason, $get_current_reply_reported_checked) = $row;

	if($get_current_reply_id == ""){
		echo"<p>Reply not found.</p>";
	}
	else{
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$l_edit_reply - $get_current_topic_title - $l_forum";
		include("$root/_webdesign/header.php");
		
		// Logged in?
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
			// Get my user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
		
	
			
			if($my_user_id == "$get_current_reply_user_id"){
				$can_edit = "true";
			}
			else{
				if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
				
					$can_edit = "true";
				}
			}
			if(!(isset($can_edit))){
				echo"
				<h1>Access denied</h1>
				
				";
			}
			if(isset($can_edit) && $can_edit == "true"){
				if($process == "1"){
					// Text
					$inp_text = $_POST['inp_text'];
					if(empty($inp_text)){
						$url = "edit_reply.php?topic_id=$topic_id&reply_id=$reply_id&l=$l&ft=error&fm=insert_some_text#answer_form";
						header("Location: $url");
						exit;
					}
					
					// Updated
					$datetime = date("Y-m-d H:i:s");



					// Topic_updated_translated
					$year = substr($datetime, 0, 4);
					$month = substr($datetime, 5, 2);
					$day = substr($datetime, 8, 2);
			
					if($day < 10){
						$day = substr($day, 1, 1);
					}
		
					if($month == "01"){
						$month_saying = "$l_january";
					}
					elseif($month == "02"){
						$month_saying = "$l_february";
					}
					elseif($month == "03"){
						$month_saying = "$l_march";
					}
					elseif($month == "04"){
						$month_saying = "$l_april";
					}
					elseif($month == "05"){
						$month_saying = "$l_may";
					}
					elseif($month == "06"){
						$month_saying = "$l_june";
					}
					elseif($month == "07"){
						$month_saying = "$l_july";
					}
					elseif($month == "08"){
						$month_saying = "$l_august";
					}
					elseif($month == "09"){
						$month_saying = "$l_september";
					}
					elseif($month == "10"){
						$month_saying = "$l_october";
					}
					elseif($month == "11"){
						$month_saying = "$l_november";
					}
					else{
						$month_saying = "$l_december";
					}

					$inp_reply_updated_translated = "$day $month_saying $year";

					$result = mysqli_query($link, "UPDATE $t_forum_replies SET 
						reply_updated='$datetime',
						reply_updated_translated='$inp_reply_updated_translated'
					 WHERE reply_id=$reply_id_mysql");

					// Text
					require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
					$config = HTMLPurifier_Config::createDefault();
					$purifier = new HTMLPurifier($config);

	
					if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator" OR $get_my_user_rank == "editor"){
					}
					elseif($get_my_user_rank == "trusted"){
					}
					else{
						// a b c d e f g h i j k l m n o p q r s t u v w x y z
						// Updated: 19:16 26.04.2019
						$config->set('HTML.Allowed', 'a[href],b,code,img[src],i,ul,li,p,pre,pre[class]');
					}

					$inp_text = $purifier->purify($inp_text);
					$inp_text = encode_national_letters($inp_text);
					$inp_text = str_replace("\x80", "&#x80;", $inp_text); // €
					$inp_text = str_replace("\x99", "&#x99;", $inp_text); // ™
			
					$sql = "UPDATE $t_forum_replies SET reply_text=? WHERE reply_id=$get_current_reply_id";
					$stmt = $link->prepare($sql);
					$stmt->bind_param("s", $inp_text);
					$stmt->execute();
					if ($stmt->errno) {
						echo "FAILURE!!! " . $stmt->error; die;
					}


					// Update user
					if($my_user_id == "$get_current_topic_user_id"){

						$inp_user_ip = $_SERVER['REMOTE_ADDR'];
						$inp_user_ip = output_html($inp_user_ip);
						$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);
	
						$inp_reply_user_alias_mysql = quote_smart($link, $get_my_user_alias);

						// Get my photo
						$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_photo_id, $get_photo_destination) = $row;

						$inp_reply_user_image_mysql = quote_smart($link, $get_photo_destination);
	

						$result = mysqli_query($link, "UPDATE $t_forum_replies SET 
							reply_user_alias=$inp_reply_user_alias_mysql,
							reply_user_image=$inp_reply_user_image_mysql,
							reply_user_ip=$inp_user_ip_mysql
						 WHERE reply_id=$reply_id_mysql");

					}
	
					$url = "view_topic.php?topic_id=$topic_id&l=$l&ft=success&fm=changes_saved#reply$get_current_reply_id";
					header("Location: $url");
					exit;
				}
				echo"
				<h1>$get_current_topic_title</h1>


				<!-- Where am I ? -->
					<p><b>$l_you_are_here</b><br />";
					if($show == "popular"){
						echo"<a href=\"index.php?show=$show&amp;l=$l\">$l_popular</a>";
					}
					elseif($show == "unanswered"){
						echo"<a href=\"index.php?show=$show&amp;l=$l\">$l_unanswered</a>";
					}
					elseif($show == "active"){
						echo"<a href=\"index.php?show=$show&amp;l=$l\">$l_active</a>";
					}
					else{
						echo"<a href=\"index.php?l=$l\">$l_forum</a>";
					}
					echo"
					&gt;
					<a href=\"view_topic.php?topic_id=$topic_id&amp;l=$l\">$get_current_topic_title</a>
					&gt;
					<a href=\"edit_reply.php?topic_id=$topic_id&amp;reply_id=$reply_id&amp;l=$l\">$l_edit_reply</a>
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
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
				<!-- //Feedback -->


				<!-- TinyMCE -->
				<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
						'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
						'//www.tiny.cloud/css/codepen.min.css'
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
	
				<!-- Form -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_text\"]').focus();
					});
					</script>
			
					<form method=\"post\" action=\"edit_reply.php?topic_id=$topic_id&amp;reply_id=$reply_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
					
					<p>
					<textarea name=\"inp_text\" rows=\"5\" cols=\"50\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_reply_text</textarea>
					</p>
		
		

					<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					</form>
				<!-- //Form -->
				";
			}  // can edit
		}
		else{
			echo"
			<h1>
			<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
			Loading...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=forum/edit_reply.php?topic_id=$topic_id&amp;reply_id=$reply_id\">
			";
		}
	} // reply found
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>