<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/forum.php
* Version 18:17 13.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ------------------------------------------------------------------------ */
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



$tables_truncate_array = array(
			"$t_forum_topics_subscribers", 
			"$t_forum_topics_read_by_user", 
			"$t_forum_topics_read_by_ip",
			"$t_forum_tags_watch", 
			"$t_forum_tags_ignore"
			);

$tables_backup_array = array(
			"$t_forum_titles", 
			"$t_forum_subscriptions", 

			"$t_forum_topics", 
			"$t_forum_topics_subscribers", 
			"$t_forum_topics_read_by_user", 
			"$t_forum_topics_read_by_ip", 
			"$t_forum_topics_tags", 
			"$t_forum_replies", 
			"$t_forum_replies_comments", 

			"$t_forum_forms", 
			"$t_forum_forms_questions", 

			"$t_forum_top_users_yearly", 
			"$t_forum_top_users_monthly", 
			"$t_forum_top_users_all_time", 

			"$t_forum_tags_index", 
			"$t_forum_tags_index_translation", 
			"$t_forum_tags_watch", 
			"$t_forum_tags_ignore"
			);

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array("_uploads/forum");

?>