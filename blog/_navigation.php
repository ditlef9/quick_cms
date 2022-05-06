<?php

/*- Current page ------------------------------------------------------------------------- */
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
include("$root/_admin/_translations/site/$l/blog/ts_blog.php");


/*- Special main mode ------------------------------------------------------------------ */
if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}


/*- Variables -------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);



if($include_as_navigation_main_mode == 0){
	echo"
	<ul class=\"toc\">
		<li class=\"header_home\"><a href=\"$root/blog/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "blog"){ echo" class=\"navigation_active\"";}echo">$l_blog</a></li>
	";
}
echo"
	

							<li><a href=\"$root/blog/user_pages.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "android"){ echo" class=\"navigation_active\"";}echo">$l_user_pages</a></li>
							<li><a href=\"$root/blog/my_blog.php?l=$l\""; if($minus_one == "my_blog.php"){ echo" class=\"navigation_active\"";}echo">$l_my_blog</a></li>
							<li><a href=\"$root/blog/my_blog_new_post.php?l=$l\""; if($minus_one == "my_blog_new_post.php"){ echo" class=\"navigation_active\"";}echo">$l_new_post</a></li>
";

if($include_as_navigation_main_mode == 0){
	echo"
	</ul>
	";
}
?>