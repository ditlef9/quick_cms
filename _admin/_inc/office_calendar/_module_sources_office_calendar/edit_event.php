<?php 
/**
*
* File: office_calendar/edit_event.php
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
include("$root/_admin/_translations/site/$l/office_calendar/ts_office_calendar.php");
include("$root/_admin/_translations/site/$l/office_calendar/ts_add_event.php");
include("$root/_admin/_translations/site/$l/users/ts_users.php");

/*- Tables ---------------------------------------------------------------------------- */
include("tables_office_calendar.php");


/*- Variables -------------------------------------------------------------------------- */
$tabindex = 0;
if(isset($_GET['event_id'])) {
	$event_id = $_GET['event_id'];
	$event_id = strip_tags(stripslashes($event_id));
}
else{
	$event_id = "";
}
$event_id_mysql = quote_smart($link, $event_id);

/*- Content ---------------------------------------------------------------------------------- */
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Me
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
	// Event
	$query = "SELECT event_id, event_user_id, event_user_name, event_location_id, event_location_title, event_equipment_id, event_equipment_title, event_text, event_bg_color, event_text_color, event_from_datetime, event_from_time, event_from_day, event_from_month, event_from_year, event_from_hour, event_from_minute, event_to_datetime, event_to_time, event_to_day, event_to_month, event_to_year, event_to_hour, event_to_minute FROM $t_office_calendar_events WHERE event_id=$event_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_event_id, $get_current_event_user_id, $get_current_event_user_name, $get_current_event_location_id, $get_current_event_location_title, $get_current_event_equipment_id, $get_current_event_equipment_title, $get_current_event_text, $get_current_event_bg_color, $get_current_event_text_color, $get_current_event_from_datetime, $get_current_event_from_time, $get_current_event_from_day, $get_current_event_from_month, $get_current_event_from_year, $get_current_event_from_hour, $get_current_event_from_minute, $get_current_event_to_datetime, $get_current_event_to_time, $get_current_event_to_day, $get_current_event_to_month, $get_current_event_to_year, $get_current_event_to_hour, $get_current_event_to_minute) = $row;
	
	if($get_current_event_id == ""){
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$l_office_calendar - 404 server error";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");
		echo"
		<h1>Event not found</h1>
		";
	}
	else{
			
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$l_office_calendar - $l_event";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		if($process == "1"){
			// Dates
			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j. M Y H:i");

			$inp_text = $_POST['inp_text'];
			$inp_text = output_html($inp_text);
			$inp_text_mysql = quote_smart($link, $inp_text);

			// From
			$inp_from_day = $_POST['inp_from_day'];
			$inp_from_day = output_html($inp_from_day);
			$inp_from_day_mysql = quote_smart($link, $inp_from_day);

			$inp_from_month = $_POST['inp_from_month'];
			$inp_from_month = output_html($inp_from_month);
			$inp_from_month_mysql = quote_smart($link, $inp_from_month);

			$inp_from_year = $_POST['inp_from_year'];
			$inp_from_year = output_html($inp_from_year);
			$inp_from_year_mysql = quote_smart($link, $inp_from_year);

			$inp_from_hour = $_POST['inp_from_hour'];
			$inp_from_hour = output_html($inp_from_hour);
			$inp_from_hour_mysql = quote_smart($link, $inp_from_hour);

			$inp_from_minute = $_POST['inp_from_minute'];
			$inp_from_minute = output_html($inp_from_minute);
			$inp_from_minute_mysql = quote_smart($link, $inp_from_minute);

			$inp_from_datetime = "$inp_from_year-$inp_from_month-$inp_from_day $inp_from_hour:$inp_from_minute:00";
			$inp_from_datetime_mysql = quote_smart($link, $inp_from_datetime);

			$inp_from_time = strtotime($inp_from_datetime);
			$inp_from_time_mysql = quote_smart($link, $inp_from_time);
		
			// From saying
			$from_month_saying = "";
			if($inp_from_month == "01" OR $inp_from_month == "1"){
				$from_month_saying = "$l_month_january";
			}
			elseif($inp_from_month == "02" OR $inp_from_month == "2"){
				$from_month_saying = "$l_month_february";
			}
			elseif($inp_from_month == "03" OR $inp_from_month == "3"){
				$from_month_saying = "$l_month_march";
			}
			elseif($inp_from_month == "04" OR $inp_from_month == "4"){
				$from_month_saying = "$l_month_april";
			}
			elseif($inp_from_month == "05" OR $inp_from_month == "5"){
				$from_month_saying = "$l_month_may";
			}
			elseif($inp_from_month == "06" OR $inp_from_month == "6"){
				$from_month_saying = "$l_month_june";
			}
			elseif($inp_from_month == "07" OR $inp_from_month == "7"){
				$from_month_saying = "$l_month_juli";
			}
			elseif($inp_from_month == "08" OR $inp_from_month == "8"){
				$from_month_saying = "$l_month_august";
			}
			elseif($inp_from_month == "09" OR $inp_from_month == "9"){
				$from_month_saying = "$l_month_september";
			}
			elseif($inp_from_month == "10"){
				$from_month_saying = "$l_month_october";
			}
			elseif($inp_from_month == "11"){
				$from_month_saying = "$l_month_november";
			}
			else{
				$from_month_saying = "$l_month_december";
			}
			
			$inp_from_saying = "$inp_from_day $from_month_saying $inp_from_year $inp_from_hour:$inp_from_minute";
			$inp_from_saying_mysql = quote_smart($link, $inp_from_saying);

			// To
			$inp_to_day = $_POST['inp_to_day'];
			$inp_to_day = output_html($inp_to_day);
			$inp_to_day_mysql = quote_smart($link, $inp_to_day);

			$inp_to_month = $_POST['inp_to_month'];
			$inp_to_month = output_html($inp_to_month);
			$inp_to_month_mysql = quote_smart($link, $inp_to_month);

			$inp_to_year = $_POST['inp_to_year'];
			$inp_to_year = output_html($inp_to_year);
			$inp_to_year_mysql = quote_smart($link, $inp_to_year);

			$inp_to_hour = $_POST['inp_to_hour'];
			$inp_to_hour = output_html($inp_to_hour);
			$inp_to_hour_mysql = quote_smart($link, $inp_to_hour);

			$inp_to_minute = $_POST['inp_to_minute'];
			$inp_to_minute = output_html($inp_to_minute);
			$inp_to_minute_mysql = quote_smart($link, $inp_to_minute);
	
			$inp_to_datetime = "$inp_to_year-$inp_to_month-$inp_to_day $inp_to_hour:$inp_to_minute:00";
			$inp_to_datetime_mysql = quote_smart($link, $inp_to_datetime);

			$inp_to_time = strtotime($inp_to_datetime);
			$inp_to_time_mysql = quote_smart($link, $inp_to_time);


		// To saying
		$to_month_saying = "";
		if($inp_to_month == "01" OR $inp_to_month == "1"){
			$to_month_saying = "$l_month_january";
		}
		elseif($inp_to_month == "02" OR $inp_to_month == "2"){
			$to_month_saying = "$l_month_february";
		}
		elseif($inp_to_month == "03" OR $inp_to_month == "3"){
			$to_month_saying = "$l_month_march";
		}
		elseif($inp_to_month == "04" OR $inp_to_month == "4"){
			$to_month_saying = "$l_month_april";
		}
		elseif($inp_to_month == "05" OR $inp_to_month == "5"){
			$to_month_saying = "$l_month_may";
		}
		elseif($inp_to_month == "06" OR $inp_to_month == "6"){
			$to_month_saying = "$l_month_june";
		}
		elseif($inp_to_month == "07" OR $inp_to_month == "7"){
			$to_month_saying = "$l_month_juli";
		}
		elseif($inp_to_month == "08" OR $inp_to_month == "8"){
			$to_month_saying = "$l_month_august";
		}
		elseif($inp_to_month == "09" OR $inp_to_month == "9"){
			$to_month_saying = "$l_month_september";
		}
		elseif($inp_to_month == "10"){
			$to_month_saying = "$l_month_october";
		}
		elseif($inp_to_month == "11"){
			$to_month_saying = "$l_month_november";
		}
		else{
			$to_month_saying = "$l_month_december";
		}
		
		$inp_to_saying = "$inp_to_day $to_month_saying $inp_to_year $inp_to_hour:$inp_to_minute";
		$inp_to_saying_mysql = quote_smart($link, $inp_to_saying);

			// Location
			$inp_location_id = $_POST['inp_location_id'];
			$inp_location_id = output_html($inp_location_id);
			$inp_location_id_mysql = quote_smart($link, $inp_location_id);

			$query = "SELECT location_id, location_title, location_bg_color, location_text_color FROM $t_office_calendar_locations WHERE location_id=$inp_location_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_location_id, $get_current_location_title, $get_current_location_bg_color, $get_current_location_text_color) = $row;
			$inp_location_title_mysql = quote_smart($link, $get_current_location_title);
			if($get_current_location_bg_color == ""){
				$get_current_location_bg_color = "#efefef";
			}
			$inp_bg_color_mysql = quote_smart($link, $get_current_location_bg_color);
			if($get_current_location_text_color == ""){
				$get_current_location_text_color = "#000";
			}
			$inp_text_color_mysql = quote_smart($link, $get_current_location_text_color);
	

			// Equipment
			$inp_equipment_id = $_POST['inp_equipment_id'];
			$inp_equipment_id = output_html($inp_equipment_id);
			$inp_equipment_id_mysql = quote_smart($link, $inp_equipment_id);

			$query = "SELECT equipment_id, equipment_location_id, equipment_location_title, equipment_title, equipment_description, equipment_sub_description, equipment_barcode FROM $t_office_calendar_equipments WHERE equipment_id=$inp_equipment_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_equipment_id, $get_current_equipment_location_id, $get_current_equipment_location_title, $get_current_equipment_title, $get_current_equipment_description, $get_current_equipment_sub_description, $get_current_equipment_barcode) = $row;
	
			$inp_equipment_title_mysql = quote_smart($link, $get_current_equipment_title);

			// User
			$inp_user_name = $_POST['inp_user_name'];
			$inp_user_name = output_html($inp_user_name);
			$inp_user_name_mysql = quote_smart($link, $inp_user_name);
			$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_name=$inp_user_name_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_language, $get_user_last_online, $get_user_rank, $get_user_login_tries) = $row;
			
			$inp_user_name_mysql = quote_smart($link, $get_user_name);
		
			// Update
			$result = mysqli_query($link, "UPDATE $t_office_calendar_events SET 
event_user_id=$get_user_id, 
event_user_name=$inp_user_name_mysql, 
event_user_name=$inp_user_name_mysql, 
event_updated_datetime='$datetime',
event_location_id=$inp_location_id_mysql, 
event_location_title=$inp_location_title_mysql,
event_equipment_id=$inp_equipment_id_mysql,
event_equipment_title=$inp_equipment_title_mysql, 
event_text=$inp_text_mysql, 
event_bg_color=$inp_bg_color_mysql, 
event_text_color=$inp_text_color_mysql, 
event_from_datetime=$inp_from_datetime_mysql, 
event_from_time=$inp_from_time_mysql, 
event_from_day=$inp_from_day_mysql, 
event_from_month=$inp_from_month_mysql, 
event_from_year=$inp_from_year_mysql, 
event_from_hour=$inp_from_hour_mysql, 
event_from_minute=$inp_from_minute_mysql, 
event_from_saying_date_time=$inp_from_saying_mysql, 
event_to_datetime=$inp_to_datetime_mysql, 
event_to_time=$inp_to_time_mysql,
event_to_day=$inp_to_day_mysql, 
event_to_month=$inp_to_month_mysql, 
event_to_year=$inp_to_year_mysql, 
event_to_hour=$inp_to_hour_mysql, 
event_to_minute=$inp_to_minute_mysql,
event_to_saying_date_time=$inp_to_saying_mysql
			WHERE event_id=$get_current_event_id") or die(mysqli_error($link));

			// Search engine 
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='office_calendar' AND index_reference_name='event_id' AND index_reference_id=$get_current_event_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$inp_index_title = substr($inp_text, 0, 50);
				$inp_index_title = "$inp_to_saying  $inp_index_title | $l_office_calendar"; 
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_short_description = substr($inp_text, 0, 200);
				$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
									index_title=$inp_index_title_mysql, 
									index_short_description=$inp_index_short_description_mysql 
									WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}

			$url = "index.php?year=$inp_from_year&month=$inp_from_month&day=$inp_from_day&l=$l&ft=success&fm=saved_event";
			header("Location: $url");
			exit;
		
		}
		echo"
		<h1>$l_event</h1>
	
		<!-- Where am I? -->
			<p style=\"padding-top:0;margin-top:0;\"><b>$l_you_are_here:</b><br />
			<a href=\"index.php?year=$get_current_event_from_year&amp;month=$get_current_event_from_month&amp;l=$l\">$l_office_calendar</a>
			&gt;
			<a href=\"edit_event.php?event_id=$get_current_event_id&amp;l=$l\">$l_event</a>
			</p>
			<div style=\"height: 10px;\"></div>
		<!-- //Where am I? -->

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

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_text\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Edit event form -->
			<form method=\"POST\" action=\"edit_event.php?event_id=$get_current_event_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		
			<p>$l_text:<br />
			<textarea name=\"inp_text\" rows=\"4\" cols=\"50\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\">";
			$get_current_event_text = str_replace("<br />", "\n", $get_current_event_text);
			echo"$get_current_event_text</textarea>
			</p>
		
			<p>$l_from:<br />
			<select name=\"inp_from_day\">
				<option value=\"\""; if($get_current_event_from_day == ""){ echo" selected=\"selected\""; } echo">- $l_day -</option>\n";
				for($x=1;$x<32;$x++){
					if($x<10){
						$y = 0 . $x;
					}
					else{
						$y = $x;
					}
					echo"<option value=\"$y\""; if($get_current_event_from_day == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
				}
			echo"
			</select>

			<select name=\"inp_from_month\">
				<option value=\"\""; if($get_current_event_from_month == ""){ echo" selected=\"selected\""; } echo">- $l_month -</option>\n";
				$l_month_array[0] = "";
				$l_month_array[1] = "$l_month_january";
				$l_month_array[2] = "$l_month_february";
				$l_month_array[3] = "$l_month_march";
				$l_month_array[4] = "$l_month_april";
				$l_month_array[5] = "$l_month_may";
				$l_month_array[6] = "$l_month_june";
				$l_month_array[7] = "$l_month_juli";
				$l_month_array[8] = "$l_month_august";
				$l_month_array[9] = "$l_month_september";
				$l_month_array[10] = "$l_month_october";
				$l_month_array[11] = "$l_month_november";
				$l_month_array[12] = "$l_month_december";
				for($x=1;$x<13;$x++){
					if($x<10){
						$y = 0 . $x;
					}
					else{
						$y = $x;
					}
					echo"<option value=\"$y\""; if($get_current_event_from_month == "$x"){ echo" selected=\"selected\""; } echo">$l_month_array[$x]</option>\n";
				}
				echo"
			</select>

			<select name=\"inp_from_year\">
					<option value=\"\""; if($get_current_event_from_year == ""){ echo" selected=\"selected\""; } echo">- $l_year -</option>\n";
				$start_year = date("Y");
				$stop_year = $start_year+10;
				for($x=$start_year;$x<$stop_year;$x++){
					echo"<option value=\"$x\""; if($get_current_event_from_year == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
					
				}
				echo"
			</select>
				$l_at_lowercase
				<select name=\"inp_from_hour\">\n";
				for($x=0;$x<24;$x++){
					if($x<10){
						$y = 0 . $x;
					}
					else{
						$y = $x;
					}
					echo"<option value=\"$y\""; if($get_current_event_from_hour == "$x"){ echo" selected=\"selected\""; } echo">$y</option>\n";
				}
				echo"
				</select>
				:
				<select name=\"inp_from_minute\">\n";
				for($x=0;$x<60;$x++){
					if($x<10){
						$y = 0 . $x;
					}
					else{
						$y = $x;
					}
					echo"<option value=\"$y\""; if($get_current_event_from_minute == "$x"){ echo" selected=\"selected\""; } echo">$y</option>\n";
				}
				echo"
			</select>
			</p>



		
			<p>$l_to:<br />
			<select name=\"inp_to_day\">
				<option value=\"\""; if($get_current_event_to_day == ""){ echo" selected=\"selected\""; } echo">- $l_day -</option>\n";
				for($x=1;$x<32;$x++){
					if($x<10){
						$y = 0 . $x;
					}
					else{
						$y = $x;
					}
					echo"<option value=\"$y\""; if($get_current_event_to_day == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
				}
			echo"
			</select>

			<select name=\"inp_to_month\">
				<option value=\"\""; if($get_current_event_to_month == ""){ echo" selected=\"selected\""; } echo">- $l_month -</option>\n";
				$l_month_array[0] = "";
				$l_month_array[1] = "$l_month_january";
				$l_month_array[2] = "$l_month_february";
				$l_month_array[3] = "$l_month_march";
				$l_month_array[4] = "$l_month_april";
				$l_month_array[5] = "$l_month_may";
				$l_month_array[6] = "$l_month_june";
				$l_month_array[7] = "$l_month_juli";
				$l_month_array[8] = "$l_month_august";
				$l_month_array[9] = "$l_month_september";
				$l_month_array[10] = "$l_month_october";
				$l_month_array[11] = "$l_month_november";
				$l_month_array[12] = "$l_month_december";
				for($x=1;$x<13;$x++){
					if($x<10){
						$y = 0 . $x;
					}
					else{
						$y = $x;
					}
					echo"<option value=\"$y\""; if($get_current_event_to_month == "$x"){ echo" selected=\"selected\""; } echo">$l_month_array[$x]</option>\n";
				}
				echo"
			</select>

			<select name=\"inp_to_year\">
				<option value=\"\""; if($get_current_event_to_year == ""){ echo" selected=\"selected\""; } echo">- $l_year -</option>\n";
				$start_year = date("Y");
				$stop_year = $start_year+10;
				for($x=$start_year;$x<$stop_year;$x++){
					echo"<option value=\"$x\""; if($get_current_event_to_year == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
				}
			echo"
			</select>

				$l_at_lowercase
				<select name=\"inp_to_hour\">\n";
				for($x=0;$x<24;$x++){
					if($x<10){
						$y = 0 . $x;
					}
					else{
						$y = $x;
					}
					echo"<option value=\"$y\""; if($get_current_event_to_hour == "$x"){ echo" selected=\"selected\""; } echo">$y</option>\n";
				}
				echo"
				</select>
				:
				<select name=\"inp_to_minute\">\n";
				for($x=0;$x<60;$x++){
					if($x<10){
						$y = 0 . $x;
					}
					else{
						$y = $x;
					}
					echo"<option value=\"$y\""; if($get_current_event_to_minute == "$x"){ echo" selected=\"selected\""; } echo">$y</option>\n";
				}
				echo"
			</select>
			</p>

			<p>$l_location:<br />
			<select name=\"inp_location_id\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\">
				<option value=\"0\">-</option>\n";
				$query = "SELECT location_id, location_title FROM $t_office_calendar_locations ORDER BY location_title ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
				list($get_location_id, $get_location_title) = $row;
					echo"							";
					echo"<option value=\"$get_location_id\""; if($get_location_id == "$get_current_event_location_id"){ echo" selected=\"selected\""; } echo">$get_location_title</option>\n";
				}
				echo"
				</select>
				</p>

			<p>$l_equipment:<br />
			<select name=\"inp_equipment_id\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\">
				<option value=\"0\">-</option>\n";
				$query = "SELECT equipment_id, equipment_title FROM $t_office_calendar_equipments ORDER BY equipment_title ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
				list($get_equipment_id, $get_equipment_title) = $row;
					echo"							";
					echo"<option value=\"$get_equipment_id\""; if($get_equipment_id == "$get_current_event_equipment_id"){ echo" selected=\"selected\""; } echo">$get_equipment_title</option>\n";
				}
				echo"
			</select>
			</p>

			<p>$l_user_name:<br />
			<input type=\"text\" name=\"inp_user_name\" id=\"inp_user_name\" value=\"$get_current_event_user_name\" size=\"25\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />
			</p>

		
			<!-- User Search script -->
			<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$('#inp_user_name').keyup(function () {
					// getting the value that user typed
       					var searchString    = $(\"#inp_user_name\").val();
 					// forming the queryString
      					var data            = 'l=$l&q='+ searchString;
         
        				// if searchString is not empty
        				if(searchString) {
						// ajax call
            					\$.ajax({
                					type: \"GET\",
               						url: \"add_event_jquery_search_for_user.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#autosearch_search_results_show\").html(''); 
							},
               						success: function(html){
                    						\$(\"#autosearch_search_results_show\").append(html);
              						}
            					});
       					}
        				return false;
            			});
         		});
			</script>
			<div id=\"autosearch_search_results_show\"></div>
			<!-- //User Search script -->
		

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn_default\" />
			<a href=\"delete_event.php?event_id=$get_current_event_id&amp;l=$l\" class=\"btn_warning\">$l_delete</a>
			</p>
			</form>
		<!-- Edit event form -->	
		";
	} // event foound
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