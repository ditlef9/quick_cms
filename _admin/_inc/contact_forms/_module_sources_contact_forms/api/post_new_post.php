<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");


/*- Config ----------------------------------------------------------------------------- */
include("../../_admin/_data/config/meta.php");
include("../../_admin/_data/config/user_system.php");
include("../../_admin/_data/logo.php");

/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables ---------------------------------------------------------------------- */
$t_contact_forms_index			= $mysqlPrefixSav . "contact_forms_index";
$t_contact_forms_images			= $mysqlPrefixSav . "contact_forms_images";
$t_contact_forms_questions		= $mysqlPrefixSav . "contact_forms_questions";
$t_contact_forms_questions_alternatives	= $mysqlPrefixSav . "contact_forms_questions_alternatives";
$t_contact_forms_auto_replies		= $mysqlPrefixSav . "contact_forms_auto_replies";
$t_contact_forms_messages		= $mysqlPrefixSav . "contact_forms_messages";
$t_contact_forms_messages_index		= $mysqlPrefixSav . "contact_forms_messages_index";
$t_contact_forms_messages_answers	= $mysqlPrefixSav . "contact_forms_messages_answers";



/*- Get form --------------------------------------------------------------------------- */
if(isset($_POST['inp_form_id'])){
	$inp_form_id = $_POST['inp_form_id'];
	$inp_form_id = output_html($inp_form_id);
}
else{
	echo"Missing inp_form_id";
	die;
}
$inp_form_id_mysql = quote_smart($link, $inp_form_id);

$query = "SELECT form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_first_field_name, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_api_password, form_ipblock, form_used_times FROM $t_contact_forms_index WHERE form_id=$inp_form_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_form_id, $get_current_form_title, $get_current_form_language, $get_current_form_mail_to, $get_current_form_text_before_form, $get_current_form_text_left_of_form, $get_current_form_text_right_of_form, $get_current_form_text_after_form, $get_current_form_first_field_name, $get_current_form_created_datetime, $get_current_form_created_by_user_id, $get_current_form_updated_datetime, $get_current_form_updated_by_user_id, $get_current_form_api_avaible, $get_current_form_api_password, $get_current_form_ipblock, $get_current_form_used_times) = $row;
if($get_current_form_id == ""){
	echo"Form not found";
	die;
}

// API avaible?
if($get_current_form_api_avaible == "0"){
	echo"API not avaible";
	die;
}

// API Password
if(isset($_POST['inp_api_password'])){
	$inp_api_password = $_POST['inp_api_password'];
	$inp_api_password = output_html($inp_api_password);
}
else{
	$inp_api_password = "";
}
if($get_current_form_api_password != "$inp_api_password"){
	echo"Wrong API password";
	die;
}

// Source
if(isset($_POST['inp_source'])){
	$inp_source = $_POST['inp_source'];
	$inp_source = output_html($inp_source);
}
else{
	$inp_source = "";
}
$inp_source_mysql = quote_smart($link, $inp_source);

// Questions
$query = "SELECT question_id, question_form_id, question_title, question_field_name, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_form_id=$get_current_form_id ORDER BY question_weight ASC";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_question_id, $get_question_form_id, $get_question_title, $get_question_field_name, $get_question_weight, $get_question_type, $get_question_size, $get_question_rows, $get_question_cols, $get_question_required, $get_question_answer) = $row;

	$inp_q = $_POST["inp_q_$get_question_field_name"];
	$inp_q = output_html($inp_q);
	$inp_q_mysql = quote_smart($link, $inp_q);

	$inp_message = $inp_message  . "<p><b>$get_question_title</b><br />\n";
	$inp_message = $inp_message  . "$inp_q</p>\n";
	
	
	// Required?
	if($get_question_required == "1" && $inp_q == ""){
		echo"Please fill inn question $get_question_title";
		die;
	}
}

// No errors so far - create message index
// Message Password
$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
$charactersLength = strlen($characters);
$inp_message_password = '';
for ($i = 0; $i < 6; $i++) {
	$inp_message_password .= $characters[rand(0, $charactersLength - 1)];
}
$inp_message_password_mysql = quote_smart($link, $inp_message_password);

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


// Insert questions to MySQL
$query = "SELECT question_id, question_form_id, question_title, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_form_id=$get_current_form_id ORDER BY question_weight ASC";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_question_id, $get_question_form_id, $get_question_title, $get_question_weight, $get_question_type, $get_question_size, $get_question_rows, $get_question_cols, $get_question_required, $get_question_answer) = $row;

	$inp_q = $_POST["inp_q_$get_question_id"];
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
$message = $message . "<b>Source:</b> $inp_source<br />\n";
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

echo"Message sent";
?>