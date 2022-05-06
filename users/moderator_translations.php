<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";


// Variables
if(isset($_GET['language'])) {
	$language = $_GET['language'];
	$language = strip_tags(stripslashes($language));
}
else{
	$language = "";
}
if(isset($_GET['step'])) {
	$step = $_GET['step'];
	$step = strip_tags(stripslashes($step));
}
else{
	$step = "";
}
if(isset($_GET['folder'])) {
	$folder = $_GET['folder'];
	$folder = strip_tags(stripslashes($folder));
}
else{
	$folder = "";
}
if(isset($_GET['file'])) {
	$file = $_GET['file'];
	$file = strip_tags(stripslashes($file));
}
else{
	$file = "";
}


function formatSizeUnits($bytes){
        if ($bytes >= 1073741824){
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576){
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024){
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        }
        elseif ($bytes > 1){
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1){
            $bytes = $bytes . ' byte';
        }
        else{
            $bytes = '0 bytes';
        }

        return $bytes;
}



if($define_in_moderator == "1"){
	if($mode == "check_integrity" && $language != "en_us"){

		// Check if language exists
		if(is_dir("$root/_scripts/language/data/$language") && $language != ""){
			echo"

			<h1>$l_check_integrity_of_files</h1>

			";
			
			if($step == ""){
				echo"
				<p>Checking folders...</p>
				";
			

				echo"
				<table style=\"width: 100%;\">
				 <tr>
				  <td class=\"outline\">
					<table style=\"border-spacing: 1px;width:100%;\">
					 <tr>
					  <td class=\"headcell\">
						<span><b>en_us folders</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>$language folders</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>$l_integrity</b></span>
					  </td>
					 </tr>

				";

				$filenames = "";
				$dir = "$root/_scripts/language/data/en_us/";
				$dirLen = strlen($dir);
				$dp = @opendir($dir);

				while($file = @readdir($dp)) $filenames [] = $file;

				for ($i = 0; $i < count($filenames); $i++){
					$content = $filenames[$i];
					$file_path = "$dir$content";

					if($file_path != "$dir." && $file_path != "$dir.." && is_dir($file_path)){
						if(isset($style) && $style == "bodycell"){
							$style = "subcell";
						}
						else{
							$style = "bodycell";
						}
					
						// This folder
						$current_language_folder = str_replace("en_us", $language, $file_path);

						echo"
						 <tr>
						  <td class=\"$style\">
							<span>$file_path</span>
						  </td>
						  <td class=\"$style\">
							<span>$current_language_folder</span>
						  </td>
						  <td class=\"$style\">
							<span>";
						if(is_dir($current_language_folder)){
							echo"OK";
						}
						else{
							mkdir($current_language_folder, 0777);
							echo"Folder created";
						}
						echo"</span>
						  </td>
						 </tr>

						";
					}
				}

				echo"
					</table>
				  </td>
				 </tr>
				</table>

				<p><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=check_integrity&amp;language=$language&amp;step=2&amp;l=$l\">Check files</a></p>
				";
			} // step == ""
			elseif($step == "2"){
				echo"
				<p>Checking files...</p>
				";



				echo"
				<table style=\"width: 100%;\">
				 <tr>
				  <td class=\"outline\">
					<table style=\"border-spacing: 1px;width:100%;\">
					 <tr>
					  <td class=\"headcell\">
						<span><b>en_us folders</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>$language folders</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>$l_integrity</b></span>
					  </td>
					 </tr>

				";

				$filenames = "";
				$dir = "$root/_scripts/language/data/en_us/";
				$dirLen = strlen($dir);
				$dp = @opendir($dir);

				while($file = @readdir($dp)) $filenames [] = $file;

				for ($i = 0; $i < count($filenames); $i++){
					$content = $filenames[$i];
					$file_path = "$dir$content";

					if($file_path != "$dir." && $file_path != "$dir.."  && is_dir($file_path)){
						if(isset($style) && $style == "bodycell"){
							$style = "subcell";
						}
						else{
							$style = "bodycell";
						}
					
						// This folder
						$current_language_folder = str_replace("en_us", $language, $file_path);

						echo"
						 <tr>
						  <td class=\"$style\">
							<span><b>$file_path</b></span>
						  </td>
						  <td class=\"$style\">
							<span><b>$current_language_folder</b></span>
						  </td>
						  <td class=\"$style\">
							<span><b>OK</b></span>
						  </td>
						 </tr>
						";

						$sub_filenames = "";
						$sub_dir = "$file_path/";
						$sub_dirLen = strlen($sub_dir);
						$sub_dp = @opendir($sub_dir);

						while($sub_file = @readdir($sub_dp)) $sub_filenames [] = $sub_file;

						for ($sub_i = 0; $sub_i < count($sub_filenames); $sub_i++){
							$sub_content = $sub_filenames[$sub_i];
							$sub_file_path = "$sub_dir$sub_content";

							if($sub_file_path != "$sub_dir." && $sub_file_path != "$sub_dir.."){
								if(isset($style) && $style == "bodycell"){
									$style = "subcell";
								}
								else{
									$style = "bodycell";
								}

								// Current language file
								$current_language_file = str_replace("en_us", $language, $sub_file_path);

								// Open en_us_file
								$fh = fopen($sub_file_path, "r");
								$en_data = fread($fh, filesize($sub_file_path));
								fclose($fh);
								$en_array = explode("\n", $en_data);

								// Open current language file
								if(!(file_exists("$current_language_file"))){
									$status = "File copied";
									copy($sub_file_path, $current_language_file);
								}
								else{
									$fh = fopen($current_language_file, "r");
									$current_data = fread($fh, filesize($current_language_file));
									fclose($fh);
									$current_array = explode("\n", $current_data);
	
									// Loop trough files
									$en_size = sizeof($en_array);
									$current_size = sizeof($current_array);

									if($en_size == "$current_size"){
										$status = "OK ($current_size lines)";
									}
									else{
										$status = "Size warning ($en_size vs $current_size lines)";
									}
								}

								echo"
								 <tr>
								  <td class=\"$style\" style=\"padding-left:8px;\">
									<span>$sub_file_path</span>
								  </td>
								  <td class=\"$style\">
									<span>$current_language_file</span>
								  </td>
								  <td class=\"$style\">
									<span>$status</span>
								  </td>
								 </tr>
								";
							}
						}

					}
				}

				echo"
					</table>
				  </td>
				 </tr>
				</table>

				<p><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;language=$language&amp;l=$l\">Home</a></p>
				";

			}
		}
		else{
			echo"<p>$l_language_not_found.</p>";
		}
	} // check_integrity
	elseif($mode == "edit_language_file"){
		
		if($process == "1" && is_dir("$root/_scripts/language/data/$language") && $language != ""){
			
			$en_file = file_get_contents("$root/_scripts/language/data/en_us/$folder/$file.php"); 
			preg_match_all('/\$[A-Za-z0-9-_]+/', $en_file, $en_vars);
			$size = sizeof($en_vars[0]);

	
			// Write header
			$fh = fopen("$root/_scripts/language/data/$language/$folder/$file.php", "w") or die("can not open file");
			fwrite($fh, "<?php");
			fclose($fh);
			
				
			for($x=0;$x<$size;$x++){
				// Variable
				$en_variable = $en_vars[0][$x];
				$inp_name = str_replace('$', "", $en_variable);

				// Get post
				$post_data = $_POST["$inp_name"];
				$post_data = output_html($post_data);

				// $post_data = htmlspecialchars($post_data);
				$post_data = htmlentities($post_data, ENT_COMPAT, 'UTF-8');

				// Norwegian letters
				$post_data = str_replace("æ","&aelig;",$post_data);
				$post_data = str_replace("ø","&oslash;",$post_data);
				$post_data = str_replace("å","&aring;",$post_data);
				$post_data = str_replace("Æ","&Aelig;",$post_data);
				$post_data = str_replace("Ø","&Oslash;",$post_data);
				$post_data = str_replace("Å","&Aring;",$post_data);
				
				// French
				$post_data = str_replace('é', 'E', $post_data);

				// Empty ?
				if(empty($post_data)){

					$post_data  = " ";

				}

				$input="
$en_variable = \"$post_data\";";
				$fh = fopen("$root/_scripts/language/data/$language/$folder/$file.php", "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);
			}

	
			// Write footer
			$fh = fopen("$root/_scripts/language/data/$language/$folder/$file.php", "a+") or die("can not open file");
			fwrite($fh, "
?>");
			fclose($fh);

			$url = "index.php?category=users&page=moderator&action=translations&mode=edit_language_file&language=$language&folder=$folder&file=$file&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		} // process == 1

		echo"
		<h1>$l_moderator</h1>


		<!-- Menu -->
			<div id=\"tabs\">
				<ul>
					<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;l=$l\">$l_users</a></li>
					<li"; if($action == "translations"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;l=$l\">$l_translations</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Menu -->
		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->
		";
		// Check if language exists
		if(is_dir("$root/_scripts/language/data/$language") && $language != ""){
			echo"
			<!-- Submenu -->
				<p>";
			if($language != "en_us"){
				echo"
				<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=check_integrity&amp;language=$language&amp;l=$l\">$l_check_integrity_of_files</a>
				&middot;
				";
			}
			echo"
				<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=flag&amp;language=$language&amp;l=$l\">$l_flag</a>

				</p>
			<!-- //Submenu -->
			";

			if(file_exists("$root/_scripts/language/data/en_us/$folder/$file.php")){

				echo"
				<!-- Next file -->
					<div style=\"float: right;\">
					";

					$filenames = "";
					$dir = "$root/_scripts/language/data/$language/$folder/";
					$dirLen = strlen($dir);
					$dp = @opendir($dir);

					while($fileread = @readdir($dp)) $filenames [] = $fileread;

					for ($i = 0; $i < count($filenames); $i++){
						$content = $filenames[$i];
						$file_path = "$dir$content";

						if($file_path != "$dir." && $file_path != "$dir.."){
							if(isset($next_file)){
								$content_saying = str_replace(".php", "", $content);
								echo"
								<p>
								<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=edit_language_file&amp;language=$language&amp;folder=$folder&amp;file=$content_saying&amp;l=$l\">$content_saying</a>
								<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=edit_language_file&amp;language=$language&amp;folder=$folder&amp;file=$content_saying&amp;l=$l\"><img src=\"$root/_webdesign/images/icons/16x16/go-next.png\" alt=\"go-next.png\" /></a>
								</p>
								";
								break;
							}

							if($content == "$file.php"){
								$next_file = "1";
							}
						}
					}
					echo"
					</div>
				<!-- //Next file -->
				<h2>$language / $folder / $file</h2>

				<form method=\"POST\" action=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=edit_language_file&amp;language=$language&amp;folder=$folder&amp;file=$file&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

				<table style=\"width: 100%;\">
				 <tr>
				  <td class=\"outline\">
					<table style=\"border-spacing: 1px;width:100%;\">
					 <tr>
					  <td class=\"headcell\">
						<span><b>Variable</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>Value</b></span>
					  </td>
					 </tr>
				";
				// En file get variables
				$en_file = file_get_contents("$root/_scripts/language/data/en_us/$folder/$file.php"); 
				preg_match_all('/\$[A-Za-z0-9-_]+/', $en_file, $en_vars);
				$size = sizeof($en_vars[0]);
	
				// Current file - Get data between brackets
				$current_file = file_get_contents("$root/_scripts/language/data/$language/$folder/$file.php");
				preg_match_all('/"([^"]+)"/', $current_file, $current_vars);


				for($x=0;$x<$size;$x++){
					if(isset($style) && $style == "bodycell"){
						$style = "subcell";
					}
					else{
						$style = "bodycell";
					}

					// Variable
					$en_variable = $en_vars[0][$x];
					if(isset($current_vars[0][$x])){
						$current_content = $current_vars[0][$x];
					}
					else{
						$current_content = "";
					}
					$current_content = str_replace('"', "", $current_content);

					// Norwegian letters
					$current_content = str_replace("æ","&aelig;", $current_content);
					$current_content = str_replace("Ã¸","&oslash;", $current_content);
					$current_content = str_replace("Ã¥","&aring;", $current_content);
					$current_content = str_replace("Æ","&Aelig;", $current_content);
					$current_content = str_replace("Ã˜","&Oslash;", $current_content);
					$current_content = str_replace("Ã…","&Aring;", $current_content);
					

					$inp_name = str_replace('$', "", $en_variable);

					// En variable display
					$en_variable_saying = str_replace("\$l_", "", $en_variable);
					$en_variable_saying = str_replace("_", " ", $en_variable_saying);
					$en_variable_saying = ucfirst($en_variable_saying);

					echo"
					 <tr>
					  <td class=\"$style\" style=\"padding-left:8px;\">
						<span>$en_variable_saying</span>
					  </td>
					  <td class=\"$style\">";
						if($x == 0){

							echo"
							<!-- Focus -->
							<script>
							\$(document).ready(function(){
								\$('[name=\"$inp_name\"]').focus();
							});
							</script>
							<!-- //Focus -->
							";
						}
						echo"
						<span><input type=\"text\" name=\"$inp_name\" value=\"$current_content\" size=\"45\" /></span>
					  </td>
					 </tr>
					";
				}

				echo"
					</table>
				  </td>
				 </tr>
				</table>

				<p>
				<input type=\"submit\" value=\"Save changes\" />
				</p>

				<p><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=open_language&amp;language=$language&amp;l=$l\">Back</a></p>
				";
			} // file_exists
			else{
				echo"<p>File not found.</p>";
			} 
		}
		else{
			echo"<p>$l_language_not_found.</p>";
		}
	} // mode == edit_language_file
	elseif($mode == "flag"){
		
		if($process == "1" && is_dir("$root/_scripts/language/data/$language") && $language != ""){
			// Upload photo


			// Get extention
			function getExtension($str) {
				$i = strrpos($str,".");
				if (!$i) { return ""; } 
				$l = strlen($str) - $i;
				$ext = substr($str,$i+1,$l);
				return $ext;
			}

			
			// Upload
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				$image = $_FILES['inp_image']['name'];
				
				$filename = stripslashes($_FILES['inp_image']['name']);
				$extension = getExtension($filename);
				$extension = strtolower($extension);

				if($image){

					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft = "warning";
						$fm = "unknown_file_format";
						$url = "index.php?category=users&page=moderator&action=translations&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;
					}
					else{
						$size=filesize($_FILES['inp_image']['tmp_name']);

						if($extension == "png"){
							$uploadedfile = $_FILES['inp_image']['tmp_name'];
							

							// Width and height
							list($width,$height) = @getimagesize($uploadedfile);

							if($width == "16" && $height == "16"){
								
								// Destination file
								$uploadfile = "$root/_scripts/language/data/$language/". $language . "." . $extension;

								if (move_uploaded_file($_FILES['inp_image']['tmp_name'], $uploadfile)) {
									// Send feedback
									$ft = "success";
									$fm = "photo_uploaded";
									$url = "index.php?category=users&page=moderator&action=translations&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
									header("Location: $url");
									exit;
								} else {
									
									$ft = "warning";
									$fm = "photo_could_not_be_uploaded_please_check_file_size";
					
									$url = "index.php?category=users&page=moderator&action=translations&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
									header("Location: $url");
									exit;
								}
							}
							else{
								$ft = "warning";
								$fm = "width_and_height_must_be";
					
								$url = "index.php?category=users&page=moderator&action=translations&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
								header("Location: $url");
								exit;

							}
						}
						else{
							$ft = "warning";
							$fm = "file_format_must_be_png";
					
							$url = "index.php?category=users&page=moderator&action=translations&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
							header("Location: $url");
							exit;

						}
						
					}
				} // if($image){
				else{
					switch ($_FILES['inp_image']['error']) {
						case UPLOAD_ERR_OK:
           						$fm = "photo_unknown_error";
							break;
						case UPLOAD_ERR_NO_FILE:
           						$fm = "no_file_selected";
							break;
						case UPLOAD_ERR_INI_SIZE:
           						$fm = "photo_exceeds_filesize";
							break;
						case UPLOAD_ERR_FORM_SIZE:
           						$fm_front = "photo_exceeds_filesize_form";
							break;
						default:
           						$fm_front = "unknown_upload_error";
							break;

					}
					if(isset($fm) && $fm != ""){
						$ft = "warning";
					}
						
					// Send feedback
					$url = "index.php?category=users&page=moderator&action=translations&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
					header("Location: $url");
					exit;
				
				} // if!($image){

			} // if($_SERVER["REQUEST_METHOD"] == "POST") {


		} // process == 1

		echo"
		<h1>$l_moderator</h1>


		<!-- Menu -->
			<div id=\"tabs\">
				<ul>
					<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;l=$l\">$l_users</a></li>
					<li"; if($action == "translations"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;l=$l\">$l_translations</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Menu -->

		";
		// Check if language exists
		if(is_dir("$root/_scripts/language/data/$language") && $language != ""){
			echo"
			<!-- Submenu -->
				<p>";
			if($language != "en_us"){
				echo"
				<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=check_integrity&amp;language=$language&amp;l=$l\">$l_check_integrity_of_files</a>
				&middot;
				";
			}
			echo"
				<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=flag&amp;language=$language&amp;l=$l\">$l_flag</a>

				</p>
			<!-- //Submenu -->
			
			
			<form method=\"POST\" action=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=flag&amp;language=$language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					if($fm == "unknown_file_format"){
						$fm = "$l_unknown_file_format";
					}
					elseif($fm == "photo_uploaded"){
						$fm = "$l_photo_uploaded";
					}
					elseif($fm == "photo_could_not_be_uploaded_please_check_file_size"){
						$fm = "$l_photo_could_not_be_uploaded_please_check_file_size";
					}
					elseif($fm == "width_and_height_must_be"){
						$fm = "$l_width_and_height_must_be";
					}
					elseif($fm == "file_format_must_be_png"){
						$fm = "$l_file_format_must_be_png";
					}
					elseif($fm == "photo_unknown_error"){
						$fm = "$l_photo_unknown_error";
					}
					elseif($fm == "no_file_selected"){
						$fm = "$l_no_file_selected";
					}
					elseif($fm == "photo_exceeds_filesize"){
						$fm = "$l_photo_exceeds_filesize";
					}
					elseif($fm == "photo_exceeds_filesize_form"){
						$fm = "$l_photo_exceeds_filesize_form";
					}
					elseif($fm == "unknown_upload_error"){
						$fm = "$l_unknown_upload_error";
					}
					
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->


			<p>";
			if(file_exists("$root/_scripts/language/data/$language/$language.png")){
				echo"<img src=\"$root/_scripts/language/data/$language/$language.png\" alt=\"_scripts/language/data/$language/$language.png\" style=\"padding: 0px 4px 0px 0px;float:left;\" />";
			}
			echo"$l_select_photo (16x16 png):<br />
			<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
			<input type=\"submit\" value=\"$l_upload\" tabindex=\"2\" />
			</p>

			</form>

			";
		}
		else{
			echo"<p>$l_language_not_found.</p>";
		}
	} // mode == flag
	elseif($mode == "open_language"){
		echo"
		<h1>$l_moderator</h1>


		<!-- Menu -->
			<div id=\"tabs\">
				<ul>
					<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;l=$l\">$l_users</a></li>
					<li"; if($action == "translations"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;l=$l\">$l_translations</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Menu -->

		";
		// Check if language exists
		if(is_dir("$root/_scripts/language/data/$language") && $language != ""){

			echo"<h2>$language</h2>

			<!-- Submenu -->
				<p>";
			if($language != "en_us"){
				echo"
				<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=check_integrity&amp;language=$language&amp;l=$l\">$l_check_integrity_of_files</a>
				&middot;
				";
			}
			echo"
				<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=flag&amp;language=$language&amp;l=$l\">$l_flag</a>

				</p>
			<!-- //Submenu -->


			<table style=\"width: 100%;\">
			 <tr>
			  <td class=\"outline\">
				<table style=\"border-spacing: 1px;width:100%;\">
				 <tr>
				  <td class=\"headcell\">
					<span><b>$l_file</b></span>
				  </td>
				  <td class=\"headcell\" style=\"text-align:right;\">
					<span><b>$l_size</b></span>
				  </td>
				  <td class=\"headcell\">
					<span><b>$l_updated (Y-m-d H:i)</b></span>
				  </td>
				 </tr>

			";

			$filenames = "";
			$dir = "$root/_scripts/language/data/$language/";
			$dirLen = strlen($dir);
			$dp = @opendir($dir);

			while($file = @readdir($dp)) $filenames [] = $file;

			for ($i = 0; $i < count($filenames); $i++){
				$content = $filenames[$i];
				$file_path = "$dir$content";

				if($file_path != "$dir." && $file_path != "$dir.." && is_dir($file_path)){
					
					echo"
					 <tr>
					  <td class=\"headcell\">
						<span>$content</span>
					  </td>
					  <td class=\"headcell\">
						<span></span>
					  </td>
					  <td class=\"headcell\">
						<span></span>
					  </td>
					 </tr>

					";
					$sub_filenames = "";
					$sub_dir = "$file_path/";
					$sub_dirLen = strlen($sub_dir);
					$sub_dp = @opendir($sub_dir);

					while($sub_file = @readdir($sub_dp)) $sub_filenames [] = $sub_file;

					for ($sub_i = 0; $sub_i < count($sub_filenames); $sub_i++){
						$sub_content = $sub_filenames[$sub_i];
						$sub_file_path = "$sub_dir$sub_content";

						if($sub_file_path != "$sub_dir." && $sub_file_path != "$sub_dir.."){
							if(isset($style) && $style == "bodycell"){
								$style = "subcell";
							}
							else{
								$style = "bodycell";
							}

							// Name
							$name = str_replace(".php", "", $sub_content);

							// Size
							$bytes = filesize($sub_file_path);
							$filesize = formatSizeUnits($bytes);

							// Last edited
							$edited = date ("Y-m-d H:i", filemtime($sub_file_path));
							echo"
							 <tr>
							  <td class=\"$style\" style=\"padding-left:8px;\">
								<span><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=edit_language_file&amp;language=$language&amp;folder=$content&amp;file=$name&amp;l=$l\">$name</a></span>
							  </td>
							  <td class=\"$style\" style=\"text-align:right;\">
								<span>$filesize</span>
							  </td>
							  <td class=\"$style\">
								<span>$edited</span>
							  </td>
							 </tr>

							";
						}
					}
				}
			}

			echo"
				</table>
			  </td>
			 </tr>
			</table>
			";
		}
		else{
			echo"<p>$l_language_not_found.</p>";
		}
	} // mode == open_language
	elseif($mode == "add_language"){
		if($process == "1"){
			$inp_lang = $_POST['inp_lang'];
			$inp_lang = output_html($inp_lang);
			$inp_lang = strtolower($inp_lang);
			if(empty($inp_lang)){
				$url = "index.php?category=users&page=moderator&action=translations&mode=add_language&ft=warning&fm=Please enter a language";
				header("Location: $url");
				exit;
			}

			$inp_charset = $_POST['inp_charset'];
			$inp_charset = output_html($inp_charset);
			$inp_charset = strtolower($inp_charset);
			if(empty($inp_charset)){
				$url = "index.php?category=users&page=moderator&action=translations&mode=add_language&ft=warning&fm=Please enter the charset";
				header("Location: $url");
				exit;
			}

			// Mkdir
			mkdir("$root/_scripts/language/data/$inp_lang");
			mkdir("$root/_scripts/language/data/$inp_lang/common");

			$input = "<?php
\$l_lang = \"$inp_lang\";
\$l_charset = \"$inp_charset\";
?>";

			$fh = fopen("$root/_scripts/language/data/$inp_lang/common/common.php", "w") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);

			$url = "index.php?category=users&page=moderator&action=translations&ft=success&fm=Language added. Please run integrity of language files.";
			header("Location: $url");
			exit;
			
			
		}
		echo"
		<h1>$l_moderator</h1>


		<!-- Menu -->
			<div id=\"tabs\">
				<ul>
					<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;l=$l\">$l_users</a></li>
					<li"; if($action == "translations"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;l=$l\">$l_translations</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Menu -->

			
		<form method=\"POST\" action=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=add_language&amp;process=1&amp;l=$l\" enctype=\"multipart/form-data\" name=\"nameform\">

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_lang\"]').focus();
		});
		</script>
		<!-- //Focus -->

		<p>
		ISO Language Code (example en-us, no-nb):<br />
		<input type=\"text\" name=\"inp_lang\" size=\"30\" />
		</p>

		<p>
		Charset:<br />
		<input type=\"text\" name=\"inp_charset\" value=\"$l_charset\" size=\"30\" />
		</p>

		<p>
		<input type=\"submit\" value=\"$l_save\" />
		</p>

		</form>
		";
	} // mode == add_language
	elseif($mode == ""){
		echo"
		<h1>$l_moderator</h1>


		<!-- Menu -->
			<div id=\"tabs\">
				<ul>
					<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;l=$l\">$l_users</a></li>
					<li"; if($action == "translations"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;l=$l\">$l_translations</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Menu -->

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Language selection -->
			<p><b>$l_language</b><br />";
			$filenames = "";
			$dir = "$root/_scripts/language/data/";
			$dirLen = strlen($dir);
			$dp = @opendir($dir);

			while($file = @readdir($dp)) $filenames [] = $file;

			for ($i = 0; $i < count($filenames); $i++){
				$content = $filenames[$i];
				$file_path = "$dir$content";

				if($file_path != "$dir." && $file_path != "$dir.."){
					if(file_exists("$root/_scripts/language/data/$content/$content.png")){
						echo"<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=open_language&amp;language=$content&amp;l=$l\"><img src=\"$root/_scripts/language/data/$content/$content.png\" alt=\"_scripts/language/data/$content/$content.png\" style=\"padding: 0px 4px 0px 0px;float:left;\" /></a> ";
					}
					echo"<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=open_language&amp;language=$content&amp;l=$l\">$content</a><br />\n";
				}
			}
			echo"	
			</p>
		<!-- //Language selection -->

		<!-- Add language -->
			<p>
			<a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;mode=add_language\">Add language</a>
			</p>
		<!-- //Add language -->
		";
	} // mode == !!
			
		
} // $define_in_admin == 1
else{
	echo"<h1>Server error 403</h1>";
}
?>