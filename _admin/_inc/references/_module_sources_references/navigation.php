<?php
/*
* references/navigation.php
*/

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


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['main_category_id'])){
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}


/*- Tables ---------------------------------------------------------------------------- */
$t_references_title_translations = $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 = $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 = $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 = $mysqlPrefixSav . "references_index";
$t_references_index_groups	 = $mysqlPrefixSav . "references_index_groups";
$t_references_index_guides	 = $mysqlPrefixSav . "references_index_guides";

/*- Language ------------------------------------------ */


/*- Settings ------------------------------------------ */


/*- Variables ----------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}
if($include_as_navigation_main_mode == 0){

	echo"
	<ul class=\"toc\">
		<li class=\"header_home\"><a href=\"$root/references/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "references"){ echo" class=\"navigation_active\"";}echo">References</a></li>
	";
}

// Categories
$query = "SELECT main_category_id, main_category_title FROM $t_references_categories_main WHERE main_category_language=$l_mysql ORDER BY main_category_title ASC";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_main_category_id, $get_main_category_title) = $row;
	echo"		";
	echo"<li><a href=\"$root/references/open_main_category.php?main_category_id=$get_main_category_id&amp;l=$l\">$get_main_category_title</a></li>\n";


} // while main

if($include_as_navigation_main_mode == 0){
	echo"
	</ul>
	";
}
?>