<?php
/**
*
* File: _admin/_inc/settings/default.php
* Version 02:10 28.12.2011
* Copyright (c) 2008-2012 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Variables ------------------------------------------------------------------------ */
if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = stripslashes(strip_tags($mode));
}
else{
	$mode = "";
}


if($mode == "save"){

	$inp_website_title = $_POST['inp_website_title'];
	$inp_website_title = output_html($inp_website_title);

	$inp_website_title_clean = clean($inp_website_title);

	$inp_website_webmaster = $_POST['inp_website_webmaster'];
	$inp_website_webmaster = output_html($inp_website_webmaster);

	$inp_website_webmaster_email = $_POST['inp_website_webmaster_email'];
	$inp_website_webmaster_email = output_html($inp_website_webmaster_email);

	$inp_website_copyright = $_POST['inp_website_copyright'];
	$inp_website_copyright = output_html($inp_website_copyright);

	$inp_from_email = $_POST['inp_from_email'];
	$inp_from_email = output_html($inp_from_email);

	$inp_from_name = $_POST['inp_from_name'];
	$inp_from_name = output_html($inp_from_name);

	$inp_website_version = $_POST['inp_website_version'];
	$inp_website_version = output_html($inp_website_version);


	$inp_mail_send_active = $_POST['inp_mail_send_active'];
	$inp_mail_send_active = output_html($inp_mail_send_active);



	// Control panel URL
	$inp_control_panel_url = $_POST['inp_control_panel_url'];
	$inp_control_panel_url = output_html($inp_control_panel_url);
	$inp_control_panel_url_len = strlen($inp_control_panel_url);

	$control_panel_url_parsed = parse_url($inp_control_panel_url);
	$inp_control_panel_url_scheme = $control_panel_url_parsed['scheme'];
	$inp_control_panel_url_host = $control_panel_url_parsed['host'];
	if(isset($control_panel_url_parsed['port'])){
		$inp_control_panel_url_port = $control_panel_url_parsed['port'];
	}
	else{
		$inp_control_panel_url_port = "";
	}
	$inp_control_panel_url_path = $control_panel_url_parsed['path'];



	// Website URL
	$inp_site_url = $_POST['inp_site_url'];
	$inp_site_url = output_html($inp_site_url);

	$inp_site_url_len = strlen($inp_site_url);
	$site_url_parsed = parse_url($inp_site_url);
	$inp_site_url_scheme = $site_url_parsed['scheme'];
	$inp_site_url_host = $site_url_parsed['host'];
	if(isset($site_url_parsed['port'])){
		$inp_site_url_port = $site_url_parsed['port'];
	}
	else{
		$inp_site_url_port = "";
	}
	if(isset($site_url_parsed['path'])){
		$inp_site_url_path = $site_url_parsed['path'];
	}
	else{
		$inp_site_url_path = "";
	}


	// Statisics
	$inp_site_use_gethostbyaddr = $_POST['inp_site_use_gethostbyaddr'];
	$inp_site_use_gethostbyaddr = output_html($inp_site_use_gethostbyaddr);

	$inp_site_days_to_keep_page_visits = $_POST['inp_site_days_to_keep_page_visits'];
	$inp_site_days_to_keep_page_visits = output_html($inp_site_days_to_keep_page_visits);
	if(!(is_numeric($inp_site_days_to_keep_page_visits))){
		echo"inp_site_days_to_keep_page_visits must be numeric!";
		die;
	}

	$inp_security_code = "";
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$inp_security_code = '';
	for ($i = 0; $i < 20; $i++) {
		$inp_security_code .= $characters[rand(0, $charactersLength - 1)];
	}

	// Test
	$inp_site_is_test = $_POST['inp_site_is_test'];
	$inp_site_is_test = output_html($inp_site_is_test);

	$update_file="<?php
// General
\$configWebsiteTitleSav		 = \"$inp_website_title\";
\$configWebsiteTitleCleanSav	 = \"$inp_website_title_clean\";
\$configWebsiteCopyrightSav	 = \"$inp_website_copyright\";
\$configFromEmailSav 		 = \"$inp_from_email\";
\$configFromNameSav 		 = \"$inp_from_name\";
 
\$configWebsiteVersionSav	= \"$inp_website_version\";
\$configMailSendActiveSav	= \"$inp_mail_send_active\";

// Webmaster
\$configWebsiteWebmasterSav	 = \"$inp_website_webmaster\";
\$configWebsiteWebmasterEmailSav = \"$inp_website_webmaster_email\";

// URLS
\$configSiteURLSav 		= \"$inp_site_url\";
\$configSiteURLLenSav 		 = \"$inp_site_url_len\";
\$configSiteURLSchemeSav	= \"$inp_site_url_scheme\";
\$configSiteURLHostSav		= \"$inp_site_url_host\";
\$configSiteURLPortSav		= \"$inp_site_url_port\";
\$configSiteURLPathSav		= \"$inp_site_url_path\";

\$configControlPanelURLSav 		= \"$inp_control_panel_url\";
\$configControlPanelURLLenSav 		= \"$inp_control_panel_url_len\";
\$configControlPanelURLSchemeSav	= \"$inp_control_panel_url_scheme\";
\$configControlPanelURLHostSav		= \"$inp_control_panel_url_host\";
\$configControlPanelURLPortSav		= \"$inp_control_panel_url_port\";
\$configControlPanelURLPathSav		= \"$inp_control_panel_url_path\";

// Statisics
\$configSiteUseGethostbyaddrSav = \"$inp_site_use_gethostbyaddr\";
\$configSiteDaysToKeepPageVisitsSav = \"$inp_site_days_to_keep_page_visits\";
\$configSecurityCodeSav = \"$inp_security_code\";

// Test
\$configSiteIsTestSav = \"$inp_site_is_test\";
?>";

	$fh = fopen("_data/config/meta.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);


	echo"
	<h1>$l_meta_data</h1>
	<h2><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Saving...</h2>
	<meta http-equiv=refresh content=\"3; url=index.php?open=settings&page=$page&ft=success&fm=changes_saved\">
	";
	// header("Location: ?open=settings&page=$page&focus=inp_website_title&ft=success&fm=changes_saved");
	// exit;
}
if($mode == ""){

	$tabindex = 0;
	echo"
	<h1>$l_meta_data</h1>
	<form method=\"post\" action=\"index.php?open=settings&amp;page=$page&amp;mode=save\" enctype=\"multipart/form-data\">
				
	
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
		\$('[name=\"inp_website_title\"]').focus();
	});
	</script>
	<!-- //Focus -->

	<h2>General</h2>
	<p>$l_website_title:<br />
	<input type=\"text\" name=\"inp_website_title\" value=\"$configWebsiteTitleSav\" size=\"50\" tabindex=\""; $tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

	<p>$l_website_copyright:<br />
	<input type=\"text\" name=\"inp_website_copyright\" value=\"$configWebsiteCopyrightSav\" size=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

	<p>Website E-mail address (used for sending e-mails):<br />
	<input type=\"text\" name=\"inp_from_email\" value=\"$configFromEmailSav\" size=\"50\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

	<p>Website from name:<br />
	<input type=\"text\" name=\"inp_from_name\" value=\"$configFromNameSav\" size=\"50\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

	<p>Website version:<br />
	<input type=\"text\" name=\"inp_website_version\" value=\"$configWebsiteVersionSav\" size=\"50\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" /></p>

	<p>Mail send active<br />
	<span class=\"smal\">If turned off then the web site will never send emails. Suitable for testing and server that doesnt have internet connection.</span><br />
	<input type=\"radio\" name=\"inp_mail_send_active\" value=\"1\""; if($configMailSendActiveSav == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> Yes
	&nbsp;
	<input type=\"radio\" name=\"inp_mail_send_active\" value=\"0\""; if($configMailSendActiveSav == "0"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> No
	</p>


	<h2>Webmaster</h2>

	<p>$l_webmaster_name:<br />
	<input type=\"text\" name=\"inp_website_webmaster\" value=\"$configWebsiteWebmasterSav\" size=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>


	<p>$l_webmaster_email:<br />
	<input type=\"text\" name=\"inp_website_webmaster_email\" value=\"$configWebsiteWebmasterEmailSav\" size=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

	<h2>URLs</h2>

	<p>$l_site_url:<br />
	<input type=\"text\" name=\"inp_site_url\" value=\"$configSiteURLSav\" size=\"30\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>


	<p>$l_control_panel_url:<br />
	<input type=\"text\" name=\"inp_control_panel_url\" value=\"$configControlPanelURLSav\" size=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

	<h2>Statistics</h2>

	<p>Use gethostbyaddr<br />
	<input type=\"radio\" name=\"inp_site_use_gethostbyaddr\" value=\"1\""; if($configSiteUseGethostbyaddrSav == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> Yes
	&nbsp;
	<input type=\"radio\" name=\"inp_site_use_gethostbyaddr\" value=\"0\""; if($configSiteUseGethostbyaddrSav == "0"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> No
	</p>

	<p>Days to keep page visits:<br />
	Every visited page is logged in table <em>stats_pages_visits_per_year</em> for statistical purpose.<br />
	How many days do you want to store at the most?<br />
	Set to 0 to deactivate logging.<br />
	<input type=\"text\" name=\"inp_site_days_to_keep_page_visits\" value=\"$configSiteDaysToKeepPageVisitsSav\" size=\"5\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /><br />
	</p>

	<h2>Test mode</h2>

	<p>Site is test-site<br />
	<input type=\"radio\" name=\"inp_site_is_test\" value=\"1\""; if($configSiteIsTestSav == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> Yes
	&nbsp;
	<input type=\"radio\" name=\"inp_site_is_test\" value=\"0\""; if($configSiteIsTestSav == "0"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> No
	</p>
	

	<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

	</form>

	";
} // mode == ""
?>