<?php 
/**
*
* File: references/_includes/reference_by_alphabet_jquery_search.php.
* Version 1.0.0
* Date 11:24 04.02.2019
* Copyright (c) 2018-2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



/*- Functions ------------------------------------------------------------------------ */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");
include("../../_admin/_functions/resize_crop_image.php");
include("../../_admin/_functions/get_extension.php");





/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);


/*- MySQL ------------------------------------------------------------ */
$check = substr($server_name, 0, 3);
if($check == "www"){
	$server_name = substr($server_name, 3);
}

$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("../../_admin/_data/$setup_finished_file"))){
	die;
}

else{
	include("../../_admin/_data/config/meta.php");
	include("../../_admin/_data/config/user_system.php");

}

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}







/*- MySQL Tables -------------------------------------------------------------------- */
$t_references_title_translations = $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 = $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 = $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 = $mysqlPrefixSav . "references_index";
$t_references_index_groups	 = $mysqlPrefixSav . "references_index_groups";
$t_references_index_guides	 = $mysqlPrefixSav . "references_index_guides";



/*- Variables ------------------------------------------------------------------------- */


if(isset($_GET['l']) OR isset($_POST['l'])) {
	if(isset($_GET['l'])){
		$l = $_GET['l'];
	}
	else{
		$l = $_POST['l'];
	}
	$l = strip_tags(stripslashes($l));
}
else{
	echo"No l";
	die;
}
$l_mysql = quote_smart($link, $l);


if(isset($_GET['reference_id']) OR isset($_POST['reference_id'])) {
	if(isset($_GET['reference_id'])){
		$reference_id = $_GET['reference_id'];
	}
	else{
		$reference_id = $_POST['reference_id'];
	}
	$reference_id = strip_tags(stripslashes($reference_id));
}
else{
	echo"No reference_id";
	die;
}
$reference_id_mysql = quote_smart($link, $reference_id);

/*- Query --------------------------------------------------------------------------- */
if(isset($_GET['q']) OR isset($_POST['q'])){
	if(isset($_GET['q'])) {
		$q = $_GET['q'];
	}
	else{
		$q = $_POST['q'];
	}
	$q = trim($q);
	$q = strtolower($q);
	$inp_datetime = date("Y-m-d H:i:s");
	$q = output_html($q);
	$q_mysql = quote_smart($link, $q);

	if($q != ""){
		

		
		// Ready for MySQL search
		$q = $q . "%";
		$q_mysql = quote_smart($link, $q);

		// Set layout
		$x = 0;

		// Query
		echo"
		";
		$query_lessons = "SELECT guide_id, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_short_description, guide_group_id, guide_reference_id FROM $t_references_index_guides WHERE ";
		$query_lessons = $query_lessons . "guide_reference_id=$reference_id_mysql AND guide_title LIKE $q_mysql";
		$result_lessons = mysqli_query($link, $query_lessons);
		while($row_lessons = mysqli_fetch_row($result_lessons)) {
			list($get_guide_id, $get_guide_title, $get_guide_title_clean, $get_guide_title_short, $get_guide_title_length, $get_guide_short_description, $get_guide_group_id, $get_guide_reference_id) = $row_lessons;
				
				// Find group title
				$query = "SELECT group_id, group_title, group_title_clean FROM $t_references_index_groups WHERE group_id=$get_guide_group_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_group_id, $get_group_title, $get_group_title_clean) = $row;



				if(isset($style) && $style == ""){
					$style = "odd";
				}
				else{
					$style = "";
				}
				echo"
				 <tr>
				  <td class=\"$style\" style=\"width: 20%;\">
					<span><a href=\"../$get_group_title_clean/$get_guide_title_clean.php?reference_id=$get_guide_reference_id&amp;group_id=$get_guide_group_id&amp;guide_id=$get_guide_id&amp;l=$l\">$get_guide_title</a></span>
				  </td>
				  <td class=\"$style\">
					<span>$get_guide_short_description</span>
				  </td>
				 </tr>";

		} // while
		echo"
		";
		
	}

}

else{

	echo"No q";

}



?>