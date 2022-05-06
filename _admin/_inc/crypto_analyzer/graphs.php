<?php
/**
*
* File: _admin/_inc/crypto_analyzer/graphs.php
* Version 10:19 10.08.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_cran_liquidbase		= $mysqlPrefixSav . "cran_liquidbase";
$t_cran_transactions_index	= $mysqlPrefixSav . "cran_transactions_index";
$t_cran_transactions_inputs	= $mysqlPrefixSav . "cran_transactions_inputs";
$t_cran_transactions_outputs	= $mysqlPrefixSav . "cran_transactions_outputs";
$t_cran_wallets			= $mysqlPrefixSav . "cran_wallets";
$t_cran_blocks			= $mysqlPrefixSav . "cran_blocks";

$t_cran_graphs_elements 	= $mysqlPrefixSav . "cran_graphs_elements";
$t_cran_graphs_index 		= $mysqlPrefixSav . "cran_graphs_index";


/*- Variables ------------------------------------------------------------------------ */


if($action == ""){
	echo"
	<h1>Graphs</h1>
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

	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_graph&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New graph</a>
	</p>


	<!-- Graph list -->
		
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>Date</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>";

		$query = "SELECT graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying FROM $t_cran_graphs_index ORDER BY graph_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_graph_id, $get_graph_title, $get_graph_group_id, $get_graph_created_by_user_id, $get_graph_created_datetime, $get_graph_created_date_saying, $get_graph_updated_by_user_id, $get_graph_updated_datetime, $get_graph_updated_date_saying) = $row;

			echo"
			 <tr>
			  <td>
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_graph&amp;graph_id=$get_graph_id&amp;l=$l&amp;editor_language=$editor_language\">$get_graph_title</a>
				</span>
			  </td>
			  <td>
				<span>$get_graph_updated_date_saying</span>
			  </td>
			  <td>
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_graph&amp;graph_id=$get_graph_id&amp;l=$l&amp;editor_language=$editor_language\">Edit</a>
				&middot;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_graph&amp;graph_id=$get_graph_id&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
				</span>
			  </td>
			 </tr>

			";
		} // while

		echo"
		 </tbody>
		</table>
	<!-- //Graph list -->

	";
} // action == ""
elseif($action == "new_graph"){
	if($process == "1"){
		// Dates
		$datetime = date("Y-m-d H:i:s");
		$date = date("Y-m-d");
		$date_saying = date("j M Y");


		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		// Check duplicates
		$query = "SELECT graph_id FROM $t_cran_graphs_index WHERE graph_title=$inp_title_mysql AND graph_created_by_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_filter_id) = $row;
		if($get_filter_id != ""){

			// Header
			$url = "index.php?open=domains_monitoring&page=filters&action=new_filter&editor_language=$editor_language&l=$l&ft=error&fm=filter_already_exists";
			header("Location: $url");
			exit;
			
		}

		// Group
		$inp_group_id = $_POST['inp_group_id'];
		$inp_group_id = output_html($inp_group_id);
		$inp_group_id_mysql = quote_smart($link, $inp_group_id);


		// Insert
		mysqli_query($link, "INSERT INTO $t_cran_graphs_index 
		(graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, 
		graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_group_id_mysql, $my_user_id_mysql, '$datetime', '$date_saying', $my_user_id_mysql, '$datetime', '$date_saying')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT graph_id FROM $t_cran_graphs_index WHERE graph_title=$inp_title_mysql AND graph_created_by_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_graph_id) = $row;

		// Header
		$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=graph_created#graph$get_current_graph_id";
		header("Location: $url");
		exit;

	} // process
	echo"
	<h1>New graph</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=crypto_analyzer&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Crypto tracker</a>
		&gt;
		<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;editor_language=$editor_language&amp;l=$l\">Graphs</a>
		&gt;
		<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;action=new_graph&amp;editor_language=$editor_language&amp;l=$l\">New graph</a>
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


	<!-- New graph form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=new_graph&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p><b>User group:</b> (<a href=\"index.php?open=users&amp;page=groups&amp;editor_language=$editor_language&amp;l=$l\">Manage groups</a>)<br />
		<select name=\"inp_group_id\">
			<option value=\"0\">-</option>\n";

			// Find my groups


			$query = "SELECT member_id, member_group_id, group_name FROM $t_users_groups_members JOIN $t_users_groups_index ON $t_users_groups_members.member_group_id=$t_users_groups_index.group_id WHERE member_user_id=$get_my_user_id";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_member_id, $get_member_group_id, $get_group_name) = $row;

				echo"			<option value=\"$get_member_group_id\">$get_group_name</option>\n";
			}
			echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Create graph\" class=\"btn_default\" />
		</p>
	
		</form>
	<!-- //New graph form -->
	
	";
} // action == "new graph"
elseif($action == "open_graph"){
	if (isset($_GET['graph_id'])) {
		$graph_id = $_GET['graph_id'];
		$graph_id = stripslashes(strip_tags($graph_id));
		if(!(is_numeric($graph_id))){
			echo"graph id not numeric";
			die;
		}
	}
	else{
		echo"Missing graph id";
		die;
	}
	$graph_id_mysql = quote_smart($link, $graph_id);

	// Get graph
	$query = "SELECT graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying FROM $t_cran_graphs_index WHERE graph_id=$graph_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_graph_id, $get_current_graph_title, $get_current_graph_group_id, $get_current_graph_created_by_user_id, $get_current_graph_created_datetime, $get_current_graph_created_date_saying, $get_current_graph_updated_by_user_id, $get_current_graph_updated_datetime, $get_current_graph_updated_date_saying) = $row;
	if($get_current_graph_id == ""){
		echo"Graph not found";
	}
	else{
		echo"
		<h1>$get_current_graph_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=crypto_analyzer&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Crypto tracker</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Graphs</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=$page&amp;action=open_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_graph_title</a>
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

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=add_element_to_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Add element</a>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=add_connection_between_elements&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Add connection between elements</a>
		<a href=\"_inc/crypto_analyzer/graph/display_graphically.php?graph_id=$get_current_graph_id&amp;l=$l\" class=\"btn_default\">Display graphically</a>
		</p>

		<!-- Element list -->
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>Type</span>
			   </th>
			   <th scope=\"col\">
				<span>Text</span>
			   </th>
			   <th scope=\"col\">
				<span>Actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>";

			$query = "SELECT element_id, element_graph_id, element_type, element_headline, element_text, element_date, element_time, element_datetime_saying, element_position_top, element_position_left, element_path_left, element_path_right, element_connection_top_to_element_ids, element_connection_right_to_element_ids, element_connection_bottom_to_element_ids, element_connection_left_to_element_ids, element_width, element_height, element_border_color, element_background_color, element_text_color, element_arrow_left_type, element_arrow_left_path, element_arrow_left_color, element_arrow_right_type, element_arrow_right_path, element_arrow_right_color, element_added_by_user_id, element_added_datetime, element_updated_by_user_id, element_updated_datetime FROM $t_cran_graphs_elements WHERE element_graph_id=$get_current_graph_id ORDER BY element_position_top ASC LIMIT 0,500";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_element_id, $get_element_graph_id, $get_element_type, $get_element_headline, $get_element_text, $get_element_date, $get_element_time, $get_element_datetime_saying, $get_element_position_top, $get_element_position_left, $get_element_path_left, $get_element_path_right, $get_element_connection_top_to_element_ids, $get_element_connection_right_to_element_ids, $get_element_connection_bottom_to_element_ids, $get_element_connection_left_to_element_ids, $get_element_width, $get_element_height, $get_element_border_color, $get_element_background_color, $get_element_text_color, $get_element_arrow_left_type, $get_element_arrow_left_path, $get_element_arrow_left_color, $get_element_arrow_right_type, $get_element_arrow_right_path, $get_element_arrow_right_color, $get_element_added_by_user_id, $get_element_added_datetime, $get_element_updated_by_user_id, $get_element_updated_datetime) = $row;

				$get_element_type = ucfirst($get_element_type);

				echo"
				 <tr>
				  <td>
					<span>$get_element_type</span>
			 	 </td>
				  <td>
					<span>
					$get_element_text
					</span>
				  </td>
				  <td>
					<span>
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_element&amp;graph_id=$get_current_graph_id&amp;element_id=$get_element_id&amp;l=$l&amp;editor_language=$editor_language\">Edit</a>
					&middot;
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_element&amp;graph_id=$get_current_graph_id&amp;element_id=$get_element_id&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
					</span>
				  </td>
				 </tr>

				";
			} // while
			echo"
			 </tbody>
			</table>
		<!-- //Elements list -->
		";

	} // graph found
} // action == open graph
elseif($action == "edit_graph"){
	if (isset($_GET['graph_id'])) {
		$graph_id = $_GET['graph_id'];
		$graph_id = stripslashes(strip_tags($graph_id));
		if(!(is_numeric($graph_id))){
			echo"graph id not numeric";
			die;
		}
	}
	else{
		echo"Missing graph id";
		die;
	}
	$graph_id_mysql = quote_smart($link, $graph_id);

	// Get graph
	$query = "SELECT graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying FROM $t_cran_graphs_index WHERE graph_id=$graph_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_graph_id, $get_current_graph_title, $get_current_graph_group_id, $get_current_graph_created_by_user_id, $get_current_graph_created_datetime, $get_current_graph_created_date_saying, $get_current_graph_updated_by_user_id, $get_current_graph_updated_datetime, $get_current_graph_updated_date_saying) = $row;
	if($get_current_graph_id == ""){
		echo"Graph not found";
	}
	else{
		if($process == "1"){
			// Dates
			$datetime = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			$date_saying = date("j M Y");


			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);


			// Group
			$inp_group_id = $_POST['inp_group_id'];
			$inp_group_id = output_html($inp_group_id);
			$inp_group_id_mysql = quote_smart($link, $inp_group_id);

			// Update
			mysqli_query($link, "UPDATE $t_cran_graphs_index SET
						graph_title=$inp_title_mysql, 
						graph_group_id=$inp_group_id_mysql, 
						graph_updated_by_user_id=$my_user_id_mysql, 
						graph_updated_datetime='$datetime', 
						graph_updated_date_saying='$date_saying'
						WHERE graph_id=$get_current_graph_id") or die(mysqli_error($link));

			// Header
			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved#graph$get_current_graph_id";
			header("Location: $url");
			exit;

		} // process == 1
		echo"
		<h1>Edit $get_current_graph_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=crypto_analyzer&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Crypto tracker</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Graphs</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=$page&amp;action=edit_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\">Edit $get_current_graph_title</a>
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


		<!-- Edit graph form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_graph_title\" size=\"25\" />
			</p>

			<p><b>User group:</b> (<a href=\"index.php?open=users&amp;page=groups&amp;editor_language=$editor_language&amp;l=$l\">Manage groups</a>)<br />
			<select name=\"inp_group_id\">
				<option value=\"0\">-</option>\n";

				// Find my groups
				$query = "SELECT member_id, member_group_id, group_name FROM $t_users_groups_members JOIN $t_users_groups_index ON $t_users_groups_members.member_group_id=$t_users_groups_index.group_id WHERE member_user_id=$get_my_user_id";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_member_id, $get_member_group_id, $get_group_name) = $row;
					echo"			<option value=\"$get_member_group_id\""; if($get_member_group_id == "$get_current_graph_group_id"){ echo" selected=\"selected\""; } echo">$get_group_name</option>\n";
				}
				echo"
			</select>
			</p>

			<p>
			<input type=\"submit\" value=\"Save graph\" class=\"btn_default\" />
			</p>
	
			</form>
			
		<!-- //Edit graph form -->
		";

	} // graph found
} // action == edit graph
elseif($action == "delete_graph"){
	if (isset($_GET['graph_id'])) {
		$graph_id = $_GET['graph_id'];
		$graph_id = stripslashes(strip_tags($graph_id));
		if(!(is_numeric($graph_id))){
			echo"graph id not numeric";
			die;
		}
	}
	else{
		echo"Missing graph id";
		die;
	}
	$graph_id_mysql = quote_smart($link, $graph_id);

	// Get graph
	$query = "SELECT graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying FROM $t_cran_graphs_index WHERE graph_id=$graph_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_graph_id, $get_current_graph_title, $get_current_graph_group_id, $get_current_graph_created_by_user_id, $get_current_graph_created_datetime, $get_current_graph_created_date_saying, $get_current_graph_updated_by_user_id, $get_current_graph_updated_datetime, $get_current_graph_updated_date_saying) = $row;
	if($get_current_graph_id == ""){
		echo"Graph not found";
	}
	else{
		if($process == "1"){
			
			// Update
			mysqli_query($link, "DELETE FROM $t_cran_graphs_index WHERE graph_id=$get_current_graph_id") or die(mysqli_error($link));
			mysqli_query($link, "DELETE FROM $t_cran_graphs_elements WHERE element_graph_id=$get_current_graph_id") or die(mysqli_error($link));

			// Header
			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=graph_deleted#graph$get_current_graph_id";
			header("Location: $url");
			exit;

		} // process == 1
		echo"
		<h1>Delete $get_current_graph_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=crypto_analyzer&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Crypto tracker</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Graphs</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=$page&amp;action=delete_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\">Delete $get_current_graph_title</a>
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


		<!-- Delete graph form -->
			<p>
			Are you sure you want to delete the graph?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>
			
		<!-- //Delete graph form -->
		";

	} // graph found
} // action == delete graph
elseif($action == "add_element_to_graph"){
	if (isset($_GET['graph_id'])) {
		$graph_id = $_GET['graph_id'];
		$graph_id = stripslashes(strip_tags($graph_id));
		if(!(is_numeric($graph_id))){
			echo"graph id not numeric";
			die;
		}
	}
	else{
		echo"Missing graph id";
		die;
	}
	$graph_id_mysql = quote_smart($link, $graph_id);

	// Get graph
	$query = "SELECT graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying FROM $t_cran_graphs_index WHERE graph_id=$graph_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_graph_id, $get_current_graph_title, $get_current_graph_group_id, $get_current_graph_created_by_user_id, $get_current_graph_created_datetime, $get_current_graph_created_date_saying, $get_current_graph_updated_by_user_id, $get_current_graph_updated_datetime, $get_current_graph_updated_date_saying) = $row;
	if($get_current_graph_id == ""){
		echo"Graph not found";
	}
	else{
		if($process == "1"){
			// Dates
			$datetime = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			$date_saying = date("j M Y");


			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);
			$inp_type_mysql = quote_smart($link, $inp_type);

			$inp_group = "";
			if($inp_type == "text_box"){
				$inp_group = "text_boxes";
			}
			$inp_group_mysql = quote_smart($link, $inp_group);

			$inp_headline = $_POST['inp_headline'];
			$inp_headline = output_html($inp_headline);
			$inp_headline_mysql = quote_smart($link, $inp_headline);

			$inp_text = $_POST['inp_text'];
			$inp_text = output_html($inp_text);
			$inp_text_mysql = quote_smart($link, $inp_text);

			$inp_date = $_POST['inp_date'];
			$inp_date = output_html($inp_date);
			$inp_date_mysql = quote_smart($link, $inp_date);

			$inp_time = $_POST['inp_time'];
			$inp_time = output_html($inp_time);
			$inp_time_mysql = quote_smart($link, $inp_time);

			// Datetime saying
			$date_len = strlen($inp_date);
			$time_len = strlen($inp_time);
			$inp_datetime_saying = "";

			$date_year = "";
			$date_month = "";
			$date_day = "";
			$date_month_saying = "";

			if($date_len == "10"){
				$date_year = substr($date_len, 0, 4);
				$date_month = substr($date_len, 5, 2);
				$date_day = substr($date_len, 8, 2);

				$date_month_saying = "";
				if($date_month == "01"){
					$date_month_saying = "Jan";
				}
				elseif($date_month == "02"){
					$date_month_saying = "Feb";
				}
				elseif($date_month == "03"){
					$date_month_saying = "Mar";
				}
				elseif($date_month == "04"){
					$date_month_saying = "Apr";
				}
				elseif($date_month == "05"){
					$date_month_saying = "May";
				}
				elseif($date_month == "06"){
					$date_month_saying = "Jun";
				}
				elseif($date_month == "07"){
					$date_month_saying = "Jul";
				}
				elseif($date_month == "08"){
					$date_month_saying = "Aug";
				}
				elseif($date_month == "09"){
					$date_month_saying = "Sep";
				}
				elseif($date_month == "10"){
					$date_month_saying = "Oct";
				}
				elseif($date_month == "11"){
					$date_month_saying = "Nov";
				}
				elseif($date_month == "12"){
					$date_month_saying = "Dec";
				}


				$inp_datetime_saying = "$date_day $date_month_saying $date_year";
			}
			if($time_len == "5"){
				if($inp_datetime_saying == ""){
					$inp_datetime_saying = "$time";
				}
				else{
					$inp_datetime_saying = "$inp_datetime_saying $time";
				}
			}
			$inp_datetime_saying_mysql = quote_smart($link, $inp_datetime_saying);

			$inp_position_top = $_POST['inp_position_top'];
			$inp_position_top = output_html($inp_position_top);
			if($inp_position_top == ""){
				$inp_position_top = "0";
			}
			$inp_position_top_mysql = quote_smart($link, $inp_position_top);

			$inp_position_left = $_POST['inp_position_left'];
			$inp_position_left = output_html($inp_position_left);
			if($inp_position_left == ""){
				$inp_position_left = "0";
			}
			$inp_position_left_mysql = quote_smart($link, $inp_position_left);

			$inp_path_left = $_POST['inp_path_left'];
			$inp_path_left = output_html($inp_path_left);
			$inp_path_left_mysql = quote_smart($link, $inp_path_left);

			$inp_path_right = $_POST['inp_path_right'];
			$inp_path_right = output_html($inp_path_right);
			$inp_path_right_mysql = quote_smart($link, $inp_path_right);

			$inp_connection_top_to_element_ids = $_POST['inp_connection_top_to_element_ids'];
			$inp_connection_top_to_element_ids = output_html($inp_connection_top_to_element_ids);
			$inp_connection_top_to_element_ids_mysql = quote_smart($link, $inp_connection_top_to_element_ids);

			$inp_connection_right_to_element_ids = $_POST['inp_connection_right_to_element_ids'];
			$inp_connection_right_to_element_ids = output_html($inp_connection_right_to_element_ids);
			$inp_connection_right_to_element_ids_mysql = quote_smart($link, $inp_connection_right_to_element_ids);

			$inp_connection_bottom_to_element_ids = $_POST['inp_connection_bottom_to_element_ids'];
			$inp_connection_bottom_to_element_ids = output_html($inp_connection_bottom_to_element_ids);
			$inp_connection_bottom_to_element_ids_mysql = quote_smart($link, $inp_connection_bottom_to_element_ids);

			$inp_connection_left_to_element_ids = $_POST['inp_connection_left_to_element_ids'];
			$inp_connection_left_to_element_ids = output_html($inp_connection_left_to_element_ids);
			$inp_connection_left_to_element_ids_mysql = quote_smart($link, $inp_connection_left_to_element_ids);

			$inp_width = $_POST['inp_width'];
			$inp_width = output_html($inp_width);
			if($inp_width == ""){
				$inp_width = "0";
			}
			$inp_width_mysql = quote_smart($link, $inp_width);

			$inp_height = $_POST['inp_height'];
			$inp_height = output_html($inp_height);
			if($inp_height == ""){
				$inp_height = "0";
			}
			$inp_height_mysql = quote_smart($link, $inp_height);

			$inp_border_color = $_POST['inp_border_color'];
			$inp_border_color = output_html($inp_border_color);
			$inp_border_color_mysql = quote_smart($link, $inp_border_color);

			$inp_background_color = $_POST['inp_background_color'];
			$inp_background_color = output_html($inp_background_color);
			$inp_background_color_mysql = quote_smart($link, $inp_background_color);

			$inp_text_color = $_POST['inp_text_color'];
			$inp_text_color = output_html($inp_text_color);
			$inp_text_color_mysql = quote_smart($link, $inp_text_color);

			$inp_arrow_left_type = $_POST['inp_arrow_left_type'];
			$inp_arrow_left_type = output_html($inp_arrow_left_type);
			$inp_arrow_left_type_mysql = quote_smart($link, $inp_arrow_left_type);

			$inp_arrow_left_path = $_POST['inp_arrow_left_path'];
			$inp_arrow_left_path = output_html($inp_arrow_left_path);
			$inp_arrow_left_path_mysql = quote_smart($link, $inp_arrow_left_path);

			$inp_arrow_left_color = $_POST['inp_arrow_left_color'];
			$inp_arrow_left_color = output_html($inp_arrow_left_color);
			$inp_arrow_left_color_mysql = quote_smart($link, $inp_arrow_left_color);


			$inp_arrow_right_type = $_POST['inp_arrow_right_type'];
			$inp_arrow_right_type = output_html($inp_arrow_right_type);
			$inp_arrow_right_type_mysql = quote_smart($link, $inp_arrow_right_type);

			$inp_arrow_right_path = $_POST['inp_arrow_right_path'];
			$inp_arrow_right_path = output_html($inp_arrow_right_path);
			$inp_arrow_right_path_mysql = quote_smart($link, $inp_arrow_right_path);

			$inp_arrow_right_color = $_POST['inp_arrow_right_color'];
			$inp_arrow_right_color = output_html($inp_arrow_right_color);
			$inp_arrow_right_color_mysql = quote_smart($link, $inp_arrow_right_color);

			// Insert
			mysqli_query($link, "INSERT INTO $t_cran_graphs_elements 
			(element_id, element_graph_id, element_group, element_type, element_headline, element_text, 
			element_date, element_time, element_datetime_saying, element_position_top, element_position_left, 
			element_path_left, element_path_right, element_connection_top_to_element_ids, element_connection_right_to_element_ids, element_connection_bottom_to_element_ids, 
			element_connection_left_to_element_ids, element_width, element_height, element_border_color, element_background_color, 
			element_text_color, element_arrow_left_type, element_arrow_left_path, element_arrow_left_color, element_arrow_right_type, 
			element_arrow_right_path, element_arrow_right_color, element_added_by_user_id, element_added_datetime, element_updated_by_user_id, 
			element_updated_datetime) 
			VALUES 
			(NULL, $get_current_graph_id, $inp_group_mysql, $inp_type_mysql, $inp_headline_mysql, $inp_text_mysql, 
			$inp_date_mysql, $inp_time_mysql, $inp_datetime_saying_mysql, $inp_position_top_mysql, $inp_position_left_mysql, 
			$inp_path_left_mysql, $inp_path_right_mysql, $inp_connection_top_to_element_ids_mysql, $inp_connection_right_to_element_ids_mysql, $inp_connection_bottom_to_element_ids_mysql, 
			$inp_connection_left_to_element_ids_mysql, $inp_width_mysql, $inp_height_mysql, $inp_border_color_mysql, $inp_background_color_mysql, 
			$inp_text_color_mysql, $inp_arrow_left_type_mysql, $inp_arrow_left_path_mysql, $inp_arrow_left_color_mysql, $inp_arrow_right_type_mysql, 
			$inp_arrow_right_path_mysql, $inp_arrow_right_color_mysql, $my_user_id_mysql, '$datetime', $my_user_id_mysql, 
			'$datetime')")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT element_id FROM $t_cran_graphs_elements WHERE element_added_by_user_id=$my_user_id_mysql AND element_added_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_element_id) = $row;

			// Header
			$url = "index.php?open=$open&page=$page&action=open_graph&graph_id=$get_current_graph_id&editor_language=$editor_language&l=$l&ft=success&fm=element_created#element$get_current_element_id";
			header("Location: $url");
			exit;

		} // process
		echo"
		<h1>New element</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=crypto_analyzer&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Crypto tracker</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;editor_language=$editor_language&amp;l=$l\">Graphs</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;action=open_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_graph_title</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;action=add_element_to_graph&amp;graph_id=$get_current_graph_id&amp;&amp;editor_language=$editor_language&amp;l=$l\">New element</a>
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


		<!-- New element form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=add_element_to_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Type:</b><br />
			<select name=\"inp_type\">
				<option value=\"text_box\">Text box</option>
			</select>
			</p>

			<div class=\"element_headline\">
				<p><b>Headline:</b><br />
				<input type=\"text\" name=\"inp_headline\" value=\"\" size=\"25\" />
				</p>
			</div>

			<div class=\"element_text\">
				<p><b>Text:</b><br />
				<input type=\"text\" name=\"inp_text\" value=\"\" size=\"25\" />
				</p>
			</div>

			<div class=\"element_date\">
				<p><b>Date:</b><br />
				<input type=\"date\" name=\"inp_date\" value=\"\" size=\"25\" />
				</p>
			</div>

			<div class=\"element_time\">
				<p><b>Time:</b><br />
				<input type=\"time\" name=\"inp_time\" value=\"\" size=\"25\" />
				</p>
			</div>

			<div class=\"element_position\">
				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Position on board:</b></p>
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Top:<br />
					<input type=\"text\" name=\"inp_position_top\" value=\"0\" size=\"5\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Left:<br />
					<input type=\"text\" name=\"inp_position_left\" value=\"0\" size=\"5\" />
					</p>
				  </td>
				  <td>
					<!-- Example -->
						<div style=\"border: #000000 1px solid;padding: 10px 0px 0px 5px;margin: 0;\">
							<p>10,5</p>
						</div>
					<!-- //Example -->
				  </td>
				 </tr>
				</table>
			</div>


			<div class=\"element_path\">
				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Path:</b></p>
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Left:<br />
					<input type=\"text\" name=\"inp_path_left\" value=\"\" size=\"5\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Right:<br />
					<input type=\"text\" name=\"inp_path_right\" value=\"\" size=\"5\" />
					</p>
				  </td>
				  <td>
					<!-- Example -->
						
					<!-- //Example -->
				  </td>
				 </tr>
				</table>
			</div>


			<div class=\"element_connections\">
				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Connections to element ids:</b></p>
				<span>Seperated by comma</span>
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Top:<br />
					<input type=\"text\" name=\"inp_connection_top_to_element_ids\" value=\"\" size=\"10\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Right:<br />
					<input type=\"text\" name=\"inp_connection_right_to_element_ids\" value=\"\" size=\"10\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Bottom:<br />
					<input type=\"text\" name=\"inp_connection_bottom_to_element_ids\" value=\"\" size=\"10\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Left:<br />
					<input type=\"text\" name=\"inp_connection_left_to_element_ids\" value=\"\" size=\"10\" />
					</p>
				  </td>
				 </tr>
				</table>
			</div>

			<div class=\"element_dimensions\">
				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Dimensions:</b></p>
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Width:<br />
					<input type=\"text\" name=\"inp_width\" value=\"150\" size=\"5\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Height:<br />
					<input type=\"text\" name=\"inp_height\" value=\"100\" size=\"5\" />
					</p>
				  </td>
				 </tr>
				</table>
			</div>

			<div class=\"element_colors\">
				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Colors:</b></p>
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Border:<br />
					<input type=\"text\" name=\"inp_border_color\" value=\"#000000\" size=\"8\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Background:<br />
					<input type=\"text\" name=\"inp_background_color\" value=\"#ffffff\" size=\"8\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Text:<br />
					<input type=\"text\" name=\"inp_text_color\" value=\"#000000\" size=\"8\" />
					</p>
				  </td>
				 </tr>
				</table>
			</div>

			<div class=\"element_arrow_left\">
				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Arrow left:</b></p>
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Type:<br />
					<select name=\"inp_arrow_left_type\">
						<option value=\"\">-</option>
						<option value=\"arrow\">Arrow</option>
					</select>
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Path:<br />
					<input type=\"text\" name=\"inp_arrow_left_path\" value=\"\" size=\"8\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Color:<br />
					<input type=\"text\" name=\"inp_arrow_left_color\" value=\"\" size=\"8\" />
					</p>
				  </td>
				 </tr>
				</table>
			</div>

			<div class=\"element_arrow_right\">
				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Arrow right:</b></p>
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Type:<br />
					<select name=\"inp_arrow_right_type\">
						<option value=\"\">-</option>
						<option value=\"arrow\">Arrow</option>
					</select>
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Path:<br />
					<input type=\"text\" name=\"inp_arrow_right_path\" value=\"\" size=\"8\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Color:<br />
					<input type=\"text\" name=\"inp_arrow_right_color\" value=\"\" size=\"8\" />
					</p>
				  </td>
				 </tr>
				</table>
			</div>
			<p>
			<input type=\"submit\" value=\"Create element\" class=\"btn_default\" />
			</p>
	
			</form>
		<!-- //New element form -->
	
		";
	} // element
} // action == "add_element_to_graph"
elseif($action == "edit_element"){
	if (isset($_GET['graph_id'])) {
		$graph_id = $_GET['graph_id'];
		$graph_id = stripslashes(strip_tags($graph_id));
		if(!(is_numeric($graph_id))){
			echo"graph id not numeric";
			die;
		}
	}
	else{
		echo"Missing graph id";
		die;
	}
	$graph_id_mysql = quote_smart($link, $graph_id);
	if (isset($_GET['element_id'])) {
		$element_id = $_GET['element_id'];
		$element_id = stripslashes(strip_tags($element_id));
		if(!(is_numeric($element_id))){
			echo"element id not numeric";
			die;
		}
	}
	else{
		echo"Missing element id";
		die;
	}
	$element_id_mysql = quote_smart($link, $element_id);

	// Get graph
	$query = "SELECT graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying FROM $t_cran_graphs_index WHERE graph_id=$graph_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_graph_id, $get_current_graph_title, $get_current_graph_group_id, $get_current_graph_created_by_user_id, $get_current_graph_created_datetime, $get_current_graph_created_date_saying, $get_current_graph_updated_by_user_id, $get_current_graph_updated_datetime, $get_current_graph_updated_date_saying) = $row;
	if($get_current_graph_id == ""){
		echo"Graph not found";
	}
	else{

		// Get element
		$query = "SELECT element_id, element_graph_id, element_type, element_headline, element_text, element_date, element_time, element_datetime_saying, element_position_top, element_position_left, element_path_left, element_path_right, element_connection_top_to_element_ids, element_connection_right_to_element_ids, element_connection_bottom_to_element_ids, element_connection_left_to_element_ids, element_width, element_height, element_border_color, element_background_color, element_text_color, element_arrow_left_type, element_arrow_left_path, element_arrow_left_color, element_arrow_right_type, element_arrow_right_path, element_arrow_right_color, element_added_by_user_id, element_added_datetime, element_updated_by_user_id, element_updated_datetime FROM $t_cran_graphs_elements WHERE element_id=$element_id_mysql AND element_graph_id=$get_current_graph_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_element_id, $get_current_element_graph_id, $get_current_element_type, $get_current_element_headline, $get_current_element_text, $get_current_element_date, $get_current_element_time, $get_current_element_datetime_saying, $get_current_element_position_top, $get_current_element_position_left, $get_current_element_path_left, $get_current_element_path_right, $get_current_element_connection_top_to_element_ids, $get_current_element_connection_right_to_element_ids, $get_current_element_connection_bottom_to_element_ids, $get_current_element_connection_left_to_element_ids, $get_current_element_width, $get_current_element_height, $get_current_element_border_color, $get_current_element_background_color, $get_current_element_text_color, $get_current_element_arrow_left_type, $get_current_element_arrow_left_path, $get_current_element_arrow_left_color, $get_current_element_arrow_right_type, $get_current_element_arrow_right_path, $get_current_element_arrow_right_color, $get_current_element_added_by_user_id, $get_current_element_added_datetime, $get_current_element_updated_by_user_id, $get_current_element_updated_datetime) = $row;
		if($get_current_element_id == ""){
			echo"Element not found";
		}
		else{
			if($process == "1"){
				// Dates
				$datetime = date("Y-m-d H:i:s");
				$date = date("Y-m-d");
				$date_saying = date("j M Y");


				$inp_type = $_POST['inp_type'];
				$inp_type = output_html($inp_type);
				$inp_type_mysql = quote_smart($link, $inp_type);

				$inp_group = "";
				if($inp_type == "text_box"){
					$inp_group = "text_boxes";
				}
				$inp_group_mysql = quote_smart($link, $inp_group);

				$inp_headline = $_POST['inp_headline'];
				$inp_headline = output_html($inp_headline);
				$inp_headline_mysql = quote_smart($link, $inp_headline);

				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);

				$inp_date = $_POST['inp_date'];
				$inp_date = output_html($inp_date);
				$inp_date_mysql = quote_smart($link, $inp_date);

				$inp_time = $_POST['inp_time'];
				$inp_time = output_html($inp_time);
				$inp_time_mysql = quote_smart($link, $inp_time);

				// Datetime saying
				$date_len = strlen($inp_date);
				$time_len = strlen($inp_time);
				$inp_datetime_saying = "";

				$date_year = "";
				$date_month = "";
				$date_day = "";
				$date_month_saying = "";

				if($date_len == "10"){
					$date_year = substr($date_len, 0, 4);
					$date_month = substr($date_len, 5, 2);
					$date_day = substr($date_len, 8, 2);

					$date_month_saying = "";
					if($date_month == "01"){
						$date_month_saying = "Jan";
					}
					elseif($date_month == "02"){
						$date_month_saying = "Feb";
					}
					elseif($date_month == "03"){
						$date_month_saying = "Mar";
					}
					elseif($date_month == "04"){
						$date_month_saying = "Apr";
					}
					elseif($date_month == "05"){
						$date_month_saying = "May";
					}
					elseif($date_month == "06"){
						$date_month_saying = "Jun";
					}
					elseif($date_month == "07"){
						$date_month_saying = "Jul";
					}
					elseif($date_month == "08"){
						$date_month_saying = "Aug";
					}
					elseif($date_month == "09"){
						$date_month_saying = "Sep";
					}
					elseif($date_month == "10"){
						$date_month_saying = "Oct";
					}
					elseif($date_month == "11"){
						$date_month_saying = "Nov";
					}
					elseif($date_month == "12"){
						$date_month_saying = "Dec";
					}
					$inp_datetime_saying = "$date_day $date_month_saying $date_year";
				}
				if($time_len == "5"){
					if($inp_datetime_saying == ""){
						$inp_datetime_saying = "$time";
					}
					else{
						$inp_datetime_saying = "$inp_datetime_saying $time";
					}
				}
				$inp_datetime_saying_mysql = quote_smart($link, $inp_datetime_saying);

				$inp_position_top = $_POST['inp_position_top'];
				$inp_position_top = output_html($inp_position_top);
				if($inp_position_top == ""){
					$inp_position_top = "0";
				}
				$inp_position_top_mysql = quote_smart($link, $inp_position_top);

				$inp_position_left = $_POST['inp_position_left'];
				$inp_position_left = output_html($inp_position_left);
				if($inp_position_left == ""){
					$inp_position_left = "0";
				}
				$inp_position_left_mysql = quote_smart($link, $inp_position_left);

				$inp_path_left = $_POST['inp_path_left'];
				$inp_path_left = output_html($inp_path_left);
				$inp_path_left_mysql = quote_smart($link, $inp_path_left);

				$inp_path_right = $_POST['inp_path_right'];
				$inp_path_right = output_html($inp_path_right);
				$inp_path_right_mysql = quote_smart($link, $inp_path_right);

				$inp_connection_top_to_element_ids = $_POST['inp_connection_top_to_element_ids'];
				$inp_connection_top_to_element_ids = output_html($inp_connection_top_to_element_ids);
				$inp_connection_top_to_element_ids_mysql = quote_smart($link, $inp_connection_top_to_element_ids);

				$inp_connection_right_to_element_ids = $_POST['inp_connection_right_to_element_ids'];
				$inp_connection_right_to_element_ids = output_html($inp_connection_right_to_element_ids);
				$inp_connection_right_to_element_ids_mysql = quote_smart($link, $inp_connection_right_to_element_ids);

				$inp_connection_bottom_to_element_ids = $_POST['inp_connection_bottom_to_element_ids'];
				$inp_connection_bottom_to_element_ids = output_html($inp_connection_bottom_to_element_ids);
				$inp_connection_bottom_to_element_ids_mysql = quote_smart($link, $inp_connection_bottom_to_element_ids);

				$inp_connection_left_to_element_ids = $_POST['inp_connection_left_to_element_ids'];
				$inp_connection_left_to_element_ids = output_html($inp_connection_left_to_element_ids);
				$inp_connection_left_to_element_ids_mysql = quote_smart($link, $inp_connection_left_to_element_ids);

				$inp_width = $_POST['inp_width'];
				$inp_width = output_html($inp_width);
				if($inp_width == ""){
					$inp_width = "0";
				}
				$inp_width_mysql = quote_smart($link, $inp_width);

				$inp_height = $_POST['inp_height'];
				$inp_height = output_html($inp_height);
				if($inp_height == ""){
					$inp_height = "0";
				}
				$inp_height_mysql = quote_smart($link, $inp_height);

				$inp_border_color = $_POST['inp_border_color'];
				$inp_border_color = output_html($inp_border_color);
				$inp_border_color_mysql = quote_smart($link, $inp_border_color);

				$inp_background_color = $_POST['inp_background_color'];
				$inp_background_color = output_html($inp_background_color);
				$inp_background_color_mysql = quote_smart($link, $inp_background_color);

				$inp_text_color = $_POST['inp_text_color'];
				$inp_text_color = output_html($inp_text_color);
				$inp_text_color_mysql = quote_smart($link, $inp_text_color);

				$inp_arrow_left_type = $_POST['inp_arrow_left_type'];
				$inp_arrow_left_type = output_html($inp_arrow_left_type);
				$inp_arrow_left_type_mysql = quote_smart($link, $inp_arrow_left_type);
	
				$inp_arrow_left_path = $_POST['inp_arrow_left_path'];
				$inp_arrow_left_path = output_html($inp_arrow_left_path);
				$inp_arrow_left_path_mysql = quote_smart($link, $inp_arrow_left_path);

				$inp_arrow_left_color = $_POST['inp_arrow_left_color'];
				$inp_arrow_left_color = output_html($inp_arrow_left_color);
				$inp_arrow_left_color_mysql = quote_smart($link, $inp_arrow_left_color);


				$inp_arrow_right_type = $_POST['inp_arrow_right_type'];
				$inp_arrow_right_type = output_html($inp_arrow_right_type);
				$inp_arrow_right_type_mysql = quote_smart($link, $inp_arrow_right_type);
	
				$inp_arrow_right_path = $_POST['inp_arrow_right_path'];
				$inp_arrow_right_path = output_html($inp_arrow_right_path);
				$inp_arrow_right_path_mysql = quote_smart($link, $inp_arrow_right_path);

				$inp_arrow_right_color = $_POST['inp_arrow_right_color'];
				$inp_arrow_right_color = output_html($inp_arrow_right_color);
				$inp_arrow_right_color_mysql = quote_smart($link, $inp_arrow_right_color);

				// Update
				mysqli_query($link, "UPDATE $t_cran_graphs_elements SET
						element_group=$inp_group_mysql, 
						element_type=$inp_type_mysql, 
						element_headline=$inp_headline_mysql, 
						element_text=$inp_text_mysql, 
						element_date=$inp_date_mysql, 
						element_time=$inp_time_mysql, 
						element_datetime_saying=$inp_datetime_saying_mysql, 
						element_position_top=$inp_position_top_mysql, 
						element_position_left=$inp_position_left_mysql, 
						element_path_left=$inp_path_left_mysql, 
						element_path_right=$inp_path_right_mysql, 
						element_connection_top_to_element_ids=$inp_connection_top_to_element_ids_mysql, 
						element_connection_right_to_element_ids=$inp_connection_right_to_element_ids_mysql,  
						element_connection_bottom_to_element_ids=$inp_connection_bottom_to_element_ids_mysql, 
						element_connection_left_to_element_ids=$inp_connection_left_to_element_ids_mysql, 
						element_width=$inp_width_mysql, 
						element_height=$inp_height_mysql, 
						element_border_color=$inp_border_color_mysql, 
						element_background_color=$inp_background_color_mysql,  
						element_text_color=$inp_text_color_mysql, 
						element_arrow_left_type=$inp_arrow_left_type_mysql, 
						element_arrow_left_path=$inp_arrow_left_path_mysql, 
						element_arrow_left_color=$inp_arrow_left_color_mysql, 
						element_arrow_right_type=$inp_arrow_right_type_mysql, 
						element_arrow_right_path=$inp_arrow_right_path_mysql, 
						element_arrow_right_color=$inp_arrow_right_color_mysql, 
						element_updated_by_user_id=$my_user_id_mysql, 
						element_updated_datetime='$datetime'
						WHERE element_id=$get_current_element_id") or die(mysqli_error($link));

				
				// Header
				$url = "index.php?open=$open&page=$page&action=open_graph&graph_id=$get_current_graph_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved#element$get_current_element_id";
				header("Location: $url");
				exit;

			} // process
			echo"
			<h1>Edit element</h1>

			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=crypto_analyzer&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Crypto tracker</a>
				&gt;
				<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;editor_language=$editor_language&amp;l=$l\">Graphs</a>
				&gt;
				<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;action=open_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_graph_title</a>
				&gt;
				<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;action=edit_element&amp;graph_id=$get_current_graph_id&amp;element_id=$get_current_element_id&amp;editor_language=$editor_language&amp;l=$l\">Edit element</a>
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


			<!-- Edit element form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_element&amp;graph_id=$get_current_graph_id&amp;element_id=$get_current_element_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>Type:</b><br />
				<select name=\"inp_type\">
					<option value=\"text_box\""; if($get_current_element_type == "text_box"){ echo" selected=\"selected\""; } echo">Text box</option>
				</select>
				</p>

				<div class=\"element_headline\">
					<p><b>Headline:</b><br />
					<input type=\"text\" name=\"inp_headline\" value=\"$get_current_element_headline\" size=\"25\" />
					</p>
				</div>

				<div class=\"element_text\">
					<p><b>Text:</b><br />
					<input type=\"text\" name=\"inp_text\" value=\"$get_current_element_text\" size=\"25\" />
					</p>
				</div>

				<div class=\"element_date\">
					<p><b>Date:</b><br />
					<input type=\"date\" name=\"inp_date\" value=\"$get_current_element_date\" size=\"25\" />
					</p>
				</div>

				<div class=\"element_time\">
					<p><b>Time:</b><br />
					<input type=\"time\" name=\"inp_time\" value=\"$get_current_element_time\" size=\"25\" />
					</p>
				</div>

				<div class=\"element_position\">
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Position on board:</b></p>
					<table>
					 <tr>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Top:<br />
						<input type=\"text\" name=\"inp_position_top\" value=\"$get_current_element_position_top\" size=\"5\" />
						</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Left:<br />
						<input type=\"text\" name=\"inp_position_left\" value=\"$get_current_element_position_left\" size=\"5\" />
						</p>
					  </td>
					  <td>
						<!-- Example -->
							<div style=\"border: #000000 1px solid;padding: 10px 0px 0px 5px;margin: 0;\">
								<p>10,5</p>
							</div>
						<!-- //Example -->
					  </td>
					 </tr>
					</table>
				</div>


				<div class=\"element_path\">
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Path:</b></p>
					<table>
					 <tr>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Left:<br />
						<input type=\"text\" name=\"inp_path_left\" value=\"$get_current_element_path_left\" size=\"5\" />
						</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Right:<br />
						<input type=\"text\" name=\"inp_path_right\" value=\"$get_current_element_path_right\" size=\"5\" />
						</p>
					  </td>
					  <td>
						<!-- Example -->
						
						<!-- //Example -->
					  </td>
					 </tr>
					</table>
				</div>


				<div class=\"element_connections\">
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Connections to element ids:</b></p>
					<span>Seperated by comma</span>
					<table>
					 <tr>
					  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Top:<br />
					<input type=\"text\" name=\"inp_connection_top_to_element_ids\" value=\"$get_current_element_connection_top_to_element_ids\" size=\"10\" />
					</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Right:<br />
					<input type=\"text\" name=\"inp_connection_right_to_element_ids\" value=\"$get_current_element_connection_right_to_element_ids\" size=\"10\" />
					</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Bottom:<br />
					<input type=\"text\" name=\"inp_connection_bottom_to_element_ids\" value=\"$get_current_element_connection_bottom_to_element_ids\" size=\"10\" />
					</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Left:<br />
					<input type=\"text\" name=\"inp_connection_left_to_element_ids\" value=\"$get_current_element_connection_left_to_element_ids\" size=\"10\" />
					</p>
					  </td>
					 </tr>
					</table>
				</div>

				<div class=\"element_dimensions\">
				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Dimensions:</b></p>
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Width:<br />
					<input type=\"text\" name=\"inp_width\" value=\"$get_current_element_width\" size=\"5\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Height:<br />
					<input type=\"text\" name=\"inp_height\" value=\"$get_current_element_height\" size=\"5\" />
					</p>
				  </td>
				 </tr>
				</table>
				</div>

				<div class=\"element_colors\">
				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Colors:</b></p>
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Border:<br />
					<input type=\"text\" name=\"inp_border_color\" value=\"$get_current_element_border_color\" size=\"8\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Background:<br />
					<input type=\"text\" name=\"inp_background_color\" value=\"$get_current_element_background_color\" size=\"8\" />
					</p>
				  </td>
				  <td style=\"padding-right: 10px;\">
					<p style=\"padding-top:0;margin-top:0;\">Text:<br />
					<input type=\"text\" name=\"inp_text_color\" value=\"$get_current_element_text_color\" size=\"8\" />
					</p>
				  </td>
				 </tr>
				</table>
				</div>

				<div class=\"element_arrow_left\">
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Arrow left:</b></p>
					<table>
					 <tr>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Type:<br />
						<select name=\"inp_arrow_left_type\">
							<option value=\"\""; if($get_current_element_arrow_left_type == ""){ echo" selected=\"selected\""; } echo">-</option>
							<option value=\"arrow\""; if($get_current_element_arrow_left_type == "arrow"){ echo" selected=\"selected\""; } echo">Arrow</option>
						</select>
						</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Path:<br />
						<input type=\"text\" name=\"inp_arrow_left_path\" value=\"$get_current_element_arrow_left_path\" size=\"8\" />
						</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Color:<br />
						<input type=\"text\" name=\"inp_arrow_left_color\" value=\"$get_current_element_arrow_left_color\" size=\"8\" />
						</p>
					  </td>
					 </tr>
					</table>
				</div>

				<div class=\"element_arrow_right\">
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Arrow right:</b></p>
					<table>
					 <tr>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Type:<br />
						<select name=\"inp_arrow_right_type\">
							<option value=\"\""; if($get_current_element_arrow_right_type == ""){ echo" selected=\"selected\""; } echo">-</option>
							<option value=\"arrow\""; if($get_current_element_arrow_right_type == "arrow"){ echo" selected=\"selected\""; } echo">Arrow</option>
						</select>
						</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Path:<br />
						<input type=\"text\" name=\"inp_arrow_right_path\" value=\"$get_current_element_arrow_right_path\" size=\"8\" />
						</p>
					  </td>
					  <td style=\"padding-right: 10px;\">
						<p style=\"padding-top:0;margin-top:0;\">Color:<br />
						<input type=\"text\" name=\"inp_arrow_right_color\" value=\"$get_current_element_arrow_right_color\" size=\"8\" />
						</p>
					  </td>
					 </tr>
					</table>
				</div>
				<p>
				<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" />
				</p>
	
				</form>
			<!-- //Edit element form -->
	
			";
		} // element found
	} // graph found
} // action == "edit_element"
elseif($action == "delete_element"){
	if (isset($_GET['graph_id'])) {
		$graph_id = $_GET['graph_id'];
		$graph_id = stripslashes(strip_tags($graph_id));
		if(!(is_numeric($graph_id))){
			echo"graph id not numeric";
			die;
		}
	}
	else{
		echo"Missing graph id";
		die;
	}
	$graph_id_mysql = quote_smart($link, $graph_id);
	if (isset($_GET['element_id'])) {
		$element_id = $_GET['element_id'];
		$element_id = stripslashes(strip_tags($element_id));
		if(!(is_numeric($element_id))){
			echo"element id not numeric";
			die;
		}
	}
	else{
		echo"Missing element id";
		die;
	}
	$element_id_mysql = quote_smart($link, $element_id);

	// Get graph
	$query = "SELECT graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying FROM $t_cran_graphs_index WHERE graph_id=$graph_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_graph_id, $get_current_graph_title, $get_current_graph_group_id, $get_current_graph_created_by_user_id, $get_current_graph_created_datetime, $get_current_graph_created_date_saying, $get_current_graph_updated_by_user_id, $get_current_graph_updated_datetime, $get_current_graph_updated_date_saying) = $row;
	if($get_current_graph_id == ""){
		echo"Graph not found";
	}
	else{

		// Get element
		$query = "SELECT element_id, element_graph_id, element_type, element_headline, element_text, element_date, element_time, element_datetime_saying, element_position_top, element_position_left, element_path_left, element_path_right, element_connection_top_to_element_ids, element_connection_right_to_element_ids, element_connection_bottom_to_element_ids, element_connection_left_to_element_ids, element_width, element_height, element_border_color, element_background_color, element_text_color, element_arrow_left_type, element_arrow_left_path, element_arrow_left_color, element_arrow_right_type, element_arrow_right_path, element_arrow_right_color, element_added_by_user_id, element_added_datetime, element_updated_by_user_id, element_updated_datetime FROM $t_cran_graphs_elements WHERE element_id=$element_id_mysql AND element_graph_id=$get_current_graph_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_element_id, $get_current_element_graph_id, $get_current_element_type, $get_current_element_headline, $get_current_element_text, $get_current_element_date, $get_current_element_time, $get_current_element_datetime_saying, $get_current_element_position_top, $get_current_element_position_left, $get_current_element_path_left, $get_current_element_path_right, $get_current_element_connection_top_to_element_ids, $get_current_element_connection_right_to_element_ids, $get_current_element_connection_bottom_to_element_ids, $get_current_element_connection_left_to_element_ids, $get_current_element_width, $get_current_element_height, $get_current_element_border_color, $get_current_element_background_color, $get_current_element_text_color, $get_current_element_arrow_left_type, $get_current_element_arrow_left_path, $get_current_element_arrow_left_color, $get_current_element_arrow_right_type, $get_current_element_arrow_right_path, $get_current_element_arrow_right_color, $get_current_element_added_by_user_id, $get_current_element_added_datetime, $get_current_element_updated_by_user_id, $get_current_element_updated_datetime) = $row;
		if($get_current_element_id == ""){
			echo"Element not found";
		}
		else{
			if($process == "1"){

				// Update
				mysqli_query($link, "DELETE FROM $t_cran_graphs_elements WHERE element_id=$get_current_element_id") or die(mysqli_error($link));

				
				// Header
				$url = "index.php?open=$open&page=$page&action=open_graph&graph_id=$get_current_graph_id&editor_language=$editor_language&l=$l&ft=success&fm=element_deleted#element$get_current_element_id";
				header("Location: $url");
				exit;

			} // process
			echo"
			<h1>Delete element</h1>

			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=crypto_analyzer&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Crypto tracker</a>
				&gt;
				<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;editor_language=$editor_language&amp;l=$l\">Graphs</a>
				&gt;
				<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;action=open_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_graph_title</a>
				&gt;
				<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;action=delete_element&amp;graph_id=$get_current_graph_id&amp;element_id=$get_current_element_id&amp;editor_language=$editor_language&amp;l=$l\">Delete element</a>
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


			<!-- Delete element form -->
				<p>Are you sure you want to delete the element?</p>
			
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_element&amp;graph_id=$get_current_graph_id&amp;element_id=$get_current_element_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Confirm</a>
				</p>
			<!-- //Delete element form -->
	
			";
		} // element found
	} // graph found
} // action == "delete_element"
elseif($action == "add_connection_between_elements"){
	if (isset($_GET['graph_id'])) {
		$graph_id = $_GET['graph_id'];
		$graph_id = stripslashes(strip_tags($graph_id));
		if(!(is_numeric($graph_id))){
			echo"graph id not numeric";
			die;
		}
	}
	else{
		echo"Missing graph id";
		die;
	}
	$graph_id_mysql = quote_smart($link, $graph_id);

	// Get graph
	$query = "SELECT graph_id, graph_title, graph_group_id, graph_created_by_user_id, graph_created_datetime, graph_created_date_saying, graph_updated_by_user_id, graph_updated_datetime, graph_updated_date_saying FROM $t_cran_graphs_index WHERE graph_id=$graph_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_graph_id, $get_current_graph_title, $get_current_graph_group_id, $get_current_graph_created_by_user_id, $get_current_graph_created_datetime, $get_current_graph_created_date_saying, $get_current_graph_updated_by_user_id, $get_current_graph_updated_datetime, $get_current_graph_updated_date_saying) = $row;
	if($get_current_graph_id == ""){
		echo"Graph not found";
	}
	else{
		if($process == "1"){
			// Dates
			$datetime = date("Y-m-d H:i:s");

			// From element
			$inp_from_element_id = $_POST['inp_from_element_id'];
			$inp_from_element_id = output_html($inp_from_element_id);
			$inp_from_element_id_mysql = quote_smart($link, $inp_from_element_id);

			$query = "SELECT element_id, element_graph_id, element_group, element_type, element_headline, element_text, element_date, element_time, element_datetime_saying, element_position_top, element_position_left, element_path_left, element_path_right, element_connection_top_to_element_ids, element_connection_right_to_element_ids, element_connection_bottom_to_element_ids, element_connection_left_to_element_ids, element_width, element_height, element_border_color, element_background_color, element_text_color, element_arrow_left_type, element_arrow_left_path, element_arrow_left_color, element_arrow_right_type, element_arrow_right_path, element_arrow_right_color, element_added_by_user_id, element_added_datetime, element_updated_by_user_id, element_updated_datetime FROM $t_cran_graphs_elements WHERE element_id=$inp_from_element_id_mysql AND element_graph_id=$get_current_graph_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_from_element_id, $get_from_element_graph_id, $get_from_element_group, $get_from_element_type, $get_from_element_headline, $get_from_element_text, $get_from_element_date, $get_from_element_time, $get_from_element_datetime_saying, $get_from_element_position_top, $get_from_element_position_left, $get_from_element_path_left, $get_from_element_path_right, $get_from_element_connection_top_to_element_ids, $get_from_element_connection_right_to_element_ids, $get_from_element_connection_bottom_to_element_ids, $get_from_element_connection_left_to_element_ids, $get_from_element_width, $get_from_element_height, $get_from_element_border_color, $get_from_element_background_color, $get_from_element_text_color, $get_from_element_arrow_left_type, $get_from_element_arrow_left_path, $get_from_element_arrow_left_color, $get_from_element_arrow_right_type, $get_from_element_arrow_right_path, $get_from_element_arrow_right_color, $get_from_element_added_by_user_id, $get_from_element_added_datetime, $get_from_element_updated_by_user_id, $get_from_element_updated_datetime) = $row;
			if($get_from_element_id == ""){
				$url = "index.php?open=crypto_analyzer&amp;page=graphs&action=add_connection_between_elements&graph_id=$get_current_graph_id&editor_language=$editor_language&l=$l&ft=error&fm=element_from_not_found";
				header("Location: $url");
				exit;
			}

			$inp_from_connection = $_POST['inp_from_connection'];
			$inp_from_connection = output_html($inp_from_connection);

			// To element
			$inp_to_element_id = $_POST['inp_to_element_id'];
			$inp_to_element_id = output_html($inp_to_element_id);
			$inp_to_element_id_mysql = quote_smart($link, $inp_to_element_id);

			$query = "SELECT element_id, element_graph_id, element_group, element_type, element_headline, element_text, element_date, element_time, element_datetime_saying, element_position_top, element_position_left, element_path_left, element_path_right, element_connection_top_to_element_ids, element_connection_right_to_element_ids, element_connection_bottom_to_element_ids, element_connection_left_to_element_ids, element_width, element_height, element_border_color, element_background_color, element_text_color, element_arrow_left_type, element_arrow_left_path, element_arrow_left_color, element_arrow_right_type, element_arrow_right_path, element_arrow_right_color, element_added_by_user_id, element_added_datetime, element_updated_by_user_id, element_updated_datetime FROM $t_cran_graphs_elements WHERE element_id=$inp_to_element_id_mysql AND element_graph_id=$get_current_graph_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_to_element_id, $get_to_element_graph_id, $get_to_element_group, $get_to_element_type, $get_to_element_headline, $get_to_element_text, $get_to_element_date, $get_to_element_time, $get_to_element_datetime_saying, $get_to_element_position_top, $get_to_element_position_left, $get_to_element_path_left, $get_to_element_path_right, $get_to_element_connection_top_to_element_ids, $get_to_element_connection_right_to_element_ids, $get_to_element_connection_bottom_to_element_ids, $get_to_element_connection_left_to_element_ids, $get_to_element_width, $get_to_element_height, $get_to_element_border_color, $get_to_element_background_color, $get_to_element_text_color, $get_to_element_arrow_left_type, $get_to_element_arrow_left_path, $get_to_element_arrow_left_color, $get_to_element_arrow_right_type, $get_to_element_arrow_right_path, $get_to_element_arrow_right_color, $get_to_element_added_by_user_id, $get_to_element_added_datetime, $get_to_element_updated_by_user_id, $get_to_element_updated_datetime) = $row;
			if($get_to_element_id == ""){
				$url = "index.php?open=crypto_analyzer&amp;page=graphs&action=add_connection_between_elements&graph_id=$get_current_graph_id&editor_language=$editor_language&l=$l&ft=error&fm=element_to_not_found";
				header("Location: $url");
				exit;
			}

			$inp_to_connection = $_POST['inp_to_connection'];
			$inp_to_connection = output_html($inp_to_connection);


			// Create connection
			
			// Connection position top
			if($inp_from_connection == "top"){
				// Top
				$inp_position_top = $get_from_element_position_top;
				$inp_position_left = round($get_from_element_width/2, 0);
			}
			elseif($inp_from_connection == "right"){
				// Right
				$inp_position_top = $get_from_element_position_top + ($get_to_element_height/2);
				$inp_position_top = round($inp_position_top, 0);
				$inp_position_top = $inp_position_top-10; // We need space for arrow

				$inp_position_left = $get_from_element_position_left + $get_from_element_width;
			}
			elseif($inp_from_connection == "bottom"){
				// Bottom
				$inp_position_top = $get_from_element_position_top + $get_to_element_height;
				$inp_position_left = round($get_from_element_width/2, 0);
			}
			elseif($inp_from_connection == "left"){
				// Left
				$inp_position_top = $get_from_element_position_top + ($get_to_element_height/2);
				$inp_position_top = round($inp_position_top, 0);

				$inp_position_left = $get_from_element_position_left;
			}
			$inp_position_left_mysql = quote_smart($link, $inp_position_left);
			$inp_position_top_mysql = quote_smart($link, $inp_position_top);


			// Connection path
			$inp_path = "";
			$inp_path_mysql = quote_smart($link, $inp_path);

			// Connection x to element ids
			/*
			* From top to bottom
			* |------|
			* | From |
			* |------|
			*    |
			*    v
			* |------|
			* |  To  |
			* |------|
			*
			* From left to right
			* |------|	|------|
			* | From | ->	|  To  | 
			* |------|	|------|
			*
			*/
			$inp_connection_top_to_element_ids = "";
			$inp_connection_right_to_element_ids = "";
			$inp_connection_bottom_to_element_ids = ""; 
			$inp_connection_left_to_element_ids  = "";
			if($inp_from_connection == "top"){
				$inp_connection_top_to_element_ids    = "$get_from_element_id"; 
				$inp_connection_bottom_to_element_ids = "$get_to_element_id"; 
			}
			elseif($inp_from_connection == "right"){
				$inp_connection_right_to_element_ids = "$get_to_element_id"; 
				$inp_connection_left_to_element_ids  = "$get_from_element_id"; 
			}
			elseif($inp_from_connection == "bottom"){
				$inp_connection_top_to_element_ids    = "$get_to_element_id"; 
				$inp_connection_bottom_to_element_ids = "$get_from_element_id"; 
			}
			elseif($inp_from_connection == "left"){
				$inp_connection_right_to_element_ids = "$get_from_element_id"; 
				$inp_connection_left_to_element_ids  = "$get_to_element_id"; 
			}
			$inp_connection_top_to_element_ids_mysql = quote_smart($link, $inp_connection_top_to_element_ids);
			$inp_connection_right_to_element_ids_mysql = quote_smart($link, $inp_connection_right_to_element_ids);
			$inp_connection_bottom_to_element_ids_mysql = quote_smart($link, $inp_connection_bottom_to_element_ids);
			$inp_connection_left_to_element_ids_mysql  = quote_smart($link, $inp_connection_left_to_element_ids);
			
			// Width and height
			$inp_width = 0;
			$inp_height = 0;
			if($inp_from_connection == "top"){
				// Top
				$inp_width = 0;
				$inp_height = $get_from_element_position_top-$get_to_element_position_top;
			}
			elseif($inp_from_connection == "right"){
				// Right
				/*
				* |- From -|	|-- To --|
				* |        |    |        |
				* |        |    |        |
				* |--------|	|--------|
				*
				* From width = $get_from_element_position_left+$get_from_element_width = 150
				* To start left = $get_to_element_position_left = 250
				*/
				$from_width =  $get_from_element_position_left+$get_from_element_width;
				$inp_width = $get_to_element_position_left-$from_width;
				$inp_height = 0;
			}
			elseif($inp_from_connection == "bottom"){
				// Bottom
				$inp_width = 0;
				$inp_height = $get_to_element_position_top-$get_from_element_position_top;
			}
			elseif($inp_from_connection == "left"){
				// Left
				$inp_width = $get_to_element_position_left-$get_from_element_position_right;
				$inp_height = 0;
			}
			$inp_width_mysql = quote_smart($link, $inp_width);
			$inp_height_mysql = quote_smart($link, $inp_height);

			// Colors
			$inp_border_color_mysql = quote_smart($link, "#00000");
			$inp_background_color_mysql = quote_smart($link, "#ffffff");
			$inp_text_color_mysql = quote_smart($link, "#00000");

			// Arrow left
			$inp_element_arrow_left_type = "";
			$inp_arrow_left_path = "";
			$inp_arrow_left_color = "red";
			
			$inp_arrow_left_type_mysql = quote_smart($link, $inp_element_arrow_left_type);
			$inp_arrow_left_path_mysql = quote_smart($link, $inp_arrow_left_path);
			$inp_arrow_left_color_mysql = quote_smart($link, $inp_arrow_left_color);

			// Arrow right
			$inp_element_arrow_right_type = "";
			$inp_arrow_right_path = "";
			$inp_arrow_right_color = "red";
			
			$inp_arrow_right_type_mysql = quote_smart($link, $inp_element_arrow_right_type);
			$inp_arrow_right_path_mysql = quote_smart($link, $inp_arrow_right_path);
			$inp_arrow_right_color_mysql = quote_smart($link, $inp_arrow_right_color);


			mysqli_query($link, "INSERT INTO $t_cran_graphs_elements 
			(element_id, element_graph_id, element_group, element_type, element_headline, 
			element_text, element_date, element_time, element_datetime_saying, element_position_top, 
			element_position_left, element_path, element_path_left, element_path_right, element_connection_top_to_element_ids, 
			element_connection_right_to_element_ids, element_connection_bottom_to_element_ids, element_connection_left_to_element_ids, element_width, element_height, 
			element_thickness, element_border_color, element_background_color, element_text_color, element_arrow_left_type, 
			element_arrow_left_path, element_arrow_left_color, element_arrow_right_type, element_arrow_right_path, element_arrow_right_color, 
			element_added_by_user_id, element_added_datetime, element_updated_by_user_id, element_updated_datetime) 
			VALUES 
			(NULL, $get_current_graph_id, 'connections', 'arrow', '', 
			'', '', '', '', $inp_position_top_mysql, 
			$inp_position_left_mysql, $inp_path_mysql, '', '', $inp_connection_top_to_element_ids_mysql, 

			$inp_connection_right_to_element_ids_mysql, $inp_connection_bottom_to_element_ids_mysql, $inp_connection_left_to_element_ids_mysql, $inp_width_mysql, $inp_height_mysql, 
			
			1, $inp_border_color_mysql, $inp_background_color_mysql, $inp_text_color_mysql, $inp_arrow_left_type_mysql, 
			$inp_arrow_left_path_mysql, $inp_arrow_left_color_mysql, $inp_arrow_right_type_mysql, $inp_arrow_right_path_mysql, $inp_arrow_right_color_mysql, 
			$my_user_id_mysql, '$datetime', $my_user_id_mysql, '$datetime'
			)")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT element_id FROM $t_cran_graphs_elements WHERE element_added_by_user_id=$my_user_id_mysql AND element_added_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_element_id) = $row;
			
		}
		echo"
		<h1>Add connection between elements</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=crypto_analyzer&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Crypto tracker</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;editor_language=$editor_language&amp;l=$l\">Graphs</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;action=open_graph&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_graph_title</a>
			&gt;
			<a href=\"index.php?open=crypto_analyzer&amp;page=graphs&amp;action=add_connection_between_elements&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;l=$l\">Add connections between elements</a>
			</p>
		<!-- //Where am I? -->


		<!-- Add connection form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=add_connection_between_elements&amp;graph_id=$get_current_graph_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>From element:</b><br />
				<select name=\"inp_from_element_id\">";
				$query = "SELECT element_id, element_type, element_headline, element_text FROM $t_cran_graphs_elements WHERE element_graph_id=$get_current_graph_id ORDER BY element_position_top ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_element_id, $get_element_type, $get_element_headline, $get_element_text) = $row;
					echo"			<option value=\"$get_element_id\">$get_element_id &middot; ";
					if($get_element_headline != ""){
						echo"$get_element_headline";
					}
					else{
						echo"$get_element_text";
					}
					echo"</option>\n";
				}
				echo"
				</select>
				</p>

				<p>Position:<br />
				<input type=\"radio\" name=\"inp_from_connection\" value=\"top\" /> Top &nbsp;
				<input type=\"radio\" name=\"inp_from_connection\" value=\"right\" checked=\"checked\" /> Right &nbsp;
				<input type=\"radio\" name=\"inp_from_connection\" value=\"bottom\" /> Bottom &nbsp;
				<input type=\"radio\" name=\"inp_from_connection\" value=\"left\" /> Left
				</p>


				<p><b>To element:</b><br />
				<select name=\"inp_to_element_id\">";
				$query = "SELECT element_id, element_type, element_headline, element_text FROM $t_cran_graphs_elements WHERE element_graph_id=$get_current_graph_id ORDER BY element_position_top ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_element_id, $get_element_type, $get_element_headline, $get_element_text) = $row;
					echo"			<option value=\"$get_element_id\">$get_element_id &middot; ";
					if($get_element_headline != ""){
						echo"$get_element_headline";
					}
					else{
						echo"$get_element_text";
					}
					echo"</option>\n";
				}
				echo"
				</select>
				</p>

				<p>Position:<br />
				<input type=\"radio\" name=\"inp_to_connection\" value=\"top\" /> Top &nbsp;
				<input type=\"radio\" name=\"inp_to_connection\" value=\"right\" /> Right &nbsp;
				<input type=\"radio\" name=\"inp_to_connection\" value=\"bottom\" /> Bottom &nbsp;
				<input type=\"radio\" name=\"inp_to_connection\" value=\"left\" checked=\"checked\" /> Left
				</p>


				<p>
				<input type=\"submit\" value=\"Create connection\" class=\"btn_default\" />
				</p>
	
				</form>
		<!-- //Add connection form -->
		";
		

	} // graph found
} // add connection between elements
?>