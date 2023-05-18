<?php
/**
*
* File: rebus/my_games.php
* Version 1.0.0.
* Date 09:50 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("_tables_rebus.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);



/*- Translation ------------------------------------------------------------------------ */


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_games";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
$main_class = "no_bg";
include("$root/_webdesign/header.php");



// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	echo"
	<!-- Headline -->
		<h1>$l_my_games</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"my_games.php?l=$l\">$l_my_games</a>
		</p>
	<!-- //Where am I ? -->


	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		$fm = str_replace("_", " ", $fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->



	<!-- Actions and Sorting -->
		<p>
		<a href=\"create_game_step_1_title.php?l=$l\" class=\"btn_default\">$l_create_game</a>
		<a href=\"groups.php?l=$l\" class=\"btn_default\">$l_groups</a>
		<a href=\"teams.php?l=$l\" class=\"btn_default\">$l_teams</a>
		</p>
	<!-- //Actions and Sorting -->
			

	<!-- My games -->
		<div class=\"games_row\">
		";
		// Find games I am owner of 
		$query = "SELECT owner_id, owner_game_id, game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_image_path, game_image_file, game_image_thumb_570x380, game_image_thumb_278x156, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_owners JOIN $t_rebus_games_index ON $t_rebus_games_owners.owner_game_id=$t_rebus_games_index.game_id WHERE owner_user_id=$my_user_id_mysql ORDER BY game_updated_datetime DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_owner_id, $get_owner_game_id, $get_game_id, $get_game_title, $get_game_language, $get_game_introduction, $get_game_description, $get_game_privacy, $get_game_published, $get_game_playable_after_datetime, $get_game_playable_after_time, $get_game_group_id, $get_game_group_name, $get_game_times_played, $get_game_image_path, $get_game_image_file, $get_game_image_thumb_570x380, $get_game_image_thumb_278x156, $get_game_country_id, $get_game_country_name, $get_game_county_id, $get_game_county_name, $get_game_municipality_id, $get_game_municipality_name, $get_game_city_id, $get_game_city_name, $get_game_place_id, $get_game_place_name, $get_game_created_by_user_id, $get_game_created_by_user_name, $get_game_created_by_user_email, $get_game_created_by_ip, $get_game_created_by_hostname, $get_game_created_by_user_agent, $get_game_created_datetime, $get_game_created_date_saying, $get_game_updated_by_user_id, $get_game_updated_by_user_name, $get_game_updated_by_user_email, $get_game_updated_by_ip, $get_game_updated_by_hostname, $get_game_updated_by_user_agent, $get_game_updated_datetime, $get_game_updated_date_saying) = $row;
			
			// Thumb
			if(file_exists("$root/$get_game_image_path/$get_game_image_file") && $get_game_image_file != ""){

				if(!(file_exists("$root/$get_game_image_path/$get_game_image_thumb_570x380")) OR $get_game_image_thumb_570x380 == ""){
					$ext = get_extension($get_game_image_file);
					$org_file_name = str_replace(".$ext", "", $get_game_image_file);
				
					$inp_thumb = $org_file_name . "_thumb_570x380" . ".$ext";
					$inp_thumb_mysql = quote_smart($link, $inp_thumb);

					mysqli_query($link, "UPDATE $t_rebus_games_index SET game_image_thumb_570x380=$inp_thumb_mysql WHERE game_id=$get_game_id") or die(mysqli_error($link));


					resize_crop_image(570, 380, "$root/$get_game_image_path/$get_game_image_file", "$root/$get_game_image_path/$inp_thumb");

					// Transfer
					$get_game_image_thumb_570x380 = "$inp_thumb";
				}
			}
			else{
				$get_game_image_path = "rebus/_gfx";
				$get_game_image_thumb_570x380 = "no_thumb_570x380.jpg";
			}

			echo"
			<div class=\"games_column\">
				<div class=\"games_column_inner\">
					<p>
					<a href=\"edit_game.php?game_id=$get_game_id&amp;l=$get_game_language\"><img src=\"$root/$get_game_image_path/$get_game_image_thumb_570x380\" alt=\"$get_game_image_thumb_570x380\" /></a><br />
					<a href=\"edit_game.php?game_id=$get_game_id&amp;l=$get_game_language\" class=\"h2\">$get_game_title</a><br />
				
					<span class=\"grey\">$get_game_place_name";
					if($get_game_city_name != "" && $get_game_city_name != "$get_game_place_name"){
						echo" ($get_game_city_name)";
					}
					echo"
					</span>

					</p>
				</div>
			</div>";
		} // while games
		echo"
		</div>
	<!-- //My games -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/my_games.php\">

	<p>Please log in...</p>
	";
}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>