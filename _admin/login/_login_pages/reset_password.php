<?php
/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['process'])) {
	$process = $_GET['process'];
	$process = strip_tags(stripslashes($process));
}
else{
	$process = "";
}
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
}
if(isset($_GET['key'])) {
	$key = $_GET['key'];
	$key = strip_tags(stripslashes($key));
}
else{
	$key = "";
}
if($process == "1"){
	// Find user
	$user_id_mysql = quote_smart($link, $user_id);
	$query = "SELECT user_id, user_email, user_name, user_password, user_salt, user_security, user_language, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip FROM $t_users WHERE user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip) = $row;
	if($get_user_id == ""){
		$ft = "warning";
		$fm = "user_not_found";
		$url ="index.php?ft=$ft&fm=$fm&l=$l"; 
		header("Location: $url");
		exit;
	}

	// Check key
	$check_key = $get_user_id . $get_user_last_online . $get_user_last_ip;
	$check_key = md5($check_key);
		
	if($check_key == "$key"){
		// Log in user and move to change password site
					
		// Set security pin
		$security = rand(0,9999);

		// -> Logg brukeren inn
		$_SESSION['user_id'] = "$get_user_id";
		$_SESSION['security'] = "$security";
		$_SESSION['admin_user_id']  = "$get_user_id";
		$_SESSION['admin_security'] = "$security";
		$user_last_ip = $_SERVER['REMOTE_ADDR'];
		$user_last_ip = output_html($user_last_ip);
		$user_last_ip_mysql = quote_smart($link, $user_last_ip);

		// Update last logged in
		$inp_user_last_online = date("Y-m-d H:i:s");
		$result = mysqli_query($link, "UPDATE $t_users SET user_security='$security', user_last_online='$inp_user_last_online', user_last_ip=$user_last_ip_mysql WHERE user_id='$get_user_id'");



		// Move user
		$url = "../index.php?open=users&page=users_edit_user_password&action=edit_password&user_id=$get_user_id&l=$get_user_language&editor_language=$get_user_language"; 
		header("Location: $url");
		exit;
	}
	else{
		// Wrong key
		$ft = "warning";
		$fm = "wrong_key";
		$url ="index.php?ft=$ft&fm=$fm&l=$l"; 
		header("Location: $url");
		exit;
	}
		
}

?>