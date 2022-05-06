<?php
/**
*
* File: _admin/_inc/hash_db/default.php
* Version 
* Date 20:34 23.02.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}




/*- Config ------------------------------------------------------------------------------- */
if(!(file_exists("_data/hash_db.php"))){
	$datetime = date("ymdhis");
	$update_file="<?php
\$hashDbApiActiveSav 	= \"1\";
\$hashDbApiPasswordSav 	= \"$datetime\";
?>";

		$fh = fopen("_data/hash_db.php", "w+") or die("can not open file");
		fwrite($fh, $update_file);
		fclose($fh);
}
include("_data/hash_db.php");

/*- Scriptstart ------------------------------------------------------------------------ */

echo"
<h1>Hash DB</h1>


<!-- Hash DB menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/hash_db/menu.php");
			echo"
		</ul>
	</div>
<!-- //Hash DB menu -->
";

?>