<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/users.php
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
$t_users_profile_headlines		= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations	= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields			= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations	= $mysqlPrefixSav . "users_profile_fields_translations";

	$t_users 	 		= $mysqlPrefixSav . "users";
	$t_users_profile 		= $mysqlPrefixSav . "users_profile";
	$t_users_friends 		= $mysqlPrefixSav . "users_friends";
	$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
	$t_users_profile		= $mysqlPrefixSav . "users_profile";
	$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
	$t_users_status 		= $mysqlPrefixSav . "users_status";
	$t_users_status_subscriptions	= $mysqlPrefixSav . "users_status_subscriptions";
	$t_users_status_replies 	= $mysqlPrefixSav . "users_status_replies";
	$t_users_status_replies_likes 	= $mysqlPrefixSav . "users_status_replies_likes";
	$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";
	$t_users_professional		= $mysqlPrefixSav . "users_professional";
	$t_users_cover_photos 		= $mysqlPrefixSav . "users_cover_photos";
	$t_users_email_subscriptions 	= $mysqlPrefixSav . "users_email_subscriptions";
	$t_users_notifications 		= $mysqlPrefixSav . "users_notifications";
	$t_users_moderator_of_the_week	= $mysqlPrefixSav . "users_moderator_of_the_week";

	$t_users_antispam_questions	= $mysqlPrefixSav . "users_antispam_questions";
	$t_users_antispam_answers	= $mysqlPrefixSav . "users_antispam_answers";

	$t_users_feeds_index		= $mysqlPrefixSav . "users_feeds_index";
	$t_users_api_sessions		= $mysqlPrefixSav . "users_api_sessions";

$tables_truncate_array = array(
			"$t_users_friends_requests");

$tables_backup_array = array(


			"$t_users_profile_headlines", 
			"$t_users_profile_headlines_translations", 
			"$t_users_profile_fields", 
			"$t_users_profile_fields_translations", 


			"$t_users", 
			"$t_users_profile", 
			"$t_users_friends", 
			"$t_users_friends_requests", 
			"$t_users_profile", 
			"$t_users_profile_photo", 
			"$t_users_status", 
			"$t_users_status_subscriptions", 
			"$t_users_status_replies", 
			"$t_users_status_replies_likes", 
			"$t_users_status_likes", 
			"$t_users_professional", 
			"$t_users_cover_photos", 
			"$t_users_email_subscriptions", 
			"$t_users_notifications", 
			
			"$t_users_antispam_questions", 
			"$t_users_antispam_answers"
			);

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array("_uploads/users");

?>