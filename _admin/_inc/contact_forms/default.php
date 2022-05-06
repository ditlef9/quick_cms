<?php
/**
*
* File: _admin/_inc/contact_forms/default.php
* Version 1.0
* Date 19:46 23.01.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['view'])) {
	$view = $_GET['view'];
	$view = strip_tags(stripslashes($view));
}
else{
	$view = "";
}


/*- Tables ---------------------------------------------------------------------------- */
$t_contact_forms_liquidbase		= $mysqlPrefixSav . "contact_forms_liquidbase";
$t_contact_forms_index			= $mysqlPrefixSav . "contact_forms_index";
$t_contact_forms_questions		= $mysqlPrefixSav . "contact_forms_questions";
$t_contact_forms_questions_alternatives	= $mysqlPrefixSav . "contact_forms_questions_alternatives";

/*- Check if setup is run ------------------------------------------------------------- */
$query = "SELECT * FROM $t_contact_forms_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	echo"
	<h1>Contact Forms</h1>


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



	<!-- Actions -->
	<script>
	\$(function(){
		\$('#inp_l').on('change', function () {
			var url = \$(this).val(); // get selected value
			if (url) { // require a URL
 				window.location = url; // redirect
			}
			return false;
		});
	});
	</script>
	<div style=\"float: left\">
		<!-- Contact forms menu buttons -->
		<p>
		";

		// Navigation
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='contact_forms/index.php'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_navigation_id) = $row;
		if($get_navigation_id == ""){
			echo"
			<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=contact_forms&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
			";
		}
		echo"
			<a href=\"index.php?open=$open&amp;page=new_contact_form&amp;editor_language=$editor_language\" class=\"btn\">New contact form</a>
		</p>
		<!-- //Contact forms menu buttons -->
	</div>
	<div style=\"float: right;\">
		<p>
		<select id=\"inp_l\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			// No language selected?
			if($editor_language == ""){
					$editor_language = "$get_language_active_iso_two";
			}
			
			echo"	<option value=\"index.php?open=$open&amp;editor_language=$get_language_active_iso_two&amp;l=$l\""; if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>
	</div>
	<div class=\"clear\"></div>
	<!-- //Actions -->


	<!-- Contact forms -->
	<div class=\"vertical\">
		<ul>
		";
	
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_ipblock, form_used_times FROM $t_contact_forms_index WHERE form_language=$editor_language_mysql";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_form_id, $get_form_title, $get_form_language, $get_form_mail_to, $get_form_text_before_form, $get_form_text_left_of_form, $get_form_text_right_of_form, $get_form_text_after_form, $get_form_created_datetime, $get_form_created_by_user_id, $get_form_updated_datetime, $get_form_updated_by_user_id, $get_form_api_avaible, $get_form_ipblock, $get_form_used_times) = $row;

		echo"
		<li><a href=\"index.php?open=$open&amp;page=open_contact_form&amp;form_id=$get_form_id&amp;editor_language=$editor_language\">$get_form_title</a></li>
		";
	}
	echo"
		</ul>
	</div>
	<!-- //Contact forms -->

	<!-- Menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/contact_forms/menu.php");
			echo"
		</ul>
	</div>
	<!-- //Menu -->
	";
} // setup has runned
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>