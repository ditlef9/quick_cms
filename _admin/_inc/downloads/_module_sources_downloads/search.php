<?php 
/**
*
* File: downloads/search.php
* Version 1.0.0
* Date 15:38 21.01.2018
* Copyright (c) 2018 S. A. Ditlefsen
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
include("_tables_downloads.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['q']) OR isset($_POST['q'])) {
	if(isset($_GET['q'])) {
		$search_query = $_GET['q'];
	}
	else{
		$search_query = $_POST['q'];
	}
	$search_query = strip_tags(stripslashes($search_query));

	if($search_query == "$l_search..."){
		$search_query = "";
	}
}
else{
	$search_query = "";
}


/*- Exact search --------------------------------------------------------------------- */
$download_file_mysql = quote_smart($link, $search_query);
$query = "SELECT download_id, download_title, download_introduction, download_image_path, download_image_store, download_main_category_id, download_sub_category_id, download_unique_hits FROM $t_downloads_index WHERE download_file=$download_file_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_exact_search_download_id, $get_exact_search_download_title, $get_exact_search_download_introduction, $get_exact_search_download_image_path, $get_exact_search_download_image_store, $get_exact_download_main_category_id, $get_exact_download_sub_category_id, $get_exact_search_download_unique_hits) = $row;
if($process == "1"){
	if($get_exact_search_download_id != ""){
		$url = "view_download.php?download_id=$get_exact_search_download_id&main_category_id=$get_exact_download_main_category_id&sub_category_id=$get_exact_download_main_category_id&l=$l";
		header("Location: $url");
		exit;
	}
	else{
		$url = "search.php?q=$search_query&l=$l";
		header("Location: $url");
		exit;
	}
}


