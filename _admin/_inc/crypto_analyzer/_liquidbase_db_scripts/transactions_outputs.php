<?php
/**
*
* File: _admin/_inc/bitmap/_liquidbase_db_scripts/transactions_outputs.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_cran_transactions_outputs");


echo"

	<!-- transactions_outputs -->
	";
	$query = "SELECT * FROM $t_cran_transactions_outputs";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_cran_transactions_outputs: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_cran_transactions_outputs(
	  	 output_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(output_id), 
	  	   output_transaction_id INT,
	  	   output_block_id INT,
	  	   output_external_transaction_id INT,
	  	   output_index INT,
	  	   output_transaction_hash VARCHAR(200),
	  	   output_datetime DATETIME,
	  	   output_datetime_saying VARCHAR(250),
	  	   output_value DOUBLE,
	  	   output_value_usd DOUBLE,
	  	   output_recipient VARCHAR(250),
	  	   output_type VARCHAR(250),
	  	   output_script_hex VARCHAR(250),
	  	   output_is_from_coinbase INT,
	  	   output_is_spendable INT,
	  	   output_is_spent INT,
	  	   output_spending_block_id INT,
	  	   output_spending_external_transaction_id INT,
	  	   output_spending_index INT,
	  	   output_spending_transaction_hash VARCHAR(250),
	  	   output_spending_datetime DATETIME,
	  	   output_spending_datetime_saying VARCHAR(250),
	  	   output_spending_value_usd DOUBLE,
	  	   output_spending_sequence INT,
	  	   output_spending_witness TEXT,
	  	   output_spending_lifespan INT,
	  	   output_spending_cdd INT,
	  	   output_scripthash_type VARCHAR(200))")
		   or die(mysqli_error());
	}
	echo"
	<!-- //transactions_outputs -->

";

?>