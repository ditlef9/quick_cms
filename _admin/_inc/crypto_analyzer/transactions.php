<?php
/**
*
* File: _admin/_inc/crypto_tracker/transactions.php
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



echo"
<h1>Transactions</h1>

<p>
<a href=\"index.php?open=crypto_tracker&amp;page=transactions_add&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Add</a>
</p>


";

?>