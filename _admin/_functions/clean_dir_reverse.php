<?php
/**
*
* File: admin/php/functions/clean_dir_reverse.php
* Version 16:05 25.08.2011
* Copyright (c) 2008-2011 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
function clean_dir_reverse($value){
        // Stripslashes
	$value = ucfirst($value);
	$value = str_replace("_", " ", $value);

        return $value;
}
?>