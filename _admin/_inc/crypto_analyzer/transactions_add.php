<?php
/**
*
* File: _admin/_inc/crypto_tracker/transactions_add.php
* Version 10:19 10.08.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables --------------------------------------------------------------------------- */
$t_crypto_tracker_liquidbase		= $mysqlPrefixSav . "crypto_tracker_liquidbase";
$t_crypto_tracker_transactions_index	= $mysqlPrefixSav . "crypto_tracker_transactions_index";
$t_crypto_tracker_transactions_inputs	= $mysqlPrefixSav . "crypto_tracker_transactions_inputs";
$t_crypto_tracker_transactions_outputs	= $mysqlPrefixSav . "crypto_tracker_transactions_outputs";
$t_crypto_tracker_wallets		= $mysqlPrefixSav . "crypto_tracker_wallets";
$t_crypto_tracker_blocks		= $mysqlPrefixSav . "crypto_tracker_blocks";


/*- Variables ------------------------------------------------------------------------ */

if($action == ""){
	echo"
	<h1>Add transaction</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=crypto_tracker&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Crypto tracker</a>
		&gt;
		<a href=\"index.php?open=crypto_tracker&amp;page=transactions&amp;editor_language=$editor_language&amp;l=$l\">Transactions</a>
		&gt;
		<a href=\"index.php?open=crypto_tracker&amp;page=transactions_add&amp;editor_language=$editor_language&amp;l=$l\">Add transaction</a>
		</p>
	<!-- //Where am I? -->

	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_transaction_external_id\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- Add form -->
		<form method=\"post\" action=\"index.php?open=crypto_tracker&amp;page=transactions_add&amp;action=do_add&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">
		

		<p>Transaction external id:<br />
		<input type=\"text\" name=\"inp_transaction_external_id\" value=\"116bd19a3ec5f210ce72043115a4d5d3ef08f7556829c4feac8d89de3195ea4e\" size=\"25\" style=\"width: 90%;\" />
		</p>

		<p>
		<input type=\"submit\" value=\"Add\" class=\"btn_default\" /></p>

		</form>

	<!-- //Add form -->
	";
}
elseif($action == "do_add"){
	// Dates
	$datetime = date("Y-m-d H:i:s");

	// Get data
	$inp_transaction_external_id = $_POST['inp_transaction_external_id'];
	$inp_transaction_external_id = output_html($inp_transaction_external_id);
	$json_url = "https://api.blockchair.com/bitcoin/dashboards/transaction/$inp_transaction_external_id?omni=true&privacy-o-meter=true";
	$json_data = file_get_contents($json_url);

	$json_array = json_decode($json_data, TRUE);


	// Transaction
	$transaction_block_id = $json_array['data']["$inp_transaction_external_id"]["transaction"]["block_id"];
	$transaction_block_id = output_html($transaction_block_id);
	$transaction_block_id_mysql = quote_smart($link, $transaction_block_id);

	$transaction_external_transaction_id = $json_array['data']["$inp_transaction_external_id"]["transaction"]["id"];
	$transaction_external_transaction_id = output_html($transaction_external_transaction_id);
	$transaction_external_transaction_id_mysql = quote_smart($link, $transaction_external_transaction_id);

	$transaction_hash = $json_array['data']["$inp_transaction_external_id"]["transaction"]["hash"];
	$transaction_hash = output_html($transaction_hash);
	$transaction_hash_mysql = quote_smart($link, $transaction_hash);

	$transaction_datetime = $json_array['data']["$inp_transaction_external_id"]["transaction"]["time"];
	$transaction_datetime = output_html($transaction_datetime);
	$transaction_datetime_mysql = quote_smart($link, $transaction_datetime);

	$transaction_datetime_saying = "$transaction_datetime";
	$transaction_datetime_saying_mysql = quote_smart($link, $transaction_datetime_saying);

	$transaction_size = $json_array['data']["$inp_transaction_external_id"]["transaction"]["size"];
	$transaction_size = output_html($transaction_size);
	$transaction_size_mysql = quote_smart($link, $transaction_size);

	$transaction_weight = $json_array['data']["$inp_transaction_external_id"]["transaction"]["weight"];
	$transaction_weight = output_html($transaction_weight);
	$transaction_weight_mysql = quote_smart($link, $transaction_weight);

	$transaction_version = $json_array['data']["$inp_transaction_external_id"]["transaction"]["version"];
	$transaction_version = output_html($transaction_version);
	$transaction_version_mysql = quote_smart($link, $transaction_version);

	$transaction_lock_time = $json_array['data']["$inp_transaction_external_id"]["transaction"]["lock_time"];
	$transaction_lock_time = output_html($transaction_lock_time);
	$transaction_lock_time_mysql = quote_smart($link, $transaction_lock_time);

	$transaction_is_coinbase = $json_array['data']["$inp_transaction_external_id"]["transaction"]["is_coinbase"];
	$transaction_is_coinbase = output_html($transaction_is_coinbase);
	if($transaction_is_coinbase == ""){
		$transaction_is_coinbase = 0;
	}
	$transaction_is_coinbase_mysql = quote_smart($link, $transaction_is_coinbase);

	$transaction_has_witness = $json_array['data']["$inp_transaction_external_id"]["transaction"]["has_witness"];
	$transaction_has_witness = output_html($transaction_has_witness);
	$transaction_has_witness_mysql = quote_smart($link, $transaction_has_witness);

	$transaction_input_count = $json_array['data']["$inp_transaction_external_id"]["transaction"]["input_count"];
	$transaction_input_count = output_html($transaction_input_count);
	$transaction_input_count_mysql = quote_smart($link, $transaction_input_count);

	$transaction_output_count = $json_array['data']["$inp_transaction_external_id"]["transaction"]["output_count"];
	$transaction_output_count = output_html($transaction_output_count);
	$transaction_output_count_mysql = quote_smart($link, $transaction_output_count);

	$transaction_input_total = $json_array['data']["$inp_transaction_external_id"]["transaction"]["input_total"];
	$transaction_input_total = output_html($transaction_input_total);
	$transaction_input_total_mysql = quote_smart($link, $transaction_input_total);

	$transaction_input_total_usd = $json_array['data']["$inp_transaction_external_id"]["transaction"]["input_total_usd"];
	$transaction_input_total_usd = output_html($transaction_input_total_usd);
	$transaction_input_total_usd_mysql = quote_smart($link, $transaction_input_total_usd);

	$transaction_output_total = $json_array['data']["$inp_transaction_external_id"]["transaction"]["output_total"];
	$transaction_output_total = output_html($transaction_output_total);
	$transaction_output_total_mysql = quote_smart($link, $transaction_output_total);

	$transaction_output_total_usd = $json_array['data']["$inp_transaction_external_id"]["transaction"]["output_total_usd"];
	$transaction_output_total_usd = output_html($transaction_output_total_usd);
	$transaction_output_total_usd_mysql = quote_smart($link, $transaction_output_total_usd);

	$transaction_fee = $json_array['data']["$inp_transaction_external_id"]["transaction"]["fee"];
	$transaction_fee = output_html($transaction_fee);
	$transaction_fee_mysql = quote_smart($link, $transaction_fee);

	$transaction_fee_usd = $json_array['data']["$inp_transaction_external_id"]["transaction"]["fee_usd"];
	$transaction_fee_usd = output_html($transaction_fee_usd);
	$transaction_fee_usd_mysql = quote_smart($link, $transaction_fee_usd);

	$transaction_fee_per_kb = $json_array['data']["$inp_transaction_external_id"]["transaction"]["fee_per_kb"];
	$transaction_fee_per_kb = output_html($transaction_fee_per_kb);
	$transaction_fee_per_kb_mysql = quote_smart($link, $transaction_fee_per_kb);

	$transaction_fee_per_kb_usd = $json_array['data']["$inp_transaction_external_id"]["transaction"]["fee_per_kb_usd"];
	$transaction_fee_per_kb_usd = output_html($transaction_fee_per_kb_usd);
	$transaction_fee_per_kb_usd_mysql = quote_smart($link, $transaction_fee_per_kb_usd);

	$transaction_fee_per_kwu = $json_array['data']["$inp_transaction_external_id"]["transaction"]["fee_per_kwu"];
	$transaction_fee_per_kwu = output_html($transaction_fee_per_kwu);
	$transaction_fee_per_kwu_mysql = quote_smart($link, $transaction_fee_per_kwu);

	$transaction_fee_per_kwu_usd = $json_array['data']["$inp_transaction_external_id"]["transaction"]["fee_per_kwu_usd"];
	$transaction_fee_per_kwu_usd = output_html($transaction_fee_per_kwu_usd);
	$transaction_fee_per_kwu_usd_mysql = quote_smart($link, $transaction_fee_per_kwu_usd);

	$transaction_cdd_total = $json_array['data']["$inp_transaction_external_id"]["transaction"]["cdd_total"];
	$transaction_cdd_total = output_html($transaction_cdd_total);
	$transaction_cdd_total_mysql = quote_smart($link, $transaction_cdd_total);

	$transaction_is_rbf = $json_array['data']["$inp_transaction_external_id"]["transaction"]["is_rbf"];
	$transaction_is_rbf = output_html($transaction_is_rbf);
	if($transaction_is_rbf == ""){
		$transaction_is_rbf = 0;
	}
	$transaction_is_rbf_mysql = quote_smart($link, $transaction_is_rbf);
	
	// Check if exists
	$query_t = "SELECT transaction_id FROM $t_crypto_tracker_transactions_index WHERE transaction_external_transaction_id=$transaction_external_transaction_id_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_transaction_id) = $row_t;
	if($get_current_transaction_id == ""){
		// Insert
		mysqli_query($link, "INSERT INTO $t_crypto_tracker_transactions_index 
		(transaction_id, transaction_added_by_user_id, transaction_added_datetime, transaction_coint, transaction_input_total, 
		transaction_input_total_usd, transaction_output_total, transaction_output_total_usd, transaction_block_id, transaction_external_transaction_id, 
		transaction_hash, transaction_datetime, transaction_datetime_saying, transaction_size, transaction_weight, 
		transaction_version, transaction_lock_time, transaction_is_coinbase, transaction_has_witness, transaction_input_count, 
		transaction_output_count, transaction_status, transaction_amount_transacted, transaction_amount_transacted_usd, transaction_fee, 
		transaction_fee_usd, transaction_fee_per_kb, transaction_fee_per_kb_usd, transaction_fee_per_kwu, transaction_fee_per_kwu_usd,
		transaction_cdd_total, transaction_is_rbf) 
		VALUES 
		(NULL, $get_my_user_id, '$datetime', 'bitcoin', $transaction_input_total_mysql, 
		$transaction_input_total_usd_mysql, $transaction_output_total_mysql, $transaction_output_total_usd_mysql, $transaction_block_id_mysql, $transaction_external_transaction_id_mysql,
		$transaction_hash_mysql, $transaction_datetime_mysql, $transaction_datetime_saying_mysql, $transaction_size_mysql, $transaction_weight_mysql, 
		$transaction_version_mysql, $transaction_lock_time_mysql, $transaction_is_coinbase_mysql, $transaction_has_witness_mysql, $transaction_input_count_mysql, 
		$transaction_output_count_mysql, 'status', -1, -1, $transaction_fee_mysql, 
		$transaction_fee_usd_mysql, $transaction_fee_per_kb_mysql, $transaction_fee_per_kb_usd_mysql, $transaction_fee_per_kwu_mysql, $transaction_fee_per_kwu_usd_mysql, 
		$transaction_cdd_total_mysql, $transaction_is_rbf_mysql)")
		or die(mysqli_error($link));
		
		// Get ID
		$query_t = "SELECT transaction_id FROM $t_crypto_tracker_transactions_index WHERE transaction_external_transaction_id=$transaction_external_transaction_id_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_transaction_id) = $row_t;
	}
	else{
		// Update 
		mysqli_query($link, "UPDATE 
				transaction_input_total=, 
				transaction_input_total_usd=, 
				transaction_output_total=, 
				transaction_output_total_usd=, 
				transaction_block_id=, 
				transaction_external_transaction_id=, 
				transaction_hash=, 
				transaction_datetime=,  
				transaction_datetime_saying, transaction_size=,  
				transaction_weight=, 
				transaction_version=, 
				transaction_lock_time=,  
				transaction_is_coinbase=,  
				transaction_has_witness=,  
				transaction_input_count=, 
				transaction_output_count=, 
				transaction_status=,  
				transaction_amount_transacted=,  
				transaction_amount_transacted_usd=,  
				transaction_fee=, 
				transaction_fee_usd=, 
				transaction_fee_per_kb=,  
				transaction_fee_per_kb_usd=,  
				transaction_fee_per_kwu=,  
				transaction_fee_per_kwu_usd=,
				transaction_cdd_total=, 
				transaction_is_rbf=,
				transaction_updated_by_user_id=$get_my_user_id, 
				transaction_updated_datetime='$datetime'
				WHERE transaction_id=$get_current_transaction_id") or die(mysqli_error($link));
	}
	echo"
	<!-- Transaction -->
		<h2>Transaction</h2>
		<table>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>ID:</b></span>
		  </td>
		  <td>
			<span>$get_current_transaction_id</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Block id:</b></span>
		  </td>
		  <td>
			<span>$transaction_block_id</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>external_transaction_id:</b></span>
		  </td>
		  <td>
			<span>$transaction_external_transaction_id</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Hash:</b></span>
		  </td>
		  <td>
			<span>$transaction_hash</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>datetime:</b></span>
		  </td>
		  <td>
			<span>$transaction_datetime</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>datetime_saying:</b></span>
		  </td>
		  <td>
			<span>$transaction_datetime_saying </span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Size:</b></span>
		  </td>
		  <td>
			<span>$transaction_size</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Weight:</b></span>
		  </td>
		  <td>
			<span>$transaction_weight</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Version:</b></span>
		  </td>
		  <td>
			<span>$transaction_version</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Lock time:</b></span>
		  </td>
		  <td>
			<span>$transaction_lock_time</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Is coinbase:</b></span>
		  </td>
		  <td>
			<span>$transaction_is_coinbase</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Has witness:</b></span>
		  </td>
		  <td>
			<span>$transaction_has_witness</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Input count:</b></span>
		  </td>
		  <td>
			<span>$transaction_input_count</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Output count:</b></span>
		  </td>
		  <td>
			<span>$transaction_output_count</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Input total:</b></span>
		  </td>
		  <td>
			<span>$transaction_input_total</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Input total usd:</b></span>
		  </td>
		  <td>
			<span>$transaction_input_total_usd</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Output_total:</b></span>
		  </td>
		  <td>
			<span>$transaction_output_total</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>output_total_usd:</b></span>
		  </td>
		  <td>
			<span>$transaction_output_total_usd</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Fee:</b></span>
		  </td>
		  <td>
			<span>$transaction_fee</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Fee USD:</b></span>
		  </td>
		  <td>
			<span>$transaction_fee_usd</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Fee per kb:</b></span>
		  </td>
		  <td>
			<span>$transaction_fee_per_kb</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Fee per kb USD:</b></span>
		  </td>
		  <td>
			<span>$transaction_fee_per_kb_usd</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Fee per kwu:</b></span>
		  </td>
		  <td>
			<span>$transaction_fee_per_kwu</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Fee per kwu USD:</b></span>
		  </td>
		  <td>
			<span>$transaction_fee_per_kwu_usd</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>CDD total:</b></span>
		  </td>
		  <td>
			<span>$transaction_cdd_total</span>
		  </td>
		 </tr>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span><b>Is rbf:</b></span>
		  </td>
		  <td>
			<span>$transaction_is_rbf</span>
		  </td>
		 </tr>
		</table>
	<!-- //Transaction -->

	";

	echo"<pre>";
	print_r( $json_array['data']["$inp_transaction_external_id"]);

	echo"</pre>";
}
?>