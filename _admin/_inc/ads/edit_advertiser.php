<?php
/**
*
* File: _admin/_inc/ads/edit_advertiser.php
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
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);

		$inp_website = $_POST['inp_website'];
		$inp_website = output_html($inp_website);

		$inp_contact_name = $_POST['inp_contact_name'];
		$inp_contact_name = output_html($inp_contact_name);

		$inp_contact_email = $_POST['inp_contact_email'];
		$inp_contact_email = output_html($inp_contact_email);

		$inp_contact_phone = $_POST['inp_contact_phone'];
		$inp_contact_phone = output_html($inp_contact_phone);

		$stmt = $mysqli->prepare("UPDATE $t_ads_advertisers SET '
			advertiser_name=?, 
			advertiser_website=?, 
			advertiser_contact_name=?, 
			advertiser_contact_email=?, 
			advertiser_contact_phone=?
			WHERE advertiser_id=?");
		$stmt->bind_param("ssssss", $inp_name, $inp_website, $inp_contact_name, $inp_contact_email, $inp_contact_phone, $get_current_advertiser_id); 
		$stmt->execute();
		if ($stmt->errno) {
			echo "Error MySQLi update: " . $stmt->error; die;
		}


		$url = "index.php?open=ads&page=$page&advertiser_id=$advertiser_id&editor_language=$editor_language&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>Edit advertiser</h1>

	<!-- Where am I ? -->
		<p>
		<b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Ads</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=advertisers&amp;editor_language=$editor_language&amp;l=$l\">Advisors</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=edit_advertiser&amp;advertiser_id=$advertiser_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_advertiser_name</a>
		</p>
	<!-- Where am I ? -->



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



	<!-- Form -->
		<script>
		window.onload = function() {
			document.getElementById(\"inp_name\").focus();
		}
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;advertiser_id=$advertiser_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Name:</b><br />
		<input type=\"text\" name=\"inp_name\" id=\"inp_name\" value=\"$get_current_advertiser_name\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Website:</b><br />
		<input type=\"text\" name=\"inp_website\" value=\"$get_current_advertiser_website\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Contact name:</b><br />
		<input type=\"text\" name=\"inp_contact_name\" value=\"$get_current_advertiser_contact_name\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Contact email:</b><br />
		<input type=\"text\" name=\"inp_contact_email\" value=\"$get_current_advertiser_contact_email\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Contact phone:</b><br />
		<input type=\"text\" name=\"inp_contact_phone\" value=\"$get_current_advertiser_contact_phone\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->

	<!-- Delete -->
		<p>
		<a href=\"index.php?open=$open&amp;page=delete_advertiser&amp;advertiser_id=$advertiser_id&amp;editor_language=$editor_language\">Delete advertiser</a>
		</p>
	<!-- //Delete -->
	";
} // found

?>