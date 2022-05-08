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
			</p>
		  </td>
		 </tr>
		</table>
	<!-- //Language select + menu -->

	<!-- Items -->
		";
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT item_id, item_type, item_title, item_connected_to_module_name, item_connected_to_module_part_name, item_text, item_weight, item_language, item_updated_by_user_id, item_updated_by_user_name, item_updated_datetime, item_updated_datetime_saying FROM $t_webdesign_front_page_items WHERE item_language=$editor_language_mysql ORDER BY item_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_item_id, $get_item_type, $get_item_title, $get_item_connected_to_module_name, $get_item_connected_to_module_part_name, $get_item_text, $get_item_weight, $get_item_language, $get_item_updated_by_user_id, $get_item_updated_by_user_name, $get_item_updated_datetime, $get_item_updated_datetime_saying) = $row;
			echo"
			<!-- Item info -->
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
			";
		}
		echo"
	<!-- //Items -->
	";
} // action == ""
elseif($action == "new_item"){
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
			document.getElementById(\"inp_title\").focus();
		}
		</script>
	<!-- //Focus -->

	<!-- New item form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=new_item&amp;weight=$weight&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

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
	
		<!-- Type -->
			<p><b>Type:</b><br />
			<select name=\"inp_type\" id=\"inp_type\">
				<option value=\"text\">Text</option>
				<option value=\"module\">Module</option>
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
			<div id=\"new_edit_item_text\">
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


			</div> <!-- //new_edit_item_text -->
		<!-- //Text -->

		<!-- Module -->
			<div id=\"new_edit_item_module\" style=\"display: none;\">
				
				<p><b>Module:</b><br />
				<select name=\"inp_module_name\" id=\"inp_module_name\">";

				$filenames = "";
				$dir = "_inc/";
				if ($handle = opendir($dir)) {
					$files = array();   
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

						array_push($files, $file);
					}
				
					sort($files);
					foreach ($files as $file){
					
						$title = ucfirst($file);
						$title = str_replace("_", " ", $title);

						echo"				";
						echo"<option value=\"$file\">$title</option>\n";
					}
					closedir($handle);
				}

				echo"
				</select>
				</p>

				<!-- Select module javascript -->
				<script>
				\$(document).ready(function(){
					\$(\"#inp_module_name\").change(function(){
						var selected = \$(this).children(\"option:selected\").val();
						
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
		<p><b>Weight:</b><br />

		</p>

		</form>
	<!-- //New item form -->
	";
} // new item
?>