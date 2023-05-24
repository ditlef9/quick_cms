<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/downloads_main_categories.php
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

echo"<span>Creating table $t_downloads_main_categories_translations<br /></span>\n";

$mysqli->query("DROP TABLE IF EXISTS $t_downloads_main_categories_translations");

if (!$mysqli -> query("CREATE TABLE $t_downloads_main_categories_translations(
	main_category_translation_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(main_category_translation_id), 
	  main_category_id INT,
	  main_category_translation_language VARCHAR(20),
	  main_category_translation_value VARCHAR(200))")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}

?>