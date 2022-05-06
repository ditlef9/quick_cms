<?php
/**
*
* File: _admin/_inc/settings/logo.php
* Version 1.0.0
* Date 16:45 04.03.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}
if(isset($_GET['type'])) {
	$type = $_GET['type'];
	$type = strip_tags(stripslashes($type));
}
else{
	$type = "";
}


if($action == ""){
	echo"
	<h1>$l_logo</h1>

	
	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->


	";

	// Show logo 
	if(file_exists("_data/logo.php")){
		include("_data/logo.php");




		echo"

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=upload&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">

		<!-- General -->
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 4px;\">
				<p><b>$l_general (about 150x50 px)</b></p>
				<!-- Feedback -->
				";
				if(isset($_GET['ft_general']) && isset($_GET['fm_general'])){
					$ft = $_GET['ft_general'];
					$ft = output_html($ft);

					$fm = $_GET['fm_general'];
					$fm = output_html($fm);
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
					echo"<div class=\"$ft \"><span>$fm</span></div>";
				}
				echo"
				<!-- //Feedback -->
			
				<span>
				<input type=\"file\" name=\"inp_image_general\" />
				</span>
			  </td>
			  <td style=\"vertical-align: top;\">
				<p>
				";
				if(file_exists("../$logoPathSav/$logoFileSav") && $logoFileSav != ""){
					echo"<img src=\"../$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" />\n";
				}
				echo"
				</p>
			  </td>
			 </tr>
			</table>
			<hr />
		<!-- //General -->
			


		<!-- Email -->
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 4px;\">
				
				<p><b>$l_email (about 150x50 px)</b></p>
				<!-- Feedback -->
				";
				if(isset($_GET['ft_email']) && isset($_GET['fm_email'])){
					$ft = $_GET['ft_email'];
					$ft = output_html($ft);

					$fm = $_GET['fm_email'];
					$fm = output_html($fm);
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
					echo"<div class=\"$ft \"><span>$fm</span></div>";
				}
				echo"
				<!-- //Feedback -->
			
				<span>
				<input type=\"file\" name=\"inp_image_email\" />
				</span>
			  </td>
			  <td style=\"vertical-align: top;\">
				<p>
				";
				if(file_exists("../$logoPathSav/$logoFileEmailSav") && $logoFileEmailSav != ""){
					echo"<img src=\"../$logoPathSav/$logoFileEmailSav\" alt=\"$logoFileEmailSav\" />\n";
				}
				echo"
				</p>
			  </td>
			 </tr>
			</table>
			<hr />
		<!-- //Email -->



		<!-- PDF -->
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 4px;\">
				
				<p><b>$l_pdf (about 150x50 px)</b></p>
				<!-- Feedback -->
				";
				if(isset($_GET['ft_pdf']) && isset($_GET['fm_pdf'])){
					$ft = $_GET['ft_pdf'];
					$ft = output_html($ft);

					$fm = $_GET['fm_pdf'];
					$fm = output_html($fm);
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
					echo"<div class=\"$ft \"><span>$fm</span></div>";
				}
				echo"
				<!-- //Feedback -->
			
				<span>
				<input type=\"file\" name=\"inp_image_pdf\" />
				</span>
			  </td>
			  <td style=\"vertical-align: top;\">
				<p>
				";
				if(file_exists("../$logoPathSav/$logoFilePdfSav") && $logoFilePdfSav != ""){
					echo"<img src=\"../$logoPathSav/$logoFilePdfSav\" alt=\"$logoFilePdfSav\" />\n";
				}
				echo"
				</p>
			  </td>
			 </tr>
			</table>
			<hr />
		<!-- //PDF -->

		<!-- Stamp images -->
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 4px;\">
				
				<p><b>Stamp images 1280x720</b></p>
				<!-- Feedback -->
				";
				if(isset($_GET['ft_stamp_images_1280x720']) && isset($_GET['fm_stamp_images_1280x720'])){
					$ft = $_GET['ft_stamp_images_1280x720'];
					$ft = output_html($ft);

					$fm = $_GET['fm_stamp_images_1280x720'];
					$fm = output_html($fm);
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
					echo"<div class=\"$ft \"><span>$fm</span></div>";
				}
				echo"
				<!-- //Feedback -->
			
				<span>
				<input type=\"file\" name=\"inp_image_stamp_images_1280x720\" />
				</p>
			  </td>
			  <td style=\"vertical-align: top;\">
				<p>
				";
				if(file_exists("../$logoPathSav/$logoFileStampImages1280x720Sav") && $logoFileStampImages1280x720Sav != ""){
					echo"<img src=\"../$logoPathSav/$logoFileStampImages1280x720Sav\" alt=\"$logoFileStampImages1280x720Sav\" />\n";
				}
				echo"
				</p>
			  </td>
			 </tr>
			</table>
			<hr />
		<!-- //Stamp images -->

		<!-- Stamp images -->
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 4px;\">
				
				<p><b>Stamp images 1920x1080</b></p>
				<!-- Feedback -->
				";
				if(isset($_GET['ft_stamp_images_1920x1080']) && isset($_GET['fm_stamp_images_1920x1080'])){
					$ft = $_GET['ft_stamp_images_1920x1080'];
					$ft = output_html($ft);

					$fm = $_GET['fm_stamp_images_1920x1080'];
					$fm = output_html($fm);
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
					echo"<div class=\"$ft \"><span>$fm</span></div>";
				}
				echo"
				<!-- //Feedback -->
			
				<span>
				<input type=\"file\" name=\"inp_image_stamp_images_1920x1080\" />
				</span>
			  </td>
			  <td style=\"vertical-align: top;\">
				<p>
				";
				if(file_exists("../$logoPathSav/$logoFileStampImages1920x1080Sav") && $logoFileStampImages1920x1080Sav != ""){
					echo"<img src=\"../$logoPathSav/$logoFileStampImages1920x1080Sav\" alt=\"$logoFileStampImages1920x1080Sav\" />\n";
				}
				echo"
				</p>
			  </td>
			 </tr>
			</table>
			<hr />
		<!-- //Stamp images -->

		<!-- Stamp images -->
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 4px;\">
				
				<p><b>Stamp images 2560x1440</b></p>
				<!-- Feedback -->
				";
				if(isset($_GET['ft_stamp_images_2560x1440']) && isset($_GET['fm_stamp_images_2560x1440'])){
					$ft = $_GET['ft_stamp_images_2560x1440'];
					$ft = output_html($ft);

					$fm = $_GET['fm_stamp_images_2560x1440'];
					$fm = output_html($fm);
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
					echo"<div class=\"$ft \"><span>$fm</span></div>";
				}
				echo"
				<!-- //Feedback -->
			
				<span>
				<input type=\"file\" name=\"inp_image_stamp_images_2560x1440\" />
				</span>
			  </td>
			  <td style=\"vertical-align: top;\">
				<p>
				";
				if(file_exists("../$logoPathSav/$logoFileStampImages2560x1440Sav") && $logoFileStampImages2560x1440Sav != ""){
					echo"<img src=\"../$logoPathSav/$logoFileStampImages2560x1440Sav\" alt=\"$logoFileStampImages2560x1440Sav\" />\n";
				}
				echo"
				</p>
			  </td>
			 </tr>
			</table>
			<hr />
		<!-- //Stamp images -->


		<!-- Stamp images -->
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 4px;\">
				
				<p><b>Stamp images 7680x4320</b></p>
				<!-- Feedback -->
				";
				if(isset($_GET['ft_stamp_images_7680x4320']) && isset($_GET['fm_stamp_images_7680x4320'])){
					$ft = $_GET['ft_stamp_images_7680x4320'];
					$ft = output_html($ft);

					$fm = $_GET['fm_stamp_images_7680x4320'];
					$fm = output_html($fm);
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
					echo"<div class=\"$ft \"><span>$fm</span></div>";
				}
				echo"
				<!-- //Feedback -->
			
				<span>
				<input type=\"file\" name=\"inp_image_stamp_images_7680x4320\" />
				</span>
			  </td>
			  <td style=\"vertical-align: top;\">
				<p>
				";
				if(file_exists("../$logoPathSav/$logoFileStampImages7680x4320Sav") && $logoFileStampImages7680x4320Sav != ""){
					echo"<img src=\"../$logoPathSav/$logoFileStampImages7680x4320Sav\" alt=\"$logoFileStampImages7680x4320Sav\" />\n";
				}
				echo"
				</p>
			  </td>
			 </tr>
			</table>
			<hr />
		<!-- //Stamp images -->

		<p>
		<input type=\"submit\" value=\"Upload\" class=\"btn_default\" />
		</p>
		</form>
		";
	}


}
elseif($action == "upload"){
	echo"
	<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $l_logo</h1>
	";

	if(file_exists("_data/logo.php")){
		include("_data/logo.php");
	}

	$images_array = array('inp_image_general', 'inp_image_email', 'inp_image_pdf', 'inp_image_stamp_images_1280x720', 'inp_image_stamp_images_1920x1080', 'inp_image_stamp_images_2560x1440', 'inp_image_stamp_images_7680x4320');
		
	// Finnes mappen?
	$upload_path = "../_uploads/logo/";

	if(!(is_dir("../_uploads/"))){
		mkdir("../_uploads/");
	}
	if(!(is_dir("../_uploads/logo/"))){
		mkdir("../_uploads/logo/");
	}
	$ft = "";
	$fm = "";

	// Header of flat file
	$input_logo_file="<?php
\$logoPathSav = \"_uploads/logo\";";


	for($x=0;$x<sizeof($images_array);$x++){

		// Type
		$type = str_replace("inp_image_", "", $images_array[$x]);

		// Feedback
		$ft = $ft . "&ft_" . $type . "=";
		$fm = $fm . "&fm_" . $type . "=";

		// Sjekk filen
		$file_name = basename($_FILES[$images_array[$x]]['name']);
		$file_exp = explode('.', $file_name); 
		$file_type = $file_exp[count($file_exp) -1]; 
		$file_type = strtolower("$file_type");

		// Sett variabler
		$random = rand(0, 100);
		$new_name = $configWebsiteTitleCleanSav . "_" . $type . "_" . $random . ".png";

		$target_path = $upload_path . "/" . $new_name;

		// Sjekk om det er en OK filendelse
		if($file_name != ""){
			if($file_type == "jpg" OR $file_type == "png" OR $file_type == "gif"){
			if(move_uploaded_file($_FILES[$images_array[$x]]['tmp_name'], $target_path)) {

				// Sjekk om det faktisk er et bilde som er lastet opp
				$image_size = getimagesize($target_path);
				if(is_numeric($image_size[0]) && is_numeric($image_size[1])){

					// Image size
					list($width,$height) = getimagesize($target_path);


					// Dette bildet er OK
					
					if($type == "general"){
						// Delete old
						if(file_exists("../$logoPathSav/$logoFileSav") && $logoFileSav != ""){
							unlink("../$logoPathSav/$logoFileSav");
						}
						$input_logo_file= $input_logo_file ."
\$logoFileSav = \"$new_name\";
";
					}
					elseif($type == "email"){
						// Delete old
						if(file_exists("../$logoPathSav/$logoFileEmailSav") && $logoFileEmailSav != ""){
							unlink("../$logoPathSav/$logoFileEmailSav");
						}
						$input_logo_file= $input_logo_file ."
\$logoFileEmailSav = \"$new_name\";
";
					}
					elseif($type == "pdf"){
						// Delete old
						if(file_exists("../$logoPathSav/$logoFilePdfSav") && $logoFilePdfSav != ""){
							unlink("../$logoPathSav/$logoFilePdfSav");
						}
						$input_logo_file= $input_logo_file ."
\$logoFilePdfSav = \"$new_name\";
";
					}
					elseif($type == "stamp_images_1280x720"){
						// Delete old
						if(file_exists("../$logoPathSav/$logoFileStampImages1280x720Sav") && $logoFileStampImages1280x720Sav != ""){
							unlink("../$logoPathSav/$logoFileStampImages1280x720Sav");
						}
						$input_logo_file= $input_logo_file ."
\$logoFileStampImages1280x720Sav = \"$new_name\";
";
					}
					elseif($type == "stamp_images_1920x1080"){
						// Delete old
						if(file_exists("../$logoPathSav/$logoFileStampImages1920x1080Sav") && $logoFileStampImages1920x1080Sav != ""){
							unlink("../$logoPathSav/$logoFileStampImages1920x1080Sav");
						}
						$input_logo_file= $input_logo_file ."
\$logoFileStampImages1920x1080Sav = \"$new_name\";
";
					}
					elseif($type == "stamp_images_2560x1440"){
						// Delete old
						if(file_exists("../$logoPathSav/$logoFileStampImages2560x1440Sav") && $logoFileStampImages2560x1440Sav != ""){
							unlink("../$logoPathSav/$logoFileStampImages2560x1440Sav");
						}
						$input_logo_file= $input_logo_file ."
\$logoFileStampImages2560x1440Sav = \"$new_name\";
";
					}
					elseif($type == "stamp_images_7680x4320"){
						// Delete old
						if(file_exists("../$logoPathSav/$logoFileStampImages7680x4320Sav") && $logoFileStampImages7680x4320Sav != ""){
							unlink("../$logoPathSav/$logoFileStampImages7680x4320Sav");
						}
						$input_logo_file= $input_logo_file ."
\$logoFileStampImages7680x4320Sav = \"$new_name\";
";
					}


					$ft = $ft . "success";
					$fm = $fm . "logo_" . $type . "uploaded";
					$mode = "";
					
				}
				else{
					// Dette er en fil som har fått byttet filendelse...
					unlink("$target_path");

					$ft = $ft . "error";
					$fm = $fm . "file_is_not_an_image";
					$mode = "";
				}
			}
			else{
   				switch ($_FILES['inp_image'] ['error']){
				case 1:
					$ft = $ft . "error";
					$fm = $fm . "to_big_file";
					$mode = "";
					break;
				case 2:
					$ft = $ft . "error";
					$fm = $fm . "to_big_file";
					$mode = "";
					break;
				case 3:
					$ft = $ft . "error";
					$fm = $fm . "only_parts_uploaded";
					$mode = "";
					break;
				case 4:
					$ft = $ft . "error";
					$fm = $fm . "no_file_uploaded";
					$mode = "";
					break;
				}
			} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
			}
			else{
				$ft = $ft . "error";
				$fm = $fm . "invalid_file_type";
			}
		}
		else{
			// $ft = $ft . "info";
			// $fm = $fm . "no_image_selected";

			if($type == "general"){
				$input_logo_file= $input_logo_file ."
\$logoFileSav = \"$logoFileSav\";
";
			}
			elseif($type == "email"){
				$input_logo_file= $input_logo_file ."
\$logoFileEmailSav = \"$logoFileEmailSav\";
";
			}
			elseif($type == "pdf"){
				$input_logo_file= $input_logo_file ."
\$logoFilePdfSav = \"$logoFilePdfSav\";
";
			}
			elseif($type == "stamp_images_1280x720"){
				$input_logo_file= $input_logo_file ."
\$logoFileStampImages1280x720Sav = \"$logoFileStampImages1280x720Sav\";
";
			}
			elseif($type == "stamp_images_1920x1080"){
				$input_logo_file= $input_logo_file ."
\$logoFileStampImages1920x1080Sav = \"$logoFileStampImages1920x1080Sav\";
";
			}
			elseif($type == "stamp_images_2560x1440"){
				$input_logo_file= $input_logo_file ."
\$logoFileStampImages2560x1440Sav = \"$logoFileStampImages2560x1440Sav\";
";
			}
			elseif($type == "stamp_images_7680x4320"){
				$input_logo_file= $input_logo_file ."
\$logoFileStampImages7680x4320Sav = \"$logoFileStampImages7680x4320Sav\";
";
			}


		}
	} // while

	$input_logo_file= $input_logo_file ."
?>";


	$fh = fopen("_data/logo.php", "w") or die("can not open file");
	fwrite($fh, $input_logo_file);
	fclose($fh);




	echo"
	<meta http-equiv=refresh content=\"3; url=index.php?open=$open&page=$page&editor_language=$editor_language$ft$fm\">
	";


} // action == "upload"
?>