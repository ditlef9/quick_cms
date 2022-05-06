<?php
/**
*
* File: _admin/_inc/social_media/sites.php
* Version 10:36 17.01.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions ----------------------------------------------------------------------- */
include("_functions/get_extension.php");


/*- Tables ---------------------------------------------------------------------------- */
$t_social_media 	= $mysqlPrefixSav . "social_media";
$t_social_media_sites	= $mysqlPrefixSav . "social_media_sites";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['site_id'])){
	$site_id = $_GET['site_id'];
	$site_id = output_html($site_id);
}
else{
	$site_id = "";
}
$tabindex = 0;

if($action == ""){
	echo"
	<h1>Social media sites</h1>

	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_site&amp;editor_language=$editor_language\" class=\"btn btn_default\">New</a>
	</p>

	<!-- List all sosial media sites -->
		<div class=\"vertical\">
			<ul>
		
	";
	
	$query = "SELECT site_id, site_title, site_logo FROM $t_social_media_sites";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_site_id, $get_site_title, $get_site_logo) = $row;

		echo"
				<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_site&amp;site_id=$get_site_id&amp;editor_language=$editor_language\"><img src=\"../_uploads/social_media_sites/$get_site_logo\" alt=\"$get_site_logo\" /> $get_site_title</a></li>
		";
	}
	echo"
			</ul>
		</div>
	<!-- //List all sosial media -->
	";
} // action == ""
elseif($action == "new_site"){
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		// Check if it exists
		$query = "SELECT site_id FROM $t_social_media_sites WHERE site_title=$inp_title_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_site_id) = $row;
		if($get_site_id != ""){
			$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=error&fm=site_already_exists&title=$inp_title";
			header("Location: $url");
			exit;
		}

		// Insert
		mysqli_query($link, "INSERT INTO $t_social_media_sites
		(site_id, site_title, site_logo) 
		VALUES 
		(NULL, $inp_title_mysql, '')")
		or die(mysqli_error($link));

		
		// Get ID
		$query = "SELECT site_id FROM $t_social_media_sites WHERE site_title=$inp_title_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_site_id) = $row;

		// Dir
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/social_media_sites"))){
			mkdir("../_uploads/social_media_sites");
		}

		// Logo
		$image = $_FILES['inp_image']['name'];
				
		$filename = stripslashes($_FILES['inp_image']['name']);
		$extension = get_extension($filename);
		$extension = strtolower($extension);

		$ft_image = "";
           	$fm_image = "";
		if($image){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft_image = "warning";
				$fm_image = "unknown_file_format";
			}
			else{
       
				$size = filesize($_FILES['inp_image']['tmp_name']);
				$tmp_name = $_FILES["inp_image"]["tmp_name"];
				$target_file = "../_uploads/social_media_sites/". $get_site_id . "." . $extension;

				if(move_uploaded_file($tmp_name, "$target_file")){
 
					list($width,$height) = @getimagesize($target_file);

					if($width == "" OR $height == ""){
						$ft_image = "warning";
						$fm_image = "photo_could_not_be_uploaded_please_check_file_size";
						unlink("$target_file");
					}
					else{
					

						// Inp site logo
						$inp_site_logo = $get_site_id . "." . $extension;
						$inp_site_logo_mysql = quote_smart($link, $inp_site_logo);
	
						// Update MySQL
						$result = mysqli_query($link, "UPDATE $t_social_media_sites SET site_logo=$inp_site_logo_mysql WHERE site_id=$get_site_id");



						// Send feedback
						$ft_image = "success";
						$fm_image  = "image_uploaded";
					}  // if($width == "" OR $height == ""){
				} // move uploaded file
			}
		} // if($image){
		else{
			switch ($_FILES['inp_image']['error']) {
				case UPLOAD_ERR_OK:
					$ft_image = "warning";
           				$fm_image = "photo_unknown_error";
					break;
				case UPLOAD_ERR_NO_FILE:
					$ft_image = "warning";
           				$fm_image = "no_file_selected";
					break;
				case UPLOAD_ERR_INI_SIZE:
					$ft_image = "warning";
           				$fm_image = "photo_exceeds_filesize";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$ft_image = "warning";
           				$fm_image = "photo_exceeds_filesize_form";
					break;
				default:
					$ft_image = "warning";
           				$fm_image = "unknown_upload_error";
					break;
			}
						
				
		}


		// Send feedback
		$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=success&fm=site_created&ft_image=$ft_image&fm_image=$fm_image";
		header("Location: $url");
		exit;
		
	} // process
	echo"
	<h1>New social media site</h1>

				
	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->


	<!-- Form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Logo 134x30:</b><br />
		<input name=\"inp_image\" type=\"file\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->

	<!-- Back -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\"></a>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Sites</a>
		</p>
	<!-- //Back -->
	";
} // new site
elseif($action == "edit_site"){
	$site_id_mysql = quote_smart($link, $site_id);
	$query = "SELECT site_id, site_title, site_logo FROM $t_social_media_sites WHERE site_id=$site_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_site_id, $get_site_title, $get_site_logo) = $row;
	if($get_site_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\"></a>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Sites</a>
		</p>
		";
	}
	else{
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			// Update title
			$result = mysqli_query($link, "UPDATE $t_social_media_sites SET site_title=$inp_title_mysql WHERE site_id=$get_site_id");

		
			// Dir
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/social_media_sites"))){
				mkdir("../_uploads/social_media_sites");
			}

			// Logo
			$image = $_FILES['inp_image']['name'];
					
			$filename = stripslashes($_FILES['inp_image']['name']);
			$extension = get_extension($filename);
			$extension = strtolower($extension);

			$ft_image = "";
           		$fm_image = "";
			if($image){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_image = "warning";
					$fm_image = "unknown_file_format";
				}
				else{
					$size = filesize($_FILES['inp_image']['tmp_name']);
					$tmp_name = $_FILES["inp_image"]["tmp_name"];
					$target_file = "../_uploads/social_media_sites/". $get_site_id . "." . $extension;

					if(move_uploaded_file($tmp_name, "$target_file")){
 
						list($width,$height) = @getimagesize($target_file);

						if($width == "" OR $height == ""){
							$ft_image = "warning";
							$fm_image = "photo_could_not_be_uploaded_please_check_file_size";
							unlink("$target_file");
						}
						else{
					

							// Inp site logo
							$inp_site_logo = $get_site_id . "." . $extension;
							$inp_site_logo_mysql = quote_smart($link, $inp_site_logo);

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_social_media_sites SET site_logo=$inp_site_logo_mysql WHERE site_id=$get_site_id");
		
							// Send feedback
							$ft_image = "success";
							$fm_image  = "image_uploaded";
						}  // if($width == "" OR $height == ""){
					}
				}
			} // if($image){
			else{
				switch ($_FILES['inp_image']['error']) {
					case UPLOAD_ERR_OK:
					$ft_image = "warning";
           				$fm_image = "photo_unknown_error";
					break;
					case UPLOAD_ERR_NO_FILE:
					$ft_image = "warning";
           				$fm_image = "no_file_selected";
					break;
					case UPLOAD_ERR_INI_SIZE:
					$ft_image = "warning";
           				$fm_image = "photo_exceeds_filesize";
					break;
					case UPLOAD_ERR_FORM_SIZE:
					$ft_image = "warning";
           				$fm_image = "photo_exceeds_filesize_form";
					break;
					default:
					$ft_image = "warning";
           				$fm_image = "unknown_upload_error";
					break;
				}
						
				
			}


			// Send feedback
			$url = "index.php?open=$open&page=$page&action=$action&site_id=$site_id&editor_language=$editor_language&ft=success&fm=changes_saved&ft_image=$ft_image&fm_image=$fm_image";
			header("Location: $url");
			exit;

		} // process == 1
		
		echo"
		<h1>$get_site_title</h1>

				
		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;site_id=$site_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_site_title\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Logo:</b><br />
			<img src=\"../_uploads/social_media_sites/$get_site_logo\" alt=\"$get_site_logo\" />
			</p>

			<p><b>New logo 134x30:</b><br />
			<input name=\"inp_image\" type=\"file\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"Save changes\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_site&amp;site_id=$site_id&amp;editor_language=$editor_language\" class=\"btn_warning\">Delete</a>
			</p>
			</form>
		<!-- //Form -->

		<!-- Back -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\"></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Sites</a>
			</p>
		<!-- //Back -->
		";
	} // found
} // edit site
elseif($action == "delete_site"){
	$site_id_mysql = quote_smart($link, $site_id);
	$query = "SELECT site_id, site_title, site_logo FROM $t_social_media_sites WHERE site_id=$site_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_site_id, $get_site_title, $get_site_logo) = $row;
	if($get_site_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\"></a>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Sites</a>
		</p>
		";
	}
	else{
		if($process == "1"){

			// Delete site
			$result = mysqli_query($link, "DELETE FROM $t_social_media_sites WHERE site_id=$get_site_id");

			// Delete logo
			if(file_exists("../_uploads/social_media_sites/$get_site_logo") && $get_site_logo != ""){
				unlink("../_uploads/social_media_sites/$get_site_logo");
			}
			
			// Send feedback
			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&ft=success&fm=site_deleted";
			header("Location: $url");
			exit;

		} // process == 1
		
		echo"
		<h1>$get_site_title</h1>

				
		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Form -->
			<p>
			Are you sure you want to delete the social media site?
			</p>
			
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_site&amp;site_id=$site_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Do delete</a>
			</p>
			</form>
		<!-- //Form -->

		<!-- Back -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\"></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Sites</a>
			</p>
		<!-- //Back -->
		";
	} // found
} // edit site
?>