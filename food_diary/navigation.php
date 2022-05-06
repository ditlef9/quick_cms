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
include("$root/_admin/_translations/site/$l/food_diary/ts_index.php");



/*- Special main mode ------------------------------------------------------------------ */
if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}


/*- Variables ----------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['main_category_id'])){
	$main_category_id= $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['sub_category_id'])){
	$sub_category_id= $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}

if($include_as_navigation_main_mode == 0){
	echo"
	<ul class=\"toc\">
		<li class=\"header_home\"><a href=\"$root/food_diary/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "food_diary"){ echo" class=\"navigation_active\"";}echo">$l_food_diary</a></li>
	";
}
echo"
	<li><a href=\"$root/food_diary/my_goal.php?l=$l\""; if($minus_one == "my_goal.php"){ echo" class=\"navigation_active\"";}echo">$l_my_goal</a></li>
	<li><a href=\"$root/food_diary/my_profile_data.php?l=$l\""; if($minus_one == "my_profile_data.php"){ echo" class=\"navigation_active\"";}echo">$l_my_profile_data</a></li>

";
if($include_as_navigation_main_mode == 0){
	echo"	
	</ul>
	";
}
?>