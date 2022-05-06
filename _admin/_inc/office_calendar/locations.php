<?php
/**
*
* File: _admin/_inc/edb/physical_locations_index.php
* Version 11:55 30.12.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_office_calendar_locations	= $mysqlPrefixSav . "office_calendar_locations";
$t_office_calendar_equipments	= $mysqlPrefixSav . "office_calendar_equipments";
$t_office_calendar_events	= $mysqlPrefixSav . "office_calendar_events";

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['location_id'])) {
	$location_id = $_GET['location_id'];
	$location_id = strip_tags(stripslashes($location_id));
}
else{
	$location_id = "";
}
if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
	if($order_method != "asc" && $order_method != "desc"){
		echo"Wrong order method";
		die;
	}
}
else{
	$order_method = "asc";
}


if($action == ""){
	echo"
	<h1>Locations</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=office_calendar&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">office calendar</a>
		&gt;
		<a href=\"index.php?open=office_calendar&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Locations</a>
		</p>
	<!-- //Where am I? -->

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->

	<!-- Navigation + Search -->
		<table>
		 <tr>
		  <td>
			<!-- Navigation -->
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;order_by=$;order_by&amp;order_method=$order_method&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New</a>
				</p>
			<!-- //Navigation -->
		  </td>
		  <td style=\"padding-left: 6px;\">
			
		  </td>
		 </tr>
		</table>
	<!-- //Navigation + Search -->


	<!-- Locations -->

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" style=\"width: 40%;\">";

			if($order_by == "location_title" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=location_title&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Title</b></a>";
			if($order_by == "location_title" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "location_title" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
	
			echo"</span>
		   </th>
		  </tr>
		 </thead>

		";
		$query = "SELECT location_id, location_title FROM $t_office_calendar_locations";
		if($order_by == "location_title"){
			if($order_method  == "asc" OR $order_method == "desc"){
				$query = $query  . " ORDER BY $order_by $order_method";
			}
		}
		else{
		}


		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_location_id, $get_location_title) = $row;
			
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
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open&amp;location_id=$get_location_id&amp;l=$l&amp;editor_language=$editor_language\">$get_location_title</a>
				</span>
			  </td>
			 </tr>";
		} // while
		
		echo"
		 </tbody>
		</table>
	<!-- //Locations -->
	";
} // action == ""
elseif($action == "new"){
	if($process == "1"){

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_bg_color = $_POST['inp_bg_color'];
		$inp_bg_color = output_html($inp_bg_color);
		$inp_bg_color_mysql = quote_smart($link, $inp_bg_color);

		$inp_text_color = $_POST['inp_text_color'];
		$inp_text_color = output_html($inp_text_color);
		$inp_text_color_mysql = quote_smart($link, $inp_text_color);

		mysqli_query($link, "INSERT INTO $t_office_calendar_locations
		(location_id, location_title, location_bg_color, location_text_color) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_bg_color_mysql, $inp_text_color_mysql)
		") or die(mysqli_error($link));

		$url = "index.php?open=$open&page=$page&action=new&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l&ft=success&fm=created_$inp_title";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=office_calendar&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">office calendar</a>
		&gt;
		<a href=\"index.php?open=office_calendar&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Locations</a>
		&gt;
		<a href=\"index.php?open=office_calendar&amp;page=$page&amp;action=new&amp;editor_language=$editor_language&amp;l=$l\">New</a>
		</p>
	<!-- //Where am I? -->

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->

	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- New form -->";
		
		echo"
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p>Bg color:<br />
		<input type=\"text\" name=\"inp_bg_color\" value=\"\" size=\"25\" />
		</p>

		<p>Text color:<br />
		<input type=\"text\" name=\"inp_text_color\" value=\"\" size=\"25\" />
		</p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" /></p>

		</form>
	<!-- //New form -->

	";

} // new
elseif($action == "open"){
	// Find location
	$location_id_mysql = quote_smart($link, $location_id);
	$query = "SELECT location_id, location_title, location_bg_color, location_text_color FROM $t_office_calendar_locations WHERE location_id=$location_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_location_id, $get_current_location_title, $get_current_location_bg_color, $get_current_location_text_color) = $row;
	
	if($get_current_location_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{

	

		if($process == "1"){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_bg_color = $_POST['inp_bg_color'];
			$inp_bg_color = output_html($inp_bg_color);
			$inp_bg_color_mysql = quote_smart($link, $inp_bg_color);

			$inp_text_color = $_POST['inp_text_color'];
			$inp_text_color = output_html($inp_text_color);
			$inp_text_color_mysql = quote_smart($link, $inp_text_color);

			$result = mysqli_query($link, "UPDATE $t_office_calendar_locations SET 
					location_title=$inp_title_mysql,
					location_bg_color=$inp_bg_color_mysql,
					location_text_color=$inp_text_color_mysql 
					 WHERE location_id=$get_current_location_id") or die(mysqli_error($link));

			$url = "index.php?open=$open&page=$page&action=$action&location_id=$get_current_location_id&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_location_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=office_calendar&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">office calendar</a>
			&gt;
			<a href=\"index.php?open=office_calendar&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Locations</a>
			&gt;
			<a href=\"index.php?open=edb&amp;page=$page&amp;action=$action&amp;location_id=$get_current_location_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_location_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Edit form -->";
		
			echo"
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;location_id=$get_current_location_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		

			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_location_title\" size=\"25\" />
			</p>

			<p>Bg color:<br />
			<input type=\"text\" name=\"inp_bg_color\" value=\"$get_current_location_bg_color\" size=\"25\" />
			</p>


			<p>Text color:<br />
			<input type=\"text\" name=\"inp_text_color\" value=\"$get_current_location_text_color\" size=\"25\" />
			</p>


			<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" />
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;location_id=$get_current_location_id&amp;l=$l\" class=\"btn_warning\">Delete</a></p>
	
			</form>
		<!-- //New form -->

		";
	} // location found
} // open_district
elseif($action == "delete"){	// Find location
	$location_id_mysql = quote_smart($link, $location_id);
	$query = "SELECT location_id, location_title FROM $t_office_calendar_locations WHERE location_id=$location_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_location_id, $get_current_location_title) = $row;
	
	if($get_current_location_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{

	

		if($process == "1"){
			$result = mysqli_query($link, "DELETE FROM $t_office_calendar_locations WHERE location_id=$get_current_location_id") or die(mysqli_error($link));

			$url = "index.php?open=$open&page=$page&&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l&ft=success&fm=deleted";
			header("Location: $url");
			exit;
	
		}
		echo"
		<h1>$get_current_location_title</h1>


		<!-- Where am I? -->

			<p><b>You are here:</b><br />
			<a href=\"index.php?open=office_calendar&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">office calendar</a>
			&gt;
			<a href=\"index.php?open=office_calendar&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Locations</a>
			&gt;
			<a href=\"index.php?open=edb&amp;page=$page&amp;action=$action&amp;location_id=$get_current_location_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_location_title</a>
			&gt;
			<a href=\"index.php?open=edb&amp;page=$page&amp;action=$action&amp;location_id=$get_current_location_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
			</p>
		<!-- //Where am I? -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Delete form -->
			<p>
			Are you sure you want to delete? The action cannot be undone.
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;location_id=$get_current_location_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>
		<!-- //Delete form -->

		";
	} // location found
} // delete
?>