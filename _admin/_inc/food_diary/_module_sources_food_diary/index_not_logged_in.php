<?php
/**
*
* File: food_diary/index_not_logged_in.php
* Version 1.0.0.
* Date 12:42 21.01.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food_diary/ts_index_not_logged_in.php");


/*- Content ---------------------------------------------------------------------------------- */

echo"
	<h1>$l_track_your_calories_for_free</h1>

	<h2>$l_lose_weight_naturally</h2>

	
	<img src=\"_gfx/index_not_logged_in/bottle-bowl-breakfast-704971.png\" alt=\"bottle-bowl-breakfast-704971.png\" style=\"float: right;padding: 0px 0px 10px 0px;\" />

	<p style=\"padding-left: 20px;\">
	<img src=\"_gfx/index_not_logged_in/check.png\" alt=\"check.png\" />
	$l_lose_four_kilos_eight_dot_eight_in_eight_weeks
	</p>

	<p style=\"padding-left: 20px;\">
	<img src=\"_gfx/index_not_logged_in/check.png\" alt=\"check.png\" />
	$l_easy_to_use
	</p>
	<p style=\"padding-left: 20px;\">
	<img src=\"_gfx/index_not_logged_in/check.png\" alt=\"check.png\" />
	$l_you_can_add_your_own_food_and_recipes 
	</p>
	

	<p style=\"padding-left: 20px;\">
	<img src=\"_gfx/index_not_logged_in/check.png\" alt=\"check.png\" />
	$l_get_help_by_other_people_who_has_lost_weight_before
	</p>
	<p>
	$l_it_has_never_been_easier_to_lose_weight $l_just_track_your_meals_with_the_food_diary
	$l_change_your_life_today
	$l_give_it_a_try_its_free
	</p>

	<p>
	<a href=\"$root/users/create_free_account.php?l=$l\" class=\"btn btn_success\">$l_start_today_click_here</a>
	<a href=\"$root/users/login.php?referer=food_diary/index.php?l=$l\" class=\"btn btn_default\">$l_login</a>
	</p>
";


?>