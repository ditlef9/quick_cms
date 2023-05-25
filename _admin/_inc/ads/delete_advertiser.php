<?php
/**
*
* File: _admin/_inc/ads/delete_advertiser.php
* Version 2
* Copyright (c) 2019-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ----------------------------------------------------------------------- */

/*- Tables ---------------------------------------------------------------------------- */
$t_ads_index		= $mysqlPrefixSav . "ads_index";
$t_ads_advertisers	= $mysqlPrefixSav . "ads_advertisers";



/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['advertiser_id'])) {
	$advertiser_id = $_GET['advertiser_id'];
	$advertiser_id = strip_tags(stripslashes($advertiser_id));
}
else{
	$advertiser_id = "";
}

// Advisor
$stmt = $mysqli->prepare("SELECT advertiser_id, advertiser_name, advertiser_website, advertiser_contact_name, advertiser_contact_email, advertiser_contact_phone FROM $t_ads_advertisers WHERE advertiser_id=?"); 
$stmt->bind_param("s", $inp_user_email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_current_advertiser_id, $get_current_advertiser_name, $get_current_advertiser_website, $get_current_advertiser_contact_name, $get_current_advertiser_contact_email, $get_current_advertiser_contact_phone) = $row;

if($get_current_advertiser_id == ""){
	echo"<p>Advisor not found</p>";
}
else{




	if($process == "1"){
		
		$mysqli->query("DELETE FROM $t_ads_advertisers WHERE advertiser_id=$get_current_advertiser_id") or die($mysqli->error);

		$url = "index.php?open=ads&page=advertisers&editor_language=$editor_language&ft=success&fm=advertiser_deleted";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>Delete advertiser</h1>

	<!-- Where am I ? -->
		<p>
		<b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Ads</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=advertisers&amp;editor_language=$editor_language&amp;l=$l\">Advisors</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=edit_advertiser&amp;advertiser_id=$advertiser_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_advertiser_name</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=delete_advertiser&amp;advertiser_id=$advertiser_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
		</p>
	<!-- Where am I ? -->




	<!-- Delete -->
		<p>Are you sure?</p>
			
		<a href=\"index.php?open=$open&amp;page=$page&amp;advertiser_id=$advertiser_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Delete advertiser</a>
		</p>
	<!-- //Delete -->
	";
} // found

?>