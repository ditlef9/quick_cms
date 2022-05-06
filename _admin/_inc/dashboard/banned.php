<?php
/**
*
* File: _admin/_inc/dashboard_banned_ips.php
* Version 1.0.1
* Date 11:46 28-Jul-18
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_banned_hostnames	= $mysqlPrefixSav . "banned_hostnames";
$t_banned_ips	 	= $mysqlPrefixSav . "banned_ips";
$t_banned_user_agents	= $mysqlPrefixSav . "banned_user_agents";


/*- Check that folders and files exists ------------------------------------------------ */
if(!(is_dir("_inc/dashboard/_banned"))){
	mkdir("_inc/dashboard/_banned");
}
if(!(file_exists("_inc/dashboard/_banned/banned_ips.txt"))){
	$fh = fopen("_inc/dashboard/_banned/banned_ips.txt", "w") or die("can not open file");
	fwrite($fh, "");
	fclose($fh);
}

if(!(file_exists("_inc/dashboard/_banned/banned_hostnames.txt"))){
	$fh = fopen("_inc/dashboard/_banned/banned_hostnames.txt", "w") or die("can not open file");
	fwrite($fh, "");
	fclose($fh);
}
if(!(file_exists("_inc/dashboard/_banned/banned_user_agents.txt"))){
	$fh = fopen("_inc/dashboard/_banned/banned_user_agents.txt", "w") or die("can not open file");
	fwrite($fh, "");
	fclose($fh);
}



