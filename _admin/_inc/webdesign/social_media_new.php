<?php
/**
*
* File: _admin/_inc/social_media/social_media_new
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

$tabindex = 0;

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

	$datetime = date("y-m-d H:i:s");


	mysqli_query($link, "INSERT INTO $t_social_media
	(social_media_id, social_media_site_id, social_media_site_title, social_media_site_logo, social_media_language, social_media_link_title, social_media_link_url, social_media_updated, social_media_placement, social_media_active, social_media_hits) 
	VALUES 
	(NULL, $inp_site_id_mysql, $inp_site_title_mysql, $inp_site_logo_mysql, $inp_language_mysql, $inp_link_title_mysql, $inp_link_url_mysql, '$datetime', $inp_placement_mysql, $inp_active_mysql, '0')")
	or die(mysqli_error($link));


	$inp_code = $_POST['inp_code'];
	
	$sql = "UPDATE $t_social_media SET social_media_code=? WHERE social_media_updated='$datetime'";
	$stmt = $link->prepare($sql);
	$stmt->bind_param("s", $inp_code);
	$stmt->execute();
	if ($stmt->errno) {
		echo "FAILURE!!! " . $stmt->error; die;
	}


	$url = "index.php?open=webdesign&page=social_media&editor_language=$editor_language&ft=success&fm=social_media_created";
	header("Location: $url");
	exit;
}
echo"
<h1>New social media</h1>

<!-- Form -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_title\"]').focus();
	});
	</script>
			
	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

	<p><b>Social media:</b><br />
	<select name=\"inp_site_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />\n";
		$query = "SELECT site_id, site_title, site_logo FROM $t_social_media_sites";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_site_id, $get_site_title, $get_site_logo) = $row;
			echo"	<option value=\"$get_site_id\">$get_site_title</option>\n";
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
		echo"	<option value=\"$get_language_active_iso_two\">$get_language_active_name</option>\n";
	}
	echo"
	</select>

	<p><b>Placement:</b><br />
	<select name=\"inp_placement\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
		<option value=\"main_below_headline\">Main, below headline</option>
		<option value=\"main_below_text\">Main, below text</option>
		<option value=\"right_side_of_content\">Right side of conent</option>
		<option value=\"left_side_of_content\">Left side of conent</option>
	</select>

	<p><b>Link title:</b><br />
	<input type=\"text\" name=\"inp_link_title\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Link URL:</b><br />
	<input type=\"text\" name=\"inp_link_url\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Code:</b><br />
	<textarea name=\"inp_code\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
	</p>

	<p><b>Active:</b><br />
	<select name=\"inp_active\">
		<option value=\"1\">Active</option>
		<option value=\"0\">Inactive</option>
	</select>

		
	<p><input type=\"submit\" value=\"Create\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	</form>
<!-- //Form -->
";

?>