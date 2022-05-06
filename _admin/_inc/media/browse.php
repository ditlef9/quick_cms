<?php
/**
*
* File: _admin/_inc/media/default.php
* Version 
* Date 18:40 02.11.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ----------------------------------------------------------------------- */
include("_functions/create_height.php");

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['image_path_id'])) {
	$image_path_id = $_GET['image_path_id'];
	$image_path_id = strip_tags(stripslashes($image_path_id));

	// Check for valid image_path_id, else create one at root level
	$image_path_id_mysql = quote_smart($link, $image_path_id);
	$query = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path FROM $t_images_paths WHERE image_path_id=$image_path_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_image_path_id, $get_current_image_path_title, $get_current_image_path_parent_id, $get_current_image_path_path) = $row;
	if($get_current_image_path_id == ""){
		echo"
		<div class=\"info\"><p>Unknown path.</p></div>
		";
		die;
	}
}
else{
	$image_path_id = "";
}

if(isset($_GET['image_path_id_b'])) {
	$image_path_id_b = $_GET['image_path_id_b'];
	$image_path_id_b = strip_tags(stripslashes($image_path_id_b));
}
else{
	$image_path_id_b = "";
}
if(isset($_GET['image_path_id_c'])) {
	$image_path_id_c = $_GET['image_path_id_c'];
	$image_path_id_c = strip_tags(stripslashes($image_path_id_c));
}
else{
	$image_path_id_c = "";
}

if($action == ""){
	echo"
	<h1>$l_media</h1>

	
	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		elseif($fm == "navgation_item_deleted"){
			$fm = "$l_navgation_item_deleted";
		}
		
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->


	<!-- Content Left -->
		<div class=\"content_left_small\">
	
			<!-- Browse -->
				<div class=\"content_right_box\">
					<h2>$l_browse</h2>

					
					";

					$query = "SELECT * FROM $t_images_paths WHERE image_path_parent_id='0' ORDER BY image_path_title ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_while_image_path_id, $get_while_image_path_title, $get_while_image_path_parent_id, $get_while_image_path_path) = $row;

						if($image_path_id == ""){
							$image_path_id = $get_while_image_path_id;
						}

						echo"
						<table>
						 <tr>
						  <td style=\"padding: 2px 6px 0px 0px;\" >
							<a href=\"index.php?open=media&amp;page=default&amp;image_path_id=$get_while_image_path_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder"; if($image_path_id == "$get_while_image_path_id"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
						  </td>
						  <td>
							<a href=\"index.php?open=media&amp;page=default&amp;image_path_id=$get_while_image_path_id&amp;editor_language=$editor_language\">$get_while_image_path_title</a>
						  </td>
						 </tr>
						</table>
						";

						if($image_path_id == "$get_while_image_path_id"){ 
						
							$query_b = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path FROM $t_images_paths WHERE image_path_parent_id='$get_while_image_path_id'";
							$result_b = mysqli_query($link, $query_b);
							while($row_b = mysqli_fetch_row($result_b)) {
								list($get_b_image_path_id, $get_b_image_path_title, $get_b_image_path_parent_id, $get_b_image_path_path) = $row_b;

								echo"
								<table>
								 <tr>
								  <td style=\"padding: 2px 6px 0px 20px;\" >	
									<a href=\"index.php?open=media&amp;page=$page&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder"; if($image_path_id_b == "$get_b_image_path_id"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
								  </td>
								  <td>
									<a href=\"index.php?open=media&amp;page=$page&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;editor_language=$editor_language\">$get_b_image_path_title</a>
								  </td>
								 </tr>
								</table>
								";


					
								if($image_path_id_b == "$get_b_image_path_id"){ 
						
									$query_c = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path FROM $t_images_paths WHERE image_path_parent_id='$get_b_image_path_id'";
									$result_c = mysqli_query($link, $query_c);
									while($row_c = mysqli_fetch_row($result_c)) {
										list($get_c_image_path_id, $get_c_image_path_title, $getc_image_path_parent_id, $get_c_image_path_path) = $row_c;

										echo"
										<table>
										 <tr>
										  <td style=\"padding: 2px 6px 0px 40px;\" >	
											<a href=\"index.php?open=media&amp;page=$page&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;image_path_id_c=$get_c_image_path_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder"; if($image_path_id_c == "$get_c_image_path_id"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
										  </td>
										  <td>
											<a href=\"index.php?open=media&amp;page=$page&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;image_path_id_c=$get_c_image_path_id&amp;editor_language=$editor_language\">$get_c_image_path_title</a>
										  </td>
										 </tr>
										</table>
										";
									} // c

								} // b open
							} // b

						} // a open

					} //a
					echo"
				</div>
			<!-- //Upload to -->
		</div>

	<!-- //Content Left -->
	

	<!-- Content Right -->
		<div class=\"content_right_big\">

			<!-- List all images -->
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_file</span>
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
				$query = "SELECT $t_images.image_id, $t_images.image_title, $t_images.image_path, $t_images.image_file_name, $t_images.image_updated, $t_images.image_updated_by_user_id, $t_users.user_name FROM $t_images
				JOIN $t_users ON $t_images.image_updated_by_user_id=$t_users.user_id
				WHERE image_language=$editor_language_mysql ORDER BY image_title ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_image_id, $get_image_title, $get_image_path, $get_image_file_name, $get_image_updated, $get_image_updated_by_user_id, $get_user_name) = $row;
	
					if(isset($odd) && $odd == false){
						$odd = true;
					}
					else{
						$odd = false;
					}			

					echo"
					<tr>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						
						<div  style=\"float: left;padding: 0px 8px 0px 0x;\">

							<p>
							<a href=\"../$get_image_path/$get_image_file_name\"><img src=\"../image.php/$get_image_file_name?width=50&amp;height=50&amp;cropratio=1:1&amp;image=/$get_image_path/$get_image_file_name\" alt=\"$get_image_file_name\" /></a>
							</p>
						</div>

						<div style=\"float: left;margin-left:10px;\">
							<p>
							<a href=\"../$get_image_path/$get_image_file_name\">$get_image_title</a><br />
							<span class=\"smal\">$get_image_path/$get_image_file_name</span>
							</p>
						</div>
					  </td>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span><a href=\"index.php?open=users&amp;page=users_edit_user&amp;user_id=$get_image_updated_by_user_id&amp;editor_language=$editor_language\">$get_user_name</a></span>
					  </td>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span>";
						echo substr($get_image_updated, 0, 10);
						echo"</span>
					  </td>
					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span>
						<a href=\"index.php?open=$open&amp;page=edit_image&amp;image_id=$get_image_id&amp;image_path_id=$image_path_id&amp;editor_language=$editor_language\">$l_edit</a>
						&middot;
						<a href=\"../$get_image_path/$get_image_file_name\">$l_view</a>
						&middot;
						<a href=\"index.php?open=$open&amp;page=delete_image&amp;image_id=$get_image_id&amp;image_path_id=$image_path_id&amp;editor_language=$editor_language\">$l_delete</a>
						</span>
					 </td>
					</tr>
					";
				}
			echo"
				 </tbody>
				</table>
			<!-- List all images -->
		</div>
	<!-- //Content Right -->

	";
} // action = ""
?>