/*- Variables -------------------------------------------------------------------------- */
if($process != 1){

	echo"
	<h1>Banned</h1>
	
	<!-- Tabs -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\""; if($action == "" OR $action == "add_new_banned_ip"){ echo" class=\"active\""; } echo">Banned IPs</a></li>
				<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=hostnames&amp;l=$l&amp;editor_language=$editor_language\""; if($action == "hostnames" OR $action == "add_new_banned_hostname"){ echo" class=\"active\""; } echo">Banned hostnames</a></li>
				<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=user_agents&amp;l=$l&amp;editor_language=$editor_language\""; if($action == "user_agents" OR $action == "add_new_banned_user_agent"){ echo" class=\"active\""; } echo">Banned user agents</a></li>
				<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=import_from_file&amp;l=$l&amp;editor_language=$editor_language\""; if($action == "import_from_file"){ echo" class=\"active\""; } echo">Import from file</a></li>
				<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=export_to_file&amp;l=$l&amp;editor_language=$editor_language\""; if($action == "export_to_file"){ echo" class=\"active\""; } echo">Export to file</a></li>
			</ul>
		</div>
		<div class=\"clear\"></div>
	<!-- //Tabs -->

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

	";
}
if($action == ""){
	$my_ip = $_SERVER['REMOTE_ADDR'];
	$my_ip = output_html($my_ip);

	echo"
	<h2>Banned IPs</h2>
	<form method=\"POST\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

	<!-- IPs -->

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=add_new_banned_ip&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn\">Add</a>
		</p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Name</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Reason</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Date</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Action</b></span>
		   </td>
		  </tr>
		 </thead>";
		$query = "SELECT banned_ip_id, banned_ip, banned_ip_datetime, banned_ip_reason FROM $t_banned_ips ORDER BY banned_ip ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_banned_ip_id, $get_banned_ip, $get_banned_ip_datetime, $get_banned_ip_reason) = $row;

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
				<span>$get_banned_ip</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_banned_ip_reason</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_banned_ip_datetime</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_banned_ip&amp;banned_ip_id=$get_banned_ip_id&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
				</span>
			  </td>
			 </tr>";

			}

			echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //IPs -->
	";
}
elseif($action == "add_new_banned_ip"){
	if($process == "1"){
		$inp_ip = $_POST['inp_ip'];
		$inp_ip = trim($inp_ip);
		$inp_ip = output_html($inp_ip);
		$inp_ip_mysql = quote_smart($link, $inp_ip);

		$inp_reason = $_POST['inp_reason'];
		$inp_reason = output_html($inp_reason);
		$inp_reason_mysql = quote_smart($link, $inp_reason);

		$query = "SELECT banned_ip_id FROM $t_banned_ips WHERE banned_ip=$inp_ip_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_banned_ip_id) = $row;
		if($get_banned_ip_id == ""){
			// Insert
			$datetime = date("Y-m-d H:i:s");
			mysqli_query($link, "INSERT INTO $t_banned_ips
			(banned_ip_id, banned_ip, banned_ip_reason, banned_ip_datetime) 
			VALUES 
			(NULL, $inp_ip_mysql, $inp_reason_mysql, '$datetime')")
			or die(mysqli_error($link));
		}

		header("Location: index.php?open=$open&page=$page&action=$action&l=$l&editor_language=$editor_language&ft=success&fm=changes_saved");
		exit;
	}

	$my_ip = $_SERVER['REMOTE_ADDR'];
	$my_ip = output_html($my_ip);

	echo"
	<h2>Banned IPs</h2>

	<p>
	Your IP is $my_ip
	</p>

	<form method=\"POST\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">


	<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_ip\"]').focus();
	});
	</script>
	<!-- //Focus -->


	<p>
	IP:<br />
	<input type=\"text\" name=\"inp_ip\" value=\"\" size=\"40\" />
	</p>


	<p>
	Reason:<br />
	<input type=\"text\" name=\"inp_reason\" value=\"Spam\" size=\"40\" />
	</p>

	<p>
	<input type=\"submit\" value=\"Save\" class=\"btn\" />
	</p>
	";
}
elseif($action == "delete_banned_ip"){
	if(isset($_GET['banned_ip_id'])){
		$banned_ip_id = $_GET['banned_ip_id'];
		$banned_ip_id = strip_tags(stripslashes($banned_ip_id));
		$banned_ip_id_mysql = quote_smart($link, $banned_ip_id);

		
		$query = "SELECT banned_ip_id, banned_ip, banned_ip_datetime, banned_ip_reason FROM $t_banned_ips WHERE banned_ip_id=$banned_ip_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_banned_ip_id, $get_banned_ip, $get_banned_ip_datetime, $get_banned_ip_reason) = $row;

		if($get_banned_ip_id == ""){
			echo"<p>Data not found in database. Already deleted?</p>";
		} // not found in database
		else{
			if($process == "1"){

				$result = mysqli_query($link, "DELETE FROM $t_banned_ips WHERE banned_ip_id=$banned_ip_id_mysql");
				header("Location: index.php?open=$open&page=$page&l=$l&editor_language=$editor_language&ft=success&fm=deleted");
				exit;
			}

			echo"
			<h2>Delete banned IPs</h2>

			<p>
			Are you sure you want to delete the ip <em>$get_banned_ip</em>
			from banned list?
			</p>

			<p>
			<b>IP:</b> $get_banned_ip<br />
			<b>Reason:</b> $get_banned_ip_reason
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;banned_ip_id=$banned_ip_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">Delete</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language#ip$get_banned_ip_id\" class=\"btn\">Go back</a>
			</p>

			";
		} // found
	}
	else{
		echo"<p>Missing variable.</p>";
	} // find banned_ip_id
}
elseif($action == "hostnames"){

	echo"
	<h2>Banned hostnames</h2>

	<!-- Hostnames -->

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=add_new_banned_hostname&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn\">Add</a>
		</p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Name</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Reason</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Date</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Action</b></span>
		   </td>
		  </tr>
		 </thead>";
		$query = "SELECT banned_hostname_id, banned_hostname, banned_hostname_datetime, banned_hostname_reason FROM $t_banned_hostnames ORDER BY banned_hostname ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_banned_hostname_id, $get_banned_hostname, $get_banned_hostname_datetime, $get_banned_hostname_reason) = $row;

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
				<span>$get_banned_hostname</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_banned_hostname_reason</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_banned_hostname_datetime</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_banned_hostname&amp;banned_hostname_id=$get_banned_hostname_id&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
				</span>
			  </td>
			 </tr>";

			}

			echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //Hostnames -->
	";
}
elseif($action == "add_new_banned_hostname"){
	if($process == "1"){
		$inp_hostname = $_POST['inp_hostname'];
		$inp_hostname = trim($inp_hostname);
		$inp_hostname = output_html($inp_hostname);
		$inp_hostname_mysql = quote_smart($link, $inp_hostname);

		$inp_reason = $_POST['inp_reason'];
		$inp_reason = output_html($inp_reason);
		$inp_reason_mysql = quote_smart($link, $inp_reason);

		$query = "SELECT banned_hostname_id FROM $t_banned_hostnames WHERE banned_hostname=$inp_hostname_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_banned_hostname_id) = $row;
		if($get_banned_hostname_id == ""){
			// Insert
			$datetime = date("Y-m-d H:i:s");
			mysqli_query($link, "INSERT INTO $t_banned_hostnames
			(banned_hostname_id, banned_hostname, banned_hostname_reason, banned_hostname_datetime) 
			VALUES 
			(NULL, $inp_hostname_mysql, $inp_reason_mysql, '$datetime')")
			or die(mysqli_error($link));
		}

		header("Location: index.php?open=$open&page=$page&action=$action&l=$l&editor_language=$editor_language&ft=success&fm=changes_saved");
		exit;
	}
	
	$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$my_hostname = output_html($my_hostname);

	echo"
	<h2>Add new banned hostname</h2>

	<p>
	Your hostname is $my_hostname
	</p>

	<form method=\"POST\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


	<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_hostname\"]').focus();
	});
	</script>
	<!-- //Focus -->


	<p>
	Hostname:<br />
	<input type=\"text\" name=\"inp_hostname\" value=\"\" size=\"40\" />
	</p>


	<p>
	Reason:<br />
	<input type=\"text\" name=\"inp_reason\" value=\"Spam\" size=\"40\" />
	</p>

	<p>
	<input type=\"submit\" value=\"Save\" class=\"btn\" />
	</p>
	";
}
elseif($action == "delete_banned_hostname"){
	if(isset($_GET['banned_hostname_id'])){
		$banned_hostname_id = $_GET['banned_hostname_id'];
		$banned_hostname_id = strip_tags(stripslashes($banned_hostname_id));
		$banned_hostname_id_mysql = quote_smart($link, $banned_hostname_id);

		
		$query = "SELECT banned_hostname_id, banned_hostname, banned_hostname_datetime, banned_hostname_reason FROM $t_banned_hostnames WHERE banned_hostname_id=$banned_hostname_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_banned_hostname_id, $get_banned_hostname, $get_banned_hostname_datetime, $get_banned_hostname_reason) = $row;

		if($get_banned_hostname_id == ""){
			echo"<p>Data not found in database. Already deleted?</p>";
		} // not found in database
		else{
			if($process == "1"){

				$result = mysqli_query($link, "DELETE FROM $t_banned_hostnames WHERE banned_hostname_id=$banned_hostname_id_mysql");
				header("Location: index.php?open=$open&page=$page&action=hostnames&l=$l&editor_language=$editor_language&ft=success&fm=deleted");
				exit;
			}

			echo"
			<h2>Delete banned hostnames</h2>

			<p>
			Are you sure you want to delete the hostname <em>$get_banned_hostname</em>
			from banned list?
			</p>

			<p>
			<b>Hostname:</b> $get_banned_hostname<br />
			<b>Reason:</b> $get_banned_hostname_reason
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;banned_hostname_id=$banned_hostname_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">Delete</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=hostnames&amp;l=$l&amp;editor_language=$editor_language#hostname$get_banned_hostname_id\" class=\"btn\">Go back</a>
			</p>

			";
		} // found
	}
	else{
		echo"<p>Missing variable.</p>";
	} // find banned_hostname_id
}
elseif($action == "user_agents"){

	echo"
	<h2>Banned user agents</h2>


	<!-- User agents -->

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=add_new_banned_user_agent&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn\">Add</a>
		</p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Name</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Reason</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Date</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Action</b></span>
		   </td>
		  </tr>
		 </thead>";
		$query = "SELECT banned_user_agent_id, banned_user_agent, banned_user_agent_datetime, banned_user_agent_reason FROM $t_banned_user_agents ORDER BY banned_user_agent ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_banned_user_agent_id, $get_banned_user_agent, $get_banned_user_agent_datetime, $get_banned_user_agent_reason) = $row;

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
				<span>$get_banned_user_agent</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_banned_user_agent_reason</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_banned_user_agent_datetime</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_banned_user_agent&amp;banned_user_agent_id=$get_banned_user_agent_id&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
				</span>
			  </td>
			 </tr>";

			}

			echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //User agent -->
	";
}
elseif($action == "add_new_banned_user_agent"){
	if($process == "1"){
		$inp_user_agent = $_POST['inp_user_agent'];
		$inp_user_agent = trim($inp_user_agent);
		$inp_user_agent = output_html($inp_user_agent);
		$inp_user_agent_mysql = quote_smart($link, $inp_user_agent);

		$inp_reason = $_POST['inp_reason'];
		$inp_reason = output_html($inp_reason);
		$inp_reason_mysql = quote_smart($link, $inp_reason);

		$query = "SELECT banned_user_agent_id FROM $t_banned_user_agents WHERE banned_user_agent=$inp_user_agent_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_banned_user_agent_id) = $row;
		if($get_banned_user_agent_id == ""){
			// Insert
			$datetime = date("Y-m-d H:i:s");
			mysqli_query($link, "INSERT INTO $t_banned_user_agents
			(banned_user_agent_id, banned_user_agent, banned_user_agent_reason, banned_user_agent_datetime) 
			VALUES 
			(NULL, $inp_user_agent_mysql, $inp_reason_mysql, '$datetime')")
			or die(mysqli_error($link));
		}

		header("Location: index.php?open=$open&page=$page&action=$action&l=$l&editor_language=$editor_language&ft=success&fm=changes_saved");
		exit;
	}


	$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
	$my_user_agent = output_html($my_user_agent);

	echo"
	<h2>Add new banned user agent</h2>

	<p>
	Your user agent is $my_user_agent
	</p>

	<form method=\"POST\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


	<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_hostname\"]').focus();
	});
	</script>
	<!-- //Focus -->


	<p>
	User agent:<br />
	<input type=\"text\" name=\"inp_user_agent\" value=\"\" size=\"40\" />
	</p>


	<p>
	Reason:<br />
	<input type=\"text\" name=\"inp_reason\" value=\"Spam\" size=\"40\" />
	</p>

	<p>
	<input type=\"submit\" value=\"Save\" class=\"btn\" />
	</p>
	";
}
elseif($action == "delete_banned_user_agent"){
	if(isset($_GET['banned_user_agent_id'])){
		$banned_user_agent_id = $_GET['banned_user_agent_id'];
		$banned_user_agent_id = strip_tags(stripslashes($banned_user_agent_id));
		$banned_user_agent_id_mysql = quote_smart($link, $banned_user_agent_id);

		
		$query = "SELECT banned_user_agent_id, banned_user_agent, banned_user_agent_datetime, banned_user_agent_reason FROM $t_banned_user_agents WHERE banned_user_agent_id=$banned_user_agent_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_banned_user_agent_id, $get_banned_user_agent, $get_banned_user_agent_datetime, $get_banned_user_agent_reason) = $row;

		if($get_banned_user_agent_id == ""){
			echo"<p>Data not found in database. Already deleted?</p>";
		} // not found in database
		else{
			if($process == "1"){

				$result = mysqli_query($link, "DELETE FROM $t_banned_user_agents WHERE banned_user_agent_id=$banned_user_agent_id_mysql");
				header("Location: index.php?open=$open&page=$page&action=user_agents&l=$l&editor_language=$editor_language&ft=success&fm=deleted");
				exit;
			}

			echo"
			<h2>Delete banned user agent</h2>

			<p>
			Are you sure you want to delete the user agent <em>$get_banned_user_agent</em>
			from banned list?
			</p>

			<p>
			<b>User agent:</b> $get_banned_user_agent<br />
			<b>Reason:</b>  $get_banned_user_agent_reason
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;banned_user_agent_id=$banned_user_agent_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">Delete</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=hostnames&amp;l=$l&amp;editor_language=$editor_language#user_agent$get_banned_user_agent_id\" class=\"btn\">Go back</a>
			</p>

			";
		} // found
	}
	else{
		echo"<p>Missing variable.</p>";
	} // find banned_hostname_id
}
elseif($action == "import_from_file"){
	if($process == "1"){
		$fh = fopen("_inc/dashboard/_banned/banned_hostnames.txt", "r");
		$data = fread($fh, filesize("_inc/dashboard/_banned/banned_hostnames.txt"));
		fclose($fh);

		$array = explode("\n", $data);
		
		$datetime = date("Y-m-d H:i:s");
		for($x=0;$x<sizeof($array);$x++){
			$line = explode("|", $array[$x]);
			
			$content = output_html($line[0]);
			$content_mysql = quote_smart($link, $content);

			$reason = output_html($line[1]);
			$reason_mysql = quote_smart($link, $reason);

			$query = "SELECT banned_hostname_id FROM $t_banned_hostnames WHERE banned_hostname=$content_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_banned_hostname_id) = $row;

			if($get_banned_hostname_id == ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_banned_hostnames
				(banned_hostname_id, banned_hostname, banned_hostname_datetime, banned_hostname_reason) 
				VALUES 
				(NULL, $content_mysql, '$datetime', $reason_mysql)")
				or die(mysqli_error($link));
			}

		}



		$fh = fopen("_inc/dashboard/_banned/banned_ips.txt", "r");
		$data = fread($fh, filesize("_inc/dashboard/_banned/banned_ips.txt"));
		fclose($fh);

		$array = explode("\n", $data);
		
		$datetime = date("Y-m-d H:i:s");
		for($x=0;$x<sizeof($array);$x++){
			$line = explode("|", $array[$x]);
			
			$content = output_html($line[0]);
			$content_mysql = quote_smart($link, $content);

			$reason = output_html($line[1]);
			$reason_mysql = quote_smart($link, $reason);

			$query = "SELECT banned_ip_id FROM $t_banned_ips WHERE banned_ip=$content_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_banned_ip_id) = $row;

			if($get_banned_ip_id == ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_banned_ips
				(banned_ip_id, banned_ip, banned_ip_datetime, banned_ip_reason) 
				VALUES 
				(NULL, $content_mysql, '$datetime', $reason_mysql)")
				or die(mysqli_error($link));
			}
		}





		$fh = fopen("_inc/dashboard/_banned/banned_user_agents.txt", "r");
		$data = fread($fh, filesize("_inc/dashboard/_banned/banned_user_agents.txt"));
		fclose($fh);

		$array = explode("\n", $data);
				
		$datetime = date("Y-m-d H:i:s");
		for($x=0;$x<sizeof($array);$x++){
			$line = explode("|", $array[$x]);
			
			$content = output_html($line[0]);
			$content_mysql = quote_smart($link, $content);

			$reason = output_html($line[1]);
			$reason_mysql = quote_smart($link, $reason);

			$query = "SELECT banned_user_agent_id FROM $t_banned_user_agents WHERE banned_user_agent=$content_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_banned_user_agent_id) = $row;

			if($get_banned_user_agent_id == ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_banned_user_agents
				(banned_user_agent_id, banned_user_agent, banned_user_agent_datetime, banned_user_agent_reason) 
				VALUES 
				(NULL, $content_mysql, '$datetime', $reason_mysql)")
				or die(mysqli_error($link));
			}

		}



		header("Location: index.php?open=$open&page=$page&action=$action&l=$l&editor_language=$editor_language&ft=success&fm=imported_data");
		exit;


	}
	echo"
	<h2>Import from file</h2>

	<p>
	This will read the following files and insert any
	data from it into the MySQL database.
	</p>

	<p>
	<a href=\"_inc/dashboard/_banned/banned_hostnames.txt\">_inc/dashboard/_banned/banned_hostnames.txt</a><br />
	<a href=\"_inc/dashboard/_banned/banned_ips.txt\">_inc/dashboard/_banned/banned_ips.txt</a><br />
	<a href=\"_inc/dashboard/_banned/banned_user_agents.txt\">_inc/dashboard/_banned/banned_user_agents.txt</a>
	</p>
	
	<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">Start import</a></p>
	";	
}
elseif($action == "export_to_file"){
	if($process == "1"){


		// Hostnames
		$x = 0;
		$query = "SELECT banned_hostname, banned_hostname_reason FROM $t_banned_hostnames ORDER BY banned_hostname ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_banned_hostname, $get_banned_hostname_reason) = $row;


			if($x == 0){
				$input = $get_banned_hostname . "|" . $get_banned_hostname_reason;
				$fh = fopen("_inc/dashboard/_banned/banned_hostnames.txt", "w") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);
			}
			else{
				$input = "\n" . $get_banned_hostname . "|" . $get_banned_hostname_reason;
				$fh = fopen("_inc/dashboard/_banned/banned_hostnames.txt", "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);
			}
			$x++;
		}

		// IPs
		$x = 0;
		$query = "SELECT banned_ip, banned_ip_reason FROM $t_banned_ips ORDER BY banned_ip ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_banned_ip, $get_banned_ip_reason) = $row;
			$get_banned_ip = str_replace("\n", "", $get_banned_ip);

			if($x == 0){
				$input = $get_banned_ip . "|" . $get_banned_ip_reason;
				$fh = fopen("_inc/dashboard/_banned/banned_ips.txt", "w") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);
			}
			else{
				$input = "\n" . $get_banned_ip . "|" . $get_banned_ip_reason;
				$fh = fopen("_inc/dashboard/_banned/banned_ips.txt", "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);
			}
			$x++;
		}

		// User agents
		$x = 0;
		$query = "SELECT banned_user_agent, banned_user_agent_reason FROM $t_banned_user_agents ORDER BY banned_user_agent ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_banned_user_agent, $get_banned_user_agent_reason) = $row;

			if($x == 0){
				$input = $get_banned_user_agent . "|" . $get_banned_user_agent_reason;
				$fh = fopen("_inc/dashboard/_banned/banned_user_agents.txt", "w") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);
			}
			else{
				$input = "\n" . $get_banned_user_agent . "|" . $get_banned_user_agent_reason;
				$fh = fopen("_inc/dashboard/_banned/banned_user_agents.txt", "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);
			}
			$x++;
		}


		header("Location: index.php?open=$open&page=$page&action=$action&l=$l&editor_language=$editor_language&ft=success&fm=exported_data");
		exit;


	}
	echo"
	<h2>Export from file</h2>

	<p>
	This will read the data in MySQL database and write it to the corresponding files.
	This makes it easy to share them.
	</p>

	<p>
	$t_banned_hostnames - &gt; <a href=\"_inc/dashboard/_banned/banned_hostnames.txt\">_inc/dashboard/_banned/banned_hostnames.txt</a><br />
	$t_banned_ips - &gt; <a href=\"_inc/dashboard/_banned/banned_ips.txt\">_inc/dashboard/_banned/banned_ips.txt</a><br />
	$t_banned_user_agents -&gt; <a href=\"_inc/dashboard/_banned/banned_user_agents.txt\">_inc/dashboard/_banned/banned_user_agents.txt</a>
	</p>
	
	<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\">Start export</a></p>
	";	
}


?>