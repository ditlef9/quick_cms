<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");


/*- Config ----------------------------------------------------------------------------- */
include("../../_admin/_data/config/meta.php");
include("../../_admin/_data/config/user_system.php");
include("../../_admin/_data/logo.php");

/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables ---------------------------------------------------------------------- */
$t_users 	 	= $mysqlPrefixSav . "users";
$t_users_profile_photo	= $mysqlPrefixSav . "users_profile_photo";
$t_users_email_subscriptions 	= $mysqlPrefixSav . "users_email_subscriptions";
$t_users_moderator_of_the_week	= $mysqlPrefixSav . "users_moderator_of_the_week";
$t_recipes		= $mysqlPrefixSav . "recipes";
$t_comments		= $mysqlPrefixSav . "comments";
$t_comments_users_block	= $mysqlPrefixSav . "comments_users_block";


/*- Find user ------------------------------------------------------------------------- */
if(isset($_POST['inp_user_id'])){
	$inp_user_id = $_POST['inp_user_id'];
	$inp_user_id = output_html($inp_user_id);
	$inp_user_id_mysql = quote_smart($link, $inp_user_id);
}
else{
	echo"Missing user id";
	die;
}
if(isset($_POST['inp_user_password'])){
	$inp_user_password = $_POST['inp_user_password']; // Already encrypted
}
else{
	echo"Missing user password";
	die;
}

// Check for user
$query = "SELECT user_id, user_password, user_email, user_alias, user_date_format FROM $t_users WHERE user_id=$inp_user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id, $get_user_password, $get_my_user_email, $get_my_user_alias, $get_my_user_date_format) = $row;






if($get_user_id == ""){
	echo"User id";
	die;
}
if($get_user_password != "$inp_user_password"){
	echo"Wrong user password";
	die;
}	

// Get my profile image
$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$inp_user_id_mysql AND photo_profile_image='1'";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_my_photo_id, $get_my_photo_destination) = $rowb;


// Get my subscription status
$q = "SELECT es_id, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$inp_user_id_mysql AND es_type='comments'";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_my_es_id, $get_my_es_on_off) = $rowb;

if($get_my_es_id == ""){
	mysqli_query($link, "INSERT INTO $t_users_email_subscriptions
	(es_id, es_user_id, es_type, es_on_off) 
	VALUES 
	(NULL, $inp_user_id_mysql, 'comments', '1')") or die(mysqli_error($link));
	
	$get_my_es_on_off = "1";
}






/*- Find recipe ------------------------------------------------------------------------- */
if(isset($_POST['inp_recipe_id'])) {
	$inp_recipe_id = $_POST['inp_recipe_id'];
	$inp_recipe_id = strip_tags(stripslashes($inp_recipe_id));
	$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);

	$query = "SELECT recipe_id, recipe_user_id, recipe_language FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_user_id, $get_recipe_language) = $row;

	if($get_recipe_id == ""){
		echo"Recipe not found";
		die;
	}
}
else{
	echo"Missing inp recipe id";
	die;
}


/*- Object -------------------------------------------------------------------------------- */
$object_mysql = quote_smart($link, "recipe");

$object_id_mysql = quote_smart($link, $get_recipe_id);


/*- Comment text --------------------------------------------------------------------------- */
$inp_comment_text = $_POST['inp_comment_text'];
$inp_comment_text = output_html($inp_comment_text);
$inp_comment_text_mysql = quote_smart($link, $inp_comment_text);
if(empty($inp_comment_text)){
	echo"Missing comment";
	die;
}


$inp_comment_language = output_html($get_recipe_language);
$inp_comment_language_mysql = quote_smart($link, $inp_comment_language);

if(isset($_GET['comment_parent_id'])){
	$inp_comment_parent_id = $_POST['comment_parent_id'];
}
else{
	$inp_comment_parent_id = "0";
}
$inp_comment_parent_id = output_html($inp_comment_parent_id);
$inp_comment_parent_id_mysql = quote_smart($link, $inp_comment_parent_id);


