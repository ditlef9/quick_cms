<?php
echo"
		</div> <!-- //layout_content_inner -->
	</main> <!-- //layout_content_wrapper -->
<!-- //Content -->


<!-- Aside -->
	<aside>
	
		<!-- Feeds -->
			";
			$t_users_feeds_index		= $mysqlPrefixSav . "users_feeds_index";
			$l = output_html($l);
			$l_mysql = quote_smart($link, $l);
			$query = "SELECT feed_id, feed_title, feed_text, feed_image_path, feed_image_file, feed_image_thumb_300x169, feed_image_thumb_540x304, feed_link_url, feed_link_name, feed_module_name, feed_module_part_name, feed_module_part_id, feed_main_category_id, feed_main_category_name, feed_sub_category_id, feed_sub_category_name, feed_user_id, feed_user_email, feed_user_name, feed_user_alias, feed_user_photo_file, feed_user_photo_thumb_40, feed_user_photo_thumb_50, feed_user_photo_thumb_60, feed_user_photo_thumb_200, feed_user_subscribe, feed_user_ip, feed_user_hostname, feed_language, feed_created_datetime, feed_created_date_saying, feed_created_year, feed_created_time, feed_modified_datetime, feed_likes, feed_dislikes, feed_comments, feed_reported, feed_reported_checked, feed_reported_reason FROM $t_users_feeds_index WHERE feed_language=$l_mysql ORDER BY feed_id DESC LIMIT 4,10";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_feed_id, $get_feed_title, $get_feed_text, $get_feed_image_path, $get_feed_image_file, $get_feed_image_thumb_300x169, $get_feed_image_thumb_540x304, $get_feed_link_url, $get_feed_link_name, $get_feed_module_name, $get_feed_module_part_name, $get_feed_module_part_id, $get_feed_main_category_id, $get_feed_main_category_name, $get_feed_sub_category_id, $get_feed_sub_category_name, $get_feed_user_id, $get_feed_user_email, $get_feed_user_name, $get_feed_user_alias, $get_feed_user_photo_file, $get_feed_user_photo_thumb_40, $get_feed_user_photo_thumb_50, $get_feed_user_photo_thumb_60, $get_feed_user_photo_thumb_200, $get_feed_user_subscribe, $get_feed_user_ip, $get_feed_user_hostname, $get_feed_language, $get_feed_created_datetime, $get_feed_created_date_saying, $get_feed_created_year, $get_feed_created_time, $get_feed_modified_datetime, $get_feed_likes, $get_feed_dislikes, $get_feed_comments, $get_feed_reported, $get_feed_reported_checked, $get_feed_reported_reason) = $row;
			
				echo"
				
				<div class=\"feed_bodycell\">
					<!-- Author and headline -->
						<table>
						 <tr>
						  <td style=\"padding: 0px 6px 0px 0px;vertical-align:top;\">
							<span>
							<a href=\"$root/users/view_profile.php?user_id=$get_feed_user_id&amp;l=$l\">";
							if(file_exists("$root/_uploads/users/images/$get_feed_user_id/$get_feed_user_photo_thumb_40") && $get_feed_user_photo_thumb_40 != ""){
								echo"<img src=\"$root/_uploads/users/images/$get_feed_user_id/$get_feed_user_photo_thumb_40\" alt=\"$get_feed_user_photo_thumb_40\" class=\"feed_author_image\"  />";
							}
							else{
								echo"<img src=\"$root/users/_gfx/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" class=\"feed_author_image\" />";
							}
							echo"</a>
							</span>
						  </td>
						  <td style=\"vertical-align:top;\">
							<span>
							<a href=\"$root/$get_feed_link_url\" class=\"feed_headline\">$get_feed_title</a><br />
							<a href=\"$root/users/view_profile.php?user_id=$get_feed_user_id&amp;l=$l\" class=\"feed_author\">$get_feed_user_name</a><br />
							</span>
						  </td>
						 </tr>
						</table>
					<!-- //Author and headline -->

					<!-- Post -->";
						if(file_exists("$root/$get_feed_image_path/$get_feed_image_file") && $get_feed_image_file != ""){
							if(!(file_exists("$root/$get_feed_image_path/$get_feed_image_thumb_540x304")) && $get_feed_image_thumb_540x304 != ""){
								// Create thumb
								resize_crop_image(540, 304, "$root/$get_feed_image_path/$get_feed_image_file", "$root/$get_feed_image_path/$get_feed_image_thumb_540x304");

							}

							if(file_exists("$root/$get_feed_image_path/$get_feed_image_thumb_540x304") && $get_feed_image_thumb_540x304 != ""){
								echo"
								<p>
								<a href=\"$root/$get_feed_link_url\"><img src=\"$root/$get_feed_image_path/$get_feed_image_thumb_540x304\" alt=\"$get_feed_image_thumb_540x304\" /></a>
								</p>
								";
							}
						}
						if($get_feed_text != ""){
							echo"
							<p>
							$get_feed_text
							</p>
							";
						}
						echo"
					<!-- //Post -->

				</div>
				<div class=\"feed_bodycell_after\"></div>
				";
			}
			echo"
		<!-- //Feeds -->
	</aside> 
