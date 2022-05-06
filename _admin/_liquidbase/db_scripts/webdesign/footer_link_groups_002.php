<?php
if(isset($_SESSION['admin_user_id'])){
	$t_webdesign_footer_link_groups = $mysqlPrefixSav . "webdesign_footer_link_groups";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_webdesign_footer_link_groups") or die(mysqli_error());

	mysqli_query($link, "CREATE TABLE $t_webdesign_footer_link_groups(
	   group_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(group_id), 
 	   group_title VARCHAR(120),
 	   group_show_title VARCHAR(120),
 	   group_type VARCHAR(50),
 	   group_language VARCHAR(120),
 	   group_weight INT,
 	   group_number_of_links INT,
 	   group_created DATE,
 	   group_created_by_user_id INT,
 	   group_updated DATE,
 	   group_updated_by_user_id INT)")
	   or die(mysqli_error($link));
	
	$datetime = date("Y-m-d H:i:s");

	// En
	mysqli_query($link, "INSERT INTO $t_webdesign_footer_link_groups(group_id, group_title, group_type, group_show_title, group_language, group_weight, group_number_of_links, group_created, group_created_by_user_id)
					VALUES 
					(NULL, 'Legal', 'text_links', '0', 'en', '1', 0, '$datetime', 1),
					(NULL, 'Social media', 'icons', '0', 'en', '2', 0, '$datetime', 1),
					(NULL, 'More', 'text_links', '1', 'en', '3', 0, '$datetime', 1)
					") or die(mysqli_error());

	// No
	mysqli_query($link, "INSERT INTO $t_webdesign_footer_link_groups(group_id, group_title, group_type, group_show_title, group_language, group_weight, group_number_of_links, group_created, group_created_by_user_id)
					VALUES 
					(NULL, 'Juridisk', 'text_links', '0', 'no', '1', 0, '$datetime', 1),
					(NULL, 'Sosiale medier', 'icons', '0', 'no', '2', 0, '$datetime', 1),
					(NULL, 'Mer', 'text_links', '1', 'no', '3', 0, '$datetime', 1)
					") or die(mysqli_error());

}
?>