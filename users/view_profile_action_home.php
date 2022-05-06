<?php
/*- Content --------------------------------------------------------------------------- */



if(isset($can_view_profile)){
	// Feedback
	echo"

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "x"){
				$fm = "$l_x";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->



	<!-- Add a status -->
		";
		// My profile
		if(isset($_SESSION['user_id'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;

			// My image
			$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_my_photo_id, $get_my_photo_destination) = $rowb;

			$inp_new_x = 40; // 950
			$inp_new_y = 40; // 640
			if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){
				$thumb_full_path = "$root/_cache/user_" . $get_my_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
				if(!(file_exists("$thumb_full_path"))){
					resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination", "$thumb_full_path");
				}
			}
			else{
				$thumb_full_path = "_gfx/avatar_blank_40.png";
			}


			// IP block
			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);
			$my_ip_mysql = quote_smart($link, $my_ip);
			
			$time = time();

			$ip_block = "false";

			$q = "SELECT status_id, status_time FROM $t_users_status WHERE status_created_by_ip=$my_ip_mysql ORDER BY status_id DESC LIMIT 0,1";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_status_id, $get_status_time) = $rowb;	

			if($get_status_id != ""){
			
				$time_since_last_status = $time-$get_status_time;
				$remaining = 60-$time_since_last_status;
				if($time_since_last_status < 60){
					$ip_block = "true";
				}
			}
			if($ip_block == "false"){
				echo"
				<h2 style=\"padding-bottom:0;margin-bottom:0\">$l_say_hello_to $get_current_user_alias</h2>
				<form method=\"post\" action=\"status_new.php?user_id=$get_current_user_id&amp;l=$l&amp;process=1\" />
				<table style=\"width: 100%;\">
				 <tr>
				  <td style=\"width: 45px;vertical-align: top;\">
					<p><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></p>
				  </td>
				  <td style=\"vertical-align: top;\">
					<p><textarea name=\"inp_text\" rows=\"3\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea><br />
					<input type=\"submit\" value=\"$l_send\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				 </tr>
				</table>
				";
			} // ip block ok
		}
		else{
			echo"
			<p><a href=\"$root/users/login.php?l=$l&amp;referer=$root/users/status_new.php?user_id=$get_current_user_id\" class=\"btn_default\">$l_add_comment</a></p>
			";
		} // not logged in
	echo"
	<!-- //Add a status -->

	
	<!-- View comments -->";

		// My user
		if(isset($_SESSION['user_id'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id_mysql = quote_smart($link, $my_user_id);
		}

		$query = "SELECT status_id, status_user_id, status_created_by_user_id, status_created_by_user_alias, status_created_by_user_image, status_created_by_ip, status_text, status_photo, status_datetime, status_datetime_print, status_time, status_language, status_likes, status_comments, status_reported, status_reported_checked, status_reported_reason, status_seen FROM $t_users_status WHERE status_user_id=$get_current_user_id ORDER BY status_id DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_status_id, $get_status_user_id, $get_status_created_by_user_id, $get_status_created_by_user_alias, $get_status_created_by_user_image, $get_status_created_by_ip, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_datetime_print, $get_status_time, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked, $get_status_reported_reason, $get_status_seen) = $row;

			// Thumb
			$inp_new_x = 40; // 950
			$inp_new_y = 40; // 640
			if(file_exists("$root/_uploads/users/images/$get_status_created_by_user_id/$get_status_created_by_user_image") && $get_status_created_by_user_image != ""){
				$thumb_full_path = "$root/_cache/user_" . $get_status_created_by_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
				if(!(file_exists("$thumb_full_path"))){
					resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_status_created_by_user_id/$get_status_created_by_user_image", "$thumb_full_path");
				}
			}
			else{
				$thumb_full_path = "_gfx/avatar_blank_40.png";
			}

			// Did I like the status?
			$i_have_liked_this_status = "0";
			if(isset($my_user_id)){
				$q_check_like = "SELECT like_id, like_status_id, like_user_id, like_user_alias FROM $t_users_status_likes WHERE like_status_id=$get_status_id AND like_user_id=$my_user_id_mysql";
				$r_check_like = mysqli_query($link, $q_check_like);
				$row_check_like = mysqli_fetch_row($r_check_like);
				list($get_like_id, $get_like_status_id, $get_like_user_id, $get_like_user_alias) = $row_check_like;

				if($get_like_id != ""){
					$i_have_liked_this_status = "1";
				}
			}


			// Did I have e-mail subscriion for this status?
			if(isset($my_user_id)){
				$q_check_like = "SELECT subscription_id, subscription_user_email_seen FROM $t_users_status_subscriptions WHERE subscription_status_id=$get_status_id AND subscription_user_id=$my_user_id_mysql";
				$r_check_like = mysqli_query($link, $q_check_like);
				$row_check_like = mysqli_fetch_row($r_check_like);
				list($get_subscription_id, $get_subscription_user_email_seen) = $row_check_like;

				if($get_subscription_id != "" && $get_subscription_user_email_seen == "0"){

					echo"<div class=\"info\"><p>$l_new_replies_below</p></div>\n";

					// Update it
					mysqli_query($link, "UPDATE $t_users_status_subscriptions SET 
					subscription_user_email_seen='1' 
					WHERE subscription_id=$get_subscription_id")
					or die(mysqli_error($link));
				}
			}


			echo"
			<a id=\"status$get_status_id\"></a>
			<table style=\"width: 100%;\">
			 <tr>
			  <td style=\"width: 45px;vertical-align: top;\">
				<p><a href=\"view_profile.php?user_id=$get_status_created_by_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a></p>
			  </td>
			  <td class=\"status\" style=\"vertical-align: top;\">
				<!-- Status author, date, and more -->
					<table>
					 <tr>
					  <td style=\"vertical-align:top;\">
						<p style=\"padding-bottom: 0;margin-bottom: 0;\">
						<a href=\"view_profile.php?user_id=$get_status_created_by_user_id&amp;l=$l\" style=\"color: #000\"><b>$get_status_created_by_user_alias</b></a>
						<span class=\"smal_grey\">$get_status_datetime_print</span>
						</p>
					  </td>
					  <td>
						<!-- Status more -->
							<p style=\"padding-bottom: 0;margin-bottom: 0;\">
							<a href=\"#status$get_status_id\" id=\"ic_more_horiz_link_status_$get_status_id\"><img src=\"_gfx/ic_more_horiz.png\" alt=\"ic_more_horiz.png\" /></a>
							</p>
							<script>
							\$(document).ready(function(){
								\$(\"#ic_more_horiz_link_status_$get_status_id\").click(function () {
									\$(\"#ic_more_horiz_div_status_$get_status_id\").toggle();
								});
							});
							</script>

							<div id=\"ic_more_horiz_div_status_$get_status_id\" style=\"display: none;\">
								<div class=\"ic_more_horiz_div_status\">
									<ul>";
									if($get_status_reported == "0"){
										echo"<li><a href=\"status_report.php?status_id=$get_status_id&amp;l=$l\">$l_report</a></li>";
									}
									else{
										echo"<li><a href=\"status_report.php?status_id=$get_status_id&amp;l=$l\">($l_already_reported)</a></li>";
									}
										
									if(isset($my_user_id) && $get_status_created_by_user_id == "$my_user_id"){

										echo"
										<li><a href=\"status_edit.php?status_id=$get_status_id&amp;l=$l\">$l_edit</a></li>
										<li><a href=\"status_delete.php?status_id=$get_status_id&amp;l=$l\">$l_delete</a></li>";

									}
										echo"
									</ul>
								</div>
							</div>
						<!-- //Status more -->
					  </td>
					 </tr>
					</table>
				<!-- //Status author, date, and more -->
				

				<p style=\"padding-top:0;margin-top:0;\">
				$get_status_text
				</p>


				<!-- Who has liked this status -->";
					$x = 0;
					$dont_put_comma_at = $get_status_likes - 1;
					$put_and_at = $get_status_likes - 2;
					$query_l = "SELECT like_id, like_status_id, like_user_id, like_user_alias FROM $t_users_status_likes WHERE like_status_id=$get_status_id";
					$result_l = mysqli_query($link, $query_l);
					while($row_l = mysqli_fetch_row($result_l)) {
						list($get_like_id, $get_like_status_id, $get_like_user_id, $get_like_user_alias) = $row_l;

						// status_likes
						echo"<a href=\"view_profile.php?user_id=$get_like_user_id&amp;l=$l\">$get_like_user_alias</a>";

						if($put_and_at == "$x"){
							echo" $l_and_lowercase ";
						}
						else{
							if($dont_put_comma_at == "$x"){
							}
							else{
								echo", ";
							}
						}

						$x++;
					}
					if($get_status_likes != "0"){
						echo" $l_likes_this_lowercase";
					}
				
					// Check that likes is correct
					if($x != "$get_status_likes"){
						$result_update_likes = mysqli_query($link, "UPDATE $t_users_status SET status_likes=$x WHERE status_id=$get_status_id") or die(mysqli_error($link));
					}
				echo"
				<!-- Who has liked this status -->

				<div class=\"status_actions\">
					<table>
					 <tr>";

					if($i_have_liked_this_status == "0"){
						echo"
						  <td style=\"width: 20px;vertical-algin:top;\">
						<p>
						<a href=\"status_like.php?status_id=$get_status_id&amp;process=1&amp;l=$l\"><img src=\"_gfx/thumbs_up.png\" alt=\"thumbs_up.png\" /></a>
						</p>
						  </td>
						  <td style=\"vertical-algin:top;padding: 0px 10px 0px 0px;\">
							<p>
							<a href=\"status_like.php?status_id=$get_status_id&amp;process=1&amp;l=$l\">$l_like</a>
							</p>
						  </td>
						";
					}
					echo"
					  <td style=\"width: 20px;vertical-algin:top;padding: 4px 0px 0px 0px;\">
						<p>
						<a href=\"status_reply.php?status_id=$get_status_id&amp;l=$l\"><img src=\"_gfx/text-x-generic-reply.png\" alt=\"text-x-generic-reply.png\" /></a>
						</p>
					  </td>
					  <td style=\"vertical-algin:top;\">
						<p>
						<a href=\"status_reply.php?status_id=$get_status_id&amp;l=$l\">$l_reply</a>
						</p>
					  </td>
					 </tr>
					</table>
				</div>

			  </td>
			 </tr>
			</table>
			
			<!-- Replies -->
				";
				$query_replies = "SELECT reply_id, reply_status_id, reply_parent_id, reply_created_by_user_id, reply_created_by_user_alias, reply_created_by_user_image, reply_created_by_ip, reply_text, reply_likes, reply_datetime, reply_datetime_print, reply_time, reply_reported, reply_reported_checked, reply_reported_reason, reply_seen FROM $t_users_status_replies WHERE reply_status_id=$get_status_id ORDER BY reply_id ASC";
				$result_replies = mysqli_query($link, $query_replies);
				while($row_replies = mysqli_fetch_row($result_replies)) {
					list($get_reply_id, $get_reply_status_id, $get_reply_parent_id, $get_reply_created_by_user_id, $get_reply_created_by_user_alias, $get_reply_created_by_user_image, $get_reply_created_by_ip, $get_reply_text, $get_reply_likes, $get_reply_datetime, $get_reply_datetime_print, $get_reply_time, $get_reply_reported, $get_reply_reported_checked, $get_reply_reported_reason, $get_reply_seen) = $row_replies;

					// Thumb
					$inp_new_x = 40; // 950
					$inp_new_y = 40; // 640
					if(file_exists("$root/_uploads/users/images/$get_reply_created_by_user_id/$get_reply_created_by_user_image") && $get_reply_created_by_user_image != ""){
						$thumb_full_path = "$root/_cache/user_" . $get_reply_created_by_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
						if(!(file_exists("$thumb_full_path"))){
							resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_reply_created_by_user_id/$get_reply_created_by_user_image", "$thumb_full_path");
						}
					}
					else{
						$thumb_full_path = "_gfx/avatar_blank_40.png";
					}

					// Did I like the reply?
					$i_have_liked_this_reply = "0";
					if(isset($my_user_id)){
						$q_check_like = "SELECT like_id, like_reply_id, like_user_id, like_user_alias FROM $t_users_status_replies_likes WHERE like_reply_id=$get_reply_id AND like_user_id=$my_user_id_mysql";
						$r_check_like = mysqli_query($link, $q_check_like);
						$row_check_like = mysqli_fetch_row($r_check_like);
						list($get_like_id, $get_like_reply_id, $get_like_user_id, $get_like_user_alias) = $row_check_like;

						if($get_like_id != ""){
							$i_have_liked_this_reply = "1";
						}
					}

					echo"
					<a id=\"reply$get_reply_id\"></a>
					<table style=\"width: 100%;\">
					 <tr>
					  <td style=\"width: 45px;vertical-align: top;padding: 0px 0px 0px 45px;\">
						<p><a href=\"view_profile.php?user_id=$get_reply_created_by_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" /></a></p>
					  </td>
					  <td class=\"reply\" style=\"vertical-align: top;\">
						<!-- Reply author, date, and more -->
							<table>
							 <tr>
							  <td style=\"vertical-align:top;\">
								<p style=\"padding-bottom: 0;margin-bottom: 0;\">
								<a href=\"view_profile.php?user_id=$get_reply_created_by_user_id&amp;l=$l\" style=\"color: #000\"><b>$get_reply_created_by_user_alias</b></a>
								<span class=\"smal_grey\">$get_reply_datetime_print</span>
								</p>
							  </td>
							  <td>
								<!-- Reply more -->
									<p style=\"padding-bottom: 0;margin-bottom: 0;\">
									<a href=\"#reply$get_reply_id\" id=\"ic_more_horiz_link_reply_$get_reply_id\"><img src=\"_gfx/ic_more_horiz.png\" alt=\"ic_more_horiz.png\" /></a>
									</p>
									<script>
									\$(document).ready(function(){
										\$(\"#ic_more_horiz_link_reply_$get_reply_id\").click(function () {
											\$(\"#ic_more_horiz_div_reply_$get_reply_id\").toggle();
										});
									});
									</script>
										<div id=\"ic_more_horiz_div_reply_$get_reply_id\" style=\"display: none;\">
										<div class=\"ic_more_horiz_div_status\">
											<ul>";
											if($get_reply_reported == "0"){
												echo"<li><a href=\"reply_report.php?reply_id=$get_reply_id&amp;l=$l\">$l_report</a></li>";
											}
											else{
												echo"<li><a href=\"reply_report.php?reply_id=$get_reply_id&amp;l=$l\">($l_already_reported)</a></li>";
											}
									
											if(isset($my_user_id) && $get_reply_created_by_user_id == "$my_user_id"){
													echo"
												<li><a href=\"reply_edit.php?reply_id=$get_reply_id&amp;l=$l\">$l_edit</a></li>
												<li><a href=\"reply_delete.php?reply_id=$get_reply_id&amp;l=$l\">$l_delete</a></li>";
												}
												echo"
											</ul>
										</div>
									</div>
								<!-- //Reply more -->
							  </td>
							 </tr>
							</table>
						<!-- //Reply author, date, and more -->
			
							<p style=\"padding-top:0;margin-top:0;\">
						$get_reply_text
						</p>

						<!-- Who has liked this reply -->";
							$x = 0;
							$dont_put_comma_at = $get_reply_likes - 1;
							$put_and_at = $get_reply_likes - 2;
								$query_l = "SELECT like_id, like_reply_id, like_user_id, like_user_alias FROM $t_users_status_replies_likes WHERE like_reply_id=$get_reply_id";
							$result_l = mysqli_query($link, $query_l);
							while($row_l = mysqli_fetch_row($result_l)) {
								list($get_like_id, $get_like_reply_id, $get_like_user_id, $get_like_user_alias) = $row_l;
									// reply likes
								echo"<a href=\"view_profile.php?user_id=$get_like_user_id&amp;l=$l\">$get_like_user_alias</a>";
									if($put_and_at == "$x"){
									echo" $l_and_lowercase ";
								}
								else{
									if($dont_put_comma_at == "$x"){
									}
									else{
										echo", ";
									}
								}
								$x++;
							}
							if($get_reply_likes != "0"){
								echo" $l_likes_this_lowercase";
							}
						echo"
						<!-- //Who has liked this reply -->

						<div class=\"reply_actions\">";
							if($i_have_liked_this_reply == "0"){
								echo"
								<table>
								 <tr>
								  <td style=\"width: 20px;vertical-algin:top;\">
									<p style=\"padding-top:0px;margin-top:0;\">
									<a href=\"reply_like.php?reply_id=$get_reply_id&amp;process=1&amp;l=$l\"><img src=\"_gfx/thumbs_up.png\" alt=\"thumbs_up.png\" /></a>
									</p>
								  </td>
								  <td style=\"vertical-algin:top;padding: 0px 10px 0px 0px;\">
									<p style=\"padding-top:0px;margin-top:0;\">
									<a href=\"reply_like.php?reply_id=$get_reply_id&amp;process=1&amp;l=$l\">$l_like</a>
									</p>
						 		  </td>
								 </tr>
								</table>
								";
							}
							echo"
						</div>

			
					  </td>
					 </tr>
					</table>
					";
				} // replies
				echo"
			
			<!-- //Replies -->
			";
		}

		echo" 
	<!-- //View comments -->
	
	";
}
?>