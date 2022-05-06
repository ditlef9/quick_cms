<?php
/**
*
* File: _admin/_liquidbase/db_scripts/webdesign/webdesign_share_buttons.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){

	/*- Tables ---------------------------------------------------------------------------- */


	$t_webdesign_share_buttons	= $mysqlPrefixSav . "webdesign_share_buttons";


	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_webdesign_share_buttons") or die(mysqli_error($link)); 



	echo"

	<!-- webdesign_share_buttons -->
	";

	$query = "SELECT * FROM $t_webdesign_share_buttons LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_webdesign_share_buttons: $row_cnt</p>
		";
		}
		else{

		mysqli_query($link, "CREATE TABLE $t_webdesign_share_buttons(
		  button_id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(button_id), 
		   button_title VARCHAR(200), 
		   button_url VARCHAR(200),
		   button_code_preload TEXT,
		   button_code_plugin TEXT,
		   button_language VARCHAR(200),
		   button_image_path VARCHAR(200),
		   button_image_18x18 VARCHAR(200),
		   button_updated DATETIME
		   )")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_webdesign_share_buttons(`button_id`, `button_title`, `button_url`, `button_language`, `button_image_path`, `button_image_18x18`)
					VALUES 
					(NULL, 'Facebook', 'https://www.facebook.com/sharer/sharer.php?u=%url%', 'no', '_webdesign/default_webdesign/images/share', 'facebook_18x18.png'),
					(NULL, 'Twitter', 'https://twitter.com/intent/tweet?text=%title% %url%&related=AddToAny,micropat', 'no', '_webdesign/default_webdesign/images/share', 'twitter_18x18.png'),
					(NULL, 'Reddit', 'https://www.reddit.com/submit?url=%url%&title=%title%', 'no', '_webdesign/default_webdesign/images/share', 'reddit_18x18.png'),
					(NULL, 'Facebook', 'https://www.facebook.com/sharer/sharer.php?u=%url%', 'en', '_webdesign/default_webdesign/images/share', 'facebook_18x18.png'),
					(NULL, 'Twitter', 'https://twitter.com/intent/tweet?text=%title% %url%&related=AddToAny,micropat', 'en', '_webdesign/default_webdesign/images/share', 'twitter_18x18.png'),
					(NULL, 'Reddit', 'https://www.reddit.com/submit?url=%url%&title=%title%', 'en', '_webdesign/default_webdesign/images/share', 'reddit_18x18.png')
					") or die(mysqli_error());
	}
	echo"
	<!-- //webdesign_share_buttons -->
	";
} // access
?>