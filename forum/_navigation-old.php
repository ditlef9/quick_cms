<?php

/*- Current page ---------------------------------------- */
$self 		= $_SERVER['PHP_SELF'];
$request_url 	= $_SERVER["REQUEST_URI"];
$self_array     = explode("/", $self);
$array_size     = sizeof($self_array);

$minus_one	= $array_size-1;
$minus_one	= $self_array[$minus_one];

$minus_two	= $array_size-2;
$minus_two	= $self_array[$minus_two];

$complex	= $minus_two . "/" . $minus_one;


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");

/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");

/*- Variables ----------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}
if($include_as_navigation_main_mode == 0){
	$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;



	echo"
	<ul class=\"toc\">
		<li class=\"header_home\"><a href=\"$root/forum/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "forum"){ echo" class=\"navigation_active\"";}echo">$get_current_title_value</a></li>
	";
}
echo"
	

	<li><a href=\"$root/forum/user_pages.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "android"){ echo" class=\"navigation_active\"";}echo">$l_user_pages</a></li>
	<li><a href=\"$root/forum/hall_of_fame.php?l=$l\""; if($minus_one == "hall_of_fame.php"){ echo" class=\"navigation_active\"";}echo">$l_hall_of_fame</a></li>
	<li><a href=\"$root/forum/new_topic.php?l=$l\""; if($minus_one == "new_topic.php"){ echo" class=\"navigation_active\"";}echo">$l_new_topic</a></li>

	";
	if($include_as_navigation_main_mode == 0 && $forumShowTagsBelowNavSav == "1"){
		echo"
		<!-- Tags -->
			<li class=\"header_home\" style=\"margin: 30px 0px 0px 0px;padding-bottom: 0px;\"><a href=\"$root/forum/tags.php?l=$l\""; if($minus_one == "tags.php" && $minus_two == "forum"){ echo" class=\"navigation_active\"";}echo" style=\"margin: 0px 0px 0px 0px;padding-bottom: 0px;\">$l_tags</a></li>
			</ul>
			<ul class=\"forum_watch_tags_list\" style=\"margin-top: 0px;padding-top: 0px;\">
			";
			$x = 0;
			$query_w = "SELECT tag_id, tag_title_clean, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_index WHERE tag_is_official='1'";
			$result_w = mysqli_query($link, $query_w);
			while($row_w = mysqli_fetch_row($result_w)) {
				list($get_tag_id, $get_tag_title_clean, $get_tag_icon_path, $get_tag_icon_file_16) = $row_w;

				echo"
				<li class=\"$forumCSSToUseOnLiTagsBelowNavSav\""; if($x == 0){ echo" style=\"clear: left;\""; } echo"><a href=\"open_tag.php?tag=$get_tag_title_clean&amp;l=$l\" class=\"$forumCSSToUseOnATagsBelowNavSav\"";
				if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
					// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
					echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
				}
				echo">$get_tag_title_clean</a></li>
				";

				$x++;
			} // tags
		echo"
			</ul>
		<!-- //Tags -->

		";
	}


	if($include_as_navigation_main_mode == 0 && $forumShowWatchedAndIgnoredTagsBelowNavSav == "1"){
		echo"
		<!-- Watched tags -->

			<ul class=\"forum_watch_tags_list\" style=\"margin-top: 0px;padding-top: 0px;\">
				<li class=\"header_home\" style=\"margin: 30px 0px 0px 0px;padding-bottom: 0px;\"><a href=\"$root/forum/watched_tags.php?l=$l\""; if($minus_one == "watched_tags.php" && $minus_two == "forum"){ echo" class=\"navigation_active\"";}echo" style=\"margin: 0px 0px 0px 0px;padding-bottom: 0px;\">$l_watched_tags</a></li>
				";
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					$my_user_id = $_SESSION['user_id'];
					$my_user_id = output_html($my_user_id);
					$my_user_id_mysql = quote_smart($link, $my_user_id);

					$x = 0;
					$query_w = "SELECT watch_id, watch_tag_id, tag_id, tag_title_clean, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_watch JOIN $t_forum_tags_index ON $t_forum_tags_watch.watch_tag_id=$t_forum_tags_index.tag_id WHERE $t_forum_tags_watch.watch_user_id=$my_user_id_mysql";
					$result_w = mysqli_query($link, $query_w);
					while($row_w = mysqli_fetch_row($result_w)) {
						list($get_watch_id, $get_watch_tag_id, $get_tag_id, $get_tag_title_clean, $get_tag_icon_path, $get_tag_icon_file_16) = $row_w;

						echo"
						<li class=\"$forumCSSToUseOnLiTagsBelowNavSav\""; if($x == 0){ echo" style=\"clear: left;\""; } echo"><a href=\"open_tag.php?tag=$get_tag_title_clean&amp;l=$l\" class=\"$forumCSSToUseOnATagsBelowNavSav\"";
						if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
							// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
							echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
						}
						echo">$get_tag_title_clean</a></li>
						";

						$x++;
					}
				} // logged in
		echo"
			</ul>
		<!-- //Watched tags -->
		<!-- Ignored tags -->
			<ul class=\"forum_watch_tags_list\" style=\"margin-top: 0px;padding-top: 0px;\">
				<li class=\"header_home\" style=\"margin: 30px 0px 0px 0px;padding-bottom: 0px;\"><a href=\"$root/forum/ignored_tags.php?l=$l\""; if($minus_one == "ignored_tags.php" && $minus_two == "forum"){ echo" class=\"navigation_active\"";}echo" style=\"margin: 0px 0px 0px 0px;padding-bottom: 0px;\">$l_ignored_tags</a></li>
			
				";
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					$my_user_id = $_SESSION['user_id'];
					$my_user_id = output_html($my_user_id);
					$my_user_id_mysql = quote_smart($link, $my_user_id);

					$x = 0;
					$query_w = "SELECT ignore_id, ignore_tag_id, tag_id, tag_title_clean, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_ignore JOIN $t_forum_tags_index ON $t_forum_tags_ignore.ignore_tag_id=$t_forum_tags_index.tag_id WHERE $t_forum_tags_ignore.ignore_user_id=$my_user_id_mysql";
					$result_w = mysqli_query($link, $query_w);
					while($row_w = mysqli_fetch_row($result_w)) {
						list($get_ignore_id, $get_ignore_tag_id, $get_tag_id, $get_tag_title_clean, $get_tag_icon_path, $get_tag_icon_file_16) = $row_w;

						echo"
						<li class=\"$forumCSSToUseOnLiTagsBelowNavSav\""; if($x == 0){ echo" style=\"clear: left;\""; } echo"><a href=\"open_tag.php?tag=$get_tag_title_clean&amp;l=$l\" class=\"$forumCSSToUseOnATagsBelowNavSav\"";
						if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
							// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
							echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
						}
						echo">$get_tag_title_clean</a></li>
						";

						$x++;
					}
				} // logged in
		echo"
			</ul>
		<ul class=\"toc\">
		<!-- //Ignored tags -->
		";
	}

if($include_as_navigation_main_mode == 0){
	echo"
	</ul>
	";
}
?>