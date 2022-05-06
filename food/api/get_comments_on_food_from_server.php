<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


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


/*- MySQL Tables -------------------------------------------------------------------- */
$t_comments	= $mysqlPrefixSav . "comments";
$t_users 	=  $mysqlPrefixSav . "users";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['food_id'])) {
	$food_id = $_GET['food_id'];
	$food_id = strip_tags(stripslashes($food_id));
}
else{
	$food_id = "";
}

$l_mysql = quote_smart($link, $l);


/*- Get comments ------------------------------------------------------------------------- */

$rows_array = array();
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT comment_id, comment_user_id, comment_language, comment_object, comment_object_id, comment_parent_id, comment_user_ip, comment_user_name, comment_user_avatar, comment_user_email, comment_user_subscribe, comment_created, comment_updated, comment_text, comment_likes, comment_dislikes, comment_reported, comment_report_checked, comment_approved FROM $t_comments WHERE comment_object='food' AND comment_object_id=$food_id_mysql AND comment_parent_id='0' AND comment_approved='1'";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}


// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";




?>