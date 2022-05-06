<?php
/**
*
* File: _webdesign/lifestyle/header.php
* Version 2.0
* Date 19:04 15.10.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
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
		";
		$datetime = date("Y-m-d H:i:s");
		echo"
		<link rel=\"stylesheet\" type=\"text/css\" href=\"$root/_webdesign/$webdesignSav/reset.css?rand=$datetime\" />
		<link rel=\"stylesheet\" type=\"text/css\" href=\"$root/_webdesign/$webdesignSav/master.css?rand=$datetime\" />
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
	<!-- Hide show nav -->
		<script>
		\$(document).ready(function(){
			\$(\".main_navigation_menu_icon\").click(function () {
				\$(\"#main_navigation_wrapper\").toggle();
			});
		});
		</script>
	<!-- //Hide show nav -->


	<header>
		<!-- Header main -->
			<div id=\"header_main\">
					<div id=\"header_left\">
						<script>
						\$(document).ready(function(){
							\$(\"#logo\").click(function () {
								window.location = $root/index.php?l=$l; // redirect
							});
						});
						</script>
						<ul>
							<li class=\"li_header_left_hamburger\"><a href=\"#\" class=\"a_header_left_hamburger\"><img src=\"$root/_webdesign/$webdesignSav/images/header/ic_menu.png\" alt=\"main_navigation_menu_icon.png\" class=\"main_navigation_menu_icon\" /></a></li>
							<li class=\"li_header_left_logo\"><a href=\"$root/index.php?l=$l\" class=\"a_header_left_logo\"><div id=\"logo\"></div></a></li>
						</ul>
					</div>

					<div id=\"header_center\">
						<!-- Search -->
						<div class=\"header_center_search_div\">
							<form method=\"get\" action=\"$root/search/search.php\" enctype=\"multipart/form-data\">
							<input type=\"text\" name=\"inp_search_query\" id=\"inp_search_query\" class=\"header_center_search_text\" value=\"\" size=\"50\" autocomplete=\"off\" />
							<input type=\"hidden\" name=\"l\" value=\"$l\" />
							<input type=\"submit\" value=\"\" class=\"header_center_search_button\" />
							</form>
							<div id=\"header_search_results\"></div>
						</div>


						<!-- Search engines Autocomplete -->
						<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
							$(document).ready(function () {
								$('#inp_search_query').keyup(function () {
									// getting the value that user typed
									var searchString    = $(\"#inp_search_query\").val();
									// forming the queryString
      									var data            = 'l=$l&inp_search_query='+ searchString;
         
        								// if searchString is not empty
        								if(searchString) {
										$(\"#header_search_results\").css('visibility','visible');
										// ajax call
        									$.ajax({
        										type: \"GET\",
        										url: \"$root/search/search_header_autocomplete.php\",
                									data: data,
											beforeSend: function(html) { // this happens before actual call
												$(\"#header_search_results\").html(''); 
											},
               										success: function(html){
                    										$(\"#header_search_results\").append(html);
              										}
            									});
       									}
        								return false;
            							});
         						});
						</script>
						<!-- //Search engines Autocomplete -->

						<!-- //Search -->
					</div>
					<div id=\"header_right\">
						<ul>
							<!-- <li><a href=\"$root/search/index.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/ic_search_0d0d0d.png\" alt=\"ic_search_0d0d0d.png\" /></a></li> -->
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
							<li><a href=\"$root/users/my_profile.php?l=$l\">$get_my_user_alias</a></li>
							<li><a href=\"$root/users/logout.php?process=1&amp;l=$l\" class=\"last\">$l_logout</a></li>
							";
						}
						else{
							echo"
							<li><a href=\"$root/users/login.php?l=$l\">$l_login</a></li>
							<li><a href=\"$root/users/create_free_account.php?l=$l\" class=\"last\" title=\"$l_create_free_account\">$l_registrer</a></li>
							";
						}
						echo"
						</ul>
					</div> <!-- //Header right -->
					<div id=\"header_right_mobile\">
						<p><a href=\"#\" id=\"ic_more_vert_link_img\"><img src=\"$root/_webdesign/$webdesignSav/images/header/ic_more_vert.png\" alt=\"ic_more_vert.png\" /></a></p>

						<script>
						\$(document).ready(function(){
							\$(\"#ic_more_vert_link_img\").click(function () {
								\$(\"#ic_more_vert_div\").toggle();
							});
						});
						</script>

						<!-- Mobile user -->
						<div id=\"ic_more_vert_div\">

							<ul>
							";
							if(isset($my_user_id)){
								echo"
								<li><a href=\"$root/users/my_profile.php?l=$l\">$get_my_user_alias</a></li>
								<li class=\"last\"><a href=\"$root/users/logout.php?process=1&amp;l=$l\">$l_logout</a></li>
								";
							}
							else{
								echo"
								<li><a href=\"$root/users/login.php?l=$l\">$l_login</a></li>
								<li class=\"last\"><a href=\"$root/users/create_free_account.php?l=$l\">$l_registrer</a></li>
								";
							}
							echo"
							</ul>
							<!-- //Mobile user -->
						</div>

					</div> <!-- //Header right mobile -->
					
			</div>
		<!-- //Header main -->
	</header>
<!-- //Header -->




<!-- Navigation -->
	<div id=\"main_navigation_wrapper\">
		<div id=\"main_navigation\">
			<!-- Mobile only -->
				<div id=\"mobile_only\">
					<!-- Mobile search -->
						<script>
						\$(document).ready(function(){
							\$(\"#mobile_only_search_q\").focus(function () {
								\$(\"#mobile_only_search_q\").val(\"\");
							});
						});
						</script>
        					<form method=\"get\" action=\"$root/_scripts/go/search.php\" enctype=\"multipart/form-data\">
						<div id=\"mobile_only_search_form\">
							<span><input type=\"text\" id=\"mobile_only_search_q\" name=\"q\" size=\"19\" value=\"$l_search...\" /></span>
						
							<div class=\"mobile_only_search_button_div\">
								<span><input type=\"submit\" id=\"mobile_only_search_button\" value=\" \" /></span>
							</div>
						</div>
						</form>
					<!-- //Mobile search -->
				</div>
				<div class=\"clear\"></div>
			<!-- //Mobile only -->
			<ul>\n";
				


			/*
			SIMPLE MENU:
			<div id=\"header_menu\">
				<ul>
							";
							// Get menu items
							$navigation_language_mysql = quote_smart($link, $l);
							$query = "SELECT navigation_title, navigation_url_path, navigation_url_query FROM $t_navigation WHERE navigation_parent_id='0' AND navigation_language=$navigation_language_mysql ORDER BY navigation_weight ASC";
							$result = mysqli_query($link, $query);
							$row_cnt = mysqli_num_rows($result);
							$x = 1;
							while($row = mysqli_fetch_row($result)) {
								list($get_navigation_title, $get_navigation_url_path, $get_navigation_url_query) = $row;
								echo"					";
								echo"<li><a href=\"$root/$get_navigation_url_path$get_navigation_url_query\""; if($url_minus_two == "$get_navigation_url_path"){ echo" class=\"selected\"";} else{ if($x == $row_cnt){ echo" class=\"last\""; } } echo">$get_navigation_title</a></li>\n";

								$x++;
							}
							echo"
						</ul>
					</div>
			*/



			echo"
	<!-- Hide show nav -->
		<script>
		\$(document).ready(function(){
			\$(\".toggle\").click(function () {
				var idname= \$(this).data('divid');
				$(\".\"+idname).toggle();
			});
		});
		</script>
	<!-- //Hide show nav -->
			";

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
		</div> <!-- //main_navigation -->
	</div>
	<div id=\"after_main_navigation\"></div>
<!-- //Navigation -->

<div class=\"clear\"></div>
<!-- //Header -->


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
					<div id=\"main_center_double_column\">
					";
				}
				else{
					echo"

					<div id=\"main_center_single_column\">

					";
				}


				echo"
			<!-- //Sub menu -->


	";
	

} // process != 1
?>