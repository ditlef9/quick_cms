<?php
/**
*
* File: _admin/_inc/discuss/default.php
* Version 15.00 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_forum_liquidbase		  = $mysqlPrefixSav . "forum_liquidbase";
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

/*- Variables ----------------------------------------------------------------------- */
$tabindex = 0;


/*- Check if setup is run ------------------------------------------------------------- */
$query = "SELECT * FROM $t_forum_titles LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Settings exists?
	if(!(file_exists("_data/forum.php"))){
		echo"
		<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Creating setup flat file</p></div>
		<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=settings&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
		";
	}

	echo"
	<h1>Forum</h1>

	<!-- Forum buttons -->";
		// Navigation
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='forum/index.php'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_navigation_id) = $row;
		if($get_navigation_id == ""){
			echo"
			<p>
			<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=forum&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
			</p>
			";
		}
	echo"
	<!-- //Forum buttons -->

	<div class=\"vertical\">
		<ul>
		";
		include("_inc/forum/menu.php");
		echo"	</ul>
	</div>
	";
} // setup ok
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>