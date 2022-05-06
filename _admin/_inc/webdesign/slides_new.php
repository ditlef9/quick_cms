<?php
/**
*
* File: _admin/_inc/slides/new_slide.php
* Version 1.0.0
* Date 18:50 29.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Language ------------------------------------------------------------------------- */
include("_translations/admin/$l/webdesign/t_slides_edit.php");

/*- Tables --------------------------------------------------------------------------- */
$t_slides = $mysqlPrefixSav . "slides";


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;

if($action == "publish"){
	if(isset($_POST['inp_slide_active'])){
		$inp_slide_active = $_POST['inp_slide_active'];
	}
	else{
		$inp_slide_active = "0";
	}
	if($inp_slide_active == "on"){
		$inp_slide_active = "1";
	}
	else{
		$inp_slide_active = "0";
	}
	$inp_slide_active_mysql = quote_smart($link, $inp_slide_active);

	// Active from
	$inp_slide_active_from_day = $_POST['inp_slide_active_from_day'];
	if($inp_slide_active_from_day == ""){
		$inp_slide_active_from_day = "00";
	}
	$inp_slide_active_from_month = $_POST['inp_slide_active_from_month'];
	if($inp_slide_active_from_month == ""){
		$inp_slide_active_from_month = "00";
	}
	$inp_slide_active_from_year = $_POST['inp_slide_active_from_year'];
	if($inp_slide_active_from_year == ""){
		$inp_slide_active_from_year = "0000";
	}
	$inp_slide_active_from_hour = $_POST['inp_slide_active_from_hour'];
	if($inp_slide_active_from_hour == ""){
		$inp_slide_active_from_hour = "00";
	}
	$inp_slide_active_from_minute = $_POST['inp_slide_active_from_minute'];
	if($inp_slide_active_from_minute == ""){
		$inp_slide_active_from_minute = "00";
	}
	$inp_slide_active_from = $inp_slide_active_from_year . "-" . $inp_slide_active_from_month . "-" . $inp_slide_active_from_day . " " . $inp_slide_active_from_hour . ":" . $inp_slide_active_from_minute;
	$inp_slide_active_from_mysql = quote_smart($link, $inp_slide_active_from);

	// Active from time
	$inp_slide_active_from_time = strtotime($inp_slide_active_from);
	$inp_slide_active_from_time_mysql = quote_smart($link, $inp_slide_active_from_time);


	// Active to
	$inp_slide_active_to_day = $_POST['inp_slide_active_to_day'];
	if($inp_slide_active_to_day == ""){
		$inp_slide_active_to_day = "00";
	}
	$inp_slide_active_to_month = $_POST['inp_slide_active_to_month'];
	if($inp_slide_active_to_month == ""){
		$inp_slide_active_to_month = "00";
	}
	$inp_slide_active_to_year = $_POST['inp_slide_active_to_year'];
	if($inp_slide_active_to_year == ""){
		$inp_slide_active_to_year = "0000";
	}
	$inp_slide_active_to_hour = $_POST['inp_slide_active_to_hour'];
	if($inp_slide_active_to_hour == ""){
		$inp_slide_active_to_hour = "00";
	}
	$inp_slide_active_to_minute = $_POST['inp_slide_active_to_minute'];
	if($inp_slide_active_to_minute == ""){
		$inp_slide_active_to_minute = "00";
	}
	$inp_slide_active_to = $inp_slide_active_to_year . "-" . $inp_slide_active_to_month . "-" . $inp_slide_active_to_day . " " . $inp_slide_active_to_hour . ":" . $inp_slide_active_to_minute;
	$inp_slide_active_to_mysql = quote_smart($link, $inp_slide_active_to);

	// Active to time
	if($inp_slide_active_to == "0000-00-00 00:00"){
		$inp_slide_active_to_time = "";
	}
	else{
		$inp_slide_active_to_time = strtotime($inp_slide_active_to);
	}
	$inp_slide_active_to_time_mysql = quote_smart($link, $inp_slide_active_to_time);


	if(isset($_POST['inp_slide_repeat_yearly'])){
		$inp_slide_repeat_yearly = $_POST['inp_slide_repeat_yearly'];
	}
	else{
		$inp_slide_repeat_yearly = "0";
	}
	if($inp_slide_repeat_yearly == "on"){
		$inp_slide_repeat_yearly = "1";
	}
	else{
		$inp_slide_repeat_yearly = "0";
	}
	$inp_slide_repeat_yearly_mysql = quote_smart($link, $inp_slide_repeat_yearly);


	$inp_slide_language = $_POST['inp_slide_language'];
	$inp_slide_language = output_html($inp_slide_language);
	$inp_slide_language_mysql = quote_smart($link, $inp_slide_language);
	$editor_language = $inp_slide_language;

	$inp_slide_headline = $_POST['inp_slide_headline'];
	$inp_slide_headline = output_html($inp_slide_headline);
	$inp_slide_headline_mysql = quote_smart($link, $inp_slide_headline);

	$inp_slide_text = $_POST['inp_slide_text'];
	$inp_slide_text = output_html($inp_slide_text);
	$inp_slide_text_mysql = quote_smart($link, $inp_slide_text);

	$inp_slide_url = $_POST['inp_slide_url'];
	$inp_slide_url = output_html($inp_slide_url);
	$inp_slide_url_mysql = quote_smart($link, $inp_slide_url);

	$inp_slide_link_name = $_POST['inp_slide_link_name'];
	$inp_slide_link_name = output_html($inp_slide_link_name);
	$inp_slide_link_name_mysql = quote_smart($link, $inp_slide_link_name);

	$inp_slide_edited_datetime = date("Y-m-d H:i:s");
	$inp_slide_edited_datetime_mysql = quote_smart($link, $inp_slide_edited_datetime);

	$inp_slide_edited_by_user_id = $_SESSION['admin_user_id'];
	$inp_slide_edited_by_user_id = output_html($inp_slide_edited_by_user_id);
	$inp_slide_edited_by_user_id_mysql = quote_smart($link, $inp_slide_edited_by_user_id);
	
	// Insert
	mysqli_query($link, "INSERT INTO $t_slides
	(slide_id, slide_language, slide_active, slide_active_repeat_yearly, slide_weight, slide_headline, slide_image, slide_text, slide_url, slide_link_name, slide_edited_by_user_id, slide_edited_datetime) 
	VALUES 
	(NULL, $inp_slide_language_mysql, $inp_slide_active_mysql, $inp_slide_repeat_yearly_mysql, '1', $inp_slide_headline_mysql, '', $inp_slide_text_mysql, $inp_slide_url_mysql, $inp_slide_link_name_mysql, $inp_slide_edited_by_user_id_mysql, $inp_slide_edited_datetime_mysql)")
	or die(mysqli_error($link));

	// Get slide id
	$query = "SELECT slide_id FROM $t_slides WHERE slide_edited_datetime=$inp_slide_edited_datetime_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_slide_id) = $row;

	// Slide from
	if($inp_slide_active_from != "0000-00-00 00:00"){
		$result = mysqli_query($link, "UPDATE $t_slides SET slide_active_from_datetime=$inp_slide_active_from_mysql, slide_active_from_time=$inp_slide_active_from_time_mysql WHERE slide_id=$get_slide_id");
	}
	// Slide to
	if($inp_slide_active_to != "0000-00-00 00:00"){
		$result = mysqli_query($link, "UPDATE $t_slides SET slide_active_to_datetime=$inp_slide_active_to_mysql, slide_active_to_time=$inp_slide_active_to_time_mysql WHERE slide_id=$get_slide_id");
	}
	
	// Check that directory exists
	if(!(is_dir("../_uploads/slides"))){
		mkdir("../_uploads/slides");
	}
	if(!(is_dir("../_uploads/slides/$inp_slide_language"))){
		mkdir("../_uploads/slides/$inp_slide_language");
	}
	if(!(is_dir("../_uploads/slides/$inp_slide_language/imgs"))){
		mkdir("../_uploads/slides/$inp_slide_language/imgs");
	}

	// Upload image
	$img_ft = "";
	$img_fm = "";
	if (!empty($_FILES)) {
     
		// Sjekk filen
		$file_name = basename($_FILES['inp_image']['name']);
		$file_exp = explode('.', $file_name); 
		$file_type = $file_exp[count($file_exp) -1]; 
		$file_type = strtolower("$file_type");

		// Finnes mappen?
		$image_path = "../_uploads/slides/$inp_slide_language/imgs";
		

		// Sett variabler
		$new_name = $get_slide_id . ".png";
		$target_path = $image_path . "/" . $new_name;


		// Sjekk om det er en OK filendelse
		if($file_type == "jpg" OR $file_type == "png" OR $file_type == "gif"){
			if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {

				// Sjekk om det faktisk er et bilde som er lastet opp
				$image_size = getimagesize($target_path);
				if(is_numeric($image_size[0]) && is_numeric($image_size[1])){
					// Dette bildet er OK

					// Insert into db
					$inp_slide_image_mysql = quote_smart($link, $new_name);
					$result = mysqli_query($link, "UPDATE $t_slides SET slide_image=$inp_slide_image_mysql WHERE slide_id=$get_slide_id");
		
	
				}
				else{
					// Dette er en fil som har fått byttet filendelse...
					unlink("$target_path");

					$img_ft = "error";
					$img_fm = "file_is_not_an_image";
				}
			}
			else{
   				switch ($_FILES['inp_image'] ['error']){
				case 1:
					$img_ft = "error";
					$img_fm = "to_big_file";
					header("Location: $url");
					exit;
					break;
				case 2:
					$img_ft = "error";
					$img_fm = "to_big_file";
					header("Location: $url");
					exit;
					break;
				case 3:
					$img_ft = "error";
					$img_fm = "only_parts_uploaded";
					header("Location: $url");
					exit;
					break;
				case 4:
					$img_ft = "error";
					$img_fm = "no_file_uploaded";
					header("Location: $url");
					exit;
					break;
				}
			} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		}
		else{
			$img_ft = "error";
			$img_fm = "invalid_file_type&file_type=$file_type";
		}
	}
	


	// Make flat file
	$header_css ="";
	$fh = fopen("../_uploads/slides/$inp_slide_language/slides.css", "w+") or die("can not open file");
	fwrite($fh, $header_css);
	fclose($fh);


	$header_php ="<?php ";
	$fh = fopen("../_uploads/slides/$inp_slide_language/slides.php", "w+") or die("can not open file");
	fwrite($fh, $header_php);
	fclose($fh);

	$x = 0;
	$query_a = "SELECT slide_id, slide_active_from_datetime, slide_active_from_time, slide_active_to_datetime, slide_active_to_time, slide_active_repeat_yearly, slide_headline, slide_image, slide_text, slide_url, slide_link_name FROM $t_slides WHERE slide_language=$inp_slide_language_mysql AND slide_active='1' ORDER BY slide_weight ASC";
	$result_a = mysqli_query($link, $query_a);
	while($row_a = mysqli_fetch_row($result_a)) {
		list($get_slide_id, $get_slide_active_from_datetime, $get_slide_active_from_time, $get_slide_active_to_datetime, $get_slide_active_to_time, $get_slide_active_repeat_yearly, $get_slide_headline, $get_slide_image, $get_slide_text, $get_slide_url, $get_slide_link_name) = $row_a;

		// Active from
		$active_from_year = substr($get_slide_active_from_datetime, 0, 4);
		$active_from_month = substr($get_slide_active_from_datetime, 5, 2);
		$active_from_day = substr($get_slide_active_from_datetime, 8, 2);
		$active_from_hour = substr($get_slide_active_from_datetime, 11, 2);
		$active_from_minute = substr($get_slide_active_from_datetime, 14, 2);

		$active_to_year = substr($get_slide_active_to_datetime, 0, 4);
		$active_to_month = substr($get_slide_active_to_datetime, 5, 2);
		$active_to_day = substr($get_slide_active_to_datetime, 8, 2);
		$active_to_hour = substr($get_slide_active_to_datetime, 11, 2);
		$active_to_minute = substr($get_slide_active_to_datetime, 14, 2);


		$slide_image_mobile = "../../image.php?width=838&height=250&image=/_uploads/slides/$inp_slide_language/imgs/$get_slide_image";

		$body_css ="

.owl-slide-$get_slide_id {
	background-image: url('imgs/$get_slide_image');
	background-repeat: no-repeat;
	background-size: cover;
	background-position: center center;
	height: 260px;
}
";
		$fh = fopen("../_uploads/slides/$inp_slide_language/slides.css", "a+") or die("can not open file");
		fwrite($fh, $body_css);
		fclose($fh);


		$body_php ="
\$slide_id[$x]          	 = \"$get_slide_id\";
\$slide_active_from_datetime[$x] = \"$get_slide_active_from_datetime\";
\$slide_active_from_year[$x] 	 = \"$active_from_year\";
\$slide_active_from_month[$x] 	 = \"$active_from_month\";
\$slide_active_from_day[$x] 	 = \"$active_from_day\";
\$slide_active_from_hour[$x] 	 = \"$active_from_hour\";
\$slide_active_from_minute[$x] 	 = \"$active_from_minute\";
\$slide_active_from_time[$x] 	 = \"$get_slide_active_from_time\";
\$slide_active_to_datetime[$x]   = \"$get_slide_active_to_datetime\";
\$slide_active_to_year[$x] 	 = \"$active_to_year\";
\$slide_active_to_month[$x] 	 = \"$active_to_month\";
\$slide_active_to_day[$x] 	 = \"$active_to_day\";
\$slide_active_to_hour[$x] 	 = \"$active_to_hour\";
\$slide_active_to_minute[$x] 	 = \"$active_to_minute\";
\$slide_active_to_time[$x] 	 = \"$get_slide_active_to_time\";
\$slide_repeat_yearly[$x]	 = \"$get_slide_active_repeat_yearly\";
\$slide_headline[$x]    	 = \"$get_slide_headline\";
\$slide_image[$x]       	 = \"$get_slide_image\";
\$slide_text[$x]        	 = \"$get_slide_text\";
\$slide_url[$x]         	 = \"$get_slide_url\";
\$slide_link_name[$x]  		 = \"$get_slide_link_name\";
";
		$fh = fopen("../_uploads/slides/$inp_slide_language/slides.php", "a+") or die("can not open file");
		fwrite($fh, $body_php);
		fclose($fh);

		$x++;
	}
	
	$footer ="?>";
	$fh = fopen("../_uploads/slides/$inp_slide_language/slides.php", "a+") or die("can not open file");
	fwrite($fh, $footer);
	fclose($fh);


	header("Location: index.php?open=$open&page=slides&editor_language=$editor_language&ft=success&fm=slide_created&img_ft=$img_ft&img_fm=$img_fm");
	exit;
} // process
if($action == ""){
	
echo"
<h1>$l_new_slide</h1>

<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_slide_headline\"]').focus();
	});
	</script>
