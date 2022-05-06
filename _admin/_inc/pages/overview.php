<?php
/**
*
* File: _admin/_inc/pages/default.php
* Version 
* Date 20:17 30.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


if($action == ""){
	echo"
	<h1>$l_pages</h1>
				
	<!-- Root ? -->	
		";
		$page_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT page_id FROM $t_pages WHERE page_language=$page_language_mysql AND page_path=''";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_page_id) = $row;

		if($get_page_id == ""){
			// Create root page
			echo"<div class=\"info\"><span>L O A D I N G</span></div>";
			echo"
 			<meta http-equiv=\"refresh\" content=\"0;URL='index.php?open=pages&amp;page=create_root_page&amp;editor_language=$editor_language'\" />
			";
		}


		echo"
	<!-- //Root ? -->
	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Menu: Editor language, Actions -->
		<script>
		\$(function(){
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>

		<div style=\"float: left\">
			<p>
			<a href=\"index.php?open=pages&amp;page=new_page&amp;editor_language=$editor_language\" class=\"btn\">$l_new_page</a>
			</p>
		</div>
		<div style=\"float: right;\">
			<p>
			<select id=\"inp_l\">\n";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

	
				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				echo"	<option value=\"index.php?open=pages&amp;editor_language=$get_language_active_iso_two&amp;l=$l\""; if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>
		</div>
		<div class=\"clear\"></div>
	<!-- //Menu: Editor language, Actions -->


	<!-- List all pages -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_title</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_author</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_date</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	


		$editor_language_mysql = quote_smart($link, $editor_language);
		//$query = "SELECT page_id, page_title, page_path, page_file_name, page_created, page_created_by_user_id, page_updated, page_updated_by_user_id, page_no_of_comments, page_uniqe_hits FROM $t_pages WHERE page_parent_id='0' AND page_language=$editor_language_mysql ORDER BY page_id ASC";
		$query = "SELECT $t_pages.page_id, $t_pages.page_title, $t_pages.page_path, $t_pages.page_file_name, $t_pages.page_created, $t_pages.page_created_by_user_id, $t_pages.page_updated, $t_pages.page_updated_by_user_id, $t_pages.page_no_of_comments, $t_pages.page_uniqe_hits, $t_users.user_name FROM $t_pages 
			JOIN $t_users ON $t_pages.page_updated_by_user_id=$t_users.user_id
			WHERE page_parent_id='0' AND page_language=$editor_language_mysql ORDER BY page_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_page_id, $get_page_title, $get_page_path, $get_page_file_name, $get_page_created, $get_page_created_by_user_id, $get_page_updated, $get_page_updated_by_user_id, $get_page_no_of_comments, $get_page_uniqe_hits, $get_user_name) = $row;

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}

			echo"
			<tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_page_id&amp;editor_language=$editor_language\">$get_page_title</a>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span><a href=\"index.php?open=users&amp;page=users_edit_user&amp;user_id=$get_page_updated_by_user_id&amp;editor_language=$editor_language\">$get_user_name</a></span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>";
				if($get_page_updated != ""){
					echo substr($get_page_updated, 0, 10);
				}
				else{
					echo substr($get_page_created, 0, 10);
				}
				echo"</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>
				<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_page_id&amp;editor_language=$editor_language\">$l_edit</a>
				&middot;
				<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_page_id&amp;edit_mode=html&amp;editor_language=$editor_language\">$l_html</a>
				&middot;
				<a href=\"../$get_page_path/$get_page_file_name\">$l_view</a>
				&middot;
				<a href=\"index.php?open=$open&amp;page=delete_page&amp;page_id=$get_page_id&amp;editor_language=$editor_language\">$l_delete</a>
				</span>
			 </td>
			</tr>
			";

			// B: Find children
			$query_b = "SELECT $t_pages.page_id, $t_pages.page_title, $t_pages.page_path, $t_pages.page_file_name, $t_pages.page_created, $t_pages.page_created_by_user_id, $t_pages.page_updated, $t_pages.page_updated_by_user_id, $t_pages.page_no_of_comments, $t_pages.page_uniqe_hits, $t_users.user_name FROM $t_pages 
				JOIN $t_users ON $t_pages.page_updated_by_user_id=$t_users.user_id
				WHERE page_parent_id='$get_page_id' AND page_language=$editor_language_mysql ORDER BY page_title ASC";
			$result_b = mysqli_query($link, $query_b);
			while($row_b = mysqli_fetch_row($result_b)) {
				list($get_b_page_id, $get_b_page_title, $get_b_page_path, $get_b_page_file_name, $get_b_page_created, $get_b_page_created_by_user_id, $get_b_page_updated, $get_b_page_updated_by_user_id, $get_b_page_no_of_comments, $get_b_page_uniqe_hits, $get_b_user_name) = $row_b;

				if(isset($odd) && $odd == false){
					$odd = true;
				}
				else{
					$odd = false;
				}


				echo"
				<tr>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_b_page_id&amp;editor_language=$editor_language\">&mdash; $get_b_page_title</a>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span><a href=\"index.php?open=users&amp;page=users_edit_user&amp;user_id=$get_b_page_updated_by_user_id&amp;editor_language=$editor_language\">$get_b_user_name</a></span>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>";
					if($get_b_page_updated != ""){
						echo substr($get_b_page_updated, 0, 10);
					}
					else{
						echo substr($get_b_page_created, 0, 10);
					}
					echo"</span>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>
					<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_b_page_id&amp;editor_language=$editor_language\">$l_edit</a>
					&middot;
					<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_b_page_id&amp;edit_mode=html&amp;editor_language=$editor_language\">$l_html</a>
					&middot;
					<a href=\"../$get_b_page_path/$get_b_page_file_name\">$l_view</a>
					&middot;
					<a href=\"index.php?open=$open&amp;page=delete_page&amp;page_id=$get_b_page_id&amp;editor_language=$editor_language\">$l_delete</a>
					</span>
				 </td>
				</tr>
				";
				// C: Find children
				$query_c = "SELECT $t_pages.page_id, $t_pages.page_title, $t_pages.page_path, $t_pages.page_file_name, $t_pages.page_created, $t_pages.page_created_by_user_id, $t_pages.page_updated, $t_pages.page_updated_by_user_id, $t_pages.page_no_of_comments, $t_pages.page_uniqe_hits, $t_users.user_name FROM $t_pages 
					JOIN $t_users ON $t_pages.page_updated_by_user_id=$t_users.user_id
					WHERE page_parent_id='$get_b_page_id' AND page_language=$editor_language_mysql ORDER BY page_title ASC";
				$result_c = mysqli_query($link, $query_c);
				while($row_c = mysqli_fetch_row($result_c)) {
					list($get_c_page_id, $get_c_page_title, $get_c_page_path, $get_c_page_file_name, $get_c_page_created, $get_c_page_created_by_user_id, $get_c_page_updated, $get_c_page_updated_by_user_id, $get_c_page_no_of_comments, $get_c_page_uniqe_hits, $get_c_user_name) = $row_c;

					if(isset($odd) && $odd == false){
						$odd = true;
					}
					else{
						$odd = false;
					}


					echo"
					<tr>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_c_page_id&amp;editor_language=$editor_language\">&mdash; &mdash; $get_c_page_title</a>
					  </td>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span><a href=\"index.php?open=users&amp;page=users_edit_user&amp;user_id=$get_c_page_updated_by_user_id&amp;editor_language=$editor_language\">$get_c_user_name</a></span>
					  </td>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span>";
						if($get_c_page_updated != ""){
							echo substr($get_c_page_updated, 0, 10);
						}
						else{
							echo substr($get_c_page_created, 0, 10);
						}
						echo"</span>
					  </td>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span>
						<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_c_page_id&amp;editor_language=$editor_language\">$l_edit</a>
						&middot;
						<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_c_page_id&amp;edit_mode=html&amp;editor_language=$editor_language\">$l_html</a>
						&middot;
						<a href=\"../$get_c_page_path/$get_c_page_file_name\">$l_view</a>
						&middot;
						<a href=\"index.php?open=$open&amp;page=delete_page&amp;page_id=$get_c_page_id&amp;editor_language=$editor_language\">$l_delete</a>
						</span>
					 </td>
					</tr>
					";
					// D: Find children
					$query_d = "SELECT $t_pages.page_id, $t_pages.page_title, $t_pages.page_path, $t_pages.page_file_name, $t_pages.page_created, $t_pages.page_created_by_user_id, $t_pages.page_updated, $t_pages.page_updated_by_user_id, $t_pages.page_no_of_comments, $t_pages.page_uniqe_hits, $t_users.user_name FROM $t_pages 
						JOIN $t_users ON $t_pages.page_updated_by_user_id=$t_users.user_id
						WHERE page_parent_id='$get_c_page_id' AND page_language=$editor_language_mysql ORDER BY page_title ASC";
					$result_d = mysqli_query($link, $query_d);
					while($row_d = mysqli_fetch_row($result_d)) {
						list($get_d_page_id, $get_d_page_title, $get_d_page_path, $get_d_page_file_name, $get_d_page_created, $get_d_page_created_by_user_id, $get_d_page_updated, $get_d_page_updated_by_user_id, $get_d_page_no_of_comments, $get_d_page_uniqe_hits, $get_d_user_name) = $row_d;

						if(isset($odd) && $odd == false){
							$odd = true;
						}
						else{
							$odd = false;
						}


						echo"
						<tr>
						  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
							<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_d_page_id&amp;editor_language=$editor_language\">&mdash; &mdash; &mdash; $get_d_page_title</a>
						  </td>
						  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
							<span><a href=\"index.php?open=users&amp;page=users_edit_user&amp;user_id=$get_d_page_updated_by_user_id&amp;editor_language=$editor_language\">$get_d_user_name</a></span>
						  </td>
						  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
							<span>";
							if($get_c_page_updated != ""){
								echo substr($get_d_page_updated, 0, 10);
							}
							else{
								echo substr($get_d_page_created, 0, 10);
							}
							echo"</span>
						  </td>
						  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
							<span>
							<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_d_page_id&amp;editor_language=$editor_language\">$l_edit</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=edit_page&amp;page_id=$get_d_page_id&amp;edit_mode=html&amp;editor_language=$editor_language\">$l_html</a>
							&middot;
							<a href=\"../$get_d_page_path/$get_d_page_file_name\">$l_view</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=delete_page&amp;page_id=$get_d_page_id&amp;editor_language=$editor_language\">$l_delete</a>
							</span>
						 </td>
						</tr>
						";
					} // _d
				} // _c
			} // _b
		}
		echo"
		 </tbody>
		</table>
	<!-- //List all pages -->
	";
}
?>