<?php 
/**
*
* File: office_calendar/index.php
* Version 1.0
* Date 21:03 04.08.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Language --------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/users/ts_users.php");

/*- Tables ---------------------------------------------------------------------------- */
include("tables_office_calendar.php");

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['year'])){
	$year = $_GET['year'];
	$year = strip_tags(stripslashes($year));
	if($year == ""){
		$year = date("Y");
	}
}
else{
	$year = date("Y");
}
$year_mysql = quote_smart($link, $year);
if(isset($_GET['month'])){
	$month = $_GET['month'];
	$month = strip_tags(stripslashes($month));
	if($month == ""){
		$month = date("m");
	}
}
else{
	$month = date("m");
}
$month_mysql = quote_smart($link, $month);
/*- Content ---------------------------------------------------------------------------------- */
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Me
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	// Month saying 
	$month_saying = "x";
	if($month == "01" OR $month == "1"){
		$month_saying = "$l_month_january";
	}
	elseif($month == "02" OR $month == "2"){
		$month_saying = "$l_month_february";
	}
	elseif($month == "03" OR $month == "3"){
		$month_saying = "$l_month_march";
	}
	elseif($month == "04" OR $month == "4"){
		$month_saying = "$l_month_april";
	}
	elseif($month == "05" OR $month == "5"){
		$month_saying = "$l_month_may";
	}
	elseif($month == "06" OR $month == "6"){
		$month_saying = "$l_month_june";
	}
	elseif($month == "07" OR $month == "7"){
		$month_saying = "$l_month_juli";
	}
	elseif($month == "08" OR $month == "8"){
		$month_saying = "$l_month_august";
	}
	elseif($month == "09" OR $month == "9"){
		$month_saying = "$l_month_september";
	}
	elseif($month == "10"){
		$month_saying = "$l_month_october";
	}
	elseif($month == "11"){
		$month_saying = "$l_month_november";
	}
	else{
		$month_saying = "$l_month_december";
	}



	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_office_calendar - $month_saying $year";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"
	<h1>$l_office_calendar</h1>


		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->
	<!-- Calendar and events -->
		<table style=\"width: 100%;\">
		 <tr>
		  <td class=\"td_upcoming_events_b_side\">

			<!-- Upcoming events right side -->
				<h2>$l_upcoming_events</h2>
				<div style=\"height: 10px;\"></div>
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_from</span>
				   </th>
				   <th scope=\"col\">
					<span>$l_title</span>
				   </th>
				  </tr>
				 </thead>
				 <tbody>";
	
				$time = time();
				$query = "SELECT event_id, event_user_id, event_user_name, event_location_id, event_location_title, event_equipment_id, event_equipment_title, event_text, event_bg_color, event_text_color, event_from_datetime, event_from_time, event_from_day, event_from_month, event_from_year, event_from_hour, event_from_minute, event_from_saying_date_time, event_to_datetime, event_to_time, event_to_day, event_to_month, event_to_year, event_to_hour, event_to_minute, event_to_saying_date_time FROM $t_office_calendar_events WHERE event_from_time > '$time' ORDER BY event_from_datetime ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_event_id, $get_event_user_id, $get_event_user_name, $get_event_location_id, $get_event_location_title, $get_event_equipment_id, $get_event_equipment_title, $get_event_text, $get_event_bg_color, $get_event_text_color, $get_event_from_datetime, $get_event_from_time, $get_event_from_day, $get_event_from_month, $get_event_from_year, $get_event_from_hour, $get_event_from_minute, $get_event_from_saying_date_time, $get_event_to_datetime, $get_event_to_time, $get_event_to_day, $get_event_to_month, $get_event_to_year, $get_event_to_hour, $get_event_to_minute, $get_event_to_saying_date_time) = $row;

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
						<span><a href=\"edit_event.php?event_id=$get_event_id&amp;l=$l\">$get_event_from_saying_date_time</a></span>
					   </td>
					   <td class=\"$style\">
						<span>$get_event_text";

						if($get_event_equipment_title != "" OR $get_event_location_title != ""){
							echo"<br />\n";
							if($get_event_equipment_title != "" && $get_event_location_title != ""){
								echo"$get_event_equipment_title &middot; $get_event_location_title<br />\n";
							}
							elseif($get_event_equipment_title != "" && $get_event_location_title == ""){
								echo"$get_event_equipment_title<br />\n";
							}
							elseif($get_event_equipment_title == "" && $get_event_location_title != ""){
								echo"$get_event_location_title<br />\n";
							}
						}
						echo"
						</span>
					   </td>
					  </tr>
					";
				} // upcoming events
				echo"
				 </tbody>
				</table>
			<!-- //Upcoming events side -->
		  </td>
		  <td class=\"td_calendar_a_side\">

				<!-- Navigation -->";
					$previous_year = $year;
					$previous_month = $month-1;
					if($previous_month == 0){
						$previous_month = 12;
						$previous_year = $year-1;
					}

					$next_year = $year;
					$next_month = $month+1;
					if($next_month == 13){
						$next_month = 01;
						$next_year = $year+1;
					}
					echo"

					<table style=\"width: 100%;\">
					 <tr>
					  <td style=\"width:33%;\">
						<p>
						<a href=\"index.php?year=$previous_year&amp;month=$previous_month&amp;l=$l\" class=\"btn_default\">&lt;</a>
						</p>
					  </td>
					  <td style=\"width:31%;text-align: center;\">
						<h2 style=\"color: #000;\">$month_saying $year</h2>
					  </td>
					  <td style=\"width:33%;text-align: right;\">
						<p>
						<a href=\"index.php?year=$next_year&amp;month=$next_month&amp;l=$l\" class=\"btn_default\">&gt;</a>
						</p>
					  </td>
					 </tr>
					</table>
					<div style=\"height: 10px;\"></div>
				<!-- //Navigation -->

				<table class=\"equipment_calendar\">
			 <thead>
			  <tr>
			   <th>
				<span><b>$l_monday</b></span>
			   </th>
			   <th>
				<span><b>$l_tuesday</b></span>
			   </th>
			   <th>
				<span><b>$l_wednesday</b></span>
			   </th>
			   <th>
				<span><b>$l_thursday</b></span>
			   </th>
			   <th>
				<span><b>$l_friday</b></span>
			   </th>
			   <th>
				<span><b>$l_saturday</b></span>
			   </th>
			   <th>
				<span><b>$l_sunday</b></span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
		";

			// days and weeks vars now 
			$days_until_month_starts = date('w',mktime(0,0,0,$month,1,$year)); // first days of calendar that are not a part of this month
			$first_day = date('D',mktime(0,0,0,$month,1,$year));
			$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
		
			if($first_day == "Sun"){
				$days_until_month_starts = 7;
			}

			$days_in_prev_month_and_this_month = $days_in_month+$days_until_month_starts-1;
			$day_of_month_counter = 1;
			for($x=0;$x<$days_in_prev_month_and_this_month;$x++){
				// First day on Sunday
				


				if($x == 0 OR $x == 14 OR $x == 21 OR $x == 28 OR $x == 35){
					echo"
					  <tr>
					";
				}

				if($days_until_month_starts-1 > $x){
					echo"
					  <td style=\"width:14%;vertical-align: top;\">
						<div class=\"calendar_min_height\"></div>
					  </td>
					";
				}
				else{
					echo"
					  <td class=\"day\" style=\"width:14%;vertical-align: top;\">
						<div class=\"calendar_min_height\">
						<div class=\"calendar_day_number\">
							<a href=\"add_event.php?year=$year&amp;month=$month&amp;day=$day_of_month_counter&amp;l=$l\">$day_of_month_counter</a>
						</div>
					";
					// Events
					$query = "SELECT event_id, event_user_id, event_user_name, event_location_id, event_location_title, event_equipment_id, event_equipment_title, event_text, event_bg_color, event_text_color, event_from_datetime, event_from_time, event_from_day, event_from_month, event_from_year, event_from_hour, event_from_minute, event_to_datetime, event_to_time, event_to_day, event_to_month, event_to_year, event_to_hour, event_to_minute FROM $t_office_calendar_events WHERE event_from_day=$day_of_month_counter AND event_from_month=$month_mysql AND event_from_year=$year_mysql ORDER BY event_from_datetime ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_event_id, $get_event_user_id, $get_event_user_name, $get_event_location_id, $get_event_location_title, $get_event_equipment_id, $get_event_equipment_title, $get_event_text, $get_event_bg_color, $get_event_text_color, $get_event_from_datetime, $get_event_from_time, $get_event_from_day, $get_event_from_month, $get_event_from_year, $get_event_from_hour, $get_event_from_minute, $get_event_to_datetime, $get_event_to_time, $get_event_to_day, $get_event_to_month, $get_event_to_year, $get_event_to_hour, $get_event_to_minute) = $row;

						$get_event_from_hour_len = strlen($get_event_from_hour);
						$get_event_from_minute_len = strlen($get_event_from_minute);
						$get_event_to_hour_len = strlen($get_event_to_hour);
						$get_event_to_minute_len = strlen($get_event_to_minute);
						if($get_event_from_hour_len == 1){
							$get_event_from_hour = "0" . $get_event_from_hour;
						}
						if($get_event_from_minute_len == 1){
							$get_event_from_minute = "0" . $get_event_from_minute;
						}
						if($get_event_to_hour_len == 1){
							$get_event_to_hour = "0" . $get_event_to_hour;
						}
						if($get_event_to_minute_len == 1){
							$get_event_to_minute = "0" . $get_event_to_minute;
						}

						$get_event_user_name_len = strlen($get_event_user_name);
						if($get_event_user_name_len > 10){
							$get_event_user_name_saying = substr($get_event_user_name, 0, 7);
							$get_event_user_name_saying = $get_event_user_name_saying . "...";
						}
						else{
							$get_event_user_name_saying = "$get_event_user_name";
						}

						echo"
						<div class=\"calendar_event\" style=\"background-color: $get_event_bg_color\">
							<p style=\"color: $get_event_text_color\">
							<a href=\"edit_event.php?event_id=$get_event_id&amp;l=$l\" style=\"color: $get_event_text_color\">$get_event_from_hour:$get_event_from_minute - $get_event_to_hour:$get_event_to_minute</a>
							<a href=\"$root/users/view_profile.php?user_id=$get_event_user_id&amp;l=$l\" style=\"color: $get_event_text_color\" title=\"$get_event_user_name\">$get_event_user_name_saying</a>
							<a href=\"edit_event.php?event_id=$get_event_id&amp;l=$l\" style=\"color: $get_event_text_color\">$get_event_text</a>
							";
							if($get_event_location_title != "" && $get_event_equipment_title != ""){
								echo"<br /><a href=\"edit_event.php?event_id=$get_event_id&amp;l=$l\" style=\"color: $get_event_text_color\">$get_event_location_title, $get_event_equipment_title</a>\n";
							}
							else{
								if($get_event_location_title != ""){
									echo"<br /><a href=\"edit_event.php?event_id=$get_event_id&amp;l=$l\" style=\"color: $get_event_text_color\">$get_event_location_title</a>\n";
								}
								if($get_event_equipment_title != ""){
									echo"<br /><a href=\"edit_event.php?event_id=$get_event_id&amp;l=$l\" style=\"color: $get_event_text_color\">$get_event_equipment_title</a>\n";
								}
							}
							echo"
							</p>
						</div>
						";
					}


					echo"
						</div>
					  </td>
					";	
					$day_of_month_counter++;
				}

				if($x == 6 OR $x == 13 OR $x == 20 OR $x == 27 OR $x == 34){
					echo"
					  </tr>
					";
				}
			} // for weeks
			
			// End table
			if($x == 37){
				// 5
				echo"
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					  </tr>
				";
			}
			elseif($x == 36){
				// 6
				echo"
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					  </tr>
				";
			}
			elseif($x == 35){
				echo"
					  </tr>
				";
			}
			elseif($x == 34){
				// 1
				echo"
					   <td>
					   </td>
					  </tr>
				";
			}
			elseif($x == 33){
				// 2 
				echo"
					   <td>
					   </td>
					   <td>
					   </td>
					  </tr>
				";
			}
			elseif($x == 32){
				// 3 
				echo"
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					  </tr>
				";
			}
			elseif($x == 31){
				// 4
				echo"
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					  </tr>
				";
			}
			elseif($x == 30){
				// 5 
				echo"
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					  </tr>
				";
			}
			elseif($x == 29){
				// 6
				echo"
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					   <td>
					   </td>
					  </tr>
				";
			}
			elseif($x == 28){
				// 0
				echo"
					  </tr>
				";
			}
			else{
				echo"
				<p>End table: x = $x</p>
				";
			}
			echo"
			 </tbody>
			</table>
		<!-- /Calendar -->

			<!-- //Calendar left side -->

		  </td>
		 </tr>
		</table>
	<!-- Calendar and events -->


	";
	

	// Draw calendar

} // logged in
else{
	// Log in
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $l_please_log_in...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=office_calendar\">
	";
} // not logged in

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>