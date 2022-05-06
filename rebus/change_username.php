<?php
/**
*
* File: rebus/change_username.php
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
$website_title = "$l_change_username";
include("$root/_webdesign/header.php");



// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);



	// My user
	$query = "SELECT user_id, user_email, user_name, user_rank, user_notes FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_rank, $get_my_user_notes) = $row;
	if($get_my_user_notes == "can_change_username"){

		if($process == "1"){
			$inp_username = $_POST['inp_username'];
			$inp_username = output_html($inp_username);
			$inp_username_mysql = quote_smart($link, $inp_username);

			// Check availability
			$query = "SELECT user_id FROM $t_users WHERE user_name=$inp_username_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_user_id) = $row;
			if($get_user_id != ""){
				$url = "change_username.php?l=$l&ft=error&fm=that_username_is_taken";
				header("Location: $url");
				exit;
			}
			else{
				
				mysqli_query($link, "UPDATE $t_users SET
							user_name=$inp_username_mysql,
							user_alias=$inp_username_mysql,
							user_notes='username changed by user'
							WHERE user_id=$get_my_user_id") or die(mysqli_error($link));


				$url = "index.php?l=$l&ft=success&fm=username_changed";
				header("Location: $url");
				exit;
			}
		}

		echo"
		<!-- Headline -->
			<h1>$l_change_username</h1>
		<!-- //Headline -->

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_rebus</a>
			&gt;
			<a href=\"change_username.php?l=$l\">$l_change_username</a>
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

		<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_username\"]').focus();
		});
		</script>
		<!-- //Focus -->

		<!-- Change username form -->
			<h2>$l_edit_username</h2>
			<form method=\"post\" action=\"change_username.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_username:</b><br />
			<input type=\"text\" name=\"inp_username\" value=\"$get_my_user_name\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"$l_change_username\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>


			</form>
		<!-- //Change username form -->

	
		";
	} // can change username
	else{
		echo"<p>You cannot change username</p>
		<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" />
		<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/index.php\">
		";
		
	}
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