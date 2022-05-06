<?php
/**
*
* File: _admin/_inc/picture_it/default.php
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




/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
}


/*- Config ------------------------------------------------------------------------------- */
if(!(file_exists("_data/rebus.php"))){
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=rebus&amp;page=settings&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
}

/*- Variables ------------------------------------------------------------------------ */
echo"
<h1>Rebus</h1>
";
?>