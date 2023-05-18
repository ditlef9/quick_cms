<?php 
/**
*
* File: food/new_workout_plan_period.php
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
if(isset($_GET['period_id'])){
	$period_id = $_GET['period_id'];
	$period_id = output_html($period_id);
}
else{
	$period_id = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_workout_plan_period - $l_workout_plans";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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

	// Get workout plan period
	$period_id_mysql = quote_smart($link, $period_id);
	$query = "SELECT workout_period_id, workout_period_user_id, workout_period_yearly_id, workout_period_weight, workout_period_language, workout_period_title, workout_period_title_clean, workout_period_introduction, workout_period_goal, workout_period_text, workout_period_from, workout_period_to, workout_period_image_path, workout_period_image_file, workout_period_created, workout_period_updated, workout_period_unique_hits, workout_period_unique_hits_ip_block, workout_period_comments, workout_period_likes, workout_period_dislikes, workout_period_rating, workout_period_ip_block, workout_period_user_ip, workout_period_notes FROM $t_workout_plans_period WHERE workout_period_id=$period_id_mysql AND workout_period_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_workout_period_id, $get_current_workout_period_user_id, $get_current_workout_period_yearly_id, $get_current_workout_period_weight, $get_current_workout_period_language, $get_current_workout_period_title, $get_current_workout_period_title_clean, $get_current_workout_period_introduction, $get_current_workout_period_goal, $get_current_workout_period_text, $get_current_workout_period_from, $get_current_workout_period_to, $get_current_workout_period_image_path, $get_current_workout_period_image_file, $get_current_workout_period_created, $get_current_workout_period_updated, $get_current_workout_period_unique_hits, $get_current_workout_period_unique_hits_ip_block, $get_current_workout_period_comments, $get_current_workout_period_likes, $get_current_workout_period_dislikes, $get_current_workout_period_rating, $get_current_workout_period_ip_block, $get_current_workout_period_user_ip, $get_current_workout_period_notes) = $row;
	
	

	if($get_workout_period_id == ""){
		echo"<p>Period not found.</p>";
	}
	else{

		if($process == "1"){

			// Year
			$inp_yearly_id = $_POST['inp_yearly_id'];
			$inp_yearly_id = output_html($inp_yearly_id);			
			$inp_yearly_id_mysql = quote_smart($link, $inp_yearly_id);


			// Introduction
			$inp_introduction = $_POST['inp_introduction'];
			$inp_introduction = output_html($inp_introduction);			
			$inp_introduction_mysql = quote_smart($link, $inp_introduction);

			
			// Update
			$result = mysqli_query($link, "UPDATE $t_workout_plans_period SET workout_period_yearly_id=$inp_yearly_id_mysql,
				workout_period_introduction=$inp_introduction_mysql 
				 WHERE workout_period_id=$period_id_mysql");


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


			$sql = "UPDATE $t_workout_plans_period SET workout_period_goal=? WHERE workout_period_id=$period_id_mysql";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_goal);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}

			// Search engine index
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='workout_plans' AND index_reference_name='workout_period_id' AND index_reference_id=$get_workout_period_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j. M Y H:i");

				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_short_description=$inp_introduction_mysql 
								 WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}


			// Header
			$url = "new_workout_plan_period_step_3_text.php?period_id=$period_id&l=$l";
			header("Location: $url");
			exit;

		} // process
	
		echo"
		<h1>$l_new_period_workout_plan</h1>
	

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

			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_yearly_id\"]').focus();
				});
			</script>
			<!-- //Focus -->


			<form method=\"post\" action=\"new_workout_plan_period.php?period_id=$period_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	


			<p><b>$l_is_child_of_yearly_workout_plan:</b><br />
			<select name=\"inp_yearly_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"0\""; if($get_current_workout_period_yearly_id == "0"){ echo" selected=\"selected\""; } echo">$l_none</option>\n";

				$query = "SELECT workout_yearly_id, workout_yearly_title FROM $t_workout_plans_yearly WHERE workout_yearly_user_id=$my_user_id_mysql AND workout_yearly_language=$l_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_workout_yearly_id, $get_workout_yearly_title) = $row;
			
					echo"				";
					echo"<option value=\"$get_workout_yearly_id\""; if($get_current_workout_period_yearly_id == $get_workout_yearly_id){ echo" selected=\"selected\""; } echo">$get_workout_yearly_title</option>\n";
				}

			echo"
			</select>
			</p>

			<p><b>$l_introduction:</b><br />
			<textarea name=\"inp_introduction\" rows=\"10\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
			$get_current_workout_period_introduction = str_replace("<br />", "\n", $get_current_workout_period_introduction);
			echo"$get_current_workout_period_introduction</textarea>
			</p>

			<p><b>$l_goal:</b><br />
			<textarea name=\"inp_goal\" rows=\"10\" cols=\"70\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_workout_period_goal</textarea>
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