<?php

/*- Current page --------------------------------------------------------------------- */
$self 		= $_SERVER['PHP_SELF'];
$request_url 	= $_SERVER["REQUEST_URI"];
$self_array     = explode("/", $self);
$array_size     = sizeof($self_array);

$minus_one	= $array_size-1;
$minus_one	= $self_array[$minus_one];

$minus_two	= $array_size-2;
$minus_two	= $self_array[$minus_two];

$complex	= $minus_two . "/" . $minus_one;



/*- Language --------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/workout_plans/ts_workout_plans.php");

/*- Special main mode ------------------------------------------------------------------ */
if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}


/*- Variables --------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['type_id'])){
	$type_id = $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
}
if(isset($_GET['main_group_id'])){
	$main_group_id = $_GET['main_group_id'];
	$main_group_id = strip_tags(stripslashes($main_group_id));
}
else{
	$main_group_id = "";
}
if(isset($_GET['sub_group_id'])){
	$sub_group_id= $_GET['sub_group_id'];
	$sub_group_id = strip_tags(stripslashes($sub_group_id));
}
else{
	$sub_group_id = "";
}

if($include_as_navigation_main_mode == 0){
	echo"
							<ul class=\"toc\">
								<li class=\"header_home\"><a href=\"$root/workout_diary/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "workout_plans"){ echo" class=\"navigation_active\"";}echo">$l_workout_plans</a></li>

	";
}
echo"
								<li><a href=\"$root/workout_diary/my_workout_plans.php?l=$l\""; if($minus_one == "my_workout_plans.php"){ echo" class=\"navigation_active\"";}echo">$l_my_workout_plans</a></li>



";

if($include_as_navigation_main_mode == 0){
	echo"
							</ul>
	";
}
?>