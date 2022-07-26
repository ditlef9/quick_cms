<?php
/**
*
* File: _admin/_inc/webdesign/front_page.php
* Version 19:08 06.05.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_webdesign_front_page_items	= $mysqlPrefixSav . "webdesign_front_page_items";

/*- Config ---------------------------------------------------------------------------- */
if(!(file_exists("_data/front_page.php"))){
	$inp_config="<?php
\$editorModeSav = \"wyciwug\";
?>";
	$fh = fopen("_data/front_page.php", "w+") or die("can not open file");
	fwrite($fh, $inp_config);
	fclose($fh);
}
include("_data/front_page.php");

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['item_id'])) {
	$item_id = $_GET['item_id'];
	$item_id = strip_tags(stripslashes($item_id));
	if(!(is_numeric($item_id))){
		echo"Item id is not numeric";
		die;
	}
}
else{
	$item_id = "";
}
$item_id_mysql = quote_smart($link, $item_id);

/*- Scriptstart ----------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>Front page</h1>

	<!-- Language select + menu -->
		<table>
		 <tr>
		  <td>
			<p>
			";
			$query = "SELECT language_active_id, language_active_name, language_active_slug, language_active_native_name, language_active_iso_two, language_active_iso_three, language_active_iso_four, language_active_flag_path_18x18, language_active_flag_active_18x18, language_active_flag_inactive_18x18 FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_slug, $get_language_active_native_name, $get_language_active_iso_two, $get_language_active_iso_three, $get_language_active_iso_four, $get_language_active_flag_path_18x18, $get_language_active_flag_active_18x18, $get_language_active_flag_inactive_18x18) = $row;
				echo"
				<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"><img src=\"../$get_language_active_flag_path_18x18/"; if($get_language_active_iso_two == "$editor_language"){ echo"$get_language_active_flag_active_18x18"; } else{ echo"$get_language_active_flag_inactive_18x18"; } echo"\" alt=\"$get_language_active_flag_active_18x18\" /></a>
				<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\""; if($get_language_active_iso_two == "$editor_language"){ echo" style=\"font-weight:bold;\""; } echo">$get_language_active_name</a>
				&nbsp;
				";
			}
			echo"
			</p>
		  </td>
		  <td>
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_item&amp;weight=0&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New item</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=settings&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Settings</a>
			</p>
		  </td>
		 </tr>
		</table>
	<!-- //Language select + menu -->

	<!-- Items -->
		";
		$style = "";
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT item_id, item_type, item_title, item_connected_to_module_name, item_connected_to_module_part_name, item_text, item_weight, item_language, item_updated_by_user_id, item_updated_by_user_name, item_updated_datetime, item_updated_datetime_saying FROM $t_webdesign_front_page_items WHERE item_language=$editor_language_mysql ORDER BY item_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_item_id, $get_item_type, $get_item_title, $get_item_connected_to_module_name, $get_item_connected_to_module_part_name, $get_item_text, $get_item_weight, $get_item_language, $get_item_updated_by_user_id, $get_item_updated_by_user_name, $get_item_updated_datetime, $get_item_updated_datetime_saying) = $row;

			// Style
			if($style == "bodycell"){
				$style = "subcell";
			}
			else{
				$style = "bodycell";
			}

			echo"
			<div class=\"$style\">
				<!-- Item info -->
					<a id=\"item$get_item_id\"></a>
					<div class=\"item_info_wrapper\">
						<div class=\"item_info_title\">
							<h2>$get_item_title</h2>
						</div>
						<div class=\"item_info_actions\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_item_up&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_upward_round_black_18x18.png\" alt=\"arrow_upward_round_black_18x18.png\" /></a>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_item_down&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_downward_round_black_18x18.png\" alt=\"arrow_downard_round_black_18x18.png\" /></a>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_item&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/edit_round_black_18x18.png\" alt=\"edit_round_black_18x18.png\" /></a>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_item&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/delete_round_black_18x18.png\" alt=\"delete_round_black_18x18.png\" /></a>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_item&amp;weight=$get_item_weight&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/add_outline_black_18x18.png\" alt=\"add_outline_black_18x18.png\" /></a>
							</p>
						</div>
					</div>
				<!-- //Item info -->

				<!-- Item data -->
					";
					if($get_item_type == "text"){
						echo"
						$get_item_text
						";
					}
					else{
						echo"Unknown type";
					}
					echo"
					$get_item_connected_to_module_name, $get_item_connected_to_module_part_name
				<!-- //Item data -->
			</div> <!-- Item -->
			<p></p>
			";
		}
		echo"
	<!-- //Items -->
	";
} // action == ""
elseif($action == "settings"){

	if(isset($_GET['mode'])) {
		$mode = $_GET['mode'];
		$mode = strip_tags(stripslashes($mode));
	}
	else{
		$mode = "";
	}
	if($mode == "save"){
		$inp_editor_mode = $_POST['inp_editor_mode'];
		$inp_editor_mode = output_html($inp_editor_mode);
		
		
		$inp_config="<?php
\$editorModeSav = \"$inp_editor_mode\";
?>";
		$fh = fopen("_data/front_page.php", "w+") or die("can not open file");
		fwrite($fh, $inp_config);
		fclose($fh);


		echo"
		<h1>Front page settings</h1>
		<h2><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Saving...</h2>
		<meta http-equiv=refresh content=\"3; url=index.php?open=$open&page=$page&action=$action&ft=success&fm=changes_saved\">
		";
	}
	else{
		echo"
		<h1>Front page settings</h1>

		<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Front page</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l\">Settings</a>
		</p>
		<!-- //Where am I ? -->


		<!-- Focus -->
		<script>
		window.onload = function() {
			document.getElementById(\"inp_type\").focus();
		}
		</script>
		<!-- //Focus -->

		<!-- Front page settings -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l&amp;mode=save\" enctype=\"multipart/form-data\">

		<p><b>Editor mode:</b><br />
		<select name=\"inp_editor_mode\">
			<option value=\"wyciwug\""; if($editorModeSav == "wyciwug"){ echo" selected=\"selected\""; } echo">What you see is what you get</option>
			<option value=\"bbcode\""; if($editorModeSav == "bbcode"){ echo" selected=\"selected\""; } echo">BBCode</option>
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
		</p>

		</form>
		<!-- //Front page settings -->
		";
	} // mode == ""
} // settings
elseif($action == "new_item"){
	if($process == "1"){
		$inp_type = $_POST['inp_type'];
		$inp_type = output_html($inp_type);
		$inp_type_mysql = quote_smart($link, $inp_type);
		
		$inp_text = $_POST['inp_text'];

		$inp_module_name = "";
		if($item_type != "text"){
			$inp_module_name = $_POST['inp_module_name'];
		}
		$inp_module_name = output_html($inp_module_name);
		$inp_module_name_mysql = quote_smart($link, $inp_module_name);


		$inp_module_name_part = $_POST['inp_module_name_part'];
		$inp_module_name_part = output_html($inp_module_name_part);
		$inp_module_name_part_mysql = quote_smart($link, $inp_module_name_part);

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		$inp_weight = $_POST['inp_weight'];
		$inp_weight = output_html($inp_weight);
		$inp_weight = $inp_weight+1;
		$inp_weight_mysql = quote_smart($link, $inp_weight);

		// Me
		$datetime = date("Y-m-d H:i:s");
		$datetime_saying = date("j M Y H:i:s");

		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);


		$query = "SELECT user_id, user_name FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_name) = $row;

		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);

		// Insert
		mysqli_query($link, "INSERT INTO $t_webdesign_front_page_items 
		(item_id, item_type, item_title, item_connected_to_module_name, item_connected_to_module_part_name, 
		item_text, item_weight, item_language, item_updated_by_user_id, item_updated_by_user_name, 
		item_updated_datetime, item_updated_datetime_saying) 
		VALUES 
		(NULL, $inp_type_mysql, $inp_title_mysql, $inp_module_name_mysql, $inp_module_name_part_mysql, 
		'', $inp_weight_mysql, $inp_language_mysql, $my_user_id_mysql, $inp_my_user_name_mysql, 
		'$datetime', '$datetime_saying')")
		or die(mysqli_error($link));

		// Get item id
		$query = "SELECT item_id FROM $t_webdesign_front_page_items WHERE item_updated_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_item_id) = $row;
		
		// Text
		if($editorModeSav == "wyciwug"){
			$sql = "UPDATE $t_webdesign_front_page_items SET item_text=? WHERE item_id='$get_item_id'";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_text);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}		
		}
		elseif($editorModeSav == "bbcode"){
			// BBcode
			$inp_text = output_html($inp_text);
			$sql = "UPDATE $t_webdesign_front_page_items SET item_text=? WHERE item_id='$get_item_id'";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_text);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}
		}


		// Header
		$url = "index.php?open=webdesign&page=front_page&editor_language=$editor_language&l=$l&ft=success&fm=added#item$get_current_item_id";
		header("Location: $url");
		exit;
	} // process == 1


	// Get variables
	$editor_language_mysql = quote_smart($link, $editor_language);
	if(isset($_GET['weight'])) {
		$weight = $_GET['weight'];
		$weight = strip_tags(stripslashes($weight));
		if(!(is_numeric($weight))){
			echo"Weight not numeric";
			die;
		}
	}
	else{
		$weight = "0";
	}

	echo"
	<h1>New item</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Front page</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_item&amp;weight=$weight&amp;editor_language=$editor_language&amp;l=$l\">New item</a>
		</p>
	<!-- //Where am I ? -->


	<!-- Focus -->
		<script>
		window.onload = function() {
			document.getElementById(\"inp_type\").focus();
		}
		</script>
	<!-- //Focus -->

	<!-- New item form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=new_item&amp;weight=$weight&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<!-- Load modules -->
			";
			$dir = "_inc/";
			$modules = array();   
			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file === '.') continue;
					if ($file === '..') continue;
					if ($file === "admin_cms") continue;
					if ($file === "backup") continue;
					if ($file === "crypto_analyzer") continue;
					if ($file === "dashboard") continue;
					if ($file === "domains_monitoring") continue;
					if ($file === "hash_db") continue;
					if ($file === "knowledge") continue;
					if ($file === "music_sheets") continue;
					if ($file === "office_calendar") continue;
					if ($file === "settings") continue;
					if ($file === "throw_the_dice") continue;
					if ($file === "webdesign") continue;
					array_push($modules, $file);
				}
				
				// Add search
				array_push($modules, "search");

				sort($modules);
			}
			closedir($handle);
			echo"
		<!-- //Load modules -->
		
		<!-- Type -->
			<p><b>Type:</b><br />
			<select name=\"inp_type\" id=\"inp_type\">
				<option value=\"module\" selected=\"selected\">Module</option>
				<option value=\"text\">Text</option>
			</select>
			</p>

			<!-- Select type javascript -->
			<script>
			\$(document).ready(function(){
				\$(\"#inp_type\").change(function(){
					var selected = \$(this).children(\"option:selected\").val();
					
					\$(\"#new_edit_item_text\").toggle();
					\$(\"#new_edit_item_module\").toggle();

					// Autoadd title
					if(selected == \"module\"){
						// Set to first title available
						\$(\"#inp_title\").val(\""; echo ucfirst($modules[0]); echo"\");
					}
				});

				
			});
			</script>
			<!-- //Select type javascript -->
		<!-- //Type -->

	

		<!-- Text -->
			<div id=\"new_edit_item_text\" style=\"display: none;\">
				";
				if($editorModeSav == "wyciwug"){
					echo"
					<!-- TinyMCE -->
					<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
					<script>
					tinymce.init({
						selector: 'textarea.editor',
						plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
						toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
						image_advtab: true,
						content_css: [
							'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
							'//www.tiny.cloud/css/codepen.min.css'
						],
						link_list: [
							{ title: 'My page 1', value: 'http://www.tinymce.com' },
							{ title: 'My page 2', value: 'http://www.moxiecode.com' }
						],
						image_list: [
							{ title: 'My page 1', value: 'http://www.tinymce.com' },
							{ title: 'My page 2', value: 'http://www.moxiecode.com' }
						],
						image_class_list: [
							{ title: 'None', value: '' },
							{ title: 'Some class', value: 'class-name' }
						],
						importcss_append: true,
						height: 500,
						file_picker_callback: function (callback, value, meta) {
							/* Provide file and text for the link dialog */
							if (meta.filetype === 'file') {
								callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
							}
							/* Provide image and alt text for the image dialog */
							if (meta.filetype === 'image') {
								callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
							}
							/* Provide alternative source and posted for the media dialog */
							if (meta.filetype === 'media') {
								callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
							}
						}
					});
					</script>
					<!-- //TinyMCE -->

					<p>
					<textarea name=\"inp_text\" rows=\"40\" cols=\"120\" class=\"editor\"></textarea>
					</p>
					";
				} // Editor mode 
				elseif($editorModeSav == "bbcode"){
					echo"
					<p>
					<input type=\"button\" value=\"b\" onclick=\"formatText ('[b][/b]');\" class=\"btn_bbcode\" style=\"font-weight: bold;\" /> 
					<input type=\"button\" value=\"i\" onclick=\"formatText ('[i][/i]');\" class=\"btn_bbcode\" style=\"font-style: italic;\" /> 
					<input type=\"button\" value=\"u\" onclick=\"formatText ('[u][/u]');\" class=\"btn_bbcode\" style=\"text-decoration: underline;\" /> 
					<input type=\"button\" value=\"URL\" onclick=\"formatText ('[url][/url]');\" class=\"btn_bbcode\" /> 
					<input type=\"button\" value=\"Code\" onclick=\"formatText ('[code][/code]');\" class=\"btn_bbcode\" /> 
					<input type=\"button\" value=\"Image\" onclick=\"formatText ('[img][/img]');\" class=\"btn_bbcode\" /> 
					<br />
					<textarea name=\"inp_text\" id=\"inp_text\" rows=\"20\" cols=\"50\" style=\"width: 100%;\"></textarea>
					</p>
					
					<!-- Javascript insert bb code -->
						<script type=\"text/javascript\"> 
						function formatText(tag) {
							// BBCode
							var Field = document.getElementById('inp_text');
							var val = Field.value;
							var selected_txt = val.substring(Field.selectionStart, Field.selectionEnd);
							var before_txt = val.substring(0, Field.selectionStart);
							var after_txt = val.substring(Field.selectionEnd, val.length);
							Field.value += tag;


							// Focus
							document.getElementById(\"inp_text\").focus();
						}
						</script>
					<!-- //Javascript insert bb code -->
					";
				}
				echo"
			</div> <!-- //new_edit_item_text -->
		<!-- //Text -->

		<!-- Module -->
			<div id=\"new_edit_item_module\">
				
				<p><b>Module:</b><br />
				<select name=\"inp_module_name\" id=\"inp_module_name\">";

				
				foreach ($modules as $file){
					$title = ucfirst($file);
					$title = str_replace("_", " ", $title);
					echo"				";
					echo"<option value=\"$file\">$title</option>\n";
				}
				

				echo"
				</select>
				</p>

				<!-- Select module javascript -->
				<script>
				\$(document).ready(function(){
				
					\$(\"#inp_module_name\").change(function(){
						var selected_value = \$(this).children(\"option:selected\").val();
						var selected_text = \$(this).children(\"option:selected\").text();
						
						// Set to first title available
						\$(\"#inp_title\").val(selected_text);
					});

				});
				</script>
				<!-- //Select module javascript -->

				<p><b>Module part:</b><br />
				<select name=\"inp_module_name_part\" id=\"inp_module_name_part\">
					<option value=\"\">None</option>
				</select>
				</p>

			</div> <!-- //new_edit_item_module -->
		<!-- //Module -->


		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" id=\"inp_title\" size=\"25\" />
		</p>

		<p><b>Language:</b><br />
		<select name=\"inp_language\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
						
		}
		echo"
		</select>
		</p>


		<p><b>Weight:</b><br />
		<select name=\"inp_weight\">\n";
		$x = 1;
		$query = "SELECT item_id, item_title, item_weight FROM $t_webdesign_front_page_items WHERE item_language=$editor_language_mysql ORDER BY item_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_item_id, $get_item_title, $get_item_weight) = $row;
			echo"	<option value=\"$get_item_weight\"";if($get_item_weight == "$weight"){ echo" selected=\"selected\"";}echo">After $get_item_title</option>\n";

			// Weight check
			if($x != "$get_item_weight"){
				$result = mysqli_query($link, "UPDATE $t_webdesign_front_page_items SET item_weight=$x WHERE item_id=$get_item_id") or die(mysqli_error($link));

			}
			$x++;
		}
		if($x == "1"){
			echo"	<option value=\"0\">First</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
		</p>

		</form>
	<!-- //New item form -->
	";
} // new item
elseif($action == "edit_item"){
	// Find item
	$query = "SELECT item_id, item_type, item_title, item_connected_to_module_name, item_connected_to_module_part_name, item_text, item_weight, item_language, item_updated_by_user_id, item_updated_by_user_name, item_updated_datetime, item_updated_datetime_saying FROM $t_webdesign_front_page_items  WHERE item_id=$item_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_item_id, $get_current_item_type, $get_current_item_title, $get_current_item_connected_to_module_name, $get_current_item_connected_to_module_part_name, $get_current_item_text, $get_current_item_weight, $get_current_item_language, $get_current_item_updated_by_user_id, $get_current_item_updated_by_user_name, $get_current_item_updated_datetime, $get_current_item_updated_datetime_saying) = $row;	
	
	if($get_current_item_id == ""){
		echo"<h1>Item not found</h1>";
	}
	else{
		if($process == "1"){
			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);
			$inp_type_mysql = quote_smart($link, $inp_type);
		
			$inp_text = $_POST['inp_text'];

			$inp_module_name = "";
			if($item_type != "text"){
				$inp_module_name = $_POST['inp_module_name'];
			}
			$inp_module_name = output_html($inp_module_name);
			$inp_module_name_mysql = quote_smart($link, $inp_module_name);
	

			$inp_module_name_part = $_POST['inp_module_name_part'];
			$inp_module_name_part = output_html($inp_module_name_part);
			$inp_module_name_part_mysql = quote_smart($link, $inp_module_name_part);

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);

			$inp_weight = $_POST['inp_weight'];
			$inp_weight = output_html($inp_weight);
			$inp_weight = $inp_weight+1;
			$inp_weight_mysql = quote_smart($link, $inp_weight);

			// Me
			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j M Y H:i:s");

			$my_user_id = $_SESSION['admin_user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);


			$query = "SELECT user_id, user_name FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_name) = $row;

			$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);

			// Update
			mysqli_query($link, "UPDATE $t_webdesign_front_page_items SET 
							item_type=$inp_type_mysql, 
							item_title=$inp_title_mysql, 
							item_connected_to_module_name=$inp_module_name_mysql, 
							item_connected_to_module_part_name=$inp_module_name_part_mysql,  
							item_weight=$inp_weight_mysql, 
							item_language=$inp_language_mysql, 
							item_updated_by_user_id=$my_user_id_mysql, 
							item_updated_by_user_name=$inp_my_user_name_mysql,  
							item_updated_datetime='$datetime',
							item_updated_datetime_saying='$datetime_saying'
							WHERE item_id=$get_current_item_id") or die(mysqli_error($link));

		
			// Text
			if($editorModeSav == "wyciwug"){
				$sql = "UPDATE $t_webdesign_front_page_items SET item_text=? WHERE item_id='$get_current_item_id'";
				$stmt = $link->prepare($sql);
				$stmt->bind_param("s", $inp_text);
				$stmt->execute();
				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error; die;
				}		
			}
			elseif($editorModeSav == "bbcode"){
				// BBcode
				$inp_text = output_html($inp_text);
				$sql = "UPDATE $t_webdesign_front_page_items SET item_text=? WHERE item_id='$get_current_item_id'";
				$stmt = $link->prepare($sql);
				$stmt->bind_param("s", $inp_text);
				$stmt->execute();
				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error; die;
				}
			}


			// Header
			$url = "index.php?open=webdesign&page=front_page&action=$action&item_id=$get_current_item_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		} // process == 1


		echo"
		<h1>Edit item</h1>

		<!-- Where am I ? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Front page</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l#item$get_current_item_id\">$get_current_item_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_item&amp;item_id=$get_current_item_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			</p>
		<!-- //Where am I ? -->


		<!-- Focus -->
			<script>
			window.onload = function() {
				document.getElementById(\"inp_type\").focus();
			}
			</script>
		<!-- //Focus -->

		<!-- Edit item form -->
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_item&amp;item_id=$get_current_item_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<!-- Load modules -->
				";
				$dir = "_inc/";
				$modules = array();   
				if ($handle = opendir($dir)) {
					while (false !== ($file = readdir($handle))) {
						if ($file === '.') continue;
						if ($file === '..') continue;
						if ($file === "admin_cms") continue;
						if ($file === "backup") continue;
						if ($file === "crypto_analyzer") continue;
						if ($file === "dashboard") continue;
						if ($file === "domains_monitoring") continue;
						if ($file === "hash_db") continue;
						if ($file === "knowledge") continue;
						if ($file === "music_sheets") continue;
						if ($file === "office_calendar") continue;
						if ($file === "settings") continue;
						if ($file === "throw_the_dice") continue;
						if ($file === "webdesign") continue;
						array_push($modules, $file);
					}

					// Add search
					array_push($modules, "search");

					sort($modules);
				}
				closedir($handle);
				echo"
			<!-- //Load modules -->
		
			<!-- Type -->
				<p><b>Type:</b><br />
				<select name=\"inp_type\" id=\"inp_type\">
					<option value=\"module\""; if($get_current_item_type == "module"){ echo" selected=\"selected\""; } echo">Module</option>
					<option value=\"text\""; if($get_current_item_type == "text"){ echo" selected=\"selected\""; } echo">Text</option>
				</select>
				</p>

				<!-- Select type javascript -->
				<script>
				\$(document).ready(function(){
					\$(\"#inp_type\").change(function(){
						var selected = \$(this).children(\"option:selected\").val();
					
						\$(\"#new_edit_item_text\").toggle();
						\$(\"#new_edit_item_module\").toggle();

					});
				});
				</script>
				<!-- //Select type javascript -->
			<!-- //Type -->

	

			<!-- Text -->
			<div id=\"new_edit_item_text\""; if($get_current_item_type == "module"){ echo" style=\"display: none;\""; } echo">
				";
				if($editorModeSav == "wyciwug"){
					echo"
					<!-- TinyMCE -->
					<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
					<script>
					tinymce.init({
						selector: 'textarea.editor',
						plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
						toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
						image_advtab: true,
						content_css: [
							'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
							'//www.tiny.cloud/css/codepen.min.css'
						],
						link_list: [
							{ title: 'My page 1', value: 'http://www.tinymce.com' },
							{ title: 'My page 2', value: 'http://www.moxiecode.com' }
						],
						image_list: [
							{ title: 'My page 1', value: 'http://www.tinymce.com' },
							{ title: 'My page 2', value: 'http://www.moxiecode.com' }
						],
						image_class_list: [
							{ title: 'None', value: '' },
							{ title: 'Some class', value: 'class-name' }
						],
						importcss_append: true,
						height: 500,
						file_picker_callback: function (callback, value, meta) {
							/* Provide file and text for the link dialog */
							if (meta.filetype === 'file') {
								callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
							}
							/* Provide image and alt text for the image dialog */
							if (meta.filetype === 'image') {
								callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
							}
							/* Provide alternative source and posted for the media dialog */
							if (meta.filetype === 'media') {
								callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
							}
						}
					});
					</script>
					<!-- //TinyMCE -->

					<p>
					<textarea name=\"inp_text\" rows=\"40\" cols=\"120\" class=\"editor\">$get_current_item_text</textarea>
					</p>
					";
				} // Editor mode 
				elseif($editorModeSav == "bbcode"){
					echo"
					<p>
					<input type=\"button\" value=\"b\" onclick=\"formatText ('[b][/b]');\" class=\"btn_bbcode\" style=\"font-weight: bold;\" /> 
					<input type=\"button\" value=\"i\" onclick=\"formatText ('[i][/i]');\" class=\"btn_bbcode\" style=\"font-style: italic;\" /> 
					<input type=\"button\" value=\"u\" onclick=\"formatText ('[u][/u]');\" class=\"btn_bbcode\" style=\"text-decoration: underline;\" /> 
					<input type=\"button\" value=\"URL\" onclick=\"formatText ('[url][/url]');\" class=\"btn_bbcode\" /> 
					<input type=\"button\" value=\"Code\" onclick=\"formatText ('[code][/code]');\" class=\"btn_bbcode\" /> 
					<input type=\"button\" value=\"Image\" onclick=\"formatText ('[img][/img]');\" class=\"btn_bbcode\" /> 
					<br />
					<textarea name=\"inp_text\" id=\"inp_text\" rows=\"20\" cols=\"50\" style=\"width: 100%;\">$get_current_item_text</textarea>
					</p>
					
					<!-- Javascript insert bb code -->
						<script type=\"text/javascript\"> 
						function formatText(tag) {
							// BBCode
							var Field = document.getElementById('inp_text');
							var val = Field.value;
							var selected_txt = val.substring(Field.selectionStart, Field.selectionEnd);
							var before_txt = val.substring(0, Field.selectionStart);
							var after_txt = val.substring(Field.selectionEnd, val.length);
							Field.value += tag;


							// Focus
							document.getElementById(\"inp_text\").focus();
						}
						</script>
					<!-- //Javascript insert bb code -->
					";
				}
				echo"
			</div> <!-- //new_edit_item_text -->
		<!-- //Text -->

		<!-- Module -->
			<div id=\"new_edit_item_module\""; if($get_current_item_type == "text"){ echo" style=\"display: none;\""; } echo">
				
				<p><b>Module:</b><br />
				<select name=\"inp_module_name\" id=\"inp_module_name\">";

				
				foreach ($modules as $file){
					$title = ucfirst($file);
					$title = str_replace("_", " ", $title);
					echo"				";
					echo"<option value=\"$file\""; if($file == "$get_current_item_connected_to_module_name"){ echo" selected=\"selected\""; } echo">$title</option>\n";
				}
				

				echo"
				</select>
				</p>

				<!-- Select module javascript -->
				<script>
				\$(document).ready(function(){
				
					\$(\"#inp_module_name\").change(function(){
						var selected_value = \$(this).children(\"option:selected\").val();
						var selected_text = \$(this).children(\"option:selected\").text();
						
						// Set to first title available
						\$(\"#inp_title\").val(selected_text);
					});

				});
				</script>
				<!-- //Select module javascript -->

				<p><b>Module part:</b><br />
				<select name=\"inp_module_name_part\" id=\"inp_module_name_part\">
					<option value=\"\""; if($get_current_item_connected_to_module_part_name == ""){ echo" selected=\"selected\""; } echo">None</option>
				</select>
				</p>

			</div> <!-- //new_edit_item_module -->
		<!-- //Module -->


		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_item_title\" id=\"inp_title\" size=\"25\" />
		</p>

		<p><b>Language:</b><br />
		<select name=\"inp_language\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\"";if($get_current_item_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>


		<p><b>Weight:</b><br />
		<select name=\"inp_weight\">\n";
		$x = 1;
		$language_mysql = quote_smart($link, $get_current_item_language);
		$query = "SELECT item_id, item_title, item_weight FROM $t_webdesign_front_page_items WHERE item_language=$language_mysql ORDER BY item_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_item_id, $get_item_title, $get_item_weight) = $row;
			echo"	<option value=\"$get_item_weight\"";if($get_item_weight == "$get_current_item_weight"){ echo" selected=\"selected\"";}echo">After $get_item_title</option>\n";

			// Weight check
			if($x != "$get_item_weight"){
				$result = mysqli_query($link, "UPDATE $t_webdesign_front_page_items SET item_weight=$x WHERE item_id=$get_item_id") or die(mysqli_error($link));
			}
			$x++;
		}
		if($x == "1"){
			echo"	<option value=\"0\">First</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
		</p>

		</form>
		<!-- //Edit item form -->
		";
	} // item found

} // edit item
elseif($action == "delete_item"){
	// Find item
	$query = "SELECT item_id, item_type, item_title, item_connected_to_module_name, item_connected_to_module_part_name, item_text, item_weight, item_language, item_updated_by_user_id, item_updated_by_user_name, item_updated_datetime, item_updated_datetime_saying FROM $t_webdesign_front_page_items  WHERE item_id=$item_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_item_id, $get_current_item_type, $get_current_item_title, $get_current_item_connected_to_module_name, $get_current_item_connected_to_module_part_name, $get_current_item_text, $get_current_item_weight, $get_current_item_language, $get_current_item_updated_by_user_id, $get_current_item_updated_by_user_name, $get_current_item_updated_datetime, $get_current_item_updated_datetime_saying) = $row;	
	
	if($get_current_item_id == ""){
		echo"<h1>Item not found</h1>";
	}
	else{
		if($process == "1"){
	
			// Delete
			mysqli_query($link, "DELETE FROM $t_webdesign_front_page_items WHERE item_id=$get_current_item_id") or die(mysqli_error($link));

		


			// Header
			$url = "index.php?open=webdesign&page=front_page&editor_language=$editor_language&l=$l&ft=success&fm=deleted";
			header("Location: $url");
			exit;
		} // process == 1


		echo"
		<h1>Delete item</h1>

		<!-- Where am I ? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Front page</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l#item$get_current_item_id\">$get_current_item_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_item&amp;item_id=$get_current_item_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Delete item form -->
			<p>
			Are you sure you want to delete?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_item&amp;item_id=$get_current_item_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l#item$get_current_item_id\" class=\"btn_default\">Cancel</a>
			</p>
		<!-- //Delete item form -->
		";
	} // item found

} // delete item
elseif($action == "move_item_up"){
	// Find item
	$query = "SELECT item_id, item_type, item_title, item_connected_to_module_name, item_connected_to_module_part_name, item_text, item_weight, item_language, item_updated_by_user_id, item_updated_by_user_name, item_updated_datetime, item_updated_datetime_saying FROM $t_webdesign_front_page_items  WHERE item_id=$item_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_item_id, $get_current_item_type, $get_current_item_title, $get_current_item_connected_to_module_name, $get_current_item_connected_to_module_part_name, $get_current_item_text, $get_current_item_weight, $get_current_item_language, $get_current_item_updated_by_user_id, $get_current_item_updated_by_user_name, $get_current_item_updated_datetime, $get_current_item_updated_datetime_saying) = $row;	

	if($get_current_item_id == ""){
		echo"<h1>Item not found</h1>";
	}
	else{
		if($process == "1"){
			$change_item_weight = $get_current_item_weight-1;

			$language_mysql = quote_smart($link, $get_current_item_language);

			$query = "SELECT item_id, item_weight FROM $t_webdesign_front_page_items WHERE item_weight=$change_item_weight AND item_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_change_item_id, $get_change_item_weight) = $row;	

			if($get_change_item_id == ""){
				// Header
				$url = "index.php?open=webdesign&page=front_page&editor_language=$editor_language&l=$l&ft=success&fm=didnt_find_any_item_to_change_with#item$get_current_item_id";
				header("Location: $url");
				exit;
			}
			else{
				// Upte this
				mysqli_query($link, "UPDATE $t_webdesign_front_page_items SET item_weight=$get_change_item_weight WHERE item_id=$get_current_item_id") or die(mysqli_error($link));


				// Upte change
				mysqli_query($link, "UPDATE $t_webdesign_front_page_items SET item_weight=$get_current_item_weight WHERE item_id=$get_change_item_id") or die(mysqli_error($link));
				
				// Header
				$url = "index.php?open=webdesign&page=front_page&editor_language=$editor_language&l=$l&ft=success&fm=changed#item$get_current_item_id";
				header("Location: $url");
				exit;

			}
		} // Process
	} // Item found

} // move item up
elseif($action == "move_item_down"){
	// Find item
	$query = "SELECT item_id, item_type, item_title, item_connected_to_module_name, item_connected_to_module_part_name, item_text, item_weight, item_language, item_updated_by_user_id, item_updated_by_user_name, item_updated_datetime, item_updated_datetime_saying FROM $t_webdesign_front_page_items  WHERE item_id=$item_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_item_id, $get_current_item_type, $get_current_item_title, $get_current_item_connected_to_module_name, $get_current_item_connected_to_module_part_name, $get_current_item_text, $get_current_item_weight, $get_current_item_language, $get_current_item_updated_by_user_id, $get_current_item_updated_by_user_name, $get_current_item_updated_datetime, $get_current_item_updated_datetime_saying) = $row;	

	if($get_current_item_id == ""){
		echo"<h1>Item not found</h1>";
	}
	else{
		if($process == "1"){
			$change_item_weight = $get_current_item_weight+1;

			$language_mysql = quote_smart($link, $get_current_item_language);

			$query = "SELECT item_id, item_weight FROM $t_webdesign_front_page_items WHERE item_weight=$change_item_weight AND item_language=$language_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_change_item_id, $get_change_item_weight) = $row;	

			if($get_change_item_id == ""){
				// Header
				$url = "index.php?open=webdesign&page=front_page&editor_language=$editor_language&l=$l&ft=success&fm=didnt_find_any_item_to_change_with#item$get_current_item_id";
				header("Location: $url");
				exit;
			}
			else{
				// Upte this
				mysqli_query($link, "UPDATE $t_webdesign_front_page_items SET item_weight=$get_change_item_weight WHERE item_id=$get_current_item_id") or die(mysqli_error($link));


				// Upte change
				mysqli_query($link, "UPDATE $t_webdesign_front_page_items SET item_weight=$get_current_item_weight WHERE item_id=$get_change_item_id") or die(mysqli_error($link));
				
				// Header
				$url = "index.php?open=webdesign&page=front_page&editor_language=$editor_language&l=$l&ft=success&fm=changed#item$get_current_item_id";
				header("Location: $url");
				exit;

			}
		} // Process
	} // Item found
} // move item down
?>