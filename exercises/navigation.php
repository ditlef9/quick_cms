<?php

/*- Current page ---------------------------------------------------------------------- */
$self 		= $_SERVER['PHP_SELF'];
$request_url 	= $_SERVER["REQUEST_URI"];
$self_array     = explode("/", $self);
$array_size     = sizeof($self_array);

$minus_one	= $array_size-1;
$minus_one	= $self_array[$minus_one];

$minus_two	= $array_size-2;
$minus_two	= $self_array[$minus_two];

$complex	= $minus_two . "/" . $minus_one;


/*- Tables ---------------------------------------------------------------------------- */
include("_tables_exercises.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/exercises/ts_index.php");

/*- Special main mode ------------------------------------------------------------------ */
if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}



/*- Variables ------------------------------------------------------------------------- */
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
	$sub_group_id = $_GET['sub_group_id'];
	$sub_group_id = strip_tags(stripslashes($sub_group_id));
}
else{
	$sub_group_id = "";
}
if(isset($_GET['main_muscle_group_id'])){
	$main_muscle_group_id = $_GET['main_muscle_group_id'];
	$main_muscle_group_id = strip_tags(stripslashes($main_muscle_group_id));
}
else{
	$main_muscle_group_id = "";
}


if($include_as_navigation_main_mode == 0){
	echo"
	<ul class=\"toc\">
		<li class=\"header_home\"><a href=\"$root/exercises/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "exercises"){ echo" class=\"navigation_active\"";}echo">$l_exercises</a></li>

	";
}

	// Get all types
	$query_sub = "SELECT type_id, type_title FROM $t_exercise_types ORDER BY type_title ASC";
	$result_sub = mysqli_query($link, $query_sub);
	while($row_sub = mysqli_fetch_row($result_sub)) {
		list($get_type_id, $get_type_title) = $row_sub;

		// Translation
		$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id='$get_type_id' AND type_translation_language=$l_mysql";
		$result_translation = mysqli_query($link, $query_translation);
		$row_translation = mysqli_fetch_row($result_translation);
		list($get_type_translation_id, $get_type_translation_value) = $row_translation;

		echo"						";
		echo"<li><a href=\"$root/exercises/view_type.php?type_id=$get_type_id&amp;l=$l\""; if($type_id == "$get_type_id"){ echo" class=\"navigation_active\"";}echo">$get_type_translation_value</a></li>\n";


		if($type_id == "$get_type_id"){

			// Get sub categories
			$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;

				// Translation
				$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;

				echo"						";
				echo"<li style=\"padding-left: 20px;\"><a href=\"$root/exercises/view_muscle_group.php?main_muscle_group_id=$get_main_muscle_group_id&amp;type_id=$get_type_id&amp;l=$l\""; if($main_muscle_group_id == "$get_main_muscle_group_id"){ echo" class=\"navigation_active\"";}echo">$get_main_muscle_group_translation_name</a></li>\n";


			}

		}
	}



	echo"
	

						<li class=\"header_up\"><a href=\"$root/exercises/user_pages.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "android"){ echo" class=\"navigation_active\"";}echo">$l_user_pages</a></li>
						<li><a href=\"$root/exercises/new_exercise.php?l=$l\""; if($minus_one == "new_exercise.php"){ echo" class=\"navigation_active\"";}echo">$l_new_exercise</a></li>
						<li><a href=\"$root/exercises/my_exercises.php?l=$l\""; if($minus_one == "my_exercises.php"){ echo" class=\"navigation_active\"";}echo">$l_my_exercises</a></li>
						<li><a href=\"$root/exercises/new_equipment.php?l=$l\""; if($minus_one == "new_equipment.php"){ echo" class=\"navigation_active\"";}echo">$l_new_equipment</a></li>
						<li><a href=\"$root/exercises/my_equipment.php?l=$l\""; if($minus_one == "my_equipment.php"){ echo" class=\"navigation_active\"";}echo">$l_my_equipment</a></li>

	";


if($include_as_navigation_main_mode == 0){
	echo"
	</ul>
	";
}
?>