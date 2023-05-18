<?php 
/**
*
* File: workout_plans/weekly_workout_plan_sessions.php
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['weekly_id'])){
	$weekly_id = $_GET['weekly_id'];
	$weekly_id = output_html($weekly_id);
}
else{
	$weekly_id = "";
}
if(isset($_GET['session_id'])){
	$session_id = $_GET['session_id'];
	$session_id = output_html($session_id);
}
else{
	$session_id = "";
}
if(isset($_GET['session_main_id'])){
	$session_main_id = $_GET['session_main_id'];
	$session_main_id = output_html($session_main_id);
}
else{
	$session_main_id = "";
}

if(isset($_GET['type_id'])){
	$type_id = $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
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
	$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
	
	

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
			if($action == ""){
				if($process == "1"){

					// Period
					$inp_period_id = $_POST['inp_period_id'];
					$inp_period_id = output_html($inp_period_id);		
					$inp_period_id_mysql = quote_smart($link, $inp_period_id);
				
					// Update
					$result = mysqli_query($link, "UPDATE $t_workout_plans_weekly SET workout_weekly_period_id=$inp_period_id_mysql,
						workout_weekly_introduction=$inp_weekly_mysql, 
						 WHERE workout_weekly_id=$weekly_id_mysql");
					// Header
					$url = "weekly_workout_plan_edit_sessions.php?period_id=$period_id&l=$l";
					header("Location: $url");
					exit;

				} // process
	
				echo"
				<h1>$get_current_workout_weekly_title</h1>
	

				<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
				&gt;
				<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
				&gt;
				<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
				</p>
				<!-- //Where am I ? -->

	

				<h2>$l_sessions</h2>
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


				<p>
				<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=new_session&amp;l=$l\" class=\"btn btn_default\">$l_new_session</a>
				</p>

				<!-- List all sessions -->
					<table class=\"hor-zebra\">
					 <thead>
					  <tr>
					   <th scope=\"col\">
						<span>$l_title</span>
					   </th>
					   <th scope=\"col\">
						<span>$l_duration</span>
				 	  </th>
					   <th scope=\"col\">
						<span>$l_actions</span>
					   </th>
					  </tr>
					 </thead>
					 <tbody>";

					$human_counter = 1;
					$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_weekly_id=$get_current_workout_weekly_id ORDER BY workout_session_weight ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_workout_session_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity) = $row;

						// Style
						if(isset($style) && $style == ""){
							$style = "odd";
						}
						else{
							$style = "";
						}
	

						echo"
						<tr>
						  <td class=\"$style\">	
							<span><a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$get_workout_session_id&amp;l=$l\">$get_workout_session_title</a>
						  </td>
						  <td class=\"$style\">
							<span>$get_workout_session_duration</span>
						  </td>
						  <td class=\"$style\">
							<span>";
						
							if($human_counter != 1){
								echo"
								<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=move_session_up&amp;session_id=$get_workout_session_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/go-up.png\" alt=\"go-up.png\" title=\"$l_move_up\" /></a>
								";
							}
							else{
								echo"
								<img src=\"_gfx/icons/go-up-transparent.png\" alt=\"go-up-transparent.png\" title=\"$l_move_up\" />
								";
							}
								echo"
								<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=move_session_down&amp;session_id=$get_workout_session_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/go-down.png\" alt=\"go-down.png\" title=\"$l_move_down\" /></a>
							
								";


							echo"
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=delete_session&amp;session_id=$get_workout_session_id&amp;l=$l\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" title=\"$l_delete\" /></a>
							</span>
						";


						if($human_counter != $get_workout_session_weight){
							$res = mysqli_query($link, "UPDATE $t_workout_plans_sessions SET workout_session_weight='$human_counter' WHERE workout_session_id='$get_workout_session_id'");

							echo"
							<span>&middot; <a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$get_workout_session_id&amp;l=$l\">$get_workout_session_weight</a></span> ";
							//<meta http-equiv=\"refresh\" content=\"1;url=weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;session_id=$get_workout_session_id&amp;l=$l\">
							//<span><a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$get_workout_session_id&amp;l=$l\">$get_workout_session_title</a>
							//";

						}
						echo"
						 </td>
						</tr>
						";
						$human_counter++;
					}
					echo"
					 </tbody>
					</table>

			
				<!-- //List all sessions -->

				<p>
				<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=new_session&amp;l=$l\" class=\"btn btn_default\">$l_new_session</a>
				</p>
				";
			}
			elseif($action == "new_session"){
				if($process == "1"){

					// Title
					$inp_title = $_POST['inp_title'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					// Title clean
					$inp_title_clean = clean($inp_title);
					$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);


					// Duration
					$inp_duration = $_POST['inp_duration'];
					$inp_duration = output_html($inp_duration);		
					$inp_duration_mysql = quote_smart($link, $inp_duration);

					// Intensity
					$inp_intensity = $_POST['inp_intensity'];
					$inp_intensity = output_html($inp_intensity);		
					$inp_intensity_mysql = quote_smart($link, $inp_intensity);

					// Warmup
					$inp_warmup = $_POST['inp_warmup'];
					$inp_warmup = output_html($inp_warmup);		
					$inp_warmup_mysql = quote_smart($link, $inp_warmup);

					// End
					$inp_end = $_POST['inp_end'];
					$inp_end = output_html($inp_end);		
					$inp_end_mysql = quote_smart($link, $inp_end);

					// Get workout_session_weight
					$query_t = "SELECT workout_session_weight FROM $t_workout_plans_sessions WHERE workout_session_user_id=$get_current_workout_weekly_user_id AND workout_session_weekly_id='$get_current_workout_weekly_id' ORDER BY workout_session_weight DESC";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_workout_session_weight) = $row_t;
					$inp_workout_session_weight = $get_workout_session_weight+1;

					// Insert
					mysqli_query($link, "INSERT INTO $t_workout_plans_sessions
					(workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity,
					workout_session_warmup, workout_session_end) 
					VALUES 
					(NULL, $get_current_workout_weekly_user_id, '$get_current_workout_weekly_id', $inp_workout_session_weight, $inp_title_mysql, $inp_title_clean_mysql, $inp_duration_mysql, $inp_intensity_mysql,
					$inp_warmup_mysql, $inp_end_mysql)
					")
					or die(mysqli_error($link));

					// Get ID
					$query_t = "SELECT workout_session_id FROM $t_workout_plans_sessions WHERE workout_session_user_id=$get_current_workout_weekly_user_id AND workout_session_weekly_id='$get_current_workout_weekly_id' AND workout_session_title=$inp_title_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_workout_session_id) = $row_t;

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

					// Goal
					$inp_goal = $_POST['inp_goal'];
					$inp_goal = $purifier->purify($inp_goal);


					$sql = "UPDATE $t_workout_plans_sessions SET workout_session_goal=? WHERE workout_session_id=$get_workout_session_id";
					$stmt = $link->prepare($sql);
					$stmt->bind_param("s", $inp_goal);
					$stmt->execute();
					if ($stmt->errno) {
						echo "FAILURE!!! " . $stmt->error; die;
					}


					// Header
					$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&l=$l&ft=success&fm=session_created";
					header("Location: $url");
					exit;

				} // process
	
				echo"
				<h1>$get_current_workout_weekly_title</h1>
	

				<!-- Where am I ? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
					&gt;
					<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
					&gt;
					<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
					&gt;
					<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;action=$action&amp;l=$l\">$l_new_session</a>
					</p>
				<!-- //Where am I ? -->


				<h2>$l_new_session</h2>
	

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
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_title\"]').focus();
					});
					</script>


					<form method=\"post\" action=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=new_session&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

					<p><b>$l_session_title:</b><br />
					<input type=\"text\" name=\"inp_title\" size=\"30\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>

					<p><b>$l_duration:</b><br />
					<input type=\"text\" name=\"inp_duration\" size=\"30\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>

					<p><b>$l_intensity:</b><br />
					<input type=\"text\" name=\"inp_intensity\" size=\"30\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> %
					</p>

					<p><b>$l_goal:</b><br />
					<textarea name=\"inp_goal\" rows=\"10\" cols=\"70\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
					</p>

					<p><b>$l_warmup:</b><br />
					<textarea name=\"inp_warmup\" rows=\"10\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
					</p>

					<p><b>$l_cooldown:</b><br />
					<textarea name=\"inp_end\" rows=\"10\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
					</p>



					<p>
					<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>

					</form>
				<!-- //Form -->

				<!-- Back -->
					<p>
					<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;l=$l\" class=\"btn btn_default\">$l_sessions</a>
					<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\" class=\"btn btn_default\">$l_next</a>
					</p>
				<!-- //Back -->
				";
			} // new
			elseif($action == "move_session_down"){

				// Get the session I want to move
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql AND workout_session_weekly_id=$get_current_workout_weekly_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_original_workout_session_id, $get_original_workout_session_weight, $get_original_workout_session_title, $get_original_workout_session_title_clean, $get_original_workout_session_duration, $get_original_workout_session_intensity) = $row;

				// Find the workout session I need to swap with
				$swap_session_weight = $get_original_workout_session_weight+1;
				$swap_session_weight_mysql = quote_smart($link, $swap_session_weight);
				$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_weight=$swap_session_weight_mysql AND workout_session_weekly_id=$get_current_workout_weekly_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_swap_workout_session_id, $get_swap_workout_session_weight, $get_swap_workout_session_title, $get_swap_workout_session_title_clean, $get_swap_workout_session_duration, $get_swap_workout_session_intensity) = $row;

				if($get_original_workout_session_id != "" && $get_swap_workout_session_id != ""){
					// Update Original
					$inp_weight_mysql = quote_smart($link, $get_swap_workout_session_weight);
					$res = mysqli_query($link, "UPDATE $t_workout_plans_sessions SET workout_session_weight=$inp_weight_mysql WHERE workout_session_id=$get_original_workout_session_id");

					// Update swap
					$inp_weight_mysql = quote_smart($link,  $get_original_workout_session_weight);
					$res = mysqli_query($link, "UPDATE $t_workout_plans_sessions SET workout_session_weight=$inp_weight_mysql WHERE workout_session_id=$get_swap_workout_session_id");
				}



				// Header
				$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&l=$l";
				header("Location: $url");
				exit;
			} // move_session_down
			elseif($action == "move_session_up"){
			
				// Get the session I want to move
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql AND workout_session_weekly_id=$get_current_workout_weekly_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_original_workout_session_id, $get_original_workout_session_weight, $get_original_workout_session_title, $get_original_workout_session_title_clean, $get_original_workout_session_duration, $get_original_workout_session_intensity) = $row;

				// Find the workout session I need to swap with
				$swap_session_weight = $get_original_workout_session_weight-1;
				$swap_session_weight_mysql = quote_smart($link, $swap_session_weight);
				$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_weight=$swap_session_weight_mysql AND workout_session_weekly_id=$get_current_workout_weekly_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_swap_workout_session_id, $get_swap_workout_session_weight, $get_swap_workout_session_title, $get_swap_workout_session_title_clean, $get_swap_workout_session_duration, $get_swap_workout_session_intensity) = $row;

				if($get_original_workout_session_id != "" && $get_swap_workout_session_id != ""){
					// Update Original
					$inp_weight_mysql = quote_smart($link, $get_swap_workout_session_weight);
					$res = mysqli_query($link, "UPDATE $t_workout_plans_sessions SET workout_session_weight=$inp_weight_mysql WHERE workout_session_id=$get_original_workout_session_id");

					// Update swap
					$inp_weight_mysql = quote_smart($link,  $get_original_workout_session_weight);
					$res = mysqli_query($link, "UPDATE $t_workout_plans_sessions SET workout_session_weight=$inp_weight_mysql WHERE workout_session_id=$get_swap_workout_session_id");
				}


				// Header
				$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&l=$l";
				header("Location: $url");
				exit;
			} // move_session_down
			elseif($action == "edit_session"){
				// Get session
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity, workout_session_repeat, workout_session_pause, workout_session_goal, workout_session_warmup, workout_session_end FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_workout_session_id, $get_current_workout_session_user_id, $get_current_workout_session_weekly_id, $get_current_workout_session_weight, $get_current_workout_session_title, $get_current_workout_session_title_clean, $get_current_workout_session_duration, $get_current_workout_session_intensity, $get_current_workout_session_repeat, $get_current_workout_session_pause, $get_current_workout_session_goal, $get_current_workout_session_warmup, $get_current_workout_session_end) = $row;
	
				if($get_current_workout_session_id == ""){
					echo"Session not found.";
				}
				else{

					// User check
					if($get_current_workout_session_user_id != "$get_my_user_id" && $get_my_user_rank != "admin" && $get_my_user_rank != "moderator"){
						echo"
						<h1>Server error 403</h1>

						<p>Access denied. Only the owner, administrator or moderator can edit.</p>
						";
					}
					else{


						if($process == "1"){
	
							// Title
							$inp_title = $_POST['inp_title'];
							$inp_title = output_html($inp_title);
							$inp_title_mysql = quote_smart($link, $inp_title);
	
							// Title clean
							$inp_title_clean = clean($inp_title);
							$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

							// Duration
							$inp_duration = $_POST['inp_duration'];
							$inp_duration = output_html($inp_duration);		
							$inp_duration_mysql = quote_smart($link, $inp_duration);

							// Intensity
							$inp_intensity = $_POST['inp_intensity'];
							$inp_intensity = output_html($inp_intensity);		
							$inp_intensity_mysql = quote_smart($link, $inp_intensity);

							// Pause
							$inp_pause = $_POST['inp_pause'];
							$inp_pause = output_html($inp_pause);		
							$inp_pause_mysql = quote_smart($link, $inp_pause);

							// Repeat
							$inp_repeat = $_POST['inp_repeat'];
							$inp_repeat = output_html($inp_repeat);		
							$inp_repeat_mysql = quote_smart($link, $inp_repeat);

							// Warmup
							$inp_warmup = $_POST['inp_warmup'];
							$inp_warmup = output_html($inp_warmup);		
							$inp_warmup_mysql = quote_smart($link, $inp_warmup);

							// End
							$inp_end = $_POST['inp_end'];
							$inp_end = output_html($inp_end);		
							$inp_end_mysql = quote_smart($link, $inp_end);


							// Update
							$res = mysqli_query($link, "UPDATE $t_workout_plans_sessions SET workout_session_title=$inp_title_mysql, 
									workout_session_title_clean=$inp_title_clean_mysql, workout_session_duration=$inp_duration_mysql, 
									workout_session_intensity=$inp_intensity_mysql,
									workout_session_pause=$inp_pause_mysql,
									workout_session_repeat=$inp_repeat_mysql,
									workout_session_warmup=$inp_warmup_mysql,
									workout_session_end=$inp_end_mysql
									 WHERE workout_session_id='$get_current_workout_session_id'");


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

							// Goal
							$inp_goal = $_POST['inp_goal'];
							$inp_goal = $purifier->purify($inp_goal);


							$sql = "UPDATE $t_workout_plans_sessions SET workout_session_goal=? WHERE workout_session_id='$get_current_workout_session_id'";
							$stmt = $link->prepare($sql);
							$stmt->bind_param("s", $inp_goal);
							$stmt->execute();
							if ($stmt->errno) {
								echo "FAILURE!!! " . $stmt->error; die;
							}

	
							// Header
							$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&action=edit_session&session_id=$session_id&l=$l&ft=success&fm=changes_saved";
							header("Location: $url");
							exit;

						} // process
	
						echo"
						<h1>$get_current_workout_weekly_title</h1>
		

						<!-- Where am I ? -->
							<p><b>$l_you_are_here:</b><br />
							<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
							&gt;
							<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;session_id=$session_id&amp;duration_type=$duration_type&amp;action=$action&amp;l=$l\">$l_edit_session</a>
							</p>
						<!-- //Where am I ? -->

						<h2>$get_current_workout_session_title</h2>
	

						<!-- Current session navigation -->
							<p>
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;l=$l\">$l_exercises</a>
							&middot;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=edit_session&amp;session_id=$session_id&amp;l=$l\" style=\"font-weight: bold;\">$l_info</a>
							</p>
						<!-- //Current session navigation -->


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
							<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/tinymce/tinymce_4.7.1/tinymce.min.js\"></script>
							<script>
							tinymce.init({
							mode : \"specific_textareas\",
        						editor_selector : \"myTextEditor\",
							plugins: \"image\",
							menubar: \"insert\",
							toolbar: \"image\",
							height: 200,
							menubar: false,";
							if($get_my_user_rank == "admin" OR $get_my_user_rank== "moderator" OR $get_my_user_rank== "editor"){
							echo"
							plugins: [
				 			   'advlist autolink lists link image charmap print preview anchor textcolor',
							    'searchreplace visualblocks code fullscreen',
							    'insertdatetime media table contextmenu paste code help'
							  ],
							  toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
							  content_css: [
							    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
							    '//www.tinymce.com/css/codepen.min.css']
							";
							}
							elseif($get_my_user_rank == "trusted"){
							echo"
							plugins: [
							    'advlist autolink lists link image charmap print preview anchor textcolor',
							    'searchreplace visualblocks code fullscreen',
							    'insertdatetime media table contextmenu paste code help'
							  ],
							  toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
							  content_css: [
							    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
							    '//www.tinymce.com/css/codepen.min.css']
							";
							}
							else{
							echo"
							plugins: [
							    'advlist autolink lists link image charmap print preview anchor textcolor',
							    'searchreplace visualblocks code fullscreen',
							    'insertdatetime media table contextmenu paste code help'
							  ],
							  toolbar: 'bold | bullist',
							  content_css: [
							    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
							    '//www.tinymce.com/css/codepen.min.css']
							";
							}
							echo"
							});
							</script>
						<!-- //TinyMCE -->

						<!-- Form -->
							<script>
								\$(document).ready(function(){
								\$('[name=\"inp_title\"]').focus();
								});
							</script>


							<form method=\"post\" action=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=edit_session&amp;session_id=$session_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

							<p><b>$l_session_title:</b><br />
							<input type=\"text\" name=\"inp_title\" size=\"30\" value=\"$get_current_workout_session_title\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							</p>

							<p><b>$l_duration:</b><br />
							<input type=\"text\" name=\"inp_duration\" size=\"30\" value=\"$get_current_workout_session_duration\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							</p>

							<p><b>$l_intensity:</b><br />
							<input type=\"text\" name=\"inp_intensity\" size=\"30\" value=\"$get_current_workout_session_intensity\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> %
							</p>

							<p><b>$l_repeat:</b><br />
							<textarea name=\"inp_repeat\" rows=\"4\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
							$get_current_workout_session_repeat = str_replace("<br />", "\n", $get_current_workout_session_repeat);
							echo"$get_current_workout_session_repeat</textarea>
							</p>

							<p><b>$l_pause:</b><br />
							<textarea name=\"inp_pause\" rows=\"4\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
							$get_current_workout_session_pause = str_replace("<br />", "\n", $get_current_workout_session_pause);
							echo"$get_current_workout_session_pause</textarea>
							</p>

							<p><b>$l_goal:</b><br />
							<textarea name=\"inp_goal\" rows=\"10\" cols=\"70\" class=\"myTextEditor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_workout_session_goal</textarea>
							</p>


							<p><b>$l_warmup:</b><br />
							<textarea name=\"inp_warmup\" rows=\"10\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
							$get_current_workout_session_warmup = str_replace("<br />", "\n", $get_current_workout_session_warmup);
							echo"$get_current_workout_session_warmup</textarea>
							</p>

							<p><b>$l_cooldown:</b><br />
							<textarea name=\"inp_end\" rows=\"10\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
							$get_current_workout_session_end = str_replace("<br />", "\n", $get_current_workout_session_end);
							echo"$get_current_workout_session_end</textarea></textarea>
							</p>


							<p>
							<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							</p>

							</form>
						<!-- //Form -->
	
						<!-- Back -->
							<p>
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;l=$l\" class=\"btn btn_default\">$l_sessions</a>
							<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\" class=\"btn btn_default\">$l_next</a>
							</p>
						<!-- //Back -->
						";
					} // access to session
				} //session found
			} // edit
			elseif($action == "delete_session"){
				// Get session
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity, workout_session_goal, workout_session_warmup, workout_session_end FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql AND workout_session_user_id=$get_current_workout_weekly_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_workout_session_id, $get_current_workout_session_user_id, $get_current_workout_session_weekly_id, $get_current_workout_session_weight, $get_current_workout_session_title, $get_current_workout_session_title_clean, $get_current_workout_session_duration, $get_current_workout_session_intensity, $get_current_workout_session_goal, $get_current_workout_session_warmup, $get_current_workout_session_end) = $row;
	
				if($get_workout_session_id == ""){
					echo"Session not found.";
				}
				else{


					if($process == "1"){
	
						$res = mysqli_query($link, "DELETE FROM $t_workout_plans_sessions WHERE workout_session_id='$get_workout_session_id'");

						// Header
						$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&l=$l&ft=success&fm=deleted";
						header("Location: $url");
						exit;
	
					} // process
	
					echo"
					<h1>$get_current_workout_weekly_title</h1>
	

					<!-- Where am I ? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
					&gt;
					<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
					&gt;
					<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
					&gt;
					<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;session_id=$session_id&amp;duration_type=$duration_type&amp;action=$action&amp;l=$l\">$l_edit_session</a>
					</p>
					<!-- //Where am I ? -->


					<h2>$get_current_workout_session_title</h2>
	
					<p>
					$l_are_you_sure_you_want_to_delete
					</p>

					<p>
					<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=delete_session&amp;session_id=$session_id&amp;process=1&amp;l=$l\" class=\"btn btn_warning\">$l_delete</a>
					</p>
	
					";
				} //session found
			} // edit
			elseif($action == "open_session"){
				// Get session
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity, workout_session_goal, workout_session_warmup, workout_session_end FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_workout_session_id, $get_current_workout_session_user_id, $get_current_workout_session_weekly_id, $get_current_workout_session_weight, $get_current_workout_session_title, $get_current_workout_session_title_clean, $get_current_workout_session_duration, $get_current_workout_session_intensity, $get_current_workout_session_goal, $get_current_workout_session_warmup, $get_current_workout_session_end) = $row;
	
				if($get_workout_session_id == ""){
					echo"Session not found.";
				}
				else{

					// User check
					if($get_current_workout_session_user_id != "$get_my_user_id" && $get_my_user_rank != "admin" && $get_my_user_rank != "moderator"){
						echo"
						<h1>Server error 403</h1>

						<p>Access denied. Only the owner, administrator or moderator can edit.</p>
						";
					}
					else{
	
						echo"
	
						<h1>$get_current_workout_weekly_title</h1>
	

						<!-- Where am I ? -->
							<p><b>$l_you_are_here:</b><br />
							<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
							&gt;
							<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$get_current_workout_session_title</a>
							</p>
						<!-- //Where am I ? -->

						<h2>$get_current_workout_session_title</h2>
	


						<!-- Current session navigation -->
							<p>
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;l=$l\" style=\"font-weight: bold;\">$l_exercises</a>
							&middot;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=edit_session&amp;session_id=$session_id&amp;l=$l\">$l_info</a>
							</p>
						<!-- //Current session navigation -->



						<!-- List sessions_main -->
							<p>
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\" class=\"btn btn_default\">$l_add_exercise</a>
							</p>

							<table class=\"hor-zebra\">
							 <thead>
							  <tr>
							   <th scope=\"col\">
								<span>$l_exercise</span>
							   </th>
							   <th scope=\"col\">
							   </th>
							   <th scope=\"col\">
								<span>$l_actions</span>
							   </th>
							  </tr>
							 </thead>
							 <tbody>";

							$x = 0;
							$query = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_sets, workout_session_main_reps, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$get_workout_session_id ORDER BY workout_session_main_weight ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_workout_session_main_id, $get_workout_session_main_user_id, $get_workout_session_main_session_id, $get_workout_session_main_weight, $get_workout_session_main_exercise_id, $get_workout_session_main_exercise_title, $get_workout_session_main_sets, $get_workout_session_main_reps, $get_workout_session_main_velocity_a, $get_workout_session_main_velocity_b, $get_workout_session_main_distance, $get_workout_session_main_duration, $get_workout_session_main_intensity, $get_workout_session_main_text) = $row;

								// Style
								if(isset($style) && $style == ""){
									$style = "odd";
								}
								else{
									$style = "";
								}


								echo"
								<tr>
								  <td class=\"$style\">
									<span><a href=\"$root/exercises/view_exercise.php?exercise_id=$get_workout_session_main_exercise_id&amp;l=$l\">$get_workout_session_main_exercise_title</a>";
									if($get_workout_session_main_text != ""){
										echo"<br />$get_workout_session_main_text\n";
									}
									echo"
									</span>
						
					 			 </td>
								  <td class=\"$style\">
									<span>
									";
									$middot = false;
									if($get_workout_session_main_sets != 0 && $get_workout_session_main_reps != 0){
										echo"$get_workout_session_main_sets x $get_workout_session_main_reps\n";
										$middot = true;
									}
									if($get_workout_session_main_velocity_a != 0 && $get_workout_session_main_velocity_b != 0){
										if($middot == "true"){
											echo" &middot; ";
										}
										$middot = true;
										echo"$get_workout_session_main_velocity_a - $get_workout_session_main_velocity_b km/h\n";
									}
									else{
										if($get_workout_session_main_velocity_a != 0){
											if($middot == "true"){
												echo" &middot; ";
										}
										$middot = true;
											echo"$get_workout_session_main_velocity_a km/h\n";
										}
										if($get_workout_session_main_velocity_b != 0){
											if($middot == "true"){
												echo" &middot; ";
											}
											$middot = true;
											echo"$get_workout_session_main_velocity_b km/h\n";
										}
									}
									if($get_workout_session_main_distance != 0){
										if($middot == "true"){
											echo" &middot; ";
										}
										$middot = true;
										echo"$get_workout_session_main_distance m\n";
									}
									if($get_workout_session_main_duration != 0){
										if($middot == "true"){
											echo" &middot; ";
										}
										$middot = true;
										echo"$get_workout_session_main_duration $l_min_lowercase\n";
									}
									if($get_workout_session_main_intensity != 0){
										if($middot == "true"){
											echo" &middot; ";
										}
										$middot = true;
										echo"$get_workout_session_main_intensity %\n";
									}
									echo"
									</span>
						
								  </td>
								  <td class=\"$style\">
									<span>";
						
									if($x != 0){
										echo"
										<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=move_session_main_up&amp;session_id=$get_workout_session_id&amp;session_main_id=$get_workout_session_main_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/go-up.png\" alt=\"go-up.png\" title=\"$l_move_up\" /></a>
										
										";
									}
									else{
										echo"
										<img src=\"_gfx/icons/go-up-transparent.png\" alt=\"go-up-transparent.png\" title=\"$l_move_up\" />
										";
									}
									echo"
									<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=move_session_main_down&amp;session_id=$get_workout_session_id&amp;session_main_id=$get_workout_session_main_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/go-down.png\" alt=\"go-down.png\" title=\"$l_move_down\" /></a>
						
									";
									echo"
									<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=edit_session_main&amp;session_id=$get_workout_session_id&amp;session_main_id=$get_workout_session_main_id&amp;l=$l\"><img src=\"_gfx/icons/edit.png\" alt=\"edit.png\" title=\"$l_edit\" /></a>
						
									<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=delete_session_main&amp;session_id=$get_workout_session_id&amp;session_main_id=$get_workout_session_main_id&amp;l=$l\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" title=\"$l_delete\" /></a>
									</span>
								 </td>
								</tr>
								";


								if($x != $get_workout_session_main_weight){
									$res = mysqli_query($link, "UPDATE $t_workout_plans_sessions_main SET workout_session_main_weight='$x' WHERE workout_session_main_id='$get_workout_session_main_id'");


								}
								$x++;
							}
							echo"
							 </tbody>
							</table>
			
						<!-- List sessions_main -->

						<p>
						<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;l=$l\" class=\"btn btn_default\">$l_add_exercise</a>
						</p>

						";
					} // access to session
				} //session found
			} // open_session
			elseif($action == "add_exercise_to_session"){
				// Get session
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity, workout_session_goal, workout_session_warmup, workout_session_end FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql AND workout_session_user_id=$get_current_workout_weekly_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_workout_session_id, $get_current_workout_session_user_id, $get_current_workout_session_weekly_id, $get_current_workout_session_weight, $get_current_workout_session_title, $get_current_workout_session_title_clean, $get_current_workout_session_duration, $get_current_workout_session_intensity, $get_current_workout_session_goal, $get_current_workout_session_warmup, $get_current_workout_session_end) = $row;
	
				if($get_workout_session_id == ""){
					echo"Session not found.";
				}
				else{
					// Muscle group ID variable
					if(isset($_GET['main_muscle_group_id'])){
						$main_muscle_group_id = $_GET['main_muscle_group_id'];
						$main_muscle_group_id = strip_tags(stripslashes($main_muscle_group_id));
					}
					else{
						$main_muscle_group_id = "";
					}


				

					if($mode == ""){
						echo"
						<h1>$get_current_workout_weekly_title</h1>

						<!-- Where am I ? -->
							<p><b>$l_you_are_here:</b><br />
							<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
							&gt;
							<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$get_current_workout_session_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$l_add_exercise</a>
							</p>
						<!-- //Where am I ? -->

						<!-- Search for exercise -->
							<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
							\$(document).ready(function () {
								\$('#nettport_inp_search_query').keyup(function () {
        								var searchString    = $(\"#nettport_inp_search_query\").val();
       									var data            = 'weekly_id=$weekly_id&session_id=$session_id&l=$l&q='+ searchString;
         
        								// if searchString is not empty
        								if(searchString) {
           									// ajax call
            									\$.ajax({
                									type: \"POST\",
               										url: \"weekly_workout_plan_edit_sessions_search_for_exercise_jquery.php\",
                									data: data,
											beforeSend: function(html) { // this happens before actual call
												\$(\"#nettport_search_results\").html(''); 
											},
               										success: function(html){
                    										\$(\"#nettport_search_results\").html(html);
              										}
            									});
       									}
        								return false;
            							});
								\$('[name=\"q\"]').focus();
            						});
							</script>


							<form method=\"post\" action=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;l=$l\" enctype=\"multipart/form-data\">
							<p><b>$l_search_for_exercise:</b><br />
							<input type=\"text\" name=\"q\" value=\"\" size=\"20\" id=\"nettport_inp_search_query\" />
							<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" />
							</p>
							</form>
							<div id=\"nettport_search_results\">
							</div>
						<!-- //Search for exercise -->

						<!-- Browse for exercise: Select type -->
							<p><b>$l_dot_dot_dot_or_select_type_of_exercises:</b></p>
							<div class=\"vertical\">
								<ul>";
								// Get all types
								$query_sub = "SELECT type_id, type_title FROM $t_exercise_types ORDER BY type_title ASC";
								$result_sub = mysqli_query($link, $query_sub);
								while($row_sub = mysqli_fetch_row($result_sub)) {
									list($get_type_id, $get_type_title) = $row_sub;
	
									// Translation
									$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id='$get_type_id' AND type_translation_language=$l_mysql";
									$result_translation = mysqli_query($link, $query_translation);
									$row_translation = mysqli_fetch_row($result_translation);
									list($get_type_translation_id, $get_type_translation_value) = $row_translation;
	
									// Type empty?
									if($type_id == ""){
										$type_id = "$get_type_id";
										$type_id_mysql = quote_smart($link, $type_id);
									}
									echo"								";
									echo"<li><a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$get_type_id&amp;mode=step_2_muscle_group&amp;l=$l\">$get_type_translation_value</a></li>\n";

								}

								echo"
								</ul>
							</div>
						<!-- //Browse for exercise: Select type -->

						";
					} // mode == ""
					elseif($mode == "step_2_muscle_group"){
						echo"
					
						<h1>$get_current_workout_weekly_title</h1>

						<!-- Where am I ? -->
							<p><b>$l_you_are_here:</b><br />
							<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
							&gt;
							<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$get_current_workout_session_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$l_add_exercise</a>
							&gt;";

							// Translation
							$type_id_mysql = quote_smart($link, $type_id);
							$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id=$type_id_mysql AND type_translation_language=$l_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_type_translation_id, $get_type_translation_value) = $row_translation;

							echo"
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;mode=step_2_muscle_group&amp;l=$l\">$get_type_translation_value</a>
							</p>
						<!-- //Where am I ? -->


						<!-- Search for exercise -->
							<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
							\$(document).ready(function () {
								\$('#nettport_inp_search_query').keyup(function () {
        								var searchString    = $(\"#nettport_inp_search_query\").val();
       									var data            = 'weekly_id=$weekly_id&session_id=$session_id&l=$l&q='+ searchString;
         
        								// if searchString is not empty
        								if(searchString) {
           									// ajax call
            									\$.ajax({
                									type: \"POST\",
               										url: \"weekly_workout_plan_edit_sessions_search_for_exercise_jquery.php\",
                									data: data,
											beforeSend: function(html) { // this happens before actual call
												\$(\"#nettport_search_results\").html(''); 
											},
               										success: function(html){
                    										\$(\"#nettport_search_results\").append(html);
              										}
            									});
       									}
        								return false;
            							});
            						});
							</script>


							<form method=\"post\" action=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;l=$l\" enctype=\"multipart/form-data\">
							<p><b>$l_search_for_exercise:</b><br />
							<input type=\"text\" name=\"q\" value=\"\" size=\"20\" id=\"nettport_inp_search_query\" />
							<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" />
							</p>
							</form>
							<div id=\"nettport_search_results\">
							</div>
						<!-- //Search for exercise -->

						

						<!-- Browse for exercise: Select muscle group -->
							<p><b>$l_select_muscle_group ($get_type_translation_value):</b></p>
							<div class=\"vertical\">
								<ul>";
								// Get all muscle groups
								$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0' ORDER BY muscle_group_name ASC";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;

									// Translation
									$query_translation = "SELECT muscle_group_translation_id, muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
									$result_translation = mysqli_query($link, $query_translation);
									$row_translation = mysqli_fetch_row($result_translation);
									list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;
	
									echo"								";
									echo"<li><a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;main_muscle_group_id=$get_main_muscle_group_id&amp;mode=step_3_exercises&amp;l=$l\">$get_main_muscle_group_translation_name</a></li>\n";

								}

								echo"
								</ul>
							</div>
						<!-- //Browse for exercise: Select muscle group -->

						";
					} // $mode == "step_2_muscle_group"
					elseif($mode == "step_3_exercises"){
						echo"
						<h1>$l_add_exercise $get_current_workout_weekly_title</h1>

						<!-- Where am I ? -->
							<p><b>$l_you_are_here:</b><br />
							<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
							&gt;
							<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$get_current_workout_session_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$l_add_exercise</a>
							&gt;";

							// Translation
							$type_id_mysql = quote_smart($link, $type_id);
							$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id=$type_id_mysql AND type_translation_language=$l_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_type_translation_id, $get_type_translation_value) = $row_translation;

							// Muscle group translation
							$main_muscle_group_id_mysql = quote_smart($link, $main_muscle_group_id);
							$query_translation = "SELECT muscle_group_translation_id, muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$main_muscle_group_id_mysql AND muscle_group_translation_language=$l_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;
							echo"
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;mode=step_2_muscle_group&amp;l=$l\">$get_type_translation_value</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;main_muscle_group_id=13&amp;mode=step_3_exercises&amp;l=$l\">$get_main_muscle_group_translation_name</a>
							</p>
						<!-- //Where am I ? -->

						<!-- Quick menu for navigating -->
							<p>
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;l=$l\">$l_sessions</a>
							</p>
						<!-- //Quick menu for navigating -->

						<!-- Feedback -->
							";
							if($ft != ""){
								if($fm == "changes_saved"){
									$fm = "$l_changes_saved";
								}
								elseif($fm == "added"){
									// Find last exersice
									$query = "SELECT workout_session_main_id, workout_session_main_exercise_title FROM $t_workout_plans_sessions_main WHERE workout_session_main_user_id=$my_user_id_mysql AND workout_session_main_session_id='$get_workout_session_id' ORDER BY workout_session_main_id DESC";
									$result = mysqli_query($link, $query);
									$row = mysqli_fetch_row($result);
									list($get_workout_session_main_id, $get_workout_session_main_exercise_title) = $row;
					
									$fm = "$l_exercise <b>$get_workout_session_main_exercise_title</b> $l_added_lowercase";
								}
								else{
									$fm = ucfirst($fm);
								}
								echo"<div class=\"$ft\"><span>$fm</span></div>";
							}
							echo"	
						<!-- //Feedback -->
						<!-- Search for exercise -->
							<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
							\$(document).ready(function () {
								\$('[name=\"q\"]').focus();
								\$('#nettport_inp_search_query').keyup(function () {
        								var searchString    = $(\"#nettport_inp_search_query\").val();
       									var data            = 'weekly_id=$weekly_id&session_id=$session_id&l=$l&q='+ searchString;
         
        								// if searchString is not empty
        								if(searchString) {
           									// ajax call
            									\$.ajax({
                									type: \"POST\",
               										url: \"new_workout_plan_weekly_step_3_sessions_search_for_exercise_jquery.php\",
                									data: data,
											beforeSend: function(html) { // this happens before actual call
												\$(\"#nettport_search_results\").html(''); 
											},
               										success: function(html){
                    										\$(\"#nettport_search_results\").append(html);
              										}
            									});
       									}
        								return false;
            							});
            						});
							</script>
	
							<form method=\"post\" action=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;l=$l\" enctype=\"multipart/form-data\">
							<p><b>$l_search_for_exercise:</b><br />
							<input type=\"text\" name=\"q\" value=\"\" size=\"20\" id=\"nettport_inp_search_query\" />
							<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" />
							</p>
							<div id=\"nettport_search_results\">
							</div>
						<!-- //Search for exercise -->

						<!-- Browse for exercise: Exercises -->
							<p><b>$l_select_exercise ($get_type_translation_value):</b></p>
							";
							// Set layout
							$x = 0;
	
							// Query
							$query = "SELECT exercise_id, exercise_title, exercise_user_id, exercise_muscle_group_id_main, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_updated_datetime, exercise_guide FROM $t_exercise_index";
							$query = $query  . " WHERE exercise_language=$l_mysql AND exercise_muscle_group_id_main=$main_muscle_group_id_mysql AND exercise_type_id=$type_id_mysql ORDER BY exercise_title ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_exercise_id, $get_exercise_title, $get_exercise_user_id, $get_exercise_muscle_group_id_main, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_updated_datetime, $get_exercise_guide) = $row;
				

								if($x == 0){
									echo"
									<div class=\"clear\" style=\"height: 10px;\"></div>
									<div class=\"left_right_left\">
									";
								}
								elseif($x == 1){
									echo"
									<div class=\"left_right_right\">
									";
								}



								// Title
								echo"
								<p style=\"padding: 10px 0px 0px 0px;margin-bottom:0;\">
								<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$get_exercise_type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;exercise_id=$get_exercise_id&amp;mode=step_4_data&amp;l=$l\" class=\"exercise_index_title\">$get_exercise_title</a><br />
								</p>
								";

								// Thumb
								$query_images = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_150x150 FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' ORDER BY exercise_image_type ASC LIMIT 0,2";
								$result_images = mysqli_query($link, $query_images);
								while($row_images = mysqli_fetch_row($result_images)) {
									list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_150x150) = $row_images;

									if($get_exercise_image_file != "" && file_exists("../$get_exercise_image_path/$get_exercise_image_file")){
							
										if($get_exercise_image_thumb_150x150 == ""){
											$extension = get_extension($get_exercise_image_file);
											$extension = strtolower($extension);

											$thumb = substr($get_exercise_image_file, 0, -4);
											$get_exercise_image_thumb_150x150 = $thumb . "_thumb_150x150." . $extension;
											$thumb_mysql = quote_smart($link, $get_exercise_image_thumb_150x150);

											$result_update = mysqli_query($link, "UPDATE $t_exercise_index_images SET exercise_image_thumb_150x150=$thumb_mysql WHERE exercise_image_id=$get_exercise_image_id") or die(mysqli_error($link));
										}
										if(!(file_exists("../$get_exercise_image_path/$get_exercise_image_thumb_150x150"))){
											// Thumb
											$inp_new_x = 150;
											$inp_new_y = 150;
											resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_exercise_image_path/$get_exercise_image_file", "$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150");
										}

										echo"				";
										echo"<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$get_exercise_type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;exercise_id=$get_exercise_id&amp;mode=step_4_data&amp;l=$l\"><img src=\"../$get_exercise_image_path/$get_exercise_image_thumb_150x150\" alt=\"$get_exercise_image_thumb_150x150\" /></a>\n";
									}
								}

								echo"
								</div>
								";

								// Increment
								if($x == 1){
									$x = -1;
								}
								$x++;
		

							} // while
							echo"
						<!-- //Browse for exercise: Exercises -->
						";
					} // $mode == "step_2_muscle_group"
					elseif($mode == "step_4_data"){

						if($process == 1){
							$inp_exercise_id = $_POST['inp_exercise_id'];
							$inp_exercise_id = output_html($inp_exercise_id);
							$inp_exercise_id_mysql = quote_smart($link, $inp_exercise_id);
		
							// Get the title
							$query_translation = "SELECT exercise_id, exercise_title FROM $t_exercise_index WHERE exercise_id=$inp_exercise_id_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_exercise_id, $get_exercise_title) = $row_translation;
		
							$inp_exercise_title_mysql = quote_smart($link, $get_exercise_title);

							$inp_sets = $_POST['inp_sets'];
							if(empty($inp_sets)){
								$inp_sets = 0;
							}
							$inp_sets = output_html($inp_sets);
							$inp_sets_mysql = quote_smart($link, $inp_sets);

							$inp_reps = $_POST['inp_reps'];
							if(empty($inp_reps)){
								$inp_reps = 0;
							}
							$inp_reps = output_html($inp_reps);
							$inp_reps_mysql = quote_smart($link, $inp_reps);

							$inp_velocity_a = $_POST['inp_velocity_a'];
							if(empty($inp_velocity_a)){
								$inp_velocity_a = 0;
							}
							$inp_velocity_a = output_html($inp_velocity_a);
							$inp_velocity_a_mysql = quote_smart($link, $inp_velocity_a);

							$inp_velocity_b = $_POST['inp_velocity_b'];
							if(empty($inp_velocity_b)){
								$inp_velocity_b = 0;
							}
							$inp_velocity_b = output_html($inp_velocity_b);
							$inp_velocity_b_mysql = quote_smart($link, $inp_velocity_b);

							$inp_distance = $_POST['inp_distance'];
							if(empty($inp_distance)){
								$inp_distance = 0;
							}
							$inp_distance = output_html($inp_distance);
							$inp_distance_mysql = quote_smart($link, $inp_distance);

							$inp_duration = $_POST['inp_duration'];
							if(empty($inp_duration)){
								$inp_duration = 0;
							}
							$inp_duration = output_html($inp_duration);
							$inp_duration_mysql = quote_smart($link, $inp_duration);

							$inp_intensity = $_POST['inp_intensity'];
							if(empty($inp_intensity)){
								$inp_intensity = 0;
							}
							$inp_intensity = output_html($inp_intensity);
							$inp_intensity_mysql = quote_smart($link, $inp_intensity);

							$inp_text = $_POST['inp_text'];
							$inp_text = output_html($inp_text);
							$inp_text_mysql = quote_smart($link, $inp_text);

							// Get correct weight
							$query = "SELECT * FROM $t_workout_plans_sessions_main WHERE workout_session_main_user_id=$get_current_workout_weekly_user_id AND workout_session_main_session_id='$get_workout_session_id'";
							$result = mysqli_query($link, $query);
							$row_cnt = mysqli_num_rows($result);
				
							// Insert
							mysqli_query($link, "INSERT INTO $t_workout_plans_sessions_main
							(workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, 
							workout_session_main_sets, workout_session_main_reps, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text) 
							VALUES 
							(NULL, $get_current_workout_weekly_user_id, '$get_workout_session_id', '$row_cnt', $inp_exercise_id_mysql, $inp_exercise_title_mysql, 
							$inp_sets_mysql, $inp_reps_mysql, $inp_velocity_a_mysql, $inp_velocity_b_mysql, $inp_distance_mysql, $inp_duration_mysql, $inp_intensity_mysql, $inp_text_mysql)
							")
							or die(mysqli_error($link));

				
							// Header
							$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&action=$action&session_id=$session_id&l=$l&type_id=$type_id&main_muscle_group_id=$main_muscle_group_id&mode=step_3_exercises&ft=success&fm=added";
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
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$get_current_workout_session_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$l_add_exercise</a>
							&gt;";

							// Translation
							$type_id_mysql = quote_smart($link, $type_id);
							$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id=$type_id_mysql AND type_translation_language=$l_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_type_translation_id, $get_type_translation_value) = $row_translation;

							// Muscle group translation
							$main_muscle_group_id_mysql = quote_smart($link, $main_muscle_group_id);
							$query_translation = "SELECT muscle_group_translation_id, muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$main_muscle_group_id_mysql AND muscle_group_translation_language=$l_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;
							echo"
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;mode=step_2_muscle_group&amp;l=$l\">$get_type_translation_value</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;mode=step_3_exercises&amp;l=$l\">$get_main_muscle_group_translation_name</a>
							</p>
						<!-- //Where am I ? -->


						<h2>$get_current_workout_session_title</h2>
	

						<!-- Feedback -->
							";
							if($ft != ""){
								if($fm == "changes_saved"){
									$fm = "$l_changes_saved";
								}
								elseif($fm == "added"){
									// Find last exersice
									$query = "SELECT workout_session_main_id, workout_session_main_exercise_title FROM $t_workout_plans_sessions_main WHERE workout_session_main_user_id=$get_current_workout_weekly_user_id AND workout_session_main_session_id='$get_workout_session_id' ORDER BY workout_session_main_id DESC";
									$result = mysqli_query($link, $query);
									$row = mysqli_fetch_row($result);
									list($get_workout_session_main_id, $get_workout_session_main_exercise_title) = $row;
					
									$fm = "$l_exercise <b>$get_workout_session_main_exercise_title</b> $l_added_lowercase";
								}
								else{
									$fm = ucfirst($fm);
								}
								echo"<div class=\"$ft\"><span>$fm</span></div>";
							}
							echo"	
						<!-- //Feedback -->

				
						<!-- Form -->
							<script>
							\$(document).ready(function(){
								\$('#inp_type_select').on('change', function () {
									var url = \$(this).val();
									if (url) { // require a URL
 										window.location = url;
									}
									return false;
								});
								\$('#inp_muscle_group_select').on('change', function () {
									var url = \$(this).val();
									if (url) { // require a URL
 										window.location = url;
									}
									return false;
								});
								\$('[name=\"inp_sets\"]').focus();
							});
							</script>



							<form method=\"post\" action=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;mode=step_4_data&amp;process=1\" enctype=\"multipart/form-data\">
	

				
							<p>
							<b>$l_type:</b><br />
							<select name=\"inp_type_select\" id=\"inp_type_select\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
								<option value=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=$action&amp;session_id=$session_id&amp;l=$l\">- $l_type -</option>\n";
								// Get all types
								$query_sub = "SELECT type_id, type_title FROM $t_exercise_types ORDER BY type_title ASC";
								$result_sub = mysqli_query($link, $query_sub);
								while($row_sub = mysqli_fetch_row($result_sub)) {
									list($get_type_id, $get_type_title) = $row_sub;
	
									// Translation
									$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id='$get_type_id' AND type_translation_language=$l_mysql";
									$result_translation = mysqli_query($link, $query_translation);
									$row_translation = mysqli_fetch_row($result_translation);
									list($get_type_translation_id, $get_type_translation_value) = $row_translation;
	
									echo"		";
									echo"<option value=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=$action&amp;session_id=$session_id&amp;l=$l&amp;type_id=$get_type_id\""; if($type_id == "$get_type_id"){ echo" selected=\"selected\"";}echo">$get_type_translation_value</option>\n";

								}
							echo"
							</select>

					
							<p>
							<b>$l_muscle_group:</b><br />
							<select name=\"inp_muscle_grpoup_id\" id=\"inp_muscle_group_select\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
							<option value=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=$action&amp;session_id=$session_id&amp;type=$type&amp;l=$l\">- $l_group -</option>\n";
							$type_id_mysql = quote_smart($link, $type_id);
							$main_muscle_group_id_mysql = quote_smart($link, $main_muscle_group_id);
							$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;

								// Translation
								$query_translation = "SELECT muscle_group_translation_id, muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
								$result_translation = mysqli_query($link, $query_translation);
								$row_translation = mysqli_fetch_row($result_translation);
								list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;

								echo"		";
								echo"<option value=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=$action&amp;session_id=$session_id&amp;l=$l&amp;type_id=$type_id&amp;main_muscle_group_id=$get_main_muscle_group_id\""; if($get_main_muscle_group_id == "$main_muscle_group_id"){ echo" selected=\"selected\""; } echo">$get_main_muscle_group_translation_name</option>\n";
							}
							echo"
							</select>
						
							<p>
							<b>$l_exercise:</b> [<a href=\"$root/exercises/new_exercise.php?l=$l\">$l_create_new</a>]<br />
							<select name=\"inp_exercise_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";

							// Get exercices in that 
							if(isset($_GET['exercise_id'])) {
								$exercise_id = $_GET['exercise_id'];
								$exercise_id = strip_tags(stripslashes($exercise_id));
							}
							else{
								$exercise_id = "";
							}


							$x = 0;
							$query_exercises = "SELECT exercise_id, exercise_title FROM $t_exercise_index WHERE exercise_language=$l_mysql AND exercise_muscle_group_id_main=$main_muscle_group_id_mysql AND exercise_type_id=$type_id_mysql ORDER BY exercise_title ASC";
							$result_exercises = mysqli_query($link, $query_exercises);
							while($row_exercises = mysqli_fetch_row($result_exercises)) {
								list($get_exercise_id, $get_exercise_title) = $row_exercises;
	
								echo"		";
								echo"<option value=\"$get_exercise_id\""; if($get_exercise_id == "$exercise_id"){ echo" selected=\"selected\"";} echo">$get_exercise_title</option>\n";
							}
							echo"
							</select>

							<p><b>$l_sets x $l_reps:</b><br />
							<input type=\"text\" name=\"inp_sets\" size=\"4\" value=\"\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							x
							<input type=\"text\" name=\"inp_reps\" size=\"4\" value=\"\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							</p>

							<p><b>$l_velocity a:</b><br />
							<input type=\"text\" name=\"inp_velocity_a\" size=\"4\" value=\"\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							km/h</p>


							<p><b>$l_velocity b:</b><br />
							<input type=\"text\" name=\"inp_velocity_b\" size=\"4\" value=\"\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							km/h</p>

							<p><b>$l_distance:</b><br />
							<input type=\"text\" name=\"inp_distance\" size=\"4\" value=\"\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							m</p>

							<p><b>$l_duration:</b><br />
							<input type=\"text\" name=\"inp_duration\" size=\"4\" value=\"\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							$l_min_lowercase
							</p>

							<p><b>$l_intensity:</b><br />
							<input type=\"text\" name=\"inp_intensity\" size=\"4\" value=\"\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> %
							</p>

							<p><b>$l_text:</b><br />
							<textarea name=\"inp_text\" rows=\"3\" cols=\"30\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
							</p>




							<p>
							<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							</p>
						</form>
						<!-- //Form -->

				
				

						<!-- Back -->
							<p style=\"margin-top: 40px;\">	
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\" class=\"btn btn_default\">$get_current_workout_session_title</a>
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;type_id=$type_id&amp;l=$l\" class=\"btn btn_default\">$l_sessions</a>
							<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;type_id=$type_id&amp;l=$l\" class=\"btn btn_default\">$l_next</a>
							</p>
						<!-- //Back -->
						";
					} // mode == 4 data
				} //session found
			} // add_exercise_to_session
			elseif($action == "edit_session_main"){
				// Get session
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity, workout_session_goal, workout_session_warmup, workout_session_end FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql AND workout_session_user_id=$get_current_workout_weekly_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_workout_session_id, $get_current_workout_session_user_id, $get_current_workout_session_weekly_id, $get_current_workout_session_weight, $get_current_workout_session_title, $get_current_workout_session_title_clean, $get_current_workout_session_duration, $get_current_workout_session_intensity, $get_current_workout_session_goal, $get_current_workout_session_warmup, $get_current_workout_session_end) = $row;
	
				if($get_workout_session_id == ""){
					echo"Session not found.";
				}
				else{
					// Get session main

					$session_main_id_mysql = quote_smart($link, $session_main_id);
					$query = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_reps, workout_session_main_sets, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text
						  FROM $t_workout_plans_sessions_main WHERE workout_session_main_id=$session_main_id_mysql AND workout_session_main_user_id=$get_current_workout_weekly_user_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_workout_session_main_id, $get_current_workout_session_main_user_id, $get_current_workout_session_main_session_id, $get_current_workout_session_main_weight, $get_current_workout_session_main_exercise_id, $get_current_workout_session_main_exercise_title, $get_current_workout_session_main_reps, $get_current_workout_session_main_sets, $get_current_workout_session_main_velocity_a, $get_current_workout_session_main_velocity_b, $get_current_workout_session_main_distance, $get_current_workout_session_main_duration, $get_current_workout_session_main_intensity, $get_current_workout_session_main_text) = $row;
	
					if($get_workout_session_main_id == ""){
						echo"Session main not found.";
					}
					else{
						if($process == 1){
							$inp_exercise_id = $_POST['inp_exercise_id'];
							$inp_exercise_id = output_html($inp_exercise_id);
							$inp_exercise_id_mysql = quote_smart($link, $inp_exercise_id);

							// Get the title
							$query_translation = "SELECT exercise_id, exercise_title FROM $t_exercise_index WHERE exercise_id=$inp_exercise_id_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_exercise_id, $get_exercise_title) = $row_translation;
	
							$inp_exercise_title_mysql = quote_smart($link, $get_exercise_title);

							$inp_sets = $_POST['inp_sets'];
							if(empty($inp_sets)){
								$inp_sets = 0;
							}
							$inp_sets = output_html($inp_sets);
							$inp_sets_mysql = quote_smart($link, $inp_sets);

							$inp_reps = $_POST['inp_reps'];
							if(empty($inp_reps)){
								$inp_reps = 0;
							}
							$inp_reps = output_html($inp_reps);
							$inp_reps_mysql = quote_smart($link, $inp_reps);

							$inp_velocity_a = $_POST['inp_velocity_a'];
							if(empty($inp_velocity_a)){
								$inp_velocity_a = 0;
							}
							$inp_velocity_a = output_html($inp_velocity_a);
							$inp_velocity_a_mysql = quote_smart($link, $inp_velocity_a);

							$inp_velocity_b = $_POST['inp_velocity_b'];
							if(empty($inp_velocity_b)){
								$inp_velocity_b = 0;
							}
							$inp_velocity_b = output_html($inp_velocity_b);
							$inp_velocity_b_mysql = quote_smart($link, $inp_velocity_b);
	
							$inp_distance = $_POST['inp_distance'];
							if(empty($inp_distance)){
								$inp_distance = 0;
							}
							$inp_distance = output_html($inp_distance);
							$inp_distance_mysql = quote_smart($link, $inp_distance);
	
							$inp_duration = $_POST['inp_duration'];
							if(empty($inp_duration)){
								$inp_duration = 0;
							}
							$inp_duration = output_html($inp_duration);
							$inp_duration_mysql = quote_smart($link, $inp_duration);

							$inp_intensity = $_POST['inp_intensity'];
							if(empty($inp_intensity)){
								$inp_intensity = 0;
							}
							$inp_intensity = output_html($inp_intensity);
							$inp_intensity_mysql = quote_smart($link, $inp_intensity);

							$inp_text = $_POST['inp_text'];
							$inp_text = output_html($inp_text);
							$inp_text_mysql = quote_smart($link, $inp_text);


							// Update
							$result = mysqli_query($link, "UPDATE $t_workout_plans_sessions_main SET 
								workout_session_main_exercise_id=$inp_exercise_id_mysql, workout_session_main_exercise_title=$inp_exercise_title_mysql, 
								workout_session_main_sets=$inp_sets_mysql, workout_session_main_reps=$inp_reps_mysql, workout_session_main_velocity_a=$inp_velocity_a_mysql, 
								workout_session_main_velocity_b=$inp_velocity_b_mysql, workout_session_main_distance=$inp_distance_mysql, 
								workout_session_main_duration=$inp_duration_mysql, workout_session_main_intensity=$inp_intensity_mysql, workout_session_main_text=$inp_text_mysql
								WHERE
								 workout_session_main_id=$session_main_id_mysql AND workout_session_main_user_id=$get_current_workout_weekly_user_id");

				
							// Header
							$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&action=open_session&session_id=$session_id&l=$l&type_id=$type_id&ft=success&fm=changes_saved";
							header("Location: $url");
							exit;
						}
			
						// Find type ID for my exercise
						if($type_id == ""){
						$query_translation = "SELECT exercise_type_id FROM $t_exercise_index WHERE exercise_id=$get_current_workout_session_main_exercise_id";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_exercise_type_id) = $row_translation;
							$type_id= $get_exercise_type_id;
						}


						echo"


						<h1>$get_current_workout_weekly_title</h1>
	
	
						<!-- Where am I ? -->
						<p><b>$l_you_are_here:</b><br />
						<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
						&gt;
						<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
						&gt;
						<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
						&gt;
						<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$get_current_workout_session_title</a>
						&gt;
						<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=$action&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$l_edit_exercise</a>
						</p>
						<!-- //Where am I ? -->


						<h2>$get_current_workout_session_title</h2>
	

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

				
						<!-- Form -->
							<script>
							\$(document).ready(function(){
								\$('#inp_type_select').on('change', function () {
									var url = \$(this).val();
									if (url) { // require a URL
 										window.location = url;
									}
									return false;
								});
								\$('[name=\"inp_type_select\"]').focus();
							});
							</script>


							<form method=\"post\" action=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=$action&amp;session_id=$session_id&amp;session_main_id=$session_main_id&amp;l=$l&amp;type_id=$type_id&amp;process=1\" enctype=\"multipart/form-data\">
	

							<p>
							<b>$l_type:</b><br />
							<select name=\"inp_type_select\" id=\"inp_type_select\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
								<option value=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=$action&amp;session_id=$session_id&amp;l=$l\">- $l_type -</option>\n";
								// Get all types
								$query_sub = "SELECT type_id, type_title FROM $t_exercise_types ORDER BY type_title ASC";
								$result_sub = mysqli_query($link, $query_sub);
								while($row_sub = mysqli_fetch_row($result_sub)) {
									list($get_type_id, $get_type_title) = $row_sub;
	
									// Translation
									$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id='$get_type_id' AND type_translation_language=$l_mysql";
									$result_translation = mysqli_query($link, $query_translation);
									$row_translation = mysqli_fetch_row($result_translation);
									list($get_type_translation_id, $get_type_translation_value) = $row_translation;
	
									echo"		";
									echo"<option value=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=$action&amp;session_id=$session_id&amp;session_main_id=$session_main_id&amp;l=$l&amp;type_id=$get_type_id\""; if($type_id == "$get_type_id"){ echo" selected=\"selected\"";}echo">$get_type_translation_value</option>\n";

								}
							echo"
							</select>

							<!-- Show image from exercise -->
							<script>
							\$(document).ready(function(){
								\$(\"#inp_exercise_id\").change(function(){
									var idname= \$(this).data('divid');
									\$('.image-swap').attr(\"src\",idname);
								});
							});
							</script>
							<!-- //Show image from exercise -->
							<img src=\"\" class=\"image-swap\" style=\"float: right;\">
							<p>
							<b>$l_exercise:</b> <a href=\"$root/exercises/new_exercise.php?l=$l\">$l_new</a><br />
							<select name=\"inp_exercise_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" id=\"inp_exercise_id\">\n";

								// Get all Show all types
						
								$type_id_mysql = quote_smart($link, $type_id);
						
								$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;

									// Translation
									$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
									$result_translation = mysqli_query($link, $query_translation);
									$row_translation = mysqli_fetch_row($result_translation);
									list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;

									echo"		";
									echo"<option value=\"0\">- $get_main_muscle_group_translation_name -</option>\n";

									// Get exercices in that 
									$x = 0;
									$query_exercises = "SELECT exercise_id, exercise_title FROM $t_exercise_index WHERE exercise_language=$l_mysql AND exercise_muscle_group_id_main='$get_main_muscle_group_id' AND exercise_type_id=$type_id_mysql";
									$result_exercises = mysqli_query($link, $query_exercises);
									while($row_exercises = mysqli_fetch_row($result_exercises)) {
										list($get_exercise_id, $get_exercise_title) = $row_exercises;

										// Find image
										$query_img = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_medium FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' ORDER BY exercise_image_type ASC LIMIT 0,1";
										$result_img = mysqli_query($link, $query_img);
										$row_img = mysqli_fetch_row($result_img);
										list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_medium) = $row_img;
								
	
										echo"		";
										echo"<option data-divid=\"$root/$get_exercise_image_path/$get_exercise_image_thumb_medium\" value=\"$get_exercise_id\""; if($get_current_workout_session_main_exercise_id == "$get_exercise_id"){ echo" selected=\"selected\"";}echo">$get_exercise_title</option>\n";
									}
									echo"		";
									echo"<option value=\"0\"></option>\n";

								}
								echo"
							</select>
		
							<p><b>$l_sets x $l_reps:</b><br />
							<input type=\"text\" name=\"inp_sets\" size=\"4\" value=\"$get_current_workout_session_main_sets\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							x
							<input type=\"text\" name=\"inp_reps\" size=\"4\" value=\"$get_current_workout_session_main_reps\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							</p>
		
							<p><b>$l_velocity a:</b><br />
							<input type=\"text\" name=\"inp_velocity_a\" size=\"4\" value=\"$get_current_workout_session_main_velocity_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							km/h</p>


							<p><b>$l_velocity b:</b><br />
							<input type=\"text\" name=\"inp_velocity_b\" size=\"4\" value=\"$get_current_workout_session_main_velocity_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							km/h</p>

							<p><b>$l_distance:</b><br />
							<input type=\"text\" name=\"inp_distance\" size=\"4\" value=\"$get_current_workout_session_main_distance\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							m</p>

							<p><b>$l_duration:</b><br />
							<input type=\"text\" name=\"inp_duration\" size=\"4\" value=\"$get_current_workout_session_main_duration\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							$l_min_lowercase
							</p>

							<p><b>$l_intensity:</b><br />
							<input type=\"text\" name=\"inp_intensity\" size=\"4\" value=\"$get_current_workout_session_main_intensity\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> %
							</p>
		
							<p><b>$l_text:</b><br />
							<textarea name=\"inp_text\" rows=\"3\" cols=\"30\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
							$get_current_workout_session_main_text = str_replace("<br />", "\n", $get_current_workout_session_main_text);
							echo"$get_current_workout_session_main_text</textarea>
							</p>




							<p>
							<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							</p>

							</form>
						<!-- //Form -->

				
				

						<!-- Back -->
							<p style=\"margin-top: 40px;\">	
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\" class=\"btn btn_default\">$get_current_workout_session_title</a>
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;type_id=$type_id&amp;l=$l\" class=\"btn btn_default\">$l_sessions</a>
							<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;type_id=$type_id&amp;l=$l\" class=\"btn btn_default\">$l_next</a>
							</p>
						<!-- //Back -->
						";
					} //session_main found
				} //session found
			} // edit_session_main
			elseif($action == "delete_session_main"){
				// Get session
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity, workout_session_goal, workout_session_warmup, workout_session_end FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql AND workout_session_user_id=$get_current_workout_weekly_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_workout_session_id, $get_current_workout_session_user_id, $get_current_workout_session_weekly_id, $get_current_workout_session_weight, $get_current_workout_session_title, $get_current_workout_session_title_clean, $get_current_workout_session_duration, $get_current_workout_session_intensity, $get_current_workout_session_goal, $get_current_workout_session_warmup, $get_current_workout_session_end) = $row;
	
				if($get_workout_session_id == ""){
					echo"Session not found.";
				}
				else{
					// Get session main

					$session_main_id_mysql = quote_smart($link, $session_main_id);
					$query = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_reps, workout_session_main_sets, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text
					  FROM $t_workout_plans_sessions_main WHERE workout_session_main_id=$session_main_id_mysql AND workout_session_main_user_id=$get_current_workout_weekly_user_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_workout_session_main_id, $get_current_workout_session_main_user_id, $get_current_workout_session_main_session_id, $get_current_workout_session_main_weight, $get_current_workout_session_main_exercise_id, $get_current_workout_session_main_exercise_title, $get_current_workout_session_main_reps, $get_current_workout_session_main_sets, $get_current_workout_session_main_velocity_a, $get_current_workout_session_main_velocity_b, $get_current_workout_session_main_distance, $get_current_workout_session_main_duration, $get_current_workout_session_main_intensity, $get_current_workout_session_main_text) = $row;
	
					if($get_workout_session_main_id == ""){
						echo"Session main not found.";
					}
					else{
						if($process == 1){
							// Delete
							$result = mysqli_query($link, "DELETE FROM $t_workout_plans_sessions_main WHERE
								 workout_session_main_id=$session_main_id_mysql AND workout_session_main_user_id=$get_current_workout_weekly_user_id");

				
							// Header
							$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&action=open_session&session_id=$session_id&l=$l&type_id=$type_id&ft=success&fm=deleted";
							header("Location: $url");
							exit;
						}
			
						// Find type ID for my exercise
						if($type_id == ""){
							$query_translation = "SELECT exercise_type_id FROM $t_exercise_index WHERE exercise_id=$get_current_workout_session_main_exercise_id";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_exercise_type_id) = $row_translation;
							$type_id= $get_exercise_type_id;
						}


						echo"
						<h1>$get_current_workout_weekly_title</h1>
	

						<!-- Where am I ? -->
							<p><b>$l_you_are_here:</b><br />
							<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
							&gt;
							<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_sessions</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\">$get_current_workout_session_title</a>
							&gt;
							<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=delete_session_main&amp;session_id=$session_id&amp;session_main_id=$session_main_id&amp;type_id=$type_id&amp;l=$l\">$l_delete</a>
							</p>
						<!-- //Where am I ? -->


						<h2>$get_current_workout_session_main_exercise_title</h2>
	

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



						<p>
						$l_are_you_sure_you_want_to_delete
						</p>

						<p>
						<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=delete_session_main&amp;session_id=$session_id&amp;session_main_id=$session_main_id&amp;type_id=$type_id&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_delete</a>
						<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=open_session&amp;session_id=$session_id&amp;type_id=$type_id&amp;l=$l\" class=\"btn btn_default\">$l_cancel</a>
						</p>

						</form>

				
						";
					} //session_main found
				} //session found
			} // delete_session_main
			elseif($action == "move_session_main_up"){
				// Get session
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity, workout_session_goal, workout_session_warmup, workout_session_end FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql AND workout_session_user_id=$get_current_workout_weekly_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_workout_session_id, $get_current_workout_session_user_id, $get_current_workout_session_weekly_id, $get_current_workout_session_weight, $get_current_workout_session_title, $get_current_workout_session_title_clean, $get_current_workout_session_duration, $get_current_workout_session_intensity, $get_current_workout_session_goal, $get_current_workout_session_warmup, $get_current_workout_session_end) = $row;
	
				if($get_workout_session_id == ""){
					echo"Session not found.";
				}
				else{
					// Get session main
					$session_main_id_mysql = quote_smart($link, $session_main_id);
					$query = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_reps, workout_session_main_sets, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text
						  FROM $t_workout_plans_sessions_main WHERE workout_session_main_id=$session_main_id_mysql AND workout_session_main_user_id=$get_current_workout_weekly_user_id AND workout_session_main_session_id=$get_workout_session_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_original_workout_session_main_id, $get_original_workout_session_main_user_id, $get_original_workout_session_main_session_id, $get_original_workout_session_main_weight, $get_original_workout_session_main_exercise_id, $get_original_workout_session_main_exercise_title, $get_original_workout_session_main_reps, $get_original_workout_session_main_sets, $get_original_workout_session_main_velocity_a, $get_original_workout_session_main_velocity_b, $get_original_workout_session_main_distance, $get_original_workout_session_main_duration, $get_original_workout_session_main_intensity, $get_original_workout_session_main_text) = $row;



					// Find the session main I need to swap with
					$swap_weight = $get_original_workout_session_main_weight-1;
					$swap_weight_mysql = quote_smart($link, $swap_weight);
					$query = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_reps, workout_session_main_sets, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text
						  FROM $t_workout_plans_sessions_main WHERE workout_session_main_weight=$swap_weight_mysql AND workout_session_main_user_id=$get_current_workout_weekly_user_id AND workout_session_main_session_id=$get_workout_session_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_swap_workout_session_main_id, $get_swap_workout_session_main_user_id, $get_swap_workout_session_main_session_id, $get_swap_workout_session_main_weight, $get_swap_workout_session_main_exercise_id, $get_swap_workout_session_main_exercise_title, $get_swap_workout_session_main_reps, $get_swap_workout_session_main_sets, $get_swap_workout_session_main_velocity_a, $get_swap_workout_session_main_velocity_b, $get_swap_workout_session_main_distance, $get_swap_workout_session_main_duration, $get_swap_workout_session_main_intensity, $get_swap_workout_session_main_text) = $row;



	
					if($get_original_workout_session_main_id == "" OR $get_swap_workout_session_main_id == ""){
						echo"Session main not found.";
					}
					else{
						// echo"$get_original_workout_session_main_id && $get_swap_workout_session_main_id";

						// Update original
						$inp_weight_mysql = quote_smart($link, $get_swap_workout_session_main_weight);
						$result = mysqli_query($link, "UPDATE $t_workout_plans_sessions_main SET workout_session_main_weight=$inp_weight_mysql
								WHERE workout_session_main_id=$get_original_workout_session_main_id AND workout_session_main_user_id=$get_current_workout_weekly_user_id");

						// Update swap
						$inp_weight_mysql = quote_smart($link, $get_original_workout_session_main_weight);
						$result = mysqli_query($link, "UPDATE $t_workout_plans_sessions_main SET workout_session_main_weight=$inp_weight_mysql
								WHERE workout_session_main_id=$get_swap_workout_session_main_id AND workout_session_main_user_id=$get_current_workout_weekly_user_id");

						// Header
						$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&action=open_session&session_id=$session_id&l=$l&type_id=$type_id";
						header("Location: $url");
						exit;
					
					} //session_main found
				} //session found
			} // move_session_main_up
			elseif($action == "move_session_main_down"){
				// Get session
				$session_id_mysql = quote_smart($link, $session_id);
				$query = "SELECT workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity, workout_session_goal, workout_session_warmup, workout_session_end FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql AND workout_session_user_id=$get_current_workout_weekly_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_workout_session_id, $get_current_workout_session_user_id, $get_current_workout_session_weekly_id, $get_current_workout_session_weight, $get_current_workout_session_title, $get_current_workout_session_title_clean, $get_current_workout_session_duration, $get_current_workout_session_intensity, $get_current_workout_session_goal, $get_current_workout_session_warmup, $get_current_workout_session_end) = $row;
	
				if($get_workout_session_id == ""){
					echo"Session not found.";
				}
				else{
					// Get session main
					$session_main_id_mysql = quote_smart($link, $session_main_id);
					$query = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_reps, workout_session_main_sets, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text
					  FROM $t_workout_plans_sessions_main WHERE workout_session_main_id=$session_main_id_mysql AND workout_session_main_user_id=$get_current_workout_weekly_user_id AND workout_session_main_session_id=$get_workout_session_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_original_workout_session_main_id, $get_original_workout_session_main_user_id, $get_original_workout_session_main_session_id, $get_original_workout_session_main_weight, $get_original_workout_session_main_exercise_id, $get_original_workout_session_main_exercise_title, $get_original_workout_session_main_reps, $get_original_workout_session_main_sets, $get_original_workout_session_main_velocity_a, $get_original_workout_session_main_velocity_b, $get_original_workout_session_main_distance, $get_original_workout_session_main_duration, $get_original_workout_session_main_intensity, $get_original_workout_session_main_text) = $row;



					// Find the session main I need to swap with
					$swap_weight = $get_original_workout_session_main_weight+1;
					$swap_weight_mysql = quote_smart($link, $swap_weight);
					$query = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_reps, workout_session_main_sets, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text
					  FROM $t_workout_plans_sessions_main WHERE workout_session_main_weight=$swap_weight_mysql AND workout_session_main_user_id=$get_current_workout_weekly_user_id AND workout_session_main_session_id=$get_workout_session_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_swap_workout_session_main_id, $get_swap_workout_session_main_user_id, $get_swap_workout_session_main_session_id, $get_swap_workout_session_main_weight, $get_swap_workout_session_main_exercise_id, $get_swap_workout_session_main_exercise_title, $get_swap_workout_session_main_reps, $get_swap_workout_session_main_sets, $get_swap_workout_session_main_velocity_a, $get_swap_workout_session_main_velocity_b, $get_swap_workout_session_main_distance, $get_swap_workout_session_main_duration, $get_swap_workout_session_main_intensity, $get_swap_workout_session_main_text) = $row;



	
					if($get_original_workout_session_main_id == "" OR $get_swap_workout_session_main_id == ""){
						echo"Session main not found.";
					}
					else{
						// echo"$get_original_workout_session_main_id && $get_swap_workout_session_main_id";

						// Update original
						$inp_weight_mysql = quote_smart($link, $get_swap_workout_session_main_weight);
						$result = mysqli_query($link, "UPDATE $t_workout_plans_sessions_main SET workout_session_main_weight=$inp_weight_mysql
								WHERE workout_session_main_id=$get_original_workout_session_main_id AND workout_session_main_user_id=$get_current_workout_weekly_user_id");

						// Update swap
						$inp_weight_mysql = quote_smart($link, $get_original_workout_session_main_weight);
						$result = mysqli_query($link, "UPDATE $t_workout_plans_sessions_main SET workout_session_main_weight=$inp_weight_mysql
									WHERE workout_session_main_id=$get_swap_workout_session_main_id AND workout_session_main_user_id=$get_current_workout_weekly_user_id");

						// Header
						$url = "weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&action=open_session&session_id=$session_id&l=$l&type_id=$type_id";
						header("Location: $url");
						exit;
					
					} //session_main found
				} //session found
			} // move_session_main_down
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