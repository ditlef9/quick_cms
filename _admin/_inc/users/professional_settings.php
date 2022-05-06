<?php
/**
*
* File: _admin/_inc/settings/professional_settings.php
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
/*- Config  ----------------------------------------------------------------------------- */
include("_data/user_professional_allowed_settings.php");

/*- MySQL Tables ----------------------------------------------------------------------- */
$t_users_professional 					= $mysqlPrefixSav . "users_professional";
$t_users_professional_allowed_companies			= $mysqlPrefixSav . "users_professional_allowed_companies";
$t_users_professional_allowed_company_locations		= $mysqlPrefixSav . "users_professional_allowed_company_locations";
$t_users_professional_allowed_departments		= $mysqlPrefixSav . "users_professional_allowed_departments";
$t_users_professional_allowed_positions			= $mysqlPrefixSav . "users_professional_allowed_positions";
$t_users_professional_allowed_districts			= $mysqlPrefixSav . "users_professional_allowed_districts";


/*- Variables ------------------------------------------------------------------------ */
if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = stripslashes(strip_tags($mode));
}
else{
	$mode = "";
}
$tabindex = 0;


if($action == ""){
	if($mode == "save"){
		
		// Companies
		$inp_can_only_use_allowed_companies = $_POST['inp_can_only_use_allowed_companies'];
		$inp_can_only_use_allowed_companies = output_html($inp_can_only_use_allowed_companies);

		$inp_can_only_use_allowed_company_locations = $_POST['inp_can_only_use_allowed_company_locations'];
		$inp_can_only_use_allowed_company_locations = output_html($inp_can_only_use_allowed_company_locations);

		$inp_can_only_use_allowed_departments = $_POST['inp_can_only_use_allowed_departments'];
		$inp_can_only_use_allowed_departments = output_html($inp_can_only_use_allowed_departments);

		$inp_can_only_use_allowed_positions = $_POST['inp_can_only_use_allowed_positions'];
		$inp_can_only_use_allowed_positions = output_html($inp_can_only_use_allowed_positions);

		$inp_can_only_use_allowed_districts = $_POST['inp_can_only_use_allowed_districts'];
		$inp_can_only_use_allowed_districts = output_html($inp_can_only_use_allowed_districts);



		$input="<?php
\$configUsersCanOnlyUseAllowedCompaniesSav		= \"$inp_can_only_use_allowed_companies\";
\$configUsersCanOnlyUseAllowedCompanyLocationsSav	= \"$inp_can_only_use_allowed_company_locations\";
\$configUsersCanOnlyUseAllowedDepartmentsSav		= \"$inp_can_only_use_allowed_departments\";
\$configUsersCanOnlyUseAllowedPositionsSav		= \"$inp_can_only_use_allowed_positions\";
\$configUsersCanOnlyUseAllowedDistrictsSav		= \"$inp_can_only_use_allowed_districts\";
?>";
		$fh = fopen("_data/user_professional_allowed_settings.php", "w+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);


		echo"
		<h1>Professional settings</h1>
		<h2><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Saving...</h2>
		<meta http-equiv=refresh content=\"3; url=index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l&ft=success&fm=changes_saved\">
		";
	}
	if($mode == ""){

	
		echo"
		<h1>Professional settings</h1>
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l&amp;mode=save\" enctype=\"multipart/form-data\">
				
	
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
			\$('[name=\"inp_users_can_register\"]').focus();
		});
		</script>
		<!-- //Focus -->

		<p>Can only use allowed companies:<br />
		<input type=\"radio\" name=\"inp_can_only_use_allowed_companies\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedCompaniesSav == "1"){ echo" checked=\"checked\"";}echo" />
		Active 
		&nbsp;
		<input type=\"radio\" name=\"inp_can_only_use_allowed_companies\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedCompaniesSav == "0"){ echo" checked=\"checked\"";}echo" />
		Inactive
		</p>

		<p>Can only use allowed company locations:<br />
		<input type=\"radio\" name=\"inp_can_only_use_allowed_company_locations\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedCompanyLocationsSav == "1"){ echo" checked=\"checked\"";}echo" />
		Active 
		&nbsp;
		<input type=\"radio\" name=\"inp_can_only_use_allowed_company_locations\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedCompanyLocationsSav == "0"){ echo" checked=\"checked\"";}echo" />
		Inactive
		</p>

		<p>Can only use allowed departments:<br />
		<input type=\"radio\" name=\"inp_can_only_use_allowed_departments\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedDepartmentsSav == "1"){ echo" checked=\"checked\"";}echo" />
		Active 
		&nbsp;
		<input type=\"radio\" name=\"inp_can_only_use_allowed_departments\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedDepartmentsSav == "0"){ echo" checked=\"checked\"";}echo" />
		Inactive
		</p>

		<p>Can only use allowed positions:<br />
		<input type=\"radio\" name=\"inp_can_only_use_allowed_positions\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedPositionsSav == "1"){ echo" checked=\"checked\"";}echo" />
		Active 
		&nbsp;
		<input type=\"radio\" name=\"inp_can_only_use_allowed_positions\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedPositionsSav == "0"){ echo" checked=\"checked\"";}echo" />
		Inactive
		</p>

		<p>Can only use allowed districts:<br />
		<input type=\"radio\" name=\"inp_can_only_use_allowed_districts\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedDistrictsSav == "1"){ echo" checked=\"checked\"";}echo" />
		Active 
		&nbsp;
		<input type=\"radio\" name=\"inp_can_only_use_allowed_districts\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";if($configUsersCanOnlyUseAllowedDistrictsSav == "0"){ echo" checked=\"checked\"";}echo" />
		Inactive
		</p>

		<p><input type=\"submit\" value=\"$l_save_changes\" class=\"submit\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

		</form>

		";
	} // mode == ""
} // action == ""
?>