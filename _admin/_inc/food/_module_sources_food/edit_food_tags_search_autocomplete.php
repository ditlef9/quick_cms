<?php 
/**
*
* File: food/edit_food_tags_search_autocomplete.php
* Version 1.0.0
* Date 15:38 21.01.2018
* Copyright (c) 2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



/*- Functions ------------------------------------------------------------------------ */
$root = "..";
include("../_admin/website_config.php");


/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);




/*- Tables ---------------------------------------------------------------------------- */
include("_tables_food.php");

/*- MySQL Tables -------------------------------------------------------------------- */
$t_food_index	 	= $mysqlPrefixSav . "food_index";
$t_food_queries 	= $mysqlPrefixSav . "food_queries";
$t_food_tags_unique	= $mysqlPrefixSav . "food_tags_unique";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
}
else{
	$l = "";
}
$l_mysql = quote_smart($link, $l);

if(isset($_GET['tag_field'])) {
	$tag_field = $_GET['tag_field'];
	$tag_field = strip_tags(stripslashes($tag_field));
}
else{
	$tag_field = "inp_tag_a";
}

/*- Language ------------------------------------------------------------------------ */
if(file_exists("../_admin/_translations/site/$l/food/ts_index.php")){
	include("../_admin/_translations/site/$l/food/ts_index.php");
}
else{
	echo"Unknown l";
	die;
}


