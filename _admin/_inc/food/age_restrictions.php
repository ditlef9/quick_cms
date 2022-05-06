<?php
/**
*
* File: _admin/_inc/food/age_restrictions.php
* Version 15:27 12.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_food_liquidbase			= $mysqlPrefixSav . "food_liquidbase";
$t_food_age_restrictions 	 	= $mysqlPrefixSav . "food_age_restrictions";
$t_food_age_restrictions_accepted	= $mysqlPrefixSav . "food_age_restrictions_accepted";
$t_food_categories		  	= $mysqlPrefixSav . "food_categories";
$t_food_categories_translations	  	= $mysqlPrefixSav . "food_categories_translations";
$t_food_index			  	= $mysqlPrefixSav . "food_index";
$t_food_index_stores		  	= $mysqlPrefixSav . "food_index_stores";
$t_food_index_ads		  	= $mysqlPrefixSav . "food_index_ads";
$t_food_index_tags		  	= $mysqlPrefixSav . "food_index_tags";
$t_food_index_prices		  	= $mysqlPrefixSav . "food_index_prices";
$t_food_index_contents		  	= $mysqlPrefixSav . "food_index_contents";
$t_food_stores		  	  	= $mysqlPrefixSav . "food_stores";
$t_food_prices_currencies	  	= $mysqlPrefixSav . "food_prices_currencies";
$t_food_favorites 		  	= $mysqlPrefixSav . "food_favorites";
$t_food_measurements	 	  	= $mysqlPrefixSav . "food_measurements";
$t_food_measurements_translations 	= $mysqlPrefixSav . "food_measurements_translations";

/*- Functions ----------------------------------------------------------------------- */

