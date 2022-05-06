<?php 
/**
*
* File: contact_form/view_form.php
* Version 1.0.0.
* Date 22:07 23.01.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");




/*- Tables ---------------------------------------------------------------------------- */
include("_tables_contact_forms.php");



/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);
if(isset($_GET['form_id'])){
	$form_id = $_GET['form_id'];
	$form_id = output_html($form_id);
}
else{
	$form_id = "";
}
$form_id_mysql = quote_smart($link, $form_id);

if(isset($_GET['message_id'])){
	$message_id = $_GET['message_id'];
	$message_id = output_html($message_id);
}
else{
	$message_id = "";
}
$message_id_mysql = quote_smart($link, $message_id);

if(isset($_GET['message_password'])){
	$message_password = $_GET['message_password'];
	$message_password = output_html($message_password);
}
else{
	$message_password = "";
}
$message_password_mysql = quote_smart($link, $message_password);

$tabindex = 0;


// Get contact form
$query = "SELECT form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_first_field_name, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_api_password, form_ipblock, form_used_times FROM $t_contact_forms_index WHERE form_id=$form_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_form_id, $get_current_form_title, $get_current_form_language, $get_current_form_mail_to, $get_current_form_text_before_form, $get_current_form_text_left_of_form, $get_current_form_text_right_of_form, $get_current_form_text_after_form, $get_current_form_first_field_name, $get_current_form_created_datetime, $get_current_form_created_by_user_id, $get_current_form_updated_datetime, $get_current_form_updated_by_user_id, $get_current_form_api_avaible, $get_current_form_api_password, $get_current_form_ipblock, $get_current_form_used_times) = $row;

