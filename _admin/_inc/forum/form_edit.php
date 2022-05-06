<?php
/**
*
* File: _admin/_inc/discuss/forms_edit.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_forum_titles			= $mysqlPrefixSav . "forum_titles";
$t_forum_subscriptions 		= $mysqlPrefixSav . "forum_subscriptions";
$t_forum_topics 		= $mysqlPrefixSav . "forum_topics";
$t_forum_topics_subscribers 	= $mysqlPrefixSav . "forum_topics_subscribers";
$t_forum_topics_read_by_user	= $mysqlPrefixSav . "forum_topics_read_by_user";
$t_forum_topics_read_by_ip	= $mysqlPrefixSav . "forum_topics_read_by_ip";
$t_forum_topics_tags 		= $mysqlPrefixSav . "forum_topics_tags";
$t_forum_replies		= $mysqlPrefixSav . "forum_replies";
$t_forum_replies_comments	= $mysqlPrefixSav . "forum_replies_comments";
$t_forum_forms			= $mysqlPrefixSav . "forum_forms";
$t_forum_forms_questions	= $mysqlPrefixSav . "forum_forms_questions";
$t_forum_top_users_yearly	= $mysqlPrefixSav . "forum_top_users_yearly";
$t_forum_top_users_monthly	= $mysqlPrefixSav . "forum_top_users_monthly";
$t_forum_top_users_all_time	= $mysqlPrefixSav . "forum_top_users_all_time";
$t_forum_tags_index		= $mysqlPrefixSav . "forum_tags_index";
$t_forum_tags_index_translation	= $mysqlPrefixSav . "forum_tags_index_translation";
$t_forum_tags_watch		= $mysqlPrefixSav . "forum_tags_watch";
$t_forum_tags_ignore		= $mysqlPrefixSav . "forum_tags_ignore";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['form_id'])){
	$form_id = $_GET['form_id'];
	$form_id = output_html($form_id);
}
else{
	$form_id = "";
}
$tabindex = 0;


// Get form
$form_id_mysql = quote_smart($link, $form_id);
$query = "SELECT form_id, form_title, form_language, form_introduction, form_insert_title_start, form_tags, form_created, form_updated FROM $t_forum_forms WHERE form_id=$form_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_form_id, $get_current_form_title, $get_current_form_language, $get_current_form_introduction, $get_current_form_insert_title_start, $get_current_form_tags, $get_current_form_created, $get_current_form_updated) = $row;

if($get_current_form_id == ""){
	echo"
	<h1>Error</h1>

	<p>
	Not found.
	</p>
	";

}
else{
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);
	
		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		$inp_introduction = $_POST['inp_introduction'];
		$inp_introduction = output_html($inp_introduction);
		$inp_introduction_mysql = quote_smart($link, $inp_introduction);

		$inp_insert_title_start = $_POST['inp_insert_title_start'];
		$inp_insert_title_start = output_html($inp_insert_title_start);
		$inp_insert_title_start_mysql = quote_smart($link, $inp_insert_title_start);

		$inp_tags = $_POST['inp_tags'];
		$inp_tags = output_html($inp_tags);
		$inp_tags_mysql = quote_smart($link, $inp_tags);
	
		$datetime = date("Y-m-d H:i:s");



		$result = mysqli_query($link, "UPDATE $t_forum_forms SET 
					 form_title=$inp_title_mysql,
					 form_language=$inp_language_mysql,
					 form_introduction=$inp_introduction_mysql,
					 form_insert_title_start=$inp_insert_title_start_mysql,
					 form_tags=$inp_tags_mysql, 
					 form_updated='$datetime'
			 WHERE form_id=$get_current_form_id");

		$url = "index.php?open=$open&page=$page&form_id=$get_current_form_id&editor_language=$editor_language&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>$get_current_form_title</h1>

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
			<li><a href=\"index.php?open=$open&amp;page=forms&amp;editor_language=$editor_language\">Forms</a></li>
			<li><a href=\"index.php?open=$open&amp;page=$page&amp;form_id=$get_current_form_id&amp;editor_language=$editor_language\">Edit form</a></li>
			<li><a href=\"index.php?open=$open&amp;page=form_edit_questions&amp;form_id=$get_current_form_id&amp;editor_language=$editor_language\">Questions</a></li>
			</ul>
		</div>
		<div class=\"clear\"></div>
	<!-- //Menu -->

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->
	<!-- Form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_text\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;form_id=$get_current_form_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		
		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_form_title\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
	
		<p><b>Language:</b><br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\""; if($get_current_form_language == "$get_language_active_iso_two"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>

		<p><b>Introduction:</b><br />
		<textarea name=\"inp_introduction\" rows=\"14\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		$get_current_form_introduction = str_replace("<br />", "\n", $get_current_form_introduction);
		echo"$get_current_form_introduction</textarea>
		</p>

		<p><b>Insert title start:</b><br />
		<input type=\"text\" name=\"inp_insert_title_start\" value=\"$get_current_form_insert_title_start\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Tags:</b><br />
		<input type=\"text\" name=\"inp_tags\" value=\"$get_current_form_tags\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /><br />
		<span class=\"smal\">Seperated by space</span>
		</p>

		
		
		<p><input type=\"submit\" value=\"Save changes\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->
	";
}
?>