/*- Check if setup is run ------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>Age Restrictions</h1>


	<!-- Get all age restrictions -->
		<div class=\"vertical\">
			<ul>";
			$query = "SELECT restriction_id, restriction_country_name, restriction_country_flag_path_16x16, restriction_country_flag_16x16 FROM $t_food_age_restrictions ORDER BY restriction_country_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_restriction_id, $get_restriction_country_name, $get_restriction_country_flag_path_16x16, $get_restriction_country_flag_16x16) = $row;

				echo"				";
				echo"<li><a href=\"index.php?open=food&amp;page=age_restrictions&amp;action=edit&amp;restriction_id=$get_restriction_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"../$get_restriction_country_flag_path_16x16/$get_restriction_country_flag_16x16\" alt=\"$get_restriction_country_flag_16x16\" /> $get_restriction_country_name</a>\n";
			}
			echo"
			</ul>
		</div>
	<!-- //Get all age restrictions -->
	";
}
elseif($action == "edit"){
	// Variables
	$restriction_id = $_GET['restriction_id'];
	$restriction_id = output_html($restriction_id);
	if(!(is_numeric($restriction_id))){
		echo"Restriction id not numeric";
		die;
	}
	$restriction_id_mysql = quote_smart($link, $restriction_id);
	
	// Select restriction
	$query = "SELECT restriction_id, restriction_country_name, restriction_country_iso_two, restriction_country_flag_path_16x16, restriction_country_flag_16x16, restriction_language, restriction_age_limit, restriction_title, restriction_text, restriction_show_food, restriction_show_image_a, restriction_show_image_b, restriction_show_image_c, restriction_show_image_d, restriction_show_image_e, restriction_show_smileys FROM $t_food_age_restrictions WHERE restriction_id=$restriction_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_restriction_id, $get_current_restriction_country_name, $get_current_restriction_country_iso_two, $get_current_restriction_country_flag_path_16x16, $get_current_restriction_country_flag_16x16, $get_current_restriction_language, $get_current_restriction_age_limit, $get_current_restriction_title, $get_current_restriction_text, $get_current_restriction_show_food, $get_current_restriction_show_image_a, $get_current_restriction_show_image_b, $get_current_restriction_show_image_c, $get_current_restriction_show_image_d, $get_current_restriction_show_image_e, $get_current_restriction_show_smileys) = $row;

	if($get_current_restriction_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>Restriction not found.</p>
		";
	}
	else{
		if($process == "1"){
			$inp_age_limit = $_POST['inp_age_limit'];
			$inp_age_limit = output_html($inp_age_limit);
			$inp_age_limit_mysql = quote_smart($link, $inp_age_limit);

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_text = $_POST['inp_text'];
			$inp_text = output_html($inp_text);
			$inp_text_mysql = quote_smart($link, $inp_text);

			$inp_show_food = $_POST['inp_show_food'];
			$inp_show_food= output_html($inp_show_food);
			$inp_show_food_mysql = quote_smart($link, $inp_show_food);

			$inp_show_image_a = $_POST['inp_show_image_a'];
			$inp_show_image_a = output_html($inp_show_image_a);
			$inp_show_image_a_mysql = quote_smart($link, $inp_show_image_a);

			$inp_show_image_b = $_POST['inp_show_image_b'];
			$inp_show_image_b = output_html($inp_show_image_b);
			$inp_show_image_b_mysql = quote_smart($link, $inp_show_image_b);

			$inp_show_image_c = $_POST['inp_show_image_c'];
			$inp_show_image_c = output_html($inp_show_image_c);
			$inp_show_image_c_mysql = quote_smart($link, $inp_show_image_c);

			$inp_show_image_d = $_POST['inp_show_image_d'];
			$inp_show_image_d = output_html($inp_show_image_d);
			$inp_show_image_d_mysql = quote_smart($link, $inp_show_image_d);

			$inp_show_image_e = $_POST['inp_show_image_e'];
			$inp_show_image_e = output_html($inp_show_image_e);
			$inp_show_image_e_mysql = quote_smart($link, $inp_show_image_e);

			$inp_show_smileys = $_POST['inp_show_smileys'];
			$inp_show_smileys = output_html($inp_show_smileys);
			$inp_show_smileys_mysql = quote_smart($link, $inp_show_smileys);


			$result = mysqli_query($link, "UPDATE $t_food_age_restrictions SET 
							restriction_age_limit=$inp_age_limit_mysql,
							restriction_title=$inp_title_mysql,
							restriction_text=$inp_text_mysql,
							restriction_show_food=$inp_show_food_mysql,
							restriction_show_image_a=$inp_show_image_a_mysql, 
							restriction_show_image_b=$inp_show_image_b_mysql, 
							restriction_show_image_c=$inp_show_image_c_mysql, 
							restriction_show_image_d=$inp_show_image_d_mysql, 
							restriction_show_image_e=$inp_show_image_e_mysql, 
							restriction_show_smileys=$inp_show_smileys_mysql 
							WHERE restriction_id=$get_current_restriction_id") or die(mysqli_error($link));

			$url = "index.php?open=$open&page=$page&action=edit&restriction_id=$get_current_restriction_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}


		echo"
		<h1>$get_current_restriction_country_name</h1>

		<!-- Where am I ? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Age restrictions</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;restriction_id=$get_current_restriction_id&amp;editor_language=$editor_language&amp;l=$l\">Edit $get_current_restriction_country_name</a>
			</p>
		<!-- //Where am I ? -->


		<!-- Feedback -->
		";
		if($ft != ""){
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Edit restriction form -->
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_age_limit\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;restriction_id=$get_current_restriction_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
			<p>
			<a href=\"index.php?open=settings&amp;page=languages&amp;action=edit_countries&amp;editor_language=$editor_language&amp;l=$l\">Edit country</a>
			</p>

			<p>Age limit:<br />
			<input type=\"text\" name=\"inp_age_limit\" value=\"$get_current_restriction_age_limit\" size=\"25\" />
			</p>

			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_restriction_title\" size=\"25\" />
			</p>

			<p>Text:<br />
			<textarea name=\"inp_text\" cols=\"100\" rows=\"20\">$get_current_restriction_text</textarea>
			</p>

			<p>Show food<br />
			<input type=\"radio\" name=\"inp_show_food\" value=\"1\""; if($get_current_restriction_show_food == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_show_food\" value=\"0\""; if($get_current_restriction_show_food == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>Show image A<br />
			<input type=\"radio\" name=\"inp_show_image_a\" value=\"1\""; if($get_current_restriction_show_image_a == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_show_image_a\" value=\"0\""; if($get_current_restriction_show_image_a == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>Show image B<br />
			<input type=\"radio\" name=\"inp_show_image_b\" value=\"1\""; if($get_current_restriction_show_image_b == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_show_image_b\" value=\"0\""; if($get_current_restriction_show_image_b == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>Show image C<br />
			<input type=\"radio\" name=\"inp_show_image_c\" value=\"1\""; if($get_current_restriction_show_image_c == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_show_image_c\" value=\"0\""; if($get_current_restriction_show_image_c == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>Show image D<br />
			<input type=\"radio\" name=\"inp_show_image_d\" value=\"1\""; if($get_current_restriction_show_image_d == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_show_image_d\" value=\"0\""; if($get_current_restriction_show_image_d == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>Show image E<br />
			<input type=\"radio\" name=\"inp_show_image_e\" value=\"1\""; if($get_current_restriction_show_image_e == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_show_image_e\" value=\"0\""; if($get_current_restriction_show_image_e == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>Show smileys<br />
			<input type=\"radio\" name=\"inp_show_smileys\" value=\"1\""; if($get_current_restriction_show_smileys == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_show_smileys\" value=\"0\""; if($get_current_restriction_show_smileys == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p> 
			<input type=\"submit\" value=\"Save changes\" class=\"btn\" />
			</p>
			</form>
					
		<!-- //Edit restriction form -->
		";
	} // found
} // action == edit


?>