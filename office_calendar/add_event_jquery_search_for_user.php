<?php 
/**
*
* File: edb/open_case_overview_assigned_to_jquery_search_for_assignee.php
* Version 1.0
* Date 21:03 04.08.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

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

/*- Tables ---------------------------------------------------------------------------- */
include("tables_office_calendar.php");


/*- Query --------------------------------------------------------------------------- */

if(isset($_GET['q']) && $_GET['q'] != ''){
	$q = $_GET['q'];
	$q = strip_tags(stripslashes($q));
	$q = trim($q);
	$q = strtolower($q);
	$q = output_html($q);
	$q = $q . "%";
	$part_mysql = quote_smart($link, $q);




	//get matched data from skills table

	$query = "SELECT $t_users.user_id, $t_users.user_name, $t_users.user_alias, $t_users_profile.profile_first_name, $t_users_profile.profile_middle_name, $t_users_profile.profile_last_name FROM $t_users JOIN $t_users_profile ON $t_users.user_id=$t_users_profile.profile_id WHERE $t_users.user_name LIKE $part_mysql OR $t_users_profile.profile_first_name LIKE $part_mysql OR $t_users_profile.profile_last_name LIKE $part_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_user_id, $get_user_name, $get_user_alias, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name) = $row;


		// Photo
		$q = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id' AND photo_profile_image='1'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_photo_id, $get_photo_destination, $get_photo_thumb_40, $get_photo_thumb_50) = $rowb;
	

		echo"			";
		echo"
		<table>
		 <tr>
		  <td style=\"padding-right: 5px;\">
			<!-- Img -->
				<p>";
				if($get_photo_destination != "" && file_exists("$root/_uploads/users/images/$get_user_id/$get_photo_destination")){
					if(file_exists("$root/$get_photo_destination/$get_photo_thumb_50") && $get_photo_thumb_50 != ""){
						echo"
						<a href=\"#\" class=\"tags_select\" data-divid=\"$get_user_name\"><img src=\"$root/_uploads/users/images/$get_user_id/$get_photo_thumb_50\" alt=\"$get_station_member_user_image_file\" /></a>
						";
					}
					else{
						if($get_photo_thumb_50 != ""){
							// Make thumb
							$inp_new_x = 50; // 950
							$inp_new_y = 50; // 640
							resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_user_id/$get_photo_destination", "$root/_uploads/users/images/$get_user_id/$get_photo_thumb_50");
							echo"
							<a href=\"#\" class=\"tags_select\" data-divid=\"$get_user_name\"><img src=\"$root/_uploads/users/images/$get_user_id/$get_photo_thumb_50\" alt=\"$get_photo_thumb_50\" /></a>
							";
						}
					}
				}
				else{
					echo"
					<a href=\"#\" class=\"tags_select\" data-divid=\"$get_user_name\"><img src=\"_gfx/avatar_blank_50.png\" alt=\"avatar_blank_50.png\" /></a>
					";
				}

			echo"
				</p>
			<!-- //Img -->
		  </td>
		  <td>
			<!-- Name -->	
				<p>
				<a href=\"#\" class=\"tags_select\" data-divid=\"$get_user_name\">$get_user_name</a><br />
				<a href=\"#\" class=\"tags_select\" data-divid=\"$get_user_name\" style=\"color:black;\">$get_profile_first_name $get_profile_middle_name $get_profile_last_name</a>
				</p>
			<!-- //Name -->
		  </td>
		 </tr>
		</table>
		";
	}
	echo"
	<!-- Javascript on click add text to text input -->
		<script type=\"text/javascript\">
		\$(function() {
			\$('.tags_select').click(function() {
				var value = \$(this).data('divid');
            			var input = \$('#inp_user_name');
            			input.val(value);

				// Close
				\$(\"#autosearch_search_results_show\").html(''); 

            			return false;
       			});
    		});
		</script>
	<!-- //Javascript on click add text to text input -->
	";

}
else{
	echo"Missing q";
}

?>