/*- Query --------------------------------------------------------------------------- */
if(isset($_GET['search_query'])) {
	$search_query = $_GET['search_query'];
	$search_query = trim($search_query);

	$search_query = str_replace("å", "|aring;", $search_query);
	$search_query = str_replace("æ", "|aelig;", $search_query);
	$search_query = str_replace("Å", "|Aring;", $search_query);
	$search_query = str_replace("Æ", "|Aelig;", $search_query);
	$search_query = str_replace("À", "|#192;", $search_query);
	$search_query = str_replace("Á", "|#193;", $search_query);
	$search_query = str_replace("Â", "|#194;", $search_query);
	$search_query = str_replace("Ã", "|#195;", $search_query);
	$search_query = str_replace("Ä", "|#196;", $search_query);
	$search_query = str_replace("Å", "|#197;", $search_query);
	$search_query = str_replace("Æ", "|#198;", $search_query);
	$search_query = str_replace("Ç", "|#199;", $search_query);
	$search_query = str_replace("È", "|#200;", $search_query);
	$search_query = str_replace("É", "|#201;", $search_query);
	$search_query = str_replace("Ê", "|#202;", $search_query);
	$search_query = str_replace("Ë", "|#203;", $search_query);
	$search_query = str_replace("Ì", "|#204;", $search_query);
	$search_query = str_replace("Í", "|#205;", $search_query);
	$search_query = str_replace("Î", "|#206;", $search_query);
	$search_query = str_replace("Ï", "|#207;", $search_query);
	$search_query = str_replace("Ð", "|#208;", $search_query);
	$search_query = str_replace("Ñ", "|#209;", $search_query);
	$search_query = str_replace("Ò", "|#210;", $search_query);
	$search_query = str_replace("Ó", "|#211;", $search_query);
	$search_query = str_replace("Ô", "|#212;", $search_query);
	$search_query = str_replace("Õ", "|#213;", $search_query);
	$search_query = str_replace("Ö", "|#214;", $search_query);
	$search_query = str_replace("×", "|#215;", $search_query);  
	$search_query = str_replace("Ø", "|#216;", $search_query);
	$search_query = str_replace("Ø", "|Oslash;", $search_query);
	$search_query = str_replace("ø", "|oslash;", $search_query);
	$search_query = str_replace("Ù", "|#217;", $search_query);
	$search_query = str_replace("Ú", "|#218;", $search_query);
	$search_query = str_replace("Û", "|#219;", $search_query);
	$search_query = str_replace("Ü", "|#220;", $search_query);
	$search_query = str_replace("Ý", "|#221;", $search_query);
	$search_query = str_replace("Þ", "|#222;", $search_query);
	$search_query = str_replace("ß", "|#223;", $search_query);
	$search_query = str_replace("à", "|#224;", $search_query);
	$search_query = str_replace("á", "|#225;", $search_query);
	$search_query = str_replace("â", "|#226;", $search_query);
	$search_query = str_replace("ã", "|#227;", $search_query);
	$search_query = str_replace("ä", "|#228;", $search_query);
	$search_query = str_replace("å", "|#229;", $search_query);
	$search_query = str_replace("æ", "|#230;", $search_query);
	$search_query = str_replace("ç", "|#231;", $search_query);
	$search_query = str_replace("è", "|#232;", $search_query);
	$search_query = str_replace("é", "|#233;", $search_query);
	$search_query = str_replace("ê", "|#234;", $search_query);
	$search_query = str_replace("ë", "|#235;", $search_query);
	$search_query = str_replace("ì", "|#236;", $search_query);
	$search_query = str_replace("í", "|#237;", $search_query);
	$search_query = str_replace("î", "|#238;", $search_query);
	$search_query = str_replace("ï", "|#239;", $search_query);
	$search_query = str_replace("ð", "|#240;", $search_query);
	$search_query = str_replace("ñ", "|#241;", $search_query);
	$search_query = str_replace("ñ", "|ntilde;", $search_query);
	$search_query = str_replace("ò", "|#242;", $search_query);
	$search_query = str_replace("ó", "|#243;", $search_query);
	$search_query = str_replace("ô", "|#244;", $search_query);
	$search_query = str_replace("õ", "|#245;", $search_query);
	$search_query = str_replace("ö", "|#246;", $search_query);
	$search_query = str_replace("÷", "|#247;", $search_query); 
	$search_query = str_replace("ø", "|#248;", $search_query);
	$search_query = str_replace("ù", "|#249;", $search_query);
	$search_query = str_replace("ú", "|#250;", $search_query);
	$search_query = str_replace("û", "|#251;", $search_query);
	$search_query = str_replace("ü", "|#252;", $search_query);
	$search_query = str_replace("ý", "|#253;", $search_query);
	$search_query = str_replace("þ", "|#254;", $search_query);
	$search_query = str_replace("ÿ", "|#255;", $search_query);

	$search_query = strtolower($search_query);
	$search_query = output_html($search_query);

	$search_query = str_replace("|aring;", "&aring;", $search_query);
	$search_query = str_replace("|aelig;", "&aelig;", $search_query);
	$search_query = str_replace("|Aring;", "Å", $search_query);
	$search_query = str_replace("|Aelig;", "Æ", $search_query);
	$search_query = str_replace("|#192;", "À", $search_query);
	$search_query = str_replace("|#193;", "Á", $search_query);
	$search_query = str_replace("|#194;", "Â", $search_query);
	$search_query = str_replace("|#195;", "Ã", $search_query);
	$search_query = str_replace("|#196;", "Ä", $search_query);
	$search_query = str_replace("|#197;", "Å", $search_query);
	$search_query = str_replace("|#198;", "Æ", $search_query);
	$search_query = str_replace("|#199;", "Ç", $search_query);
	$search_query = str_replace("|#200;", "È", $search_query);
	$search_query = str_replace("|#201;", "É", $search_query);
	$search_query = str_replace("|#202;", "Ê", $search_query);
	$search_query = str_replace("|#203;", "Ë", $search_query);
	$search_query = str_replace("|#204;", "Ì", $search_query);
	$search_query = str_replace("|#205;", "Í", $search_query);
	$search_query = str_replace("|#206;", "Î", $search_query);
	$search_query = str_replace("|#207;", "Ï", $search_query);
	$search_query = str_replace("|#208;", "Ð", $search_query);
	$search_query = str_replace("|#209;", "Ñ", $search_query);
	$search_query = str_replace("|#210;", "Ò", $search_query);
	$search_query = str_replace("|#211;", "Ó", $search_query);
	$search_query = str_replace("|#212;", "Ô", $search_query);
	$search_query = str_replace("|#213;", "Õ", $search_query);
	$search_query = str_replace("|#214;", "Ö", $search_query);
	$search_query = str_replace("|#215;", "×", $search_query);  
	$search_query = str_replace("|#216;", "Ø", $search_query);
	$search_query = str_replace("|Oslash;", "Ø", $search_query);
	$search_query = str_replace("|oslash;", "&oslash;", $search_query);
	$search_query = str_replace("|oslash;", "ø", $search_query);
	$search_query = str_replace("|#217;", "Ù", $search_query);
	$search_query = str_replace("|#218;", "Ú", $search_query);
	$search_query = str_replace("|#219;", "Û", $search_query);
	$search_query = str_replace("|#220;", "Ü", $search_query);
	$search_query = str_replace("|#221;", "Ý", $search_query);
	$search_query = str_replace("|#222;", "Þ", $search_query);
	$search_query = str_replace("|#223;", "ß", $search_query);
	$search_query = str_replace("|#224;", "à", $search_query);
	$search_query = str_replace("|#225;", "á", $search_query);
	$search_query = str_replace("|#226;", "â", $search_query);
	$search_query = str_replace("|#227;", "ã", $search_query);
	$search_query = str_replace("|#228;", "ä", $search_query);
	$search_query = str_replace("|#229;", "å", $search_query);
	$search_query = str_replace("|#230;", "æ", $search_query);
	$search_query = str_replace("|#231;", "ç", $search_query);
	$search_query = str_replace("|#232;", "è", $search_query);
	$search_query = str_replace("|#233;", "é", $search_query);
	$search_query = str_replace("|#234;", "ê", $search_query);
	$search_query = str_replace("|#235;", "ë", $search_query);
	$search_query = str_replace("|#236;", "ì", $search_query);
	$search_query = str_replace("|#237;", "í", $search_query);
	$search_query = str_replace("|#238;", "î", $search_query);
	$search_query = str_replace("|#239;", "ï", $search_query);
	$search_query = str_replace("|#240;", "ð", $search_query);
	$search_query = str_replace("|#241;", "ñ", $search_query);
	$search_query = str_replace("|ntilde;", "ñ", $search_query);
	$search_query = str_replace("|#242;", "ò", $search_query);
	$search_query = str_replace("|#243;", "ó", $search_query);
	$search_query = str_replace("|#244;", "ô", $search_query);
	$search_query = str_replace("|#245;", "õ", $search_query);
	$search_query = str_replace("|#246;", "ö", $search_query);
	$search_query = str_replace("|#247;", "÷", $search_query); 
	$search_query = str_replace("|#248;", "ø", $search_query);
	$search_query = str_replace("|#249;", "ù", $search_query);
	$search_query = str_replace("|#250;", "ú", $search_query);
	$search_query = str_replace("|#251;", "û", $search_query);
	$search_query = str_replace("|#252;", "ü", $search_query);
	$search_query = str_replace("|#253;", "ý", $search_query);
	$search_query = str_replace("|#254;", "þ", $search_query);
	$search_query = str_replace("|#255;", "ÿ", $search_query);

	$search_query_mysql = quote_smart($link, $search_query);

	$search_query_clean = clean($search_query);

	$inp_datetime = date("Y-m-d H:i:s");
	if($search_query != ""){
		// Check for hacker
		include("$root/_admin/_functions/look_for_hacker_in_string.php");

		// Ready for MySQL search
		$search_query_mysql = quote_smart($link, $search_query);

		$search_query_like = "%" . $search_query . "%";
		$search_query_like_mysql = quote_smart($link, $search_query_like);

		$search_query_clean = "" . $search_query_clean . "%";
		$search_query_clean_mysql = quote_smart($link, $search_query_clean);

		// Set layout
		$x = 0;

		// Query
		$query = "SELECT tag_id, tag_title, tag_title_clean FROM $t_food_tags_unique WHERE tag_language=$l_mysql AND tag_title LIKE $search_query_like_mysql";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_tag_id, $get_tag_title, $get_tag_title_clean) = $row;
	
			echo"
			<a href=\"#\" class=\"btn_default tag_select\" data-divid=\"$get_tag_title\">$get_tag_title</a>
			";
		} // while

		echo"
		<!-- On click add to text field -->
		<script type=\"text/javascript\">
		\$(function() {
			\$('.tag_select').click(function() {
		
				var value = \$(this).data('divid');
				var input = \$('#$tag_field');
				input.val(value);

				// Close
				\$(\"#search_result_$tag_field\").html(''); 
            			return false;

       			});
    		});
		</script>

		
		<!-- //On click add to text field -->
		";
	} // q
	else{
		echo"Search query is blank";
	}
}
else{
	echo"No search_query";
}


?>