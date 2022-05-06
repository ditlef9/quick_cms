<?php
/**
*
* File: _admin/_inc/equipment_calendar/equipments.php
* Version 22:06 11.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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
if(isset($_GET['equipment_id'])) {
	$equipment_id = $_GET['equipment_id'];
	$equipment_id = strip_tags(stripslashes($equipment_id));
}
else{
	$equipment_id = "";
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
	<h1>Equipments</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Office calendar</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Equipments</a>
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
		   <th scope=\"col\">";

			if($order_by == "equipment_id" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=equipment_id&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Id</b></a>";
			if($order_by == "equipment_id" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "equipment_id" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
	
			echo"</span>
		   </th>
		   <th scope=\"col\">";

			if($order_by == "equipment_location_title" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=equipment_location_title&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Location</b></a>";
			if($order_by == "equipment_location_title" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "equipment_location_title" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
	
			echo"</span>
		   </th>
		   <th scope=\"col\">";

			if($order_by == "equipment_title" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=location_title&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Equipment</b></a>";
			if($order_by == "equipment_title" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "equipment_title" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
	
			echo"</span>
		   </th>
		   <th scope=\"col\">";

			if($order_by == "equipment_description" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=location_title&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Description</b></a>";
			if($order_by == "equipment_description" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "equipment_description" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
	
			echo"</span>
		   </th>
		  </tr>
		 </thead>

		";
		$query = "SELECT equipment_id, equipment_location_id, equipment_location_title, equipment_title, equipment_description, equipment_sub_description, equipment_barcode FROM $t_office_calendar_equipments";
		if($order_by == "equipment_location_title" OR $order_by == "equipment_title" OR $order_by == "equipment_description"){
			if($order_method  == "asc" OR $order_method == "desc"){
				$query = $query  . " ORDER BY $order_by $order_method";
			}
		}
		else{
		}
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_equipment_id, $get_equipment_location_id, $get_equipment_location_title, $get_equipment_title, $get_equipment_description, $get_equipment_sub_description, $get_equipment_barcode) = $row;
			
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
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open&amp;equipment_id=$get_equipment_id&amp;l=$l&amp;editor_language=$editor_language\">$get_equipment_id</a>
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_equipment_location_title
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_equipment_title
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_equipment_description
				</span>
			  </td>
			 </tr>";
		} // while
		
		echo"
		 </tbody>
		</table>
	<!-- //equipments -->
	";
} // action == ""
elseif($action == "new"){
	if($process == "1"){

		$inp_location_id = $_POST['inp_location_id'];
		$inp_location_id = output_html($inp_location_id);
		$inp_location_id_mysql = quote_smart($link, $inp_location_id);
	
		$query = "SELECT location_id, location_title FROM $t_office_calendar_locations WHERE location_id=$inp_location_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_location_id, $get_current_location_title) = $row;
		
		$inp_location_title_mysql = quote_smart($link, $get_current_location_title);

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_description = $_POST['inp_description'];
		$inp_description = output_html($inp_description);
		$inp_description_mysql = quote_smart($link, $inp_description);

		$inp_sub_description = $_POST['inp_sub_description'];
		$inp_sub_description = output_html($inp_sub_description);
		$inp_sub_description_mysql = quote_smart($link, $inp_sub_description);

		$inp_barcode = $_POST['inp_barcode'];
		$inp_barcode = output_html($inp_barcode);
		$inp_barcode_mysql = quote_smart($link, $inp_barcode);

		mysqli_query($link, "INSERT INTO $t_office_calendar_equipments
		(equipment_id, equipment_location_id, equipment_location_title, equipment_title, equipment_description, equipment_sub_description, equipment_barcode) 
		VALUES 
		(NULL, $inp_location_id_mysql, $inp_location_title_mysql, $inp_title_mysql, $inp_description_mysql, $inp_sub_description_mysql, $inp_barcode_mysql)
		") or die(mysqli_error($link));

		$url = "index.php?open=$open&page=$page&action=new&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l&ft=success&fm=created_$inp_title";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>New</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Equipment calendar</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Equipments</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language&amp;l=$l\">New</a>
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
			\$('[name=\"inp_location_id\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- New form -->";
		
		echo"
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


		<p>Location:<br />
		<select name=\"inp_location_id\">";
		$query = "SELECT location_id, location_title FROM $t_office_calendar_locations ORDER BY location_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_location_id, $get_location_title) = $row;
			echo"		";
			echo"<option value=\"$get_location_id\">$get_location_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p>Description:<br />
		<input type=\"text\" name=\"inp_description\" value=\"\" size=\"25\" />
		</p>

		<p>Sub description:<br />
		<input type=\"text\" name=\"inp_sub_description\" value=\"\" size=\"25\" />
		</p>

		<p>Barcode:<br />
		<input type=\"text\" name=\"inp_barcode\" value=\"\" size=\"25\" />
		</p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" /></p>

		</form>
	<!-- //New form -->

	";

} // new
elseif($action == "open"){
	// Find equipment
	$equipment_id_mysql = quote_smart($link, $equipment_id);
	$query = "SELECT equipment_id, equipment_location_id, equipment_location_title, equipment_title, equipment_description, equipment_sub_description, equipment_barcode FROM $t_office_calendar_equipments WHERE equipment_id=$equipment_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_equipment_id, $get_current_equipment_location_id, $get_current_equipment_location_title, $get_current_equipment_title, $get_current_equipment_description, $get_current_equipment_sub_description, $get_current_equipment_barcode) = $row;
	
	if($get_current_equipment_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{

		if($process == "1"){

			$inp_location_id = $_POST['inp_location_id'];
			$inp_location_id = output_html($inp_location_id);
			$inp_location_id_mysql = quote_smart($link, $inp_location_id);
	
			$query = "SELECT location_id, location_title FROM $t_office_calendar_locations WHERE location_id=$inp_location_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_location_id, $get_current_location_title) = $row;
		
			$inp_location_title_mysql = quote_smart($link, $get_current_location_title);

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_description = $_POST['inp_description'];
			$inp_description = output_html($inp_description);
			$inp_description_mysql = quote_smart($link, $inp_description);

			$inp_sub_description = $_POST['inp_sub_description'];
			$inp_sub_description = output_html($inp_sub_description);
			$inp_sub_description_mysql = quote_smart($link, $inp_sub_description);

			$inp_barcode = $_POST['inp_barcode'];
			$inp_barcode = output_html($inp_barcode);
			$inp_barcode_mysql = quote_smart($link, $inp_barcode);


			$result = mysqli_query($link, "UPDATE $t_office_calendar_equipments SET 
					equipment_location_id=$inp_location_id_mysql, 
					equipment_location_title=$inp_location_title_mysql, 
					equipment_title=$inp_title_mysql, 
					equipment_description=$inp_description_mysql, 
					equipment_sub_description=$inp_sub_description_mysql, 
					equipment_barcode=$inp_barcode_mysql
					 WHERE equipment_id=$get_current_equipment_id") or die(mysqli_error($link));

			$url = "index.php?open=$open&page=$page&action=$action&equipment_id=$get_current_equipment_id&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_equipment_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Equipment calendar</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Equipments</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;equipment_id=$get_current_equipment_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_equipment_title</a>
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
				\$('[name=\"inp_location_id\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Edit form -->";
		
			echo"
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;equipment_id=$get_current_equipment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		

			<p>Location:<br />
			<select name=\"inp_location_id\">";
			$query = "SELECT location_id, location_title FROM $t_office_calendar_locations ORDER BY location_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_location_id, $get_location_title) = $row;
				echo"		";
				echo"<option value=\"$get_location_id\""; if($get_location_id == "$get_current_equipment_location_id"){ echo" selected=\"selected\""; } echo">$get_location_title</option>\n";
			}
			echo"
			</select>
			</p>

			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_equipment_title\" size=\"25\" />
			</p>

			<p>Description:<br />
			<input type=\"text\" name=\"inp_description\" value=\"$get_current_equipment_description\" size=\"25\" />
			</p>

			<p>Sub description:<br />
			<input type=\"text\" name=\"inp_sub_description\" value=\"$get_current_equipment_sub_description\" size=\"25\" />
			</p>

			<p>Barcode:<br />
			<input type=\"text\" name=\"inp_barcode\" value=\"$get_current_equipment_barcode\" size=\"25\" />
			</p>


			<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" />
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;equipment_id=$get_current_equipment_id&amp;l=$l\" class=\"btn_warning\">Delete</a></p>
	
			</form>
		<!-- //New form -->

		";
	} // equipment found
} // open
elseif($action == "delete"){

	// Find equipment
	$equipment_id_mysql = quote_smart($link, $equipment_id);
	$query = "SELECT equipment_id, equipment_location_id, equipment_location_title, equipment_title, equipment_description, equipment_sub_description, equipment_barcode FROM $t_office_calendar_equipments WHERE equipment_id=$equipment_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_equipment_id, $get_current_equipment_location_id, $get_current_equipment_location_title, $get_current_equipment_title, $get_current_equipment_description, $get_current_equipment_sub_description, $get_current_equipment_barcode) = $row;
	
	if($get_current_equipment_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{
	

		if($process == "1"){
			$result = mysqli_query($link, "DELETE FROM $t_office_calendar_equipments WHERE equipment_id=$get_current_equipment_id") or die(mysqli_error($link));

			$url = "index.php?open=$open&page=$page&&order_by=$order_by&order_method=$order_method&editor_language=$editor_language&l=$l&ft=success&fm=deleted";
			header("Location: $url");
			exit;
	
		}
		echo"
		<h1>$get_current_equipment_title</h1>


		<!-- Where am I? -->

			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Office calendar</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Equipments</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;equipment_id=$get_current_equipment_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_equipment_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;equipment_id=$get_current_equipment_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
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
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;equipment_id=$get_current_equipment_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>
		<!-- //Delete form -->

		";
	} // equipment found
} // delete
?>