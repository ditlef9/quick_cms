<?php
/**
*
* File: _admin/_inc/ads/advertisers.php
* Version 1.0.0
* Date 20:32 01.05.2019
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
$t_ads_index		= $mysqlPrefixSav . "ads_index";
$t_ads_advertisers	= $mysqlPrefixSav . "ads_advertisers";



if($action == ""){
	echo"
	<h1>Advertiser</h1>

	<!-- Where am I ? -->
		<p>
		<b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Ads</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=advisors&amp;editor_language=$editor_language&amp;l=$l\">Advertiser</a>
		</p>
	<!-- Where am I ? -->


	<!-- Navigation -->
		<p>
		<a href=\"index.php?open=$open&amp;page=new_advertiser&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New advertiser</a>
		</p>
	<!-- //Navigation -->
		
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


	<!-- All advertisers -->
		<div class=\"vertical\">
			<ul>
			";


			$query = "SELECT advertiser_id, advertiser_name FROM $t_ads_advertisers ORDER BY advertiser_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_advertiser_id, $get_advertiser_name) = $row;
				echo"
				<li><a href=\"index.php?open=$open&amp;page=edit_advertiser&amp;advertiser_id=$get_advertiser_id&amp;editor_language=$editor_language&amp;l=$l\">$get_advertiser_name</a></li>
				";
			}


			echo"
			</ul>
		</div>
	<!-- //All advertisers-->
	";
}
?>