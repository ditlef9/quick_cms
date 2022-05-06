<?php
if(isset($_SESSION['admin_user_id'])){
	$t_webdesign_footer_link_links = $mysqlPrefixSav . "webdesign_footer_link_links";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_webdesign_footer_link_links") or die(mysqli_error());

	mysqli_query($link, "CREATE TABLE $t_webdesign_footer_link_links(
	   link_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(link_id), 
 	   link_group_id INT,
 	   link_title VARCHAR(120),
 	   link_url VARCHAR(120),
 	   link_icon_path VARCHAR(120),
 	   link_icon_24x24 VARCHAR(120),
 	   link_icon_32x32 VARCHAR(120),
 	   link_internal_or_external VARCHAR(50),
 	   link_language VARCHAR(120),
 	   link_weight INT,
 	   link_created_datetime DATETIME,
 	   link_created_by_user_id INT,
 	   link_updated_datetime DATETIME,
 	   link_updated_by_user_id INT)")
	   or die(mysqli_error($link));
	
	$datetime = date("Y-m-d H:i:s");

	// En :: Legal
	mysqli_query($link, "INSERT INTO $t_webdesign_footer_link_links(link_id, link_group_id, link_title, link_url, link_icon_path, link_icon_24x24, link_icon_32x32, link_internal_or_external, link_language, link_weight, link_created_datetime, link_created_by_user_id)
					VALUES 
					(NULL, '1', 'Cookies Policy', 'legal/index.php?doc=cookies_policy&amp;l=en', '', '', '', 'internal', 'en', 1,  '$datetime', 1),
					(NULL, '1', 'Privacy Policy', 'legal/index.php?doc=privacy_policy&amp;l=en', '', '', '', 'internal', 'en', 1,  '$datetime', 1),
					(NULL, '1', 'Terms of Use', 'legal/index.php?doc=terms_of_use&amp;l=en', '', '', '', 'internal', 'en', 1,  '$datetime', 1)
					") or die(mysqli_error());

	// No :: Legal
	mysqli_query($link, "INSERT INTO $t_webdesign_footer_link_links(link_id, link_group_id, link_title, link_url, link_icon_path, link_icon_24x24, link_icon_32x32, link_internal_or_external, link_language, link_weight, link_created_datetime, link_created_by_user_id)
					VALUES 
					(NULL, '4', 'Retningslinjer for informasjonskapsler', 'legal/index.php?doc=cookies_policy&amp;l=no', '', '', '', 'internal', 'no', 1,  '$datetime', 1),
					(NULL, '4', 'Personvernregler', 'legal/index.php?doc=privacy_policy&amp;l=no', '', '', '', 'internal', 'no', 1,  '$datetime', 1),
					(NULL, '4', 'Vilk&aring;r for bruk', 'legal/index.php?doc=terms_of_use&amp;l=no', '', '', '', 'internal', 'no', 1,  '$datetime', 1)
					") or die(mysqli_error());


}
?>