/*- Translations -------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/downloads/ts_open_main_category.php");


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_search - $l_downloads"; 
if($search_query != ""){
	$website_title =  "$search_query - " . $website_title; 
}

if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
echo"
<h1>$l_search"; if($search_query != ""){ echo" - $search_query"; }echo"</h1>

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


<!-- Search -->
	<form method=\"post\" action=\"search.php\" enctype=\"multipart/form-data\">
	<p>
	<input type=\"text\" name=\"q\" value=\"$search_query\" size=\"25\" id=\"nettport_inp_search_query\" />
	<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" class=\"btn_default\" />
	</p>
<!-- //Search -->

";

if($search_query != ""){
	// Check for hacker
	include("$root/_admin/_functions/look_for_hacker_in_string.php");

	
	$search_results_count = 0;


	// Exact search
	if($get_exact_search_download_id != ""){
		echo"
		<div class=\"downloads_exact_search_wrapper\">
		";

			if(file_exists("$root/$get_exact_search_download_image_path/$get_exact_search_download_image_store") && $get_exact_search_download_image_store != ""){
				echo"
				<div class=\"downloads_exact_search_inner_left\">
					<img src=\"$root/$get_exact_search_download_image_path/$get_download_image_store\" alt=\"$get_exact_search_download_image_store\" width=\"616\" height=\"353\" />
				</div>
				";
			}
			echo"
			<div class=\"downloads_exact_search_inner_right\">
				<p>
				<a href=\"view_download.php?download_id=$get_exact_search_download_id&amp;l=$l\" class=\"download_title_a\">$get_exact_search_download_title</a>
				</p>

				<p class=\"download_intro\">
				$get_exact_search_download_introduction
				</p>

				<p class=\"download_data\">
				$get_exact_search_download_unique_hits $l_unique_downloads_lowercase
				</p>
			</div>
		</div>
		";
	}

	echo"
	<!-- Run search -->
		<table class=\"downloads_list\">
		";
		// Get all downloads
		$query = "SELECT download_id, download_title, download_introduction, download_image_path, download_image_store_thumb, download_main_category_id, download_sub_category_id, download_unique_hits FROM $t_downloads_index";

		if($search_query != ""){

			$search_query = "%" . $search_query . "%";
			$search_query_mysql = quote_smart($link, $search_query);
			$query = $query . " WHERE download_title LIKE $search_query_mysql";
		}

		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_download_id, $get_download_title, $get_download_introduction, $get_download_image_path, $get_download_image_store_thumb, $get_download_main_category_id, $get_download_sub_category_id, $get_download_unique_hits) = $row;

			echo"
			 <tr class=\"downloads_list_tr\">";
			if(file_exists("$root/$get_download_image_path/$get_download_image_store_thumb") && $get_download_image_store_thumb != ""){
				echo"
				  <td class=\"downloads_list_td\" style=\"width: 184px;padding: 10px 0px 10px 10px\">
					<img src=\"$root/$get_download_image_path/$get_download_image_store_thumb\" alt=\"$get_download_image_store_thumb\" width=\"184\" height=\"69\" />
				  </td>
				  <td class=\"downloads_list_td\" style=\"vertical-align: top;padding-left: 20px;\">
				";
			}
			else{
				echo"
				  <td class=\"downloads_list_td\" style=\"vertical-align: top;padding-left: 20px;\" colspan=\"2\">
				";
			}
			echo"
				<p>
				<a href=\"view_download.php?download_id=$get_download_id&amp;main_category_id=$get_download_main_category_id&amp;sub_category_id=$get_download_sub_category_id&amp;l=$l\" class=\"download_title_a\">$get_download_title</a>
				</p>

				<p class=\"download_intro\">
				$get_download_introduction
				</p>

				<p class=\"download_data\">
				$get_download_unique_hits $l_unique_downloads_lowercase
				</p>
			  </td>
			 </tr>
			";
			$search_results_count++;
		}
		echo"
		</table>
	<!--// Run search -->


	";

	if($search_results_count == 0){
		echo"
		<p>$l_sorry_no_downloads_found_for_your_search</p>
		";

		// Send email to moderator
		$search_query = str_replace("%", "", $search_query);
		$q_encrypted = md5("$search_query");
		$q_antispam_file = "$root/_cache/downloads_search_no_results_" . $q_encrypted . ".txt";
		
		if(!(file_exists("$q_antispam_file"))){
			
			$fh = fopen($q_antispam_file, "w") or die("can not open file");
			fwrite($fh, "$search_query");
			fclose($fh);
			
		
			// Who is moderator of the week?
			$week = date("W");
			$year = date("Y");
			$datetime = date("Y-m-d H:i:s");
	
			$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
			if($get_moderator_user_id == ""){
				// Create moderator of the week
				include("$root/_admin/_functions/create_moderator_of_the_week.php");
					
				$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
			}




			// Mail from
			$host = $_SERVER['HTTP_HOST'];
			
			$referer = $_SERVER['HTTP_REFERER'];
			$referer = output_html($referer);
			$search_link = $configSiteURLSav . "/downloads/search.php?q=$search_query&amp;l=$l";
			$subject = "Downloads search without results for $search_query at $host";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			$message = $message . "<p>Hi $get_moderator_user_name,</p>\n\n";
			$message = $message . "<p>A user has searched for <em>$search_query</em> and got no search results at $host for lanugage $l.\n";
			$message = $message . "Please consider to add a download for that query.</p>\n\n";

			$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Search information:</b></p>\n";
			$message = $message . "<table>\n";
			$message = $message . " <tr><td><span>Query:</span></td><td><span>$search_query</span></td></tr>\n";
			$message = $message . " <tr><td><span>Link:</span></td><td><span><a href=\"$search_link\">$search_link</a></span></td></tr>\n";
			$message = $message . " <tr><td><span>Datetime:</span></td><td><span>$datetime</span></td></tr>\n";
			$message = $message . " <tr><td><span>Referer:</span></td><td><span><a href=\"$referer\">$referer</a></span></td></tr>\n";
			$message = $message . " <tr><td><span>User Agent:</span></td><td><span>$my_user_agent</span></td></tr>\n";
			$message = $message . " <tr><td><span>Language:</span></td><td><span>$inp_accept_language</span></td></tr>\n";
			$message = $message . " <tr><td><span>IP:</span></td><td><span>$my_ip</span></td></tr>\n";
			$message = $message . "</table>\n";

			$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$host</p>";
			$message = $message. "</body>\n";
			$message = $message. "</html>\n";

			// Email headers
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=utf-8';
			$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
			
			mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers));

			//echo"<p>$l_a_moderator_look_into_your_search_query_and_maybe_add_this_download_soon $l_choosen_moderator_is $get_moderator_user_name.</p>";
		}
	}
}
else{
	echo"
	<p>$l_type_your_search_in_the_search_field</p>
	";
}
echo"

";



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>