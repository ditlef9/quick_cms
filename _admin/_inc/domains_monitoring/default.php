<?php
/**
*
* File: _admin/_inc/domains_monitoring/default.php
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


/*- Functions ----------------------------------------------------------------------- */


/*- Variables ------------------------------------------------------------------------ */



/*- Check if setup is run ------------------------------------------------------------- */
$t_domains_monitoring_liquidbase		= $mysqlPrefixSav . "domains_monitoring_liquidbase";
$query = "SELECT * FROM $t_domains_monitoring_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){


	echo"
	<h1>Domains monitoring</h1>

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


	<!-- domains_monitoring buttons -->";
		// Navigation
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='domains_monitoring/index.php'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_navigation_id) = $row;
		if($get_navigation_id == ""){
			echo"
			<p>
			<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=cdomains_monitoring&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
			</p>
			";
		}
		echo"
	<!-- //domains_monitoring buttons -->

	<!-- Menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/domains_monitoring/menu.php");
			echo"
		</ul>
	</div>
	<!-- //Menu -->
	";
}
else{
	// Create setup file
	$date = date("Y-m-d");
	$create_file="<?php
// General
\$daysToKeepDomainsSav = \"30\";
\$lastCheckedDeleteRoutineSav = \"$date\";
?>";

	$fh = fopen("_data/domain_monitoring.php", "w+") or die("can not open file");
	fwrite($fh, $create_file);
	fclose($fh);




	// Create tables
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>