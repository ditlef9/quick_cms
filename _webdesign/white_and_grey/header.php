<?php
/**
*
* File: _webdesign/thefitpot/header.php
* Version 2.1
* Date 10:10 23.01.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Web design start ------------------------------------------------------------------ */
if($process != "1") {
echo"<!DOCTYPE html>
<html lang=\"$l\">
<head>
	<title>$configWebsiteTitleSav";
	if(isset($website_title)){
		echo" - $website_title";
	}
	else{
		$website_title = $_SERVER['PHP_SELF'];
		$website_title = str_replace("/", " - ", $website_title);
		$website_title = str_replace(".php", "", $website_title);
		$website_title = str_replace("_", " ", $website_title);
		$website_title = ucwords($website_title);
		echo" - $website_title";
	}
	echo"</title>

	<!-- Site CSS-->
		<link rel=\"stylesheet\" type=\"text/css\" href=\"$root/_webdesign/$webdesignSav/master.css?rand="; $datetime = date("Y-m-d H:i:s"); echo"$datetime\" />
	<!-- //Site CSS -->

	<!-- Special CSS -->
		";
		if(isset($pageCSSFile)){
			if(file_exists("$pageCSSFile")){
				echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"$pageCSSFile?rand=$datetime\" />";
			}
			else{
				echo"<!-- <link rel=\"stylesheet\" type=\"text/css\" href=\"$pageCSSFile\" /> -->";
			}
		}
		echo"
	<!-- //Special CSS -->

	<!-- Favicon -->";
		if(file_exists("$root/_uploads/favicon/16x16.png")){
			echo"\n	<link rel=\"icon\" href=\"$root/_uploads/favicon/16x16.png\" type=\"image/png\" sizes=\"16x16\" />";
		}
		if(file_exists("$root/_uploads/favicon/32x32.png")){
			echo"\n	<link rel=\"icon\" href=\"$root/_uploads/favicon/32x32.png\" type=\"image/png\" sizes=\"32x32\" />";
		}
		if(file_exists("$root/_uploads/favicon/260x260.png")){
			echo"\n	<link rel=\"icon\" href=\"$root/_uploads/favicon/260x260.png\" type=\"image/png\" sizes=\"260x260\" />";
		}
		echo"	
	<!-- //Favicon -->

	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>

	<!-- jQuery -->
		<script type=\"text/javascript\" src=\"$root/_scripts/javascripts/jquery/jquery-3.4.0.min.js\"></script>
	<!-- //jQuery -->

	";
	if($root == "." && file_exists("_uploads/slides/$l/slides.php")){
		echo"
		<!-- Carousel -->
		<script type=\"text/javascript\" src=\"$root/_scripts/javascripts/carousel/owl.carousel.min.js\"></script>
		<link rel=\"stylesheet\" href=\"$root/_scripts/javascripts/carousel/owl.carousel.css\" />
		<link rel=\"stylesheet\" href=\"$root/_scripts/javascripts/carousel/owl.theme.css\" />
		<link rel=\"stylesheet\" href=\"$root/_scripts/javascripts/carousel/owl.transitions.css\" />
		<link rel=\"stylesheet\" href=\"$root/_scripts/javascripts/carousel/custom.css\" />
		<script>
		\$(document).ready(function() {
 
			\$(\"#owl-example\").owlCarousel({
				navigation : false, 
      				slideSpeed : 300,
      				paginationSpeed : 400,
      				singleItem: true,
				pagination: true,
    				rewindSpeed: 500
			});
 
		});
		</script>
			
		<link rel=\"stylesheet\" type=\"text/css\" href=\"_uploads/slides/$l/slides.css\" />

		<!-- //Carousel -->
		";
	}
	echo"

</head>
<body>
<a id=\"top\"></a>