$inp_user_ip = $_SERVER['REMOTE_ADDR'];
$inp_user_ip = output_html($inp_user_ip);
$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);

$inp_comment_user_name_mysql = quote_smart($link, $get_my_user_alias);

$inp_comment_user_avatar_mysql = quote_smart($link, $get_my_photo_destination);

$inp_comment_user_email_mysql = quote_smart($link, $get_my_user_email);

$inp_comment_user_subscribe_mysql = quote_smart($link, $get_my_es_on_off);

$inp_comment_created = date("Y-m-d H:i:s");
$inp_comment_updated = date("Y-m-d H:i:s");




/*- IP Block --------------------------------------------------------------------------- */

$my_user_ip = $_SERVER['REMOTE_ADDR'];
$my_user_ip = output_html($my_user_ip);
$my_user_ip_mysql = quote_smart($link, $my_user_ip);

$block_to = date("ymdh");

// Check by user ID
$query = "SELECT block_id FROM $t_comments_users_block WHERE block_user_id=$inp_user_id_mysql AND block_object=$object_mysql AND block_object_id=$object_id_mysql AND block_to=$block_to";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_block_id) = $row;
if($get_block_id != ""){
	echo"Please wait one hour before commenting again";
	die;
}
else{
	// Check by user IP
	$query = "SELECT block_id FROM $t_comments_users_block WHERE block_user_ip=$inp_user_id_mysql AND block_object=$object_mysql AND block_object_id=$object_id_mysql AND block_to=$block_to";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_block_id) = $row;
	if($get_block_id != ""){
		echo"Please wait one hour before commenting again";
		die;
	}
	else{
		mysqli_query($link, "INSERT INTO $t_comments_users_block
		(block_id, block_user_id, block_user_ip, block_object, block_object_id, block_to) 
		VALUES 
		(NULL, $inp_user_id_mysql, $my_user_ip_mysql, $object_mysql, $object_id_mysql, '$block_to')")
		or die(mysqli_error($link));
	}
}


