<?php
/**
*
* File: _admin/_inc/crypto_analyzer/default.php
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
$t_cran_liquidbase		= $mysqlPrefixSav . "cran_liquidbase";
$query = "SELECT * FROM $t_cran_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){


	echo"
	<h1>Crypto tracker</h1>

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


	<!-- Crypto tracker buttons -->";
		// Navigation
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='crypto_analyzer/index.php'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_navigation_id) = $row;
		if($get_navigation_id == ""){
			echo"
			<p>
			<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=crypto_analyzer&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
			</p>
			";
		}
		echo"
	<!-- //Crypto tracker buttons -->

	<!-- Menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/crypto_analyzer/menu.php");
			echo"
		</ul>
	</div>
	<!-- //Menu -->
	";
}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>