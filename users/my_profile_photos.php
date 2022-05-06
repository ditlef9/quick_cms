<?php
/**
*
* File: users/my_profile_photos.php
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

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_photos - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */


if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name, $get_user_language, $get_user_rank) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	if($action == ""){
		echo"
		<h1>$l_photo</h1>



		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_photos.php?l=$l\">$l_photo</a>
				</p>
			</div>
		<!-- //You are here -->


		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "photo_not_found_in_database"){
					$fm = "$l_photo_not_found_in_database";
				}
				elseif($fm == "photo_not_found"){
					$fm = "$l_photo_not_found";
				}
				elseif($fm == "photo_deleted"){
					$fm = "$l_photo_deleted";
				}
				elseif($fm == "photo_rotated"){
					$fm = "$l_photo_rotated";
				}
				elseif($fm == "photo_uploaded"){
					$fm = "$l_photo_uploaded";
				}
				elseif($fm == "photo_sat_as_profile_photo"){
					$fm = "$l_photo_sat_as_profile_photo";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Form -->


			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_image\"]').focus();
				});
				</script>
			<!-- //Focus -->
			<form method=\"POST\" action=\"my_profile_photos_upload.php?action=upload&amp;l=$l&amp;process=1\" id=\"upload_cover_photo_form\" enctype=\"multipart/form-data\">

			<p>$l_upload_photo:<br />
			<input type=\"file\" name=\"inp_image\" />
			</p>

			<p>$l_title<br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
			</p>

			<p>
			<input type=\"submit\" value=\"$l_upload\" class=\"btn\" />
			</p>
			</form>

		<!-- //Form -->

		<!-- Display photos -->
			";
			$query = "SELECT photo_id, photo_user_id, photo_title, photo_destination, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id' ORDER BY photo_profile_image DESC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_photo_id, $get_photo_user_id, $get_photo_title, $get_photo_destination, $get_photo_thumb_200) = $row;

				if(file_exists("$root/_uploads/users/images/$get_photo_user_id/$get_photo_destination")){
					if($get_photo_thumb_200 == ""){
						$extension = get_extension($get_photo_destination);
						$extension = strtolower($extension);
						$name = str_replace(".$extension", "", $get_photo_destination);
	
						// Small
						$thumb_a = $name . "_40." . $extension;
						$thumb_a_mysql = quote_smart($link, $thumb_a);

						// Medium
						$thumb_b = $name . "_50." . $extension;
						$thumb_b_mysql = quote_smart($link, $thumb_b);

						// Large
						$thumb_c = $name . "_60." . $extension;
						$thumb_c_mysql = quote_smart($link, $thumb_c);

						// Extra Large
						$thumb_d = $name . "_200." . $extension;
						$thumb_d_mysql = quote_smart($link, $thumb_d);
		
						// Update
						$result_update = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_thumb_40=$thumb_a_mysql, photo_thumb_50=$thumb_b_mysql, photo_thumb_60=$thumb_c_mysql, photo_thumb_200=$thumb_d_mysql WHERE photo_id=$get_photo_id");
				
						// Pass new variables
						$get_photo_thumb_40 = "$thumb_a";
						$get_photo_thumb_50 = "$thumb_b";
						$get_photo_thumb_60 = "$thumb_c";
						$get_photo_thumb_200 = "$thumb_d";
					}
					if(!(file_exists("$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_200"))){
						// Thumb
						$inp_new_x = 200;
						$inp_new_y = 200;
						resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_photo_user_id/$get_photo_destination", "$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_200");
					} // thumb
					echo"
					<!-- Hide show -->
						<script>
						\$(document).ready(function(){
							\$(\"#photo_frame_$get_photo_id\").hover(function () {
								\$(\"#photo_title_$get_photo_id\").toggle();
							});
						});
						</script>
					<!-- //Hide show -->

					<div class=\"responsive\">
						<div class=\"gallery\">

							<a id=\"photo$get_photo_id\"></a>
						
							<a href=\"my_profile_photos_edit.php?photo_id=$get_photo_id&amp;l=$l\"><img src=\"$root/_uploads/users/images/$get_photo_user_id/$get_photo_thumb_200\" alt=\"$get_photo_destination\" class=\"photo_img\" /></a>
							<div class=\"desc\">
								<span>$get_photo_title<br />
								<a href=\"my_profile_photos_rotate.php?photo_id=$get_photo_id&amp;rotate=90&amp;process=1&amp;l=$l\" class=\"btn\">$l_rotate</a>
								</span>
							</div>
						</div>
					</div>
					";
				}
				else{
					echo"<div class=\"clear\"></div>
					<div class=\"error\"><p>Image not found.. Deleting from MySQL</p></div>";
					$rd = mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_id='$get_photo_id' AND photo_user_id='$get_user_id'");
				}
			
			}

			echo"
			<div class=\"clear\"></div>
		<!-- //Display photos -->
			<script type=\"text/javascript\">
			\$(function(){
				// See if this is a touch device
				if ('ontouchstart' in window){
					// Set the correct body class
					\$('body').removeClass('no-touch').addClass('touch');
        
					// Add the touch toggle to show text
					\$('div.boxInner img').click(function(){
						\$(this).closest('.boxInner').toggleClass('touchFocus');
					});
				}
			});
			</script>

		
		";
	}
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>