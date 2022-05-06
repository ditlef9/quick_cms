<?php
echo"

				<!-- Favorites -->
					";
					if(isset($_SESSION['user_id']) && isset($_SESSION['security']) && isset($get_current_space_id)){

						$my_user_id = $_SESSION['user_id'];
						$my_user_id = output_html($my_user_id);
						$my_user_id_mysql = quote_smart($link, $my_user_id);
						include("$root/_admin/_translations/site/$l/knowledge/ts_right.php");
						echo"
						<div>
							<p><span class=\"main_right_headline\">$l_favorites</spans></p>
							<ul>";
							$query = "SELECT favorite_id, favorite_page_id, favorite_space_id, favorite_user_id, favorite_category_id, favorite_page_title, favorite_page_description FROM $t_knowledge_pages_favorites WHERE favorite_space_id=$get_current_space_id AND favorite_user_id=$my_user_id_mysql";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_favorite_id, $get_favorite_page_id, $get_favorite_space_id, $get_favorite_user_id, $get_favorite_category_id, $get_favorite_page_title, $get_favorite_page_description) = $row;

								$title_len = strlen($get_favorite_page_title);
								if($title_len > 30){
									$favorite_page_title_substr = substr($get_favorite_page_title, 0, 27);
									$favorite_page_title_substr = $favorite_page_title_substr . "...";

									echo"<li><a href=\"view_page.php?space_id=$get_favorite_space_id&amp;page_id=$get_favorite_page_id&amp;l=$l\" title=\"$get_favorite_page_title\">$favorite_page_title_substr</a></li>\n";

								}
								else{
									echo"<li><a href=\"view_page.php?space_id=$get_favorite_space_id&amp;page_id=$get_favorite_page_id&amp;l=$l\">$get_favorite_page_title</a></li>\n";

								}

								echo"								\n";
							
							}
							echo"
							</ul>
						</div>


						<div>
							<p><span class=\"main_right_headline\">$l_history</spans></p>
							<ul>";
							$x = 0;
							$query = "SELECT history_id, history_page_id, history_space_id, history_user_id, history_page_title, history_page_description, history_page_viewed_datetime, history_page_viewed_year FROM $t_knowledge_pages_view_history WHERE history_space_id=$get_current_space_id AND history_user_id=$my_user_id_mysql ORDER BY history_page_viewed_datetime DESC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_history_id, $get_history_page_id, $get_history_space_id, $get_history_user_id, $get_history_page_title, $get_history_page_description, $get_history_page_viewed_datetime, $get_history_page_viewed_year) = $row;

								$title_len = strlen($get_history_page_title);
								if($title_len > 30){
									$history_page_title_substr = substr($get_history_page_title, 0, 27);
									$history_page_title_substr = $history_page_title_substr . "...";

									echo"
									<li><a href=\"view_page.php?space_id=$get_history_space_id&amp;page_id=$get_history_page_id&amp;l=$l\" title=\"$get_history_page_title\">$history_page_title_substr</a></li>
									";
								}
								else{
									echo"
									<li><a href=\"view_page.php?space_id=$get_history_space_id&amp;page_id=$get_history_page_id&amp;l=$l\">$get_history_page_title</a></li>
									";
								}
								

								if($x > 10){
									$result_delete = mysqli_query($link, "DELETE FROM $t_knowledge_pages_view_history WHERE history_id=$get_history_id");
								}


								$x = $x+1;
							}
							echo"
							</ul>
						</div>
						";
					}
					echo"
				<!-- //Favorites and history -->


				<!-- Community -->
					<div>
					<p><a href=\"$root/discuss/index.php?l=$l\" class=\"main_right_headline\">Community</a></p>
					";
					$query_w = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_title, topic_updated_translated, topic_replies, topic_views FROM $t_discuss_topics ORDER BY topic_id DESC LIMIT 0,5";
					$result_w = mysqli_query($link, $query_w);
					while($row_w = mysqli_fetch_row($result_w)) {
						list($get_topic_id, $get_topic_user_id, $get_topic_user_alias, $get_topic_user_image, $get_topic_title, $get_topic_updated_translated, $get_topic_replies, $get_topic_views) = $row_w;


						// Avatar
						$inp_new_x = 40; // 950
						$inp_new_y = 40; // 640
						if(file_exists("$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image") && $get_topic_user_image != ""){
							$thumb_full_path = "$root/_cache/user_" . $get_topic_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
							if(!(file_exists("$thumb_full_path"))){
								resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_topic_user_id/$get_topic_user_image", "$thumb_full_path");
							}
			
						}
						else{
							$thumb_full_path = "$root/discuss/_gfx/avatar_blank_40.png";
						}

						// Substr
						$title_len = strlen($get_topic_title);
						if($title_len > 45){
							$get_topic_title = substr($get_topic_title, 0, 42);
							$get_topic_title = $get_topic_title . "...";
						}


						echo"
						<table>
						 <tr>
						  <td class=\"last_topic_author\">
							<a href=\"$root/users/view_profile.php?user_id=$get_topic_user_id&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$thumb_full_path\" class=\"last_topic_author_image\" /></a>
						  </td>
						  <td class=\"last_topic_text\">
							<a href=\"$root/discuss/view_topic.php?topic_id=$get_topic_id&amp;l=$l\" class=\"last_topic_text_item\">$get_topic_title</a>
						  </td>
						 </tr>
						</table>
						";
					}
					echo"
					</div>
				<!-- //Community -->

				<!-- New members -->

					<div>
					<p><a href=\"$root/users/index.php?l=$l\" class=\"main_right_headline\">New members</a></p>
					<ul>";
							
						$t_users = $mysqlPrefixSav . "users";
						$q = "SELECT user_id, user_name,  user_alias, user_rank FROM $t_users WHERE user_verified_by_moderator='1' ORDER BY user_id DESC LIMIT 0,10";
						$r = mysqli_query($link, $q);
						while($row = mysqli_fetch_row($r)) {
							$get_user_id		= $row[0];
							$get_user_name		= $row[1];
							$get_user_alias		= $row[2];
							$get_user_rank		= $row[3];

							echo"								\n";
							echo"<li class=\"list_box_item\"><a href=\"$root/users/view_profile.php?user_id=$get_user_id&amp;l=$l\" class=\"list_box_item\">$get_user_alias</a></li>\n";

						}
						echo"
					</ul>
					</div>
				<!-- //New members -->


";
?>