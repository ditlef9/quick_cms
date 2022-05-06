<?php
/**
*
* File: _admin/_inc/settings/liquidbase.php
* Version 1.0
* Date: 13:37 14.11.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- MySQL Tables -------------------------------------------------- */
$t_admin_liquidbase		  = $mysqlPrefixSav . "admin_liquidbase";

if($action == ""){
	echo"
	<h1>Liquidbase</h1>


	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			elseif($fm == "deleted"){
				$fm = "$l_deleted";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->

	<p>
	<a href=\"_liquidbase/liquidbase.php?refererer_open=settings&amp;refererer_page=liquidbase&amp;l=$l\" class=\"btn_default\">Run</a>
	</p>

	<!-- Liquidbase scripts -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Module</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_name</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_run_date</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>


	";

	$query = "SELECT liquidbase_id, liquidbase_module, liquidbase_name, liquidbase_run_datetime, liquidbase_run_saying FROM $t_admin_liquidbase ORDER BY liquidbase_id DESC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_liquidbase_id, $get_liquidbase_module, $get_liquidbase_name, $get_liquidbase_run_datetime, $get_liquidbase_run_saying) = $row;

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
			<span>$get_liquidbase_module</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_liquidbase_name</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_liquidbase_run_saying</span>
		  </td>
		  <td class=\"$style\">
			<span>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;liquidbase_id=$get_liquidbase_id&amp;editor_language=$editor_language\">$l_delete</a></span>
		  </td>
		 </tr>
		";

	}
	echo"
	
		 </tbody>
		</table>

	<!-- //Liquidbase scripts -->
	";
}
elseif($action == "delete"){
	if(isset($_GET['liquidbase_id'])) {
		$liquidbase_id = $_GET['liquidbase_id'];
		$liquidbase_id  = strip_tags(stripslashes($liquidbase_id));
	}
	else{
		$liquidbase_id = "";
	}
	$liquidbase_id_mysql = quote_smart($link, $liquidbase_id);
	$query = "SELECT liquidbase_id, liquidbase_name, liquidbase_run_datetime FROM $t_admin_liquidbase WHERE liquidbase_id=$liquidbase_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_liquidbase_id, $get_liquidbase_name, $get_liquidbase_run_datetime) = $row;

	if($get_liquidbase_id != ""){
		if($process == "1"){

			mysqli_query($link, "DELETE FROM $t_admin_liquidbase WHERE liquidbase_id=$get_liquidbase_id") or die(mysqli_error($link));

			$url = "index.php?open=$open&page=$page&ft=success&fm=deleted";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>$l_delete_liquidbase $get_liquidbase_name</h1>


		<p>
		$l_are_you_sure_you_want_to_delete_the_liquidbase_from_database_table
		$l_this_will_cause_the_script_to_run_once_more_at_the_next_login
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;liquidbase_id=$get_liquidbase_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">$l_confirm_delete</a>
		</p>
		";
	}
} // delete

?>