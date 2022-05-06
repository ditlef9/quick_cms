<?php
/**
*
* File: _admin/_inc/dicuss/form_new.php
* Version 1.0.0
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

$tabindex = 0;

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

	mysqli_query($link, "INSERT INTO $t_forum_forms
	(form_id, form_language, form_title, form_introduction, form_insert_title_start, form_tags, form_created, form_updated) 
	VALUES 
	(NULL, $inp_language_mysql, $inp_title_mysql, $inp_introduction_mysql, $inp_insert_title_start_mysql, $inp_tags_mysql, '$datetime', '$datetime')")
	or die(mysqli_error($link));


	$url = "index.php?open=$open&editor_language=$editor_language&ft=success&fm=form_created";
	header("Location: $url");
	exit;
}
echo"
<h1>New form</h1>

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
		\$('[name=\"inp_title\"]').focus();
	});
	</script>
			
	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

	<p><b>Title:</b><br />
	<input type=\"text\" name=\"inp_title\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Language:</b><br />
	<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
	$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
		echo"	<option value=\"$get_language_active_iso_two\">$get_language_active_name</option>\n";
	}
	echo"
	</select>

	<p><b>Text:</b><br />
	<textarea name=\"inp_introduction\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
	</p>

	<p><b>Insert title start:</b><br />
	<input type=\"text\" name=\"inp_insert_title_start\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Tags:</b><br />
	<input type=\"text\" name=\"inp_tags\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /><br />
	<span class=\"smal\">Seperated by space</span>
	</p>


		
	<p><input type=\"submit\" value=\"Create\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	</form>
<!-- //Form -->
";

?>