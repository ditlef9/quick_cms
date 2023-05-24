<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/sub_categories.php
* Version 2
* Copyright (c) 2021-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

echo"<span>Creating table $t_downloads_sub_categories<br /></span>\n";

$mysqli->query("DROP TABLE IF EXISTS $t_downloads_sub_categories");

if (!$mysqli -> query("CREATE TABLE $t_downloads_sub_categories(
	sub_category_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(sub_category_id), 
	  sub_category_parent_id INT,
	  sub_category_title VARCHAR(200),
	  sub_category_title_clean VARCHAR(200),
	  sub_category_icon_path VARCHAR(100),
	  sub_category_icon_file VARCHAR(100),
	  sub_category_created DATETIME)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


?>