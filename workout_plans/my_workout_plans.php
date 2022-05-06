<?php 
/**
*
* File: workout_plans/my_workout_plans.php
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


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

if(isset($_GET['duration_type'])){
	$duration_type = $_GET['duration_type'];
	$duration_type = strip_tags(stripslashes($duration_type));
	if($duration_type == ""){
		$duration_type = "week";
	}
}
else{
	$duration_type = "week";
}
if(isset($_GET['yearly_id'])){
	$yearly_id = $_GET['yearly_id'];
	$yearly_id = output_html($yearly_id);
}
else{
	$yearly_id = "";
}
if(isset($_GET['period_id'])){
	$period_id = $_GET['period_id'];
	$period_id = output_html($period_id);
}
else{
	$period_id = "";
}

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_workout_plans - $l_workout_plans";
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


	
	echo"
	<h1>$l_my_workout_plans</h1>
	
	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_workout_plans</a>
		&gt;
		<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Quick menu -->
		<p>
		<a href=\"$root/workout_plans/new_workout_plan.php?l=$l\" class=\"btn_default\">$l_new_workout_plan</a>
		</p>
	<!-- //Quick menu -->


	<!-- Selector -->

	<div class=\"right\" style=\"text-align: right;\">

		<script>
			\$(function(){
				\$('#inp_language_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
				\$('#inp_duration_type_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
			});
		</script>

		<form method=\"get\" action=\"cc\" enctype=\"multipart/form-data\">
			<p>

			<select name=\"inp_language_select\" id=\"inp_language_select\">
				<option value=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">- $l_language -</option>\n";

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;



					echo"		";
					echo"<option value=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$get_language_active_iso_two\""; if($l == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";

				}
			echo"
			</select>


			<select name=\"inp_duration_type_select\" id=\"inp_duration_type_select\">
				<option value=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">- $l_type -</option>
				<option value=\"my_workout_plans.php?duration_type=year&amp;l=$l\""; if($duration_type == "year"){ echo" selected=\"selected\"";}echo">$l_year</option>
				<option value=\"my_workout_plans.php?duration_type=period&amp;l=$l\""; if($duration_type == "period"){ echo" selected=\"selected\"";}echo">$l_period</option>
				<option value=\"my_workout_plans.php?duration_type=week&amp;l=$l\""; if($duration_type == "week"){ echo" selected=\"selected\"";}echo">$l_week</option>
			</select>


			</p>
        	</form>
	</div>
	<!-- //Selector -->

	<!-- List my exercises -->
	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th scope=\"col\">
		<span>$l_title</span>
	   </th>
	  </tr>
	 </thead>
	 <tbody>
	";

	if($duration_type == "year"){
	
		$count_yearly = 0;

		$query = "SELECT workout_yearly_id, workout_yearly_title, workout_yearly_updated FROM $t_workout_plans_yearly WHERE workout_yearly_user_id=$my_user_id_mysql AND workout_yearly_language=$l_mysql ORDER BY workout_yearly_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_workout_yearly_id, $get_workout_yearly_title, $get_workout_yearly_updated) = $row;

			if(isset($style) && $style == "odd"){
				$style = "";
			}
			else{
				$style = "odd";
			}

			echo"
			 <tr>
			  <td class=\"$style\">
				<span><a href=\"yearly_workout_plan_edit.php?yearly_id=$get_workout_yearly_id\"";
				if($yearly_id == "$get_workout_yearly_id"){
					echo" style=\"font-weight: bold;\""; 
				}
				echo">$get_workout_yearly_title</a></span>
				
			  </td>
			 </tr>
			";
			$count_yearly = $count_yearly+1;

			
			// Get period
			
	
				$query_p = "SELECT workout_period_id, workout_period_title, workout_period_updated FROM $t_workout_plans_period WHERE workout_period_user_id=$my_user_id_mysql AND workout_period_yearly_id=$get_workout_yearly_id AND workout_period_language=$l_mysql ORDER BY workout_period_title ASC";
				$result_p = mysqli_query($link, $query_p);
				while($row_p = mysqli_fetch_row($result_p)) {
					list($get_workout_period_id, $get_workout_period_title, $get_workout_period_updated) = $row_p;
				
					if(isset($style) && $style == "odd"){
						$style = "";
					}
					else{
						$style = "odd";
					}

					echo"
					<tr>
					  <td class=\"$style\">
						<span>&nbsp; &nbsp; <a href=\"period_workout_plan_view.php?duration_type=year&amp;yearly_id=$yearly_id&amp;period_id=$get_workout_period_id\"";
						if($action == "open_period" && $period_id == "$get_workout_period_id"){
							echo" style=\"font-weight: bold;\""; 
						}
						echo">$get_workout_period_title</a></span>
				
					  </td>
					</tr>
					";

					// Get week
					if($period_id == "$get_workout_period_id"){
	
						$query_w = "SELECT workout_weekly_id, workout_weekly_title, workout_weekly_updated FROM $t_workout_plans_weekly WHERE workout_weekly_user_id=$my_user_id_mysql AND workout_weekly_period_id=$get_workout_period_id AND workout_weekly_language=$l_mysql ORDER BY workout_weekly_title ASC";
						$result_w = mysqli_query($link, $query_w);
						while($row_w = mysqli_fetch_row($result_w)) {
							list($get_workout_weekly_id, $get_workout_weekly_title, $get_workout_weekly_updated) = $row_w;
				
							// Updated
							if(isset($style) && $style == "odd"){
								$style = "";
							}
							else{
								$style = "odd";
							}

							echo"
							 <tr>
						 	  <td class=\"$style\">
								<span>&nbsp; &nbsp; &nbsp; &nbsp; 
								<a href=\"weekly_workout_plan_edit.php?weekly_id=$get_workout_weekly_id&amp;l=$l\">$get_workout_weekly_title</a>
								</span>
							 </td>
							</tr>
							";

						} // while week
					}
				} // while period

		} // while

		if($count_yearly == 0){
			$duration_type = "week";
		}

	} // $duration_type == "year"
	if($duration_type == "period"){


		$query_p = "SELECT workout_period_id, workout_period_yearly_id, workout_period_title, workout_period_updated FROM $t_workout_plans_period WHERE workout_period_user_id=$my_user_id_mysql AND workout_period_language=$l_mysql ORDER BY workout_period_title ASC";
		$result_p = mysqli_query($link, $query_p);
		while($row_p = mysqli_fetch_row($result_p)) {
			list($get_workout_period_id, $get_workout_period_yearly_id, $get_workout_period_title, $get_workout_period_updated) = $row_p;



			if(isset($style) && $style == "odd"){
				$style = "";
			}
			else{
				$style = "odd";
			}

			echo"
			 <tr>
		 	  <td class=\"$style\">
				<span>";

				// Parent?
				if($get_workout_period_yearly_id != 0){
					$query_y = "SELECT workout_yearly_id, workout_yearly_title, workout_yearly_updated FROM $t_workout_plans_yearly WHERE workout_yearly_id=$get_workout_period_yearly_id AND workout_yearly_user_id=$my_user_id_mysql ORDER BY workout_yearly_title ASC";
					$result_y = mysqli_query($link, $query_y);
					$row_y = mysqli_fetch_row($result_y);
					list($get_workout_yearly_id, $get_workout_yearly_title, $get_workout_yearly_updated) = $row_y;

					echo"
					$get_workout_yearly_title &gt;
					";
				}

				echo"<a href=\"period_workout_plan_edit.php?period_id=$get_workout_period_id\">$get_workout_period_title</a></span>
			 </td>
			</tr>
			";
		} // while period

	} // $duration_type == "period"
	if($duration_type == "week"){

		$query_w = "SELECT workout_weekly_id, workout_weekly_period_id, workout_weekly_title, workout_weekly_updated FROM $t_workout_plans_weekly WHERE workout_weekly_user_id=$my_user_id_mysql AND workout_weekly_language=$l_mysql ORDER BY workout_weekly_title ASC";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_workout_weekly_id, $get_workout_weekly_period_id, $get_workout_weekly_title, $get_workout_weekly_updated) = $row_w;

			// Updated
			$updated_year = substr($get_workout_weekly_updated, 0, 4);
			$updated_month = substr($get_workout_weekly_updated, 5, 2);


			$updated_day = substr($get_workout_weekly_updated, 8, 2);
			$updated_day_first = substr($updated_day, 0, 1);
			if($updated_day_first == "0"){
				$updated_day = substr($updated_day, 1, 1);
			}


			if($updated_month == "01"){
				$month_saying = $l_january;
			}
			elseif($updated_month == "02"){
				$month_saying = $l_february;
			}
			elseif($updated_month == "03"){
				$month_saying = $l_march;
			}
			elseif($updated_month == "04"){
				$month_saying = $l_april;
			}
			elseif($updated_month == "05"){
				$month_saying = $l_may;
			}
			elseif($updated_month == "06"){
				$month_saying = $l_june;
			}
			elseif($updated_month == "07"){
				$month_saying = $l_july;
			}
			elseif($updated_month == "08"){
				$month_saying = $l_august;
			}
			elseif($updated_month == "09"){
				$month_saying = $l_september;
			}
			elseif($updated_month == "10"){
				$month_saying = $l_october;
			}
			elseif($updated_month == "11"){
				$month_saying = $l_november;
			}
			else{
				$month_saying = $l_december;
			}

			if(isset($style) && $style == "odd"){
				$style = "";
			}
			else{
				$style = "odd";
			}

			echo"
			 <tr>
		 	  <td class=\"$style\">
				<span>";

				// Parent?
				if($get_workout_weekly_period_id != 0){
					$query_p = "SELECT workout_period_id, workout_period_yearly_id, workout_period_title, workout_period_updated FROM $t_workout_plans_period WHERE workout_period_id=$get_workout_weekly_period_id AND workout_period_user_id=$my_user_id_mysql";
					$result_p = mysqli_query($link, $query_p);
					$row_p = mysqli_fetch_row($result_p);
					list($get_workout_period_id, $get_workout_period_yearly_id, $get_workout_period_title, $get_workout_period_updated) = $row_p;

					// Does the parent have parent?	
					if($get_workout_period_yearly_id != 0){
						$query_y = "SELECT workout_yearly_id, workout_yearly_title, workout_yearly_updated FROM $t_workout_plans_yearly WHERE workout_yearly_id=$get_workout_period_yearly_id AND workout_yearly_user_id=$my_user_id_mysql";
						$result_y = mysqli_query($link, $query_y);
						$row_y = mysqli_fetch_row($result_y);
						list($get_workout_yearly_id, $get_workout_yearly_title, $get_workout_yearly_updated) = $row_y;

						echo"
						$get_workout_yearly_title &gt;
						";
					}

					echo"
					$get_workout_period_title &gt;
					";
				}

				echo"<a href=\"weekly_workout_plan_edit.php?weekly_id=$get_workout_weekly_id&amp;l=$l\">$get_workout_weekly_title</span>
			 </td>
			</tr>
			";
		} // while 
	} // $duration_type == "week"
	echo"
	 </tbody>
	</table>
	<!-- //List all exercises -->
	";
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/exercises/my_exercises.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>