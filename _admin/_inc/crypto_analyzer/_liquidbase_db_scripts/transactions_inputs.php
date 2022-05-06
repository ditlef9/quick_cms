<?php
/**
*
* File: _admin/_inc/bitmap/_liquidbase_db_scripts/transactions_input.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_cran_transactions_inputs");


echo"

	<!-- transactions_inputs -->
	";
	$query = "SELECT * FROM $t_cran_transactions_inputs";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_cran_transactions_inputs: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_cran_transactions_inputs(
	  	 input_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(input_id), 
	  	   input_transaction_id INT,
	  	   input_block_id INT,
	  	   input_external_transaction_id INT,
	  	   input_index INT,
	  	   input_transaction_hash VARCHAR(200),
	  	   input_datetime DATETIME,
	  	   input_datetime_saying VARCHAR(250),
	  	   input_value DOUBLE,
	  	   input_value_usd DOUBLE,
	  	   input_recipient VARCHAR(250),
	  	   input_type VARCHAR(250),
	  	   input_script_hex VARCHAR(250),
	  	   input_is_from_coinbase INT,
	  	   input_is_spendable INT,
	  	   input_is_spent INT,
	  	   input_spending_block_id INT,
	  	   input_spending_external_transaction_id INT,
	  	   input_spending_index INT,
	  	   input_spending_transaction_hash VARCHAR(250),
	  	   input_spending_datetime DATETIME,
	  	   input_spending_datetime_saying VARCHAR(250),
	  	   input_spending_value_usd DOUBLE,
	  	   input_spending_sequence INT,
	  	   input_spending_witness TEXT,
	  	   input_spending_lifespan INT,
	  	   input_spending_cdd INT,
	  	   input_scripthash_type VARCHAR(200))")
		   or die(mysqli_error());
	}
	echo"
	<!-- //transactions_inputs -->
";
?>