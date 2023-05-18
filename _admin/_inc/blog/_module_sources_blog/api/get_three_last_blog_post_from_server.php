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
$t_blog_info 		= $mysqlPrefixSav . "blog_info";
$t_blog_categories	= $mysqlPrefixSav . "blog_categories";
$t_blog_posts 		= $mysqlPrefixSav . "blog_posts";
$t_blog_posts_tags 	= $mysqlPrefixSav . "blog_posts_tags";
$t_blog_images	 	= $mysqlPrefixSav . "blog_images";


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
}
else{
	$l = "";
}
$l_mysql = quote_smart($link, $l);



/*- Get blog post ------------------------------------------------------------------------- */

// Build array
$rows_array = array();

$q = "SELECT blog_post_id, blog_post_user_id, blog_post_category_id FROM $t_blog_posts WHERE blog_post_language=$l_mysql AND blog_post_privacy_level='everyone' ORDER BY blog_post_id DESC LIMIT 0,3";
$r = mysqli_query($link, $q);
while($rows = mysqli_fetch_row($r)) {
	list($get_current_blog_post_id, $get_current_blog_post_user_id, $get_current_blog_post_category_id) = $rows;

	// Build array
	$return_array = array();


	// Info
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views FROM $t_blog_info WHERE blog_user_id=$get_current_blog_post_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$return_array['info'] = $row;

	// Category
	$query = "SELECT * FROM $t_blog_categories WHERE blog_category_id=$get_current_blog_post_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$return_array['category'] = $row;

	// Post
	$query = "SELECT blog_post_id, blog_post_user_id, blog_post_title, blog_post_language, blog_post_category_id, blog_post_introduction, blog_post_privacy_level, blog_post_text, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_ad, blog_post_created, blog_post_updated, blog_post_allow_comments, blog_post_comments, blog_post_views FROM $t_blog_posts WHERE blog_post_id=$get_current_blog_post_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$return_array['post'] = $row;

	// Tags
	$return_array['tags'] = array();
	$query = "SELECT * FROM $t_blog_posts_tags WHERE blog_post_id=$get_current_blog_post_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		$return_array['tags'][] = $row;
	}

	
	array_push($rows_array, $return_array);

}
// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";



?>