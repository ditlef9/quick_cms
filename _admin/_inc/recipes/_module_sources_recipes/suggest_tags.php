<?php 
/**
*
* File: recipes/suggest_tags.php
* Version 1.0.0
* Date 22:33 05.02.2019
* Copyright (c) 2019 Localhost
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
include("_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");
include("$root/_admin/_translations/site/$l/recipes/ts_view_recipe.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}
$l_mysql = quote_smart($link, $l);


/*- Get recipe ------------------------------------------------------------------------- */
// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password) = $row;

/*- Headers ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$get_recipe_title - $l_recipes";
}
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to view was not found.
	</p>

	<p>
	<a href=\"index.php\">Back</a>
	</p>
	";
}
else{

	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
		// Find recipe owner
		$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$get_recipe_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_recipe_user_id, $get_recipe_user_email, $get_recipe_user_name, $get_recipe_user_alias) = $row;

		// Find me
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias) = $row;


		if($process == "1"){
			$inp_tags = $_POST['inp_tags'];
			$inp_tags = output_html($inp_tags);
			$inp_tags_mysql = quote_smart($link, $inp_tags);


			if(empty($inp_tags)){
				$url = "suggest_tags.php?recipe_id=$get_recipe_id&l=$l&ft=error&fm=missing_title&inp_rating=$inp_rating&inp_text=$inp_text";
				header("Location: $url");
				exit;
			}


			// Ip 
			// Maby this should be logged???
			$inp_ip = $_SERVER['REMOTE_ADDR'];
			$inp_ip = output_html($inp_ip);
			$inp_ip_mysql = quote_smart($link, $inp_ip);

			$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$inp_hostname = output_html($inp_hostname);
			$inp_hostname_mysql = quote_smart($link, $inp_hostname);

			$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
			$inp_user_agent = output_html($user_agent);
			$inp_user_agent_mysql = quote_smart($link, $user_agent);
			
				
			// Email to owner
			$date = date("j M Y");
			$subject = "$configWebsiteTitleSav $l_new_tags_suggestion_lowercase ($date)";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";



			$message = $message. "<p>$l_hello $get_recipe_user_alias,</p>

<p>
$get_my_user_alias $l_has_suggested_the_following_tags_for_your_recipe_lowercase $get_recipe_title:<br />
$inp_tags
</p>

<p>
$l_view_your_recipe:<br />
<a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l\">$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l</a>
</p>

<p>
$l_edit_recipe_tags:<br />
<a href=\"$configSiteURLSav/recipes/edit_recipe_tags.php?recipe_id=$get_recipe_id&l=$l\">$configSiteURLSav/recipes/edit_recipe_tags.php?recipe_id=$get_recipe_id&l=$l</a>
</p>

<p>
$l_sender_information:<br />
$l_user_id: $get_my_user_id<br />
$l_email: $get_my_user_email<br />
$l_username: $get_my_user_name<br />
$l_alias: $get_my_user_alias<br />
$l_ip: $inp_ip
</p>

<p>
--<br />
$l_regards<br />
$configFromNameSav<br />
$l_email: $configFromEmailSav<br />
$l_web: $configWebsiteTitleSav
</p>";


			$message = $message. "</body>\n";
			$message = $message. "</html>\n";


			$headers_mail_mod = array();
			$headers_mail_mod[] = 'MIME-Version: 1.0';
			$headers_mail_mod[] = 'Content-type: text/html; charset=utf-8';
			$headers_mail_mod[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
			mail($get_recipe_user_email, $subject, $message, implode("\r\n", $headers_mail_mod));




				$url = "view_recipe.php?recipe_id=$get_recipe_id&l=$l&ft=success&fm=suggestion_sent";
				header("Location: $url");
				exit;

		} // process

        	echo" 
		<h1>$get_recipe_title</h1>

		<!-- Where am I? -->
				<p>$l_you_are_here:<br />
				<a href=\"index.php?l=$l\">$l_recipes</a>
				&gt;
				<a href=\"view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\">$get_recipe_title</a>
				&gt;
				<a href=\"suggest_tags.php?recipe_id=$get_recipe_id&amp;l=$l\">$l_suggest_tags</a>
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


		<!-- New tags form -->
			<form method=\"post\" action=\"suggest_tags.php?recipe_id=$get_recipe_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_tags\"]').focus();
				});
				</script>
			<!-- //Focus -->
			
			<p><b>$l_your_tags_suggestions:</b><br />
			<input type=\"text\" name=\"inp_tags\" ";if(isset($_GET['inp_tags'])) { $inp_title = $_GET['inp_tags']; $inp_tags = strip_tags(stripslashes($inp_tags)); echo"value=\"$inp_tags\""; } echo" size=\"25\" />
			</p>

			<p>
			<input type=\"submit\" value=\"$l_send\" class=\"btn_default\" />
			</p>
			</form>
		<!-- //New tags form -->
		";


	} // logged in
	else{
		echo"
		<h1>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/recipes/suggest_tags.php?recipe_id=$get_recipe_id\">
		";	
	}
} // recipe found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>