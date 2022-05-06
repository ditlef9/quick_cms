<?php
/**
*
* File: users/index.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_create_free_account - $l_users";
include("$root/_webdesign/header.php");


/*- Variables --------------------------------------------------------------------------- */
$tabindex = 0;

if(isset($_GET['referer'])) {
	$referer = $_GET["referer"];
	$referer = output_html($referer);
	if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $referer)){
		echo"Server error 403, invalid parameters";
		die;
	}
}
else{
	$referer = "";
}
/*- Content --------------------------------------------------------------------------- */

$inp_ip = $_SERVER['REMOTE_ADDR'];
$inp_ip = output_html($inp_ip);

$ip_date = date("Y-m-d");

$inp_my_ip_block = $inp_ip . "|" . $ip_date . "|" . "1";

// IP Check
if(!(is_dir("$root/_cache"))){
	mkdir("$root/_cache");
}
if(!(file_exists("$root/_cache/create_free_account_ipblock.dat"))){
	$fh = fopen("$root/_cache/create_free_account_ipblock.dat", "w+") or die("can not open file");
	fwrite($fh, $inp_my_ip_block);
	fclose($fh);
}
else{
	$fh = fopen("$root/_cache/create_free_account_ipblock.dat", "r");
	$filesize = filesize("$root/_cache/create_free_account_ipblock.dat");
	if($filesize == 0){
		$data = "";
	}
	else{
		$data = fread($fh, $filesize);
	}
	fclose($fh);

	$array = explode("\n", $data);
	$array_size = sizeof($array);

	if($array_size > 50){
		$array_size = 1;
	}

	$inp_ip_block = "";
	$found_my_ip = 0;

	for($x=0;$x<$array_size;$x++){
		$temp = explode("|", $array[$x]);

		if(isset($temp[1]) && $temp[1] == "$ip_date"){
			if($temp[0] == "$inp_ip"){
				$temp[2] = $temp[2]+1;
				$found_my_ip = 1;
				$my_hits_counter = $temp[2];
			}

			if($inp_ip_block == ""){
				$inp_ip_block = $temp[0] . "|" . $temp[1] . "|" . $temp[2];
			}
			else{
				$inp_ip_block = $inp_ip_block . "\n" . $temp[0] . "|" . $temp[1] . "|" . $temp[2];
			}
		}

	}

	if($found_my_ip == 0){
		$inp_ip_block = $inp_my_ip_block . "\n" . $inp_ip_block;
	}

	$fh = fopen("$root/_cache/create_free_account_ipblock.dat", "w+") or die("can not open file");
	fwrite($fh, $inp_ip_block);
	fclose($fh);


}

