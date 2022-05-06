<?php
/**
*
* File: _admin/_liquidbase/db_scripts/webdesign/cookies_policy.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){

	/*- Tables ---------------------------------------------------------------------------- */


	$t_pages_cookies_policy = $mysqlPrefixSav . "pages_cookies_policy";


	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_pages_cookies_policy") or die(mysqli_error($link)); 



	echo"

	<!-- webdesign_share_buttons -->
	";

	$query = "SELECT * FROM $t_pages_cookies_policy LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_pages_cookies_policy: $row_cnt</p>
		";
		}
		else{

		mysqli_query($link, "CREATE TABLE $t_pages_cookies_policy(
		  cookies_policy_id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(cookies_policy_id), 
		   cookies_policy_title VARCHAR(200), 
		   cookies_policy_language VARCHAR(200), 
		   cookies_policy_text TEXT,
		   cookies_policy_is_active INT,
		   cookies_policy_created_date DATE,
		   cookies_policy_created_date_saying VARCHAR(200), 
		   cookies_policy_created_by_user_id INT,
		   cookies_policy_created_by_user_name VARCHAR(200), 
		   cookies_policy_created_by_user_email VARCHAR(200), 
		   cookies_policy_created_by_name VARCHAR(200), 
		   cookies_policy_updated_date DATE,
		   cookies_policy_updated_date_saying VARCHAR(200), 
		   cookies_policy_updated_by_user_id INT,
		   cookies_policy_updated_by_user_name VARCHAR(200), 
		   cookies_policy_updated_by_user_email VARCHAR(200), 
		   cookies_policy_updated_by_name VARCHAR(200)
		   )")
		   or die(mysqli_error());

		$date = date("Y-m-d");
		$date_saying = date("j F Y");

		// English
		$inp_text="
		<p>This website uses cookies. Cookies are small text files which are transferred to your computer or mobile when you visit a website or app.</p>

		<p>We use them to:</p>
		
		<ul>
			<li><p>Remember information about you, so you don’t have to give it to us again.</p></li>

			<li><p>Keep you signed in, even on different devices.</p></li>

		</ul>


		<h2>Strictly necessary cookies</h2>

			<p>
			These cookies let you use all the different parts of this website. 
			Without them, services that you've asked for can't be provided. 
			Also, we collect data from you to help us understand how people are using the website online, so we can make it better.
			</p>

			<p>
			Some examples of how we use these cookies are:
			</p>

			<ul>
				<li><p>When you sign in to this website.</p></li>
   				<li><p>Remembering security settings that affect access to certain content, for example, any parental controls.</p></li>
				<li><p>Collecting information on which web pages visitors go to most often so we can improve our online services.</p></li>
			</ul>



		<h2>Other documents</h2>

			<p>
			<a href=\"index.php?doc=privacy_policy&amp;l=en\" class=\"btn_default\">Privacy policy</a>
			<a href=\"index.php?doc=terms_of_use&amp;l=en\" class=\"btn_default\">Terms of use</a>
			</p>
		";
		$inp_text_mysql = quote_smart($link, $inp_text);

		mysqli_query($link, "INSERT INTO $t_pages_cookies_policy(cookies_policy_id, cookies_policy_title, cookies_policy_language, cookies_policy_text, cookies_policy_is_active, 
					cookies_policy_created_date, cookies_policy_created_date_saying, cookies_policy_created_by_user_id, cookies_policy_created_by_user_name, cookies_policy_created_by_user_email, 
					cookies_policy_created_by_name)
					VALUES 
					(NULL, 'Cookies Policy', 'en', $inp_text_mysql, '1', '$date', '$date_saying', '1', 'Admin', '', 'Admin')
					") or die(mysqli_error());


		// Norwegian
		$inp_text="<p>
		Dette nettstedet bruker informasjonskapsler. Informasjonskapsler er små tekstfiler som overføres til datamaskinen eller mobilen din når du besøker et nettsted eller en app.
		</p>

		<p>Vi bruker dem til å:</p>

		<ul>
			<i><p>Husk informasjon om deg, slik at du ikke trenger å gi den til oss igjen.</p></li>

			<li><p>Hold deg pålogget, selv på forskjellige enheter</p></li>
		</ul>

		<h2>Strengt nødvendige informasjonskapsler</h2>

			<p>
			Disse informasjonskapslene lar deg bruke alle de forskjellige delene av dette nettstedet. Uten dem kan du ikke tilby tjenester du har bedt om. Vi samler også inn data fra deg for å hjelpe oss å forstå hvordan folk bruker nettstedet på nettet, slik at vi kan gjøre det bedre.
			</p>

			<p>
			Noen eksempler på hvordan vi bruker disse informasjonskapslene er:
			</p>

			<ul>
				<li><p>Når du logger på dette nettstedet</p></li>

				<li><p>Husker sikkerhetsinnstillinger som påvirker tilgang til bestemt innhold, for eksempel foreldrekontroll</p></li>

				<li><p>Samle informasjon om hvilke websider besøkende besøker oftest, slik at vi kan forbedre våre online tjenester</p></li>
			</ul>

		<h2>Andre dokumenter</h2>

			<p>
			<a href=\"index.php?doc=privacy_policy&amp;l=no\" class=\"btn_default\">Personvernregler</a>
			<a href=\"index.php?doc=terms_of_use&amp;l=no\" class=\"btn_default\">Vilkår for bruk</a>
			</p>

		";
		$inp_text_mysql = quote_smart($link, $inp_text);

		mysqli_query($link, "INSERT INTO $t_pages_cookies_policy(cookies_policy_id, cookies_policy_title, cookies_policy_language, cookies_policy_text, cookies_policy_is_active, 
					cookies_policy_created_date, cookies_policy_created_date_saying, cookies_policy_created_by_user_id, cookies_policy_created_by_user_name, cookies_policy_created_by_user_email, 
					cookies_policy_created_by_name)
					VALUES 
					(NULL, 'Retningslinjer for informasjonskapsler', 'no', $inp_text_mysql, '1', '$date', '$date_saying', '1', 'Admin', '', 'Admin')
					") or die(mysqli_error());
	}
	echo"
	<!-- //webdesign_share_buttons -->
	";
} // access
?>