<?php
if(isset($_SESSION['admin_user_id'])){


	$t_admin_navigation		= $mysqlPrefixSav . "admin_navigation";
	$t_users 	 		= $mysqlPrefixSav . "users";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_admin_navigation") or die(mysqli_error());


$query = "SELECT * FROM $t_admin_navigation LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_admin_navigation(
	   navigation_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(navigation_id), 
	   navigation_url VARCHAR(200),
	   navigation_title VARCHAR(200),
	   navigation_icon VARCHAR(200),
	   navigation_icon_black_18 VARCHAR(200),
	   navigation_icon_black_24 VARCHAR(200),
	   navigation_icon_white_18 VARCHAR(200),
	   navigation_icon_white_24 VARCHAR(200),
	   navigation_icon_color_18 VARCHAR(200),
	   navigation_icon_color_24 VARCHAR(200),
	   navigation_user_id INT,
	   navigation_show INT,
	   navigation_weight INT)")
	or die(mysqli_error($link));


	// Insert general for all users
	$query = "SELECT user_id FROM $t_users WHERE user_rank='admin' OR user_rank='moderator' OR user_rank='editor'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_user_id) = $row;

		
		mysqli_query($link, "INSERT INTO $t_admin_navigation
		(navigation_id, navigation_url, navigation_title, navigation_icon, navigation_icon_black_18, navigation_icon_white_18, navigation_user_id, navigation_show, navigation_weight) 
		VALUES 
		(NULL, 'dashboard', 'Dashboard', 'dashboard', 'dashboard_black_18x18.png', 'dashboard_white_18x18.png', $get_user_id, 1, 1),
		(NULL, 'pages', 'Pages', 'description', 'pages_black_18x18.png', 'pages_white_18x18.png', $get_user_id, 1, 2),
		(NULL, 'media', 'Media', 'picture_in_picture', 'media_black_18x18.png', 'media_white_18x18.png', $get_user_id, 1, 3),
		(NULL, 'users', 'Users', 'supervisor_account', 'users_black_18x18.png', 'users_white_18x18.png', $get_user_id, 1, 4),
		(NULL, 'settings', 'Settings', 'settings', 'settings_black_18x18.png', 'settings_white_18x18.png', $get_user_id, 1, 5),
		(NULL, 'webdesign', 'Webdesign', 'view_module', 'webdesign_black_18x18.png', 'webdesign_white_18x18.png', $get_user_id, 1, 6)
		")
		or die(mysqli_error($link));
	}

}


}
?>