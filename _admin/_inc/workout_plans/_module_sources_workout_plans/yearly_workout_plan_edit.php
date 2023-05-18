<?php 
/**
*
* File: workout_plans/yearly_workout_plan_edit.php
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

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['yearly_id'])){
	$yearly_id = $_GET['yearly_id'];
	$yearly_id = output_html($yearly_id);
}
else{
	$yearly_id = "";
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
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get workout plan yearly
	$yearly_id_mysql = quote_smart($link, $yearly_id);
	$query = "SELECT workout_yearly_id, workout_yearly_user_id, workout_yearly_language, workout_yearly_title, workout_yearly_title_clean, workout_yearly_introduction, workout_yearly_goal, workout_yearly_text, workout_yearly_year, workout_yearly_image_path, workout_yearly_image_file, workout_yearly_created, workout_yearly_updated, workout_yearly_unique_hits, workout_yearly_unique_hits_ip_block, workout_yearly_comments, workout_yearly_likes, workout_yearly_dislikes, workout_yearly_rating, workout_yearly_ip_block, workout_yearly_user_ip, workout_yearly_notes FROM $t_workout_plans_yearly WHERE workout_yearly_id=$yearly_id_mysql AND workout_yearly_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_workout_yearly_id, $get_current_workout_yearly_user_id, $get_current_workout_yearly_language, $get_current_workout_yearly_title, $get_current_workout_yearly_title_clean, $get_current_workout_yearly_introduction, $get_current_workout_yearly_goal, $get_current_workout_yearly_text, $get_current_workout_yearly_year, $get_current_workout_yearly_image_path, $get_current_workout_yearly_image_file, $get_current_workout_yearly_created, $get_current_workout_yearly_updated, $get_current_workout_yearly_unique_hits, $get_current_workout_yearly_unique_hits_ip_block, $get_current_workout_yearly_comments, $get_current_workout_yearly_likes, $get_current_workout_yearly_dislikes, $get_current_workout_yearly_rating, $get_current_workout_yearly_ip_block, $get_current_workout_yearly_user_ip, $get_current_workout_yearly_notes) = $row;
	
	

	if($get_workout_yearly_id == ""){
		echo"<p>Workout yearly not found.</p>";
	}
	else{

		if($process == "1"){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);
			if(empty($inp_title)){
				$url = "new_workout_plan.php?l=$l";
				$url = $url . "&ft=error&fm=missing_title";
				header("Location: $url");
				exit;
			}
		
			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			// Year
			$inp_year = $_POST['inp_year'];
			if($inp_year == ""){
				$inp_year = date("Y");
			}
			$inp_year = output_html($inp_year);			
			$inp_year_mysql = quote_smart($link, $inp_year);


			// Introduction
			$inp_introduction = $_POST['inp_introduction'];
			$inp_introduction = output_html($inp_introduction);			
			$inp_introduction_mysql = quote_smart($link, $inp_introduction);

			
			// Update
			$result = mysqli_query($link, "UPDATE $t_workout_plans_yearly SET workout_yearly_title=$inp_title_mysql, 
				workout_yearly_title_clean=$inp_title_clean_mysql,
				workout_yearly_introduction=$inp_introduction_mysql, 
				workout_yearly_year=$inp_year_mysql WHERE workout_yearly_id=$yearly_id_mysql") or die(mysqli_error($link));



			// Purifier
			require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
			$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);

			if($get_user_rank == "admin" OR $get_user_rank == "moderator" OR $get_user_rank == "editor"){
			}
			elseif($get_user_rank == "trusted"){
			}
			else{
				// p, ul, li, b
				$config->set('HTML.Allowed', 'p,b,a[href],i,ul,li');
			}

			// Goal
			$inp_goal = $_POST['inp_goal'];
			$inp_goal = $purifier->purify($inp_goal);


			$sql = "UPDATE $t_workout_plans_yearly SET workout_yearly_goal=? WHERE workout_yearly_id=$yearly_id_mysql";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_goal);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}

			// Search engine index
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='workout_plans' AND index_reference_name='workout_yearly_id' AND index_reference_id=$get_workout_yearly_id";
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


			// Header
			$url = "yearly_workout_plan_edit.php?yearly_id=$yearly_id&duration_type=$duration_type&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		} // process
	
		echo"
		<h1>$get_current_workout_yearly_title</h1>
	

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
			&gt;
			<a href=\"yearly_workout_plan_edit.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_edit</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Navigation -->
			<div class=\"tabs\" style=\"margin-top: 6px;\">
				<ul>
					<li><a href=\"yearly_workout_plan_view.php?yearly_id=$yearly_id&amp;l=$l\">$l_view</a></li>
					<li><a href=\"yearly_workout_plan_edit.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_edit</a></li>
					<li><a href=\"yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_image</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"margin-bottom: 6px;\"></div>
		<!-- //Navigation -->

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
			
			<h2>$l_edit</h2>

			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
			</script>
			<!-- //Focus -->


			<form method=\"post\" action=\"yearly_workout_plan_edit.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<p><b>$l_title*:</b><br />
			<input type=\"text\" name=\"inp_title\" size=\"40\" value=\"$get_current_workout_yearly_title\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>



			<p><b>$l_year:</b><br />
			<input type=\"text\" name=\"inp_year\" size=\"10\" value=\"$get_current_workout_yearly_year\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>$l_introduction:</b><br />
			<textarea name=\"inp_introduction\" rows=\"10\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
			$get_current_workout_yearly_introduction = str_replace("<br />", "\n", $get_current_workout_yearly_introduction);
			echo"$get_current_workout_yearly_introduction</textarea>
			</p>

			<p><b>$l_goal:</b><br />
			<textarea name=\"inp_goal\" rows=\"10\" cols=\"70\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_workout_yearly_goal</textarea>
			</p>


			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			</form>
		<!-- //Form -->
		";
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