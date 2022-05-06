<?php
echo"

			<!-- Menu -->
				<div class=\"tabs\">
					<ul>
						<li><a href=\"index.php?open=$open&amp;page=users_edit_user&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer&amp;editor_language=$editor_language\""; if($action == ""){ echo" class=\"active\""; } echo">$l_edit_user</a></li>
						<li><a href=\"index.php?open=$open&amp;page=users_edit_user_password&amp;action=edit_password&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer&amp;editor_language=$editor_language\" "; if($action == "edit_password"){ echo" class=\"active\""; } echo">$l_password</a></li>
						<li><a href=\"index.php?open=$open&amp;page=users_edit_user_photos&amp;action=photos&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer&amp;editor_language=$editor_language\""; if($action == "photos"){ echo" class=\"active\""; } echo">$l_photos</a></li>
						<li><a href=\"index.php?open=$open&amp;page=users_edit_user_professional&amp;action=professional&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer&amp;editor_language=$editor_language\""; if($action == "professional"){ echo" class=\"active\""; } echo">Professional</a></li>\n";

						// Headlines
						$l_mysql = quote_smart($link, $l);
						$query = "SELECT headline_id, headline_title, headline_icon_path_18x18, headline_icon_file_18x18 FROM $t_users_profile_headlines WHERE headline_user_can_view_headline=1 ORDER BY headline_weight DESC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_headline_id, $get_headline_title, $get_headline_icon_path_18x18, $get_headline_icon_file_18x18) = $row;

							// Get translation
							$query_t = "SELECT translation_id, translation_headline_id, translation_language, translation_value FROM $t_users_profile_headlines_translations WHERE translation_headline_id=$get_headline_id AND translation_language=$l_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_translation_id, $get_translation_headline_id, $get_translation_language, $get_translation_value) = $row_t;

							if($get_translation_id == ""){
								$inp_title_mysql = quote_smart($link, $get_headline_title);
								mysqli_query($link, "INSERT INTO $t_users_profile_headlines_translations
								(translation_id, translation_headline_id, translation_language, translation_value) 
								VALUES 
								(NULL, $get_headline_id, $l_mysql, $inp_title_mysql)")
								or die(mysqli_error($link));
								$get_translation_value = "$get_current_headline_title";
							}


							echo"<li><a href=\"index.php?open=$open&amp;page=users_edit_user_headline&amp;action=headline&amp;user_id=$user_id&amp;headline_id=$get_headline_id&amp;l=$l\""; if($action == "headline"){ echo" class=\"active\""; } echo">$get_translation_value</a></li>\n";
						}
						echo"
					</ul>
				</div>
				<div class=\"clear\"></div>
			<!-- //Menu -->
";
?>