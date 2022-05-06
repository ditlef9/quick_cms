<?php

/*- Current page ----------------------------------------------------------------------- */
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
include("$root/_admin/_translations/site/$l/meal_plans/ts_index.php");

/*- Special main mode ------------------------------------------------------------------ */
if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}


/*- Variables -------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if($include_as_navigation_main_mode == 0){
	echo"
							<ul class=\"toc\">
								<li class=\"header_home\"><a href=\"$root/meal_plans/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "meal_plans"){ echo" class=\"navigation_active\"";}echo">$l_meal_plans</a></li>
	";
}
echo"
	

								<li"; if($include_as_navigation_main_mode == 0){ echo" class=\"header_up\""; } echo"><a href=\"$root/meal_plans/user_pages.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "android"){ echo" class=\"navigation_active\"";}echo">$l_user_pages</a></li>
								<li><a href=\"$root/meal_plans/my_meal_plans.php?l=$l\""; if($minus_one == "my_meal_plans.php"){ echo" class=\"navigation_active\"";}echo">$l_my_meal_plans</a></li>
								<li><a href=\"$root/meal_plans/new_meal_plan.php?l=$l\""; if($minus_one == "new_meal_plan.php"){ echo" class=\"navigation_active\"";}echo">$l_new_meal_plan</a></li>

";

if($include_as_navigation_main_mode == 0){
	echo"
							</ul>
	";
}
?>