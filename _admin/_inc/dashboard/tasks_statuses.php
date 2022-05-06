<?php
/**
*
* File: _admin/_inc/tasks_statuses.php
* Version 1.0.1
* Date 12:54 28.04.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_tasks_index  		= $mysqlPrefixSav . "tasks_index";
$t_tasks_status_codes  		= $mysqlPrefixSav . "tasks_status_codes";
$t_tasks_projects  		= $mysqlPrefixSav . "tasks_projects";
$t_tasks_projects_parts  	= $mysqlPrefixSav . "tasks_projects_parts";
$t_tasks_systems  		= $mysqlPrefixSav . "tasks_systems";
$t_tasks_systems_parts  	= $mysqlPrefixSav . "tasks_systems_parts";



/*- Variables  ---------------------------------------------------- */
if(isset($_GET['status_code_id'])) {
	$status_code_id = $_GET['status_code_id'];
	$status_code_id = strip_tags(stripslashes($status_code_id));
}
else{
	$status_code_id = "";
}


if($action == ""){
	echo"
	<h1>Tasks statuses</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Statuses</a>
		</p>
	<!-- //Where am I? -->


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

	<!-- Menu -->
		<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;l=$l\" class=\"btn_default\">New status code</a></p>
	<!-- Menu -->

	<!-- Status codes -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>ID</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Title</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Color</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Show on board</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Task is assigned</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Actions</b></span>
		   </td>
		  </tr>
		 </thead>
		 <tbody>
			";
			$y=1;
			$query = "SELECT status_code_id, status_code_title, status_code_text_color, status_code_bg_color, status_code_border_color, status_code_weight, status_code_show_on_board, status_code_task_is_assigned, status_code_on_status_close_task, status_code_count_tasks FROM $t_tasks_status_codes ORDER BY status_code_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_status_code_id, $get_status_code_title, $get_status_code_text_color, $get_status_code_bg_color, $get_status_code_border_color, $get_status_code_weight, $get_status_code_show_on_board, $get_status_code_task_is_assigned, $get_status_code_on_status_close_task, $get_status_code_count_tasks) = $row;
				// Style
				if(isset($style) && $style == ""){
					$style = "odd";
				}
				else{
					$style = "";
				}

				// Weight
				if($y != "$get_status_code_weight"){

					$result_update = mysqli_query($link, "UPDATE $t_tasks_status_codes SET
										status_code_weight=$y
										WHERE status_code_id=$get_status_code_id");
				}

				echo"
				 <tr>
				  <td class=\"$style\">
					<span>$get_status_code_id</span>
				  </td>
				  <td class=\"$style\">
					<span>$get_status_code_title</span>
				  </td>
				  <td class=\"$style\">
					<div style=\"float: left;background: $get_status_code_bg_color;border: $get_status_code_border_color 1px solid;padding:4px;margin-right: 6px;\">
						<span style=\"color: $get_status_code_text_color;\">$get_status_code_text_color</span>
					</div>
					<div style=\"float: left;background: $get_status_code_bg_color;border: #eeeeee 1px solid;border-top: $get_status_code_border_color 4px solid;padding:4px;\">
						<span style=\"color: $get_status_code_text_color;\">$get_status_code_text_color</span>
					</div>
				  </td>
				  <td class=\"$style\">
					<span>$get_status_code_show_on_board</span>
				  </td>
				  <td class=\"$style\">
					<span>$get_status_code_task_is_assigned</span>
				  </td>
				  <td class=\"$style\">
					<span>";
					if($y != "1"){
						// Move up
						echo"
						<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_up&amp;status_code_id=$get_status_code_id&amp;l=$l&amp;process=1\">Up</a> |
						";
					}
	
					echo"
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;status_code_id=$get_status_code_id&amp;l=$l\">Edit</a>
					|
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;status_code_id=$get_status_code_id&amp;l=$l\">Delete</a>
					</span>
				  </td>
				 </tr>
				";

				$y++;
			}
			echo"
		 </tbody>
		</table>
	<!-- //Status codes -->
	";
}
elseif($action == "new"){
	if($process == 1){

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_text_color = $_POST['inp_text_color'];
		$inp_text_color = output_html($inp_text_color);
		$inp_text_color_mysql = quote_smart($link, $inp_text_color);

		$inp_bg_color = $_POST['inp_bg_color'];
		$inp_bg_color = output_html($inp_bg_color);
		$inp_bg_color_mysql = quote_smart($link, $inp_bg_color);

		$inp_border_color = $_POST['inp_border_color'];
		$inp_border_color = output_html($inp_border_color);
		$inp_border_color_mysql = quote_smart($link, $inp_border_color);


		$inp_show_on_board = $_POST['inp_show_on_board'];
		$inp_show_on_board = output_html($inp_show_on_board);
		$inp_show_on_board_mysql = quote_smart($link, $inp_show_on_board);

		$inp_task_is_assigned = $_POST['inp_task_is_assigned'];
		$inp_task_is_assigned = output_html($inp_task_is_assigned);
		$inp_task_is_assigned_mysql = quote_smart($link, $inp_task_is_assigned);

		// Insert
		mysqli_query($link, "INSERT INTO $t_tasks_status_codes 
		(status_code_id, status_code_title, status_code_text_color, status_code_bg_color, status_code_border_color, status_code_weight, status_code_show_on_board, status_code_task_is_assigned, status_code_count_tasks) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_text_color_mysql, $inp_bg_color_mysql, $inp_border_color_mysql, 999, $inp_show_on_board_mysql, $inp_task_is_assigned_mysql, 0)")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT status_code_id FROM $t_tasks_status_codes WHERE status_code_title=$inp_title_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_status_code_id) = $row;


		header("Location: index.php?open=dashboard&page=$page&ft=success&fm=created");
		exit;
	}
	$tabindex = 0;
	echo"
	<h1>New project</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Statuses</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l\">New</a>
		</p>
	<!-- //Where am I? -->

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

	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	<!-- //Focus -->


	<!-- New form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p>Color:<br />
		<input type=\"text\" name=\"inp_text_color\" value=\"\" size=\"25\" />
		</p>

		<p>Bg color:<br />
		<input type=\"text\" name=\"inp_bg_color\" value=\"\" size=\"25\" />
		</p>

		<p>Border color:<br />
		<input type=\"text\" name=\"inp_border_color\" value=\"\" size=\"25\" />
		</p>

		<p>Show on board:<br />
		<input type=\"radio\" name=\"inp_show_on_board\" value=\"1\" checked=\"checked\" /> Yes
		<input type=\"radio\" name=\"inp_show_on_board\" value=\"0\" /> No
		</p>

		<p>Task is assigned:<br />
		<span class=\"smal\">The task have to be assigned to a user when it has this status</span>
		<br />
		<input type=\"radio\" name=\"inp_task_is_assigned\" value=\"1\" checked=\"checked\" /> Yes
		<input type=\"radio\" name=\"inp_task_is_assigned\" value=\"0\" /> No
		</p>


		<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" /></p>

		</form>
	<!-- //New form -->

	";
} // new
elseif($action == "edit"){
	// Get ID
	$status_code_id_mysql = quote_smart($link, $status_code_id);
	$query = "SELECT status_code_id, status_code_title, status_code_text_color, status_code_bg_color, status_code_border_color, status_code_weight, status_code_show_on_board, status_code_task_is_assigned, status_code_on_status_close_task, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$status_code_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_status_code_id, $get_current_status_code_title, $get_current_status_code_text_color, $get_current_status_code_bg_color, $get_current_status_code_border_color, $get_current_status_code_weight, $get_current_status_code_show_on_board, $get_current_status_code_task_is_assigned, $get_current_status_code_on_status_close_task, $get_current_status_code_count_tasks) = $row;

	if($get_current_status_code_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		if($process == 1){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_text_color = $_POST['inp_text_color'];
			$inp_text_color = output_html($inp_text_color);
			$inp_text_color_mysql = quote_smart($link, $inp_text_color);

			$inp_bg_color = $_POST['inp_bg_color'];
			$inp_bg_color = output_html($inp_bg_color);
			$inp_bg_color_mysql = quote_smart($link, $inp_bg_color);

			$inp_border_color = $_POST['inp_border_color'];
			$inp_border_color = output_html($inp_border_color);
			$inp_border_color_mysql = quote_smart($link, $inp_border_color);

			$inp_show_on_board = $_POST['inp_show_on_board'];
			$inp_show_on_board = output_html($inp_show_on_board);
			$inp_show_on_board_mysql = quote_smart($link, $inp_show_on_board);

			$inp_task_is_assigned = $_POST['inp_task_is_assigned'];
			$inp_task_is_assigned = output_html($inp_task_is_assigned);
			$inp_task_is_assigned_mysql = quote_smart($link, $inp_task_is_assigned);

			$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET
					status_code_title=$inp_title_mysql, 
					status_code_text_color=$inp_text_color_mysql, 
					status_code_bg_color=$inp_bg_color_mysql, 
					status_code_border_color=$inp_border_color_mysql, 
					status_code_show_on_board=$inp_show_on_board_mysql, 
					status_code_task_is_assigned=$inp_task_is_assigned_mysql
					WHERE status_code_id=$get_current_status_code_id") or die(mysqli_error($link));


			header("Location: index.php?open=dashboard&page=$page&action=$action&status_code_id=$get_current_status_code_id&ft=success&fm=changes_saved");
			exit;
		}
		echo"
		<h1>$get_current_status_code_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Status codes</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;status_code_id=$get_current_status_code_id&amp;l=$l\">$get_current_status_code_title</a>
			</p>
		<!-- //Where am I? -->

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
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->


		<!-- Edit form -->
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;status_code_id=$get_current_status_code_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_status_code_title\" size=\"25\" />
			</p>

			<p>Text color:<br />
			<input type=\"text\" name=\"inp_text_color\" value=\"$get_current_status_code_text_color\" size=\"25\" />
			</p>

			<p>Bg color:<br />
			<input type=\"text\" name=\"inp_bg_color\" value=\"$get_current_status_code_bg_color\" size=\"25\" />
			</p>

			<p>Border color:<br />
			<input type=\"text\" name=\"inp_border_color\" value=\"$get_current_status_code_border_color\" size=\"25\" />
			</p>

			<p>Show on board:<br />
			<input type=\"radio\" name=\"inp_show_on_board\" value=\"1\""; if($get_current_status_code_show_on_board == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_show_on_board\" value=\"0\""; if($get_current_status_code_show_on_board == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>Task is assigned:<br />
			<span class=\"smal\">The task have to be assigned to a user when it has this status</span>
			<br />
			<input type=\"radio\" name=\"inp_task_is_assigned\" value=\"1\""; if($get_current_status_code_task_is_assigned == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_task_is_assigned\" value=\"0\""; if($get_current_status_code_task_is_assigned == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>


			<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" /></p>

			</form>
		<!-- //Edit form -->
		";
	}
} // edit
elseif($action == "delete"){
	// Get ID
	$status_code_id_mysql = quote_smart($link, $status_code_id);
	$query = "SELECT status_code_id, status_code_title, status_code_weight, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$status_code_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_status_code_id, $get_current_status_code_title, $get_current_status_code_weight, $get_current_status_code_count_tasks) = $row;

	if($get_current_status_code_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		if($process == 1){

			
			$result = mysqli_query($link, "DELETE FROM $t_tasks_status_codes WHERE status_code_id=$get_current_status_code_id");


			header("Location: index.php?open=dashboard&page=$page&ft=success&fm=deleted");
			exit;
		}
		echo"
		<h1>$get_current_status_code_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Status codes</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;status_code_id=$get_current_status_code_id&amp;l=$l\">$get_current_status_code_title</a>
			</p>
		<!-- //Where am I? -->

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
		

		<!-- Delete form -->
			<p>
			Are you sure you want to delete?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;status_code_id=$get_current_status_code_id&amp;l=$l&amp;process=1\" class=\"btn_default\" />Confirm</a>
			</p>

		<!-- //Delete form -->
		";
	}
} // delete
elseif($action == "move_up"){
	// Get ID
	$status_code_id_mysql = quote_smart($link, $status_code_id);
	$query = "SELECT status_code_id, status_code_title, status_code_text_color, status_code_bg_color, status_code_border_color, status_code_weight, status_code_show_on_board, status_code_task_is_assigned, status_code_on_status_close_task, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$status_code_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_status_code_id, $get_current_status_code_title, $get_current_status_code_text_color, $get_current_status_code_bg_color, $get_current_status_code_border_color, $get_current_status_code_weight, $get_current_status_code_show_on_board, $get_current_status_code_task_is_assigned, $get_current_status_code_on_status_close_task, $get_current_status_code_count_tasks) = $row;

	if($get_current_status_code_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		$new_weight = $get_current_status_code_weight-1;

		// Find changer
		$query = "SELECT status_code_id, status_code_weight FROM $t_tasks_status_codes WHERE status_code_weight=$new_weight";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_change_status_code_id, $get_change_status_code_weight) = $row;

		// Update me
		$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_weight=$new_weight WHERE status_code_id=$get_current_status_code_id") or die(mysqli_error($link));

		// Update other
		$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_weight=$get_current_status_code_weight WHERE status_code_id=$get_change_status_code_id") or die(mysqli_error($link));
		

		header("Location: index.php?open=dashboard&page=$page&ft=success&fm=updated_weight");
		exit;
	} // found
} // move up
?>