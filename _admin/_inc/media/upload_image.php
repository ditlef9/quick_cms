<?php
/**
*
* File: _admin/_inc/media/upload_image.php
* Version 1
* Date 15.18 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Access check --------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}
if(isset($_GET['image_path_id'])) {
	$image_path_id = $_GET['image_path_id'];
	$image_path_id = strip_tags(stripslashes($image_path_id));

	// Check for valid image_path_id, else create one at root level
	$image_path_id_mysql = quote_smart($link, $image_path_id);
	$query = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path  FROM $t_images_paths WHERE image_path_id=$image_path_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_image_path_id, $get_current_image_path_title, $get_current_image_path_parent_id, $get_current_image_path_path) = $row;
	if($get_current_image_path_id == ""){
		echo"
		<div class=\"info\"><p>Unknown path A.</p></div>
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

	if($image_path_id_b != ""){
		// Check for valid image_path_id, else create one at root level
		$image_path_id_b_mysql = quote_smart($link, $image_path_id_b);
		$query = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path  FROM $t_images_paths WHERE image_path_id=$image_path_id_b_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_image_path_id, $get_current_image_path_title, $get_current_image_path_parent_id, $get_current_image_path_path) = $row;
		if($get_current_image_path_id == ""){
			echo"
			<div class=\"info\"><p>Unknown path B.</p></div>
			";
			die;
		}
	}
}
else{
	$image_path_id_b = "";
}
if(isset($_GET['image_path_id_c'])) {
	$image_path_id_c = $_GET['image_path_id_c'];
	$image_path_id_c = strip_tags(stripslashes($image_path_id_c));

	if($image_path_id_c != ""){
		// Check for valid image_path_id, else create one at root level
		$image_path_id_c_mysql = quote_smart($link, $image_path_id_c);
		$query = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path  FROM $t_images_paths WHERE image_path_id=$image_path_id_c_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_image_path_id, $get_current_image_path_title, $get_current_image_path_parent_id, $get_current_image_path_path) = $row;
		if($get_current_image_path_id == ""){
			echo"
			<div class=\"info\"><p>Unknown path C.</p></div>
			";
			die;
		}
	}
}
else{
	$image_path_id_c = "";
}

if($action == ""){

	echo"
	<h2>$l_upload_image</h2>

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
	
				<!-- Upload to -->


				<div class=\"content_right_box\">
					<p><b>$l_upload_to</b></p>

		
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
							<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$get_while_image_path_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder"; if($image_path_id == "$get_while_image_path_id"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
						  </td>
						  <td>
							<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$get_while_image_path_id&amp;editor_language=$editor_language\">$get_while_image_path_title</a>
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
									<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder"; if($image_path_id_b == "$get_b_image_path_id"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
								  </td>
								  <td>
									<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;editor_language=$editor_language\">$get_b_image_path_title</a>
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
											<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;image_path_id_c=$get_c_image_path_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder"; if($image_path_id_c == "$get_c_image_path_id"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
										  </td>
										  <td>
											<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;image_path_id_c=$get_c_image_path_id&amp;editor_language=$editor_language\">$get_c_image_path_title</a>
										  </td>
										 </tr>
										</table>
										";
									} // c

								} // b open
							} // b

						} // a open


					} // a
					echo"
					<table>
					 <tr>
					  <td style=\"padding: 2px 6px 0px 0px;\" >	
						<a href=\"index.php?open=media&amp;page=upload_image&amp;action=new_directory&amp;editor_language=$editor_language\"><img src=\"_inc/media/_gfx/icons/create_new_folder_black_18x18.png\" alt=\"create_new_folder_black_18x18.png\" /></a>
					  </td>
					  <td>
						<a href=\"index.php?open=media&amp;page=upload_image&amp;action=new_directory&amp;editor_language=$editor_language\">$l_new_directory</a>
					  </td>
					 </tr>
					</table>
				
				</div>
				<!-- //Upload to -->
			</div>

		<!-- //Content Left -->
	

		<!-- Content Right -->
			<div class=\"content_right_big\">
				<p><b>$l_upload_type:<br />
				<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;&image_path_id_b=$image_path_id_b&amp;image_path_id_c=$image_path_id_c&amp;editor_language=$editor_language\" style=\"font-weight:bold;\">$l_multible_form</a>
				&middot;
				<a href=\"index.php?open=media&amp;page=upload_image&amp;action=simple_form&amp;image_path_id=$image_path_id&amp;image_path_id_b=$image_path_id_b&amp;&image_path_id_c=$image_path_id_c&amp;editor_language=$editor_language\">$l_simple_form</a>
				</p>

				<p>
				<b>$l_image:</b><br />
				</p>

				<script src=\"_javascripts/dropzone/dropzone.js\"></script>
				<link rel=\"stylesheet\" href=\"_javascripts/dropzone/dropzone.css\">

				<form action=\"index.php?open=media&amp;page=upload_image&amp;action=do_upload&amp;image_path_id=$image_path_id&amp;image_path_id_b=$image_path_id_b&amp;image_path_id_c=$image_path_id_c&amp;editor_language=$editor_language&amp;process=1\" method=\"post\" enctype=\"multipart/form-data\" class=\"dropzone\">
				</form>
			</div>
		<!-- //Content Right -->

	";
}
elseif($action == "simple_form"){
	echo"
	<h2>$l_simple_form</h2>

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
	
				<!-- Upload to -->


				<div class=\"content_right_box\">
					<p><b>$l_upload_to</b></p>

		
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
							<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$get_while_image_path_id&amp;&image_path_id_b=$image_path_id_b&amp;&image_path_id_c=$image_path_id_c&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder"; if($image_path_id == "$get_while_image_path_id"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
						  </td>
						  <td>
							<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$get_while_image_path_id&amp;&image_path_id_b=$image_path_id_b&amp;&image_path_id_c=$image_path_id_c&amp;editor_language=$editor_language\">$get_while_image_path_title</a>
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
									<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder"; if($image_path_id_b == "$get_b_image_path_id"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
								  </td>
								  <td>
									<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;editor_language=$editor_language\">$get_b_image_path_title</a>
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
											<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;image_path_id_c=$get_c_image_path_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder"; if($image_path_id_c == "$get_c_image_path_id"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
										  </td>
										  <td>
											<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;image_path_id_b=$get_b_image_path_id&amp;image_path_id_c=$get_c_image_path_id&amp;editor_language=$editor_language\">$get_c_image_path_title</a>
										  </td>
										 </tr>
										</table>
										";
									} // c

								} // b open
							} // b

						} // a open


					} // a
					echo"
					<table>
					 <tr>
					  <td style=\"padding: 2px 6px 0px 0px;\" >	
						<a href=\"index.php?open=media&amp;page=upload_image&amp;action=new_directory&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/folder-new.png\" alt=\"folder-new.png\" /></a>
					  </td>
					  <td>
						<a href=\"index.php?open=media&amp;page=upload_image&amp;action=new_directory&amp;editor_language=$editor_language\">$l_new_directory</a>
					  </td>
					 </tr>
					</table>
				
				</div>
				<!-- //Upload to -->
			</div>

		<!-- //Content Left -->

		<!-- Content Right -->
			<div class=\"content_right_big\">
				<p><b>$l_upload_type:<br />
				<a href=\"index.php?open=media&amp;page=upload_image&amp;image_path_id=$image_path_id&amp;&image_path_id_b=$image_path_id_b&amp;&image_path_id_c=$image_path_id_c&amp;editor_language=$editor_language\">$l_multible_form</a>
				&middot;
				<a href=\"index.php?open=media&amp;page=upload_image&amp;action=simple_form&amp;image_path_id=$image_path_id&amp;&image_path_id_b=$image_path_id_b&amp;&image_path_id_c=$image_path_id_c&amp;editor_language=$editor_language\" style=\"font-weight:bold;\">$l_simple_form</a>
				</p>

				<form action=\"index.php?open=media&amp;page=upload_image&amp;action=do_upload&amp;mode=simple_form&amp;image_path_id=$image_path_id&amp;&image_path_id_b=$image_path_id_b&amp;&image_path_id_c=$image_path_id_c&amp;editor_language=$editor_language&amp;process=1\" method=\"post\" enctype=\"multipart/form-data\">
				<p>
				<b>$l_image:</b><br />
				<input type=\"file\" name=\"file\" />
				</p>


				<p>
				<input type=\"submit\" value=\"$l_upload\" class=\"submit\" />
				</p>
				</form>
			</div>
		<!-- //Content Right -->

	";
}
elseif($action == "do_upload"){
 
	if (!empty($_FILES)) {
     
		// Sjekk filen
		$file_name = basename($_FILES['file']['name']);
		$file_exp = explode('.', $file_name); 
		$file_type = $file_exp[count($file_exp) -1]; 
		$file_type = strtolower("$file_type");

		// Finnes mappen?
		$image_path = "../$get_current_image_path_path";
		if(isset($get_b_image_path_id) && $get_b_image_path_id != ""){
			$image_path = $image_path . "/$get_b_image_path_id";
		}
		if(isset($get_c_image_path_id) && $get_c_image_path_id != ""){
			$image_path = $image_path . "/$get_c_image_path_id";
		}
		if(!(is_dir($image_path))){
			mkdir($image_path);
		}


		// Sett variabler
		$new_name = str_replace(".$file_type", "", $file_name);
		$new_name = str_replace(" ", "_", $new_name);
		$new_name = clean($new_name);
		$new_name = strtolower($new_name);
		if($new_name == ""){
			$new_name = date("y-m-d-h-i-s");
		}
		$target_path = $image_path . "/" . $new_name . "." . $file_type;
		$thumb_path  = $image_path . "/" . $new_name . "-thumb." . $file_type;


		// Sjekk om det er en OK filendelse
		if($file_type == "jpg" OR $file_type == "png" OR $file_type == "gif"){
			if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {

				// Sjekk om det faktisk er et bilde som er lastet opp
				$image_size = getimagesize($target_path);
				if(is_numeric($image_size[0]) && is_numeric($image_size[1])){
					// Dette bildet er OK

					// Insert into db


					$inp_image_title = output_html($new_name);
					$inp_image_title_mysql = quote_smart($link, $inp_image_title);

					$inp_image_language_mysql = quote_smart($link, $editor_language);

					$inp_image_path_mysql = quote_smart($link, $get_current_image_path_path);

					$inp_image_file_name = $new_name . "." . $file_type;
					$inp_image_file_name = output_html($inp_image_file_name);
					$inp_image_file_name_mysql = quote_smart($link, $inp_image_file_name);

					$inp_image_slug = clean($inp_image_file_name);
					$inp_image_slug_mysql = quote_smart($link, $inp_image_slug);

					$inp_image_created = date("Y-m-d H:i:s");

					$inp_image_created_by_user_id = $_SESSION['admin_user_id'];
					$inp_image_created_by_user_id = output_html($inp_image_created_by_user_id);
					$inp_image_created_by_user_id_mysql = quote_smart($link, $inp_image_created_by_user_id);
	
					mysqli_query($link, "INSERT INTO $t_images
					(image_id, image_title, image_language, image_path, image_file_name, image_slug, image_created, image_created_by_user_id, image_updated, image_updated_by_user_id, image_uniqe_hits) 
					VALUES 
					(NULL,  $inp_image_title_mysql, $inp_image_language_mysql, $inp_image_path_mysql, $inp_image_file_name_mysql, $inp_image_slug_mysql, '$inp_image_created', $inp_image_created_by_user_id_mysql, '$inp_image_created', $inp_image_created_by_user_id_mysql, '0')")
					or die(mysqli_error($link));

					if($mode == "simple_form"){
						$url = "index.php?open=media&page=upload_image&action=simple_form&editor_language=$editor_language&ft=success&fm=image_uploaded";
						header("Location: $url");
						exit;
					}
				}
				else{
					// Dette er en fil som har fått byttet filendelse...
					unlink("$target_path");

					$url = "index.php?open=media&page=upload_image&action=simple_form&editor_language=$editor_language&ft=error&fm=file_is_not_an_image";
					header("Location: $url");
					exit;
				}
			}
			else{
   				switch ($_FILES['inp_file'] ['error']){
				case 1:
					$url = "index.php?open=media&page=upload_image&action=simple_form&editor_language=$editor_language&ft=error&fm=to_big_file";
					header("Location: $url");
					exit;
					break;
				case 2:
					$url = "index.php?open=media&page=upload_image&action=simple_form&editor_language=$editor_language&ft=error&fm=to_big_file";
					header("Location: $url");
					exit;
					break;
				case 3:
					$url = "index.php?open=media&page=upload_image&action=simple_form&editor_language=$editor_language&ft=error&fm=only_parts_uploaded";
					header("Location: $url");
					exit;
					break;
				case 4:
					$url = "index.php?open=media&page=upload_image&action=simple_form&editor_language=$editor_language&ft=error&fm=no_file_uploaded";
					header("Location: $url");
					exit;
					break;
				}
			} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		}
		else{
			$url = "index.php?open=media&page=upload_image&action=simple_form&editor_language=$editor_language&ft=error&fm=invalid_file_type&file_type=$file_type";
			header("Location: $url");
			exit;
		}
	}
	else{
		echo"?";	
	}
}
elseif($action == "new_directory"){
	if($process == "1"){
		$inp_image_path_title = $_POST['inp_image_path_title'];
		$inp_image_path_title = strtolower($inp_image_path_title);
		$inp_image_path_title = str_replace(" ", "_", $inp_image_path_title);
		$inp_image_path_title = clean($inp_image_path_title);
		$inp_image_path_title = output_html($inp_image_path_title);
		if(empty($inp_image_path_title)){
			header("Location: index.php?open=media&page=upload_image&action=new_directory&editor_language=$editor_language&ft=error&fm=directory_name_cant_be_empty");
			exit;
		}
		$inp_image_path_title_mysql = quote_smart($link, $inp_image_path_title);

		$inp_image_path_parent_id = $_POST['inp_image_path_parent_id'];
		$inp_image_path_parent_id = output_html($inp_image_path_parent_id);
		$inp_image_path_parent_id_mysql = quote_smart($link, $inp_image_path_parent_id);


		// Create dir
		if($inp_image_path_parent_id == "0"){
			$inp_image_path_path = $inp_image_path_title . "/_img";
			$inp_image_path_path = output_html($inp_image_path_path);
			$inp_image_path_path_mysql = quote_smart($link, $inp_image_path_path);

			if(!(is_dir("../$inp_image_path_title"))){
				mkdir("../$inp_image_path_title");
			}
			if(!(is_dir("../$inp_image_path_path"))){
				mkdir("../$inp_image_path_path");
			}
		}
		else{
			// Get parent
			$query = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path  FROM $t_images_paths WHERE image_path_id=$inp_image_path_parent_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_a_image_path_id, $get_a_image_path_title, $get_a_image_path_parent_id, $get_a_image_path_path) = $row;
		
			$inp_image_path_path_a_without_img = str_replace("_img", "", $get_a_image_path_path);
			$inp_image_path_path = $inp_image_path_path_a_without_img . $inp_image_path_title . "/_img";
			$inp_image_path_path = output_html($inp_image_path_path);
			$inp_image_path_path_mysql = quote_smart($link, $inp_image_path_path);


			if(!(is_dir("../$inp_image_path_path_a_without_img$inp_image_path_title"))){
				mkdir("../$inp_image_path_path_a_without_img$inp_image_path_title");
				//echo"mkdir $inp_image_path_path_a_without_img$inp_image_path_title<br />";
			}
			if(!(is_dir("../$inp_image_path_path"))){
				mkdir("../$inp_image_path_path");
			}
		} // has parent

		mysqli_query($link, "INSERT INTO $t_images_paths
		(image_path_id, image_path_title, image_path_parent_id, image_path_path) 
		VALUES 
		(NULL,  $inp_image_path_title_mysql, $inp_image_path_parent_id_mysql, $inp_image_path_path_mysql)")
		or die(mysqli_error($link));

		header("Location: index.php?open=media&page=upload_image&action=new_directory&editor_language=$editor_language&ft=success&fm=directory_created");
		exit;
		
	}
	echo"
	<h2>$l_new_directory</h2>

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


	<form action=\"index.php?open=media&amp;page=upload_image&amp;action=new_directory&amp;editor_language=$editor_language&amp;process=1\" method=\"post\" enctype=\"multipart/form-data\">
		
		<p><b>$l_directory_name</b><br />
		<input type=\"text\" name=\"inp_image_path_title\" size=\"40\" />
		</p>

		<p><b>$l_parent</b><br />
		<select name=\"inp_image_path_parent_id\">
			<option value=\"0\" selected=\"selected\">$l_this_is_parent</option>
			<option value=\"0\">-</option>";
		
		
			$names = array();
			$parents = array();
			$children = array();

			$query_a = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path FROM $t_images_paths WHERE image_path_parent_id='0'";
			$result_a = mysqli_query($link, $query_a);
			while($row_a = mysqli_fetch_row($result_a)) {
				list($get_a_image_path_id, $get_a_image_path_title, $get_a_image_path_parent_id, $get_a_image_path_path) = $row_a;
				echo"			<option value=\"$get_a_image_path_id\">$get_a_image_path_title</option>";
			
						
				$query_b = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path FROM $t_images_paths WHERE image_path_parent_id='$get_a_image_path_id'";
				$result_b = mysqli_query($link, $query_b);
				while($row_b = mysqli_fetch_row($result_b)) {
					list($get_b_image_path_id, $get_b_image_path_title, $get_b_image_path_parent_id, $get_b_image_path_path) = $row_b;
					echo"			<option value=\"$get_b_image_path_id\">&nbsp; $get_b_image_path_title</option>";
	
					$query_c = "SELECT image_path_id, image_path_title, image_path_parent_id, image_path_path FROM $t_images_paths WHERE image_path_parent_id='$get_b_image_path_id'";
					$result_c = mysqli_query($link, $query_c);
					while($row_c = mysqli_fetch_row($result_c)) {
						list($get_c_image_path_id, $get_c_image_path_title, $get_c_image_path_parent_id, $get_c_image_path_path) = $row_c;
						echo"			<option value=\"$get_c_image_path_id\">&nbsp; &nbsp; $get_c_image_path_title</option>";

					
					}

				}

			}

		echo"
		</select>
		</p>
		

		<p>
		<input type=\"submit\" value=\"$l_create\" class=\"submit\" />
		</p>
	</form>


	<p>
	<a href=\"index.php?open=media&amp;page=upload_image&amp;editor_language=$editor_language\">$l_back</a>
	</p>

	";
}
?>