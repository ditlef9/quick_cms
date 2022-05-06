<?php
/**
*
* File: _admin/_inc/comments/social_media_edit.php
* Version 1
* Date 10:34 03.03.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ----------------------------------------------------------------------- */

/*- Tables ---------------------------------------------------------------------------- */
$t_social_media 	= $mysqlPrefixSav . "social_media";
$t_social_media_sites	= $mysqlPrefixSav . "social_media_sites";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['social_media_id'])){
	$social_media_id = $_GET['social_media_id'];
	$social_media_id = output_html($social_media_id);
}
else{
	$social_media_id = "";
}
$tabindex = 0;


// Get social_media
$social_media_id_mysql = quote_smart($link, $social_media_id);
$query = "SELECT social_media_id, social_media_site_id, social_media_site_title, social_media_language, social_media_link_title, social_media_link_url, social_media_placement, social_media_code, social_media_active, social_media_hits FROM $t_social_media WHERE social_media_id=$social_media_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_social_media_id, $get_current_social_media_site_id, $get_current_social_media_site_title, $get_current_social_media_language, $get_current_social_media_link_title, $get_current_social_media_link_url, $get_current_social_media_placement, $get_current_social_media_code, $get_current_social_media_active, $get_current_social_media_hits) = $row;

if($get_current_social_media_id == ""){
	echo"
	<h1>Error</h1>

	<p>
	Not found.
	</p>
	";

}
else{
	if($process == "1"){
		$inp_site_id = $_POST['inp_site_id'];
		$inp_site_id = output_html($inp_site_id);
		$inp_site_id_mysql = quote_smart($link, $inp_site_id);

		// Get site name
		$query = "SELECT site_id, site_title, site_logo FROM $t_social_media_sites WHERE site_id=$inp_site_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_site_id, $get_site_title, $get_site_logo) = $row;
		$inp_site_title_mysql = quote_smart($link, $get_site_title);
		$inp_site_logo_mysql = quote_smart($link, $get_site_logo);
	
		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		$inp_placement = $_POST['inp_placement'];
		$inp_placement = output_html($inp_placement);
		$inp_placement_mysql = quote_smart($link, $inp_placement);

		$inp_link_title = $_POST['inp_link_title'];
		$inp_link_title = output_html($inp_link_title);
		$inp_link_title_mysql = quote_smart($link, $inp_link_title);

		$inp_link_url = $_POST['inp_link_url'];
		$inp_link_url = output_html($inp_link_url);
		$inp_link_url_mysql = quote_smart($link, $inp_link_url);

		$inp_active = $_POST['inp_active'];
		$inp_active = output_html($inp_active);
		$inp_active_mysql = quote_smart($link, $inp_active);

		$datetime = date("Y-m-d H:i:s");

		$result = mysqli_query($link, "UPDATE $t_social_media SET 
					social_media_site_id=$inp_site_id_mysql,
					social_media_site_title=$inp_site_title_mysql,
					social_media_site_logo=$inp_site_logo_mysql,
					social_media_language=$inp_language_mysql,
					social_media_link_title=$inp_link_title_mysql, 
					social_media_link_url=$inp_link_url_mysql, 
					social_media_updated='$datetime',
					social_media_placement=$inp_placement_mysql,
					social_media_active=$inp_active_mysql

			 WHERE social_media_id=$get_current_social_media_id") or die(mysqli_error($link));


		$inp_code = $_POST['inp_code'];
	
		$sql = "UPDATE $t_social_media SET social_media_code=? WHERE social_media_id=$get_current_social_media_id";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_code);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}


		$url = "index.php?open=$open&page=social_media_edit&social_media_id=$social_media_id&editor_language=$editor_language&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>Edit social media</h1>

	<!-- Feedback -->
		";
		if(isset($fm)){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst("$fm");
				$fm = str_replace("_", " ", $fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->

	<!-- Form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_text\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;social_media_id=$get_current_social_media_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		
		<p><b>Social media:</b><br />
		<select name=\"inp_site_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />\n";
		$query = "SELECT site_id, site_title, site_logo FROM $t_social_media_sites";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_site_id, $get_site_title, $get_site_logo) = $row;
			echo"	<option value=\"$get_site_id\""; if($get_current_social_media_site_id == "$get_site_id"){ echo" selected=\"selected\""; } echo">$get_site_title</option>\n";
		}
		echo"
		</select>
		</p>
	
		<p><b>Language:</b><br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\""; if($get_current_social_media_language == "$get_language_active_iso_two"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>

		<p><b>Placement:</b><br />
		<select name=\"inp_placement\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"main_below_headline\""; if($get_current_social_media_placement == "main_below_headline"){ echo" selected=\"selected\""; } echo">Main, below headline</option>
			<option value=\"main_below_text\""; if($get_current_social_media_placement == "main_below_text"){ echo" selected=\"selected\""; } echo">Main, below text</option>
			<option value=\"right_side_of_content\""; if($get_current_social_media_placement == "right_side_of_content"){ echo" selected=\"selected\""; } echo">Right side of conent</option>
			<option value=\"left_side_of_content\""; if($get_current_social_media_placement == "left_side_of_content"){ echo" selected=\"selected\""; } echo">Left side of conent</option>
		</select>

		<p><b>Link title:</b><br />
		<input type=\"text\" name=\"inp_link_title\" value=\"$get_current_social_media_link_title\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Link URL:</b><br />
		<input type=\"text\" name=\"inp_link_url\" value=\"$get_current_social_media_link_url\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Code:</b><br />
		<textarea name=\"inp_code\" rows=\"14\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_social_media_code</textarea>
		</p>

		<p><b>Active:</b><br />
		<select name=\"inp_active\">
			<option value=\"1\""; if($get_current_social_media_active == "1"){ echo" selected=\"selected\""; } echo">Active</option>
			<option value=\"0\""; if($get_current_social_media_active == "0"){ echo" selected=\"selected\""; } echo">Inactive</option>
		</select>

		
		
		<p><input type=\"submit\" value=\"Save changes\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->
	";
}
?>