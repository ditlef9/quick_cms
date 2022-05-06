<?php
/**
*
* File: admin/php/functions/create_height.php
* Version 21:13 22.02.2012
* Copyright (c) 2008-2012 Sindre Andre Ditlefsen
* Thanks for the help giaever
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
function create_height($MaxHeight, $HighestHit, $Hit){
	if($HighestHit == "0"){
		return 1;
	}
	else{	
		return ( ( ( $MaxHeight - 1 ) / $HighestHit ) * $Hit ) + 1;
	}
}
?>