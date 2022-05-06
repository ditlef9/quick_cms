<?php
/**
*
* File: _admin/_inc/tasks_subscriptions.php
* Version 1.0.1
* Date 12:54 28.04.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	// This page has special access because it may be that we just want to unsubscribe directly from e-mail inbox



	if(isset($_GET['user_id']) && isset($_GET['unsubscribe_code'])) {

		/*- Functions ------------------------------------------------------------------------ */
		include("../../_functions/output_html.php");
		include("../../_functions/clean.php");
		include("../../_functions/quote_smart.php");



		$user_id = $_GET['user_id'];
		$user_id = strip_tags(stripslashes($user_id));
		if(!(is_numeric($user_id))){
			echo"user_id is not numeric";
			die;
		}

		$unsubscribe_code = $_GET['unsubscribe_code'];
		$unsubscribe_code = strip_tags(stripslashes($unsubscribe_code));
		if(!(is_numeric($unsubscribe_code))){
			echo"unsubscribe_code is not numeric";
			die;
		}

		// Connect to MySQL
		$server_name = $_SERVER['HTTP_HOST'];
		$server_name = clean($server_name);
		$setup_finished_file = "setup_finished_" . $server_name . ".php";
		if(!(file_exists("../../_data/$setup_finished_file"))){
			echo"Could not find finish file";
			die;
		}

		$mysql_config_file = "../../_data/mysql_" . $server_name . ".php";
		if(file_exists($mysql_config_file)){
			include("$mysql_config_file");
			$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
			if (!$link) {
				echo"Could not connect to database";
				die;
			}
		}
		else{
			echo"Could not find db file";
			die;
		}

		// Find
		$user_id_mysql = quote_smart($link, $user_id);
		$unsubscribe_code_mysql = quote_smart($link, $unsubscribe_code);

		$t_tasks_user_subscription_selections	= $mysqlPrefixSav . "tasks_user_subscription_selections";
		$query = "SELECT selection_id, selection_user_id, selection_user_email, selection_subscribe_to_new_tasks, selection_subscribe_to_monthly_newsletter, selection_last_sendt_monthly_newsletter_month, selection_last_sendt_datetime, selection_last_sendt_time FROM $t_tasks_user_subscription_selections WHERE selection_user_id=$user_id_mysql AND selection_unsubscribe_code=$unsubscribe_code_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_selection_id, $get_current_selection_user_id, $get_current_selection_user_email, $get_current_selection_subscribe_to_new_tasks, $get_current_selection_subscribe_to_monthly_newsletter, $get_current_selection_last_sendt_monthly_newsletter_month, $get_current_selection_last_sendt_datetime, $get_current_selection_last_sendt_time) = $row;
		if($get_current_selection_id == ""){
			echo"Server error 404: Could not find selection";
		}
		else{
			echo"Unsubscribed successfully";
			mysqli_query($link, "UPDATE $t_tasks_user_subscription_selections SET
					selection_subscribe_to_new_tasks=0, 
					selection_subscribe_to_monthly_newsletter=0
					WHERE selection_id=$get_current_selection_id")
					or die(mysqli_error($link));
		}
		die;
	}
	else{
		echo"<h1>Server error 403</h1>";
		die;
	}
}


/*- Tables ---------------------------------------------------------------------------- */
$t_tasks_index  			= $mysqlPrefixSav . "tasks_index";
$t_tasks_status_codes  			= $mysqlPrefixSav . "tasks_status_codes";
$t_tasks_projects  			= $mysqlPrefixSav . "tasks_projects";
$t_tasks_projects_parts  		= $mysqlPrefixSav . "tasks_projects_parts";
$t_tasks_systems  			= $mysqlPrefixSav . "tasks_systems";
$t_tasks_systems_parts  		= $mysqlPrefixSav . "tasks_systems_parts";
$t_tasks_read				= $mysqlPrefixSav . "tasks_read";

