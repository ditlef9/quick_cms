<?php
/**
*
* File: _admin/_inc/settings/favicon.php
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
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->


	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_favicon&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">$l_new_favicon</a>
	</p>

	<!-- 16x16 -->
		<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_favicon $l_smal_lowercase</b></p>
		<table>
		 <tr>

	";
		if(file_exists("../_uploads/favicon/16x16.png")){
			echo"
			  <td style=\"padding-right: 16px;\">
				<span>16x16.png<br />
				<img src=\"../_uploads/favicon/16x16.png\" alt=\"16x16.png\" width=\"16\" height=\"16\" />
				</span>
			  <//td>
			";
		}
		if(file_exists("../_uploads/favicon/16x16.ico")){
			echo"
			  <td style=\"padding-right: 16px;\">
				<span>16x16.ico<br />
				<img src=\"../_uploads/favicon/16x16.ico\" alt=\"16x16.ico\" width=\"16\" height=\"16\" />
				</span>
			  </td>
			";
		}
	echo"
		 </tr>
		</table>
	<!-- 16x16 -->

	<!-- 32x32 -->
		<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_favicon $l_medium_lowercase</b></p>
		<table>
		 <tr>

	";
		if(file_exists("../_uploads/favicon/32x32.png")){
			echo"
			  <td style=\"padding-right: 16px;\">
				<span>32x32.png<br />
				<img src=\"../_uploads/favicon/32x32.png\" alt=\"32x32.png\" width=\"32\" height=\"32\" />
				</span>
			  <//td>
			";
		}
		if(file_exists("../_uploads/favicon/32x32.ico")){
			echo"
			  <td style=\"padding-right: 16px;\">
				<span>32x32.ico<br />
				<img src=\"../_uploads/favicon/32x32.ico\" alt=\"32x32.ico\" width=\"32\" height=\"32\" />
				</span>
			  </td>
			";
		}
	echo"
		 </tr>
		</table>
	<!-- //32x32 -->


	<!-- 260x260 -->
		";
		if(file_exists("../_uploads/favicon/260x260.png")){
			echo"
			<p><b>$l_favicon $l_large_lowercase</b><br />
			260x260.png<br />
			<img src=\"../_uploads/favicon/260x260.png\" alt=\"260x260.png\" />
			</p>
			";
		}
		echo"
	<!-- //260x260 -->
	";


}
elseif($action == "new_favicon"){
	if(file_exists("_data/favicon.php")){
		include("_data/favicon.php");
	}


	if($process == "1"){
		// Get type
		$inp_type = $_POST['inp_type'];
		$inp_type = output_html($inp_type);

		// Sjekk filen
		$file_name = basename($_FILES['inp_image']['name']);
		$file_exp = explode('.', $file_name); 
		$file_type = $file_exp[count($file_exp) -1]; 
		$file_type = strtolower("$file_type");

		// Finnes mappen?
		$year = date("Y");
		$upload_path = "../_uploads/favicon/";

		if(!(is_dir("../_uploads/"))){
			mkdir("../_uploads/");
		}
		if(!(is_dir("../_uploads/favicon/"))){
			mkdir("../_uploads/favicon/");
		}


		// Sett variabler
		if($inp_type == "smal_png"){
			$new_name = "16x16.png";
		}
		elseif($inp_type == "smal_ico"){
			$new_name = "16x16.ico";
		}
		elseif($inp_type == "medium_png"){
			$new_name = "32x32.png";
		}
		elseif($inp_type == "medium_ico"){
			$new_name = "32x32.ico";
		}
		else{
			$new_name = "260x260.png";
		}

		$target_path = $upload_path . "/" . $new_name;

		// Sjekk om det er en OK filendelse
		if($file_type == "jpg" OR $file_type == "png" OR $file_type == "gif" OR $file_type == "ico"){
			if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {

				// Sjekk om det faktisk er et bilde som er lastet opp
				$image_size = getimagesize($target_path);
				if(is_numeric($image_size[0]) && is_numeric($image_size[1])){

					// Image size
					list($width,$height) = getimagesize($target_path);


					// Dette bildet er OK
					$url = "index.php?open=$open&page=$page&editor_language=$editor_language&ft=success&fm=image_uploaded";
					header("Location: $url");
					exit;
					
				}
				else{
					// Dette er en fil som har fått byttet filendelse...
					unlink("$target_path");

					$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=error&fm=file_is_not_an_image";
					header("Location: $url");
					exit;
				}
			}
			else{
   				switch ($_FILES['inp_image'] ['error']){
				case 1:
					$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=error&fm=to_big_file";
					header("Location: $url");
					exit;
					break;
				case 2:
					$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=error&fm=to_big_file";
					header("Location: $url");
					exit;
					break;
				case 3:
					$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=error&fm=only_parts_uploaded";
					header("Location: $url");
					exit;
					break;
				case 4:
					$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=error&fm=no_file_uploaded";
					header("Location: $url");
					exit;
					break;
				}
			} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		}
		else{
			$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=error&fm=invalid_file_type&file_type=$file_type";
			header("Location: $url");
			exit;
		}
	}
	echo"
	<h1>$l_new_favicon</h1>

	<!-- You are here -->
		<p>
		<b>$l_you_are_here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_favicon</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_favicon&amp;editor_language=$editor_language&amp;l=$l\">$l_new_favicon</a>
		</p>
	<!-- //You are here -->

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->


	<!-- Form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
	

		<p><b>$l_new_image:</b><br />
		<input type=\"file\" name=\"inp_image\" />
		</p>

		<p><b>$l_type:</b><br />
		<select name=\"inp_type\">
			<option value=\"smal_png\">$l_smal 16x16.png</option>
			<option value=\"smal_ico\">$l_smal 16x16.ico</option>
			<option value=\"medium_png\">$l_medium 32x32.png</option>
			<option value=\"medium_ico\">$l_medium 32x32.ico</option>
			<option value=\"large\">$l_large 260x260.png</option>
		</select>
		</p>



		<p>
		<input type=\"submit\" value=\"$l_upload\" class=\"submit\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
	
			
		</form>

	<!-- //Form -->
	";
}
?>