<?php
/**
*
* File: users/index.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_status - $l_users";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */

/*- Variables ------------------------------------------------------------------------- */
if (isset($_GET['status_id'])) {
	$status_id = $_GET['status_id'];
	$status_id = stripslashes(strip_tags($status_id));
}
else{
	$status_id = "";
}
if (isset($_GET['refer'])) {
	$refer = $_GET['refer'];
	$refer = stripslashes(strip_tags($refer));
	$refer = str_replace("amp;", "&", $refer);
}
else{
	$refer = "";
}



if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id = output_html($user_id);
	$user_id_mysql = quote_smart($link, $user_id);

	$security = $_SESSION['security'];
	$security= output_html($security);
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_alias, user_language, user_date_format, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name, $get_user_alias, $get_user_language, $get_user_date_format, $get_user_rank) = $row;

	$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	if($action == "rotate_image"){

		// Get that status
		$status_id_mysql = quote_smart($link, $status_id);

		$query = "SELECT status_id, status_user_id, status_text, status_photo, status_datetime, status_language, status_likes, status_comments, status_reported, status_reported_checked FROM $t_users_status WHERE status_id=$status_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_status_id, $get_status_user_id, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked) = $row;
	
		if($get_status_id == ""){
			echo"
			<h1>Server error 404</h1>

			<div class=\"alert alert-danger\" role=\"alert\">
				<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
				<span class=\"sr-only\">Error:</span>
				<span>$l_status_not_found</span>
			</div>

			<p>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\"><img src=\"$root/_webdesign/images/icons/16x16/go-previous.png\" style=\"float: left;padding: 0px 4px 0px 0px;\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\">$l_status</a>
			</p>
			";
		}
		else{
			if($get_user_id != "$get_status_user_id"){
				echo"
				<h1>Server error 403</h1>

				<div class=\"alert alert-danger\" role=\"alert\">
					<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
					<span class=\"sr-only\">Error:</span>
					<span>$l_access_denied</span>
				</div>
				<p>$l_only_administrator_moderator_editor_and_the_owner_can_edit</p>
				";
			}
			else{
				
				if($get_status_photo == ""){
					$url = "index.php?category=users&page=edit_status&ft=warning&fm=photo_not_found_in_database&l=$l"; 
					header("Location: $url");
					die;
				}
				// Roate
				
				// Get extention
				function getExtension($str) {
					$i = strrpos($str,".");
					if (!$i) { return ""; } 
					$l = strlen($str) - $i;
					$ext = substr($str,$i+1,$l);
					return $ext;
				}
				$extension = getExtension($get_status_photo);
				$extension = strtolower($extension);

				
				// Get a new name
				$datetime = date("ymdhis");
				$new_name = "$root/_scripts/users/images/$user_id/status_" . $get_status_id . "_" . $datetime . "." . $extension;

				// To MySQL
				$inp_status_photo = "_scripts/users/images/$user_id/status_" . $get_status_id . "_" . $datetime . "." . $extension;
				$inp_status_photo_mysql = quote_smart($link, $inp_status_photo);

				// Update table
				$result = mysqli_query($link, "UPDATE $t_users_status SET status_photo=$inp_status_photo_mysql WHERE status_id='$get_status_id'");
			

				// Rename
				rename("$root/$get_status_photo", "$new_name");

				

				
				if($extension == "jpg"){
					// Load
					$source = imagecreatefromjpeg("$new_name");

					// Rotate
					if(isset($rotate)){
						$rotate = imagerotate($source, -90, 0);
					}
					else{
						$rotate = imagerotate($source, 90, 0);
					}

					// Save
					imagejpeg($rotate, "$new_name");
				}
				elseif($extension == "png"){
					// Load
					$source = imagecreatefrompng("$new_name");

					// Bg
					$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);

					// Rotate
					if(isset($rotate)){
						$rotate = imagerotate($source, -90, $bgColor);
					}
					else{
						$rotate = imagerotate($source, 90, $bgColor);
					}
	
					// Save
					imagesavealpha($rotate, true);
					imagepng($rotate, "$new_name");

				}

		
				// Free the memory
				imagedestroy($source);



				// Header
				$url = "index.php?category=users&page=edit_status&l=$l&ft=success&fm=photo_rotated";

				if($refer != ""){
					$url = $url . "&" . $refer;
				}

				$url = $url . "#status$status_id";
				header("Location: $url");
				die;
			}
		}
	} // rotate_image
	elseif($action == "do_edit_status"){
		// Get that status
		$status_id_mysql = quote_smart($link, $status_id);

		$query = "SELECT status_id, status_user_id, status_text, status_photo, status_datetime, status_language, status_likes, status_comments, status_reported, status_reported_checked FROM $t_users_status WHERE status_id=$status_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_status_id, $get_status_user_id, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked) = $row;
	
		if($get_status_id == ""){
			echo"
			<h1>Server error 404</h1>

			<div class=\"alert alert-danger\" role=\"alert\">
				<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
				<span class=\"sr-only\">Error:</span>
				<span>$l_status_not_found</span>
			</div>

			<p>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\"><img src=\"$root/_webdesign/images/icons/16x16/go-previous.png\" style=\"float: left;padding: 0px 4px 0px 0px;\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\">$l_status</a>
			</p>
			";
		}
		else{
			if($get_user_id != "$get_status_user_id"){
				echo"
				<h1>Server error 403</h1>

				<div class=\"alert alert-danger\" role=\"alert\">
					<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
					<span class=\"sr-only\">Error:</span>
					<span>$l_access_denied</span>
				</div>
				<p>$l_only_administrator_moderator_editor_and_the_owner_can_edit</p>
				";
			}
			else{

				// Get new status
				$inp_status = $_POST['inp_status'];
				$inp_status = output_html($inp_status);
				$inp_status_mysql = quote_smart($link, $inp_status);
				if(empty($inp_status)){
					$url = "index.php?category=users&page=edit_status&action=edit_status&status_id=$status_id&ft=warning&fm=please_enter_some_text";
					header("Location: $url");
					exit;
				}
				if($inp_status == "$l_whats_up"){
					$url = "index.php?category=users&page=edit_status&action=edit_status&status_id=$status_id&ft=warning&fm=status_can_not_be_whats_up";
					header("Location: $url");
					exit;
				}
		
				/* Update text */
				$result = mysqli_query($link, "UPDATE $t_users_status SET status_text=$inp_status_mysql WHERE status_id=$status_id_mysql");

				/* Any changes to image? */
				

				// Get extention
				function getExtension($str) {
					$i = strrpos($str,".");
					if (!$i) { return ""; } 
					$l = strlen($str) - $i;
					$ext = substr($str,$i+1,$l);
					return $ext;
				}

				$image = $_FILES['inp_image']['name'];
				
				$filename = stripslashes($_FILES['inp_image']['name']);
				$extension = getExtension($filename);
				$extension = strtolower($extension);

				if($image){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft = "warning";
						$fm = "unknown_file_format";
						$url = "index.php?category=users&page=edit_status&action=edit_status&status_id=$status_id&l=$l&ft=warning&fm=$fm&status=$inp_status"; 
						header("Location: $url");
						exit;
					}
					else{
						$size=filesize($_FILES['inp_image']['tmp_name']);

						if($extension == "jpg" OR $extension == "png" OR $extension == "gif"){
							$uploadedfile = $_FILES['inp_image']['tmp_name'];
							
							// Width and height
							list($width,$height) = @getimagesize($uploadedfile);

					

							// Destination file
							$date = date("ymdhis");
							$uploadfile = "$root/_scripts/users/images/$user_id/status_". $get_status_id . "_" . $date . "." . $extension;

					

							// Reisize?
							$resize = 0;

							// Max width = 900
							if($width > 900){
								$resize = 1;
								$newwidth=970;
								$newheight= round(($height/$width)*$newwidth, 0);
							}
							else{
								$newwidth=$width;
								$newheight=$height;
							}
							if($resize == 1){
								echo"resize to $newwidth to $newheight";
							

								if($extension=="jpg" || $extension=="jpeg" ){
									ini_set ('gd.jpeg_ignore_warning', 1);
									error_reporting(0);
									$src = imagecreatefromjpeg($uploadedfile);
								}
								elseif($extension=="png"){
									$src = imagecreatefrompng($uploadedfile);
								}
								else{
									$src = @imagecreatefromgif($uploadedfile);
								}
								$tmp_org = imagecreatetruecolor($newwidth,$newheight);
								imagecopyresampled($tmp_org,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
								$filename = "$root/_scripts/users/images/$user_id/status_". $get_status_id . "_" . $date . "_resized." . $extension;

								if($extension == "png"){
									imagepng($tmp_org,$filename);
								}
								elseif($extension == "gif"){
									imagegif($tmp_org,$filename);
								}
								else{
									imagejpeg($tmp_org,$filename,100);
								}
					
								imagedestroy($tmp_org);


								// Update MySQL
								$inp_status_photo = "_scripts/users/images/$user_id/status_". $get_status_id . "_" . $date . "_resized." . $extension;
								$inp_status_photo_mysql = quote_smart($link, $inp_status_photo);
								$result = mysqli_query($link, "UPDATE $t_users_status SET status_photo=$inp_status_photo_mysql WHERE status_id='$get_status_id'");
			
								// Unlink old image
								if($get_photo_destination != ""){
									unlink("$root/_scripts/users/images/$get_user_id/$get_photo_destination");
								}

							} // resize == 1
							else{
								if (move_uploaded_file($_FILES['inp_image']['tmp_name'], $uploadfile)) {
									// Send feedback
									$ft = "success";
									$fm = "photo_uploaded";

									// Update MySQL
									$inp_status_photo = "_scripts/users/images/$user_id/status_". $get_status_id . "." . $extension;
									$inp_status_photo_mysql = quote_smart($link, $inp_status_photo);
									$result = mysqli_query($link, "UPDATE $t_users_status SET status_photo=$inp_status_photo_mysql WHERE status_id='$get_status_id'");
		
								}
								else {
									$ft = "warning";
									$fm = "photo_could_not_be_uploaded_please_check_file_size";
					
									$url = "index.php?category=users&page=edit_status&action=edit_status&status_id=$status_id&l=$l&ft=warning&fm=$fm&status=$inp_status"; 
									header("Location: $url");
									exit;
								}
							}// resize == 0
						}
						else{
							$ft = "warning";
							$fm = "file_format_must_be_jpg_png_or_gif";
					
							$url = "index.php?category=users&page=edit_status&action=edit_status&status_id=$status_id&l=$l&ft=$ft&fm=$fm"; 
							header("Location: $url");
							exit;

						}
					}
				} // if($image){


				$url = "index.php?category=users&page=edit_status&l=$l&ft=success&fm=changes_saved#status$status_id";
				header("Location: $url");
				exit;

			} // if($get_user_id == "$get_status_user_id"){
		} // if($get_status_id != ""){
	} // do_edit_status
	elseif($action == "edit_status"){
		// Get that status
		$status_id_mysql = quote_smart($link, $status_id);

		$query = "SELECT status_id, status_user_id, status_text, status_photo, status_datetime, status_language, status_likes, status_comments, status_reported, status_reported_checked FROM $t_users_status WHERE status_id=$status_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_status_id, $get_status_user_id, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked) = $row;
	
		if($get_status_id == ""){
			echo"
			<h1>Server error 404</h1>

			<div class=\"alert alert-danger\" role=\"alert\">
				<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
				<span class=\"sr-only\">Error:</span>
				<span>$l_status_not_found</span>
			</div>

			<p>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\"><img src=\"$root/_webdesign/images/icons/16x16/go-previous.png\" style=\"float: left;padding: 0px 4px 0px 0px;\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\">$l_status</a>
			</p>
			";
		}
		else{
			if($get_user_id != "$get_status_user_id"){
				echo"
				<h1>Server error 403</h1>

				<div class=\"alert alert-danger\" role=\"alert\">
					<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
					<span class=\"sr-only\">Error:</span>
					<span>$l_access_denied</span>
				</div>
				<p>$l_only_administrator_moderator_editor_and_the_owner_can_edit</p>
				";
			}
			else{

				// Get profile image
				$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql AND photo_profile_image='1'";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_photo_id, $get_photo_destination) = $rowb;

				echo"
				<h1>$l_edit</h1>

				<!-- Feedback -->
					";
					if($ft != "" && $fm != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						elseif($fm == "please_enter_some_text"){
							$fm = "$l_please_enter_some_text";
						}
						elseif($fm == "status_can_not_be_whats_up"){
							$fm = "$l_status_can_not_be_whats_up";
						}
						elseif($fm == "photo_not_found_in_database"){
							$fm = "$l_photo_not_found_in_database";
						}
						elseif($fm == "photo_rotated"){
							$fm = "$l_photo_rotated";
						}
						elseif($fm == "unknown_file_format"){
							$fm = "$l_unknown_file_format";
						}
						elseif($fm == "photo_uploaded"){
							$fm = "$l_photo_uploaded";
						}
						elseif($fm == "file_format_must_be_jpg_png_or_gif"){
							$fm = "$l_file_format_must_be_jpg_png_or_gif";
						}
						elseif($fm == "status_deleted"){
							$fm = "$l_status_deleted";
						}
						else{
							$fm = "$ft";
						}
						echo"<div class=\"$ft\"><p>$fm</p></div>";
					}
					echo"
				<!-- //Feedback -->


				<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_status\"]').focus();
				});
				</script>
				<!-- //Focus -->

				<!-- Show post for editing -->
				";

					$date_day = substr($get_status_datetime, 8, 2);
					$date_month = substr($get_status_datetime, 5, 2);
					$date_year = substr($get_status_datetime, 0, 4);

					if($get_user_date_format == "l jS \of F Y" OR $get_user_date_format == "l d. f Y"){
						if($date_day < 10){
							$date_day = substr($date_day, 1, 1);
						}
						if($date_month == "01"){
							$date_month_saying = "$l_month_january";
						}
						elseif($date_month == "02"){
							$date_month_saying = "$l_month_february";
						}
						elseif($date_month == "03"){
							$date_month_saying = "$l_month_march";
						}
						elseif($date_month == "04"){
							$date_month_saying = "$l_month_april";
						}
						elseif($date_month == "05"){
							$date_month_saying = "$l_month_may";
						}
						elseif($date_month == "06"){
							$date_month_saying = "$l_month_june";
						}
						elseif($date_month == "07"){
							$date_month_saying = "$l_month_juli";
						}
						elseif($date_month == "08"){
							$date_month_saying = "$l_month_august";
						}
						elseif($date_month == "09"){
							$date_month_saying = "$l_month_september";
						}
						elseif($date_month == "10"){
							$date_month_saying = "$l_month_october";
						}
						elseif($date_month == "11"){
							$date_month_saying = "$l_month_november";
						}
						else{
							$date_month_saying = "$l_month_december";
						}

						if($get_user_date_format == "l jS \of F Y"){
							$date_saying = $date_day . "th of " . $date_month_saying . " " . $date_year;
						}
						elseif($get_user_date_format == "l d. f Y"){
							$date_month_saying = strtolower($date_month_saying);
							$date_saying = $date_day . ". " . $date_month_saying . " " . $date_year;
						}

					} // l jS \of F Y and l d. f Y
					else{
						$date_saying = $date_year . "-" . $date_month . "-" . $date_day;
				
					}

					echo"
					<div class=\"subcell\" style=\"padding: 0px 8px 0px 8px;\">
						<p style=\"float: left;padding: 10px 8px 10px 0px;margin:0;\">
						";
						if($get_photo_destination == ""){ 
							echo"	<img src=\"$root/_webdesign/images/avatar_blank_64.png\" alt=\"avatar_blank_64.png\" class=\"image_rounded\" />"; 
						} 
						else{ 
							echo"	<img src=\"$root/image.php?width=64&amp;height=64&amp;cropratio=1:1&amp;image=/_scripts/users/images/$get_user_id/$get_photo_destination\" alt=\"avatar_blank_64.png\" class=\"image_rounded\" />"; 
						} 
						echo"
						</p>
						<div style=\"overflow: hidden;\">
							<p style=\"float: left;padding:0;margin: 8px 0px 8px 0px;\">
							<a href=\"index.php?category=users&amp;page=view_profile&amp;user_id=$get_user_id&amp;l=$l\" style=\"font-weight:bold;color:#000;\">$get_user_alias</a>
					
							<span class=\"dark_grey\">@$get_user_name
							&middot; $date_saying</span>
							</p>

							<!-- Menu -->
								<div style=\"float: left;margin: 4px 0px 0px 4px;\">
									<div class=\"dropdown\" style=\"position: absolute;margin-left: 10px;\">
										<button class=\"btn btn-default btn-xs dropdown-toggle\" type=\"button\" id=\"dropdownMenu1\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\" style=\"padding: 3px 5px 3px 5px;\">
										<span class=\"caret\"></span>
										</button>
										<ul class=\"dropdown-menu\" aria-labelledby=\"dropdownMenu1\">
											<li><a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l#status$get_status_id\"><span class=\"glyphicon glyphicon-chevron-left\"></span> $l_show_status</a></li>
											<li><a href=\"index.php?category=users&amp;page=edit_status&amp;action=edit_status&amp;status_id=$get_status_id&amp;l=$l\"><span class=\"glyphicon glyphicon-pencil\"></span> $l_edit</a></li>
											<li><a href=\"index.php?category=users&amp;page=edit_status&amp;action=rotate_image&amp;status_id=$get_status_id&amp;l=$l&amp;process=1\"><span class=\"glyphicon glyphicon-repeat\"></span> $l_rotate_image</a></li>
											<li role=\"separator\" class=\"divider\"></li>
											<li><a href=\"index.php?category=users&amp;page=delete_status&amp;status_id=$get_status_id&amp;l=$l\"><span class=\"glyphicon glyphicon-remove\"></span> $l_delete_status</a></li>
											<li><a href=\"index.php?category=users&amp;page=delete_status_image&amp;status_id=$get_status_id&amp;l=$l\"><span class=\"glyphicon glyphicon-remove-sign\"></span> $l_delete_image</a></li>
										</ul>
									</div>
								</div>
								<div style=\"clear:left;\"></div>
							<!-- //Menu -->
			
				
							<!-- Edit status form -->
								<div class=\"clear\"></div>
								<form method=\"POST\" action=\"index.php?category=users&amp;page=edit_status&amp;action=do_edit_status&amp;status_id=$status_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

								<div style=\"min-width: 490px;position: relative;display: inline-block;\">
									<p style=\"padding: 0px 0px 3px 0px;margin:0;\">
									<textarea name=\"inp_status\" rows=\"5\" cols=\"50\" id=\"inp_status\" style=\"width:490px;\">$get_status_text</textarea><br />
									</p>
		
									<div class=\"left\">
										<div class=\"fileUpload btn btn-default\">
    											<span class=\"glyphicon glyphicon-picture\" aria-hidden=\"true\"></span> <span>$l_image</span>
											<input type=\"file\" id=\"uploadBtn\" class=\"upload\" name=\"inp_image\" />
										</div>
										<input id=\"uploadFile\" placeholder=\"Choose File\" disabled=\"disabled\" style=\"display: none;\" />


									</div>

									<div style=\"float:right;\">
										<span><input type=\"submit\" value=\"$l_save_changes\" class=\"btn btn-success\" style=\"padding: 4px 6px 4px 6px;\" /></span>
									</div>
								</div>
	
								</form>
	
								<script type=\"text/javascript\">  
								\$(document).ready(function() {
 
									document.getElementById(\"uploadBtn\").onchange = function () {
										document.getElementById(\"uploadFile\").value = this.value;
										\$(\"#uploadFile\").toggle();
									};

								});
								</script>


								<!-- Photo -->
								";
								if($get_status_photo != ""){
									echo"
									<p>
									<img src=\"$root/image.php?width=400&amp;height=300&amp;image=/$get_status_photo\" alt=\"$get_status_photo\" class=\"image\" />
									</p>
									";
								}
								echo"
								<!-- //Photo -->
							<!-- //Edit status form -->
	
						
						</div>
					</div>
				<!-- //Show post for editing -->
				<p>
				<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\"><img src=\"$root/_webdesign/images/icons/16x16/go-previous.png\" style=\"float: left;padding: 0px 4px 0px 0px;\" alt=\"go-previous.png\" /></a>
				<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\">$l_status</a>
				</p>
				";
			} // Access

		} // if($get_status_id != ""){
	} // edit_status
	elseif($action == "save"){

		$inp_user_id_mysql = quote_smart($link, $get_user_id);
		$inp_language_mysql = quote_smart($link, $get_user_language);
		$inp_datetime = date("Y-m-d H:i:s");

		/* Status */
		$inp_status = $_POST['inp_status'];
		$inp_status = output_html($inp_status);
		$inp_status_mysql = quote_smart($link, $inp_status);
		if(empty($inp_status)){
			$url = "index.php?category=users&page=edit_status&l=$l&ft=warning&fm=please_enter_some_text";
			if($refer != ""){
				$url = $url . "&" . $refer;
			}
			header("Location: $url");
			exit;
		}
		if($inp_status == "$l_whats_up"){
			$url = "index.php?category=users&page=edit_status&l=$l&ft=warning&fm=status_can_not_be_whats_up";
			if($refer != ""){
				$url = $url . "&" . $refer;
			}
			header("Location: $url");
			exit;
		}
		
		/* Insert */
		mysqli_query($link, "INSERT INTO $t_users_status
		(status_id, status_user_id, status_text, status_datetime, status_language, status_likes, status_comments) 
		VALUES 
		(NULL, $inp_user_id_mysql, $inp_status_mysql, '$inp_datetime', $inp_language_mysql, '0', '0')")
		or die(mysqli_error($link));

		/* Get status ID */
		$query = "SELECT status_id FROM $t_users_status WHERE status_user_id=$user_id_mysql AND status_datetime='$inp_datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_status_id) = $row;
	

	
		/* Image */
		// Create folders
		if(!(is_dir("$root/_scripts/users/"))){
			mkdir("$root/_scripts/users/", 0777);
		}
		if(!(is_dir("$root/_scripts/users/images"))){
			mkdir("$root/_scripts/users/images", 0777);
		}
		if(!(is_dir("$root/_scripts/users/images/$user_id"))){
			mkdir("$root/_scripts/users/images/$user_id", 0777);
		}
		
		// Get extention
		function getExtension($str) {
			$i = strrpos($str,".");
			if (!$i) { return ""; } 
			$l = strlen($str) - $i;
			$ext = substr($str,$i+1,$l);
			return $ext;
		}

		$image = $_FILES['inp_image']['name'];
				
		$filename = stripslashes($_FILES['inp_image']['name']);
		$extension = getExtension($filename);
		$extension = strtolower($extension);

		if($image){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft = "warning";
				$fm = "unknown_file_format";
				$url = "index.php?category=users&page=edit_status&l=$l&ft=warning&fm=$fm&status=$inp_status"; 
				header("Location: $url");
				exit;
			}
			else{
				$size=filesize($_FILES['inp_image']['tmp_name']);

				if($extension == "jpg" OR $extension == "png" OR $extension == "gif"){
					$uploadedfile = $_FILES['inp_image']['tmp_name'];
							
					// Width and height
					list($width,$height) = @getimagesize($uploadedfile);

					

					// Destination file
					$uploadfile = "$root/_scripts/users/images/$user_id/status_". $get_status_id . "." . $extension;

					

					// Reisize?
					$resize = 0;

					// Max width = 900
					if($width > 900){
						$resize = 1;
						$newwidth=970;
						$newheight= round(($height/$width)*$newwidth, 0);
					}
					else{
						$newwidth=$width;
						$newheight=$height;
					}
					if($resize == 1){
						echo"resize to $newwidth to $newheight";
							

						if($extension=="jpg" || $extension=="jpeg" ){
							ini_set ('gd.jpeg_ignore_warning', 1);
							error_reporting(0);
							$src = imagecreatefromjpeg($uploadedfile);
						}
						elseif($extension=="png"){
							$src = imagecreatefrompng($uploadedfile);
						}
						else{
							$src = @imagecreatefromgif($uploadedfile);
						}
						$tmp_org = imagecreatetruecolor($newwidth,$newheight);
						imagecopyresampled($tmp_org,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
						$filename = "$root/_scripts/users/images/$user_id/status_". $get_status_id . "_resized." . $extension;

						if($extension == "png"){
							imagepng($tmp_org,$filename);
						}
						elseif($extension == "gif"){
							imagegif($tmp_org,$filename);
						}
						else{
							imagejpeg($tmp_org,$filename,100);
						}
					
						imagedestroy($tmp_org);


						// Update MySQL
						$inp_status_photo = "_scripts/users/images/$user_id/status_". $get_status_id . "_resized." . $extension;
						$inp_status_photo_mysql = quote_smart($link, $inp_status_photo);
						$result = mysqli_query($link, "UPDATE $t_users_status SET status_photo=$inp_status_photo_mysql WHERE status_id='$get_status_id'");
		

					} // resize == 1
					else{
						if (move_uploaded_file($_FILES['inp_image']['tmp_name'], $uploadfile)) {
							// Send feedback
							$ft = "success";
							$fm = "photo_uploaded";

							// Insert
							$inp_status_photo = "_scripts/users/images/$user_id/status_". $get_status_id . "." . $extension;
							$inp_status_photo_mysql = quote_smart($link, $inp_status_photo);


							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_users_status SET status_photo=$inp_status_photo_mysql WHERE status_id='$get_status_id'");
		
						}
						else {
							$ft = "warning";
							$fm = "photo_could_not_be_uploaded_please_check_file_size";
					
							$url = "index.php?category=users&page=edit_status&l=$l&ft=warning&fm=$fm&status=$inp_status";
							if($refer != ""){
								$url = $url . "&" . $refer;
							}
							header("Location: $url");
							exit;
						}
					}// resize == 0
				}
				else{
					$ft = "warning";
					$fm = "file_format_must_be_jpg_png_or_gif";
					
					$url = "index.php?category=users&page=edit_status&l=$&ft=$ft&fm=$fm"; 
					if($refer != ""){
						$url = $url . "&" . $refer;
					}
					header("Location: $url");
					exit;

				}
			}
		} // if($image){


		$url = "index.php?category=users&page=edit_status&l=$l&ft=success&fm=changes_saved";
		if($refer != ""){
			$url = $url . "&" . $refer;
		}
		header("Location: $url");
		exit;
	}
	elseif($action == ""){
		echo"
		<h1>$l_status</h1>


		
		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "please_enter_some_text"){
					$fm = "$l_please_enter_some_text";
				}
				elseif($fm == "status_can_not_be_whats_up"){
					$fm = "$l_status_can_not_be_whats_up";
				}
				elseif($fm == "photo_not_found_in_database"){
					$fm = "$l_photo_not_found_in_database";
				}
				elseif($fm == "photo_rotated"){
					$fm = "$l_photo_rotated";
				}
				elseif($fm == "unknown_file_format"){
					$fm = "$l_unknown_file_format";
				}
				elseif($fm == "photo_uploaded"){
					$fm = "$l_photo_uploaded";
				}
				elseif($fm == "file_format_must_be_jpg_png_or_gif"){
					$fm = "$l_file_format_must_be_jpg_png_or_gif";
				}
				elseif($fm == "status_image_deleted"){
					$fm = "$l_status_image_deleted";
				}
				elseif($fm == "status_deleted"){
					$fm = "$l_status_deleted";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->



		<!-- Add status form -->
			<form method=\"POST\" action=\"index.php?category=users&amp;page=edit_status&amp;action=save&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

			<div style=\"min-width: 490px;position: relative;display: inline-block;\">
				<p style=\"padding: 0px 0px 3px 0px;margin:0;\">$l_whats_up<br />
				<textarea name=\"inp_status\" rows=\"5\" cols=\"50\" id=\"inp_status\" style=\"width:490px;\"></textarea><br />
				</p>
		
				<div class=\"left\">
					<div class=\"fileUpload btn btn-default\">
    						<span class=\"glyphicon glyphicon-picture\" aria-hidden=\"true\"></span> <span>$l_image</span>
						<input type=\"file\" id=\"uploadBtn\" class=\"upload\" name=\"inp_image\" />
					</div>
					<input id=\"uploadFile\" placeholder=\"Choose File\" disabled=\"disabled\" style=\"display: none;\" />
				</div>

				<div style=\"float:right;\">
					<span><input type=\"submit\" value=\"$l_publish\" class=\"btn btn-success\" style=\"padding: 4px 6px 4px 6px;\" /></span>
				</div>
			
			</div>
	
			</form>
	
			<script type=\"text/javascript\">  
			\$(document).ready(function() {
 
				document.getElementById(\"uploadBtn\").onchange = function () {
					document.getElementById(\"uploadFile\").value = this.value;
					\$(\"#uploadFile\").toggle();
				};
				\$('[name=\"inp_status\"]').focus();

			});
			</script>
		<!-- //Add status form -->


		<!-- Statuses -->
			";
			// User
			$my_user_id = $_SESSION['user_id'];
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Get profile image
			$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_photo_id, $get_photo_destination) = $rowb;
			

			// Get many rows
			$query = "SELECT * FROM $t_users_status WHERE status_user_id=$my_user_id_mysql ORDER BY status_id DESC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_status_id, $get_status_user_id, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked) = $row;

				
				$date_day = substr($get_status_datetime, 8, 2);
				$date_month = substr($get_status_datetime, 5, 2);
				$date_year = substr($get_status_datetime, 0, 4);

				if($get_user_date_format == "l jS \of F Y" OR $get_user_date_format == "l d. f Y"){
					if($date_day < 10){
						$date_day = substr($date_day, 1, 1);
					}
					if($date_month == "01"){
						$date_month_saying = "$l_month_january";
					}
					elseif($date_month == "02"){
						$date_month_saying = "$l_month_february";
					}
					elseif($date_month == "03"){
						$date_month_saying = "$l_month_march";
					}
					elseif($date_month == "04"){
						$date_month_saying = "$l_month_april";
					}
					elseif($date_month == "05"){
						$date_month_saying = "$l_month_may";
					}
					elseif($date_month == "06"){
						$date_month_saying = "$l_month_june";
					}
					elseif($date_month == "07"){
						$date_month_saying = "$l_month_juli";
					}
					elseif($date_month == "08"){
						$date_month_saying = "$l_month_august";
					}
					elseif($date_month == "09"){
						$date_month_saying = "$l_month_september";
					}
					elseif($date_month == "10"){
						$date_month_saying = "$l_month_october";
					}
					elseif($date_month == "11"){
						$date_month_saying = "$l_month_november";
					}
					else{
						$date_month_saying = "$l_month_december";
					}

					if($get_user_date_format == "l jS \of F Y"){
						$date_saying = $date_day . "th of " . $date_month_saying . " " . $date_year;
					}
					elseif($get_user_date_format == "l d. f Y"){
						$date_month_saying = strtolower($date_month_saying);
						$date_saying = $date_day . ". " . $date_month_saying . " " . $date_year;
					}

				} // l jS \of F Y and l d. f Y
				else{
						$date_saying = $date_year . "-" . $date_month . "-" . $date_day;
				
				}

				echo"
				<a id=\"status$get_status_id\"></a>
				<div class=\"clear\" style=\"height:14px;\"></div>
				<div class=\"subcell\" style=\"padding: 0px 8px 0px 8px;\">
					<p style=\"float: left;padding: 10px 8px 10px 0px;margin:0;\">
					";
					if($get_photo_destination == ""){ 
						echo"	<img src=\"$root/_webdesign/images/avatar_blank_64.png\" alt=\"avatar_blank_64.png\" class=\"image_rounded\" />"; 
					} 
					else{ 
						echo"	<img src=\"$root/image.php?width=64&amp;height=64&amp;cropratio=1:1&amp;image=/_scripts/users/images/$get_user_id/$get_photo_destination\" alt=\"$get_photo_destination\" class=\"image_rounded\" />"; 
					} 
					echo"
					</p>
					<div style=\"overflow: hidden;\">
						<p style=\"float: left;padding:0;margin: 8px 0px 8px 0px;\">
						<a href=\"index.php?category=users&amp;page=view_profile&amp;user_id=$get_user_id&amp;l=$l\" style=\"font-weight:bold;color:#000;\">$get_user_alias</a>
					
						<span class=\"dark_grey\">@$get_user_name
						&middot; $date_saying</span>
						</p>
						<!-- Menu -->
							<div style=\"float: left;margin: 4px 0px 0px 4px;\">
								<div class=\"dropdown\" style=\"position: absolute;margin-left: 10px;\">
									<button class=\"btn btn-default btn-xs dropdown-toggle\" type=\"button\" id=\"dropdownMenu1\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\" style=\"padding: 3px 5px 3px 5px;\">
									<span class=\"caret\"></span>
									</button>
									<ul class=\"dropdown-menu\" aria-labelledby=\"dropdownMenu1\">
										<li><a href=\"index.php?category=users&amp;page=edit_status&amp;action=edit_status&amp;status_id=$get_status_id&amp;l=$l\"><span class=\"glyphicon glyphicon-pencil\"></span> $l_edit</a></li>
										<li><a href=\"index.php?category=users&amp;page=edit_status&amp;action=rotate_image&amp;status_id=$get_status_id&amp;process=1&amp;l=$l\"><span class=\"glyphicon glyphicon-repeat\"></span> $l_rotate_image</a></li>
										<li role=\"separator\" class=\"divider\"></li>
										<li><a href=\"index.php?category=users&amp;page=delete_status&amp;status_id=$get_status_id&amp;l=$l\"><span class=\"glyphicon glyphicon-remove\"></span> $l_delete_status</a></li>
										<li><a href=\"index.php?category=users&amp;page=delete_status_image&amp;status_id=$get_status_id&amp;l=$l\"><span class=\"glyphicon glyphicon-remove-sign\"></span> $l_delete_image</a></li>
										
									</ul>
								</div>
							</div>
							<div style=\"clear:left;\"></div>
						<!-- //Menu -->
			
		
						<p style=\"clear:left;margin-top: 0px;\">$get_status_text</p>
						

						";

						// Attachment?
						if($get_status_photo != ""){
							if(file_exists("$root/$get_status_photo")){
								echo"<p><img src=\"$root/image.php?width=400&amp;height=300&amp;image=/$get_status_photo\" alt=\"$get_status_photo\" class=\"image\" /></p>";
							}
							else{
								echo"<p><b>Error:</b> Image not found. (<a href=\"$root/$get_status_photo\">$root/$get_status_photo</a>) Deleting it from database.</p>";
								// $res = mysqli_query($link, "UPDATE $t_users_status SET status_photo='' WHERE status_id='$get_status_id'");
							}
						}

						echo"

						<!-- Like and add comment -->
							<p>
							";

							// Did I like this?
							$q = "SELECT sl_id FROM $t_users_status_likes WHERE sl_status_id='$get_status_id' AND sl_user_id=$my_user_id_mysql";
							$r = mysqli_query($link, $q);
							$rowb = mysqli_fetch_row($r);
							list($get_sl_id) = $rowb;
						
							if($get_sl_id == ""){
								echo"
								<a href=\"index.php?category=users&amp;page=like_status&amp;status_id=$get_status_id&amp;l=$l&amp;process=1\" class=\"dark_grey\"><img src=\"$root/_webdesign/images/icons/16x16/heart_grey.png\" alt=\"hearth_grey.png\" /> $l_like</a>
								";
							}
							else{
								echo"
								<a href=\"index.php?category=users&amp;page=like_status&amp;status_id=$get_status_id&amp;l=$l&amp;process=1\"><img src=\"$root/_webdesign/images/icons/16x16/heart_fill.png\" alt=\"hearth_fill.png\" /> $l_like</a>
								";
							}
							echo"

							<a href=\"index.php?page=view_profile&amp;user_id=$my_user_id&amp;mode=comment_status&amp;status_id=$get_status_id&amp;l=$l#comment_status$get_status_id\" class=\"dark_grey\" style=\"margin-left: 10px;\"><img src=\"$root/_webdesign/images/icons/16x16/comment_grey.png\" alt=\"comment_grey.png\" /> $l_comment</a>
							</p>
						<!-- //Like and add comment -->

						<!-- Who liked this post? -->
							";

							if($get_status_likes != 0){
								echo"
								<p>
								";
								$x = 0;
								$query = "SELECT sl_user_id, sl_user_alias FROM $t_users_status_likes WHERE sl_status_id='$get_status_id'";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_sl_user_id, $get_sl_user_alias) = $row;
									echo"							";
									echo"<a href=\"index.php?category=users&amp;page=view_profile&amp;user_id=$get_sl_user_id&amp;l=$l\">$get_sl_user_alias</a>";
		
									if($get_status_likes > 1){
										
									}
									$x++;
								}
								echo"
								$l_likes_this_lcfirst
								</p>
								";
							}
							echo"
						<!-- //Who liked this post? -->

					</div>
				</div>
				
				";
			}
			echo"
		<!-- //Statuses -->
		";
	} // action == ""
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
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>