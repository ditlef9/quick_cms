<?php
/**
*
* File: _admin/_inc/users/anti_spam.php
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
$tabindex = 0;
if(isset($_GET['antispam_question_id'])){
	$antispam_question_id = $_GET['antispam_question_id'];
	$antispam_question_id = output_html($antispam_question_id);
}
else{
	$antispam_question_id = "";
}
if(isset($_GET['antispam_answer_id'])){
	$antispam_answer_id = $_GET['antispam_answer_id'];
	$antispam_answer_id = output_html($antispam_answer_id);
}
else{
	$antispam_answer_id = "";
}

if($action == ""){
	echo"
	<h1>$l_anti_spam</h1>
	
	

	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\" class=\"btn btn_default\">New</a>
	</p>

	<!-- List all anti spam questions -->
	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th scope=\"col\">
		<span>Question</span>
	   </th>
	   <th scope=\"col\">
		<span>Language</span>
	   </th>
	  </tr>
	</thead>
	<tbody>
	";
	
	$query = "SELECT antispam_question_id, antispam_question_language, antispam_question FROM $t_users_antispam_questions ORDER BY antispam_question_language ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_antispam_question_id, $get_antispam_question_language, $get_antispam_question) = $row;

		if(isset($odd) && $odd == false){
			$odd = true;
		}
		else{
			$odd = false;
		}

		echo"
		<tr>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_antispam_question&amp;antispam_question_id=$get_antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">$get_antispam_question</a></span>
		  </td>
		  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
			<span>$get_antispam_question_language
			</span>
		 </td>
		</tr>
		";
	}
	echo"
	 </tbody>
	</table>
	<!-- //List all anti spam questions -->
	";
}
elseif($action == "new"){
	if($process == "1"){
		$inp_question = $_POST['inp_question'];
		$inp_question = output_html($inp_question);
		$inp_question_mysql = quote_smart($link, $inp_question);

		$inp_answers = $_POST['inp_answers'];
		$inp_answers = strtolower($inp_answers);
		$inp_answers = output_html($inp_answers);

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);



		mysqli_query($link, "INSERT INTO $t_users_antispam_questions
		(antispam_question_id, antispam_question_language, antispam_question) 
		VALUES 
		(NULL, $inp_language_mysql, $inp_question_mysql)")
		or die(mysqli_error($link));


		// Get ID
		$query = "SELECT antispam_question_id FROM $t_users_antispam_questions WHERE antispam_question_language=$inp_language_mysql AND antispam_question=$inp_question_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_antispam_question_id) = $row;

		// Insert answers
		$anwers_array = explode("<br />", $inp_answers);
		for($x=0;$x<sizeof($anwers_array);$x++){
			$inp_answer_mysql = quote_smart($link, $anwers_array[$x]);

			mysqli_query($link, "INSERT INTO $t_users_antispam_answers
			(antispam_answer_id, antispam_answer_question_id, antispam_answer_language, antispam_answer) 
			VALUES 
			(NULL, '$get_antispam_question_id', $inp_language_mysql, $inp_answer_mysql)")
			or die(mysqli_error($link));

		}

		$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=antispam_created";
		header("Location: $url");
		exit;
	}

	echo"
	<h1>$l_new_antispam</h1>

	<!-- Form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_question\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>$l_queston:</b><br />
		<input type=\"text\" name=\"inp_question\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_language:</b><br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\">$get_language_active_name</option>\n";
		}
		echo"
		</select>

		<p><b>$l_answers:</b><br />
		$l_you_can_specify_more_than_one_answer_by_adding_a_new_line<br />
		<textarea name=\"inp_answers\" rows=\"5\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
		</p>
		
		<p><input type=\"submit\" value=\"$l_create\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->
	";
}
elseif($action == "edit_antispam_question"){
	$antispam_question_id_mysql = quote_smart($link, $antispam_question_id);
	$query = "SELECT antispam_question_id, antispam_question_language, antispam_question FROM $t_users_antispam_questions WHERE antispam_question_id=$antispam_question_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_antispam_question_id, $get_current_antispam_question_language, $get_current_antispam_question) = $row;
	
	if($get_current_antispam_question_id == ""){
		echo"<h1>Server error 404</h1>";
	}
	else{
		if($process == "1"){
			$inp_question = $_POST['inp_question'];
			$inp_question = output_html($inp_question);
			$inp_question_mysql = quote_smart($link, $inp_question);

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);


			$result = mysqli_query($link, "UPDATE $t_users_antispam_questions SET 
					antispam_question_language=$inp_language_mysql,
					antispam_question=$inp_question_mysql
			 WHERE antispam_question_id=$get_current_antispam_question_id");

			$url = "index.php?open=$open&page=$page&action=$action&antispam_question_id=$antispam_question_id&editor_language=$editor_language&l=$l&ft=success&fm=antispam_created";
			header("Location: $url");
			exit;
		}
		echo"

		<h1>$get_current_antispam_question</h1>


		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_antispam_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"selected\">Question</a></li>
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=answers&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Answers</a></li>
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Delete question</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- Menu -->

		<!-- Form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_question\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>$l_queston:</b><br />
		<input type=\"text\" name=\"inp_question\" value=\"$get_current_antispam_question\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_language:</b><br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\""; if($get_current_antispam_question_language == "$get_language_active_iso_two"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>

		
		<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
		<!-- //Form -->
		";
	}
}
elseif($action == "answers"){
	$antispam_question_id_mysql = quote_smart($link, $antispam_question_id);
	$query = "SELECT antispam_question_id, antispam_question_language, antispam_question FROM $t_users_antispam_questions WHERE antispam_question_id=$antispam_question_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_antispam_question_id, $get_current_antispam_question_language, $get_current_antispam_question) = $row;
	
	if($get_current_antispam_question_id == ""){
		echo"<h1>Server error 404</h1>";
	}
	else{
		if($process == "1"){
			$inp_question = $_POST['inp_question'];
			$inp_question = output_html($inp_question);
			$inp_question_mysql = quote_smart($link, $inp_question);

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);


			$result = mysqli_query($link, "UPDATE $t_users_antispam_questions SET 
					antispam_question_language=$inp_language_mysql,
					antispam_question=$inp_question_mysql
			 WHERE antispam_question_id=$get_current_antispam_question_id");

			$url = "index.php?open=$open&page=$page&action=$action&antispam_question_id=$antispam_question_id&editor_language=$editor_language&l=$l&ft=success&fm=antispam_created";
			header("Location: $url");
			exit;
		}
		echo"

		<h1>$get_current_antispam_question</h1>


		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_antispam_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Question</a></li>
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=answers&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"selected\">Answers</a></li>
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Delete question</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- Menu -->

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_answer&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">$l_new_answer</a>
		</p>

		<!-- List all answers -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_answers</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
		
		
		$query = "SELECT antispam_answer_id, antispam_answer FROM $t_users_antispam_answers WHERE antispam_answer_question_id=$get_current_antispam_question_id ORDER BY antispam_answer ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_antispam_answer_id, $get_antispam_answer) = $row;
			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}

			echo"
			<tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_antispam_answer&amp;antispam_question_id=$get_current_antispam_question_id&amp;antispam_answer_id=$get_antispam_answer_id&amp;editor_language=$editor_language&amp;l=$l\">$get_antispam_answer</a></span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_antispam_answer&amp;antispam_question_id=$get_current_antispam_question_id&amp;antispam_answer_id=$get_antispam_answer_id&amp;editor_language=$editor_language&amp;l=$l\">$l_delete</a>
				</span>
			 </td>
			</tr>
			";
		}
		echo"
		 </tbody>
		</table>
		<!-- //List all answers -->
		";
	}
}
elseif($action == "new_answer"){
	$antispam_question_id_mysql = quote_smart($link, $antispam_question_id);
	$query = "SELECT antispam_question_id, antispam_question_language, antispam_question FROM $t_users_antispam_questions WHERE antispam_question_id=$antispam_question_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_antispam_question_id, $get_current_antispam_question_language, $get_current_antispam_question) = $row;
	
	if($get_current_antispam_question_id == ""){
		echo"<h1>Server error 404</h1>";
	}
	else{
		if($process == "1"){
			$inp_answer = $_POST['inp_answer'];
			$inp_answer = output_html($inp_answer);
			$inp_answer_mysql = quote_smart($link, $inp_answer);



			mysqli_query($link, "INSERT INTO $t_users_antispam_answers
			(antispam_answer_id, antispam_answer_question_id, antispam_answer_language, antispam_answer) 
			VALUES 
			(NULL, '$get_current_antispam_question_id', '$get_current_antispam_question_language', $inp_answer_mysql)")
			or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&action=answers&antispam_question_id=$antispam_question_id&editor_language=$editor_language&l=$l&ft=success&fm=answer_created";
			header("Location: $url");
			exit;
		}
		echo"

		<h1>$get_current_antispam_question</h1>


		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_antispam_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Question</a></li>
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=answers&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"selected\">Answers</a></li>
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Delete question</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- Menu -->
		
		<!-- New form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_answer\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_answer:</b><br />
			<input type=\"text\" name=\"inp_answer\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"$l_create\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
			</form>
		<!-- //New form -->

		";
	}
}
elseif($action == "edit_antispam_answer"){
	$antispam_question_id_mysql = quote_smart($link, $antispam_question_id);
	$query = "SELECT antispam_question_id, antispam_question_language, antispam_question FROM $t_users_antispam_questions WHERE antispam_question_id=$antispam_question_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_antispam_question_id, $get_current_antispam_question_language, $get_current_antispam_question) = $row;
	
	if($get_current_antispam_question_id == ""){
		echo"<h1>Server error 404</h1>";
	}
	else{
		$antispam_answer_id_mysql = quote_smart($link, $antispam_answer_id);
		$query = "SELECT antispam_answer_id, antispam_answer_question_id, antispam_answer_language, antispam_answer FROM $t_users_antispam_answers WHERE antispam_answer_id=$antispam_answer_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_antispam_answer_id, $get_current_antispam_answer_question_id, $get_current_antispam_answer_language, $get_current_antispam_answer) = $row;
	
		if($get_current_antispam_answer_id == ""){
			echo"<h1>Server error 404</h1>";

		}
		else{
			if($process == "1"){
				$inp_answer = $_POST['inp_answer'];
				$inp_answer = output_html($inp_answer);
				$inp_answer_mysql = quote_smart($link, $inp_answer);



				$result = mysqli_query($link, "UPDATE $t_users_antispam_answers SET 
					antispam_answer=$inp_answer_mysql
					 WHERE antispam_answer_id=$get_current_antispam_answer_id");




				$url = "index.php?open=$open&page=$page&action=answers&antispam_question_id=$antispam_question_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
				header("Location: $url");
				exit;
			}
			echo"

			<h1>$get_current_antispam_question</h1>


			<!-- Menu -->
				<div class=\"tabs\">
					<ul>
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_antispam_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Question</a></li>
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=answers&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"selected\">Answers</a></li>
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Delete question</a></li>
					</ul>
				</div>
				<div class=\"clear\"></div>
			<!-- Menu -->
			
			<!-- Edit form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_answer\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;antispam_question_id=$antispam_question_id&amp;antispam_answer_id=$antispam_answer_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>$l_answer:</b><br />
				<input type=\"text\" name=\"inp_answer\" value=\"$get_current_antispam_answer\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				</form>
			<!-- //Edit form -->

			";
		} // a found
	} // q found
}
elseif($action == "delete_antispam_answer"){
	$antispam_question_id_mysql = quote_smart($link, $antispam_question_id);
	$query = "SELECT antispam_question_id, antispam_question_language, antispam_question FROM $t_users_antispam_questions WHERE antispam_question_id=$antispam_question_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_antispam_question_id, $get_current_antispam_question_language, $get_current_antispam_question) = $row;
	
	if($get_current_antispam_question_id == ""){
		echo"<h1>Server error 404</h1>";
	}
	else{
		$antispam_answer_id_mysql = quote_smart($link, $antispam_answer_id);
		$query = "SELECT antispam_answer_id, antispam_answer_question_id, antispam_answer_language, antispam_answer FROM $t_users_antispam_answers WHERE antispam_answer_id=$antispam_answer_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_antispam_answer_id, $get_current_antispam_answer_question_id, $get_current_antispam_answer_language, $get_current_antispam_answer) = $row;
	
		if($get_current_antispam_answer_id == ""){
			echo"<h1>Server error 404</h1>";

		}
		else{
			if($process == "1"){


				$result = mysqli_query($link, "DELETE FROM $t_users_antispam_answers WHERE antispam_answer_id=$get_current_antispam_answer_id");




				$url = "index.php?open=$open&page=$page&action=answers&antispam_question_id=$antispam_question_id&editor_language=$editor_language&l=$l&ft=success&fm=answer_deleted";
				header("Location: $url");
				exit;
			}
			echo"

			<h1>$get_current_antispam_question</h1>


			<!-- Menu -->
				<div class=\"tabs\">
					<ul>
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_antispam_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Question</a></li>
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=answers&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"selected\">Answers</a></li>
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Delete question</a></li>
					</ul>
				</div>
				<div class=\"clear\"></div>
			<!-- Menu -->
			
			<!-- Edit form -->
				<h2>$l_delete $l_answer</h2>

				<p>$l_are_you_sure_you_want_to_delete</p>
			
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;antispam_question_id=$antispam_question_id&amp;antispam_answer_id=$antispam_answer_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_confirm</a>
				</p>
			<!-- //Edit form -->

			";
		} // a found
	} // q found
}
elseif($action == "delete_question"){
	$antispam_question_id_mysql = quote_smart($link, $antispam_question_id);
	$query = "SELECT antispam_question_id, antispam_question_language, antispam_question FROM $t_users_antispam_questions WHERE antispam_question_id=$antispam_question_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_antispam_question_id, $get_current_antispam_question_language, $get_current_antispam_question) = $row;
	
	if($get_current_antispam_question_id == ""){
		echo"<h1>Server error 404</h1>";
	}
	else{
		if($process == "1"){
			$result = mysqli_query($link, "DELETE FROM $t_users_antispam_questions WHERE antispam_question_id=$antispam_question_id_mysql");
			$result = mysqli_query($link, "DELETE FROM $t_users_antispam_answers WHERE antispam_answer_question_id=$antispam_question_id_mysql");




			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=question_deleted";
			header("Location: $url");
			exit;
		}
		echo"

		<h1>$get_current_antispam_question</h1>


		<!-- Menu -->
			<div class=\"tabs\">
					<ul>
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_antispam_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Question</a></li>
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=answers&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"selected\">Answers</a></li>
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_question&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l\">Delete question</a></li>
					</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- Menu -->
			
		<h2>$l_delete $get_current_antispam_question</h2>

		<p>$l_are_you_sure_you_want_to_delete</p>
			
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;antispam_question_id=$antispam_question_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_confirm</a>
		</p>

		";
	} // q found
}
?>