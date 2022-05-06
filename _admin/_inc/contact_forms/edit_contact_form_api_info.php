<?php
/**
*
* File: _admin/_inc/contact_forms/edit_contact_form_general.php
* Version 1.0.0
* Date 20:20 23.01.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables --------------------------------------------------------------------------- */
$t_contact_forms_index			= $mysqlPrefixSav . "contact_forms_index";
$t_contact_forms_questions		= $mysqlPrefixSav . "contact_forms_questions";
$t_contact_forms_questions_alternatives	= $mysqlPrefixSav . "contact_forms_questions_alternatives";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['form_id'])){
	$form_id = $_GET['form_id'];
	$form_id = output_html($form_id);
}
else{
	$form_id = "";
}
$form_id_mysql = quote_smart($link, $form_id);



// Get contact form
$query = "SELECT form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_api_password, form_ipblock, form_used_times FROM $t_contact_forms_index WHERE form_id=$form_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_form_id, $get_current_form_title, $get_current_form_language, $get_current_form_mail_to, $get_current_form_text_before_form, $get_current_form_text_left_of_form, $get_current_form_text_right_of_form, $get_current_form_text_after_form, $get_current_form_created_datetime, $get_current_form_created_by_user_id, $get_current_form_updated_datetime, $get_current_form_updated_by_user_id, $get_current_form_api_avaible, $get_current_form_api_password, $get_current_form_ipblock, $get_current_form_used_times) = $row;

if($get_current_form_id == ""){
	echo"
	<h1>Form not found</h1>
	";

}
else{

	echo"
	<h1>$get_current_form_title</h1>

	<!-- Where am I? -->
		<p>
		<b>You are here:</b><br />
		<a href=\"index.php?open=contact_forms&amp;editor_language=$editor_language\">Contact forms</a>
		&gt;
		<a href=\"index.php?open=contact_forms&amp;page=open_contact_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">$get_current_form_title</a>
		&gt;
		<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_api_info&amp;form_id=$form_id&amp;editor_language=$editor_language\">API info</a>
		</p>
	<!-- //Where am I? -->


	<p>
	/* Api variables */<br />
    	String apiURL       = &quot;<span style=\"color:red;\">$configSiteURLSav/contact_forms/api</span>&quot;; // Without ending slash<br />
    	String apiPassword  = &quot;<span style=\"color:red;\">$get_current_form_api_password</span>&quot;;<br />
	</p>

	<p>
	private void buttonSubmitClicked() {<br />
        	&nbsp; &nbsp; EditText editTextEmail = findViewById(R.id.editTextEmail);<br />
        	&nbsp; &nbsp; String email = editTextEmail.getText().toString();<br /><br />

        	&nbsp; &nbsp; EditText editTextMessage = findViewById(R.id.editTextMessage);<br />
        	&nbsp; &nbsp; String message = editTextMessage.getText().toString();<br /><br />


       		&nbsp; &nbsp; // Call HTTP request<br />
        	&nbsp; &nbsp; String stringURL    = apiURL + &quot;/post_new_post.php&quot;;<br />
        	&nbsp; &nbsp; String stringMethod = &quot;post&quot;;<br /><br />

        	&nbsp; &nbsp; Map<String, String> data = new HashMap<String, String>();<br />
        	&nbsp; &nbsp; data.put(&quot;inp_form_id&quot;, &quot;<span style=\"color:red;\">$form_id</span>&quot;);<br /><br />
        	&nbsp; &nbsp; data.put(&quot;inp_api_password&quot;, apiPassword);<br />
        	&nbsp; &nbsp; data.put(&quot;inp_source&quot;, &quot;Workout plans and exercises Android app&quot;);<br />";

		$query = "SELECT question_id, question_form_id, question_title, question_field_name, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_form_id=$get_current_form_id ORDER BY question_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_question_id, $get_question_form_id, $get_question_title, $get_question_field_name, $get_question_weight, $get_question_type, $get_question_size, $get_question_rows, $get_question_cols, $get_question_required, $get_question_answer) = $row;

			echo"&nbsp; &nbsp; data.put(&quot;<span style=\"color:red;\">inp_q_$get_question_field_name</span>&quot;, <span style=\"color:green;\">$get_question_field_name</span>); // $get_question_title<br />\n";
		}

        	echo"<br /><br />

        	&nbsp; &nbsp; HttpRequestLongOperation task = new HttpRequestLongOperation(this, stringURL, stringMethod, data, new HttpRequestLongOperation.TaskListener() {<br />
     			&nbsp; &nbsp; &nbsp; &nbsp; @Override<br />
            		&nbsp; &nbsp; &nbsp; &nbsp; public void onFinished(String result) {<br />
                		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; // Do Something after the task has finished<br />
                		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; sendMessageToServerPost();<br />
            		&nbsp; &nbsp; }<br />
        	&nbsp; &nbsp; &nbsp; &nbsp; });<br />
        	&nbsp; &nbsp; task.execute();<br />
    	}
	</p>






	";
} // form found
?>