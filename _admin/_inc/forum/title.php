<?php
/**
*
* File: _admin/_inc/discuss/title.php
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


/*- Tables ---------------------------------------------------------------------------- */

$t_forum_titles		= $mysqlPrefixSav . "forum_titles";
$t_forum_subscriptions 	= $mysqlPrefixSav . "forum_subscriptions";

$t_forum_topics 		= $mysqlPrefixSav . "forum_topics";
$t_forum_topics_subscribers 	= $mysqlPrefixSav . "forum_topics_subscribers";
$t_forum_topics_read_by_user	= $mysqlPrefixSav . "forum_topics_read_by_user";
$t_forum_topics_read_by_ip	= $mysqlPrefixSav . "forum_topics_read_by_ip";
$t_forum_topics_tags 		= $mysqlPrefixSav . "forum_topics_tags";
$t_forum_replies		= $mysqlPrefixSav . "forum_replies";
$t_forum_replies_comments	= $mysqlPrefixSav . "forum_replies_comments";

$t_forum_forms		= $mysqlPrefixSav . "forum_forms";
$t_forum_forms_questions	= $mysqlPrefixSav . "forum_forms_questions";

$t_forum_top_users_yearly	= $mysqlPrefixSav . "forum_top_users_yearly";
$t_forum_top_users_monthly	= $mysqlPrefixSav . "forum_top_users_monthly";
$t_forum_top_users_all_time	= $mysqlPrefixSav . "forum_top_users_all_time";

$t_forum_tags_index			= $mysqlPrefixSav . "forum_tags_index";
$t_forum_tags_index_translation	= $mysqlPrefixSav . "forum_tags_index_translation";
$t_forum_tags_watch			= $mysqlPrefixSav . "forum_tags_watch";
$t_forum_tags_ignore			= $mysqlPrefixSav . "forum_tags_ignore";
/*- Functions ----------------------------------------------------------------------- */


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;



if($process == "1"){

	$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;

		$inp_value = $_POST["inp_value_$get_language_active_id"];
		$inp_value = output_html($inp_value);
		$inp_value_mysql = quote_smart($link, $inp_value);
		
		$result_update = mysqli_query($link, "UPDATE $t_forum_titles SET 
					 title_value=$inp_value_mysql
			 WHERE title_language='$get_language_active_iso_two'");

	} // while
	$url = "index.php?open=$open&page=title&editor_language=$editor_language&ft=success&fm=changes_saved";
	header("Location: $url");
	exit;
} // process
else{
	echo"
	<h1>Title</h1>


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



	<!-- Titles -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Language</span>
		   </th>
	 	  <th scope=\"col\">
			<span>Title</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
	
		";
		
		$x = 0;
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;



			// Get value
			$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language='$get_language_active_iso_two'";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_title_id, $get_title_language, $get_title_value) = $row_t;
			if($get_title_id == ""){
				mysqli_query($link, "INSERT INTO $t_forum_titles
				(title_id, title_language, title_value) 
				VALUES 
				(NULL, '$get_language_active_iso_two', 'Forum')")
				or die(mysqli_error($link));
			}

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}
			echo"
			<tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			";
			if($x == "0"){
				echo"
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_value_$get_language_active_id\"]').focus();
				});
				</script>
				";
			}
			echo"
				<span>$get_language_active_name</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span><input type=\"text\" name=\"inp_value_$get_language_active_id\" value=\"$get_title_value\" size=\"40\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></span>
			 </td>
			</tr>
			";
			$x++;
		}
		echo"
		 </tbody>
		</table>
		<p><input type=\"submit\" value=\"Save changes\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->
	";
}
?>