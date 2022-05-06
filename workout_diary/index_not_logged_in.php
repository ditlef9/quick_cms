<?php
/**
*
* File: workout_diary/index_not_logged_in.php
* Version 1.0.0.
* Date 12:42 21.01.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_diary/ts_index_not_logged_in.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");


/*- Content ---------------------------------------------------------------------------------- */

echo"
	<h1>$l_track_your_workouts_for_free</h1>

	<h2>$l_strenght_and_cardio_log</h2>

	
	<img src=\"_gfx/index_not_logged_in/matthew.jpg\" alt=\"matthew.jpg\" style=\"float: right;padding: 0px 0px 10px 0px;\" />

	<p>
	<img src=\"_gfx/index_not_logged_in/check.png\" alt=\"check.png\" />
	$l_track_sets_reps_and_weights
	</p>

	<p>
	<img src=\"_gfx/index_not_logged_in/check.png\" alt=\"check.png\" />
	$l_track_distance_and_velocity
	</p>

	<p>
	<img src=\"_gfx/index_not_logged_in/check.png\" alt=\"check.png\" />
	$l_follow_your_progress_with_graphs
	</p>


	<p>
	$l_start_by_choosing_your_workout_plan
	$l_you_can_either_use_a_existing_one_or_create_your_own
	$l_then_track_your_workout_sessions
	</p>

	<p>
	$l_its_really_easy<br />
	$l_everybody_can_do_it 
	</p>

	<p>
	<a href=\"$root/users/create_free_account.php?l=$l\" class=\"btn btn_success\">$l_create_free_account</a>
	<a href=\"$root/users/login.php?referer=../workout_diary/index.php?l=$l\" class=\"btn btn_default\">$l_login</a>
	</p>
";


?>