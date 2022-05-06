<?php
/**
*
* File: rebus/teams_new.php
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
$tabindex = 0;

/*- Translation ------------------------------------------------------------------------ */


/*- Headers ---------------------------------------------------------------------------- */
$website_title = "$l_new_team - $l_teams";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);


	if($process == "1"){
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);
		if($inp_name == ""){
			$url = "team_new.php?l=$l&ft=error&fm=missing_name";
			header("Location: $url");
			exit;
		}
			
		$l_mysql = quote_smart($link, $l);

		$inp_privacy = $_POST['inp_privacy'];
		$inp_privacy = output_html($inp_privacy);
		$inp_privacy_mysql = quote_smart($link, $inp_privacy);

		// Group
		$inp_group_id = $_POST['inp_group_id'];
		$inp_group_id = output_html($inp_group_id);
		$inp_group_id_mysql = quote_smart($link, $inp_group_id);

		$inp_group_name = "";

		if($inp_group_id != "0"){
			// Find group
			$query = "SELECT group_id, group_name FROM $t_rebus_groups_index WHERE group_id=$inp_group_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_group_id, $get_group_name) = $row;
			
			// Check that I am a member of that group
			$query = "SELECT member_id FROM $t_rebus_groups_members WHERE member_group_id=$get_group_id AND member_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_member_id) = $row;

			if($get_member_id != ""){
				$inp_group_id = "$get_group_id";
				$inp_group_id = output_html($inp_group_id);
				$inp_group_id_mysql = quote_smart($link, $inp_group_id);

				$inp_group_name = output_html($get_group_name);
			}
		}
		$inp_group_name_mysql = quote_smart($link, $inp_group_name);


		// Key
		$characters = '023456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
    		$charactersLength = strlen($characters);
    		$inp_key = '';
    		for ($i = 0; $i < 6; $i++) {
        		$inp_key .= $characters[rand(0, $charactersLength - 1)];
    		}
		$inp_key_mysql = quote_smart($link, $inp_key);

		// Me
		$query = "SELECT user_id, user_email, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;
		
		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

		// Profile photo
		$query = "SELECT photo_id, photo_destination, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_50) = $row;

		$inp_my_photo_destination_mysql = quote_smart($link, $get_my_photo_destination);
		$inp_my_photo_thumb_50_mysql = quote_smart($link, $get_my_photo_thumb_50);

		// Ip 
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);

		$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$my_hostname = output_html($my_hostname);
		$my_hostname_mysql = quote_smart($link, $my_hostname);

		$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
		$my_user_agent = output_html($my_user_agent);
		$my_user_agent_mysql = quote_smart($link, $my_user_agent);

		// Dates
		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		// Check if tream exists
		$query = "SELECT team_id FROM $t_rebus_teams_index WHERE team_name=$inp_name_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_team_id) = $row;
		if($get_team_id != ""){
			$url = "team_new.php?privacy=$inp_privacy&group_id=$inp_group_id&l=$l&ft=error&fm=there_is_already_a_team_with_that_name_(" . $inp_name . ")";
			header("Location: $url");
			exit;
		}

		// Pick color
		$json = '{

	  "aliceblue": "#f0f8ff",
  "antiquewhite": "#faebd7",
  "aqua": "#00ffff",
  "aquamarine": "#7fffd4",
  "azure": "#f0ffff",
  "beige": "#f5f5dc",
  "bisque": "#ffe4c4",
  "black": "#000000",
  "blanchedalmond": "#ffebcd",
  "blue": "#0000ff",
  "blueviolet": "#8a2be2",
  "brown": "#a52a2a",
  "burlywood": "#deb887",
  "cadetblue": "#5f9ea0",
  "chartreuse": "#7fff00",
  "chocolate": "#d2691e",
  "coral": "#ff7f50",
  "cornflowerblue": "#6495ed",
  "cornsilk": "#fff8dc",
  "crimson": "#dc143c",
  "cyan": "#00ffff",
  "darkblue": "#00008b",
  "darkcyan": "#008b8b",
  "darkgoldenrod": "#b8860b",
  "darkgray": "#a9a9a9",
  "darkgreen": "#006400",
  "darkgrey": "#a9a9a9",
  "darkkhaki": "#bdb76b",
  "darkmagenta": "#8b008b",
  "darkolivegreen": "#556b2f",
  "darkorange": "#ff8c00",
  "darkorchid": "#9932cc",
  "darkred": "#8b0000",
  "darksalmon": "#e9967a",
  "darkseagreen": "#8fbc8f",
  "darkslateblue": "#483d8b",
  "darkslategray": "#2f4f4f",
  "darkslategrey": "#2f4f4f",
  "darkturquoise": "#00ced1",
  "darkviolet": "#9400d3",
  "deeppink": "#ff1493",
  "deepskyblue": "#00bfff",
  "dimgray": "#696969",
  "dimgrey": "#696969",
  "dodgerblue": "#1e90ff",
  "firebrick": "#b22222",
  "floralwhite": "#fffaf0",
  "forestgreen": "#228b22",
  "fuchsia": "#ff00ff",
  "gainsboro": "#dcdcdc",
  "ghostwhite": "#f8f8ff",
  "goldenrod": "#daa520",
  "gold": "#ffd700",
  "gray": "#808080",
  "green": "#008000",
  "greenyellow": "#adff2f",
  "grey": "#808080",
  "honeydew": "#f0fff0",
  "hotpink": "#ff69b4",
  "indianred": "#cd5c5c",
  "indigo": "#4b0082",
  "ivory": "#fffff0",
  "khaki": "#f0e68c",
  "lavenderblush": "#fff0f5",
  "lavender": "#e6e6fa",
  "lawngreen": "#7cfc00",
  "lemonchiffon": "#fffacd",
  "lightblue": "#add8e6",
  "lightcoral": "#f08080",
  "lightcyan": "#e0ffff",
  "lightgoldenrodyellow": "#fafad2",
  "lightgray": "#d3d3d3",
  "lightgreen": "#90ee90",
  "lightgrey": "#d3d3d3",
  "lightpink": "#ffb6c1",
  "lightsalmon": "#ffa07a",
  "lightseagreen": "#20b2aa",
  "lightskyblue": "#87cefa",
  "lightslategray": "#778899",
  "lightslategrey": "#778899",
  "lightsteelblue": "#b0c4de",
  "lightyellow": "#ffffe0",
  "lime": "#00ff00",
  "limegreen": "#32cd32",
  "linen": "#faf0e6",
  "magenta": "#ff00ff",
  "maroon": "#800000",
  "mediumaquamarine": "#66cdaa",
  "mediumblue": "#0000cd",
  "mediumorchid": "#ba55d3",
  "mediumpurple": "#9370db",
  "mediumseagreen": "#3cb371",
  "mediumslateblue": "#7b68ee",
  "mediumspringgreen": "#00fa9a",
  "mediumturquoise": "#48d1cc",
  "mediumvioletred": "#c71585",
  "midnightblue": "#191970",
  "mintcream": "#f5fffa",
  "mistyrose": "#ffe4e1",
  "moccasin": "#ffe4b5",
  "navajowhite": "#ffdead",
  "navy": "#000080",
  "oldlace": "#fdf5e6",
  "olive": "#808000",
  "olivedrab": "#6b8e23",
  "orange": "#ffa500",
  "orangered": "#ff4500",
  "orchid": "#da70d6",
  "palegoldenrod": "#eee8aa",
  "palegreen": "#98fb98",
  "paleturquoise": "#afeeee",
  "palevioletred": "#db7093",
  "papayawhip": "#ffefd5",
  "peachpuff": "#ffdab9",
  "peru": "#cd853f",
  "pink": "#ffc0cb",
  "plum": "#dda0dd",
  "powderblue": "#b0e0e6",
  "purple": "#800080",
  "rebeccapurple": "#663399",
  "red": "#ff0000",
  "rosybrown": "#bc8f8f",
  "royalblue": "#4169e1",
  "saddlebrown": "#8b4513",
  "salmon": "#fa8072",
  "sandybrown": "#f4a460",
  "seagreen": "#2e8b57",
  "seashell": "#fff5ee",
  "sienna": "#a0522d",
  "silver": "#c0c0c0",
  "skyblue": "#87ceeb",
  "slateblue": "#6a5acd",
  "slategray": "#708090",
  "slategrey": "#708090",
  "snow": "#fffafa",
  "springgreen": "#00ff7f",
  "steelblue": "#4682b4",
  "tan": "#d2b48c",
  "teal": "#008080",
  "thistle": "#d8bfd8",
  "tomato": "#ff6347",
  "turquoise": "#40e0d0",
  "violet": "#ee82ee",
  "wheat": "#f5deb3",
  "white": "#ffffff",
  "whitesmoke": "#f5f5f5",
  "yellow": "#ffff00",
		  "yellowgreen": "#9acd32"}';

		
		$arr = json_decode($json, true);
		$size = sizeof($arr);
		$count = 0;
		$random = rand(1, $size);
		$inp_color = "";
		foreach($arr as $key => $value) {
			if($count == "$random"){
				$inp_color = "$key";
			}
			$count++;
		}
		$inp_color = output_html($inp_color);
		$inp_color_mysql = quote_smart($link, $inp_color);


		// Create team
		mysqli_query($link, "INSERT INTO $t_rebus_teams_index
		(team_id, team_name, team_language, team_description, team_privacy, 
		team_key, team_group_id, team_group_name, team_color, team_created_by_user_id, 
		team_created_by_user_name, team_created_by_user_email, team_created_by_ip, team_created_by_hostname, team_created_by_user_agent, 
		team_created_datetime, team_created_date_saying) 
		VALUES 
		(NULL, $inp_name_mysql, $l_mysql, '', $inp_privacy_mysql, 
		$inp_key_mysql, $inp_group_id_mysql, $inp_group_name_mysql, $inp_color_mysql, $get_my_user_id, 
		$inp_my_user_name_mysql, $inp_my_user_email_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, 
		'$datetime', '$date_saying')")
		or die(mysqli_error($link));

		// Get id
		$query = "SELECT team_id FROM $t_rebus_teams_index WHERE team_created_by_user_id=$get_my_user_id AND team_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_team_id) = $row;

		// Insert me as member
		mysqli_query($link, "INSERT INTO $t_rebus_teams_members
		(member_id, member_team_id, member_user_id, member_user_name, member_user_email, 
		member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, 
		member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying) 
		VALUES 
		(NULL, $get_current_team_id, $get_my_user_id, $inp_my_user_name_mysql, $inp_my_user_email_mysql, 
		$inp_my_photo_destination_mysql, $inp_my_photo_thumb_50_mysql, 'admin', 1, 1, 
		1, '$datetime', '$date_saying')")
		or die(mysqli_error($link));

		// Open team
		$url = "team_members.php?action=invite_member&team_id=$get_current_team_id&l=$l&ft=success&fm=team_created";
		header("Location: $url");
		exit;


	} // process

	echo"
	<!-- Headline -->
		<h1>$l_new_team</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"teams.php?l=$l\">$l_teams</a>
		&gt;
		<a href=\"team_new.php?l=$l\">$l_new_team</a>
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
			\$('[name=\"inp_name\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- New team form -->
		<form method=\"post\" action=\"team_new.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>$l_name:</b><br />
		<input type=\"text\" name=\"inp_name\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p><b>$l_privacy:</b><br />";
		if(isset($_GET['privacy'])) {
			$privacy = $_GET['privacy'];
			$privacy = output_html($privacy);
		}
		else{
			$privacy = "private";
		}
		echo"
		<input type=\"radio\" name=\"inp_privacy\" value=\"public\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($privacy == "public"){ echo" checked=\"checked\""; } echo" /> $l_public &nbsp;
		<input type=\"radio\" name=\"inp_privacy\" value=\"private\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($privacy == "private"){ echo" checked=\"checked\""; } echo" /> $l_private
		</p>

		<p><b>$l_team_is_a_part_of_group:</b>";
		if(isset($_GET['group_id'])) {
			$group_id = $_GET['group_id'];
			$group_id = output_html($group_id);
			if(!(is_numeric($group_id))){
				echo"Group id not numeric";
				die;
			}
		}
		else{
			$group_id = "0";
		}
		echo"
		(<a href=\"new_group.php?l=$l\">$l_create_group</a>)<br />
		<select name=\"inp_group_id\">
			<option value=\"0\""; if($group_id == "0"){ echo" selected=\"selected\""; } echo">$l_none</selected>";
			$query = "SELECT member_id, member_group_id, group_name FROM $t_rebus_groups_members JOIN $t_rebus_groups_index ON $t_rebus_groups_members.member_group_id=$t_rebus_groups_index.group_id WHERE member_user_id=$my_user_id_mysql ORDER BY $t_rebus_groups_index.group_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_member_id, $get_member_group_id, $get_group_name) = $row;
				echo"			<option value=\"$get_member_group_id\""; if($group_id == "$get_member_group_id"){ echo" selected=\"selected\""; } echo">$get_group_name</selected>\n";
			}
			echo"
		</select></p>

		<p><input type=\"submit\" value=\"$l_create_team\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
		
		</form>
	<!-- //New team form -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/team_new.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>