<?php
/**
*
* File: _admin/_inc/pages/cookies_policy.php
* Version 1.0 
* Date 19:18 27.01.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_pages_cookies_policy = $mysqlPrefixSav . "pages_cookies_policy";

// Get language
$editor_language = output_html($editor_language);
$editor_language_mysql = quote_smart($link, $editor_language);
$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active WHERE language_active_iso_two=$editor_language_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_language_active_id, $get_current_language_active_name, $get_current_language_active_iso_two, $get_current_language_active_default) = $row;
if($get_current_language_active_id == ""){
	echo"Could not find editor lang";
	die;
}

// Get my user
$my_user_id = $_SESSION['admin_user_id'];
$my_user_id = output_html($my_user_id);
$my_user_id_mysql = quote_smart($link, $my_user_id);
$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_registered_date_saying, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$my_user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_password, $get_my_user_password_replacement, $get_my_user_password_date, $get_my_user_salt, $get_my_user_security, $get_my_user_rank, $get_my_user_verified_by_moderator, $get_my_user_first_name, $get_my_user_middle_name, $get_my_user_last_name, $get_my_user_language, $get_my_user_country_id, $get_my_user_country_name, $get_my_user_city_name, $get_my_user_timezone_utc_diff, $get_my_user_timezone_value, $get_my_user_measurement, $get_my_user_date_format, $get_my_user_gender, $get_my_user_height, $get_my_user_dob, $get_my_user_registered, $get_my_user_registered_time, $get_my_user_registered_date_saying, $get_my_user_newsletter, $get_my_user_privacy, $get_my_user_views, $get_my_user_views_ipblock, $get_my_user_points, $get_my_user_points_rank, $get_my_user_likes, $get_my_user_dislikes, $get_my_user_status, $get_my_user_login_tries, $get_my_user_last_online, $get_my_user_last_online_time, $get_my_user_last_ip, $get_my_user_synchronized, $get_my_user_notes, $get_my_user_marked_as_spammer) = $row;


// Get policy
$query = "SELECT cookies_policy_id, cookies_policy_title, cookies_policy_language, cookies_policy_text, cookies_policy_is_active, cookies_policy_created_date, cookies_policy_created_date_saying, cookies_policy_created_by_user_id, cookies_policy_created_by_user_name, cookies_policy_created_by_user_email, cookies_policy_created_by_name, cookies_policy_updated_date, cookies_policy_updated_date_saying, cookies_policy_updated_by_user_id, cookies_policy_updated_by_user_name, cookies_policy_updated_by_user_email, cookies_policy_updated_by_name FROM $t_pages_cookies_policy WHERE cookies_policy_language=$editor_language_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_cookies_policy_id, $get_current_cookies_policy_title, $get_current_cookies_policy_language, $get_current_cookies_policy_text, $get_current_cookies_policy_is_active, $get_current_cookies_policy_created_date, $get_current_cookies_policy_created_date_saying, $get_current_cookies_policy_created_by_user_id, $get_current_cookies_policy_created_by_user_name, $get_current_cookies_policy_created_by_user_email, $get_current_cookies_policy_created_by_name, $get_current_cookies_policy_updated_date, $get_current_cookies_policy_updated_date_saying, $get_current_cookies_policy_updated_by_user_id, $get_current_cookies_policy_updated_by_user_name, $get_current_cookies_policy_updated_by_user_email, $get_current_cookies_policy_updated_by_name) = $row;
if($get_current_cookies_policy_id == ""){
	echo"<div class=\"info\"><p>Inserting policy</p></div>";
	
	$date = date("Y-m-d");
	$date_saying = date("j F Y");
	$inp_my_user_id_mysql = quote_smart($link, $get_my_user_id);
	$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
	$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
	$inp_my_name_mysql = quote_smart($link, "$get_my_user_first_name $get_my_user_middle_name $get_my_user_last_name");
	mysqli_query($link, "INSERT INTO $t_pages_cookies_policy(cookies_policy_id, cookies_policy_title, cookies_policy_language, cookies_policy_text, cookies_policy_is_active, 
					cookies_policy_created_date, cookies_policy_created_date_saying, cookies_policy_created_by_user_id, cookies_policy_created_by_user_name, cookies_policy_created_by_user_email, 
					cookies_policy_created_by_name)
					VALUES 
					(NULL, 'Cookies Policy', $editor_language_mysql, '', '1', '$date', '$date_saying', $inp_my_user_id_mysql, $inp_my_user_name_mysql, $inp_my_user_email_mysql, $inp_my_name_mysql)
					") or die(mysqli_error());

	echo"
	<meta http-equiv=refresh content=\"1; url=index.php?open=$open&page=$page&editor_language=$editor_language\">
	";
}


/*- Start ------------------------------------------------------------------------ */
if($action ==""){
	if($process == "1"){
	// Dates
	$date = date("Y-m-d");
	$date_saying = date("j F Y");

	// Me
	$inp_my_user_id_mysql = quote_smart($link, $get_my_user_id);
	$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
	$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
	$inp_my_name = "$get_my_user_first_name $get_my_user_middle_name $get_my_user_last_name";
	$inp_my_name_mysql = quote_smart($link, $inp_my_name);
	
	
	$inp_title = $_POST['inp_title'];
	$inp_title = output_html($inp_title);
	$inp_title_mysql = quote_smart($link, $inp_title);

	$inp_is_active = $_POST['inp_is_active'];
	$inp_is_active = output_html($inp_is_active);
	$inp_is_active_mysql = quote_smart($link, $inp_is_active);

	$inp_text = $_POST['inp_text'];

	// Insert content
	$sql = "UPDATE $t_pages_cookies_policy SET cookies_policy_title=?, cookies_policy_text=?, cookies_policy_is_active=?, cookies_policy_updated_date=?, cookies_policy_updated_date_saying=?, cookies_policy_updated_by_user_id=?, cookies_policy_updated_by_user_name=?, cookies_policy_updated_by_user_email=?, cookies_policy_updated_by_name =? WHERE cookies_policy_id='$get_current_cookies_policy_id'";
	$stmt = $link->prepare($sql);
	$stmt->bind_param("sssssssss", $inp_title, $inp_text, $inp_is_active, $date, $date_saying, $get_my_user_id, $get_my_user_name, $get_my_user_email, $inp_my_name);
	$stmt->execute();
	if ($stmt->errno) {
		echo "FAILURE!!! " . $stmt->error; die;
	}


	// Header
	header("Location: index.php?open=$open&page=$page&editor_language=$editor_language&ft=success&fm=changes_saved");
	exit;

	} // process

	
	echo"
	<h1>Cookies Policy</h1>

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

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=pages&amp;page=cookies_policy&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Cookies policy</a></li>
				<li><a href=\"index.php?open=pages&amp;page=cookies_policy&amp;action=cookies_accept_code&amp;editor_language=$editor_language&amp;l=$l\">Cookies accept code</a></li>
				
			</ul>
		</div>
		<div class=\"clear\"></div>
	<!-- //Menu -->

	<!-- Select language -->

		<script>
		\$(function(){
			// bind change event to select
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>

	<form method=\"get\" enctype=\"multipart/form-data\">
		<p>
		$l_language:
		<select id=\"inp_l\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
		echo"
		</select>
		</p>
	</form>
<!-- //Select language -->

	<p>
	URL:<br />
	<a href=\"../legal/index.php?doc=cookies_policy&amp;l=$editor_language\">../legal/index.php?doc=cookies_policy&amp;l=$editor_language</a>
	</p>

<!-- Edit form -->

	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
	
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

		<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
		<!-- //Focus -->


		<p><b>Title</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_cookies_policy_title\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Is active</b><br />
		<input type=\"radio\" name=\"inp_is_active\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_cookies_policy_is_active == "1"){ echo" checked=\"checked\""; } echo" /> Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_is_active\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_cookies_policy_is_active == "0"){ echo" checked=\"checked\""; } echo" /> No
		</p>

		<p>
		<textarea name=\"inp_text\" rows=\"40\" cols=\"120\" class=\"editor\">$get_current_cookies_policy_text</textarea>
		</p>

		<p>
		<input type=\"submit\" value=\"Save Changes\" class=\"submit\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
			



	</form>
	<!-- //Edit form -->
	";
}
elseif($action == "cookies_accept_code"){
	echo"
	<h1>Cookies Policy</h1>
	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=pages&amp;page=cookies_policy&amp;editor_language=$editor_language&amp;l=$l\">Cookies policy</a></li>
				<li><a href=\"index.php?open=pages&amp;page=cookies_policy&amp;action=cookies_accept_code&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Cookies accept code</a></li>	
			</ul>
		</div>
		<div class=\"clear\"></div>
	<!-- //Menu -->

	<p>PHP:<br />
	<textarea style=\"width:100%;\" cols=\"10\" rows=\"10\"><!-- Cookies warning -->
	&quot;;
	include(&quot;\$root/_admin/_functions/cookies_warning_include.php&quot;);
	echo&quot;
<!-- //Cookies warning -->
	</textarea>
	</p>


	<p>CSS:<br />
	<textarea style=\"width:100%;\" cols=\"10\" rows=\"10\">/*- Cookies ----------------------------------------------------------------- */
#div_legal{
	background: #000;
	text-align: center;
	position: absolute;
	bottom: 0;
	width: 100%;
}
#div_legal > p{
	color: #fff;
}
a.legal_read_more{
	color: #fff;
	text-decoration: underline;
}	</textarea>
	</p>

	";
}
?>