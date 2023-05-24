<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/downloads_main_categories.php
* Version 1.0.0
* Date 12:57 24.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

echo"<span>Creating table $t_downloads_main_categories<br /></span>\n";

$mysqli->query("DROP TABLE IF EXISTS $t_downloads_main_categories");

if (!$mysqli -> query("CREATE TABLE $t_downloads_main_categories(
	main_category_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(main_category_id), 
	  main_category_title VARCHAR(200),
	  main_category_title_clean VARCHAR(200),
	  main_category_icon_path VARCHAR(100),
	  main_category_icon_file VARCHAR(100),
	  main_category_created DATETIME)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}



$datetime = date("Y-m-d H:i:s");
$mysqli->query("INSERT INTO $t_downloads_main_categories
(`main_category_id`, `main_category_title`, `main_category_title_clean`, `main_category_icon_path`, `main_category_icon_file`, `main_category_created`)
VALUES
(NULL, 'Downloads', 'downloads', '_uploads/downloads/_icons', '1.png', '$datetime')")  or die($mysqli->error);


?>