<!-- //Focus -->


<!-- Form -->
	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;action=publish&amp;process=1\" enctype=\"multipart/form-data\">
	
	<h2>$l_active</h2>

	<p><b>$l_active</b><br />
	<input type=\"checkbox\" name=\"inp_slide_active\" checked=\"checked\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> $l_yes
	</p>

	<p><b>$l_active_from</b><br />";
	$inp_slide_active_from_year = date("Y");
	$inp_slide_active_from_month = date("m");
	$inp_slide_active_from_day = date("d");
	$inp_slide_active_from_hour = "00";
	$inp_slide_active_from_minute = "00";
			
	echo"
	<select name=\"inp_slide_active_from_day\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_slide_active_from_day == ""){ echo" selected=\"selected\""; } echo">- $l_day -</option>\n";
		for($x=1;$x<32;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}
			echo"<option value=\"$y\""; if($inp_slide_active_from_day == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
		}
	echo"
	</select>

	<select name=\"inp_slide_active_from_month\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_slide_active_from_month == ""){ echo" selected=\"selected\""; } echo">- $l_month -</option>\n";

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
			echo"<option value=\"$y\""; if($inp_slide_active_from_month == "$y"){ echo" selected=\"selected\""; } echo">$l_month_array[$x]</option>\n";
		}
	echo"
	</select>

	<select name=\"inp_slide_active_from_year\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_slide_active_from_year == ""){ echo" selected=\"selected\""; } echo">- $l_year -</option>\n";
		$year = date("Y");

		for($x=0;$x<150;$x++){
			echo"<option value=\"$year\""; if($inp_slide_active_from_year == "$year"){ echo" selected=\"selected\""; } echo">$year</option>\n";
			$year = $year-1;
		}
		echo"
	</select>
	
	&nbsp;

	<select name=\"inp_slide_active_from_hour\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\">- hh-</option>\n";
		for($x=0;$x<24;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}

			echo"<option value=\"$y\">$y</option>\n";
		}
		echo"
	</select>
	:
	<select name=\"inp_slide_active_from_minute\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\">- mm-</option>\n";
		for($x=0;$x<60;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}

			echo"<option value=\"$y\">$y</option>\n";
		}
		echo"
	</select>
	</p>


	<p><b>$l_active_to</b><br />";
	$inp_slide_active_to_year = "";
	$inp_slide_active_to_month = "";
	$inp_slide_active_to_day = "";
			
	echo"
	<select name=\"inp_slide_active_to_day\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_slide_active_to_day == ""){ echo" selected=\"selected\""; } echo">- $l_day -</option>\n";
		for($x=1;$x<32;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}
			echo"<option value=\"$y\""; if($inp_slide_active_to_day == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
		}
	echo"
	</select>

	<select name=\"inp_slide_active_to_month\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_slide_active_to_month == ""){ echo" selected=\"selected\""; } echo">- $l_month -</option>\n";


			for($x=1;$x<13;$x++){
				if($x<10){
					$y = 0 . $x;
				}
				else{
					$y = $x;
				}
				echo"<option value=\"$y\""; if($inp_slide_active_to_month == "$y"){ echo" selected=\"selected\""; } echo">$l_month_array[$x]</option>\n";
			}
	echo"
	</select>

	<select name=\"inp_slide_active_to_year\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\""; if($inp_slide_active_to_year == ""){ echo" selected=\"selected\""; } echo">- $l_year -</option>\n";
		$year = date("Y");

		for($x=0;$x<150;$x++){
			echo"<option value=\"$year\""; if($inp_slide_active_to_year == "$year"){ echo" selected=\"selected\""; } echo">$year</option>\n";
			$year = $year+1;
		}
		echo"
	</select>
	
	&nbsp;

	<select name=\"inp_slide_active_to_hour\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\">- hh -</option>\n";
		for($x=0;$x<24;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}

			echo"<option value=\"$y\">$y</option>\n";
		}
		echo"
	</select>
	:
	<select name=\"inp_slide_active_to_minute\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" >
		<option value=\"\">- mm-</option>\n";
		for($x=0;$x<60;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}

			echo"<option value=\"$y\">$y</option>\n";
		}
		echo"
	</select>
	<br />
		<span class=\"smal\">$l_dont_select_anything_to_make_it_active_forever<br />
		$l_to_make_a_slide_active_every_year_from_a_date_to_a_date_then_let_year_be_blank
		</span>
	</p>

	<p><b>Repeat yearly</b><br />
	<input type=\"checkbox\" name=\"inp_slide_repeat_yearly\" checked=\"checked\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> $l_yes
	</p>



	<h2>$l_slide</h2>

	<p><b>$l_language</b><br />
	<select name=\"inp_slide_language\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
			$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
			echo"	<option value=\"$get_language_active_iso_two\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
						
		}
		echo"
	</select>
	</p>

	<p><b>$l_headline</b><br />
	<input type=\"text\" name=\"inp_slide_headline\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>$l_text</b><br />
	<textarea name=\"inp_slide_text\" rows=\"10\" cols=\"70\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" ></textarea>
	</p>

	<p><b>$l_url</b><br />
	<input type=\"text\" name=\"inp_slide_url\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>$l_link_name</b><br />
	<input type=\"text\" name=\"inp_slide_link_name\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>

	<p><b>$l_image (2000x260, png)</b><br />
	<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
	</p>


	<p><input type=\"submit\" value=\"Publish\" class=\"submit\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	


	</form>


	";
}
?>