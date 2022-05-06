<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";


// Variables
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
	echo"
	<h1>Error</h1>
	
	<p>$l_user_profile_not_found</p>
	";
	die;
}


if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$current_user_id_mysql = quote_smart($link, $user_id);

	$query = "SELECT user_id, user_name, user_alias, user_language, user_rank FROM $t_users WHERE user_id=$current_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_user_id, $get_current_user_name, $get_current_user_alias, $get_current_user_language, $get_current_user_rank) = $row;

	if($get_current_user_id == ""){
		echo"
		<h1>Error</h1>
	
		<p>$l_user_profile_not_found</p>
		";
	
	}
	else{
		// Get my profile
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$query = "SELECT user_id, user_name, user_alias, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_rank) = $row;

		// Are we friends?
		// user_A = Lowest ID
		// user_B = Higher ID

		if($get_current_user_id > $my_user_id){
			$inp_friend_user_id_a_mysql = quote_smart($link, $my_user_id);
			$inp_friend_user_id_b_mysql = quote_smart($link, $get_current_user_id);
		}
		else{
			$inp_friend_user_id_a_mysql = quote_smart($link, $get_current_user_id);
			$inp_friend_user_id_b_mysql = quote_smart($link, $my_user_id);
		}
		$query = "SELECT friend_id FROM $t_users_friends WHERE friend_user_id_a=$inp_friend_user_id_a_mysql AND friend_user_id_b=$inp_friend_user_id_b_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_friend_id) = $row;
		
		if($get_friend_id == ""){
			// We are not friends

			// Did I alreaddy send a request?
			$query = "SELECT fr_id FROM $t_users_friends_requests WHERE fr_from_user_id=$my_user_id_mysql AND fr_to_user_id=$current_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_fr_id) = $row;
			

			if($get_fr_id == ""){
				// Friend request not sent
				
				// Variables
				$inp_fr_text = $_POST['inp_text'];
				$inp_fr_text = output_html($inp_fr_text);
				$inp_fr_text_mysql = quote_smart($link, $inp_fr_text);

				$inp_fr_datetime = date("y-m-d H:i:s");
				
				// Insert
				mysqli_query($link, "INSERT INTO $t_users_friends_requests
				(fr_id, fr_from_user_id, fr_to_user_id, fr_text, fr_datetime) 
				VALUES 
				(NULL, $my_user_id_mysql, $current_user_id_mysql, $inp_fr_text_mysql, '$inp_fr_datetime')")
				or die(mysqli_error($link));

				// Send email to tell about friend request
				$q = "SELECT es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$current_user_id_mysql AND es_type='friend_request'";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_es_friend_request) = $rowb;
				if($get_es_friend_request == "1"){
					$host = $_SERVER['HTTP_HOST'];
					$from = "$configFromEmailSav";
					$reply = "$configFromEmailSav";
					$site = ucfirst($host);

					// Subject
					$subject = str_replace("%alias%", $get_my_user_alias, $l_email_new_friend_request_from_alias);

					// Accept or decline link
					include("_scripts/functions/page_url.php");
					$accept_link = str_replace("page=view_profile_send_friend_request&user_id=$get_current_user_id&l=$l&process=1", "page=friend_requests", $pageURL);
					$subscriptions_link = str_replace("page=friend_requests", "page=edit_subscriptions", $accept_link);



					// Message
					$m_hello = str_replace("%alias%", $get_current_user_alias,  $l_hello_alias);
					$m_you_got_a = str_replace("%site%", $site,  $l_you_got_a_friend_request_on_site_from_alias);
					$m_you_got_a = str_replace("%alias%", $get_my_user_alias,  $m_you_got_a);
					$m_accept = $l_accept_or_decline_this_friend_request_by_following_this_url . "\n" . $accept_link;
					$m_subscriptions = $l_manage_your_email_subscriptions_at_this_url . "\n" . $subscriptions_link;
					
					$message = $m_hello . "\n\n" . $m_you_got_a . "\n\n" . $m_accept . "\n\n" . $m_subscriptions . "\n\n--\n$site";

					$headers = "From: $from" . "\r\n" .
					    "Reply-To: $reply" . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();

					mail($to, $subject, $message, $headers);
				}

				// Friend request sent
				$url = "index.php?page=view_profile&user_id=$get_current_user_id&l=$l&ft=warning&fm=friend_request_sent";
				header("Location: $url");
				exit;
				
				
			}
			else{
				// Friend request already sent
				$url = "index.php?page=view_profile&user_id=$get_current_user_id&l=$l&ft=warning&fm=friend_request_already_sent";
				header("Location: $url");
				exit;
				
			}

		}
		else{
			// We are friends
			$url = "index.php?page=view_profile&user_id=$get_current_user_id&l=$l&ft=warning&fm=you_are_already_friends";
			header("Location: $url");
			exit;
		}
		


	} // user found
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?page=login&amp;l=$l&refer=page=view_profileamp;user_id=$user_id\">
	";
}
?>