<?php
if(isset($_SESSION['admin_user_id'])){
	// Navigation
	$t_pages_navigation = $mysqlPrefixSav . "pages_navigation";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_pages_navigation") or die(mysqli_error());

	mysqli_query($link, "CREATE TABLE $t_pages_navigation(
	   navigation_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(navigation_id), 
 	   navigation_parent_id INT,
 	   navigation_title VARCHAR(120),
 	   navigation_title_clean VARCHAR(120),
 	   navigation_url VARCHAR(120),
 	   navigation_url_path VARCHAR(120),
 	   navigation_url_path_md5 VARCHAR(120),
 	   navigation_url_query VARCHAR(120),
 	   navigation_language VARCHAR(120),
 	   navigation_internal_or_external VARCHAR(50),
 	   navigation_icon_path VARCHAR(200),
 	   navigation_icon_char_inactive VARCHAR(200),
 	   navigation_icon_char_hover VARCHAR(200),
 	   navigation_icon_char_active VARCHAR(200),
 	   navigation_icon_16x16_inactive VARCHAR(200),
 	   navigation_icon_16x16_hover VARCHAR(200),
 	   navigation_icon_16x16_active VARCHAR(200),
 	   navigation_icon_18x18_inactive VARCHAR(200),
 	   navigation_icon_18x18_hover VARCHAR(200),
 	   navigation_icon_18x18_active VARCHAR(200),
 	   navigation_icon_24x24_inactive VARCHAR(200),
 	   navigation_icon_24x24_hover VARCHAR(200),
 	   navigation_icon_24x24_active VARCHAR(200),
 	   navigation_weight INT,
 	   navigation_created_datetime DATETIME,
 	   navigation_created_by_user_id INT,
 	   navigation_updated_datetime DATETIME,
 	   navigation_updated_by_user_id INT)")
	   or die(mysqli_error($link));

}
?>