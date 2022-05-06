<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/prices_currencies.php
* Version 1.0.0
* Date 15:43 18.10.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_prices_currencies") or die(mysqli_error($link)); 


echo"


	<!-- food_prices_currencies -->
	";
	$query = "SELECT * FROM $t_food_prices_currencies";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_prices_currencies: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_prices_currencies(
	  	 currency_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(currency_id), 
	  	   currency_name VARCHAR(200), 
	  	   currency_code VARCHAR(200), 
	  	   currency_symbol VARCHAR(200), 
	  	   currency_country_id VARCHAR(200), 
	  	   currency_country_name VARCHAR(200), 
	  	   currency_last_used_language VARCHAR(200))")
		   or die(mysqli_error());

mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Leke', 'ALL', 'Lek')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'USD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Afghanis', 'AFN', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pesos', 'ARS', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Guilders', 'AWG', 'f')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'AUD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'New Manats', 'AZN', '???')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'BSD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'BBD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rubles', 'BYR', 'p.')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Euro', 'EUR', '&euro;')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'BZD', 'BZ$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'BMD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Bolivianos', 'BOB', '\$b')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Convertible Marka', 'BAM', 'KM')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pula', 'BWP', 'P')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Leva', 'BGN', '??')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Reais', 'BRL', 'R$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'GBP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'BND', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Riels', 'KHR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'CAD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'KYD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pesos', 'CLP', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Yuan Renminbi', 'CNY', '¥')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pesos', 'COP', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Colón', 'CRC', '¢')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Kuna', 'HRK', 'kn')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pesos', 'CUP', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Koruny', 'CZK', 'Kc')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Kroner', 'DKK', 'kr')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pesos', 'DOP ', 'RD$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'XCD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'EGP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Colones', 'SVC', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'FKP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'FJD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Cedis', 'GHC', '¢')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'GIP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Quetzales', 'GTQ', 'Q')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'GGP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'GYD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Lempiras', 'HNL', 'L')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'HKD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Forint', 'HUF', 'Ft')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Kronur', 'ISK', 'kr')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rupees', 'INR', 'Rp')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rupiahs', 'IDR', 'Rp')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rials', 'IRR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'IMP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'New Shekels', 'ILS', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'JMD', 'J$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Yen', 'JPY', '¥')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'JEP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Tenge', 'KZT', '??')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Won', 'KPW', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Won', 'KRW', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Soms', 'KGS', '??')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Kips', 'LAK', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Lati', 'LVL', 'Ls')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'LBP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'LRD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Switzerland Francs', 'CHF', 'CHF')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Litai', 'LTL', 'Lt')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Denars', 'MKD', '???')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Ringgits', 'MYR', 'RM')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rupees', 'MUR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pesos', 'MXN', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Tugriks', 'MNT', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Meticais', 'MZN', 'MT')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'NAD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rupees', 'NPR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Guilders', 'ANG', 'ƒ')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'NZD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Cordobas', 'NIO', 'C$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Nairas', 'NGN', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Krone', 'NOK', 'kr')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rials', 'OMR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rupees', 'PKR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Balboa', 'PAB', 'B/.')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Guarani', 'PYG', 'Gs')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Nuevos Soles', 'PEN', 'S/.')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pesos', 'PHP', 'Php')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Zlotych', 'PLN', 'zl')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rials', 'QAR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'New Lei', 'RON', 'lei')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rubles', 'RUB', '???')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'SHP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Riyals', 'SAR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dinars', 'RSD', '???.')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rupees', 'SCR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'SGD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'SBD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Shillings', 'SOS', 'S')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rand', 'ZAR', 'R')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rupees', 'LKR', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Kronor', 'SEK', 'kr')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'SRD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pounds', 'SYP', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'New Dollars', 'TWD', 'NT$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Baht', 'THB', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'TTD', 'TT$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Lira', 'TRY', 'TL')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Liras', 'TRL', '£')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dollars', 'TVD', '$')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Hryvnia', 'UAH', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Pesos', 'UYU', '\$U')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Sums', 'UZS', '??')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Bolivares Fuertes', 'VEF', 'Bs')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Dong', 'VND', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Rials', 'YER', '?')") or die(mysqli_error($link));
mysqli_query($link, "INSERT INTO $t_food_prices_currencies (currency_id, currency_name, currency_code, currency_symbol) VALUES (NULL, 'Zimbabwe Dollars', 'ZWD', 'Z$')") or die(mysqli_error($link));
	}
	echo"
	<!-- //food_prices_currencies -->
";
?>