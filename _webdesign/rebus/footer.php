<?php
echo"
		</div> <!-- //main_inner -->
	</main> <!-- //main -->
<!-- //Content -->

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
		</div> <!-- //footer_parent -->


		<div class=\"footer_after\">
			<div class=\"footer_after_child\">
				<p>
				$configWebsiteCopyrightSav
				</p>
			</div>
			<div class=\"footer_after_child\">
				<!-- Languages -->
					<p>\n";
					$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16, language_active_default FROM $t_languages_active";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16, $get_language_active_default) = $row;
						echo"<a href=\"index.php?l=$get_language_active_iso_two\"><img src=\"$root/$get_language_active_flag_path_16x16/$get_language_active_flag_16x16\" alt=\"$get_language_active_flag_16x16\" /></a>\n";
					}
					echo"
					</p>
				<!-- //Languages -->
			</div>
		</div> <!-- //footer_after -->
	</footer>
<!-- //Footer -->

</body>
</html>";

?>