<?php
/**
*
* File: _admin/_liquidbase/db_scripts/webdesign/front_page_items.php
* Version 1.0.0
* Date 19:10 06.05.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){

	/*- Tables ---------------------------------------------------------------------------- */


	$t_webdesign_front_page_items	= $mysqlPrefixSav . "webdesign_front_page_items";
	$t_users			= $mysqlPrefixSav . "users";


	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_webdesign_front_page_items") or die(mysqli_error($link)); 



	echo"
	<!-- front_page_items -->
	";

	$query = "SELECT * FROM $t_webdesign_front_page_items LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_webdesign_front_page_items: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_webdesign_front_page_items(
		  item_id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(item_id), 
		   item_type VARCHAR(200), 
		   item_title VARCHAR(200), 
		   item_connected_to_module_name VARCHAR(200),
		   item_connected_to_module_part_name VARCHAR(200),
		   item_text TEXT,
		   item_weight INT, 
		   item_language VARCHAR(5),
		   item_updated_by_user_id INT,
		   item_updated_by_user_name VARCHAR(50) ,
		   item_updated_datetime DATETIME,
		   item_updated_datetime_saying VARCHAR(50) 
		   )")
		   or die(mysqli_error());

		// Date
		$datetime = date("Y-m-d H:i:s");
		$datetime_saying = date("j M Y");

		// Me
		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$my_security = $_SESSION['admin_security'];
		$my_security = output_html($my_security);
		$my_security_mysql = quote_smart($link, $my_security);

		$query = "SELECT user_id, user_email, user_name, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;


		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);




		$front_page_items = array(
  array('item_id' => '1','item_type' => 'text','item_title' => 'Welcome text','item_connected_to_module_name' => NULL,'item_connected_to_module_part_name' => NULL,'item_text' => 'Welcome to my website!','item_weight' => '1','item_language' => 'en','item_updated_by_user_id' => NULL,'item_updated_by_user_name' => NULL,'item_updated_datetime' => NULL,'item_updated_datetime_saying' => NULL)
);



		foreach ($front_page_items as $item) {
			$item_id = quote_smart($link, $item['item_id']);
			$item_type = quote_smart($link, $item['item_type']);
			$item_title = quote_smart($link, $item['item_title']);
			$item_connected_to_module_name = quote_smart($link, $item['item_connected_to_module_name']);
			$item_connected_to_module_part_name = quote_smart($link, $item['item_connected_to_module_part_name']);
			$item_text = quote_smart($link, $item['item_text']);
			$item_weight = quote_smart($link, $item['item_weight']);
			$item_language = quote_smart($link, $item['item_language']);


			mysqli_query($link, "INSERT INTO $t_webdesign_front_page_items (item_id, item_type, item_title, item_connected_to_module_name, item_connected_to_module_part_name, 
						item_text, item_weight, item_language, item_updated_by_user_id, item_updated_by_user_name, 
						item_updated_datetime, item_updated_datetime_saying) 
						VALUES
						(NULL, $item_type, $item_title, $item_connected_to_module_name, $item_connected_to_module_part_name, 
						$item_text, $item_weight, $item_language, $get_my_user_id, $inp_my_user_name_mysql, 
						'$datetime', '$datetime_saying')") or die(mysqli_error($link));

		} // foreach
	}
	echo"
	<!-- //front_page_items -->
	";
} // access
?>