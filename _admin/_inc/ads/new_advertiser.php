<?php
/**
*
* File: _admin/_inc/ads/new_advertiser.php
* Version 1
* Date 08:57 17.05.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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

if($process == "1"){
	$inp_name = $_POST['inp_name'];
	$inp_name = output_html($inp_name);
	$inp_name_mysql = quote_smart($link, $inp_name);

	$inp_website = $_POST['inp_website'];
	$inp_website = output_html($inp_website);
	$inp_website_mysql = quote_smart($link, $inp_website);

	$inp_contact_name = $_POST['inp_contact_name'];
	$inp_contact_name = output_html($inp_contact_name);
	$inp_contact_name_mysql = quote_smart($link, $inp_contact_name);

	$inp_contact_email = $_POST['inp_contact_email'];
	$inp_contact_email = output_html($inp_contact_email);
	$inp_contact_email_mysql = quote_smart($link, $inp_contact_email);

	$inp_contact_phone = $_POST['inp_contact_phone'];
	$inp_contact_phone = output_html($inp_contact_phone);
	$inp_contact_phone_mysql = quote_smart($link, $inp_contact_phone);


	mysqli_query($link, "INSERT INTO $t_ads_advertisers
	(advertiser_id, advertiser_name, advertiser_website, advertiser_contact_name, advertiser_contact_email, advertiser_contact_phone) 
	VALUES 
	(NULL, $inp_name_mysql, $inp_website_mysql, $inp_contact_name_mysql, $inp_contact_email_mysql, $inp_contact_phone_mysql)")
	or die(mysqli_error($link));

	$url = "index.php?open=ads&page=advertisers&editor_language=$editor_language&ft=success&fm=advertiser_created";
	header("Location: $url");
	exit;
}
echo"
<h1>New advertiser</h1>

<!-- Form -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_name\"]').focus();
	});
	</script>
			
	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

	<p><b>Name:</b><br />
	<input type=\"text\" name=\"inp_name\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Website:</b><br />
	<input type=\"text\" name=\"inp_website\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Contact name:</b><br />
	<input type=\"text\" name=\"inp_contact_name\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Contact email:</b><br />
	<input type=\"text\" name=\"inp_contact_email\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Contact phone:</b><br />
	<input type=\"text\" name=\"inp_contact_phone\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	</form>
<!-- //Form -->
";

?>