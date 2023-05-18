<?php
/**
*
* File: forum/ignored_tags.php
* Version 1.0.0.
* Date 22:49 13.04.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");

/*- Forum config ------------------------------------------------------------------------ */
include("_include_tables.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['ignore_id'])) {
	$ignore_id = $_GET['ignore_id'];
	$ignore_id = strip_tags(stripslashes($ignore_id));
	$ignore_id = output_html($ignore_id);
}
else{
	$ignore_id = "";
}

/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_ignored_tags - $get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");



// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

	if($action == ""){
		echo"
		<h1>$l_ignored_tags</h1>

		<!-- Watched tags -->
			<div class=\"vertical_list\">
				<ul>
				";
				$query_w = "SELECT ignore_id, ignore_tag_id, ignore_user_id, tag_id, tag_title_clean, tag_icon_path, tag_icon_file_16  FROM $t_forum_tags_ignore JOIN $t_forum_tags_index ON $t_forum_tags_ignore.ignore_tag_id=$t_forum_tags_index.tag_id WHERE $t_forum_tags_ignore.ignore_user_id=$my_user_id_mysql";
				$result_w = mysqli_query($link, $query_w);
				while($row_w = mysqli_fetch_row($result_w)) {
					list($get_ignore_id, $get_ignore_tag_id, $get_ignore_user_id, $get_tag_id, $get_tag_title_clean, $get_tag_icon_path, $get_tag_icon_file_16) = $row_w;

					echo"
					<li"; if($x == 0){ echo" style=\"clear: left;\""; } echo"><a href=\"open_tag.php?tag=$get_tag_title_clean&amp;l=$l\"";
					if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
						// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
						echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
					}
					echo">$get_tag_title_clean</a>
					<a href=\"ignored_tags.php?action=remove_from_ignore_list&amp;ignore_id=$get_ignore_id&amp;l=$l\"><img src=\"_gfx/delete.png\" alt=\"delete.png\" /></a></li>
					";
				}
				echo"
				</ul>
			</div>

		<!-- //Watched tags -->
		";
	} // action == ""
	elseif($action == "remove_from_ignore_list"){

		$ignore_id_mysql = quote_smart($link, $ignore_id);
		$query = "SELECT ignore_id, ignore_tag_id, ignore_user_id FROM $t_forum_tags_ignore WHERE ignore_id=$ignore_id_mysql AND ignore_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_ignore_id, $get_current_ignore_tag_id, $get_current_ignore_user_id) = $row;
		
		if($get_current_ignore_id == ""){
			echo"Not found";
		}
		else{
			// Get tag
			$query = "SELECT tag_id, tag_title, tag_title_clean FROM $t_forum_tags_index WHERE tag_id=$get_current_ignore_tag_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_tag_id, $get_tag_title, $get_tag_title_clean) = $row;
		
			if($process == "1"){
				$result = mysqli_query($link, "DELETE FROM $t_forum_tags_ignore WHERE ignore_id=$ignore_id_mysql AND ignore_user_id=$my_user_id_mysql");
				header("Location: ignored_tags.php?=$l&ft=success&fm=removed");
				exit;
			}


			echo"
			<h1>$l_ignore $get_tag_title_clean</h1>

			<p>$l_are_you_sure_you_want_to_remove_from_the_ignore_list</p>

			<p>
			<a href=\"ignored_tags.php?action=remove_from_ignore_list&amp;ignore_id=$get_ignore_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_yes</a>
			<a href=\"ignored_tags.php?=$l\" class=\"btn\">$l_no</a>
			</p>
			";
		} // found

	} // unwatch
}
else{
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=forum/watched_tags.php\">
	";
} // not logged in


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>