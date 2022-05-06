<?php
/**
*
* File: _admin/_inc/settings/owner.php
* Version 19.25 18.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}





if($process == "1"){
	// General
	$inp_owner_name = $_POST['inp_owner_name'];
	$inp_owner_name = output_html($inp_owner_name);

	$inp_owner_mail = $_POST['inp_owner_mail'];
	$inp_owner_mail = output_html($inp_owner_mail);

	$inp_owner_phone = $_POST['inp_owner_phone'];
	$inp_owner_phone = output_html($inp_owner_phone);

	$inp_owner_fax = $_POST['inp_owner_fax'];
	$inp_owner_fax = output_html($inp_owner_fax);


	// Opening hours - Monday
	$inp_owner_opening_hours_mo_from_a = $_POST['inp_owner_opening_hours_mo_from_a'];
	$inp_owner_opening_hours_mo_from_a = output_html($inp_owner_opening_hours_mo_from_a);

	$inp_owner_opening_hours_mo_to_a = $_POST['inp_owner_opening_hours_mo_to_a'];
	$inp_owner_opening_hours_mo_to_a = output_html($inp_owner_opening_hours_mo_to_a);

	$inp_owner_opening_hours_mo_closed = $_POST['inp_owner_opening_hours_mo_closed'];
	$inp_owner_opening_hours_mo_closed = output_html($inp_owner_opening_hours_mo_closed);
	if($inp_owner_opening_hours_mo_closed == "on"){
		$inp_owner_opening_hours_mo_closed = "1";
	}
	else{
		$inp_owner_opening_hours_mo_closed = "0";
	}

	$inp_owner_opening_hours_mo_from_b = $_POST['inp_owner_opening_hours_mo_from_b'];
	$inp_owner_opening_hours_mo_from_b = output_html($inp_owner_opening_hours_mo_from_b);

	$inp_owner_opening_hours_mo_to_b = $_POST['inp_owner_opening_hours_mo_to_b'];
	$inp_owner_opening_hours_mo_to_b = output_html($inp_owner_opening_hours_mo_to_b);


	// Opening hours - Tuesday
	$inp_owner_opening_hours_tu_from_a = $_POST['inp_owner_opening_hours_tu_from_a'];
	$inp_owner_opening_hours_tu_from_a = output_html($inp_owner_opening_hours_tu_from_a);

	$inp_owner_opening_hours_tu_to_a = $_POST['inp_owner_opening_hours_tu_to_a'];
	$inp_owner_opening_hours_tu_to_a = output_html($inp_owner_opening_hours_tu_to_a);

	$inp_owner_opening_hours_tu_closed = $_POST['inp_owner_opening_hours_tu_closed'];
	$inp_owner_opening_hours_tu_closed = output_html($inp_owner_opening_hours_tu_closed);
	if($inp_owner_opening_hours_tu_closed == "on"){
		$inp_owner_opening_hours_tu_closed = "1";
	}
	else{
		$inp_owner_opening_hours_tu_closed = "0";
	}

	$inp_owner_opening_hours_tu_from_b = $_POST['inp_owner_opening_hours_tu_from_b'];
	$inp_owner_opening_hours_tu_from_b = output_html($inp_owner_opening_hours_tu_from_b);

	$inp_owner_opening_hours_tu_to_b = $_POST['inp_owner_opening_hours_tu_to_b'];
	$inp_owner_opening_hours_tu_to_b = output_html($inp_owner_opening_hours_tu_to_b);

	// Opening hours - Wedensday
	$inp_owner_opening_hours_we_from_a = $_POST['inp_owner_opening_hours_we_from_a'];
	$inp_owner_opening_hours_we_from_a = output_html($inp_owner_opening_hours_we_from_a);

	$inp_owner_opening_hours_we_to_a = $_POST['inp_owner_opening_hours_we_to_a'];
	$inp_owner_opening_hours_we_to_a = output_html($inp_owner_opening_hours_we_to_a);

	$inp_owner_opening_hours_we_closed = $_POST['inp_owner_opening_hours_we_closed'];
	$inp_owner_opening_hours_we_closed = output_html($inp_owner_opening_hours_we_closed);
	if($inp_owner_opening_hours_we_closed == "on"){
		$inp_owner_opening_hours_we_closed = "1";
	}
	else{
		$inp_owner_opening_hours_we_closed = "0";
	}

	$inp_owner_opening_hours_we_from_b = $_POST['inp_owner_opening_hours_we_from_b'];
	$inp_owner_opening_hours_we_from_b = output_html($inp_owner_opening_hours_we_from_b);

	$inp_owner_opening_hours_we_to_b = $_POST['inp_owner_opening_hours_we_to_b'];
	$inp_owner_opening_hours_we_to_b = output_html($inp_owner_opening_hours_we_to_b);



	// Opening hours - Thursday
	$inp_owner_opening_hours_th_from_a = $_POST['inp_owner_opening_hours_th_from_a'];
	$inp_owner_opening_hours_th_from_a = output_html($inp_owner_opening_hours_th_from_a);

	$inp_owner_opening_hours_th_to_a = $_POST['inp_owner_opening_hours_th_to_a'];
	$inp_owner_opening_hours_th_to_a = output_html($inp_owner_opening_hours_th_to_a);

	$inp_owner_opening_hours_th_closed = $_POST['inp_owner_opening_hours_th_closed'];
	$inp_owner_opening_hours_th_closed = output_html($inp_owner_opening_hours_th_closed);
	if($inp_owner_opening_hours_th_closed == "on"){
		$inp_owner_opening_hours_th_closed = "1";
	}
	else{
		$inp_owner_opening_hours_th_closed = "0";
	}

	$inp_owner_opening_hours_th_from_b = $_POST['inp_owner_opening_hours_th_from_b'];
	$inp_owner_opening_hours_th_from_b = output_html($inp_owner_opening_hours_th_from_b);

	$inp_owner_opening_hours_th_to_b = $_POST['inp_owner_opening_hours_th_to_b'];
	$inp_owner_opening_hours_th_to_b = output_html($inp_owner_opening_hours_th_to_b);


	// Opening hours - Friday
	$inp_owner_opening_hours_fr_from_a = $_POST['inp_owner_opening_hours_fr_from_a'];
	$inp_owner_opening_hours_fr_from_a = output_html($inp_owner_opening_hours_fr_from_a);

	$inp_owner_opening_hours_fr_to_a = $_POST['inp_owner_opening_hours_fr_to_a'];
	$inp_owner_opening_hours_fr_to_a = output_html($inp_owner_opening_hours_fr_to_a);

	$inp_owner_opening_hours_fr_closed = $_POST['inp_owner_opening_hours_fr_closed'];
	$inp_owner_opening_hours_fr_closed = output_html($inp_owner_opening_hours_fr_closed);
	if($inp_owner_opening_hours_fr_closed == "on"){
		$inp_owner_opening_hours_fr_closed = "1";
	}
	else{
		$inp_owner_opening_hours_fr_closed = "0";
	}

	$inp_owner_opening_hours_fr_from_b = $_POST['inp_owner_opening_hours_fr_from_b'];
	$inp_owner_opening_hours_fr_from_b = output_html($inp_owner_opening_hours_fr_from_b);

	$inp_owner_opening_hours_fr_to_b = $_POST['inp_owner_opening_hours_fr_to_b'];
	$inp_owner_opening_hours_fr_to_b = output_html($inp_owner_opening_hours_fr_to_b);

	// Opening hours - Saturday
	$inp_owner_opening_hours_sa_from_a = $_POST['inp_owner_opening_hours_sa_from_a'];
	$inp_owner_opening_hours_sa_from_a = output_html($inp_owner_opening_hours_sa_from_a);

	$inp_owner_opening_hours_sa_to_a = $_POST['inp_owner_opening_hours_sa_to_a'];
	$inp_owner_opening_hours_sa_to_a = output_html($inp_owner_opening_hours_sa_to_a);

	$inp_owner_opening_hours_sa_closed = $_POST['inp_owner_opening_hours_sa_closed'];
	$inp_owner_opening_hours_sa_closed = output_html($inp_owner_opening_hours_sa_closed);
	if($inp_owner_opening_hours_sa_closed == "on"){
		$inp_owner_opening_hours_sa_closed = "1";
	}
	else{
		$inp_owner_opening_hours_sa_closed = "0";
	}

	$inp_owner_opening_hours_sa_from_b = $_POST['inp_owner_opening_hours_sa_from_b'];
	$inp_owner_opening_hours_sa_from_b = output_html($inp_owner_opening_hours_sa_from_b);

	$inp_owner_opening_hours_sa_to_b = $_POST['inp_owner_opening_hours_sa_to_b'];
	$inp_owner_opening_hours_sa_to_b = output_html($inp_owner_opening_hours_sa_to_b);


	// Opening hours - Sunday
	$inp_owner_opening_hours_su_from_a = $_POST['inp_owner_opening_hours_su_from_a'];
	$inp_owner_opening_hours_su_from_a = output_html($inp_owner_opening_hours_su_from_a);

	$inp_owner_opening_hours_su_to_a = $_POST['inp_owner_opening_hours_su_to_a'];
	$inp_owner_opening_hours_su_to_a = output_html($inp_owner_opening_hours_su_to_a);

	$inp_owner_opening_hours_su_closed = $_POST['inp_owner_opening_hours_su_closed'];
	$inp_owner_opening_hours_su_closed = output_html($inp_owner_opening_hours_su_closed);
	if($inp_owner_opening_hours_su_closed == "on"){
		$inp_owner_opening_hours_su_closed = "1";
	}
	else{
		$inp_owner_opening_hours_su_closed = "0";
	}

	$inp_owner_opening_hours_su_from_b = $_POST['inp_owner_opening_hours_su_from_b'];
	$inp_owner_opening_hours_su_from_b = output_html($inp_owner_opening_hours_su_from_b);

	$inp_owner_opening_hours_su_to_b = $_POST['inp_owner_opening_hours_su_to_b'];
	$inp_owner_opening_hours_su_to_b = output_html($inp_owner_opening_hours_su_to_b);






	$inp_owner_opening_two_time_intervals = $_POST['inp_owner_opening_two_time_intervals'];
	$inp_owner_opening_two_time_intervals = output_html($inp_owner_opening_two_time_intervals);
	if($inp_owner_opening_two_time_intervals == "on"){
		$inp_owner_opening_two_time_intervals = "1";
	}
	else{
		$inp_owner_opening_two_time_intervals = "0";
	}

	// Visit address
	$inp_owner_visit_address_a = $_POST['inp_owner_visit_address_a'];
	$inp_owner_visit_address_a = output_html($inp_owner_visit_address_a);

	$inp_owner_visit_address_b = $_POST['inp_owner_visit_address_b'];
	$inp_owner_visit_address_b = output_html($inp_owner_visit_address_b);

	$inp_owner_visit_address_zip = $_POST['inp_owner_visit_address_zip'];
	$inp_owner_visit_address_zip = output_html($inp_owner_visit_address_zip);

	$inp_owner_visit_address_city = $_POST['inp_owner_visit_address_city'];
	$inp_owner_visit_address_city = output_html($inp_owner_visit_address_city);

	$inp_owner_visit_address_country = $_POST['inp_owner_visit_address_country'];
	$inp_owner_visit_address_country = output_html($inp_owner_visit_address_country);

	$inp_owner_visit_address_route_description = $_POST['inp_owner_visit_address_route_description'];
	$inp_owner_visit_address_route_description = output_html($inp_owner_visit_address_route_description);

	// Post address
	$inp_owner_post_address_a = $_POST['inp_owner_post_address_a'];
	$inp_owner_post_address_a = output_html($inp_owner_post_address_a);

	$inp_owner_post_address_b = $_POST['inp_owner_post_address_b'];
	$inp_owner_post_address_b = output_html($inp_owner_post_address_b);

	$inp_owner_post_address_zip = $_POST['inp_owner_post_address_zip'];
	$inp_owner_post_address_zip = output_html($inp_owner_post_address_zip);

	$inp_owner_post_address_city = $_POST['inp_owner_post_address_city'];
	$inp_owner_post_address_city = output_html($inp_owner_post_address_city);

	$inp_owner_post_address_country = $_POST['inp_owner_post_address_country'];
	$inp_owner_post_address_country = output_html($inp_owner_post_address_country);

	$update_file="<?php
// General
\$configOwnerNameSav   	= \"$inp_owner_name\";
\$configOwnerMailSav   	= \"$inp_owner_mail\";
\$configOwnerPhoneSav  	= \"$inp_owner_phone\";
\$configOwnerFaxSav   	= \"$inp_owner_fax\";

// Opening hours
\$configOwnerOpeningHoursMoFromASav	= \"$inp_owner_opening_hours_mo_from_a\";
\$configOwnerOpeningHoursMoToASav	= \"$inp_owner_opening_hours_mo_to_a\";
\$configOwnerOpeningHoursMoFromBSav	= \"$inp_owner_opening_hours_mo_from_b\";
\$configOwnerOpeningHoursMoToBSav	= \"$inp_owner_opening_hours_mo_to_b\";
\$configOwnerOpeningHoursMoClosedSav	= \"$inp_owner_opening_hours_mo_closed\";

\$configOwnerOpeningHoursTuFromASav	= \"$inp_owner_opening_hours_tu_from_a\";
\$configOwnerOpeningHoursTuToASav	= \"$inp_owner_opening_hours_tu_to_a\";
\$configOwnerOpeningHoursTuFromBSav	= \"$inp_owner_opening_hours_tu_from_b\";
\$configOwnerOpeningHoursTuToBSav	= \"$inp_owner_opening_hours_tu_to_b\";
\$configOwnerOpeningHoursTuClosedSav	= \"$inp_owner_opening_hours_tu_closed\";

\$configOwnerOpeningHoursWeFromASav	= \"$inp_owner_opening_hours_we_from_a\";
\$configOwnerOpeningHoursWeToASav	= \"$inp_owner_opening_hours_we_to_a\";
\$configOwnerOpeningHoursWeFromBSav	= \"$inp_owner_opening_hours_we_from_b\";
\$configOwnerOpeningHoursWeToBSav	= \"$inp_owner_opening_hours_we_to_b\";
\$configOwnerOpeningHoursWeClosedSav	= \"$inp_owner_opening_hours_we_closed\";

\$configOwnerOpeningHoursThFromASav	= \"$inp_owner_opening_hours_th_from_a\";
\$configOwnerOpeningHoursThToASav	= \"$inp_owner_opening_hours_th_to_a\";
\$configOwnerOpeningHoursThFromBSav	= \"$inp_owner_opening_hours_th_from_b\";
\$configOwnerOpeningHoursThToBSav	= \"$inp_owner_opening_hours_th_to_b\";
\$configOwnerOpeningHoursThClosedSav	= \"$inp_owner_opening_hours_th_closed\";

\$configOwnerOpeningHoursFrFromASav	= \"$inp_owner_opening_hours_fr_from_a\";
\$configOwnerOpeningHoursFrToASav	= \"$inp_owner_opening_hours_fr_to_a\";
\$configOwnerOpeningHoursFrFromBSav	= \"$inp_owner_opening_hours_fr_from_b\";
\$configOwnerOpeningHoursFrToBSav	= \"$inp_owner_opening_hours_fr_to_b\";
\$configOwnerOpeningHoursFrClosedSav	= \"$inp_owner_opening_hours_fr_closed\";

\$configOwnerOpeningHoursSaFromASav	= \"$inp_owner_opening_hours_sa_from_a\";
\$configOwnerOpeningHoursSaToASav	= \"$inp_owner_opening_hours_sa_to_a\";
\$configOwnerOpeningHoursSaFromBSav	= \"$inp_owner_opening_hours_sa_from_b\";
\$configOwnerOpeningHoursSaToBSav	= \"$inp_owner_opening_hours_sa_to_b\";
\$configOwnerOpeningHoursSaClosedSav	= \"$inp_owner_opening_hours_sa_closed\";

\$configOwnerOpeningHoursSuFromASav	= \"$inp_owner_opening_hours_su_from_a\";
\$configOwnerOpeningHoursSuToASav	= \"$inp_owner_opening_hours_su_to_a\";
\$configOwnerOpeningHoursSuFromBSav	= \"$inp_owner_opening_hours_su_from_b\";
\$configOwnerOpeningHoursSuToBSav	= \"$inp_owner_opening_hours_su_to_b\";
\$configOwnerOpeningHoursSuClosedSav	= \"$inp_owner_opening_hours_su_closed\";

\$configOwnerOpeningTwoTimeIntervalsSav = \"$inp_owner_opening_two_time_intervals\";

// Visit address
\$configOwnerVisitAddressASav		= \"$inp_owner_visit_address_a\";
\$configOwnerVisitAddressBSav		= \"$inp_owner_visit_address_b\";
\$configOwnerVisitAddressZipSav		= \"$inp_owner_visit_address_zip\";
\$configOwnerVisitAddressCitySav	= \"$inp_owner_visit_address_city\";
\$configOwnerVisitAddressCountrySav	= \"$inp_owner_visit_address_country\";
\$configOwnerVisitAddressRouteDescriptionSav	= \"$inp_owner_visit_address_route_description\";

// Post address
\$configOwnerPostAddressASav		= \"$inp_owner_post_address_a\";
\$configOwnerPostAddressBSav		= \"$inp_owner_post_address_b\";
\$configOwnerPostAddressZipSav		= \"$inp_owner_post_address_zip\";
\$configOwnerPostAddressCitySav		= \"$inp_owner_post_address_city\";
\$configOwnerPostAddressCountrySav	= \"$inp_owner_post_address_country\";
?>";

	$fh = fopen("_data/config/owner.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);

	header("Location: ?open=settings&page=owner&focus=inp_owner_name&ft=success&fm=changes_saved");
	exit;
}

$tabindex = 0;
echo"
<h1>$l_owner</h1>
<form method=\"post\" action=\"?open=settings&amp;page=owner&amp;process=1\" enctype=\"multipart/form-data\">
				
	
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

<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_owner_name\"]').focus();
	});
	</script>
<!-- //Focus -->
<h2>$l_general</h2>
<table>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_name:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_name\" value=\"$configOwnerNameSav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_email_address:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_mail\" value=\"$configOwnerMailSav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_phone:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_phone\" value=\"$configOwnerPhoneSav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_fax:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_fax\" value=\"$configOwnerFaxSav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
</table>


<h2>$l_opening_hours</h2>
<table>

 <tr>
  <td style=\"text-align:right;padding-right: 4px;vertical-align:top\">
	<p>$l_monday:</p>
  </td>
  <td>
	<table>
	 <tr>
 	  <td style=\"";if($configOwnerOpeningHoursMoClosedSav != "1"){echo"padding-right:4px;";}echo"vertical-align:top\">
		<div id=\"toggle_monday_closed\"";if($configOwnerOpeningHoursMoClosedSav == "1"){echo" style=\"display:none;\"";}echo">
		<p>
		<select name=\"inp_owner_opening_hours_mo_from_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursMoFromASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_mo_to_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursMoToASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		<div id=\"toggle_monday\"";if($configOwnerOpeningTwoTimeIntervalsSav == "0"){echo" style='display:none;'";}echo">
		
		<p>
		<select name=\"inp_owner_opening_hours_mo_from_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursMoFromBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_mo_to_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
				$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursMoToBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		</div>
		</div>
	  </td>
 	  <td style=\"vertical-align:top\">
		<p>
		<input onclick=\"toggle_visibility('toggle_monday_closed');\" type=\"checkbox\" name=\"inp_owner_opening_hours_mo_closed\" ";if($configOwnerOpeningHoursMoClosedSav == "1"){ echo" checked=\"checked\"";}echo"/> 
		$l_closed
		</p>
	  </td>
	 </tr>
	</table>
  </td>
 </tr>

 <tr>
  <td style=\"text-align:right;padding-right: 4px;vertical-align:top\">
	<p>$l_tuesday:</p>
  </td>
  <td>
	<table>
	 <tr>
 	  <td style=\"";if($configOwnerOpeningHoursTuClosedSav != "1"){echo"padding-right:4px;";}echo"vertical-align:top\">
		<div id=\"toggle_tuesday_closed\"";if($configOwnerOpeningHoursTuClosedSav == "1"){echo" style=\"display:none;\"";}echo">
		<p>
		<select name=\"inp_owner_opening_hours_tu_from_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursTuFromASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_tu_to_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursTuToASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		<div id=\"toggle_tuesday\"";if($configOwnerOpeningTwoTimeIntervalsSav == "0"){echo" style='display:none;'";}echo">
		
		<p>
		<select name=\"inp_owner_opening_hours_tu_from_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursTuFromBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_tu_to_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
				$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursTuToBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		</div>
		</div>
	  </td>
 	  <td style=\"vertical-align:top\">
		<p>
		<input onclick=\"toggle_visibility('toggle_tuesday_closed');\" type=\"checkbox\" name=\"inp_owner_opening_hours_tu_closed\" ";if($configOwnerOpeningHoursTuClosedSav == "1"){ echo" checked=\"checked\"";}echo"/> 
		$l_closed
		</p>
	  </td>
	 </tr>
	</table>
  </td>
 </tr>


 <tr>
  <td style=\"text-align:right;padding-right: 4px;vertical-align:top\">
	<p>$l_wednesday:</p>
  </td>
  <td>
	<table>
	 <tr>
 	  <td style=\"";if($configOwnerOpeningHoursWeClosedSav != "1"){echo"padding-right:4px;";}echo"vertical-align:top\">
		<div id=\"toggle_wednesday_closed\"";if($configOwnerOpeningHoursWeClosedSav == "1"){echo" style=\"display:none;\"";}echo">
		<p>
		<select name=\"inp_owner_opening_hours_we_from_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursWeFromASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_we_to_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursWeToASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		<div id=\"toggle_wednesday\"";if($configOwnerOpeningTwoTimeIntervalsSav == "0"){echo" style='display:none;'";}echo">
		
		<p>
		<select name=\"inp_owner_opening_hours_we_from_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursWeFromBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_we_to_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
				$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursWeToBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		</div>
		</div>
	  </td>
 	  <td style=\"vertical-align:top\">
		<p>
		<input onclick=\"toggle_visibility('toggle_wednesday_closed');\" type=\"checkbox\" name=\"inp_owner_opening_hours_we_closed\" ";if($configOwnerOpeningHoursWeClosedSav == "1"){ echo" checked=\"checked\"";}echo"/> 
		$l_closed
		</p>
	  </td>
	 </tr>
	</table>
  </td>
 </tr>


 <tr>
  <td style=\"text-align:right;padding-right: 4px;vertical-align:top\">
	<p>$l_thursday:</p>
  </td>
  <td>
	<table>
	 <tr>
 	  <td style=\"";if($configOwnerOpeningHoursThClosedSav != "1"){echo"padding-right:4px;";}echo"vertical-align:top\">
		<div id=\"toggle_thursday_closed\"";if($configOwnerOpeningHoursThClosedSav == "1"){echo" style=\"display:none;\"";}echo">
		<p>
		<select name=\"inp_owner_opening_hours_th_from_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursThFromASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_th_to_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursThToASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		<div id=\"toggle_thursday\"";if($configOwnerOpeningTwoTimeIntervalsSav == "0"){echo" style='display:none;'";}echo">
		
		<p>
		<select name=\"inp_owner_opening_hours_th_from_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursThFromBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_th_to_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
				$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursThToBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		</div>
		</div>
	  </td>
 	  <td style=\"vertical-align:top\">
		<p>
		<input onclick=\"toggle_visibility('toggle_thursday_closed');\" type=\"checkbox\" name=\"inp_owner_opening_hours_th_closed\" ";if($configOwnerOpeningHoursThClosedSav == "1"){ echo" checked=\"checked\"";}echo"/> 
		$l_closed
		</p>
	  </td>
	 </tr>
	</table>
  </td>
 </tr>


 <tr>
  <td style=\"text-align:right;padding-right: 4px;vertical-align:top\">
	<p>$l_friday:</p>
  </td>
  <td>
	<table>
	 <tr>
 	  <td style=\"";if($configOwnerOpeningHoursFrClosedSav != "1"){echo"padding-right:4px;";}echo"vertical-align:top\">
		<div id=\"toggle_friday_closed\"";if($configOwnerOpeningHoursFrClosedSav == "1"){echo" style=\"display:none;\"";}echo">
		<p>
		<select name=\"inp_owner_opening_hours_fr_from_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursFrFromASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_fr_to_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursFrToASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		<div id=\"toggle_friday\"";if($configOwnerOpeningTwoTimeIntervalsSav == "0"){echo" style='display:none;'";}echo">
		
		<p>
		<select name=\"inp_owner_opening_hours_fr_from_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursFrFromBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_fr_to_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
				$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursFrToBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		</div>
		</div>
	  </td>
 	  <td style=\"vertical-align:top\">
		<p>
		<input onclick=\"toggle_visibility('toggle_friday_closed');\" type=\"checkbox\" name=\"inp_owner_opening_hours_fr_closed\" ";if($configOwnerOpeningHoursFrClosedSav == "1"){ echo" checked=\"checked\"";}echo"/> 
		$l_closed
		</p>
	  </td>
	 </tr>
	</table>
  </td>
 </tr>


 <tr>
  <td style=\"text-align:right;padding-right: 4px;vertical-align:top\">
	<p>$l_saturday:</p>
  </td>
  <td>
	<table>
	 <tr>
 	  <td style=\"";if($configOwnerOpeningHoursSaClosedSav != "1"){echo"padding-right:4px;";}echo"vertical-align:top\">
		<div id=\"toggle_saturday_closed\"";if($configOwnerOpeningHoursSaClosedSav == "1"){echo" style=\"display:none;\"";}echo">
		<p>
		<select name=\"inp_owner_opening_hours_sa_from_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursSaFromASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_sa_to_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursSaToASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		<div id=\"toggle_saturday\"";if($configOwnerOpeningTwoTimeIntervalsSav == "0"){echo" style='display:none;'";}echo">
		
		<p>
		<select name=\"inp_owner_opening_hours_sa_from_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursSaFromBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_sa_to_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
				$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursSaToBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		</div>
		</div>
	  </td>
 	  <td style=\"vertical-align:top\">
		<p>
		<input onclick=\"toggle_visibility('toggle_saturday_closed');\" type=\"checkbox\" name=\"inp_owner_opening_hours_sa_closed\" ";if($configOwnerOpeningHoursSaClosedSav == "1"){ echo" checked=\"checked\"";}echo"/> 
		$l_closed
		</p>
	  </td>
	 </tr>
	</table>
  </td>
 </tr>


 <tr>
  <td style=\"text-align:right;padding-right: 4px;vertical-align:top\">
	<p>$l_sunday:</p>
  </td>
  <td>
	<table>
	 <tr>
 	  <td style=\"";if($configOwnerOpeningHoursSuClosedSav != "1"){echo"padding-right:4px;";}echo"vertical-align:top\">
		<div id=\"toggle_sunday_closed\"";if($configOwnerOpeningHoursSuClosedSav == "1"){echo" style=\"display:none;\"";}echo">
		<p>
		<select name=\"inp_owner_opening_hours_su_from_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursSuFromASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_su_to_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursSuToASav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		<div id=\"toggle_sunday\"";if($configOwnerOpeningTwoTimeIntervalsSav == "0"){echo" style='display:none;'";}echo">
		
		<p>
		<select name=\"inp_owner_opening_hours_su_from_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
					$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursSuFromBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		-
		<select name=\"inp_owner_opening_hours_su_to_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		for($h=0;$h<24;$h++){
			for($m=0;$m<60;$m++){
				if($m < "10"){
				$m = "0" . $m;
				}
				echo"			<option value=\"$h:$m\""; if($configOwnerOpeningHoursSuToBSav == "$h:$m"){ echo" selected=\"selected\"";}echo">$h:$m</option>\n";
				$m = $m+14;
			}
		}
		echo"
		</select>
		</p>
		</div>
		</div>
	  </td>
 	  <td style=\"vertical-align:top\">
		<p>
		<input onclick=\"toggle_visibility('toggle_sunday_closed');\" type=\"checkbox\" name=\"inp_owner_opening_hours_su_closed\" ";if($configOwnerOpeningHoursSuClosedSav == "1"){ echo" checked=\"checked\"";}echo"/> 
		$l_closed
		</p>
	  </td>
	 </tr>
	</table>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	
  </td>
  <td>
	<p>
	<input onclick=\"toggle_visibility('toggle_monday');toggle_visibility('toggle_tuesday');toggle_visibility('toggle_wednesday');toggle_visibility('toggle_thursday');toggle_visibility('toggle_friday');toggle_visibility('toggle_saturday');toggle_visibility('toggle_sunday');\" type=\"checkbox\" name=\"inp_owner_opening_two_time_intervals\" ";if($configOwnerOpeningTwoTimeIntervalsSav == "1"){ echo" checked=\"checked\"";}echo"/> 
	$l_i_want_to_add_two_time_intervals_for_the_same_day
	</p>
  </td>
 </tr>
</table>


<h3>$l_visit_address</h3>
<table>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_address_line_a:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_visit_address_a\" value=\"$configOwnerVisitAddressASav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_address_line_b:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_visit_address_b\" value=\"$configOwnerVisitAddressBSav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_zip_and_city:</p>
  </td>
  <td>
	<p>
	<input type=\"text\" name=\"inp_owner_visit_address_zip\" value=\"$configOwnerVisitAddressZipSav\" size=\"5\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	<input type=\"text\" name=\"inp_owner_visit_address_city\" value=\"$configOwnerVisitAddressCitySav\" size=\"51\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_country:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_visit_address_country\" value=\"$configOwnerVisitAddressCountrySav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;vertical-align: top;\">
	<p>$l_route_description:</p>
  </td>
  <td>
	<p><textarea name=\"inp_owner_visit_address_route_description\" rows=\"9\" cols=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />"; $configOwnerVisitAddressRouteDescriptionSav = str_replace("<br />", "\n", $configOwnerVisitAddressRouteDescriptionSav);echo"$configOwnerVisitAddressRouteDescriptionSav</textarea></p>
  </td>
 </tr>
</table>


<h3>$l_mailing_address</h3>
<table>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_address_line_a:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_post_address_a\" value=\"$configOwnerPostAddressASav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_address_line_b:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_post_address_b\" value=\"$configOwnerPostAddressBSav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_zip_and_city:</p>
  </td>
  <td>
	<p>
	<input type=\"text\" name=\"inp_owner_post_address_zip\" value=\"$configOwnerPostAddressZipSav\" size=\"5\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	<input type=\"text\" name=\"inp_owner_post_address_city\" value=\"$configOwnerPostAddressCitySav\" size=\"51\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	<p>$l_country:</p>
  </td>
  <td>
	<p><input type=\"text\" name=\"inp_owner_post_address_country\" value=\"$configOwnerPostAddressCountrySav\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
 <tr>
  <td style=\"text-align:right;padding-right: 4px;\">
	
  </td>
  <td>		
	<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn btn-success btn-sm\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
  </td>
 </tr>
</table>


</form>

";
?>