<?php
/**
*
* File: _admin/_inc/throw_the_dice/default.php
* Version 
* Date 10:27 09.12.2021
* Copyright (c) 2008-2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_dice_liquidbase 	 = $mysqlPrefixSav . "dice_liquidbase";

$t_dice_game_index	= $mysqlPrefixSav . "dice_game_index";
$t_dice_game_members	= $mysqlPrefixSav . "dice_game_members";

/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;

if(isset($_GET['where'])){
	$where = $_GET['where'];
	$where = output_html($where);
}
else {
	$where = "comment_approved != '-1'";
}


/*- Check if setup is run ------------------------------------------------------------- */
$query = "SELECT * FROM $t_dice_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	echo"
	<h1>Throw the Dice</h1>
				

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=throw_the_dice&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Throw the Dice</a>
		&gt;
		<a href=\"index.php?open=throw_the_dice&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Throw the Dice index</a>
		</p>
	<!-- //Where am I? -->


	
	";
}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>