<!-- Header -->

	<header>
		<!-- Header main -->
			<div id=\"header_main\">

				<!-- Header hamburger -->
					<div id=\"header_hamburger\">
						<!-- Hide show nav + change hamburger icon -->
							<script>
							\$(document).ready(function(){
								\$(\".main_navigation_menu_icon\").click(function () {
									\$(\"nav\").fadeToggle();


									var src = (\$(this).attr('src') === '$root/_webdesign/$webdesignSav/images/header/ic_menu_24x24_333333.png')
										? '$root/_webdesign/$webdesignSav/images/header/ic_clear_24x24_333333.png'
										: '$root/_webdesign/$webdesignSav/images/header/ic_menu_24x24_333333.png';
									\$(\".main_navigation_menu_icon\").attr('src', src);


								});
							});
							</script>
						<!-- //Hide show nav + change hamburger icon -->
						<a href=\"#\" class=\"a_header_left_hamburger\"><img src=\"$root/_webdesign/$webdesignSav/images/header/ic_menu_24x24_333333.png\" alt=\"ic_menu_24x24_333333.png\" class=\"main_navigation_menu_icon\" /></a>
					</div>
				<!-- //Header hamburger -->


				<!-- Header logo -->
					<div id=\"header_logo\">
						<a href=\"$root/index.php?l=$l\">$configWebsiteTitleSav</a>
					</div>
				<!-- //Header logo -->

				<!-- Header navigation -->
					<nav>

						<ul>";

						$count_parent = 0;
						$count_children = 0;
						$include_as_navigation_main_mode = 1; // We want to include navigation.php, in special navigation main mode

						$navigation_language_mysql = quote_smart($link, $l);
						$query_nav_main = "SELECT navigation_id, navigation_title, navigation_url_path, navigation_url_query FROM $t_navigation WHERE navigation_parent_id='0' AND navigation_language=$navigation_language_mysql ORDER BY navigation_weight ASC";
						$result_nav_main = mysqli_query($link, $query_nav_main);
						$row_cnt_nav_main = mysqli_num_rows($result_nav_main);
						while($row_nav_main = mysqli_fetch_row($result_nav_main)) {
							list($get_parent_navigation_id, $get_parent_navigation_title, $get_parent_navigation_url_path, $get_parent_navigation_url_query) = $row_nav_main;
					
							$query_children = "SELECT navigation_id, navigation_title, navigation_url_path, navigation_url_query FROM $t_navigation WHERE navigation_parent_id='$get_parent_navigation_id' AND navigation_language=$navigation_language_mysql ORDER BY navigation_weight ASC";
							$result_children = mysqli_query($link, $query_children);
							$row_cnt_children = mysqli_num_rows($result_children);
							$y = 1;
							while($row_children = mysqli_fetch_row($result_children)) {
								list($get_child_navigation_id, $get_child_navigation_title, $get_child_navigation_url_path, $get_child_navigation_url_query) = $row_children;


								if($count_children == 0){
									// Parent with children
									echo"				<li><a href=\"$root/$get_parent_navigation_url_path$get_parent_navigation_url_query\">$get_parent_navigation_title</a></li>\n";
								}


								echo"
								<!-- child here -->
								";
								$count_children++;
							}
							if($count_children == 0){
								if(file_exists("$root/$get_parent_navigation_url_path/navigation.php")){
									// Parent have children in php file
									echo"
									<li class=\"main_navigation_has_sub\"><a href=\"$root/$get_parent_navigation_url_path$get_parent_navigation_url_query\">$get_parent_navigation_title</a>  <img src=\"$root/_webdesign/$webdesignSav/images/main_navigation/main_navigation_has_sub_mobile.png\" alt=\"main_navigation_has_sub_mobile.png\" class=\"main_navigation_has_sub_mobile toggle\" data-divid=\"diplay_main_navigation_sub_$get_parent_navigation_id\" />
										<ul class=\"main_navigation_sub diplay_main_navigation_sub_$get_parent_navigation_id\">
									";
									include("$root/$get_parent_navigation_url_path/navigation.php");

									echo"
										</ul>
									</li>\n";
								}	
								else{
									// Parent doesnt have children
									echo"				<li><a href=\"$root/$get_parent_navigation_url_path$get_parent_navigation_url_query\">$get_parent_navigation_title</a></li>\n";
								}
							}
							$count_parent = 0;
							$count_children = 0;
						}
						$include_as_navigation_main_mode = 0; // We reset special navigation main mode
						echo"
						</ul>

					</nav>
				<!-- //Header navigation -->
					
				<!-- Header user icons -->
					<div id=\"header_user_icons\">

						<!-- Hide show search -->
							<script>
							\$(document).ready(function(){
								\$(\".header_show_search\").click(function () {
									\$(\"#header_search_wrapper\").fadeToggle();
									\$(\"#inp_header_search_query\").focus();
								});
								\$(\".header_show_new_a\").click(function () {
									\$(\"#header_new_wrapper\").fadeToggle();

									var src = (\$(\".header_show_new_img\").attr('src') === '$root/_webdesign/$webdesignSav/images/header/header_show_new_img_add.png')
										? '$root/_webdesign/$webdesignSav/images/header/header_show_new_img_clear.png'
										: '$root/_webdesign/$webdesignSav/images/header/header_show_new_img_add.png';
									\$(\".header_show_new_img\").attr(\"src\", src);
								});
							});
							</script>
						<!-- //Hide show search -->

						<ul>
							<li><a href=\"#\" class=\"header_show_search\"><img src=\"$root/_webdesign/$webdesignSav/images/header/ic_search_24x24_333333.png\" alt=\"ic_search_24x24_333333.png\" /></a></li>
						";

						// Am I logged in?
						if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
							// Get my user alias, date format, profile image
							$my_user_id 	  = $_SESSION['user_id'];
							$my_user_id_mysql = quote_smart($link, $my_user_id);

							$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;

							if($get_my_user_id == ""){
								echo"<p style=\"color:red\">Session error</p>";
								unset($_SESSION['user_id']);
							}
						}
						if(isset($my_user_id)){
							echo"
							<li><a href=\"$root/users/my_profile.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/ic_my_profile_24x24_333333.png\" alt=\"ic_my_profile_24x24_333333.png\" title=\"$get_my_user_alias\" /></a></li>
							";
						}
						else{
							echo"
							<li><a href=\"$root/users/login.php?l=$l\" class=\"last\"><img src=\"$root/_webdesign/$webdesignSav/images/header/ic_login_24x24_333333.png\" alt=\"ic_login_24x24_333333.png\" title=\"$l_login\" /></a></li>
							";
						}
						echo"
							<li><a href=\"#\" class=\"header_show_new_a\"><img src=\"$root/_webdesign/$webdesignSav/images/header/header_show_new_img_add.png\" alt=\"baseline_add_black_18dp.png\" class=\"header_show_new_img\" /></a></li>
						</ul>
					<div id=\"header_new_wrapper\">";
						include("$root/_admin/_translations/site/$l/root/ts_header_new_wrapper.php");
						echo"
						<p>$l_new</p>
						<ul>";
						if(!(isset($my_user_id))){
							echo"
							<li><a href=\"$root/$root/users/create_free_account.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/ic_registrer_24x24_333333.png\" alt=\"ic_registrer_24x24_333333.png\" /> $l_registrer</a></li>\n";
						}
						echo"
							<li><a href=\"$root/blog/my_blog_new_post.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/outline_article_black_18dp.png\" alt=\"outline_post_add_black_18dp.png\" /> $l_new_post</a></li>
							<li><a href=\"$root/food/new_food.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/outline_restaurant_black_18dp.png\" alt=\"outline_restaurant_black_18dp.png\" /> $l_new_food</a></li>
							<li><a href=\"$root/recipes/submit_recipe.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/outline_restaurant_menu_black_18dp.png\" alt=\"outline_restaurant_menu_black_18dp.png\" /> $l_new_recipe</a></li>
							<li><a href=\"$root/exercises/new_exercise.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/outline_directions_bike_black_18dp.png\" alt=\"outline_directions_bike_black_18dp.png\" /> $l_new_exercise</a></li>
							<li><a href=\"$root/workout_plans/new_workout_plan.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/outline_directions_run_black_18dp.png\" alt=\"outline_directions_run_black_18dp.png\" /> $l_new_workout_plan</a></li>
							<li><a href=\"$root/forum/new_topic.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/outline_topic_black_18dp.png\" alt=\"outline_topic_black_18dp.png\" /> $l_new_topic</a></li>
						</ul>
					</div>
					</div> <!-- //Header user icons -->

				<!-- //Header user icons -->
					
			</div>
		<!-- //Header main -->
	</header>
<!-- //Header -->

<!-- Search -->
	<div id=\"header_search_wrapper\">
		<div class=\"header_search_inner\">
			<form method=\"get\" action=\"$root/search/search.php\" enctype=\"multipart/form-data\">
			<p>
			<input type=\"text\" name=\"inp_search_query\" id=\"inp_header_search_query\" class=\"header_center_search_text\" value=\"\" size=\"50\" autocomplete=\"off\" />
			<input type=\"hidden\" name=\"l\" value=\"$l\" />
			<input type=\"submit\" value=\"\" class=\"header_center_search_button\" />
			</p>
			</form>
			<div id=\"header_search_results\"></div>
		</div>
		<div class=\"header_search_after\"></div>
	</div>

	<!-- Search engines Autocomplete -->

		<script language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$('#inp_header_search_query').keyup(function () {
					// getting the value that user typed
					var searchString    = \$(\"#inp_header_search_query\").val();
					// forming the queryString
      					var data            = 'l=$l&inp_search_query='+ searchString;
        				// if searchString is not empty
        				if(searchString) {
						\$(\"#header_search_results\").css('visibility','visible');
						// ajax call
        					\$.ajax({
        						type: \"GET\",
        						url: \"$root/search/search_header_autocomplete.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#header_search_results\").html(''); 
							},
               						success: function(html){
                    						\$(\"#header_search_results\").append(html);
              						}
            					});
       					}
        				return false;
            			});
         		});
		</script>
	<!-- //Search engines Autocomplete -->
<!-- //Search -->


<!-- Slides -->
	";
	if($root == "." && file_exists("_uploads/slides/$l/slides.php")){
		echo"
		<div id=\"slides\">
		";
		include("_scripts/slides/show_slides.php");
		echo"
		</div>
		";
	}
	echo"
<!-- //Slides -->

<!-- Main -->
	<div id=\"main_wrapper\">

			<!-- Sub menu -->


				";
				// Find sub navigation
				$nav = "";
				if($root != "."){
					if(file_exists("navigation.php")){
						$nav = "navigation.php";
					}
					else{
						if(file_exists("../navigation.php")){
							$nav = "../navigation.php";
						}
						else{
							if(file_exists("../../navigation.php")){
								$nav = "../../navigation.php";
							}
						}
					}
				}

				if($nav != ""){
					echo"

					<div id=\"main_left\">
						<!-- Menu -->
							<div class=\"main_left_box\">
								<div id=\"div_toc\">";
									// Menu
									include("$nav");
									echo"
								</div>
							</div>
						<!-- //Menu -->

						<!-- Feed -->
						<!-- //Feed -->


					</div>
					<div id=\"main_center_double_column_wrapper\">
						<div id=\"main_center_double_column_content\">
					";
				}
				else{
					echo"

					<div id=\"main_center_single_column_wrapper\">
						<div id=\"main_center_single_column_content\">

					";
				}


				echo"
			<!-- //Sub menu -->


	";
	

} // process != 1
?>