/*- Insert comment ------------------------------------------------------------------ */
mysqli_query($link, "INSERT INTO $t_comments
(comment_id, comment_user_id, comment_language, 
comment_object, comment_object_id, comment_parent_id, 
comment_user_ip, comment_user_name, comment_user_avatar, 
comment_user_email, comment_user_subscribe, comment_created, 
comment_updated, comment_text, comment_likes, comment_dislikes, comment_reported, comment_approved) 
VALUES 
(NULL, $inp_user_id_mysql, $inp_comment_language_mysql, 
$object_mysql, $object_id_mysql, $inp_comment_parent_id_mysql, 
$inp_user_ip_mysql, $inp_comment_user_name_mysql, $inp_comment_user_avatar_mysql, 
$inp_comment_user_email_mysql, $inp_comment_user_subscribe_mysql, '$inp_comment_created', 
'$inp_comment_updated', $inp_comment_text_mysql, '0', '0', '0', '1')")
or die(mysqli_error($link));


// Get comment ID
$query = "SELECT comment_id FROM $t_comments WHERE comment_user_id=$inp_user_id_mysql AND comment_created='$inp_comment_created'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_comment_id) = $row;


echo"Comment saved";




/*- Email to moderator ---------------------------------------------------------------- */
// Who is moderator of the week?
$week = date("W");
$year = date("Y");

$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
if($get_moderator_user_id == ""){
	// Create moderator of the week
	include("../../_admin/_functions/create_moderator_of_the_week.php");
					
	$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
}


		
// Mail from
	
$referer_new = str_replace("../", "", $referer);

$security = md5("$inp_comment_created");
$approve_link = $configSiteURLSav . "/comments/approve_comment.php?comment_id=$get_comment_id&security=$security";
$decline_link = $configSiteURLSav . "/comments/decline_comment.php?comment_id=$get_comment_id&security=$security";

$view_link = $configSiteURLSav . "/recipes/view_recipe.php?recipe_id=$get_recipe_id#comments";
$report_link = $configSiteURLSav . "/comments/report_comment.php?comment_id=$get_comment_id&l=$get_object_owner_user_language";
$unsubscribe_link = $configSiteURLSav . "/comments/unsubscribe.php?user_id=$get_moderator_user_id&l=$get_object_owner_user_language";
		
// Commenter Img
if($get_my_photo_destination != ""){
	$commenter_image = $configSiteURLSav . "/image.php?width=64&amp;height=64&amp;cropratio=1:1&amp;image=/_uploads/users/images/$get_my_user_id/$get_my_photo_destination";
}
else{
	$commenter_image = $configSiteURLSav . "/comments/_gfx/avatar_blank_40.png";
}

		
// Email	
$subject = "New Recipe API comment from $get_my_user_alias at $host ($inp_comment_created)";

$message = "<html>\n";
$message = $message. "<head>\n";
$message = $message. "  <title>$subject</title>\n";
$message = $message. " </head>\n";
$message = $message. "<body>\n";

$message = $message . "<!-- Comment -->
	
			<table>
				 <tr>
				  <td style=\"padding-right: 10px;text-align:center;vertical-align: top;\">
					<p style=\"color: #000;font: normal 14px 'Open Sans',sans-serif;\">
					<img src=\"$commenter_image\" alt=\"commenter_image.png\" /><br />
					<a href=\"$configSiteURLSav/users/index.php?page=view_profile&user_id=$get_my_user_id&l=$get_object_owner_user_language\" style=\"color: #000;font: normal 14px 'Open Sans',sans-serif;text-decoration: none;\">$get_my_user_alias</a>
					</p>
				  </td>
				  <td style=\"vertical-align: top;\">

					<p style=\"color: #000;font: normal 14px 'Open Sans',sans-serif;\">
					$inp_comment_text
					</p>
					<p style=\"color: #000;font: normal 14px 'Open Sans',sans-serif;\">
					<a href=\"$view_link\" style=\"font: normal 14px 'Open Sans',sans-serif;\">View</a>
					&middot;
					<a href=\"$configSiteURLSav/comments/reply_comment.php?comment_id=$get_comment_id&l=$get_object_owner_user_language\" style=\"font: normal 14px 'Open Sans',sans-serif;\">Reply</a>
					&middot;
					<a href=\"$report_link\" style=\"font: normal 14px 'Open Sans',sans-serif;\">Report</a>
					</p>
				  </td>
				 </tr>
				</table>
				<!-- //Comment -->\n\n";


$message = $message . "<p>&nbsp;</p>\n";
$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav<br />\n$configSiteURLSav</p>";

$message = $message . "<p>&nbsp;</p>\n";
$message = $message . "<p style=\"color: #4a4a4a;font: normal 12px 'Open Sans',sans-serif;\">Dont want any more e-mails? <br />\n";
$message = $message . "Then you can unsubscribe:  <br />\n";
$message = $message . "<a href=\"$unsubscribe_link\">$unsubscribe_link</a></p>";

$message = $message. "</body>\n";
$message = $message. "</html>\n";

$encoding = "utf-8";

// Preferences for Subject field
$subject_preferences = array(
	       "input-charset" => $encoding,
	      "output-charset" => $encoding,
	       "line-length" => 76,
	       "line-break-chars" => "\r\n"
	);
$header = "Content-type: text/html; charset=".$encoding." \r\n";
$header .= "From: ".$configFromNameSav." <".$configFromEmailSav."> \r\n";
$header .= "MIME-Version: 1.0 \r\n";
$header .= "Content-Transfer-Encoding: 8bit \r\n";
$header .= "Date: ".date("r (T)")." \r\n";
$header .= iconv_mime_encode("Subject", $subject, $subject_preferences);
mail($get_moderator_user_email, $subject, $message, $header);

?>