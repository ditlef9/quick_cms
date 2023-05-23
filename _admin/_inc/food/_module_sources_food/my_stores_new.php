<?php 
/**
*
* File: food/store_new.php
* Version 1.0.0
* Date 23:59 27.11.2017
* Copyright (c) 2011-2017 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_food.php");


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_index.php");


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_store - $get_current_title_value";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	if($process == "1"){
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);
		if(empty($inp_name)){
			$ft = "error";
			$fm = "missing_name";
		}

		$inp_country = $_POST['inp_country'];
		$inp_country = output_html($inp_country);
		$inp_country_mysql = quote_smart($link, $inp_country);
		if(empty($inp_country)){
			$ft = "error";
			$fm = "missing_country";
		}

		$inp_website = $_POST['inp_website'];
		$inp_website = output_html($inp_website);
		$len = strlen($inp_website);
		if($len > 5){
			$start = substr($inp_website, 0, 4);
			if($start != "http"){
				$inp_website = "http://" . $inp_website;
			}
		}
		$inp_website_mysql = quote_smart($link, $inp_website);
		if(empty($inp_website)){
			$ft = "error";
			$fm = "missing_website";
		}

		


		if($ft == ""){
			if($l == ""){
				echo"Missing l";die;
			}
			$inp_l = output_html($l);
			$inp_l_mysql = quote_smart($link, $inp_l);
					
			$inp_my_user_id = $_SESSION['user_id'];
			$inp_my_user_id = output_html($inp_my_user_id);
			$inp_my_user_id_mysql = quote_smart($link, $inp_my_user_id);

			// IP 
			$inp_my_ip = $_SERVER['REMOTE_ADDR'];
			$inp_my_ip = output_html($inp_my_ip);
			$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);

			// Datetime (notes)
			$datetime = date("Y-m-d H:i:s");
			$datetime_print = date("j M Y");

			mysqli_query($link, "INSERT INTO $t_food_stores
				(store_id, store_user_id, store_name, store_country, 
				store_language, store_website, store_logo, store_added_datetime, 
				store_added_datetime_print, store_updatet_datetime, store_updatet_datetime_print, store_user_ip, 
				store_reported, store_reported_checked) 
				VALUES 
				(NULL, $inp_my_user_id_mysql, $inp_name_mysql, $inp_country_mysql,
				$inp_l_mysql, $inp_website_mysql, '', '$datetime',
				'$datetime_print', '$datetime', '$datetime_print', $inp_my_ip_mysql, 
				'0', '')")
				or die(mysqli_error($link));

			// Get _id
			$query = "SELECT store_id, store_name FROM $t_food_stores WHERE store_user_id=$inp_my_user_id_mysql AND store_name=$inp_name_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_store_id, $get_current_store_name) = $row;

			// Dir
			if(!(is_dir("$root/_uploads"))){
				mkdir("$root/_uploads");
			}
			if(!(is_dir("$root/_uploads/food"))){
				mkdir("$root/_uploads/food");
			}
			if(!(is_dir("$root/_uploads/food/stores"))){
				mkdir("$root/_uploads/food/stores");
			}


					// Logo
					$ft_logo = "";
					$fm_logo = "";
					$image = $_FILES['inp_logo']['name'];
					$uploadedfile = $_FILES['inp_logo']['tmp_name'];
					$filename = stripslashes($_FILES['inp_logo']['name']);
					$extension = get_extension($filename);
					$extension = strtolower($extension);

					if($image){

						if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
							$ft_logo = "warning";
							$fm_logo = "logo_has_unknown_file_format";
						}
						else{
							// Check mime
							$check = getimagesize($_FILES["inp_logo"]["tmp_name"]);
							$mime = $check["mime"];
							if (($mime != "image/jpg") && ($mime != "image/jpge") && ($mime != "image/png") && ($mime != "image/gif")) {
								$mime_clean = clean($mime);
								$ft_logo = "warning";
								$fm_logo = "logo_has_unknown_mime_format_$mime_clean";
							}
							else{
								// Upload image
								$datetime = date("ymdhis");
								$storename_clean = clean($get_current_store_name);
								$target_file = "$root/_uploads/food/stores/$storename_clean" . "_logo_" . $my_user_id . "_" . $datetime . "." . $extension;
								if (move_uploaded_file($_FILES["inp_logo"]["tmp_name"], $target_file)) {

									// Width and height
									list($width,$height) = @getimagesize($target_file);
									if($width == "" OR $height == ""){
										$ft_logo = "warning";
										$fm_logo = "logo_could_not_be_uploaded_please_check_file_size";
										unlink("$target_file");
									}
									else{
										// Keep orginal
										if($width > 1281){
											$newwidth=1280;
											$newheight=round(($height/$width)*$newwidth, 0);
											$tmp_org =imagecreatetruecolor($newwidth,$newheight);


											// Create image
											if($extension=="jpg" || $extension=="jpeg" ){
												ini_set ('gd.jpeg_ignore_warning', 1);
												error_reporting(0);
												$src = imagecreatefromjpeg($uploadedfile);
											}
											elseif($extension=="png"){
												$src = @imagecreatefrompng($uploadedfile);
											}
											else{
												$src = @imagecreatefromgif($uploadedfile);
											}
 

											imagecopyresampled($tmp_org,$src,0,0,0,0,$newwidth,$newheight, $width,$height);

											if($extension=="jpg" || $extension=="jpeg" ){
												imagejpeg($tmp_org, $target_file,80);
											}
											elseif($extension=="png"){
												imagepng($tmp_org, $target_file);
											}
											else{
												imagegif($tmp_org, $target_file);
											}
					
											imagedestroy($tmp_org);

										} // width exceeds max width

										// Update MySQL
										$inp_logo = "$storename_clean" . "_logo_" . $my_user_id . "_" . $datetime . "." . $extension;
										$inp_logo_mysql = quote_smart($link, $inp_logo);
									
										mysqli_query($link, "UPDATE $t_food_stores SET
											store_logo=$inp_logo_mysql
											WHERE store_id=$get_current_store_id") or die(mysqli_error($link));
						
										// Send feedback
										$ft_logo = "success";
										$fm_logo = "logo_uploaded";

									} // widht and height ok
								} // move_uploaded_file
								else{
									switch ($_FILES['inp_logo']['error']) {
										case UPLOAD_ERR_OK:
											$ft_logo = "error";
           										$fm_logo = "logo_unknown_error";
											break;
										case UPLOAD_ERR_NO_FILE:
											$ft_logo = "info";
           										$fm_logo = "no_logo_file_selected";
											break;
										case UPLOAD_ERR_INI_SIZE:
											$ft_logo = "error";
           										$fm_logo = "logo_exceeds_filesize";
											break;
										case UPLOAD_ERR_FORM_SIZE:
											$ft_logo = "error";
           										$fm_logo = "logo_exceeds_filesize_form";
											break;
										default:
											$ft_logo = "error";
           										$fm_logo = "unknown_logo_upload_error";
											break;
									} // switch
								} // move_uploaded_file failed
							} // ok mime
						} // ok extension
					} // if($image){


					// Icon
					$ft_icon_18x18 = "";
					$fm_icon_18x18 = "";
					$image = $_FILES['inp_icon_18x18']['name'];
					$uploadedfile = $_FILES['inp_icon_18x18']['tmp_name'];
					$filename = stripslashes($_FILES['inp_icon_18x18']['name']);
					$extension = get_extension($filename);
					$extension = strtolower($extension);

					if($image){

						if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
							$ft_icon_18x18 = "warning";
							$fm_icon_18x18 = "icon_18x18_has_unknown_file_format";
						}
						else{
							// Check mime
							$check = getimagesize($_FILES["inp_icon_18x18"]["tmp_name"]);
							$mime = $check["mime"];
							if (($mime != "image/jpg") && ($mime != "image/jpeg") && ($mime != "image/png") && ($mime != "image/gif")) {
								$ft_logo = "warning";
								$fm_logo = "icon_18x18_has_unknown_mime_format";
							}
							else{
								// Upload icon
								$datetime = date("ymdhis");
								$storename_clean = clean($get_current_store_name);
								$target_file = "$root/_uploads/food/stores/$storename_clean" . "_icon_18x18_" . $my_user_id . "_" . $datetime . "." . $extension;
								if (move_uploaded_file($_FILES["inp_icon_18x18"]["tmp_name"], $target_file)) {



									// Width and height
									list($width,$height) = @getimagesize($target_file);
									if($width == "" OR $height == ""){
										$ft_icon_18x18 = "warning";
										$fm_icon_18x18 = "icon_18x18_could_not_be_uploaded_please_check_file_size";
										unlink("$target_file");
									}
									else{

										// Keep orginal
										if($width > 19){
											resize_crop_image(18, 18, $target_file, $target_file); // $max_width, $max_height, $source_file, $dst_dir, $quality = 80){
										}

										// Update MySQL
										$inp_icon_18x18 = "$storename_clean" . "_icon_18x18_" . $my_user_id . "_" . $datetime . "." . $extension;
										$inp_icon_18x18_mysql = quote_smart($link, $inp_icon_18x18);
										
										mysqli_query($link, "UPDATE $t_food_stores SET
											store_icon_18x18=$inp_icon_18x18_mysql
											WHERE store_id=$get_current_store_id") or die(mysqli_error($link));
								
										// Send feedback
										$ft_icon_18x18 = "success";
										$fm_icon_18x18 = "icon_18x18_uploaded";
									} // widht and height ok
								} // move_uploaded_file
								else{
									switch ($_FILES['inp_icon_18x18']['error']) {
									case UPLOAD_ERR_OK:
										$ft_icon_18x18 = "error";
           									$fm_icon_18x18 = "icon_18x18_unknown_error";
										break;
									case UPLOAD_ERR_NO_FILE:
										$ft_icon_18x18 = "info";
           									$fm_icon_18x18 = "no_icon_18x18_selected";
										break;
									case UPLOAD_ERR_INI_SIZE:
										$ft_icon_18x18 = "error";
           									$fm_icon_18x18 = "icon_18x18_exceeds_filesize";
										break;
									case UPLOAD_ERR_FORM_SIZE:
										$ft_icon_18x18 = "error";
           									$fm_icon_18x18 = "icon_18x18_exceeds_filesize_form";
										break;
									default:
										$ft_icon_18x18 = "error";
           									$fm_icon_18x18 = "unknown_icon_18x18_upload_error";
										break;
									} // switch
								} // move_uploaded_file failed
							} // ok mime
						} // ok extension
					} // if($image){

			$url = "my_stores.php?l=$l&ft=success&fm=store_added";
			if($fm_logo != "" && $ft_logo != ""){
				$url = $url . "&fm_logo=$fm_logo&ft_logo=$ft_logo";
			}
			if($fm_icon_18x18 != "" && $ft_icon_18x18 != ""){
				$url = $url . "&fm_icon_18x18=$fm_icon_18x18&ft_icon_18x18=$ft_icon_18x18";
			}
			header("Location: $url");
			exit;
		}
		else{
			$url = "my_stores_new.php?l=$l";
			$url = $url . "&ft=$ft&fm=$fm";
			$url = $url . "&inp_name=$inp_name";
			$url = $url . "&inp_country=$inp_country";
			$url = $url . "&inp_website=$inp_website";

			header("Location: $url");
			exit;
		}	
	} // process == 1

	echo"
	<h1>$l_new_store</h1>
	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "missing_energy"){
				$fm = "Please enter energy";
			}
			elseif($fm == "missing_proteins"){
				$fm = "Please enter proteins";
			}
			elseif($fm == "missing_carbohydrates"){
				$fm = "Please enter carbohydrates";
			}
			elseif($fm == "missing_fat"){
				$fm = "Please enter fat";
			}
			else{
					$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";	
		}
			if(isset($_GET['ft_logo']) && isset($_GET['fm_logo'])){
				$ft = $_GET['ft_logo'];
				$ft = strip_tags(stripslashes($ft));
				if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
					echo"Server error 403 feedback error";die;
				}

				$fm = $_GET['fm_logo'];
				$fm = output_html($fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";	
			}
			if(isset($_GET['ft_icon_18x18']) && isset($_GET['fm_icon_18x18'])){
				$ft = $_GET['ft_icon_18x18'];
				$ft = strip_tags(stripslashes($ft));
				if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
					echo"Server error 403 feedback error";die;
				}

				$fm = $_GET['fm_icon_18x18'];
				$fm = output_html($fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";	
			}
		echo"
	<!-- //Feedback -->

	<!-- New store form -->
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
		</script>
		<!-- //Focus -->
		<form method=\"post\" action=\"my_stores_new.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">


		<p>$l_name*:<br />
		<input type=\"text\" name=\"inp_name\" value=\"";
		if(isset($_GET['inp_name'])){
			$inp_name= $_GET['inp_name'];
			$inp_name = output_html($inp_name);
			echo"$inp_name";
		}
		echo"\" size=\"25\" /></p>

		<p>$l_country*:<br />
		<select name=\"inp_country\">";
			if(isset($_GET['inp_country'])){
				$inp_country = $_GET['inp_country'];
				$inp_country = output_html($inp_country);
			}
			else{
				// Fetch last country used
				$query = "SELECT store_country FROM $t_food_stores ORDER BY store_id DESC LIMIT 0,1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($inp_country) = $row;
			}
			$query = "SELECT country_id, country_name FROM $t_languages_countries ORDER BY country_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_country_id, $get_country_name) = $row;

				echo"			";
				echo"<option value=\"$get_country_name\""; if(isset($inp_country) && $inp_country == "$get_country_name"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
				
		}
		echo"
		</select>

		<p>$l_website*:<br />
		<input type=\"text\" name=\"inp_website\" value=\"";
		if(isset($_GET['inp_website'])){
			$inp_website= $_GET['inp_website'];
			$inp_website = output_html($inp_website);
			echo"$inp_website";
		}
		echo"\" size=\"25\" /></p>



		<p>$l_logo (1280x720):<br />\n";
		echo"
		<input type=\"file\" name=\"inp_logo\" />
		</p>

		<p>$l_icon 18x18:<br />\n";
		echo"
		<input type=\"file\" name=\"inp_icon_18x18\" />
		</p>

		<p><input type=\"submit\" value=\"$l_save_store\" class=\"btn_default\" /></p>
	<!-- //New store form -->
		
	";
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/food/new_food.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>