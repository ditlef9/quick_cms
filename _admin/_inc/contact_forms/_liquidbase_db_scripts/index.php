<?php
/**
*
* File: _admin/_inc/chat/_liquibase/talk/001c_talk.php
* Version 1.0.0
* Date 11:46 24.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_contact_forms_index") or die(mysqli_error($link)); 


echo"


	<!-- contact_forms_index -->
	";
	
	$query = "SELECT * FROM $t_contact_forms_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_contact_forms_index: $row_cnt</p>
		";
	}
	else{

		mysqli_query($link, "CREATE TABLE $t_contact_forms_index(
	  	 form_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(form_id), 
	  	   form_title VARCHAR(250),
	  	   form_language VARCHAR(50),
	  	   form_mail_to TEXT,
	  	   form_text_before_form TEXT,
	  	   form_text_left_of_form TEXT,
	  	   form_text_right_of_form TEXT,
	  	   form_text_after_form TEXT,
	  	   form_first_field_name VARCHAR(250),
	  	   form_created_datetime DATETIME,
	  	   form_created_by_user_id INT,
	  	   form_updated_datetime DATETIME,
	  	   form_updated_by_user_id INT,
	  	   form_api_avaible INT,
	  	   form_api_password VARCHAR(250),
	  	   form_ipblock TEXT,
	  	   form_used_times INT)")
		   or die(mysqli_error());

		// Me
		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$query = "SELECT user_id, user_email, user_name, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;

		$inp_my_email_mysql = quote_smart($link, $get_my_user_email);

		$datetime = date("Y-m-d H:i:s");

		// Random password
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    		$charactersLength = strlen($characters);
    		$randa = '';
    		$randb = '';
    		$randc = '';
    		$randd = '';
    		for ($i = 0; $i < 6; $i++) {
        		$randa .= $characters[rand(0, $charactersLength - 1)];
        		$randb .= $characters[rand(0, $charactersLength - 1)];
        		$randc .= $characters[rand(0, $charactersLength - 1)];
        		$randd .= $characters[rand(0, $charactersLength - 1)];
    		}
		$randa_mysql = quote_smart($link, $randa);
		$randb_mysql = quote_smart($link, $randb);
		$randc_mysql = quote_smart($link, $randc);
		$randd_mysql = quote_smart($link, $randd);

		mysqli_query($link, "INSERT INTO $t_contact_forms_index 
		(form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_first_field_name, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_api_password, form_ipblock, form_used_times) 
		VALUES
		(NULL, 'Contact form', 'en', $inp_my_email_mysql, '<h1>Contact form</h1><p>Please feel free to contact us by filling in this form.</p>', '', '', '', 'inp_q_name', '$datetime', 1, '$datetime', 1, 1, $randa_mysql, '', 0),
		(NULL, 'Kontaktskjema', 'no', $inp_my_email_mysql, '<h1>Kontaktskjema</h1><p>Kontakt oss ved å fylle inn skjemaet under.</p>', '', '', '', 'inp_q_name', '$datetime', 1, '$datetime', 1, 1, $randb_mysql, '', 0),
		(NULL, 'App feedback', 'en', $inp_my_email_mysql, '<h1>App feedback</h1><p>Tell us what you think about our app. Email address is optional.</p>', '', '', '', 'inp_q_email', '$datetime', 1, '$datetime', 1, 1, $randc_mysql, '', 0),
		(NULL, 'App tilbakemelding', 'no', $inp_my_email_mysql, '<h1>App tilbakemelding</h1><p>Fortell oss hva du synes om appen vår ved å fylle inn skjemaet under. E-post adresse er valgfritt.</p>', '', '', '', 'inp_q_email', '$datetime', 1, '$datetime', 1, 1, $randd_mysql, '', 0)
		") or die(mysqli_error());
	}


	echo"
	<!-- //contact_forms_index -->


";
?>