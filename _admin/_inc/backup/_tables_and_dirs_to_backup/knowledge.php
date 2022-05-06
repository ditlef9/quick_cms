<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/knowledge.php
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
$t_knowledge_liquidbase	= $mysqlPrefixSav . "knowledge_liquidbase";


$t_knowledge_spaces_index			= $mysqlPrefixSav . "knowledge_spaces_index";
$t_knowledge_spaces_categories			= $mysqlPrefixSav . "knowledge_spaces_categories";
$t_knowledge_spaces_members			= $mysqlPrefixSav . "knowledge_spaces_members";
$t_knowledge_spaces_requested_memberships	= $mysqlPrefixSav . "knowledge_spaces_requested_memberships";
$t_knowledge_spaces_favorites			= $mysqlPrefixSav . "knowledge_spaces_favorites";


$t_knowledge_pages_index			= $mysqlPrefixSav . "knowledge_pages_index";
$t_knowledge_pages_edit_history			= $mysqlPrefixSav . "knowledge_pages_edit_history";
$t_knowledge_pages_tags	    			= $mysqlPrefixSav . "knowledge_pages_tags";
$t_knowledge_pages_comments			= $mysqlPrefixSav . "knowledge_pages_comments";
$t_knowledge_pages_favorites    		= $mysqlPrefixSav . "knowledge_pages_favorites";
$t_knowledge_pages_view_history 		= $mysqlPrefixSav . "knowledge_pages_view_history";
$t_knowledge_pages_media	 		= $mysqlPrefixSav . "knowledge_pages_media";
$t_knowledge_pages_diagrams	 		= $mysqlPrefixSav . "knowledge_pages_diagrams";

$t_knowledge_preselected_subscribe		= $mysqlPrefixSav . "knowledge_preselected_subscribe";


$t_knowledge_home_page_user_remember 		= $mysqlPrefixSav . "knowledge_home_page_user_remember";


$tables_truncate_array = array(
	"$t_knowledge_spaces_requested_memberships", 
	"$t_knowledge_pages_edit_history", 
	"$t_knowledge_pages_view_history", 
	"$t_knowledge_preselected_subscribe");

$tables_backup_array = array(
	"$t_knowledge_liquidbase", 
	"$t_knowledge_spaces_index", 
	"$t_knowledge_spaces_categories", 
	"$t_knowledge_spaces_members", 
	"$t_knowledge_spaces_requested_memberships", 
	"$t_knowledge_spaces_favorites", 
	"$t_knowledge_pages_index", 
	"$t_knowledge_pages_edit_history", 
	"$t_knowledge_pages_tags", 
	"$t_knowledge_pages_comments", 
	"$t_knowledge_pages_favorites", 
	"$t_knowledge_pages_view_history", 
	"$t_knowledge_pages_media", 
	"$t_knowledge_pages_diagrams", 
	"$t_knowledge_preselected_subscribe", 
	"$t_knowledge_home_page_user_remember");

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array("_uploads/knowledge");

?>