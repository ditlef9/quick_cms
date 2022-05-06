<?php
/**
*
* File: _admin/_functions/quote_smart.php
* Version 23.37 23.11.2016
* Copyright (c) 2008-2016 Solo
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
function quote_smart($link, $value){
        //Change decimal values from , to . if applicable
        if( is_numeric($value) && strpos($value,',') !== false ){
                $value = str_replace(',','.',$value);
        }
        if( is_null($value) ){
                $value = 'NULL';
        }
        // Quote if not integer or null
        elseif (!is_numeric($value)) {
                $value = "'" . mysqli_real_escape_string($link, $value) . "'";
        }
	
	// Illegal double
	if($value == "3e2018" OR $value == "5e0817" OR $value == "4e2546"){
		$value = "'" . $value . "'";
	}
        return $value;
}
?>