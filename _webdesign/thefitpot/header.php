<?php
/**
*
* File: _webdesign/thefitpot/header.php
* Version 3.1
* Date 17:55 07.02.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Web design start ------------------------------------------------------------------ */
if($process != "1") {
echo"<!DOCTYPE html>
<html lang=\"$l\">
<head>
	<title>";
	if(isset($website_title)){
		echo"$website_title";
	}
	else{
		$website_title = $_SERVER['PHP_SELF'];
		$website_title = str_replace("/", " - ", $website_title);
		$website_title = str_replace(".php", "", $website_title);
		$website_title = str_replace("_", " ", $website_title);
		$website_title = ucwords($website_title);
		echo"$website_title";
	}
	echo" | $configWebsiteTitleSav</title>

	<!-- Site CSS-->
		<link rel=\"stylesheet\" type=\"text/css\" href=\"$root/_webdesign/$webdesignSav/master.css?"; echo filesize("$root/_webdesign/$webdesignSav/master.css"); echo"\" />
	<!-- //Site CSS -->

	<!-- Special CSS -->
		";
		if(isset($pageCSSFile)){
			if(file_exists("$pageCSSFile")){
				echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"$pageCSSFile?"; echo filesize("$pageCSSFile"); echo"\" />";
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
		<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/jquery/jquery.min.js\"></script>
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

		<!-- Logo -->
			<div id=\"logo\">
				<a href=\"$root/index.php?l=$l\" title=\"$configWebsiteTitleSav\">The<span></span>Fit<span></span>Pot</a>
			</div>
		<!-- //Logo -->

		<!-- Mobile icons -->
			<div id=\"header_user_icons\">

				<!-- Hide show search, Header hamburger -->
							<script>
							\$(document).ready(function(){
								\$(\".header_show_search\").click(function () {
									\$(\".site_search\").fadeToggle();
									\$(\"#inp_site_search_query\").focus();
								});
								\$(\".header_show_new_a\").click(function () {
									\$(\"#header_new_wrapper\").fadeToggle();

									var src = (\$(\".header_show_new_img\").attr('src') === '$root/_webdesign/$webdesignSav/images/header/header_show_new_img_add_white.png')
										? '$root/_webdesign/$webdesignSav/images/header/header_show_new_img_clear_white.png'
										: '$root/_webdesign/$webdesignSav/images/header/header_show_new_img_add_white.png';
									\$(\".header_show_new_img\").attr(\"src\", src);
								});
								\$(\".main_navigation_menu_icon\").click(function () {
									\$(\"nav\").fadeToggle();

									var src = (\$(this).attr('src') === '$root/_webdesign/$webdesignSav/images/header/menu_24x24_white.png')
										? '$root/_webdesign/$webdesignSav/images/header/clear_24x24_white.png'
										: '$root/_webdesign/$webdesignSav/images/header/menu_24x24_white.png';
									\$(\".main_navigation_menu_icon\").attr('src', src);


								});
							});
							</script>
						<!-- //Hide show nav + change hamburger icon -->
						
				<!-- //Header hamburger -->




				<ul>
					<li><a href=\"#\" class=\"a_header_left_hamburger\"><img src=\"$root/_webdesign/$webdesignSav/images/header/menu_24x24_white.png\" alt=\"ic_menu_24x24_333333.png\" class=\"main_navigation_menu_icon\" /></a></li>
					<li><a href=\"#\" class=\"header_show_search\"><img src=\"$root/_webdesign/$webdesignSav/images/header/search_24x24_white.png\" alt=\"search_24x24_white.png\" /></a></li>
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
							<li><a href=\"$root/users/my_profile.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/my_profile_24x24_white.png\" alt=\"my_profile_24x24_white.png\" title=\"$get_my_user_alias\" /></a></li>
							";
						}
						else{
							echo"
							<li><a href=\"$root/users/login.php?l=$l\" class=\"last\"><img src=\"$root/_webdesign/$webdesignSav/images/header/login_24x24_white.png\" alt=\"login_24x24_white.png\" title=\"$l_login\" /></a></li>
							";
						}
						echo"
							<li><a href=\"#\" class=\"header_show_new_a\"><img src=\"$root/_webdesign/$webdesignSav/images/header/header_show_new_img_add_white.png\" alt=\"header_show_new_img_add_white.png\" class=\"header_show_new_img\" /></a></li>
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
		<!-- //Mobile icons -->

		<!-- Header navigation -->
			<nav>\n";
				// Chat
				include("$root/chat/_tables_chat.php");
				$look_for_chat_messages = 0;
				$navigation_language_mysql = quote_smart($link, $l);
				$query_nav_main = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, navigation_url_path, navigation_url_query, navigation_internal_or_external, navigation_icon_path, navigation_icon_18x18_inactive, navigation_icon_18x18_hover, navigation_icon_18x18_active FROM $t_pages_navigation WHERE navigation_parent_id='0' AND navigation_language=$navigation_language_mysql ORDER BY navigation_weight ASC";
				$result_nav_main = mysqli_query($link, $query_nav_main);
				$row_cnt_nav_main = mysqli_num_rows($result_nav_main);
				while($row_nav_main = mysqli_fetch_row($result_nav_main)) {
					list($get_a_navigation_id, $get_a_navigation_a_id, $get_a_navigation_title, $get_a_navigation_title_clean, $get_a_navigation_url, $get_a_navigation_url_path, $get_a_navigation_url_query, $get_a_navigation_internal_or_external, $get_a_navigation_icon_path, $get_a_navigation_icon_18x18_inactive, $get_a_navigation_icon_18x18_hover, $get_a_navigation_icon_18x18_active) = $row_nav_main;
					echo"				";
					echo"<p>";
					if($get_a_navigation_url != ""){
						echo"<a href=\"$root/$get_a_navigation_url_path$get_a_navigation_url_query\">$get_a_navigation_title</a>";
					}
					else{
						echo"$get_a_navigation_title";
					}
					echo"</p>

					<ul class=\"main_navigation\">\n";


					// Children level 2
					$query_c = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_internal_or_external, navigation_icon_path, navigation_icon_18x18_inactive, navigation_icon_18x18_hover, navigation_icon_18x18_active FROM $t_pages_navigation WHERE navigation_parent_id=$get_a_navigation_id AND navigation_language=$navigation_language_mysql ORDER BY navigation_weight ASC";
					$result_c = mysqli_query($link, $query_c);
					while($row_c = mysqli_fetch_row($result_c)) {
						list($get_b_navigation_id, $get_b_navigation_b_id, $get_b_navigation_title, $get_b_navigation_title_clean, $get_b_navigation_url, $get_b_navigation_url_path, $get_b_navigation_url_path_md5, $get_b_navigation_url_query, $get_b_navigation_internal_or_external, $get_b_navigation_icon_path, $get_b_navigation_icon_18x18_inactive, $get_b_navigation_icon_18x18_hover, $get_b_navigation_icon_18x18_active) = $row_c;
						echo"				";
						echo"				<li><a href=\"$root/$get_b_navigation_url_path$get_b_navigation_url_query\" class=\"nav_$get_b_navigation_url_path_md5\">$get_b_navigation_title";

					

						echo"</a></li>\n";
					}
					echo"
					</ul>
					";
				}
				echo"
			</nav>";
			if($look_for_chat_messages == "1"){
				echo"
				<!-- Look for new messages script -->
					<script language=\"javascript\" type=\"text/javascript\">
					\$(document).ready(function () {
						function navigation_look_for_new_messages_and_conversations(){

							var data = 'l=$l&starred_channel_id=$get_starred_channel_id&t_user_id=$my_user_id';
            						\$.ajax({
                						type: \"POST\",
               							url: \"$root/chat/navigation_look_for_new_messages_and_conversations.php\",
                						data: data,
								beforeSend: function(html) { // this happens before actual call
								},
               							success: function(html){
                    							\$(\"#navigation_look_for_new_messages_and_conversations_result\").html(html);
              							}
       									
							});
						}
						setInterval(navigation_look_for_new_messages_and_conversations,2000);
         				});
					</script>
				<!-- //Look for new messages script -->
				";
			}
			echo"

		<!-- //Header navigation -->

		<!-- Search -->	
			<div class=\"site_search\">
					<form method=\"get\" action=\"$root/search/search.php\" enctype=\"multipart/form-data\">
					
					<p>
					<span>$l_search</span><br />
					<input type=\"text\" name=\"inp_search_query\" id=\"inp_site_search_query\" class=\"site_search_text\" value=\"\" size=\"20\" autocomplete=\"off\" />
					<input type=\"hidden\" name=\"l\" value=\"$l\" />
					<input type=\"submit\" value=\"\" class=\"site_search_button\" />
					</p>
					</form>
					<div id=\"site_search_results\"></div>
				<div class=\"site_search_after\"></div>
			</div>

			<!-- Search engines Autocomplete -->

			<script language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$('#inp_site_search_query').keyup(function () {
					// getting the value that user typed
					var searchString    = \$(\"#inp_search_query\").val();
					// forming the queryString
      					var data            = 'l=$l&inp_search_query='+ searchString;
        				// if searchString is not empty
        				if(searchString) {
						\$(\"#site_search_results\").css('visibility','visible');
						// ajax call
        					\$.ajax({
        						type: \"GET\",
        						url: \"$root/search/search_header_autocomplete.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#site_search_results\").html(''); 
							},
               						success: function(html){
								\$(\"#site_search_results\").append(html);
              						}
            					});
       					}
        				return false;
            			});
         		});
			</script>
			<!-- //Search engines Autocomplete -->
		<!-- //Search -->

		

		<!-- Header Bottom -->
			<div class=\"header_bottom\">
				<ul>
				";
				// Am I logged in?
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					// Get my user alias, date format, profile image
					$my_user_id 	  = $_SESSION['user_id'];
					$my_user_id_mysql = quote_smart($link, $my_user_id);

					// User
					$query = "SELECT user_id, user_name, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_date_format) = $row;
					if($get_my_user_id == ""){
						echo"<p style=\"color:red\">Session error</p>";
						unset($_SESSION['user_id']);
					}

					// Image
					$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_title, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60 FROM $t_users_profile_photo WHERE photo_user_id=$get_my_user_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_photo_id, $get_my_photo_user_id, $get_my_photo_profile_image, $get_my_photo_title, $get_my_photo_destination, $get_my_photo_thumb_40, $get_my_photo_thumb_50, $get_my_photo_thumb_60) = $row;

					echo"
					<li><a href=\"$root/users/my_profile.php?l=$l\">";
					if(file_exists("$root/$get_my_photo_destination/$get_my_photo_thumb_40") && $get_my_photo_thumb_40 != ""){
						echo"<img src=\"$root/$get_my_photo_destination/$get_my_photo_thumb_40\" alt=\"$get_my_photo_thumb_40\" title=\"$get_my_user_alias\" />";
					}
					else{
						echo"<img src=\"$root/_webdesign/thefitpot/images/left/user_no_image_30.png\" alt=\"user_no_image_40.png\" />";
					}

					echo" $get_my_user_name</a></li>
					";
				}
				else{
					echo"
					<li><a href=\"$root/users/login.php?l=$l\" class=\"last\"><img src=\"$root/_webdesign/$webdesignSav/images/header/login_24x24_white.png\" alt=\"ic_login_24x24_333333.png\" title=\"$l_login\" /> $l_login</a></li>
					";
				}
				echo"
				</ul>
			</div>
		<!-- //Header Bottom -->
	</header>
<!-- //Header -->


<!-- Content -->
	<main>
		<div id=\"main_inner\">

	";
	

} // process != 1
?>