if($get_current_form_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_contact_forms - 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"
	<h1>Form not found</h1>
	";

}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_form_title";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");


	if($action == ""){
		if($process == "1"){
			// Existing message?
			$query = "SELECT message_id, message_form_id, message_password, message_notes, message_created_datetime, message_updated_datetime, message_ip, message_hostname, message_agent FROM $t_contact_forms_messages_index WHERE message_id=$message_id_mysql AND message_form_id=$get_current_form_id AND message_password=$message_password_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_message_id, $get_current_message_form_id, $get_current_message_password, $get_current_message_notes, $get_current_message_created_datetime, $get_current_message_updated_datetime, $get_current_message_ip, $get_current_message_hostname, $get_current_message_agent) = $row;

			if($get_current_message_id == ""){
				// Create a message index

				// Message Password
				$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    				$charactersLength = strlen($characters);
    				$inp_message_password = '';
    				for ($i = 0; $i < 6; $i++) {
        				$inp_message_password .= $characters[rand(0, $charactersLength - 1)];
    				}
				$inp_message_password_mysql = quote_smart($link, $inp_message_password);

				$inp_source = "$configSiteURLSav/contact_forms/view_form.php?form_id=$form_id&amp;l=$l";
				$inp_source = output_html($inp_source);
				$inp_source_mysql = quote_smart($link, $inp_source);

				$datetime = date("Y-m-d H:i:s");

				$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
				$inp_user_agent = output_html($inp_user_agent);
				$inp_user_agent_mysql = quote_smart($link, $inp_user_agent);
	
				$inp_ip = $_SERVER['REMOTE_ADDR'];
				$inp_ip = output_html($inp_ip);
				$inp_ip_mysql = quote_smart($link, $inp_ip);

				$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
				$inp_hostname = output_html($inp_hostname);
				$inp_hostname_mysql = quote_smart($link, $inp_hostname);

				mysqli_query($link, "INSERT INTO $t_contact_forms_messages_index
				(message_id, message_form_id, message_password, message_notes, message_source, message_created_datetime, message_updated_datetime, message_ip, message_hostname, message_agent) 
				VALUES 
				(NULL, $get_current_form_id, $inp_message_password_mysql, '', $inp_source_mysql, '$datetime', '$datetime', $inp_ip_mysql, $inp_hostname_mysql, $inp_user_agent_mysql)")
				or die(mysqli_error($link));

				// Get message index
				$query = "SELECT message_id, message_form_id, message_password, message_notes, message_created_datetime, message_updated_datetime, message_ip, message_hostname, message_agent FROM $t_contact_forms_messages_index WHERE message_created_datetime='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_message_id, $get_current_message_form_id, $get_current_message_password, $get_current_message_notes, $get_current_message_created_datetime, $get_current_message_updated_datetime, $get_current_message_ip, $get_current_message_hostname, $get_current_message_agent) = $row;

			}



			// Feedback
			$ft = "";
			$fm = "";
			$fmissing_question_id = "";

			$inp_message = "";

			$query = "SELECT question_id, question_form_id, question_title, question_field_name, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_form_id=$get_current_form_id ORDER BY question_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_question_id, $get_question_form_id, $get_question_title, $get_question_field_name, $get_question_weight, $get_question_type, $get_question_size, $get_question_rows, $get_question_cols, $get_question_required, $get_question_answer) = $row;

				$inp_q = $_POST["inp_q_$get_question_field_name"];
				$inp_q = output_html($inp_q);
				$inp_q_mysql = quote_smart($link, $inp_q);

				// Exists in MySQL?
				if($inp_q != ""){
					$query_answer = "SELECT answer_id FROM $t_contact_forms_messages_answers WHERE form_id=$get_question_form_id AND question_id=$get_question_id AND message_id=$get_current_message_id";
					$result_answer = mysqli_query($link, $query_answer);
					$row_answer = mysqli_fetch_row($result_answer);
					list($get_answer_id) = $row_answer;
					if($get_answer_id == ""){
						// Insert
						mysqli_query($link, "INSERT INTO $t_contact_forms_messages_answers 
						(answer_id, form_id, question_id, message_id, qestion_answer) 
						VALUES 
						(NULL, $get_question_form_id, $get_question_id, $get_current_message_id, $inp_q_mysql)")
						or die(mysqli_error($link));
					}
					else{
						$result_answer = mysqli_query($link, "UPDATE $t_contact_forms_messages_answers SET qestion_answer=$inp_q_mysql WHERE answer_id=$get_answer_id");
					}
				}
			
				$inp_message = $inp_message  . "<p><b>$get_question_title</b><br />\n";
				$inp_message = $inp_message  . "$inp_q</p>\n";
	
	
				// Required?
				if($get_question_required == "1" && $inp_q == ""){
					$ft = "error";
					$fm = "missing_question";
					$fmissing_question_id = "$get_question_id";
				}
			}


			// Error?
			if($ft == "error"){

				$url = "view_form.php?form_id=$form_id&message_id=$get_current_message_id&message_password=$get_current_message_password&ft=$ft&fm=$fm&fmissing_question_id=$fmissing_question_id";
				header("Location: $url");
				exit;
			}
			
			// Send email
			$datetime_print = date('j M Y H:i');
			$subject = "$get_current_form_title at $configWebsiteTitleSav - $get_current_message_id";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			$message = $message . "$inp_message\n\n";

			$message = $message . "<hr />\n";
			$message = $message . "<p><b>Message Id:</b> $get_current_message_id<br />\n";
			$message = $message . "<b>Source:</b> $configSiteURLSav/contact_forms/view_form.php?form_id=$form_id&amp;l=$l<br />\n";
			$message = $message . "<b>IP:</b> $get_current_message_ip<br />\n";
			$message = $message . "<b>Host:</b> $get_current_message_hostname<br />\n";
			$message = $message . "<b>Agent:</b> $get_current_message_agent</p>\n";
			$message = $message . "<hr />\n";

			$message = $message . "<p>--<br />\n";
			$message = $message . "Regards<br />\n";
			$message = $message . "$configWebsiteTitleSav</p>\n";

			$message = $message. "</body>\n";
			$message = $message. "</html>\n";


			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=utf-8';
			$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
			mail($get_current_form_mail_to, $subject, $message, implode("\r\n", $headers));
			

			// Auto reply?
			$query = "SELECT auto_reply_id, auto_reply_form_id, auto_reply_from_email, auto_reply_from_name, auto_reply_subject, auto_reply_text, auto_reply_delay, auto_reply_active FROM $t_contact_forms_auto_replies WHERE auto_reply_form_id=$get_current_form_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_auto_reply_id, $get_auto_reply_form_id, $get_auto_reply_from_email, $get_auto_reply_from_name, $get_auto_reply_subject, $get_auto_reply_text, $get_auto_reply_delay, $get_auto_reply_active) = $row;

			if($get_auto_reply_id != ""){
				if($get_auto_reply_active == "1"){
					// Fetch e-mail to the person
					$query = "SELECT question_id, question_field_name FROM $t_contact_forms_questions WHERE question_form_id=$get_current_form_id AND question_type='email'";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_question_id, $get_question_field_name) = $row;

					$inp_to_email = $_POST["inp_q_$get_question_field_name"];
					$inp_to_email = output_html($inp_to_email);
	
	
					// Send auto reply
					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "$get_auto_reply_text\n";
	
					$message = $message. "</body>\n";
					$message = $message. "</html>\n";

					$headers = array();
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=utf-8';
					$headers[] = "From: $get_auto_reply_from_name <" . $get_auto_reply_from_email . ">";
					if($inp_to_email != ""){
						mail($inp_to_email, $get_auto_reply_subject, $message, implode("\r\n", $headers));
					}
				} // active 
			} // auto reply

			// Header
			$url = "view_form.php?form_id=$form_id&action=message_sent&message_id=$get_current_message_id&message_password=$get_current_message_password&ft=success&fm=message_sent";
			header("Location: $url");
			exit;
			

		} // send
	

		// Existing message?
		$query = "SELECT message_id, message_form_id, message_password, message_notes, message_created_datetime, message_updated_datetime, message_ip, message_hostname, message_agent FROM $t_contact_forms_messages_index WHERE message_id=$message_id_mysql AND message_form_id=$get_current_form_id AND message_password=$message_password_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_message_id, $get_current_message_form_id, $get_current_message_password, $get_current_message_notes, $get_current_message_created_datetime, $get_current_message_updated_datetime, $get_current_message_ip, $get_current_message_hostname, $get_current_message_agent) = $row;


		echo"
		<!-- Before -->
			$get_current_form_text_before_form
		<!-- //Before -->

		<!-- Left -->
			";
			if($get_current_form_text_left_of_form != "" && $get_current_form_text_right_of_form == ""){
				echo"<div class=\"form_text_left_right_left\">$get_current_form_text_left_of_form</div>";
			}
			if($get_current_form_text_left_of_form != "" && $get_current_form_text_right_of_form != ""){
				echo"<div class=\"form_text_left_center_left_left\">$get_current_form_text_left_of_form</div>";
			}
			echo"
		<!-- //Left -->


		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			elseif($fm == "missing_question"){

				if(isset($_GET['fmissing_question_id'])){
					$fmissing_question_id = $_GET['fmissing_question_id'];
					$fmissing_question_id = output_html($fmissing_question_id);
				}
				else{
					$fmissing_question_id = "";
				}
				$fmissing_question_id_mysql = quote_smart($link, $fmissing_question_id);

		
				$query = "SELECT question_id, question_title FROM $t_contact_forms_questions WHERE question_id=$fmissing_question_id_mysql AND question_form_id=$get_current_form_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_question_id, $get_question_title) = $row;



				$fm = "$l_please_fill_in_the_field &quot;$get_question_title&quot;";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"$get_current_form_first_field_name\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"view_form.php?form_id=$form_id&amp;process=1&amp;message_id=$get_current_message_id&amp;message_password=$get_current_message_password\" enctype=\"multipart/form-data\">
			";


			$query = "SELECT question_id, question_form_id, question_title, question_field_name, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_form_id=$get_current_form_id ORDER BY question_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_question_id, $get_question_form_id, $get_question_title, $get_question_field_name, $get_question_weight, $get_question_type, $get_question_size, $get_question_rows, $get_question_cols, $get_question_required, $get_question_answer) = $row;
				
				// Look for answer
				$get_qestion_answer = "";
				if($get_current_message_id != ""){
					$query_answer = "SELECT answer_id, qestion_answer FROM $t_contact_forms_messages_answers WHERE form_id=$get_question_form_id AND question_id=$get_question_id AND message_id=$get_current_message_id";
					$result_answer = mysqli_query($link, $query_answer);
					$row_answer = mysqli_fetch_row($result_answer);
					list($get_answer_id, $get_qestion_answer) = $row_answer;
				}
			

				echo"
				<p><b>$get_question_title";
				if($get_question_required == "1"){ echo" *"; } 
				echo"</b><br />
				";
				if($get_question_type == "text" OR $get_question_type == "email"){
					echo"<input type=\"text\" name=\"inp_q_$get_question_field_name\" size=\"$get_question_size\" value=\"$get_qestion_answer\" /><br />\n";
				}
				elseif($get_question_type == "textarea"){
					$get_qestion_answer = str_replace("<br />", "\n", $get_qestion_answer);
					echo"<textarea name=\"inp_q_$get_question_field_name\" rows=\"$get_question_rows\" cols=\"$get_question_cols\">$get_qestion_answer</textarea><br />\n";
				}
				elseif($get_question_type == "radio"){
					$y = 0;
					$query_alt = "SELECT alternative_id, form_id, question_id, alternative_title, alternative_preselected FROM $t_contact_forms_questions_alternatives WHERE form_id=$get_question_form_id AND question_id=$get_question_id";
					$result_alt = mysqli_query($link, $query_alt);
					while($row_alt = mysqli_fetch_row($result_alt)) {
						list($get_alternative_id, $get_form_id, $get_question_id, $get_alternative_title, $get_alternative_preselected) = $row_alt;
	
						echo"
						<input type=\"radio\" name=\"inp_q_$get_question_field_name\" value=\"$get_alternative_title\"";
						if($get_qestion_answer == ""){
							if($y == "0"){ echo" checked=\"checked\""; }
						}
						else{
							if($get_qestion_answer == "$get_alternative_title"){ echo" checked=\"checked\""; }
						}

						echo" /> $get_alternative_title
						";

						$y++;
					}

					echo"<br />\n";
				}
				elseif($get_question_type == "select"){
					echo"<select name=\"inp_q_$get_question_field_name\">";

					$y = 0;
					$query_alt = "SELECT alternative_id, form_id, question_id, alternative_title, alternative_preselected FROM $t_contact_forms_questions_alternatives WHERE form_id=$get_question_form_id AND question_id=$get_question_id";
					$result_alt = mysqli_query($link, $query_alt);
					while($row_alt = mysqli_fetch_row($result_alt)) {
						list($get_alternative_id, $get_form_id, $get_question_id, $get_alternative_title, $get_alternative_preselected) = $row_alt;

						echo"
						<option value=\"$get_alternative_title\"";
						if($get_qestion_answer == ""){
							 if($y == "0"){ echo" selected=\"selected\""; }
						}
						else{
							if($get_qestion_answer == "$get_alternative_title"){ echo" selected=\"selected\""; }
						}
						echo">$get_alternative_title</option>\n";

						$y++;
					}

					echo"
					</select><br />\n";
	

				}
				echo"
				</p>
				";
			}  // questions
			echo"
			<p><input type=\"submit\" value=\"$l_send\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
			</form>
		<!-- Form -->


		<!-- Right -->
			";
			if($get_current_form_text_right_of_form != "" && $get_current_form_text_left_of_form == ""){
				echo"<div class=\"form_text_left_right_right\">$get_current_form_text_right_of_form</div>";
			}
			if($get_current_form_text_right_of_form != "" && $get_current_form_text_left_of_form != ""){
				echo"<div class=\"form_text_left_center_left_right\">$get_current_form_text_right_of_form</div>";
			}
			echo"
			
		<!-- //Right -->

		<!-- After -->
			$get_current_form_text_after_form
		<!-- //After -->
	
		";
	} // action == ""
	elseif($action == "message_sent"){
		echo"
		<h1>$l_thank_you</h1>

		<p>$l_your_message_is_sent</p>

		<p><a href=\"../index.php?l=$l\">$l_home</a></p>
		";
	}
} // form found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>