<?php
/**
*
* File: _admin/_inc/ads/ads_settings.php
* Version 15.00 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Config ----------------------------------------------------------------------- */
if(!(file_exists("_data/ads.php"))){
	$update_file="<?php
\$adsActiveSav = \"1\";
?>";

	$fh = fopen("_data/ads.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
}


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = stripslashes(strip_tags($mode));
}
else{
	$mode = "";
}


if($mode == "save"){


	$inp_ads_active = $_POST['inp_ads_active'];
	$inp_ads_active = output_html($inp_ads_active);



	$update_file="<?php
\$adsActiveSav = \"$inp_ads_active\";
?>";

	$fh = fopen("_data/ads.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);

	echo"
	<h1>Ads settings</h1>
	<h2><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Saving...</h2>
	<meta http-equiv=refresh content=\"3; url=index.php?open=$open&page=$page&ft=success&fm=changes_saved\">
	";

}


if($mode == ""){
	/*- Include config ------------------------------------------------------------------------ */
	include("_data/ads.php");

	echo"
	<h1>Ads settings</h1>

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


	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;mode=save\" enctype=\"multipart/form-data\">


	<p>Ads active:<br />
	<input type=\"radio\" name=\"inp_ads_active\" value=\"1\""; if($adsActiveSav == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> Yes
	&nbsp;
	<input type=\"radio\" name=\"inp_ads_active\" value=\"0\""; if($adsActiveSav == "0"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> No
	</p>

	<p><input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	</form>

	";
}
?>