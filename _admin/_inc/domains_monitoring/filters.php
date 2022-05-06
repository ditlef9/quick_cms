<?php
/**
*
* File: _admin/_inc/domains_monitoring/filters.php
* Version 09:19 31.08.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions ------------------------------------------------------------------------ */
function fix_utf($value){
	$value = str_replace("ÃƒÂ¸", "ø", $value);
	$value = str_replace("ÃƒÂ¥", "å", $value);

        return $value;
}
function fix_local($value){
	$value = htmlentities($value);

        return $value;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_domains_monitoring_liquidbase		= $mysqlPrefixSav . "domains_monitoring_liquidbase";
$t_domains_monitoring_domains_index		= $mysqlPrefixSav . "domains_monitoring_domains_index";
$t_domains_monitoring_filters_index		= $mysqlPrefixSav . "domains_monitoring_filters_index";
$t_domains_monitoring_filters_keywords		= $mysqlPrefixSav . "domains_monitoring_filters_keywords";

$t_users_groups_index	= $mysqlPrefixSav . "users_groups_index";
$t_users_groups_members	= $mysqlPrefixSav . "users_groups_members";

if($action == ""){
	echo"
	<h1>Filters</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
		&gt;
		<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
		</p>
	<!-- //Where am I? -->


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

	<p>
	<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=new_filter&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New filter</a>
	</p>

	<!-- Filters list -->
		
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>Date</span>
		   </th>
		   <th scope=\"col\">
			<span>Active</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>";

		$query = "SELECT filter_id, filter_title, filter_active, filter_updated_date_saying FROM $t_domains_monitoring_filters_index ORDER BY filter_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_filter_id, $get_filter_title, $get_filter_active, $get_filter_updated_date_saying) = $row;

			echo"
			 <tr>
			  <td>
				<span>
				<a href=\"?open=$open&amp;page=$page&amp;action=open_filter&amp;filter_id=$get_filter_id&amp;l=$l&amp;editor_language=$editor_language\">$get_filter_title</a>
				</span>
			  </td>
			  <td>
				<span>$get_filter_updated_date_saying</span>
			  </td>
			  <td>
				<span>";
				if($get_filter_active == "1"){
					echo"Yes";
				}
				else{
					echo"No";
				}
				echo"</span>
			  </td>
			  <td>
				<span>
				<a href=\"?open=$open&amp;page=$page&amp;action=edit_filter&amp;filter_id=$get_filter_id&amp;l=$l&amp;editor_language=$editor_language\">Edit</a>
				&middot;
				<a href=\"?open=$open&amp;page=$page&amp;action=delete_filter&amp;filter_id=$get_filter_id&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
				</span>
			  </td>
			 </tr>

			";
		} // while

		echo"
		 </tbody>
		</table>

	<!-- //Filters list -->
	";
}
elseif($action == "new_filter"){
	if($process == "1"){
		// Dates
		$date = date("Y-m-d");
		$date_saying = date("j M Y");


		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		// Check duplicates
		$query = "SELECT filter_id FROM $t_domains_monitoring_filters_index WHERE filter_title=$inp_title_mysql AND filter_created_by_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_filter_id) = $row;
		if($get_filter_id != ""){

			// Header
			$url = "index.php?open=domains_monitoring&page=filters&action=new_filter&editor_language=$editor_language&l=$l&ft=error&fm=filter_already_exists";
			header("Location: $url");
			exit;
			
		}

		// Group
		$inp_group_id = $_POST['inp_group_id'];
		$inp_group_id = output_html($inp_group_id);
		$inp_group_id_mysql = quote_smart($link, $inp_group_id);


		// Insert
		mysqli_query($link, "INSERT INTO $t_domains_monitoring_filters_index 
		(filter_id, filter_group_id, filter_title, filter_active, filter_created_date, filter_created_date_saying, 
		filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id) 
		VALUES 
		(NULL, $inp_group_id_mysql, $inp_title_mysql, 1, '$date', '$date_saying', 
		$my_user_id_mysql, '$date', '$date_saying', $my_user_id_mysql)")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT filter_id FROM $t_domains_monitoring_filters_index WHERE filter_title=$inp_title_mysql AND filter_created_by_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_filter_id) = $row;

		// Header
		$url = "index.php?open=domains_monitoring&page=filters&editor_language=$editor_language&l=$l&ft=success&fm=filter_created#filter$get_current_filter_id";
		header("Location: $url");
		exit;

	} // process

	echo"
	<h1>New filter</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
		&gt;
		<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
		&gt;
		<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=new_filter&amp;editor_language=$editor_language&amp;l=$l\">New filter</a>
		</p>
	<!-- //Where am I? -->


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


	<!-- New filter form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=new_filter&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p><b>User group:</b> (<a href=\"index.php?open=users&amp;page=groups&amp;editor_language=$editor_language&amp;l=$l\">Manage groups</a>)<br />
		<select name=\"inp_group_id\">
			<option value=\"0\">-</option>\n";

			// Find my groups


			$query = "SELECT member_id, member_group_id, group_name FROM $t_users_groups_members JOIN $t_users_groups_index ON $t_users_groups_members.member_group_id=$t_users_groups_index.group_id WHERE member_user_id=$get_my_user_id";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_member_id, $get_member_group_id, $get_group_name) = $row;

				echo"			<option value=\"$get_member_group_id\">$get_group_name</option>\n";
			}
			echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Create filter\" class=\"btn_default\" />
		</p>
	
		</form>
	<!-- //New filter form -->
	";
}
elseif($action == "open_filter"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	// Get filter
	$query = "SELECT filter_id, filter_title, filter_group_id, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filter_id, $get_current_filter_title, $get_current_filter_group_id, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
	if($get_current_filter_id == ""){
		echo"Filter not found";
	}
	else{
		echo"
		<h1>$get_current_filter_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=open_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_filter_title</a>
			</p>
		<!-- //Where am I? -->


		<!-- Feedback -->
			";
			if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<p>
		<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=add_keyword_to_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Add keyword</a>
		<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=upload_keyword_list_to_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Upload keyword list</a>
		<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=generate_keywords_combinations&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Generate combinations</a>
		<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=edit_all_keywords&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Edit all keywords</a>
		<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=delete_all_keywords&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Delete all keywords</a>
		</p>

		<!-- Check for unprosessed filter files -->";

			$filenames = "";
			$dir = "../_cache/";
			if ($handle = opendir($dir)) {
				$files = array();   
				while (false !== ($file = readdir($handle))) {
					if ($file === '.') continue;
					if ($file === '..') continue;
					if ($file === "admin_cms") continue;
					if ($file === "login") continue;
					if ($file === "ucp") continue;
					if ($file === "setup") continue;

					array_push($files, $file);
				}
				
				sort($files);
				foreach ($files as $file){

					$file_len = strlen($file);
					if($file_len > 15){
						$file_start = substr($file, 0, 13);
						$file_id = substr($file, 13);
						$file_id = str_replace(".txt", "", $file_id);
						if($file_start == "keyword_list_"){
							echo"<p><a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=process_keyword_list_to_filter&amp;filter_id=$filter_id&amp;keyword_list_id=$file_id&amp;minimum_domain_length=3&amp;combinations=0&amp;type=starts_with_word_ends_with_another_word&amp;domain_tlds=&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\">Process $file_id</a></p>";
						}
					}
				} // foreach
			} // if
			echo"
		<!-- //Check for unprosessed filter files -->

		<!-- Keywords list -->
		
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>Keyword</span>
			   </th>
			   <th scope=\"col\">
				<span>Title</span>
			   </th>
			   <th scope=\"col\">
				<span>Type</span>
			   </th>
			   <th scope=\"col\">
				<span>TLDs</span>
			   </th>
			   <th scope=\"col\">
				<span>Actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>";

			$query = "SELECT keyword_id, keyword_title, keyword_type, keyword_value, keyword_domain_tlds FROM $t_domains_monitoring_filters_keywords WHERE keyword_filter_id=$get_current_filter_id ORDER BY keyword_value ASC LIMIT 0,100";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_keyword_id, $get_keyword_title, $get_keyword_type, $get_keyword_value, $get_keyword_domain_tlds) = $row;

				$get_keyword_type = ucfirst($get_keyword_type);

				echo"
				 <tr>
				  <td>
					<span>$get_keyword_value</span>
			 	 </td>
				  <td>
					<span>$get_keyword_title</span>
			 	 </td>
				  <td>
					<span>
					$get_keyword_type
					</span>
				  </td>
				  <td>
					<span>
					$get_keyword_domain_tlds
					</span>
				  </td>
				  <td>
					<span>
					<a href=\"?open=$open&amp;page=$page&amp;action=edit_keyword&amp;filter_id=$get_current_filter_id&amp;keyword_id=$get_keyword_id&amp;l=$l&amp;editor_language=$editor_language\">Edit</a>
					&middot;
					<a href=\"?open=$open&amp;page=$page&amp;action=delete_keyword&amp;filter_id=$get_current_filter_id&amp;keyword_id=$get_keyword_id&amp;l=$l&amp;editor_language=$editor_language\">Delete</a>
					</span>
				  </td>
				 </tr>

				";
			} // while
			echo"
			 </tbody>
			</table>
		<!-- //Keywords list -->
		";

	} // filter found
} // action == open filter
elseif($action == "add_keyword_to_filter"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	// Get filter
	$query = "SELECT filter_id, filter_title, filter_group_id, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filter_id, $get_current_filter_title, $get_current_filter_group_id, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
	if($get_current_filter_id == ""){
		echo"Filter not found";
	}
	else{
		if($process == "1"){
			$datetime = date("Y-m-d H:i:s");

			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);
			$inp_type_mysql = quote_smart($link, $inp_type);

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_keyword = $_POST['inp_keyword'];
			$inp_keyword = output_html($inp_keyword);
			$inp_keyword_mysql = quote_smart($link, $inp_keyword);

			$inp_domain_tlds = $_POST['inp_domain_tlds'];
			$inp_domain_tlds = output_html($inp_domain_tlds);
			$inp_domain_tlds = str_replace("\n", "", $inp_domain_tlds);
			$inp_domain_tlds = str_replace("<br />", "", $inp_domain_tlds);
			$inp_domain_tlds_mysql = quote_smart($link, $inp_domain_tlds);

			$inp_combinations  = $_POST['inp_combinations'];
			$inp_combinations = output_html($inp_combinations);
			$inp_combinations_mysql = quote_smart($link, $inp_combinations);

			// User
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Check duplicates
			$query = "SELECT keyword_id FROM $t_domains_monitoring_filters_keywords WHERE keyword_type=$inp_type_mysql AND keyword_value=$inp_keyword_mysql AND keyword_filter_id=$get_current_filter_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_keyword_id) = $row;
			if($get_keyword_id != ""){
				$url = "index.php?open=domains_monitoring&page=filters&action=add_keyword_to_filter&filter_id=$get_current_filter_id&editor_language=$editor_language&l=$l&ft=error&fm=keyword_already_exists";
				header("Location: $url");
				exit;
			}
			
			// Insert
			mysqli_query($link, "INSERT INTO $t_domains_monitoring_filters_keywords 
			(keyword_id, keyword_filter_id, keyword_group_id, keyword_user_id, keyword_title, keyword_type, keyword_value, keyword_combinations, keyword_domain_tlds, keyword_added_datetime, keyword_updated_datetime ) 
			VALUES 
			(NULL, $get_current_filter_id, $get_current_filter_group_id, $my_user_id_mysql, $inp_title_mysql, $inp_type_mysql, $inp_keyword_mysql, $inp_combinations_mysql, $inp_domain_tlds_mysql, '$datetime', '$datetime')")
			or die(mysqli_error($link));
			
			
			$url = "index.php?open=domains_monitoring&page=filters&action=add_keyword_to_filter&filter_id=$get_current_filter_id&editor_language=$editor_language&l=$l&ft=success&fm=keyword_added";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_filter_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=open_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_filter_title</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=add_keyword_to_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">Add keyword to filter</a>
			</p>
		<!-- //Where am I? -->


		<!-- Feedback -->
			";
			if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Add keyword form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_keyword\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=add_keyword_to_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Type:</b><br />
			<select name=\"inp_type\">
				<option value=\"contains\">- Keywords: -</option>
				<option value=\"contains\">Contains</option>
				<option value=\"starts_with\">Starts with</option>
				<option value=\"ends_with\">Ends with</option>
				<option value=\"exact\">Exact</option>
				<option value=\"regex\">Regex</option>
				<option value=\"starts_with_word_ends_with_another_word\" selected=\"selected\">Starts with word ends with another word (in keyword list)</option>
				<option value=\"contains\"> </option>
				<option value=\"contains\">- IP/Host: -</option>
				<option value=\"ip_exact\">IP exact</option>
				<option value=\"host_exact\">Host exact</option>
			</select>
			</p>

			<p><b>Keyword:</b><br />
			<input type=\"text\" name=\"inp_keyword\" value=\"\" size=\"25\" />
			</p>

			<p><b>Title (not mandatory):</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
			</p>

			<p><b>Limit domain tlds:</b><br />
			<span class=\"smal\">Example if you only want the keyword to work on .link, .site and .me then write: <em>link, site, me</em><br />
			If you want all domain tlds then leave the textarea blank</span><br />
			<textarea name=\"inp_domain_tlds\" rows=\"5\" cols=\"25\" style=\"width: 100%;\"></textarea>
			</p>

			<p><b>Create combinations:</b><br />
			This will create all combinations of words in dictionary. Example word <em>loan and bank</em> will be 
			<em>loanbank</em>, <em>loan-bank</em>, <em>bankloan</em> and <em>bank-loan</em>. This is good to use with type excact.<br />
			<input type=\"radio\" name=\"inp_combinations\" value=\"1\" checked=\"checked\" /> Yes
			<input type=\"radio\" name=\"inp_combinations\" value=\"0\" /> No
			</p>


			<p>
			<input type=\"submit\" value=\"Add keyword\" class=\"btn_default\" />
			</p>
	
			</form>
		<!-- //Add keyword form -->
		";

	} // filter found
} // action == add_keyword_to_filter
elseif($action == "upload_keyword_list_to_filter"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	// Get filter
	$query = "SELECT filter_id, filter_title, filter_group_id, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filter_id, $get_current_filter_title, $get_current_filter_group_id, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
	if($get_current_filter_id == ""){
		echo"Filter not found";
	}
	else{
		if($process == "1"){


			$inp_domain_tlds = $_POST['inp_domain_tlds'];
			$inp_domain_tlds = output_html($inp_domain_tlds);
			$inp_domain_tlds = str_replace("\n", "", $inp_domain_tlds);
			$inp_domain_tlds = str_replace("<br />", "", $inp_domain_tlds);

			$inp_minimum_domain_length = $_POST['inp_minimum_domain_length'];
			$inp_minimum_domain_length = output_html($inp_minimum_domain_length);
			if($inp_minimum_domain_length == ""){
				echo"Missing inp_minimum_domain_length";
				die;
			}

			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);

			$inp_combinations = $_POST['inp_combinations'];
			$inp_combinations = output_html($inp_combinations);
			

			// Sjekk filen
			$unique_id = date("YmdHis");
			$file_name = basename($_FILES['inp_keyword_list']['name']);
			$file_exp = explode('.', $file_name); 
			$file_type = $file_exp[count($file_exp) -1]; 
			$file_type = strtolower("$file_type");


			// Finnes mappen?
			$year = date("Y");
			$upload_path = "../_cache";

			if(!(is_dir("../_cache"))){
				mkdir("../_cache");
			}



			// Sett variabler
			$new_name = "keyword_list_" . $unique_id . ".txt";
			$target_path = $upload_path . "/" . $new_name;
			
			// Sjekk om det er en OK filendelse
			if($file_type == "txt"){
				if(move_uploaded_file($_FILES['inp_keyword_list']['tmp_name'], $target_path)) {
					$url = "index.php?open=domains_monitoring&page=filters&action=process_keyword_list_to_filter&filter_id=$get_current_filter_id&editor_language=$editor_language&l=$l&keyword_list_id=$unique_id&domain_tlds=$inp_domain_tlds&minimum_domain_length=$inp_minimum_domain_length&type=$inp_type&combinations=$inp_combinations&process=1";
					header("Location: $url");
					exit;
				}
				else{
					switch ($_FILES['inp_keyword_list']['error']) {
					case UPLOAD_ERR_OK:
           					$fm = "unknown_error";
						break;
					case UPLOAD_ERR_NO_FILE:
           					$fm = "no_file_selected";
						break;
					case UPLOAD_ERR_INI_SIZE:
           					$fm = "file_exceeds_filesize_at_server";
						break;
					case UPLOAD_ERR_FORM_SIZE:
           					$fm_front = "file_exceeds_filesize_form";
						break;
					default:
           					$fm_front = "unknown_upload_error";
						break;

					}
					$ft = "warning";
					
						
					// Send feedback
					$url = "index.php?open=domains_monitoring&page=filters&action=upload_keyword_list_to_filter&filter_id=$get_current_filter_id&editor_language=$editor_language&l=$l&ft=$ft&fm=$fm"; 
					header("Location: $url");
					exit;
				}
			} // $file_type == "txt"
			else{
				$url = "index.php?open=domains_monitoring&page=filters&action=upload_keyword_list_to_filter&filter_id=$get_current_filter_id&editor_language=$editor_language&l=$l&ft=error&fm=wrong_file_type";
				header("Location: $url");
				exit;
			}
		}
		echo"
		<h1>$get_current_filter_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=open_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_filter_title</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=upload_keyword_list_to_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">Upload keyword list to filter</a>
			</p>
		<!-- //Where am I? -->


		<!-- Feedback -->
			";
			if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- upload_keyword_list_to_filter form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_keyword\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=upload_keyword_list_to_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>Keyword list (.txt):</b><br />
			Each keyword is seperated by line shift<br />
			&aelig;, 	&oslash;, &aring; will be replaced with ae, o, a.<br />
			<input type=\"file\" name=\"inp_keyword_list\" />
			</p>

			<p><b>Limit domain tlds:</b><br />
			<span class=\"smal\">Example if you only want the keyword to work on .link, .site and .me then write: <em>link, site, me</em><br />
			If you want all domain tlds then leave the textarea blank</span><br />
			<textarea name=\"inp_domain_tlds\" rows=\"5\" cols=\"25\" style=\"width: 100%;\"></textarea>
			</p>

			<p><b>Minimum domain length:</b><br />
			<input type=\"text\" name=\"inp_minimum_domain_length\" size=\"4\" value=\"3\" />
			</p>

			<p><b>Type:</b><br />
			<select name=\"inp_type\">
				<option value=\"contains\">- Keywords: -</option>
				<option value=\"contains\">Contains</option>
				<option value=\"starts_with\">Starts with</option>
				<option value=\"ends_with\">Ends with</option>
				<option value=\"exact\">Exact</option>
				<option value=\"regex\">Regex</option>
				<option value=\"starts_with_word_ends_with_another_word\" selected=\"selected\">Starts with word ends with another word (in keyword list)</option>
				<option value=\"contains\"> </option>
				<option value=\"contains\">- IP/Host: -</option>
				<option value=\"ip_exact\">IP exact</option>
				<option value=\"host_exact\">Host exact</option>
			</select>
			</p>


			<p><b>Create combinations:</b><br />
			This will create all combinations of words in dictionary. Example word <em>loan and bank</em> will be 
			<em>loanbank</em>, <em>loan-bank</em>, <em>bankloan</em> and <em>bank-loan</em>. This is good to use with type excact.<br />
			<input type=\"radio\" name=\"inp_combinations\" value=\"1\" /> Yes
			<input type=\"radio\" name=\"inp_combinations\" value=\"0\" checked=\"checked\" /> No
			</p>

			<p>
			<input type=\"submit\" value=\"Upload keyword list\" class=\"btn_default\" />
			</p>
	
			</form>
		<!-- //upload_keyword_list_to_filter form -->
		";

	} // filter found
} // action == upload_keyword_list_to_filter
elseif($action == "process_keyword_list_to_filter"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	if (isset($_GET['keyword_list_id'])) {
		$keyword_list_id = $_GET['keyword_list_id'];
		$keyword_list_id = stripslashes(strip_tags($keyword_list_id));
		if(!(is_numeric($keyword_list_id))){
			echo"keyword_list_id not numeric";
			die;
		}
	}
	else{
		echo"Missing keyword_list_id";
		die;
	}

	if (isset($_GET['minimum_domain_length'])) {
		$minimum_domain_length = $_GET['minimum_domain_length'];
		$minimum_domain_length = stripslashes(strip_tags($minimum_domain_length));
		if(!(is_numeric($minimum_domain_length))){
			echo"minimum_domain_length not numeric";
			die;
		}
	}
	else{
		echo"Missing minimum_domain_length";
		die;
	}
	if (isset($_GET['combinations'])) {
		$combinations = $_GET['combinations'];
		$combinations = stripslashes(strip_tags($combinations));
		if(!(is_numeric($combinations))){
			echo"combinations not numeric";
			die;
		}
	}
	else{
		echo"Missing combinations";
		die;
	}
	$combinations_mysql = quote_smart($link, $combinations);

	if (isset($_GET['total_lines_initially'])) {
		$total_lines_initially = $_GET['total_lines_initially'];
		$total_lines_initially = stripslashes(strip_tags($total_lines_initially));
		if(!(is_numeric($total_lines_initially))){
			echo"total_lines_initially not numeric";
			die;
		}
	}
	else{
		$total_lines_initially = -1;
	}
	if (isset($_GET['total_lines_processed'])) {
		$total_lines_processed = $_GET['total_lines_processed'];
		$total_lines_processed = stripslashes(strip_tags($total_lines_processed));
		if(!(is_numeric($total_lines_processed))){
			echo"total_lines_processed not numeric";
			die;
		}
	}
	else{
		$total_lines_processed = 0;
	}
	$total_lines_processed_num_format = number_format($total_lines_processed, 0, ',', ' ');

	// Get filter
	$query = "SELECT filter_id, filter_title, filter_group_id, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filter_id, $get_current_filter_title, $get_current_filter_group_id, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
	if($get_current_filter_id == ""){
		echo"Filter not found";
	}
	else{
		if($process == "1"){
			// Vars
			$datetime = date("Y-m-d H:i:s");


			$inp_type = $_GET['type'];
			$inp_type = output_html($inp_type);
			$inp_type_mysql = quote_smart($link, $inp_type);

			$inp_domain_tlds = $_GET['domain_tlds'];
			$inp_domain_tlds = output_html($inp_domain_tlds);
			$inp_domain_tlds = str_replace("\n", "", $inp_domain_tlds);
			$inp_domain_tlds = str_replace("<br />", "", $inp_domain_tlds);
			$inp_domain_tlds_mysql = quote_smart($link, $inp_domain_tlds);

			// User
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Find list
			$list = "keyword_list_" . $keyword_list_id . ".txt";
			if(file_exists("../_cache/$list")){
				// Read file
				$myfile = fopen("../_cache/$list", "r") or die("Unable to open file!");
				$data = fread($myfile,filesize("../_cache/$list"));
				fclose($myfile);

				
				// Make read array
				$words = explode("\n", $data);
				$words_length = sizeof($words);
				if($total_lines_initially == "-1"){
					$total_lines_initially = $words_length;
				}
				$words_length_num_format = number_format($words_length, 0, ',', ' ');
				$total_lines_initially_num_format = number_format($total_lines_initially, 0, ',', ' ');

				// Start stop
				$lines_to_read = 100;
				if($lines_to_read > $words_length){
					$lines_to_read = "$words_length";
				}

				// Remaining
				$remaining_lines = $words_length-$lines_to_read;
				$remaining_lines_num_format = number_format($remaining_lines, 0, ',', ' ');
				
				// Echo
				$percentage = round(($total_lines_processed/$total_lines_initially)*100);
				echo"
				<table>
				 <tr>
				  <td>
					<img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" />
				  </td>
				  <td>
					<p>
					File: <a href=\"../_cache/$list\">../_cache/$list</a><br />
					Length: $words_length_num_format<br />
					Total lines initially = $total_lines_initially_num_format<br />
					Total lines processed: $total_lines_processed_num_format<br />
					Remaining lines = $remaining_lines_num_format<br />
					Percentage done = $percentage %<br />
					Lines to read: $lines_to_read<br />
					</p>
				  </td>
				 </tr>
				</table>

				<p>
				";



				$words_inserted_counter = 0;
				for($x=0;$x<$lines_to_read;$x++){
	
					$inp_keyword = "$words[$x]";
					$inp_keyword = str_replace("æ", "ae", $inp_keyword);
					$inp_keyword = str_replace("ø", "o", $inp_keyword);
					$inp_keyword = str_replace("å", "a", $inp_keyword);
					$inp_keyword = str_replace("é", "e", $inp_keyword);
					$inp_keyword = str_replace("É", "e", $inp_keyword);
					$inp_keyword = trim($inp_keyword);
					$inp_keyword = strtolower($inp_keyword);
					$inp_keyword = output_html($inp_keyword);
					$inp_keyword_mysql = quote_smart($link, $inp_keyword);

					$inp_keyword_len = strlen($inp_keyword);
					$inp_keyword_len = output_html($inp_keyword_len);
					$inp_keyword_len_mysql = quote_smart($link, $inp_keyword_len);

					if($inp_keyword != "" && $minimum_domain_length < $inp_keyword_len){

						// Check if exists
						$query_c = "SELECT keyword_id FROM $t_domains_monitoring_filters_keywords WHERE keyword_filter_id=$get_current_filter_id AND keyword_value=$inp_keyword_mysql";
						$result_c = mysqli_query($link, $query_c);
						$row_c = mysqli_fetch_row($result_c);
						list($get_check_keyword_id) = $row_c;
						if($get_check_keyword_id == ""){
							// Insert
							mysqli_query($link, "INSERT INTO $t_domains_monitoring_filters_keywords 
							(keyword_id, keyword_filter_id, keyword_group_id, keyword_user_id, keyword_type, keyword_combinations, keyword_value, keyword_value_length, keyword_domain_tlds, keyword_added_datetime, keyword_updated_datetime ) 
							VALUES 
							(NULL, $get_current_filter_id, $get_current_filter_group_id, $my_user_id_mysql, $inp_type_mysql, $combinations_mysql, $inp_keyword_mysql, $inp_keyword_len_mysql, $inp_domain_tlds_mysql, '$datetime', '$datetime')")
							or die(mysqli_error($link));
						}
					}
					if($x < 10){
						echo"$inp_keyword<br />\n";

					}

					$words_inserted_counter++;
				}
				echo"

				</p>
				";

				// Write rest of data to a new text file
				$myfile = fopen("../_cache/$list", "w+") or die("Unable to open file!");
				fwrite($myfile, "");
				for($x=$lines_to_read;$x<$words_length;$x++){
					$words[$x]= trim($words[$x]);
					if($words[$x] != ""){
						fwrite($myfile, "$words[$x]\n");
					}
				} // for rest of data
				fclose($myfile);
				
				// Move to read from file
				if($words_inserted_counter == "0"){
					// Finished
					$url = "index.php?open=domains_monitoring&page=filters&action=open_filter&filter_id=$get_current_filter_id&l=$l&editor_language=$editor_language";
					echo"<meta http-equiv=\"refresh\" content=\"1; url=$url\" />";
					exit;
					
				}
				else{
					$total_lines_processed = $total_lines_processed+$words_inserted_counter;
					
					$url = "index.php?open=domains_monitoring&page=filters&action=process_keyword_list_to_filter&filter_id=$get_current_filter_id&keyword_list_id=$keyword_list_id&domain_tlds=$inp_domain_tlds&l=$l&combinations=$combinations&editor_language=$editor_language&amp;minimum_domain_length=$minimum_domain_length&amp;type=$inp_type&amp;total_lines_initially=$total_lines_initially&amp;total_lines_processed=$total_lines_processed&amp;process=1";
					echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\" />";
					exit;
				}


			} // list found
			else{
				echo"Keyword list no found";
				die;
			}
		} // process

	} // filter found
} // action == process_keyword_list_to_filter
elseif($action == "generate_keywords_combinations"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	if (isset($_GET['debug'])) {
		$debug = $_GET['debug'];
		$debug = output_html($debug);
		if(!(is_numeric($debug))){
			echo"debug not numeric";
			die;
		}
	}
	else{
		$debug = 0;
	}
	if (isset($_GET['start_from'])) {
		$start_from = $_GET['start_from'];
		$start_from = output_html($start_from);
		if(!(is_numeric($start_from))){
			echo"start_from not numeric";
			die;
		}
	}
	else{
		$start_from = 0;
	}
	if (isset($_GET['stop_from'])) {
		$stop_from = $_GET['stop_from'];
		$stop_from = output_html($stop_from);
		if(!(is_numeric($stop_from))){
			echo"stop_from not numeric";
			die;
		}
	}
	else{
		// Count stop from 
		$query = "SELECT count(keyword_id) FROM $t_domains_monitoring_filters_keywords WHERE keyword_filter_id=$filter_id_mysql AND keyword_combinations=1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($stop_from) = $row;
	}
	if($start_from == "$stop_from"){
		echo"We need to stop";
		$url = "index.php?open=domains_monitoring&page=filters&action=open_filter&filter_id=$filter_id&debug=$debug&l=$l&editor_language=$editor_language";
		echo"<meta http-equiv=\"refresh\" content=\"5; url=$url\" />";
		die;
	}



	if (isset($_GET['start_with'])) {
		$start_with = $_GET['start_with'];
		$start_with = output_html($start_with);
		if(!(is_numeric($start_with))){
			echo"start_with not numeric";
			die;
		}
	}
	else{
		$start_with = 0;
	}
	if($start_with > "$stop_from"){
		echo"We need to stop and go to next start_from because all the keywords added at a later state is combined keywords";
		$next_start_from = $start_from+1;
		$url = "index.php?open=domains_monitoring&page=filters&action=generate_keywords_combinations&filter_id=$filter_id&start_from=$next_start_from&stop_from=$stop_from&debug=$debug&l=$l&editor_language=$editor_language&amp;process=1";
		echo"<meta http-equiv=\"refresh\" content=\"1; url=$url\" />";
		die;
	}

	// Write to textfile (so we can continue later on)
	
	

	// Percentage
	$percentage_from = round(($start_from/$stop_from)*100, 0);
	$percentage_with = round(($start_with/$stop_from)*100, 0);


	// Dates
	$datetime = date("Y-m-d H:i:s");

	// Header
	echo"<!DOCTYPE html>
<html lang=\"$editor_language\">
<head>
	<title>$start_from of $stop_from ($percentage_from %)</title>
</head>
<body>
	<h1>Combine</h1>

	<p>
	<span style=\"font-size: 110%;\">From: $start_from of $stop_from <b>$percentage_from %</b></span><br />
	With: $start_with <b>$percentage_with %</b><br />
	</p>

	<p>
	<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=generate_keywords_combinations_pause&amp;filter_id=$filter_id&amp;start_from=$start_from&amp;stop_from=$stop_from&amp;start_with=$start_with&amp;l=$l&amp;editor_language=$editor_language\">Pause insert</a>
	</p>
	";
	


	// Get filter
	$query = "SELECT filter_id, filter_title, filter_group_id, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filter_id, $get_current_filter_title, $get_current_filter_group_id, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
	if($get_current_filter_id == ""){
		echo"Filter not found";
	}
	else{
		// Find from keyword
		$query = "SELECT keyword_id, keyword_filter_id, keyword_type, keyword_value, keyword_domain_tlds, keyword_added_datetime, keyword_updated_datetime FROM $t_domains_monitoring_filters_keywords WHERE keyword_filter_id=$get_current_filter_id ORDER BY keyword_id ASC LIMIT $start_from,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_keyword_id, $get_current_keyword_filter_id, $get_current_keyword_type, $get_current_keyword_value, $get_current_keyword_domain_tlds, $get_current_keyword_added_datetime, $get_current_keyword_updated_datetime) = $row;
		if($get_current_keyword_id == ""){
			// Finished
			echo"We are finished!";
			$url = "index.php?open=domains_monitoring&page=filters&action=open_filter&filter_id=$get_current_filter_id&debug=$debug&l=$l&editor_language=$editor_language";
			echo"<meta http-equiv=\"refresh\" content=\"5; url=$url\" />";
			exit;
		}
		else{
			// Ready variables
			$inp_type_mysql = quote_smart($link, $get_current_keyword_type);
			$inp_domain_tlds_mysql = quote_smart($link, $get_current_keyword_domain_tlds);

			// Header
			if($debug == "1"){
				echo"
				<h2>$get_current_keyword_value</h2>
				";
			}

			// Find all other keywords this will have combination with
			$count_keywords_to_combine = 0;
			$query_k = "SELECT keyword_id, keyword_type, keyword_value, keyword_domain_tlds, keyword_notes FROM $t_domains_monitoring_filters_keywords WHERE keyword_filter_id=$get_current_filter_id ORDER BY keyword_id ASC LIMIT $start_with,10000";
			$result_k = mysqli_query($link, $query_k);
			while($row_k = mysqli_fetch_row($result_k)) {
				list($get_keyword_id, $get_keyword_type, $get_keyword_value, $get_keyword_domain_tlds, $get_keyword_notes) = $row_k;

				if($get_keyword_notes == "combined"){
					// Body
					if($debug == "1"){
						echo"
						<p>
						<b>Keyword to combine value:</b> $get_keyword_value<br />
						<span style=\"color: red;\">Skip because is marked combined</span><br />
						";
					}

				}
				else{
					// Body
					if($debug == "1"){
						echo"
						<p>
						<b>Keyword to combine value:</b> $get_keyword_value<br />
						<b>Notes:</b> $get_keyword_notes<br />
						";
					}


					$inp_keyword_value_a = $get_current_keyword_value . $get_keyword_value;
					$inp_keyword_value_a = output_html($inp_keyword_value_a);
					$inp_keyword_value_a_mysql = quote_smart($link, $inp_keyword_value_a);

					$inp_keyword_len_a = strlen($inp_keyword_value_a);
					$inp_keyword_len_a_mysql = quote_smart($link, $inp_keyword_len_a);

					$inp_keyword_value_b = $get_current_keyword_value . "-" . $get_keyword_value;
					$inp_keyword_value_b = output_html($inp_keyword_value_b);
					$inp_keyword_value_b_mysql = quote_smart($link, $inp_keyword_value_b);
	
					$inp_keyword_len_b = strlen($inp_keyword_value_b);
					$inp_keyword_len_b_mysql = quote_smart($link, $inp_keyword_len_b);

					$inp_keyword_value_c = $get_current_keyword_value . "_" . $get_keyword_value;
					$inp_keyword_value_c = output_html($inp_keyword_value_c);
					$inp_keyword_value_c_mysql = quote_smart($link, $inp_keyword_value_c);

					$inp_keyword_len_c = strlen($inp_keyword_value_c);
					$inp_keyword_len_c_mysql = quote_smart($link, $inp_keyword_len_c);

					$inp_keyword_value_d = $get_current_keyword_value . "s" . $get_keyword_value;
					$inp_keyword_value_d = output_html($inp_keyword_value_d);
					$inp_keyword_value_d_mysql = quote_smart($link, $inp_keyword_value_d);

					$inp_keyword_len_d = strlen($inp_keyword_value_d);
					$inp_keyword_len_d_mysql = quote_smart($link, $inp_keyword_len_d);


					// Insert A
					if($debug == "1"){
						echo"
						<b>Combination A:</b> $inp_keyword_value_a<br />
						<b>Combination B:</b> $inp_keyword_value_b<br />
						<b>Combination C:</b> $inp_keyword_value_c<br />
						<b>Combination D:</b> $inp_keyword_value_d<br />
						";
					}
					
					mysqli_query($link, "INSERT INTO $t_domains_monitoring_filters_keywords 
						(keyword_id, keyword_filter_id, keyword_group_id, keyword_user_id, keyword_type, 
						keyword_value, keyword_value_length, keyword_domain_tlds, keyword_added_datetime, keyword_updated_datetime, 
						keyword_notes) 
						VALUES 
					(NULL, $get_current_filter_id, $get_current_filter_group_id, $my_user_id_mysql, $inp_type_mysql, 
						$inp_keyword_value_a_mysql, $inp_keyword_len_a_mysql, $inp_domain_tlds_mysql, '$datetime', '$datetime',
						'combined'), 
					(NULL, $get_current_filter_id, $get_current_filter_group_id, $my_user_id_mysql, $inp_type_mysql, 
						$inp_keyword_value_b_mysql, $inp_keyword_len_b_mysql, $inp_domain_tlds_mysql, '$datetime', '$datetime',
						'combined'),
					(NULL, $get_current_filter_id, $get_current_filter_group_id, $my_user_id_mysql, $inp_type_mysql, 
						$inp_keyword_value_c_mysql, $inp_keyword_len_c_mysql, $inp_domain_tlds_mysql, '$datetime', '$datetime',
						'combined'),
					(NULL, $get_current_filter_id, $get_current_filter_group_id, $my_user_id_mysql, $inp_type_mysql, 
						$inp_keyword_value_d_mysql, $inp_keyword_len_d_mysql, $inp_domain_tlds_mysql, '$datetime', '$datetime',
						'combined')")
						or die(mysqli_error($link));
				
					// Foot
					if($debug == "1"){
						echo"<br />
						</p>
						";
					}
				} // not combined
				$count_keywords_to_combine++;		
			} // while keywords to combine

			if($count_keywords_to_combine == "0"){
				// Next
				if($debug == "1"){
					echo"Next keyword number please!";
				}
				$next_start_from = $start_from+1;
				$url = "index.php?start_from=$next_start_from&stop_from=$stop_from&open=domains_monitoring&page=filters&action=generate_keywords_combinations&filter_id=$get_current_filter_id&l=$l&editor_language=$editor_language&amp;process=1";
				echo"<meta http-equiv=\"refresh\" content=\"1; url=$url\" />";
				exit;
			}
		} // keyword found

		// Next keyword
		if($debug == "1"){
			echo"<div style=\"text-align:center;\"><h3>Next keyword!</h3><p>Insertet $count_keywords_to_combine combinations.</p></div>";

		}
		$next_start_with = $start_with+10000;
		$url = "index.php?start_from=$start_from&amp;stop_from=$stop_from&amp;start_with=$next_start_with&amp;open=domains_monitoring&amp;page=filters&amp;action=generate_keywords_combinations&amp;filter_id=$get_current_filter_id&amp;debug=$debug&amp;l=$l&amp;editor_language=$editor_language&amp;process=1";
		echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\" />";
		
		// Footer
		echo"</body>
</html>";
			

	} // filter found
} // action == "create combinations"
elseif($action == "delete_all_keywords"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	// Get filter
	$query = "SELECT filter_id, filter_title, filter_group_id, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filter_id, $get_current_filter_title, $get_current_filter_group_id, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
	if($get_current_filter_id == ""){
		echo"Filter not found";
	}
	else{
		if($process == "1"){

			mysqli_query($link, "DELETE FROM $t_domains_monitoring_filters_keywords WHERE keyword_filter_id=$get_current_filter_id") or die(mysqli_error($link));

			$url = "index.php?open=domains_monitoring&page=filters&action=open_filter&filter_id=$get_current_filter_id&editor_language=$editor_language&l=$l&ft=success&fm=keywords_deleted";
			header("Location: $url");
			exit;
			
		}
		echo"
		<h1>$get_current_filter_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=open_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_filter_title</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=delete_all_keywords&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">Delete all keywords</a>
			</p>
		<!-- //Where am I? -->


		<!-- Feedback -->
			";
			if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- delete_all_keywords form -->
			<p>
			Are you sure you want to delete all keywords in list?
			</p>
			
			<p>
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=delete_all_keywords&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_defaul\">Confirm</a>
			</p>
		<!-- //delete_all_keywords form -->
		";

	} // filter found
} // action == delete_all_keywords
elseif($action == "edit_keyword"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	if (isset($_GET['keyword_id'])) {
		$keyword_id = $_GET['keyword_id'];
		$keyword_id = stripslashes(strip_tags($keyword_id));
		if(!(is_numeric($keyword_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$keyword_id_mysql = quote_smart($link, $keyword_id);


	// Get keyword
	$query = "SELECT keyword_id, keyword_filter_id, keyword_type, keyword_value, keyword_combinations, keyword_domain_tlds, keyword_added_datetime, keyword_updated_datetime FROM $t_domains_monitoring_filters_keywords WHERE keyword_id=$keyword_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_keyword_id, $get_current_keyword_filter_id, $get_current_keyword_type, $get_current_keyword_value, $get_current_keyword_combinations, $get_current_keyword_domain_tlds, $get_current_keyword_added_datetime, $get_current_keyword_updated_datetime ) = $row;
	if($get_current_keyword_id == ""){
		echo"Keyword not found";
	}
	else{

		// Get filter
		$query = "SELECT filter_id, filter_title, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_filter_id, $get_current_filter_title, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
		if($get_current_filter_id == ""){
			echo"Filter not found";
		}
		else{
			if($process == "1"){
				$datetime = date("Y-m-d H:i:s");
	
				$inp_type = $_POST['inp_type'];
				$inp_type = output_html($inp_type);
				$inp_type_mysql = quote_smart($link, $inp_type);

				$inp_keyword = $_POST['inp_keyword'];
				$inp_keyword = output_html($inp_keyword);
				$inp_keyword_mysql = quote_smart($link, $inp_keyword);

				$inp_domain_tlds = $_POST['inp_domain_tlds'];
				$inp_domain_tlds = output_html($inp_domain_tlds);
				$inp_domain_tlds = str_replace("\n", "", $inp_domain_tlds);
				$inp_domain_tlds = str_replace("<br />", "", $inp_domain_tlds);
				$inp_domain_tlds_mysql = quote_smart($link, $inp_domain_tlds);

				$inp_combinations = $_POST['inp_combinations'];
				$inp_combinations = output_html($inp_combinations);
				$inp_combinations_mysql = quote_smart($link, $inp_combinations);

			
				// Update
				mysqli_query($link, "UPDATE $t_domains_monitoring_filters_keywords SET 
							keyword_type=$inp_type_mysql, 
							keyword_value=$inp_keyword_mysql,
							keyword_combinations=$inp_combinations_mysql,
							keyword_domain_tlds=$inp_domain_tlds_mysql,
							keyword_updated_datetime='$datetime'
							WHERE keyword_id=$get_current_keyword_id") or die(mysqli_error($link));
			
			
				$url = "index.php?open=domains_monitoring&page=filters&action=edit_keyword&filter_id=$get_current_filter_id&keyword_id=$get_current_keyword_id&editor_language=$editor_language&l=$l&ft=success&fm=keyword_edited";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$get_current_keyword_value</h1>


			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
				&gt;
				<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
				&gt;
				<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=open_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_filter_title</a>
				&gt;
				<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=edit_keyword&amp;filter_id=$get_current_filter_id&amp;keyword_id=$get_current_keyword_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_keyword_value</a>
				</p>
			<!-- //Where am I? -->


			<!-- Feedback -->
			";
			if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->

			<!-- Edit keyword form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_keyword\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=edit_keyword&amp;filter_id=$get_current_filter_id&amp;keyword_id=$get_current_keyword_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>Type:</b><br />
				<select name=\"inp_type\">
					<option value=\"contains\">- Keywords: -</option>
					<option value=\"contains\""; if($get_current_keyword_type == "contains"){ echo" selected=\"selected\""; } echo">Contains</option>
					<option value=\"starts_with\""; if($get_current_keyword_type == "starts_with"){ echo" selected=\"selected\""; } echo">Starts with</option>
					<option value=\"ends_with\""; if($get_current_keyword_type == "ends_with"){ echo" selected=\"selected\""; } echo">Ends with</option>
					<option value=\"exact\""; if($get_current_keyword_type == "exact"){ echo" selected=\"selected\""; } echo">Exact</option>
					<option value=\"regex\""; if($get_current_keyword_type == "regex"){ echo" selected=\"selected\""; } echo">Regex</option>
					<option value=\"starts_with_word_ends_with_another_word\""; if($get_current_keyword_type == "starts_with_word_ends_with_another_word"){ echo" selected=\"selected\""; } echo">Starts with word ends with another word (in keyword list)</option>
					<option value=\"contains\"> </option>
					<option value=\"contains\">- IP/Host: -</option>
					<option value=\"ip_exact\""; if($get_current_keyword_type == "ip_exact"){ echo" selected=\"selected\""; } echo">IP exact</option>
					<option value=\"host_exact\""; if($get_current_keyword_type == "host_exact"){ echo" selected=\"selected\""; } echo">Host exact</option>
				</select>
				</p>

				<p><b>Keyword:</b><br />
				<input type=\"text\" name=\"inp_keyword\" value=\"$get_current_keyword_value\" size=\"25\" />
				</p>

				<p><b>Limit domain tlds:</b><br />
				<span class=\"smal\">Example if you only want the keyword to work on .link, .site and .me then write: <em>link, site, me</em><br />
				If you want all domain tlds then leave the textarea blank</span><br />
				<textarea name=\"inp_domain_tlds\" rows=\"5\" cols=\"25\" style=\"width: 100%;\">$get_current_keyword_domain_tlds</textarea>
				</p>

				<p><b>Create combinations:</b><br />
				This will create all combinations of words in dictionary. Example word <em>loan and bank</em> will be 
				<em>loanbank</em>, <em>loan-bank</em>, <em>bankloan</em> and <em>bank-loan</em>. This is good to use with type excact.<br />
				<input type=\"radio\" name=\"inp_combinations\" value=\"1\""; if($get_current_keyword_combinations == "1"){ echo" checked=\"checked\""; } echo" /> Yes
				<input type=\"radio\" name=\"inp_combinations\" value=\"0\""; if($get_current_keyword_combinations == "1"){ echo" checked=\"checked\""; } echo" /> No
				</p>

				<p>
				<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" />
				</p>
	
				</form>
			<!-- //Edit keyword form -->
			";
		} // filter found
	} // keyword found
} // action == edit_keyword
elseif($action == "delete_keyword"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	if (isset($_GET['keyword_id'])) {
		$keyword_id = $_GET['keyword_id'];
		$keyword_id = stripslashes(strip_tags($keyword_id));
		if(!(is_numeric($keyword_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$keyword_id_mysql = quote_smart($link, $keyword_id);


	// Get keyword
	$query = "SELECT keyword_id, keyword_filter_id, keyword_type, keyword_value, keyword_added_datetime, keyword_updated_datetime FROM $t_domains_monitoring_filters_keywords WHERE keyword_id=$keyword_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_keyword_id, $get_current_keyword_filter_id, $get_current_keyword_type, $get_current_keyword_value, $get_current_keyword_added_datetime, $get_current_keyword_updated_datetime ) = $row;
	if($get_current_keyword_id == ""){
		echo"Keyword not found";
	}
	else{

		// Get filter
		$query = "SELECT filter_id, filter_title, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_filter_id, $get_current_filter_title, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
		if($get_current_filter_id == ""){
			echo"Filter not found";
		}
		else{
			if($process == "1"){
				// Delete
				mysqli_query($link, "DELETE FROM $t_domains_monitoring_filters_keywords WHERE keyword_id=$get_current_keyword_id") or die(mysqli_error($link));
			
			
				$url = "index.php?open=domains_monitoring&page=filters&action=open_filter&filter_id=$get_current_filter_id&editor_language=$editor_language&l=$l&ft=success&fm=keyword_deleted";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$get_current_keyword_value</h1>


			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
				&gt;
				<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
				&gt;
				<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=open_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_filter_title</a>
				&gt;
				<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=deleted_keyword&amp;filter_id=$get_current_filter_id&amp;keyword_id=$get_current_keyword_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_keyword_value</a>
				</p>
			<!-- //Where am I? -->


			<!-- Feedback -->
			";
			if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->

			<!-- Deleted keyword form -->
				<p>Are you sure you want to delete the keyword?</p>

				<p>
				<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=delete_keyword&amp;filter_id=$get_current_filter_id&amp;keyword_id=$get_current_keyword_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
				</p>

			<!-- //Deleted keyword form -->
			";
		} // filter found
	} // keyword found
} // action == delete_keyword
elseif($action == "edit_filter"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	// Get filter
	$query = "SELECT filter_id, filter_title, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filter_id, $get_current_filter_title, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
	if($get_current_filter_id == ""){
		echo"Filter not found";
	}
	else{
		if($process == "1"){
			// Dates
			$date = date("Y-m-d");
			$date_saying = date("j M Y");

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_active = $_POST['inp_active'];
			$inp_active = output_html($inp_active);
			$inp_active_mysql = quote_smart($link, $inp_active);

			// Update
			mysqli_query($link, "UPDATE $t_domains_monitoring_filters_index SET
						filter_title=$inp_title_mysql,
						filter_active=$inp_active_mysql, 
						filter_updated_date='$date',
						filter_updated_date_saying='$date_saying',
						filter_updated_by_user_id=$my_user_id_mysql
						 WHERE filter_id=$get_current_filter_id") or die(mysqli_error($link));
			
			$url = "index.php?open=domains_monitoring&page=filters&editor_language=$editor_language&l=$l&ft=success&fm=filter_changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_filter_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=open_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_filter_title</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=edit_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">Edit filter</a>
			</p>
		<!-- //Where am I? -->


		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Edit filter form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=edit_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_filter_title\" size=\"25\" />
			</p>

			<p><b>Acive:</b><br />
			<input type=\"radio\" name=\"inp_active\" value=\"1\""; if($get_current_filter_active == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_active\" value=\"0\""; if($get_current_filter_active == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>
			<input type=\"submit\" value=\"Save filter\" class=\"btn_default\" />
			</p>
	
			</form>
		<!-- //Edit filter form -->
		";
	} // filter found
} // action == edit_filter
elseif($action == "delete_filter"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	// Get filter
	$query = "SELECT filter_id, filter_title, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filter_id, $get_current_filter_title, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
	if($get_current_filter_id == ""){
		echo"Filter not found";
	}
	else{
		if($process == "1"){
			// Delete
			mysqli_query($link, "DELETE FROM $t_domains_monitoring_filters_index WHERE filter_id=$get_current_filter_id") or die(mysqli_error($link));
			mysqli_query($link, "DELETE FROM $t_domains_monitoring_filters_keywords WHERE keyword_filter_id=$get_current_filter_id") or die(mysqli_error($link));
			
			$url = "index.php?open=domains_monitoring&page=filters&editor_language=$editor_language&l=$l&ft=success&fm=filter_deleted";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_filter_title</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=open_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_filter_title</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=delete_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">Delete filter</a>
			</p>
		<!-- //Where am I? -->


		<!-- Feedback -->
			";
			if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Delete filter form -->
			<p>Are you sure you want to delete the filter?</p>

			<p>
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=delete_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>
		<!-- //Delete filter form -->
		";
	} // filter found
} // action == delete_filter
elseif($action == "edit_all_keywords"){
	if (isset($_GET['filter_id'])) {
		$filter_id = $_GET['filter_id'];
		$filter_id = stripslashes(strip_tags($filter_id));
		if(!(is_numeric($filter_id))){
			echo"Filter id not numeric";
			die;
		}
	}
	else{
		echo"Missing filter id";
		die;
	}
	$filter_id_mysql = quote_smart($link, $filter_id);

	// Get filter
	$query = "SELECT filter_id, filter_title, filter_group_id, filter_active, filter_created_date, filter_created_date_saying, filter_created_by_user_id, filter_updated_date, filter_updated_date_saying, filter_updated_by_user_id FROM $t_domains_monitoring_filters_index WHERE filter_id=$filter_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filter_id, $get_current_filter_title, $get_current_filter_group_id, $get_current_filter_active, $get_current_filter_created_date, $get_current_filter_created_date_saying, $get_current_filter_created_by_user_id, $get_current_filter_updated_date, $get_current_filter_updated_date_saying, $get_current_filter_updated_by_user_id) = $row;
	if($get_current_filter_id == ""){
		echo"Filter not found";
	}
	else{
		// Find a keyword
		$query = "SELECT keyword_id, keyword_filter_id, keyword_group_id, keyword_user_id, keyword_title, keyword_type, keyword_value, keyword_value_length, keyword_combinations, keyword_domain_tlds, keyword_added_datetime, keyword_updated_datetime, keyword_notes FROM $t_domains_monitoring_filters_keywords WHERE keyword_filter_id=$get_current_filter_id LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_keyword_id, $get_keyword_filter_id, $get_keyword_group_id, $get_keyword_user_id, $get_keyword_title, $get_keyword_type, $get_keyword_value, $get_keyword_value_length, $get_keyword_combinations, $get_keyword_domain_tlds, $get_keyword_added_datetime, $get_keyword_updated_datetime, $get_keyword_notes) = $row;
		
		if($process == "1"){
			$inp_domain_tlds = $_POST['inp_domain_tlds'];
			$inp_domain_tlds = output_html($inp_domain_tlds);
			$inp_domain_tlds_mysql = quote_smart($link, $inp_domain_tlds);

			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);
			$inp_type_mysql = quote_smart($link, $inp_type);

			$inp_combinations = $_POST['inp_combinations'];
			$inp_combinations = output_html($inp_combinations);
			$inp_combinations_mysql = quote_smart($link, $inp_combinations);

			mysqli_query($link, "UPDATE $t_domains_monitoring_filters_keywords SET 
						keyword_type=$inp_type_mysql,
						keyword_combinations=$inp_combinations_mysql, 
						keyword_domain_tlds=$inp_domain_tlds_mysql
					     WHERE keyword_filter_id=$get_current_filter_id") or die(mysqli_error($link));
			
			$url = "index.php?open=domains_monitoring&page=filters&action=open_filter&filter_id=$get_current_filter_id&editor_language=$editor_language&l=$l&ft=success&fm=keywords_updated";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Edit all keywords</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;editor_language=$editor_language&amp;l=$l\">Filters</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=open_filter&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_filter_title</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=edit_all_keywords&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l\">Edit all keywords</a>
			</p>
		<!-- //Where am I? -->


		<!-- Feedback -->
			";
			if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Edit all keywords form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_keyword\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=domains_monitoring&amp;page=filters&amp;action=edit_all_keywords&amp;filter_id=$get_current_filter_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>Limit domain tlds:</b><br />
			<span class=\"smal\">Example if you only want the keyword to work on .link, .site and .me then write: <em>link, site, me</em><br />
			If you want all domain tlds then leave the textarea blank</span><br />
			<textarea name=\"inp_domain_tlds\" rows=\"5\" cols=\"25\" style=\"width: 100%;\">$get_keyword_domain_tlds</textarea>
			</p>

			<p><b>Type:</b><br />
			<select name=\"inp_type\">
				<option value=\"contains\">- Keywords: -</option>
				<option value=\"contains\""; if($get_keyword_type == "contains"){ echo" selected=\"selected\""; } echo">Contains</option>
				<option value=\"starts_with\""; if($get_keyword_type == "starts_with"){ echo" selected=\"selected\""; } echo">Starts with</option>
				<option value=\"ends_with\""; if($get_keyword_type == "ends_with"){ echo" selected=\"selected\""; } echo">Ends with</option>
				<option value=\"exact\""; if($get_keyword_type == "exact"){ echo" selected=\"selected\""; } echo">Exact</option>
				<option value=\"regex\""; if($get_keyword_type == "regex"){ echo" selected=\"selected\""; } echo">Regex</option>
				<option value=\"starts_with_word_ends_with_another_word\""; if($get_keyword_type == "starts_with_word_ends_with_another_word"){ echo" selected=\"selected\""; } echo">Starts with word ends with another word (in keyword list)</option>
				<option value=\"contains\"> </option>
				<option value=\"contains\">- IP/Host: -</option>
				<option value=\"ip_exact\""; if($get_current_keyword_type == "ip_exact"){ echo" selected=\"selected\""; } echo">IP exact</option>
				<option value=\"host_exact\""; if($get_current_keyword_type == "host_exact"){ echo" selected=\"selected\""; } echo">Host exact</option>
			</select>
			</p>


			<p><b>Create combinations:</b><br />
			This will create all combinations of words in dictionary. Example word <em>loan and bank</em> will be 
			<em>loanbank</em>, <em>loan-bank</em>, <em>bankloan</em> and <em>bank-loan</em>. This is good to use with type excact.<br />
			<input type=\"radio\" name=\"inp_combinations\" value=\"1\""; if($get_keyword_combinations == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_combinations\" value=\"0\""; if($get_keyword_combinations == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>
			<input type=\"submit\" value=\"Update keywords\" class=\"btn_default\" />
			</p>
	
			</form>

		<!-- //Edit all keywords form -->
		";


	} // filter found
} // edit all keywords
?>