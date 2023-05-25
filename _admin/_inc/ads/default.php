<?php
/**
*
* File: _admin/_inc/ads/default.php
* Version 2
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_ads_index		= $mysqlPrefixSav . "ads_index";
$t_ads_advertisers	= $mysqlPrefixSav . "ads_advertisers";


/*- Check if installed ---------------------------------------------------------------- */
$ads_index_exists = false;
$query = "SHOW TABLES";
$result = $mysqli->query($query);
if($result !== false) {
	if($result->num_rows > 0) {
		while($row = $result->fetch_row()) {
			if($row[0] == "$t_ads_index"){
				$ads_index_exists = true;
				break;
			}
    		}
  	}
}
else echo "Error Unable to check tables " . $mysqli->error;

if(!($ads_index_exists)){
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=ads&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
}


echo"
	<h1>Ads</h1>

	<!-- Backup menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/ads/menu.php");
			echo"
		</ul>
	</div>
	<!-- //Backup menu -->
	";

?>