if(isset($my_hits_counter) && $my_hits_counter > 20){
	echo"
	<h1>$l_ip_block</h1>

	<p>$l_your_ip_has_been_blocked</p>
	<p>$l_this_is_to_prevent_spam</p>
	";
}
else{

	if(!(isset($_SESSION['user_id']))){


		if($process == "1"){
			// Language
			$inp_language = "$l";
			$inp_language = output_html($inp_language);

			// Anti spam
			$question_id = $_GET['question_id'];
			$question_id = strip_tags(stripslashes($question_id));
			$question_id_mysql = quote_smart($link, $question_id);
			
			
			$inp_antispam_answer = $_POST['inp_antispam_answer'];
			$inp_antispam_answer = output_html($inp_antispam_answer);
			$inp_antispam_answer = strtolower($inp_antispam_answer);
			$inp_antispam_answer = trim($inp_antispam_answer);

			// Policies
			if(isset($_POST['inp_cookies_policy'])){
				$inp_cookies_policy = "1";
			}
			else{
				$inp_cookies_policy = "0";
			}

			if(isset($_POST['inp_privacy_policy'])){
				$inp_privacy_policy = "1";
			}
			else{
				$inp_privacy_policy = "0";
			}

			if(isset($_POST['inp_terms_of_use'])){
				$inp_terms_of_use = "1";
			}
			else{
				$inp_terms_of_use = "0";
			}


			// -> check answers
			$antispam_correct = "false"; // make a guess
			$query = "SELECT antispam_answer_id, antispam_answer FROM $t_users_antispam_answers WHERE antispam_answer_question_id=$question_id_mysql";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_antispam_answer_id, $get_antispam_answer) = $row;
				$get_antispam_answer = trim($get_antispam_answer);



				if($inp_antispam_answer == "$get_antispam_answer"){
					// Set antispam OK
					$_SESSION['antispam_ok'] = "1";
					$antispam_correct = "true";

					if($inp_cookies_policy == "1" && $inp_privacy_policy == "1" && $inp_terms_of_use == "1"){
			
						// Move user
						$url = "create_free_account_step_2_user.php?l=$inp_language";
						if($referer != ""){ $url = $url  . "&referer=$referer"; }
						header("Location: $url");
						die;
					}
					else{
						// Move user
						$ft = "error";
						$fm = "all_policies_must_be_accepted";
						$url = "create_free_account.php?l=$inp_language&ft=$ft&fm=$fm&your_answer=$inp_antispam_answer&question_id=$question_id";
						if($referer != ""){ $url = $url  . "&referer=$referer"; }
						header("Location: $url");
						die;
					}

				}
			}


			if($antispam_correct  == "false"){
				$ft = "error";
				$fm = "users_you_answered_wrong_on_antispam_question";

				// Move user
				$url = "create_free_account.php?l=$inp_language&ft=$ft&fm=$fm&your_answer=$inp_antispam_answer&question_id=$question_id";
				if($referer != ""){ $url = $url  . "&referer=$referer"; }
				header("Location: $url");
				exit;
			}
		}
		if($action == ""){

			// Anti spam
			$l_mysql = quote_smart($link, $l);
			$query = "SELECT antispam_question_id, antispam_question_language, antispam_question FROM $t_users_antispam_questions WHERE antispam_question_language=$l_mysql";
			$result = mysqli_query($link, $query);
			$row_cnt = mysqli_num_rows($result);
			$random = rand(1, $row_cnt);

			$x = 1;
			while($row = mysqli_fetch_row($result)) {
				if($x == $random){
					list($get_antispam_question_id, $get_antispam_question_language, $get_antispam_question) = $row;
					break;
				}
				$x++;

			}
			if($get_antispam_question_id == ""){
				echo"Error: Could not get anti spam question";
			}


			echo"
			<h1>$l_menu_create_free_account</h1>

			<!-- Login / Create profile tabs -->
				<div class=\"tabs\">
					<ul>
						<li><a href=\"login.php?l=$l"; if($referer != ""){ echo"&amp;referer=$referer"; } echo"\">$l_login</a>
						<li><a href=\"create_free_account.php?l=$l"; if($referer != ""){ echo"&amp;referer=$referer"; } echo"\" class=\"active\">$l_registrer</a>
					</ul>
				</div>
				<div class=\"clear\" style=\"height: 20px;\"></div>
			<!-- //Login / Create profile tabs -->

			
			<form method=\"POST\" action=\"create_free_account.php?action=check_antispam&amp;l=$l&amp;question_id=$get_antispam_question_id&amp;process=1"; if($referer != ""){ echo"&amp;referer=$referer"; } echo"\" enctype=\"multipart/form-data\">

			<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "users_you_answered_wrong_on_antispam_question"){
					$fm = "$l_users_you_answered_wrong_on_antispam_question";
				}
				elseif($fm == "all_policies_must_be_accepted"){
					$fm = "$l_all_policies_must_be_accepted";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
			<!-- //Feedback -->


			<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_antispam_answer\"]').focus();
			});
			</script>
			<!-- //Focus -->


			<p>
			$l_users_about_registration
			</p>

			<p>$l_language:<br />
			<select name=\"inp_language\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" class=\"on_select_go_to_url\">";

			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

				echo"			";
				echo"<option value=\"create_free_account.php?l=$get_language_active_iso_two&amp;referer=$referer\""; if($get_language_active_iso_two == "$l"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>

			<!-- On select go to URL -->
				<script>
				\$(function(){
					// bind change event to select
					\$('.on_select_go_to_url').on('change', function () {
						var url = \$(this).val(); // get selected value
						if (url) { // require a URL
							window.location = url; // redirect
						}
						return false;
					});
				});
				</script>
			<!-- //On select go to URL -->
			


			<p>$get_antispam_question:<br />
			<input type=\"text\" name=\"inp_antispam_answer\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /><br />
			</p>


			<p>$l_i_accept:<br />
			<label><input type=\"checkbox\" name=\"inp_cookies_policy\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /> <span class=\"click_cookies_policy\">$l_cookies_policy</span> [<a href=\"$root/legal/index.php?doc=cookies_policy&amp;l=$l\">$l_read</a>]</label><br />
			<label><input type=\"checkbox\" name=\"inp_privacy_policy\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /> <span class=\"click_privacy_policy\">$l_privacy_policy</span> [<a href=\"$root/legal/index.php?doc=privacy_policy&amp;l=$l\">$l_read</a>]</label><br />
			<label><input type=\"checkbox\" name=\"inp_terms_of_use\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /> <span class=\"click_terms_of_use\">$l_terms_of_use</span> [<a href=\"$root/legal/index.php?doc=terms_of_use&amp;l=$l\">$l_read</a>]</label><br />
			</p>

			<p>
			<input type=\"submit\" value=\"$l_continue\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			</form>

			";

		}
	}
	else{
		echo"
		<table>
		 <tr> 
		  <td style=\"padding-right: 6px;\">
			<p>
			<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" />
			</p>
		  </td>
		  <td>
			<h1>Loading</h1>
		  </td>
		 </tr>
		</table>
		<p>You are registered!</p>
		<p>
		<a href=\"$root/index.php\" class=\"btn\">Home</a></p>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/index.php\">
		";
	}
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>