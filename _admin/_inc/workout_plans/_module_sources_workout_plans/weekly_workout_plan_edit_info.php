<?php 
/**
*
* File: workout_plans/weekly_workout_plan_info.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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
include("_tables.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_weekly_workout_plan_edit_sessions.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['weekly_id'])){
	$weekly_id = $_GET['weekly_id'];
	$weekly_id = output_html($weekly_id);
}
else{
	$weekly_id = "";
}

if(isset($_GET['duration_type'])){
	$duration_type = $_GET['duration_type'];
	$duration_type = strip_tags(stripslashes($duration_type));
}
else{
	$duration_type = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_workout_plan - $l_workout_plans";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

	// Get workout plan weekly
	$weekly_id_mysql = quote_smart($link, $weekly_id);
	$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_text, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_text, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
	
	

	if($get_current_workout_weekly_id == ""){
		echo"<p>Weekly not found.</p>";
	}
	else{
		// User check
		if($get_current_workout_weekly_user_id != "$get_my_user_id" && $get_my_user_rank != "admin" && $get_my_user_rank != "moderator"){
			echo"
			<h1>Server error 403</h1>

			<p>Access denied. Only the owner, administrator or moderator can edit.</p>
			";
		}
		else{
		if($process == "1"){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);
			if(empty($inp_title)){
				$url = "weekly_workout_plan_edit_info.php?weekly_id=$weekly_id&duration_type=$duration_type=&l=$l";
				$url = $url . "&ft=error&fm=missing_title";
				header("Location: $url");
				exit;
			}
		
			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			// Period
			$inp_period_id = $_POST['inp_period_id'];
			$inp_period_id = output_html($inp_period_id);		
			$inp_period_id_mysql = quote_smart($link, $inp_period_id);


			// Introduction
			$inp_introduction = $_POST['inp_introduction'];
			$inp_introduction = output_html($inp_introduction);			
			$inp_introduction_mysql = quote_smart($link, $inp_introduction);

			
			// Update
			$result = mysqli_query($link, "UPDATE $t_workout_plans_weekly SET 
				 workout_weekly_title=$inp_title_mysql,
				 workout_weekly_title_clean=$inp_title_clean_mysql,
				 workout_weekly_period_id=$inp_period_id_mysql,
				 workout_weekly_introduction=$inp_introduction_mysql
				 WHERE workout_weekly_id=$weekly_id_mysql");



			// Purifier
			require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
			$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);

			if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator" OR $get_my_user_rank == "editor"){
			}
			elseif($get_my_user_rank == "trusted"){
			}
			else{
				// p, ul, li, b
				$config->set('HTML.Allowed', 'p,b,a[href],i,ul,li');
			}

			// Text
			$inp_text = $_POST['inp_text'];
			$inp_text = $purifier->purify($inp_text);

			// Goal
			$inp_goal = $_POST['inp_goal'];
			$inp_goal = $purifier->purify($inp_goal);


			$sql = "UPDATE $t_workout_plans_weekly SET workout_weekly_text=?, workout_weekly_goal=? WHERE workout_weekly_id=$weekly_id_mysql";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("ss", $inp_text, $inp_goal);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}

			// Search engine index
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='workout_plans' AND index_reference_name='workout_weekly_id' AND index_reference_id=$get_current_workout_weekly_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j. M Y H:i");

				$inp_index_title = "$inp_title | $l_workout_plans";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_title=$inp_index_title_mysql, 
								index_short_description=$inp_introduction_mysql,
								index_updated_datetime='$datetime',
								index_updated_datetime_print='$datetime_saying'
								 WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}

			// Make sure workout plan exists in feed
			include("new_workout_plan_weekly_include_insert_into_feed.php");


			// Header
			$url = "weekly_workout_plan_edit_info.php?weekly_id=$weekly_id&duration_type=$duration_type=&l=$l";
				$url = $url . "&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}

		echo"
		<h1>$get_current_workout_weekly_title</h1>
	

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
			&gt;
			<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
			&gt;
			<a href=\"weekly_workout_plan_edit_info.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_info</a>
			</p>
		<!-- //Where am I ? -->

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

		<h2>$l_info</h2>

		
		<!-- TinyMCE -->
			
				<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/tinymce/tinymce.min.js\"></script>
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


		<!-- Form -->

			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
			</script>
			<!-- //Focus -->


			<form method=\"post\" action=\"weekly_workout_plan_edit_info.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<p><b>$l_title*:</b><br />
			<input type=\"text\" name=\"inp_title\" size=\"40\" value=\"$get_current_workout_weekly_title\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>


			<p><b>$l_is_child_of_period_workout_plan:</b><br />
			<select name=\"inp_period_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"0\""; if($get_current_workout_weekly_period_id == "0"){ echo" selected=\"selected\""; } echo">$l_none</option>\n";

				$query = "SELECT workout_period_id, workout_period_title FROM $t_workout_plans_period WHERE workout_period_user_id=$my_user_id_mysql AND workout_period_language=$l_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_workout_period_id, $get_workout_period_title) = $row;
			
					echo"				";
					echo"<option value=\"$get_workout_period_id\""; if($get_current_workout_weekly_period_id == $get_workout_period_id){ echo" selected=\"selected\""; } echo">$get_workout_period_title</option>\n";
				}

			echo"
			</select>
			</p>

			<p><b>$l_introduction:</b><br />
			<textarea name=\"inp_introduction\" rows=\"10\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
			$get_current_workout_weekly_introduction = str_replace("<br />", "\n", $get_current_workout_weekly_introduction);
			echo"$get_current_workout_weekly_introduction</textarea>
			</p>

			<p><b>$l_text:</b><br />
			<textarea name=\"inp_text\" rows=\"10\" cols=\"70\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_workout_weekly_text</textarea>
			</p>

			<p><b>$l_goal:</b><br />
			<textarea name=\"inp_goal\" rows=\"10\" cols=\"70\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_workout_weekly_goal</textarea>
			</p>


			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			</form>
		<!-- //Form -->
		";
		} // access
	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/exercises/new_exercise.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>