<?php
/**
*
* File: _admin/_inc/discuss/default.php
* Version 1.0.0
* Date 14:42 17.03.2018
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




echo"
<h1>Forms</h1>

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

<p>
<a href=\"index.php?open=$open&amp;page=form_new&amp;editor_language=$editor_language\" class=\"btn btn_default\">New</a>
</p>

<!-- List all sosial media -->
	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th scope=\"col\">
		<span>Title</span>
	   </th>
	   <th scope=\"col\">
		<span>Language</span>
	   </th>
	   <th scope=\"col\">
		<span>Updated</span>
	   </th>
	   <th scope=\"col\">
		<span>Actions</span>
	   </th>
	  </tr>
	</thead>
	<tbody>
	";
	
	$query = "SELECT form_id, form_language, form_title, form_introduction, form_tags, form_created, form_updated FROM $t_forum_forms";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_form_id, $get_form_language, $get_form_title, $get_form_introduction, $get_form_tags, $get_form_created, $get_form_updated) = $row;

		if(isset($odd) && $odd == false){
			$odd = true;
		}
		else{
			$odd = false;
		}

		echo"
		<tr>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>$get_form_title</span>
		  </td>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>$get_form_language</span>
		  </td>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>$get_form_updated</span>
		  </td>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>
			<a href=\"index.php?open=$open&amp;page=form_edit&amp;form_id=$get_form_id&amp;editor_language=$editor_language\">Edit</a>
			&middot;
			<a href=\"../forum/form_view.php?form_id=$get_form_id&amp;l=$get_form_language\">View</a>
			&middot;
			<a href=\"index.php?open=$open&amp;page=form_delete&amp;form_id=$get_form_id&amp;editor_language=$editor_language\">Delete</a>
			</span>
		 </td>
		</tr>
		";
	}
	echo"
	 </tbody>
	</table>
<!-- //List all sosial media -->

";
?>