<?php
/**
*
* File: _admin/_inc/ads/new_ad.php
* Version 2
* Copyright (c) 2019-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ----------------------------------------------------------------------- */

/*- Tables ---------------------------------------------------------------------------- */
$t_ads_index		= $mysqlPrefixSav . "ads_index";
$t_ads_advertisers	= $mysqlPrefixSav . "ads_advertisers";


/*- Variables ------------------------------------------------------------------------ */

$tabindex = 0;

if($process == "1"){
	$inp_internal_name = $_POST['inp_internal_name'];
	$inp_internal_name = output_html($inp_internal_name);

	$inp_active = $_POST['inp_active'];
	$inp_active = output_html($inp_active);

	$inp_title = $_POST['inp_title'];
	$inp_title = output_html($inp_title);

	$inp_text = $_POST['inp_text'];
	$inp_text = output_html($inp_text);

	$inp_url = $_POST['inp_url'];
	$inp_url = output_html($inp_url);
	$inp_url = str_replace("&amp;", "&", $inp_url);

	$inp_language = $_POST['inp_language'];
	$inp_language = output_html($inp_language);

	$inp_placement = $_POST['inp_placement'];
	$inp_placement = output_html($inp_placement);

	$inp_advertiser_id = $_POST['inp_advertiser_id'];
	$inp_advertiser_id = output_html($inp_advertiser_id);

	$datetime = date("y-m-d H:i:s");

	// Active from
	$inp_active_from_day = $_POST['inp_active_from_day'];
	if($inp_active_from_day == ""){
		$inp_active_from_day = "00";
	}
	$inp_active_from_day = output_html($inp_active_from_day);


	$inp_active_from_month = $_POST['inp_active_from_month'];
	if($inp_active_from_month == ""){
		$inp_active_from_month = "00";
	}
	$inp_active_from_month = output_html($inp_active_from_month);

	$inp_active_from_year = $_POST['inp_active_from_year'];
	if($inp_active_from_year == ""){
		$inp_active_from_year = "0000";
	}
	$inp_active_from_year = output_html($inp_active_from_year);
	
	$inp_active_from = $inp_active_from_year . "-" . $inp_active_from_month . "-" . $inp_active_from_day . " 00:00:00";

	// Active from time
	$inp_active_from_time = strtotime($inp_active_from);
	
	// Active from saying
	if($inp_active_from_month == "1" OR $inp_active_from_month == "01"){
		$inp_active_from_month_saying = "Jan";
	}
	elseif($inp_active_from_month == "2" OR $inp_active_from_month == "02"){
		$inp_active_from_month_saying = "Feb";
	}
	elseif($inp_active_from_month == "3" OR $inp_active_from_month == "03"){
		$inp_active_from_month_saying = "Mar";
	}
	elseif($inp_active_from_month == "4" OR $inp_active_from_month == "04"){
		$inp_active_from_month_saying = "Apr";
	}
	elseif($inp_active_from_month == "5" OR $inp_active_from_month == "05"){
		$inp_active_from_month_saying = "May";
	}
	elseif($inp_active_from_month == "6" OR $inp_active_from_month == "06"){
		$inp_active_from_month_saying = "Jun";
	}
	elseif($inp_active_from_month == "7" OR $inp_active_from_month == "07"){
		$inp_active_from_month_saying = "Jul";
	}
	elseif($inp_active_from_month == "8" OR $inp_active_from_month == "08"){
		$inp_active_from_month_saying = "Aug";
	}
	elseif($inp_active_from_month == "9" OR $inp_active_from_month == "09"){
		$inp_active_from_month_saying = "Sep";
	}
	elseif($inp_active_from_month == "10" OR $inp_active_from_month == "10"){
		$inp_active_from_month_saying = "Oct";
	}
	elseif($inp_active_from_month == "11" OR $inp_active_from_month == "11"){
		$inp_active_from_month_saying = "Nov";
	}
	elseif($inp_active_from_month == "12" OR $inp_active_from_month == "12"){
		$inp_active_from_month_saying = "Dec";
	}
	else{
		$inp_active_from_month_saying = "";
	}
	$inp_active_from_saying = $inp_active_from_day . " " . $inp_active_from_month_saying  . " " . $inp_active_from_year;

	$inp_active_from_hour = "00";


	// Active to 
	$inp_active_to_day = $_POST['inp_active_to_day'];
	if($inp_active_to_day == ""){
		$inp_active_to_day = "01";
	}
	$inp_active_to_day = output_html($inp_active_to_day);


	$inp_active_to_month = $_POST['inp_active_to_month'];
	if($inp_active_to_month == ""){
		$inp_active_to_month = "01";
	}
	$inp_active_to_month = output_html($inp_active_to_month);

	$inp_active_to_year = $_POST['inp_active_to_year'];
	if($inp_active_to_year == ""){
		$inp_active_to_year = "9999";
	}
	$inp_active_to_year = output_html($inp_active_to_year);
	
	$inp_active_to = $inp_active_to_year . "-" . $inp_active_to_month . "-" . $inp_active_to_day . " 00:00:00";

	// Active from time
	$inp_active_to_time = strtotime($inp_active_to);
	
	// Active to saying
	if($inp_active_to_month == "1" OR $inp_active_to_month == "01"){
		$inp_active_to_month_saying = "Jan";
	}
	elseif($inp_active_to_month == "2" OR $inp_active_to_month == "02"){
		$inp_active_to_month_saying = "Feb";
	}
	elseif($inp_active_to_month == "3" OR $inp_active_to_month == "03"){
		$inp_active_to_month_saying = "Mar";
	}
	elseif($inp_active_to_month == "4" OR $inp_active_to_month == "04"){
		$inp_active_to_month_saying = "Apr";
	}
	elseif($inp_active_to_month == "5" OR $inp_active_to_month == "05"){
		$inp_active_to_month_saying = "May";
	}
	elseif($inp_active_to_month == "6" OR $inp_active_to_month == "06"){
		$inp_active_to_month_saying = "Jun";
	}
	elseif($inp_active_to_month == "7" OR $inp_active_to_month == "07"){
		$inp_active_to_month_saying = "Jul";
	}
	elseif($inp_active_to_month == "8" OR $inp_active_to_month == "08"){
		$inp_active_to_month_saying = "Aug";
	}
	elseif($inp_active_to_month == "9" OR $inp_active_to_month == "09"){
		$inp_active_to_month_saying = "Sep";
	}
	elseif($inp_active_to_month == "10" OR $inp_active_to_month == "10"){
		$inp_active_to_month_saying = "Oct";
	}
	elseif($inp_active_to_month == "11" OR $inp_active_to_month == "11"){
		$inp_active_to_month_saying = "Nov";
	}
	elseif($inp_active_to_month == "12" OR $inp_active_to_month == "12"){
		$inp_active_to_month_saying = "Dec";
	}
	else{
		$inp_active_to_month_saying = "";
	}
	$inp_active_to_saying = $inp_active_to_day . " " . $inp_active_to_month_saying  . " " . $inp_active_to_year;

	$inp_active_to_hour = "00";

	// Code
	$inp_html_code = $_POST['inp_html_code'];

	$inp_clicks = 0;
	$inp_unique_clicks = 0;

	// Me
	$my_user_id = $_SESSION['admin_user_id'];
	$my_user_id = output_html($my_user_id);
	
	$stmt = $mysqli->prepare("SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=?"); 
	$stmt->bind_param("s", $my_user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;

	$stmt = $mysqli->prepare("INSERT INTO $t_ads_index
		(ad_id, ad_internal_name, ad_active, ad_html_code, ad_title, 
		ad_text, ad_url, ad_language, ad_placement, ad_advertiser_id, 
		ad_active_from_datetime, ad_active_from_time, ad_active_from_saying, ad_active_from_year, ad_active_from_month, 
		ad_active_from_day, ad_active_from_hour, ad_active_to_datetime, ad_active_to_time, ad_active_to_saying,
		ad_active_to_year, ad_active_to_month, ad_active_to_day, ad_active_to_hour, ad_clicks, 
		ad_unique_clicks, ad_created_by_user_id, ad_created_by_user_alias, ad_created_datetime, ad_updated_by_user_id, 
		ad_updated_by_user_alias, ad_updated_datetime) 
		VALUES 
		(NULL,?,?,?,?,
		?,?,?,?,?,
		?,?,?,?,?,
		?,?,?,?,?,
		?,?,?,?,?,
		?,?,?,?,?,
		?,?)");
	$stmt->bind_param("ssssssssssssssssssssssssssssss",  $inp_internal_name, $inp_active, $inp_html_code, $inp_title,
		$inp_text, $inp_url, $inp_language, $inp_placement, $inp_advertiser_id,
		$inp_active_from, $inp_active_from_time, $inp_active_from_saying, $inp_active_from_year, $inp_active_from_month, 
		$inp_active_from_day, $inp_active_from_hour, $inp_active_to, $inp_active_to_time, $inp_active_to_saying, 
		$inp_active_to_year, $inp_active_to_month, $inp_active_to_day, $inp_active_to_hour,  $inp_clicks,
		$inp_unique_clicks, $get_my_user_id, $get_my_user_name, $datetime,  $get_my_user_id,
		$get_my_user_name, $datetime); 
	$stmt->execute();
	if ($stmt->errno) { echo "Error MySQLi insert: " . $stmt->error; die; }



	// Get ID
	$query = "SELECT ad_id FROM $t_ads_index WHERE ad_created_datetime='$datetime'";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	list($get_current_ad_id) = $row;
	
	
	// Image
	$image_ft = "";
	$image_fm = "";

	$image_name = basename($_FILES['inp_image']['name']);
	$image_file_exp = explode('.', $image_name); 
	$image_file_type = $image_file_exp[count($image_file_exp) -1]; 
	$image_file_type = strtolower("$image_file_type");

	// Finnes mappen?
	$inp_image_path = "_uploads/a/$inp_language/$get_current_ad_id";
	if(!(is_dir("../_uploads"))){
		mkdir("../_uploads");
	}
	if(!(is_dir("../_uploads/a"))){
		mkdir("../_uploads/a");
	}
	if(!(is_dir("../_uploads/a/$inp_language"))){
		mkdir("../_uploads/a/$inp_language");
	}
	if(!(is_dir("../_uploads/a/$inp_language/$get_current_ad_id"))){
		mkdir("../_uploads/a/$inp_language/$get_current_ad_id");
	}
	

	$image_target_path = $inp_image_path . "/" . $get_current_ad_id . "." . $image_file_type;
	if($image_file_type == "jpg" OR $image_file_type == "png" OR $image_file_type == "gif"){
		if(move_uploaded_file($_FILES['inp_image']['tmp_name'], "../$image_target_path")) {

			// Sjekk om det faktisk er et bilde som er lastet opp
			$image_size = getimagesize("../$image_target_path");
			if(is_numeric($image_size[0]) && is_numeric($image_size[1])){
				// Dette bildet er OK
				// Insert into db


				$inp_image_file = $get_current_ad_id . "." . $image_file_type;

				$stmt = $mysqli->prepare("UPDATE $t_ads_index SET ad_image_path=?, ad_image_file=? WHERE ad_id=?");
				$stmt->bind_param("sss", $$inp_image_path, $inp_image_file, $get_current_ad_id); 
				$stmt->execute();
				if ($stmt->errno) {
					echo "Error MySQLi update: " . $stmt->error; die;
				}

				$image_ft = "success";
				$image_fm = "image_uploaded";


			} // numeric width and height
			else{
				unlink("../$image_target_path");
				$image_ft = "warning";
				$image_fm = "cant_get_file_size_of_image_" . "../$image_target_path";
			}
		} // move uploaded file
		else{
			switch ($_FILES['inp_image']['error']) {
				case UPLOAD_ERR_OK:
					$image_ft = "warning";
						$image_fm = "photo_unknown_error";
					break;
				case UPLOAD_ERR_NO_FILE:
					$image_ft = "warning";
						$image_fm = "no_file_selected";
					break;
				case UPLOAD_ERR_INI_SIZE:
					$image_ft = "warning";
						$image_fm = "photo_exceeds_filesize";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$image_ft = "warning";
						$image_fm = "photo_exceeds_filesize_form";
					break;
				default:
					$image_ft = "warning";
						$image_fm = "unknown_upload_error";
					break;
			}
				
			
		}
	} // jpg, png, gif
	else{
		if($image_file_type != ""){
			$image_ft = "warning";
			$image_fm = "unknown_file_type_" . $image_file_type;
		}
	}




	$url = "index.php?open=ads&page=edit_ad&ad_id=$get_current_ad_id&editor_language=$editor_language&ft=success&fm=ad_created&image_ft=$image_ft&image_fm=$image_fm";
	header("Location: $url");
	exit;
}
echo"
<h1>New ad</h1>

	<!-- Where am I ? -->
		<p>
		<b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Ads</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=new_ad&amp;editor_language=$editor_language&amp;l=$l\">New Ad</a>
		</p>
	<!-- Where am I ? -->

<!-- Form -->
	<script>
	window.onload = function() {
		document.getElementById(\"inp_internal_name\").focus();
	}
	</script>
			
	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

	<p><b>Internal name:</b><br />
	<input type=\"text\" name=\"inp_internal_name\" id=\"inp_internal_name\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Active:</b><br />
	<input type=\"radio\" name=\"inp_active\" value=\"1\" checked=\"checked\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> Yes &nbsp;
	<input type=\"radio\" name=\"inp_active\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> No
	</p>

	<p><b>HTML-code</b><br />
	<span class=\"small\">If you have a HTML code then paste it here (example from Google Ads)</span><br />
	<textarea name=\"inp_html_code\" rows=\"5\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea><br />
	</p>

	<p><b>Title:</b><br />
	<input type=\"text\" name=\"inp_title\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Text</b><br />
	<span class=\"small\">Used in text ads</span><br />
	<textarea name=\"inp_text\" rows=\"5\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea><br />
	</p>

	<p><b>URL:</b><br />
	<input type=\"text\" name=\"inp_url\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Language:</b><br />
	<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
	$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;
		echo"	<option value=\"$get_language_active_iso_two\">$get_language_active_name</option>\n";
	}
	echo"
	</select>

	<p><b>Image</b><br />
	<span class=\"small\">
	Medium Rectangle (aside): 300 x 250<br />
	Large rectangle  (aside): 336 x 280<br />
	Leaderboard (header, under h1, under text): 728 x 90<br />
	Half side (aside): 300 x 600<br />
	Large mobile banner (under h1, under text): 320 x 100<br />
	</span>
	<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>Placement:</b><br />
	<select name=\"inp_placement\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
		<option value=\"main_below_headline\">Main, below headline (h1)</option>
		<option value=\"main_below_text\">Main, below text</option>
		<option value=\"header\">Header</option>
		<option value=\"right_side_aside\">Right side (aside)</option>
	</select>

		
	<p><b>Advisor:</b> <a href=\"index.php?open=ads&amp;page=new_advertiser&amp;editor_language=$editor_language&amp;l=$l\" target=\"_blank\">New</a><br />
	<select name=\"inp_advertiser_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
	<option value=\"0\">- Please select -</option>\n";
	$query = "SELECT advertiser_id, advertiser_name FROM $t_ads_advertisers";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_advertiser_id, $get_advertiser_name) = $row;
		echo"	<option value=\"$get_advertiser_id\">$get_advertiser_name</option>\n";
	}
	echo"
	</select>

	
	
	<p><b>Active from</b><br />
	";
	$inp_active_from_year = date("Y");
	$inp_active_from_month = date("m");
	$inp_active_from_day = date("d");


	echo"
	<select name=\"inp_active_from_day\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_active_from_day == ""){ echo" selected=\"selected\""; } echo">- $l_day -</option>\n";
		for($x=1;$x<32;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}
			echo"<option value=\"$y\""; if($inp_active_from_day == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
		}
	echo"
	</select>

	<select name=\"inp_active_from_month\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_active_from_month == ""){ echo" selected=\"selected\""; } echo">- $l_month -</option>\n";

		$l_month_array[0] = "";
		$l_month_array[1] = "$l_january";
		$l_month_array[2] = "$l_february";
		$l_month_array[3] = "$l_march";
		$l_month_array[4] = "$l_april";
		$l_month_array[5] = "$l_may";
		$l_month_array[6] = "$l_june";
		$l_month_array[7] = "$l_juli";
		$l_month_array[8] = "$l_august";
		$l_month_array[9] = "$l_september";
		$l_month_array[10] = "$l_october";
		$l_month_array[11] = "$l_november";
		$l_month_array[12] = "$l_december";
		for($x=1;$x<13;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}
			echo"<option value=\"$y\""; if($inp_active_from_month == "$y"){ echo" selected=\"selected\""; } echo">$l_month_array[$x]</option>\n";
		}
	echo"
	</select>

	<select name=\"inp_active_from_year\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_active_from_year == ""){ echo" selected=\"selected\""; } echo">- $l_year -</option>\n";
		$year = date("Y");

		for($x=0;$x<150;$x++){
			echo"<option value=\"$year\""; if($inp_active_from_year == "$year"){ echo" selected=\"selected\""; } echo">$year</option>\n";
			$year = $year-1;
		}
		echo"
	</select>
	</p>

	<p><b>Active to</b><br />
	<span class=\"smal\">Leave blank to make it active forever</span><br />
	";
	

	echo"
	<select name=\"inp_active_to_day\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\">- $l_day -</option>\n";
		for($x=1;$x<32;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}
			echo"<option value=\"$y\">$x</option>\n";
		}
	echo"
	</select>

	<select name=\"inp_active_to_month\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\">- $l_month -</option>\n";

		$l_month_array[0] = "";
		$l_month_array[1] = "$l_january";
		$l_month_array[2] = "$l_february";
		$l_month_array[3] = "$l_march";
		$l_month_array[4] = "$l_april";
		$l_month_array[5] = "$l_may";
		$l_month_array[6] = "$l_june";
		$l_month_array[7] = "$l_juli";
		$l_month_array[8] = "$l_august";
		$l_month_array[9] = "$l_september";
		$l_month_array[10] = "$l_october";
		$l_month_array[11] = "$l_november";
		$l_month_array[12] = "$l_december";
		for($x=1;$x<13;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}
			echo"<option value=\"$y\">$l_month_array[$x]</option>\n";
		}
	echo"
	</select>

	<select name=\"inp_active_to_year\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_active_from_year == ""){ echo" selected=\"selected\""; } echo">- $l_year -</option>\n";
		$year = date("Y");

		for($x=0;$x<150;$x++){
			echo"<option value=\"$year\">$year</option>\n";
			$year = $year+1;
		}
		echo"
	</select>
	</p>


	<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	</form>
<!-- //Form -->
";

?>