<?php
/**
*
* File: users/index.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_users";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */



echo"
<h1>$l_users</h1>

<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		if($fm == "you_are_now_logged_out_see_you"){
			$fm = "$l_you_are_now_logged_out_see_you";
		}
		else{
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
<!-- //Feedback -->

<!-- Actions -->
	<p>";
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
		// Get user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		echo"	<a href=\"view_profile.php?user_id=$my_user_id&amp;l=$l\" class=\"btn_default\">$l_me</a>\n";
		echo"	<a href=\"my_profile.php?l=$l\" class=\"btn_default\">$l_my_profile</a>\n";
	}
	else{
		echo"	<a href=\"login.php?l=$l\" class=\"btn_default\">$l_login</a>\n";
		echo"	<a href=\"create_free_account.php?l=$l\" class=\"btn_default\">$l_registrer</a>\n";
	}
	echo"
	</p>
<!-- //Actions -->
";

$x = 0;
$query = "SELECT user_id, user_name,  user_alias, user_rank, user_country_name, user_city_name FROM $t_users ORDER BY user_last_online DESC";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_user_id, $get_user_name, $get_user_alias, $get_user_rank, $get_user_country_name, $get_user_city_name) = $row;


	// Photo
	$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id' AND photo_profile_image='1'";
	$r = mysqli_query($link, $q);
	$rowb = mysqli_fetch_row($r);
	list($get_photo_id, $get_photo_destination) = $rowb;

	// Thumb
	$inp_new_x = 175;
	$inp_new_y = 175;
	$thumb = "user_" . $get_user_id . "-" . $inp_new_x . "x" . $inp_new_y . "png";

	if($get_photo_id != "" && !(file_exists("$root/_cache/$thumb")) && file_exists("$root/_uploads/users/images/$get_user_id/$get_photo_destination") && $get_photo_destination != ""){
		resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_user_id/$get_photo_destination", "$root/_cache/$thumb");
	}



	if($x == 0){
		echo"
		<div class=\"clear\"></div>
		<div class=\"left_center_center_right_left\">
		";
	}
	elseif($x == 1){
		echo"
		<div class=\"left_center_center_left_right_center\">
		";
	}
	elseif($x == 2){
		echo"
		<div class=\"left_center_center_right_right_center\">
		";
	}
	elseif($x == 3){
		echo"
		<div class=\"left_center_center_right_right\">
		";
	}

	
	echo"
		<p style=\"padding:0;margin: 8px 0px 8px 0px;\">
		";
		if($get_photo_id != ""){
			if(!(file_exists("$root/_uploads/users/images/$get_user_id/$get_photo_destination"))){
				$res = mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id' AND photo_profile_image='1'");
			}
			echo"
			<a href=\"view_profile.php?user_id=$get_user_id&amp;l=$l\"><img src=\"$root/_cache/$thumb\" alt=\"$get_photo_destination\" class=\"image_rounded\" width=\"$inp_new_x\" height=\"$inp_new_y\" /></a>
			";
			
		}
		else{
			echo"
			<a href=\"view_profile.php?user_id=$get_user_id&amp;l=$l\"><img src=\"$root/users/_gfx/avatar_blank_175.png\" style=\"position: relative; top: 0; left: 0;\" alt=\"Avatar\" class=\"image_rounded\" /></a>
			";
		}
		echo"
		</p>
		<p style=\"padding:0;margin: 8px 0px 4px 0px;\">
		<a href=\"view_profile.php?user_id=$get_user_id&amp;l=$l\" style=\"font-weight:bold;color:#000;\">$get_user_alias</a>
		
		";
		if($get_user_name != "$get_user_alias"){
			echo"<span class=\"dark_grey\">@$get_user_name</span>";
		}
		echo"
		</p>	
		<p style=\"padding:0;margin: 2px 0px 5px 0px;\" class=\"dark_grey\">
		";
		if($get_user_city_name != ""){
			echo"
			$get_user_city_name";  if($get_user_country_name != ""){ echo", $get_user_country_name"; } echo"
			";
		}
		else{
			if($get_user_country_name != ""){ 
				echo"$get_user_country_name"; 
			}
		}
		echo"
		</p>
	</div>
	";
	// Increment
	$x++;

	// Reset
	if($x == 4){
		$x = 0;
	}		
}
if($x == "1"){
	echo"
		<div class=\"left_center_center_left_right_center\">
		</div>
		<div class=\"left_center_center_right_right_center\">
		</div>
		<div class=\"left_center_center_right_right\">
		</div>
		<div class=\"clear\"></div>
	";

}


/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>