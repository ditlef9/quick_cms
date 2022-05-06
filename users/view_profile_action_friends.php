<?php
/*- Variables ------------------------------------------------------------------------- */
if (isset($_GET['friend_id'])) {
	$friend_id = $_GET['friend_id'];
	$friend_id = stripslashes(strip_tags($friend_id));
}
else{
	$friend_id = "";
}

if(isset($can_view_profile)){
	if($mode == "delete_friend" OR $mode == "do_delete_friend"){
		// Make sure that I am logged in
		
		if(!(isset($_SESSION['user_id']))){
			echo"
			<table>
			 <tr> 
			  <td style=\"padding-right: 6px;vertical-align: top;\">
				<span>
				<img src=\"$root/users/_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
				</span>
			  </td>
			  <td>
				<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">Please log in</h1>
			  </td>
			 </tr>
			</table>
			<meta http-equiv=\"refresh\" content=\"1;url=login.php?l=$l&amp;referer=view_profile.php?action=friendsamp;user_id=$get_current_user_id\">
			";
		}
		else{

		// Get that friend
		$friend_id_mysql = quote_smart($link, $friend_id);

		$query = "SELECT friend_id, friend_user_id_a, friend_user_id_b, friend_user_alias_a, friend_user_alias_b, friend_user_image_a, friend_user_image_b, friend_text_a, friend_text_b, friend_datetime FROM $t_users_friends WHERE friend_id=$friend_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_friend_id, $get_friend_user_id_a, $get_friend_user_id_b, $get_friend_user_alias_a, $get_friend_user_alias_b, $get_friend_user_image_a, $get_friend_user_image_b, $get_friend_text_a, $get_friend_text_b, $get_friend_datetime) = $row;
	
		if($get_friend_id == ""){
			echo"
			<h1>Server error 404</h1>

			$l_friend_not_found

			<p>
			<a href=\"view_profile.php?action=friends&amp;user_id=$user_id&amp;l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"view_profile.php?action=friends&amp;user_id=$user_id&amp;l=$l\">$l_go_back</a>
			</p>
			";
		}
		else{
			// Check permission
			if($get_friend_user_id_a == "$my_user_id"){
				$friend_user_id 	= $get_friend_user_id_b;
				$friend_user_alias 	= $get_friend_user_alias_b;
				$friend_user_image 	= $get_friend_user_image_b;
				$friend_user_text 	= $get_friend_text_a;
			}
			elseif($get_friend_user_id_b == "$my_user_id"){
				$friend_user_id 	= $get_friend_user_id_a;
				$friend_user_alias 	= $get_friend_user_alias_a;
				$friend_user_image 	= $get_friend_user_image_a;
				$friend_user_text 	= $get_friend_text_b;
			}
			else{
				$friend_user_id 	= "";
				$friend_user_alias 	= "";
				$friend_user_image 	= "";
				$friend_user_text 	= "";
				echo"
				<h1>Server error 403</h1>

				$l_only_the_owner_administrator_and_moderator_can_delete_this_friend

				<p>
				<a href=\"view_profile.php?action=friends&amp;user_id=$user_id&amp;l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"view_profile.php?action=friends&amp;user_id=$user_id&amp;l=$l\">$l_go_back</a>
				</p>
				";
			}

			if($friend_user_id != ""){

				if($process == "1"){
					$result = mysqli_query($link, "DELETE FROM $t_users_friends WHERE friend_id=$friend_id_mysql");
					

					// Header
					$ft = "success";
					$fm = "changes_saved";
					$url = "view_profile.php?action=friends&user_id=$user_id&ft=$ft&fm=$fm&l=$l";

					echo"
					<table>
					 <tr> 
					  <td style=\"padding-right: 6px;vertical-align: top;\">
						<span>
						<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
						</span>
	 				  </td>
					  <td>
						<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">Loading</h1>
					  </td>
					 </tr>
					</table>
	
					<meta http-equiv=\"refresh\" content=\"1;url=$url\">
					";
					die;
					die;
				}





				$l_how_do_you_know_alias = str_replace("%alias%", $friend_user_alias, $l_how_do_you_know_alias);
				echo"
				<h2>$l_delete $friend_user_alias</h2>

				<p>$l_are_you_sure_you_want_to_delete_the_friend
				$l_the_action_can_not_be_undone</p>

				
				

				<p>
				<a href=\"view_profile.php?user_id=$user_id&amp;action=friends&amp;mode=do_delete_friend&amp;friend_id=$friend_id&amp;process=1\"><img src=\"_gfx//delete.png\" alt=\"delete.png\" /></a>
				<a href=\"view_profile.php?user_id=$user_id&amp;action=friends&amp;mode=do_delete_friend&amp;friend_id=$friend_id&amp;process=1\">$l_delete</a>
				&nbsp;
				<a href=\"view_profile.php?user_id=$user_id&amp;action=friends\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"view_profile.php?user_id=$user_id&amp;action=friends\">$l_go_back</a>
				</p>

				";
			}
		}
		} // logged in
	} // delete_friend
	elseif($mode == "edit_friend" OR $mode == "do_edit_friend"){		
		// Make sure that I am logged in
		
		if(!(isset($_SESSION['user_id']))){
			echo"
			<table>
			 <tr> 
			  <td style=\"padding-right: 6px;vertical-align: top;\">
				<span>
				<img src=\"$root/users/_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
				</span>
			  </td>
			  <td>
				<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">Please log in</h1>
			  </td>
			 </tr>
			</table>
			<meta http-equiv=\"refresh\" content=\"1;url=login.php?l=$l&amp;referer=view_profile.php?action=friendsamp;user_id=$get_current_user_id\">
			";
		}
		else{
		
		
		// Get that friend
		$friend_id_mysql = quote_smart($link, $friend_id);

		$query = "SELECT friend_id, friend_user_id_a, friend_user_id_b, friend_user_alias_a, friend_user_alias_b, friend_user_image_a, friend_user_image_b, friend_text_a, friend_text_b, friend_datetime FROM $t_users_friends WHERE friend_id=$friend_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_friend_id, $get_friend_user_id_a, $get_friend_user_id_b, $get_friend_user_alias_a, $get_friend_user_alias_b, $get_friend_user_image_a, $get_friend_user_image_b, $get_friend_text_a, $get_friend_text_b, $get_friend_datetime) = $row;
	
		if($get_friend_id == ""){
			echo"
			<h1>Server error 404</h1>

			$l_friend_not_found

			<p>
			<a href=\"view_profile.php?action=friends&amp;user_id=$user_id&amp;l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"view_profile.php?action=friends&amp;user_id=$user_id&amp;l=$l\">$l_go_back</a>
			</p>
			";
		}
		else{
			// Check permission
			if($get_friend_user_id_a == "$my_user_id"){
				$friend_user_id 	= $get_friend_user_id_b;
				$friend_user_alias 	= $get_friend_user_alias_b;
				$friend_user_image 	= $get_friend_user_image_b;
				$friend_user_text 	= $get_friend_text_a;
			}
			elseif($get_friend_user_id_b == "$my_user_id"){
				$friend_user_id 	= $get_friend_user_id_a;
				$friend_user_alias 	= $get_friend_user_alias_a;
				$friend_user_image 	= $get_friend_user_image_a;
				$friend_user_text 	= $get_friend_text_b;
			}
			else{
				$friend_user_id 	= "";
				$friend_user_alias 	= "";
				$friend_user_image 	= "";
				$friend_user_text 	= "";
				echo"
				<h1>Server error 403</h1>

				$l_only_the_owner_administrator_and_moderator_can_edit_this_friend

				<p>
				<a href=\"view_profile.php?action=friends&amp;user_id=$user_id&amp;l=$l\"><img src=\"_gfx//go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"view_profile.php?action=friends&amp;user_id=$user_id&amp;l=$l\">$l_go_back</a>
				</p>
				";
			}

			if($friend_user_id != ""){

				if($process == "1"){
					$inp_friend_text = $_POST['inp_friend_text'];
					$inp_friend_text = output_html($inp_friend_text);
					$inp_friend_text_mysql = quote_smart($link, $inp_friend_text);

					if($get_friend_user_id_a == "$my_user_id"){
						$result = mysqli_query($link, "UPDATE $t_users_friends SET friend_text_a=$inp_friend_text_mysql WHERE friend_id=$friend_id_mysql");
					}
					elseif($get_friend_user_id_b == "$my_user_id"){
						$result = mysqli_query($link, "UPDATE $t_users_friends SET friend_text_b=$inp_friend_text_mysql WHERE friend_id=$friend_id_mysql");
					}

					// Header
					$ft = "success";
					$fm = "changes_saved";
					echo"
					<table>
					 <tr> 
					  <td style=\"padding-right: 6px;vertical-align: top;\">
						<span>
						<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
						</span>
	 				  </td>
					  <td>
						<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">Loading</h1>
					  </td>
					 </tr>
					</table>
	
					<meta http-equiv=\"refresh\" content=\"1;url=view_profile.php?action=friends&user_id=$user_id&ft=$ft&fm=$fm&l=$l\">
					";
					die;
				}





				$l_how_do_you_know_alias = str_replace("%alias%", $friend_user_alias, $l_how_do_you_know_alias);
				echo"
				<h2>$l_edit</h2>

				
				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_friend_text\"]').focus();
					});
					</script>
				<!-- //Focus -->

				<form method=\"POST\" action=\"view_profile.php?user_id=$user_id&amp;action=friends&amp;mode=do_edit_friend&amp;friend_id=$friend_id&amp;process=1&amp;l=$l\" enctype=\"multipart/form-data\">


				<p>$l_how_do_you_know_alias:<br />
				<textarea name=\"inp_friend_text\" rows=\"4\" cols=\"60\">"; 
				$friend_user_text = str_replace("<br />", "\n", $friend_user_text);
				echo"$friend_user_text</textarea>
				</p>

				<p>
				<input type=\"submit\" value=\"$l_send\" class=\"btn\" />
				</p>

				<p>
				<a href=\"view_profile.php?user_id=$user_id&amp;action=friends\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"view_profile.php?user_id=$user_id&amp;action=friends\">$l_go_back</a>
				</p>

				";
			}
		}
		} // logged in
	} // edit_friend
	if($mode == ""){
		// Get profile of the user
		$current_user_id = $_GET['user_id'];
		$current_user_id = strip_tags(stripslashes($current_user_id));
		$current_user_id = output_html($current_user_id);
		$current_user_id_mysql = quote_smart($link, $current_user_id);


		echo"
		<h2>$l_friends</h2>
		";
		// Get many rows
		$query = "SELECT friend_id, friend_user_id_a, friend_user_id_b, friend_user_alias_a, friend_user_alias_b, friend_user_image_a, friend_user_image_b, friend_text_a, friend_text_b, friend_datetime FROM $t_users_friends WHERE friend_user_id_a=$current_user_id_mysql OR friend_user_id_b=$current_user_id_mysql";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_friend_id, $get_friend_user_id_a, $get_friend_user_id_b, $get_friend_user_alias_a, $get_friend_user_alias_b, $get_friend_user_image_a, $get_friend_user_image_b, $get_friend_text_a, $get_friend_text_b, $get_friend_datetime) = $row;

			if($get_friend_user_id_a == "$get_current_user_id"){
				$friend_user_id 	= $get_friend_user_id_b;
				$friend_user_alias 	= $get_friend_user_alias_b;
				$friend_user_image 	= $get_friend_user_image_b;
				$friend_user_text 	= $get_friend_text_a;
			}
			else{
				$friend_user_id 	= $get_friend_user_id_a;
				$friend_user_alias 	= $get_friend_user_alias_a;
				$friend_user_image 	= $get_friend_user_image_a;
				$friend_user_text 	= $get_friend_text_b;
			}
			echo"
			<div class=\"friends_list_image\">
				<p style=\"padding:0;margin: 8px 0px 8px 0px;\">
				";
				if($friend_user_image != ""){
					if(!(file_exists("$root/_uploads/users/images/$friend_user_id/$friend_user_image"))){
						echo"<div class=\"alert alert-danger\" role=\"alert\">
						<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
						<span>Photo not found on server..</span>
						</div>
						";
					}
					else{
						// Thumb
						$inp_new_x = 80;
						$inp_new_y = 80;
						$thumb = "user_" . $friend_user_image . "-" . $inp_new_x . "x" . $inp_new_y . "png";
						if(!(file_exists("$root/_cache/$thumb"))){
							resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$friend_user_id/$friend_user_image", "$root/_cache/$thumb");
						}

						echo"
						<a href=\"view_profile.php?user_id=$friend_user_id&amp;l=$l\"><img src=\"$root/_cache/$thumb\" alt=\"$friend_user_image\" class=\"image_rounded\" /></a>
						";
					}
				}
				else{
					echo"
					<a href=\"view_profile.php?user_id=$friend_user_id&amp;l=$l\"><img src=\"$root/_webdesign/images/avatar_blank_85.png\" style=\"position: relative; top: 0; left: 0;\" alt=\"Avatar\" class=\"image_rounded\" /></a>
					";
				}
				echo"
				</p>
			</div>
			<div class=\"friends_list_text\">
			
				<!-- Menu -->
					";
					if($get_current_user_id == "$my_user_id"){
						echo"
						<div style=\"float: right;margin: 4px 0px 0px 4px;\">
							<p>
							<a href=\"view_profile.php?action=friends&amp;user_id=$get_my_user_id&amp;mode=edit_friend&amp;friend_id=$get_friend_id&amp;l=$l\">$l_edit</a>
							&middot;
							<a href=\"view_profile.php?action=friends&amp;user_id=$get_my_user_id&amp;mode=delete_friend&amp;friend_id=$get_friend_id&amp;l=$l\">$l_delete</a>
							</p>
						</div>
						";
					}
					echo"
				<!-- //Menu -->
				<p style=\"margin-bottom:0;padding-bottom:0;\">
				<a href=\"view_profile.php?user_id=$friend_user_id&amp;l=$l\" style=\"font-weight:bold;color:#000;\">$friend_user_alias</a>
				</p>
				<p>$friend_user_text</p>
			</div>
			<div class=\"clear\"></div>
			";
		}
	} // mode == ""
				
}
?>