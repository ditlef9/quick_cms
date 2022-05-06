<?php
/**
*
* File: _admin/_inc/bitmap/_liquidbase_db_scripts/transactions.php
* Version 1.0.0
* Date 14:28 25.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

mysqli_query($link, "DROP TABLE IF EXISTS $t_cran_transactions_index");


echo"

	<!-- transactions -->
	";
	$query = "SELECT * FROM $t_cran_transactions_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_cran_transactions_index: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_cran_transactions_index(
	  	 transaction_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(transaction_id), 
	  	   transaction_coint VARCHAR(250),
	  	   transaction_input_total DOUBLE,
	  	   transaction_input_total_usd DOUBLE,
	  	   transaction_output_total DOUBLE,
	  	   transaction_output_total_usd DOUBLE,

	  	   transaction_block_id INT,
	  	   transaction_external_transaction_id INT,
	  	   transaction_hash VARCHAR(200),
	  	   transaction_datetime DATETIME,
	  	   transaction_datetime_saying VARCHAR(250),
	  	   transaction_size INT,
	  	   transaction_weight INT,
	  	   transaction_version INT,
	  	   transaction_lock_time INT,
	  	   transaction_is_coinbase INT,
	  	   transaction_has_witness INT,
	  	   transaction_input_count INT,
	  	   transaction_output_count INT,
	  	   transaction_status VARCHAR(200),
	  	   transaction_amount_transacted DOUBLE,
	  	   transaction_amount_transacted_usd DOUBLE,
	  	   transaction_fee DOUBLE,
	  	   transaction_fee_usd DOUBLE,
	  	   transaction_fee_per_kb DOUBLE,
	  	   transaction_fee_per_kb_usd DOUBLE,
	  	   transaction_fee_per_kwu DOUBLE,
	  	   transaction_fee_per_kwu_usd DOUBLE,
	  	   transaction_cdd_total DOUBLE,
	  	   transaction_is_rbf INT,
	  	   transaction_added_by_user_id INT,
	  	   transaction_added_datetime DATETIME,
	  	   transaction_updated_by_user_id INT,
	  	   transaction_updated_datetime DATETIME)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //transactions -->
";
?>