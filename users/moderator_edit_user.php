<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";


// Variables
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
	echo"
	<h1>Error</h1>

	<p>$l_user_profile_not_found</p>
	";
	die;
}
if(isset($_GET['refer'])) {
	$refer = $_GET['refer'];
	$refer = strip_tags(stripslashes($refer));
}
else{
	$refer = "";
}


// Am I admin?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$my_security = $_SESSION['security'];
	$my_security_mysql = quote_smart($link, $my_security);

	$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;


	if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){


		// Get user
		$user_id_mysql = quote_smart($link, $user_id);

		$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_date_format, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip FROM $t_users WHERE user_id=$user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_date_format, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip) = $row;

		$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter) = $row;

		if($get_user_id == ""){
			echo"<h1>Error</h1><p>Error with user id.</p>"; 
			die;
		}

		if($get_my_user_rank == "moderator" && $get_user_rank == "admin"){
			echo"
			<h1>Server error 403</h1>
			<p>You can not edit a administrator.</p>
			";
			die;
		}

		if($action == "photos"){
			if($mode == "delete_photo"){
				// Variables
				if(isset($_GET['photo_id'])) {
					$photo_id = $_GET['photo_id'];
					$photo_id = strip_tags(stripslashes($photo_id));
				}
				else{
					$photo_id = "";
				}
				if(isset($_GET['prev_photo_id'])) {
					$prev_photo_id = $_GET['prev_photo_id'];
					$prev_photo_id = strip_tags(stripslashes($prev_photo_id));
				}
				else{
					$prev_photo_id = "";
				}

				// Get photo id
				$photo_id_mysql = quote_smart($link, $photo_id);
				$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_destination FROM $t_users_profile_photo WHERE photo_id=$photo_id_mysql AND photo_user_id=$user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_photo_id, $get_photo_user_id, $get_photo_profile_image, $get_photo_destination) = $row;

				if($get_photo_id == ""){
					// Send warning
					$fm = "photo_not_found";
					$ft = "warning";
				}
				else{


					if(!(file_exists("$root/_scripts/users/images/$get_user_id/$get_photo_destination"))){
						// Send warning
						$fm = "photo_not_found";
						$ft = "warning";
					}
					else{
	
						// Delete from MySQL
						$result = mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_id='$get_photo_id'");

						// Delete photo
						unlink("$root/_scripts/users/images/$get_user_id/$get_photo_destination");

						// Delete thumb
						$thumb = str_replace("_org", "_thumb", $get_photo_destination);
						unlink("$root/_scripts/users/images/$get_user_id/$thumb");

						// Check if this was my profile photo
						if($get_photo_profile_image == "1"){
							// get a new photo to use as profile photo
							$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_photo_id, $get_photo_user_id, $get_users_profile_photo, $get_photo_destination) = $row;
		
							if($get_photo_id != ""){
								$result = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_profile_image='1' WHERE photo_id=$get_photo_id");
							}
						}


						// Send success
						$fm = "photo_deleted";
						$ft = "success";
					}
				}

			}
			echo"
			<h1>$l_photos $get_user_name</h1>

			<!-- Menu -->
				<div id=\"tabs\">
					<ul>
						<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_edit_user</a></li>
						<li"; if($action == "edit_profile"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_profile&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_edit_profile</a></li>
						<li"; if($action == "edit_password"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_password&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_password</a></li>
						<li"; if($action == "photos"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=photos&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_photos</a></li>
					</ul>
				</div>
				<div class=\"clear\"></div>
			<!-- //Menu -->


			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					if($fm == "photo_not_found"){
						$fm = "$l_photo_not_found";
					}
					elseif($fm == "photo_deleted"){
						$fm = "$l_photo_deleted";
					}
					else{
						$fm = "$ft";
					}
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->

			<!-- Display photos -->
				";
				$prev_photo_id = "";
				$query = "SELECT photo_id, photo_destination, photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_photo_id, $get_photo_destination, $get_photo_uploaded, $get_photo_uploaded_ip, $get_photo_views, $get_photo_views_ip_block, $get_photo_likes, $get_photo_comments) = $row;
					$thumb = str_replace("_org", "_thumb", $get_photo_destination);

					echo"
					<div class=\"left\">
						<p>
						<a id=\"photo$get_photo_id\"></a>
						<a href=\"_scripts/users/images/$get_user_id/$get_photo_destination\"><img src=\"$root/image.php?width=100&amp;height=100&amp;cropratio=1:1&amp;image=/_scripts/users/images/$get_user_id/$thumb\" alt=\"$get_photo_destination\" /></a>
						</p>
					</div>
					<div class=\"right\">
						<p>
						$l_uploaded: $get_photo_uploaded<br />
						$l_ip: $get_photo_uploaded_ip<br />
						$l_views: $get_photo_views<br />
						$l_likes: $get_photo_likes<br />
						$l_comments: $get_photo_comments<br />

						<a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=photos&amp;mode=delete_photo&amp;photo_id=$get_photo_id&amp;user_id=$user_id&amp;l=$l#photo$prev_photo_id\">$l_delete_this_photo</a>
						</p>
					</div>
					<div class=\"clear\"></div>
					";

					$prev_photo_id = $get_photo_id;
				}
				echo"
			<!-- //Display photos -->

			";
		} // action == "photos"
		elseif($action == "edit_password"){
			if($mode == "save"){


				$inp_password = $_POST['inp_password'];
				$inp_password_encrypted = sha1("$inp_password");

	
				if(empty($inp_password)){
					// Send error
					$fm = "users_please_enter_a_password";
					$ft = "warning";
				}
				else{

					// Create salt
					$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    					$charactersLength = strlen($characters);
    					$salt = '';
    						for ($i = 0; $i < 6; $i++) {
        					$salt .= $characters[rand(0, $charactersLength - 1)];
    					}
					$inp_user_salt_mysql = quote_smart($link, $salt);


					// Password
					$inp_user_password = $inp_password_encrypted . $salt;
					$inp_user_password_mysql = quote_smart($link, $inp_user_password);


					$result = mysqli_query($link, "UPDATE $t_users SET user_password=$inp_user_password_mysql, user_salt=$inp_user_salt_mysql WHERE user_id=$user_id_mysql");
		


					// Send success
					$fm = "changes_saved";
					$ft = "success";
				}

			}
			echo"
			<h1>$l_edit_password_for $get_user_name</h1>

			<!-- Menu -->
				<div id=\"tabs\">
					<ul>
						<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_edit_user</a></li>
						<li"; if($action == "edit_profile"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_profile&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_edit_profile</a></li>
						<li"; if($action == "edit_password"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_password&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_password</a></li>
						<li"; if($action == "photos"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=photos&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_photos</a></li>
					</ul>
				</div>
				<div class=\"clear\"></div>
			<!-- //Menu -->


			<form method=\"POST\" action=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_password&amp;mode=save&amp;user_id=$user_id&amp;l=$l\" enctype=\"multipart/form-data\" name=\"nameform\">

			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					if($fm == "users_please_enter_a_password"){
						$fm = "$l_changes_saved";
					}
					elseif($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = "$ft";
					}
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->


			<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_password\"]').focus();
			});
			</script>
			<!-- //Focus -->


			<p>
			$l_wanted_password:<br />
			<input type=\"password\" name=\"inp_password\" size=\"30\" />
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" />
			</p>

			</form>

			";
		} // action == "edit_password"
		elseif($action == "edit_profile"){
			if($mode == "save"){

				$inp_profile_first_name = $_POST['inp_profile_first_name'];
				$inp_profile_first_name = output_html($inp_profile_first_name);
				$inp_profile_first_name = ucwords($inp_profile_first_name);
				$inp_profile_first_name_mysql = quote_smart($link, $inp_profile_first_name);

				$inp_profile_middle_name = $_POST['inp_profile_middle_name'];
				$inp_profile_middle_name = output_html($inp_profile_middle_name);
				$inp_profile_middle_name = ucwords($inp_profile_middle_name);
				$inp_profile_middle_name_mysql = quote_smart($link, $inp_profile_middle_name);

				$inp_profile_last_name = $_POST['inp_profile_last_name'];
				$inp_profile_last_name = output_html($inp_profile_last_name);
				$inp_profile_last_name = ucwords($inp_profile_last_name);
				$inp_profile_last_name_mysql = quote_smart($link, $inp_profile_last_name);

				$inp_profile_address_line_a = $_POST['inp_profile_address_line_a'];
				$inp_profile_address_line_a = output_html($inp_profile_address_line_a);
				$inp_profile_address_line_a_mysql = quote_smart($link, $inp_profile_address_line_a);

				$inp_profile_address_line_b = $_POST['inp_profile_address_line_b'];
				$inp_profile_address_line_b = output_html($inp_profile_address_line_b);
				$inp_profile_address_line_b_mysql = quote_smart($link, $inp_profile_address_line_b);

				$inp_profile_zip = $_POST['inp_profile_zip'];
				$inp_profile_zip = output_html($inp_profile_zip);
				$inp_profile_zip_mysql = quote_smart($link, $inp_profile_zip);

				$inp_profile_city = $_POST['inp_profile_city'];
				$inp_profile_city = output_html($inp_profile_city);
				$inp_profile_city = ucfirst($inp_profile_city);
				$inp_profile_city_mysql = quote_smart($link, $inp_profile_city);

				$inp_profile_country = $_POST['inp_profile_country'];
				$inp_profile_country = output_html($inp_profile_country);
				$inp_profile_country = ucfirst($inp_profile_country);
				$inp_profile_country_mysql = quote_smart($link, $inp_profile_country);

				$inp_profile_phone = $_POST['inp_profile_phone'];
				$inp_profile_phone = output_html($inp_profile_phone);
				$inp_profile_phone_mysql = quote_smart($link, $inp_profile_phone);

				$inp_profile_work = $_POST['inp_profile_work'];
				$inp_profile_work = output_html($inp_profile_work);
				$inp_profile_work_mysql = quote_smart($link, $inp_profile_work);

				$inp_profile_university = $_POST['inp_profile_university'];
				$inp_profile_university = output_html($inp_profile_university);
				$inp_profile_university_mysql = quote_smart($link, $inp_profile_university);

				$inp_profile_high_school = $_POST['inp_profile_high_school'];
				$inp_profile_high_school = output_html($inp_profile_high_school);
				$inp_profile_high_school_mysql = quote_smart($link, $inp_profile_high_school);
	
				$inp_profile_languages = $_POST['inp_profile_languages'];
				$inp_profile_languages = output_html($inp_profile_languages);
				$inp_profile_languages_mysql = quote_smart($link, $inp_profile_languages);

				$inp_profile_website = $_POST['inp_profile_website'];
				$inp_profile_website = output_html($inp_profile_website);
				$inp_profile_website_mysql = quote_smart($link, $inp_profile_website);

				if(isset($_POST['inp_interested_in_men'])){
					$inp_interested_in_men = $_POST['inp_interested_in_men'];
				}
				else{
					$inp_interested_in_men = "0";
				}
				if(isset($_POST['inp_interested_in_women'])){
					$inp_interested_in_women = $_POST['inp_interested_in_women'];
				}
				else{
					$inp_interested_in_women = "0";
				}
		
				$inp_interested_in = $inp_interested_in_men . "|" . $inp_interested_in_women;
				$inp_interested_in = output_html($inp_interested_in);
				$inp_interested_in_mysql = quote_smart($link, $inp_interested_in);

				$inp_profile_relationship = $_POST['inp_profile_relationship'];
				$inp_profile_relationship = output_html($inp_profile_relationship);
				$inp_profile_relationship_mysql = quote_smart($link, $inp_profile_relationship);

				$inp_profile_about_me = $_POST['inp_profile_about_me'];
				$inp_profile_about_me = output_html($inp_profile_about_me);
				$inp_profile_about_me_mysql = quote_smart($link, $inp_profile_about_me);


				$inp_profile_gender = $_POST['inp_profile_gender'];
				$inp_profile_gender = output_html($inp_profile_gender);
				$inp_profile_gender_mysql = quote_smart($link, $inp_profile_gender);

				$inp_profile_dob_day = $_POST['inp_profile_dob_day'];
				$day_len = strlen($inp_profile_dob_day);

				$inp_profile_dob_month = $_POST['inp_profile_dob_month'];
				$month_len = strlen($inp_profile_dob_month);

				$inp_profile_dob_year = $_POST['inp_profile_dob_year'];
				$year_len = strlen($inp_profile_dob_year);

				$inp_profile_dob = $inp_profile_dob_year . "-" . $inp_profile_dob_month . "-" . $inp_profile_dob_day;
				$inp_profile_dob = output_html($inp_profile_dob);
				$inp_profile_dob_mysql = quote_smart($link, $inp_profile_dob);
				if($inp_profile_dob != "--"){
					$result = mysqli_query($link, "UPDATE $t_users_profile SET profile_dob=$inp_profile_dob_mysql WHERE profile_user_id=$user_id_mysql");
				}

				$result = mysqli_query($link, "UPDATE $t_users_profile SET profile_first_name=$inp_profile_first_name_mysql, profile_middle_name=$inp_profile_middle_name_mysql, profile_last_name=$inp_profile_last_name_mysql, profile_address_line_a=$inp_profile_address_line_a_mysql, profile_address_line_b=$inp_profile_address_line_b_mysql, profile_zip=$inp_profile_zip_mysql, profile_city=$inp_profile_city_mysql, profile_country=$inp_profile_country_mysql, profile_phone=$inp_profile_phone_mysql, profile_work=$inp_profile_work_mysql, profile_university=$inp_profile_university_mysql, profile_high_school=$inp_profile_high_school_mysql, profile_languages=$inp_profile_languages_mysql, profile_website=$inp_profile_website_mysql, profile_interested_in=$inp_interested_in_mysql, profile_relationship=$inp_profile_relationship_mysql, profile_about=$inp_profile_about_me_mysql, profile_gender=$inp_profile_gender_mysql WHERE profile_user_id=$user_id_mysql");
				// Send success
				$fm = "changes_saved";
				$ft = "success";
				
				// Get new information
				$query = "SELECT * FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_gender, $get_profile_mesurment, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter, $get_profile_dob) = $row;
	

			}
			echo"
			<h1>$l_edit $get_user_name</h1>

			<!-- Menu -->
				<div id=\"tabs\">
					<ul>
						<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_edit_user</a></li>
						<li"; if($action == "edit_profile"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_profile&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_edit_profile</a></li>
						<li"; if($action == "edit_password"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_password&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_password</a></li>
						<li"; if($action == "photos"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=photos&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_photos</a></li>
					</ul>
				</div>
				<div class=\"clear\"></div>
			<!-- //Menu -->


			<form method=\"POST\" action=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_profile&amp;mode=save&amp;user_id=$user_id&amp;l=$l\" enctype=\"multipart/form-data\" name=\"nameform\">

			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = "$ft";
					}
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->


			<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_profile_first_name\"]').focus();
			});
			</script>
			<!-- //Focus -->

			<p>
			$l_first_name:<br />
			<input type=\"text\" name=\"inp_profile_first_name\" size=\"78\" value=\"$get_profile_first_name\" /><br />
			</p>

			<p>
			$l_middle_name:<br />
			<input type=\"text\" name=\"inp_profile_middle_name\" size=\"78\" value=\"$get_profile_middle_name\" /><br />
			</p>

			<p>
			$l_last_name:<br />
			<input type=\"text\" name=\"inp_profile_last_name\" size=\"78\" value=\"$get_profile_last_name\" /><br />
			</p>

			<p>
			$l_address_line_a:<br />
			<input type=\"text\" name=\"inp_profile_address_line_a\" size=\"78\" value=\"$get_profile_address_line_a\" /><br />
			</p>

			<p>
			$l_address_line_b:<br />
			<input type=\"text\" name=\"inp_profile_address_line_b\" size=\"78\" value=\"$get_profile_address_line_b\" /><br />
			</p>

			<p>
			$l_zip_and_city:<br />
			<input type=\"text\" name=\"inp_profile_zip\" size=\"5\" value=\"$get_profile_zip\" />
			<input type=\"text\" name=\"inp_profile_city\" size=\"68\" value=\"$get_profile_city\" /><br />
			</p>

			<p>
			$l_country:<br />
			<select name=\"inp_profile_country\">
			<option value=\"\""; if($get_profile_country == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>";
			$filenames = "";
			$dir = "$root/_webdesign/images/flags/16x16/";
			$dirLen = strlen($dir);
			$dp = @opendir($dir);

			while($file = @readdir($dp)) $filenames [] = $file;
			for ($i = 0; $i < count($filenames); $i++){
				@sort($filenames);
				$content = $filenames[$i];
				$file_path = "$dir$content";

				if($file_path != "$root/_webdesign/images/flags/16x16/.." && $file_path != "$root/_webdesign/images/flags/16x16/."){
					$country = "$content";
					$country = str_replace("_16x16.png", "", $country);
					$country = str_replace("_", " ", $country);
					$country = ucwords($country);
					echo"
					<option value=\"$country\""; if($get_profile_country == "$country"){ echo" selected=\"selected\""; } echo" style=\"background: url('$file_path') no-repeat;padding-left: 20px;\">$country</option>
					";
				}
			}
			echo"
			</select>
			</p>

			<p>
			$l_phone:<br />
			<input type=\"text\" name=\"inp_profile_phone\" size=\"78\" value=\"$get_profile_phone\" /><br />
			</p>



			<p>
			$l_work:<br />
			<input type=\"text\" name=\"inp_profile_work\" size=\"78\" value=\"$get_profile_work\" /><br />
			</p>


			<p>
			$l_university:<br />
			<input type=\"text\" name=\"inp_profile_university\" size=\"78\" value=\"$get_profile_university\" /><br />
			</p>

			<p>
			$l_high_school:<br />
			<input type=\"text\" name=\"inp_profile_high_school\" size=\"78\" value=\"$get_profile_high_school\" /><br />
			</p>

			<p>
			$l_languages:<br />
			<input type=\"text\" name=\"inp_profile_languages\" size=\"78\" value=\"$get_profile_languages\" /><br />
			</p>

			<p>
			$l_website:<br />
			<input type=\"text\" name=\"inp_profile_website\" size=\"78\" value=\"$get_profile_website\" /><br />
			</p>


			<p>
			$l_interested_in:<br />";
			$intrested_in_array = explode("|", $get_profile_interested_in);
			echo"
			<input type=\"checkbox\" name=\"inp_interested_in_men\""; if($intrested_in_array[0] == "on"){ echo" checked=\"checked\""; } echo" /> $l_men
			&nbsp;
			<input type=\"checkbox\" name=\"inp_interested_in_women\""; if(isset($intrested_in_array[1]) && $intrested_in_array[1] == "on"){ echo" checked=\"checked\""; } echo" /> $l_women
			</p>


			<p>
			$l_relationship_status:<br />
			<select name=\"inp_profile_relationship\"> 
			<option value=\"\""; if($get_profile_relationship == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>
			<option value=\"single\""; if($get_profile_relationship == "single"){ echo" selected=\"selected\""; } echo">$l_single</option>
			<option value=\"in_a_relationship\""; if($get_profile_relationship == "in_a_relationship"){ echo" selected=\"selected\""; } echo">$l_in_a_relationship</option>
			<option value=\"engaged\""; if($get_profile_relationship == "engaged"){ echo" selected=\"selected\""; } echo">$l_engaged</option>
			<option value=\"married\""; if($get_profile_relationship == "married"){ echo" selected=\"selected\""; } echo">$l_married</option>
			<option value=\"in_a_open_relationship\""; if($get_profile_relationship == "in_a_open_relationship"){ echo" selected=\"selected\""; } echo">$l_in_a_open_relationship</option>
			<option value=\"its_complicated\""; if($get_profile_relationship == "its_complicated"){ echo" selected=\"selected\""; } echo">$l_its_complicated</option>
			<option value=\"seperated\""; if($get_profile_relationship == "seperated"){ echo" selected=\"selected\""; } echo">$l_seperated</option>
			<option value=\"divorced\""; if($get_profile_relationship == "divorced"){ echo" selected=\"selected\""; } echo">$l_divorced</option>
			<option value=\"widow_widower\""; if($get_profile_relationship == "widow_widower"){ echo" selected=\"selected\""; } echo">$l_widow_widower</option>
			</select>
			</p>

			<p>
			$l_about_me:<br />
			<textarea name=\"inp_profile_about_me\" rows=\"5\" cols=\"40\">"; $get_profile_about = str_replace("<br />", "\n", $get_profile_about); echo"$get_profile_about</textarea>
			</p>



			<p>
			$l_gender:<br />
			<select name=\"inp_profile_gender\"> 
				<option value=\"\""; if($get_profile_gender == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>
				<option value=\"male\""; if($get_profile_gender == "male"){ echo" selected=\"selected\""; } echo">$l_male</option>
				<option value=\"female\""; if($get_profile_gender == "female"){ echo" selected=\"selected\""; } echo">$l_female</option>
			</select>
			</p>


			<p>
			$l_birthday:<br />";

			$dob_array = explode("-", $get_profile_dob);
			$dob_year = $dob_array[0];
			if(isset($dob_array[1])){
				$dob_month = $dob_array[1];
			}
			else{
				$dob_month = 0;
			}
			if(isset($dob_array[2])){
				$dob_day = $dob_array[2];
			}
			else{
				$dob_day = 0;
			}
				
	
			echo"
			<select name=\"inp_profile_dob_day\">
				<option value=\"\""; if($dob_day == ""){ echo" selected=\"selected\""; } echo">- $l_day -</option>\n";
			for($x=1;$x<32;$x++){
				if($x<10){
					$y = 0 . $x;
				}
				else{
					$y = $x;
				}
				echo"<option value=\"$y\""; if($dob_day == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
			}
			echo"
			</select>

			<select name=\"inp_profile_dob_month\">
				<option value=\"\""; if($dob_month == ""){ echo" selected=\"selected\""; } echo">- $l_month -</option>\n";

			$l_month_array[0] = "";
			$l_month_array[1] = "$l_month_january";
			$l_month_array[2] = "$l_month_february";
			$l_month_array[3] = "$l_month_march";
			$l_month_array[4] = "$l_month_april";
			$l_month_array[5] = "$l_month_may";
			$l_month_array[6] = "$l_month_june";
			$l_month_array[7] = "$l_month_juli";
			$l_month_array[8] = "$l_month_august";
			$l_month_array[9] = "$l_month_september";
			$l_month_array[10] = "$l_month_october";
			$l_month_array[11] = "$l_month_november";
			$l_month_array[12] = "$l_month_december";
			for($x=1;$x<13;$x++){
				if($x<10){
					$y = 0 . $x;
				}
				else{
					$y = $x;
				}
				echo"<option value=\"$y\""; if($dob_month == "$y"){ echo" selected=\"selected\""; } echo">$l_month_array[$x]</option>\n";
			}
			echo"
			</select>

			<select name=\"inp_profile_dob_year\">
				<option value=\"\""; if($dob_year == ""){ echo" selected=\"selected\""; } echo">- $l_year -</option>\n";
			$year = date("Y");

			for($x=0;$x<150;$x++){
				echo"<option value=\"$year\""; if($dob_year == "$year"){ echo" selected=\"selected\""; } echo">$year</option>\n";
				$year = $year-1;

			}
			echo"
			</select>
			</p>


			<p>
			<input type=\"submit\" value=\"$l_save\" />
			</p>

			</form>

			";
		} // action == "edit_profile"
		elseif($action == ""){
			if($mode == "save"){
				$ft = "";
				$fm = "";

				$inp_user_email = $_POST['inp_user_email'];
				$inp_user_email = output_html($inp_user_email);
				$inp_user_email = strtolower($inp_user_email);
				$inp_user_email_mysql = quote_smart($link, $inp_user_email);

				if($inp_user_email != "$get_user_email"){
					// Check if new email is taken
					
					$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_email=$inp_user_email_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($check_user_id, $check_user_email, $check_user_name) = $row;

					if($check_user_id == ""){
						// Update email
						$result = mysqli_query($link, "UPDATE $t_users SET user_email=$inp_user_email_mysql WHERE user_id=$user_id_mysql");
						$fm = "email_address_updated";
						$ft = "success";
					}
					else{
						$fm = "email_alreaddy_in_use";
						$ft = "warning";
					}
		
				}
			

				$inp_user_name = $_POST['inp_user_name'];
				$inp_user_name = output_html($inp_user_name);
				$inp_user_name = ucfirst($inp_user_name);
				$inp_user_name_mysql = quote_smart($link, $inp_user_name);

				if($inp_user_name != "$get_user_name"){
					// Check if new email is taken
					
					$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_name=$inp_user_name_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($check_user_id, $check_user_email, $check_user_name) = $row;

					if($check_user_id == ""){
						// Update email
						$result = mysqli_query($link, "UPDATE $t_users SET user_name=$inp_user_name_mysql WHERE user_id=$user_id_mysql");
						$fm = "user_name_updated";
						$ft = "success";
					}
					else{
						$fm = "user_name_alreaddy_in_use";
						$ft = "warning";
					}
		
				}

				$inp_user_language = $_POST['inp_user_language'];
				$inp_user_language = output_html($inp_user_language);
				$inp_user_language_mysql = quote_smart($link, $inp_user_language);

				$inp_user_rank = $_POST['inp_user_rank'];
				$inp_user_rank = output_html($inp_user_rank);
				$inp_user_rank_mysql = quote_smart($link, $inp_user_rank);

				$inp_user_points = $_POST['inp_user_points'];
				$inp_user_points = output_html($inp_user_points);
				$inp_user_points_mysql = quote_smart($link, $inp_user_points);

				$inp_user_likes = $_POST['inp_user_likes'];
				$inp_user_likes = output_html($inp_user_likes);
				$inp_user_likes_mysql = quote_smart($link, $inp_user_likes);

				$inp_user_dislikes = $_POST['inp_user_dislikes'];
				$inp_user_dislikes = output_html($inp_user_dislikes);
				$inp_user_dislikes_mysql = quote_smart($link, $inp_user_dislikes);

				$inp_user_status = $_POST['inp_user_status'];
				$inp_user_status = output_html($inp_user_status);
				$inp_user_status_mysql = quote_smart($link, $inp_user_status);


				$result = mysqli_query($link, "UPDATE $t_users SET user_language=$inp_user_language_mysql, user_rank=$inp_user_rank_mysql, user_points=$inp_user_points_mysql, user_likes=$inp_user_likes_mysql, user_dislikes=$inp_user_dislikes_mysql, user_status=$inp_user_status_mysql WHERE user_id=$user_id_mysql");
		
				if($ft == "" OR $ft == "success"){
					if($fm == ""){
						$fm = "changes_saved";
						$ft = "success";
					}
				}



				// get new information
				$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_date_format, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip FROM $t_users WHERE user_id=$user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_date_format, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip) = $row;


			}
			echo"
			<h1>$l_edit_user $get_user_name</h1>

			<!-- Menu -->
				<div id=\"tabs\">
					<ul>
						<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_edit_user</a></li>
						<li"; if($action == "edit_profile"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_profile&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_edit_profile</a></li>
						<li"; if($action == "edit_password"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=edit_password&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_password</a></li>
						<li"; if($action == "photos"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=photos&amp;user_id=$user_id&amp;l=$l&amp;refer=$refer\">$l_photos</a></li>
					</ul>
				</div>
				<div class=\"clear\"></div>
			<!-- //Menu -->


			<form method=\"POST\" action=\"index.php?category=users&amp;page=moderator_edit_user&amp;action=&amp;mode=save&amp;user_id=$user_id&amp;l=$l\" enctype=\"multipart/form-data\" name=\"nameform\">

			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					if($fm == "email_alreaddy_in_use"){
						$fm = "$l_email_alreaddy_in_use";
					}
					elseif($fm == "user_name_updated"){
						$fm = "$l_user_name_updated";
					}
					elseif($fm == "user_name_alreaddy_in_use"){
						$fm = "$l_user_name_alreaddy_in_use";
					}
					elseif($fm == "email_address_updated"){
						$fm = "$l_email_address_updated";
					}
					elseif($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = "$ft";
					}
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->


			<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_user_email\"]').focus();
			});
			</script>
			<!-- //Focus -->



			<p>
			$l_email_address:<br />
			<input type=\"text\" name=\"inp_user_email\" size=\"78\" value=\"$get_user_email\" /><br />
			</p>

			<p>
			$l_user_name:<br />
			<input type=\"text\" name=\"inp_user_name\" size=\"78\" value=\"$get_user_name\" /><br />
			</p>
			<p>
			$l_language:<br />
			<select name=\"inp_user_language\">";
			$filenames = "";
			$dir = "$root/_scripts/language/data/";
			$dirLen = strlen($dir);
			$dp = @opendir($dir);

			while($file = @readdir($dp)) $filenames [] = $file;

			for ($i = 0; $i < count($filenames); $i++){
				$content = $filenames[$i];
				$file_path = "$dir$content";

				if($file_path != "$dir." && $file_path != "$dir.."){
					echo"			";
					echo"<option value=\"$content\""; if($content == "$get_user_language"){ echo" selected=\"selected\""; } echo">$content</option>\n";
				}
			}
			echo"
			</select>
			</p>

			<p>
			$l_rank:<br />
			<select name=\"inp_user_rank\">";
			if($get_my_user_rank == "admin"){
				echo"<option value=\"admin\""; if($get_user_rank == "admin"){ echo" selected=\"selected\""; } echo">$l_admin</option>\n";
				echo"<option value=\"moderator\""; if($get_user_rank == "moderator"){ echo" selected=\"selected\""; } echo">$l_moderator</option>\n";
				echo"<option value=\"editor\""; if($get_user_rank == "editor"){ echo" selected=\"selected\""; } echo">$l_editor</option>\n";
				echo"<option value=\"trusted\""; if($get_user_rank == "trusted"){ echo" selected=\"selected\""; } echo">$l_trusted</option>\n";
				echo"<option value=\"user\""; if($get_user_rank == "user"){ echo" selected=\"selected\""; } echo">$l_user</option>\n";
			}
			elseif($get_my_user_rank == "moderator"){
				echo"<option value=\"moderator\""; if($get_user_rank == "moderator"){ echo" selected=\"selected\""; } echo">$l_moderator</option>\n";
				echo"<option value=\"editor\""; if($get_user_rank == "editor"){ echo" selected=\"selected\""; } echo">$l_editor</option>\n";
				echo"<option value=\"trusted\""; if($get_user_rank == "trusted"){ echo" selected=\"selected\""; } echo">$l_trusted</option>\n";
				echo"<option value=\"user\""; if($get_user_rank == "user"){ echo" selected=\"selected\""; } echo">$l_user</option>\n";
			}
			echo"
			</select>
			</p>

			<p>
			$l_points:<br />
			<input type=\"text\" name=\"inp_user_points\" size=\"78\" value=\"$get_user_points\" /><br />
			</p>

			<p>
			$l_likes:<br />
			<input type=\"text\" name=\"inp_user_likes\" size=\"78\" value=\"$get_user_likes\" /><br />
			</p>

			<p>
			$l_dislikes:<br />
			<input type=\"text\" name=\"inp_user_dislikes\" size=\"78\" value=\"$get_user_dislikes\" /><br />
			</p>


			<p>
			$l_status:<br />
			<input type=\"text\" name=\"inp_user_status\" size=\"78\" value=\"$get_user_status\" /><br />
			</p>


			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" />
			</p>

			</form>

			";
		} // action == ""
	}
	else{
		echo"
		<h1>Server error 403</h1>

		<p>Administrator and moderator only.</p>
		";
	}
}
else{

	echo"
	<h1>Server error 403</h1>

	<p>Not logged in.</p>
	";
}
?>