<!-- //Aside wrapper -->

<!-- Cookies warning -->
	";
	// include("$root/_admin/_functions/cookies_warning_include.php");
	echo"
<!-- //Cookies warning -->

<!-- Footer -->
	<footer>
		
			<div class=\"footer_parent\">";

				// Footer groups
				$t_webdesign_footer_link_groups = $mysqlPrefixSav . "webdesign_footer_link_groups";
				$t_webdesign_footer_link_links = $mysqlPrefixSav . "webdesign_footer_link_links";
				$l_mysql = quote_smart($link, $l);
				$query = "SELECT group_id, group_title, group_show_title, group_type FROM $t_webdesign_footer_link_groups WHERE group_language=$l_mysql ORDER BY group_weight ASC LIMIT 0,10";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_group_id, $get_group_title, $get_group_show_title, $get_group_type) = $row;
					echo"
					<div class=\"footer_child\">\n";
						if($get_group_show_title == "1"){ 
							echo"
							<h4>$get_group_title</h4>\n"; 
						}
						echo"
						<ul class=\"footer_$get_group_type\">\n";
						// Footer links
						$query_links = "SELECT link_id, link_title, link_url, link_icon_path, link_icon_24x24, link_icon_32x32, link_internal_or_external FROM $t_webdesign_footer_link_links WHERE link_group_id=$get_group_id ORDER BY link_weight ASC LIMIT 0,10";
						$result_links = mysqli_query($link, $query_links);
						while($row_links = mysqli_fetch_row($result_links)) {
							list($get_link_id, $get_link_title, $get_link_url, $get_link_icon_path, $get_link_icon_24x24, $get_link_icon_32x32, $get_link_internal_or_external) = $row_links;

							echo"						";
							echo"<li><a href=\"";
							if($get_link_internal_or_external == "internal"){
								echo"$root/$get_link_url";
							}
							else{
								echo"$get_link_url";
							}
							echo"\">";
							
							// Text or Icon
							if(file_exists("$root/$get_link_icon_path/$get_link_icon_24x24") && $get_link_icon_24x24 != ""){
								echo"<img src=\"$root/$get_link_icon_path/$get_link_icon_24x24\" alt=\"$get_link_icon_24x24\" />";
							}
							else{
								echo"$get_link_title";
							}

							echo"</a></li>\n";
						}
						echo"

						</ul>
					</div>
					";
				}
				echo"
			</div>

			<div class=\"footer_after\">
				<div class=\"footer_after_child\">
					<p>
					$configWebsiteCopyrightSav
					</p>
				</div>
				<div class=\"footer_after_child\">
					<!-- Languages -->
						<p>\n";
						$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_active_16x16, language_active_default FROM $t_languages_active";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_active_16x16, $get_language_active_default) = $row;
							echo"<a href=\"index.php?l=$get_language_active_iso_two\"><img src=\"$root/$get_language_active_flag_path_16x16/$get_language_active_flag_active_16x16\" alt=\"$get_language_active_flag_active_16x16\" /></a>\n";
						}
						echo"
						</p>
					<!-- //Languages -->
				</div>
			</div>
	</footer>
<!-- //Footer -->

</body>
</html>";

?>