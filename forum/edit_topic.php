<?php 
/**
*
* File: forum/edit_topic.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

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
if(isset($_GET['show'])) {
	$show = $_GET['show'];
	$show = strip_tags(stripslashes($show));
}
else{
	$show = "";
}


/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;

// Get topic
$topic_id_mysql = quote_smart($link, $topic_id);
$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_updated, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_updated, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip) = $row;

if($get_current_topic_id == ""){
	echo"<p>Topic post not found.</p>";
	
}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_topic_title - $l_forum";
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

		// Get topic owners subscription status
		$query = "SELECT topic_subscriber_id FROM $t_forum_topics_subscribers WHERE topic_id=$get_current_topic_id AND topic_subscriber_user_id=$get_current_topic_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_topic_subscriber_id) = $row;

		
		if($my_user_id == "$get_current_topic_user_id"){
			$can_edit = "true";
		}
		else{
			if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
				$can_edit = "true";
			}
		}
		if(isset($can_edit) && $can_edit == "true"){
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);
				if(empty($inp_title)){
					$url = "edit_topic.php?topic_id=$topic_id&l=$l&ft=error&fm=insert_a_title";
					header("Location: $url");
					exit;
				}

				// Text
				$inp_text = $_POST['inp_text'];
				if(empty($inp_text)){
					$url = "edit_topic.php?topic_id=$topic_id&l=$l&ft=error&fm=insert_some_text";
					header("Location: $url");
					exit;
				}


				// Updated
				$datetime = date("Y-m-d H:i:s");
				
				$year = substr($datetime, 0, 4);
				$month = substr($datetime, 5, 2);
				$day = substr($datetime, 8, 2);

				if($day < 10){
					$day = substr($day, 1, 1);
				}
		

				if($month == "01"){
					$month_saying = $l_january;
				}
				elseif($month == "02"){
					$month_saying = $l_february;
				}
				elseif($month == "03"){
					$month_saying = $l_march;
				}
				elseif($month == "04"){
					$month_saying = $l_april;
				}
				elseif($month == "05"){
					$month_saying = $l_may;
				}
				elseif($month == "06"){
					$month_saying = $l_june;
				}
				elseif($month == "07"){
					$month_saying = $l_july;
				}
				elseif($month == "08"){
					$month_saying = $l_august;
				}
				elseif($month == "09"){
					$month_saying = $l_september;
				}
				elseif($month == "10"){
					$month_saying = $l_october;
				}
				elseif($month == "11"){
					$month_saying = $l_november;
				}
				else{
					$month_saying = $l_december;
				}

				$inp_topic_updated_translated = "$day $month_saying $year";
				

				// Update
				$result = mysqli_query($link, "UPDATE $t_forum_topics SET 
						topic_title=$inp_title_mysql,
						topic_updated='$datetime',
						topic_updated_translated='$inp_topic_updated_translated'
				 WHERE topic_id=$topic_id_mysql");


				// Text
				if($forumWritingMethodSav == "what_you_see_is_what_you_get"){
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
			
					$sql = "UPDATE $t_forum_topics SET topic_text=? WHERE topic_id=$get_current_topic_id";
					$stmt = $link->prepare($sql);
					$stmt->bind_param("s", $inp_text);
					$stmt->execute();
					if ($stmt->errno) {
						echo "FAILURE!!! " . $stmt->error; die;
					}
				} // what you see is what you get
				else{
					// BBcode
					$inp_text = output_html($inp_text);

					$sql = "UPDATE $t_forum_topics SET topic_text=? WHERE topic_id=$get_current_topic_id";
					$stmt = $link->prepare($sql);
					$stmt->bind_param("s", $inp_text);
					$stmt->execute();
					if ($stmt->errno) {
						echo "FAILURE!!! " . $stmt->error; die;
					}
				}


				// Update user fields
				if($my_user_id == "$get_current_topic_user_id"){
					
					$inp_user_ip = $_SERVER['REMOTE_ADDR'];
					$inp_user_ip = output_html($inp_user_ip);
					$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);
	
					$inp_topic_user_alias_mysql = quote_smart($link, $get_my_user_alias);

					// Get my photo
					$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_photo_id, $get_photo_destination) = $row;

					$inp_topic_user_image_mysql = quote_smart($link, $get_photo_destination);


					$result = mysqli_query($link, "UPDATE $t_forum_topics SET 
						topic_user_alias=$inp_topic_user_alias_mysql,
						topic_user_image=$inp_topic_user_image_mysql,
						topic_user_ip=$inp_user_ip_mysql
					 WHERE topic_id=$topic_id_mysql");

				}


		
				// Tags
				// First delete old tags, so we can insert the new ones
				$r_delete = mysqli_query($link, "DELETE FROM $t_forum_tags_index $t_forum_topics_tags WHERE topic_id=$get_current_topic_id");


				$inp_tags = $_POST['inp_tags'];
				$inp_tags = output_html(strtolower($inp_tags));
				$inp_tags_array = explode(" ", $inp_tags);
				$size = sizeof($inp_tags_array);

				if($size > 0){
					for($x=0;$x<$size;$x++){
						$inp_tag_title = $inp_tags_array[$x];
						$inp_tag_title_mysql = quote_smart($link, $inp_tag_title);

						$inp_tag_clean = clean($inp_tags_array[$x]);
						$inp_tag_clean_mysql = quote_smart($link, $inp_tag_clean);

						// Check if I have this tag from before
						$query = "SELECT topic_tag_id FROM $t_forum_topics_tags WHERE topic_id=$get_current_topic_id AND topic_tag_clean=$inp_tag_clean_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_topic_tag_id) = $row;
						if($get_topic_tag_id == ""){
							// Insert
							mysqli_query($link, "INSERT INTO $t_forum_topics_tags 
							(topic_tag_id, topic_id, topic_tag_title, topic_tag_clean) 
							VALUES 
							(NULL, $get_current_topic_id, $inp_tag_title_mysql, $inp_tag_clean_mysql)")
							or die(mysqli_error($link));
						}

						// Tag index
						$datetime = date("Y-m-d H:i:s");
						$day = date("d");
						$week = date("W");

						$query = "SELECT tag_id, tag_topics_total_counter, tag_topics_today_counter, tag_topics_today_day, tag_topics_this_week_counter, tag_topics_this_week_week FROM $t_forum_tags_index WHERE tag_title_clean=$inp_tag_clean_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_tag_id, $get_tag_topics_total_counter, $get_tag_topics_today_counter, $get_tag_topics_today_day, $get_tag_topics_this_week_counter, $get_tag_topics_this_week_week) = $row;
						if($get_tag_id == ""){
							// Insert
							mysqli_query($link, "INSERT INTO $t_forum_tags_index 
							(tag_id, tag_title, tag_title_clean, tag_introduction, tag_description, tag_created, tag_updated, tag_topics_total_counter, tag_topics_today_counter, tag_topics_today_day, tag_topics_this_week_counter, tag_topics_this_week_week, tag_is_official, tag_icon_path) 
							VALUES 
							(NULL, $inp_tag_title_mysql, $inp_tag_clean_mysql, '', '', '$datetime', '$datetime', '1', '1', '$day', '1', '$week', -1, '_uploads/forum/tags_icons')")
							or die(mysqli_error($link));
						}
						else{
							$inp_tag_topics_total_counter = $get_tag_topics_total_counter+1;

							if($get_tag_topics_today_day == "$day"){
								$inp_tag_topics_today_counter = $get_tag_topics_today_counter+1;
							}
							else{
								$inp_tag_topics_today_counter = 0;
							}

							if($get_tag_topics_this_week_week == "$week"){
								$inp_tag_topics_this_week_counter = $get_tag_topics_this_week_counter+1;
							}
							else{
								$inp_tag_topics_this_week_counter = $get_tag_topics_this_week_counter+1;
							}

							$r_update = mysqli_query($link, "UPDATE $t_forum_tags_index SET tag_topics_total_counter=$inp_tag_topics_total_counter, tag_topics_today_counter=$inp_tag_topics_today_counter, tag_topics_today_day=$day, tag_topics_this_week_counter=$inp_tag_topics_this_week_counter, tag_topics_this_week_week=$week WHERE tag_id=$get_tag_id");

						}
				
					}	
				}

				// Search engine
				$reference_name_mysql = quote_smart($link, "topic_id");
				$reference_id_mysql = quote_smart($link, "$get_current_topic_id");
				$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='forum' AND index_reference_name=$reference_name_mysql AND index_reference_id=$reference_id_mysql";
				$result_exists = mysqli_query($link, $query_exists);
				$row_exists = mysqli_fetch_row($result_exists);
				list($get_index_id) = $row_exists;
				if($get_index_id != ""){

					$inp_index_title = "$inp_title | $get_current_title_value";
					$inp_index_title_mysql = quote_smart($link, $inp_index_title);

					$inp_index_short_description = substr($inp_text, 0, 200);
					$inp_index_short_description = output_html($inp_index_short_description);
					$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

					$inp_index_keywords = "$inp_tags";
					$inp_index_keywords_mysql = quote_smart($link, $inp_index_keywords);

					$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
									index_title=$inp_index_title_mysql,
									index_short_description=$inp_index_short_description_mysql,
									index_keywords=$inp_index_keywords_mysql
									 WHERE index_id=$get_index_id") or die(mysqli_error($link));
				}

	
				$url = "view_topic.php?topic_id=$topic_id&l=$l&ft=success&fm=changes_saved";
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
				<a href=\"edit_topic.php?topic_id=$topic_id&amp;l=$l\">$l_edit_topic</a>
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


			<!-- Form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"edit_topic.php?topic_id=$topic_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>$l_title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_current_topic_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>$l_tags:</b><br />
				<input type=\"text\" name=\"inp_tags\" value=\"";
				$query_t = "SELECT topic_tag_id, topic_tag_title, topic_tag_clean FROM $t_forum_topics_tags WHERE topic_id=$get_current_topic_id";
				$result_t = mysqli_query($link, $query_t);
				while($row_t = mysqli_fetch_row($result_t)) {
					list($get_topic_tag_id, $get_topic_tag_title, $get_topic_tag_clean) = $row_t;
					echo"$get_topic_tag_title ";
				}
				echo"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
		
				";
				if($forumWritingMethodSav == "what_you_see_is_what_you_get"){
					echo"
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
			height: 500,
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
	
					<p><b>$l_post:</b><br />
					<textarea name=\"inp_text\" rows=\"5\" cols=\"50\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_topic_text</textarea>
					</p>
					";
				}
		} // what_you_see_is_what_you_get
		else{
			echo"
			<p><b>$l_post:</b><br />
			<input type=\"button\" value=\"b\" onclick=\"formatText ('[b][/b]');\" class=\"btn_bbcode\" style=\"font-weight: bold;\" /> 
			<input type=\"button\" value=\"i\" onclick=\"formatText ('[i][/i]');\" class=\"btn_bbcode\" style=\"font-style: italic;\" /> 
			<input type=\"button\" value=\"u\" onclick=\"formatText ('[u][/u]');\" class=\"btn_bbcode\" style=\"text-decoration: underline;\" /> 
			<input type=\"button\" value=\"URL\" onclick=\"formatText ('[url][/url]');\" class=\"btn_bbcode\" /> 
			<input type=\"button\" value=\"Code\" onclick=\"formatText ('[code][/code]');\" class=\"btn_bbcode\" /> 
			<input type=\"button\" value=\"Image\" onclick=\"formatText ('[img][/img]');\" class=\"btn_bbcode\" /> 
			<br />
			<textarea name=\"inp_text\" id=\"inp_text\" rows=\"5\" cols=\"50\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_topic_text</textarea>
			
			</p>

					<!-- Javascript insert bb code -->
						<script type=\"text/javascript\"> 
						function formatText(tag) {
							// BBCode
							var Field = document.getElementById('inp_text');
							var val = Field.value;
							var selected_txt = val.substring(Field.selectionStart, Field.selectionEnd);
							var before_txt = val.substring(0, Field.selectionStart);
							var after_txt = val.substring(Field.selectionEnd, val.length);
							Field.value += tag;


							// Focus
							document.getElementById(\"inp_text\").focus();
						}
						</script>
					<!-- //Javascript insert bb code -->
			
					";
				} // BBCode
				echo"
				<p>
				<input type=\"checkbox\" name=\"inp_notify_me_when_a_reply_is_posted\" "; if($get_topic_subscriber_id != ""){ echo" checked=\"checked\""; } echo"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> $l_notify_me_when_a_reply_is_posted
				</p>

				<p><input type=\"submit\" value=\"$l_publish\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				</form>
			<!-- //Form -->
			";
		}
		else{
			echo"<p>You can not edit this topic.</p>";
		}
	}
	else{
		echo"
		<p>Not logged in.</p>
		";
	}
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>