$t_tasks_user_subscription_selections	= $mysqlPrefixSav . "tasks_user_subscription_selections";


/*- Variables  ---------------------------------------------------- */
$tabindex = 0;


if($action == ""){
	if($process == 1){
		// Me
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

		// Selections

		$inp_subscribe_to_new_tasks = $_POST['inp_subscribe_to_new_tasks'];
		$inp_subscribe_to_new_tasks = output_html($inp_subscribe_to_new_tasks);
		$inp_subscribe_to_new_tasks_mysql = quote_smart($link, $inp_subscribe_to_new_tasks);

		$inp_subscribe_to_monthly_newsletter = $_POST['inp_subscribe_to_monthly_newsletter'];
		$inp_subscribe_to_monthly_newsletter = output_html($inp_subscribe_to_monthly_newsletter);
		$inp_subscribe_to_monthly_newsletter_mysql = quote_smart($link, $inp_subscribe_to_monthly_newsletter);


		// Update
		mysqli_query($link, "UPDATE $t_tasks_user_subscription_selections SET
					selection_user_email=$inp_my_user_email_mysql, 
					selection_subscribe_to_new_tasks=$inp_subscribe_to_new_tasks_mysql, 
					selection_subscribe_to_monthly_newsletter=$inp_subscribe_to_monthly_newsletter_mysql
					WHERE selection_user_id=$get_my_user_id")
					or die(mysqli_error($link));
			

		header("Location: index.php?open=dashboard&page=$page&ft=success&fm=changes_saved");
		exit;
	}

	// Get subscription status
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$query = "SELECT selection_id, selection_user_id, selection_user_email, selection_subscribe_to_new_tasks, selection_subscribe_to_monthly_newsletter, selection_last_sendt_monthly_newsletter_month, selection_last_sendt_datetime, selection_last_sendt_time FROM $t_tasks_user_subscription_selections WHERE selection_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_selection_id, $get_current_selection_user_id, $get_current_selection_user_email, $get_current_selection_subscribe_to_new_tasks, $get_current_selection_subscribe_to_monthly_newsletter, $get_current_selection_last_sendt_monthly_newsletter_month, $get_current_selection_last_sendt_datetime, $get_current_selection_last_sendt_time) = $row;
	if($get_current_selection_id == ""){
		// Me
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

		// Insert
		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
		$time = time();
		mysqli_query($link, "INSERT INTO $t_tasks_user_subscription_selections 
		(selection_id, selection_user_id, selection_user_email, selection_unsubscribe_code) 
		VALUES 
		(NULL, $get_my_user_id, $inp_my_user_email_mysql, '$time')")
		or die(mysqli_error($link));
	}

	echo"
	<h1>Tasks projects</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Subscriptions</a>
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

	<!-- Subscription form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_subscribe_to_new_tasks\"]').focus();
		});
		</script>
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p>Subscribe to new tasks:<br />
		<input type=\"radio\" name=\"inp_subscribe_to_new_tasks\" value=\"1\""; if($get_current_selection_subscribe_to_new_tasks == "1"){ echo" checked=\"checked\""; } echo"  /> Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_subscribe_to_new_tasks\" value=\"0\""; if($get_current_selection_subscribe_to_new_tasks == "0"){ echo" checked=\"checked\""; } echo"  /> No
		</p>

		<p>Subscribe to monthly newsletter:<br />
		<input type=\"radio\" name=\"inp_subscribe_to_monthly_newsletter\" value=\"1\""; if($get_current_selection_subscribe_to_monthly_newsletter == "1"){ echo" checked=\"checked\""; } echo"  /> Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_subscribe_to_monthly_newsletter\" value=\"0\""; if($get_current_selection_subscribe_to_monthly_newsletter == "0"){ echo" checked=\"checked\""; } echo"  /> No
		</p>


		<p><input type=\"submit\" value=\"Create project\" class=\"btn_default\" /></p>

		</form>
	<!-- //Subscription form -->
	";
}
?>