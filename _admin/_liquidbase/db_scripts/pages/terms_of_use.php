<?php
/**
*
* File: _admin/_liquidbase/db_scripts/webdesign/terms_of_use.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){

	/*- Tables ---------------------------------------------------------------------------- */


	$t_pages_terms_of_use = $mysqlPrefixSav . "pages_terms_of_use";


	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_pages_terms_of_use") or die(mysqli_error($link)); 



	echo"

	<!-- webdesign_share_buttons -->
	";

	$query = "SELECT * FROM $t_pages_terms_of_use LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_pages_terms_of_use: $row_cnt</p>
		";
		}
		else{
		mysqli_query($link, "CREATE TABLE $t_pages_terms_of_use(
		  terms_of_use_id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(terms_of_use_id), 
		   terms_of_use_title VARCHAR(200), 
		   terms_of_use_language VARCHAR(200), 
		   terms_of_use_text TEXT,
		   terms_of_use_is_active INT,
		   terms_of_use_created_date DATE,
		   terms_of_use_created_date_saying VARCHAR(200), 
		   terms_of_use_created_by_user_id INT,
		   terms_of_use_created_by_user_name VARCHAR(200), 
		   terms_of_use_created_by_user_email VARCHAR(200), 
		   terms_of_use_created_by_name VARCHAR(200), 
		   terms_of_use_updated_date DATE,
		   terms_of_use_updated_date_saying VARCHAR(200), 
		   terms_of_use_updated_by_user_id INT,
		   terms_of_use_updated_by_user_name VARCHAR(200), 
		   terms_of_use_updated_by_user_email VARCHAR(200), 
		   terms_of_use_updated_by_name VARCHAR(200)
		   )")
		   or die(mysqli_error());

		$date = date("Y-m-d");
		$date_saying = date("j F Y");
	
		$server_name = $_SERVER['SERVER_NAME'];
		$email = "$server_name at $server_name";

		// English
		$inp_text="
		<p>These terms and conditions (&quot;Agreement&quot;) set forth the general terms and conditions of your use of the <a href=\"http://$server_name\">$server_name</a> 
		website (&quot;Website&quot;), &quot;$server_name&quot; mobile application (&quot;Mobile Application&quot;) and any of their related products and services (collectively, 
		&quot;Services&quot;). This Agreement is legally binding between you (&quot;User&quot;, &quot;you&quot; or &quot;your&quot;) and this Website operator and 
		Mobile Application developer (&quot;Operator&quot;, &quot;we&quot;, &quot;us&quot; or &quot;our&quot;). By accessing and using the Services, you acknowledge 
		that you have read, understood, and agree to be bound by the terms of this Agreement. If you are entering into this Agreement on behalf of a business or other 
		legal entity, you represent that you have the authority to bind such entity to this Agreement, in which case the terms &quot;User&quot;, &quot;you&quot; or 
		&quot;your&quot; shall refer to such entity. If you do not have such authority, or if you do not agree with the terms of this Agreement, you must not accept this 
		Agreement and may not access and use the Services. You acknowledge that this Agreement is a contract between you and the Operator, even though it is electronic and 
		is not physically signed by you, and it governs your use of the Services.</p>

		<h2>1 Accounts and membership</h2>
<p>You must be at least 16 years of age to use the Services. By using the Services and by agreeing to this Agreement you warrant and represent that you are at least 16 years of age.</p>
<p>If you create an account on the Services, you are responsible for maintaining the security of your account and you are fully responsible for all activities that occur under the account and any other actions taken in connection with it. We may, but have no obligation to, monitor and review new accounts before you may sign in and start using the Services. Providing false contact information of any kind may result in the termination of your account. You must immediately notify us of any unauthorized uses of your account or any other breaches of security. We will not be liable for any acts or omissions by you, including any damages of any kind incurred as a result of such acts or omissions. We may suspend, disable, or delete your account (or any part thereof) if we determine that you have violated any provision of this Agreement or that your conduct or content would tend to damage our reputation and goodwill. If we delete your account for the foregoing reasons, you may not re-register for our Services. We may block your email address and Internet protocol address to prevent further registration.</p>

		<h2>2 User content</h2>
<p>We do not own any data, information or material (collectively, &quot;Content&quot;) that you submit on the Services in the course of using the Service. You shall have sole responsibility for the accuracy, quality, integrity, legality, reliability, appropriateness, and intellectual property ownership or right to use of all submitted Content. We may, but have no obligation to, monitor and review the Content on the Services submitted or created using our Services by you. You grant us permission to access, copy, distribute, store, transmit, reformat, display and perform the Content of your user account solely as required for the purpose of providing the Services to you. Without limiting any of those representations or warranties, we have the right, though not the obligation, to, in our own sole discretion, refuse or remove any Content that, in our reasonable opinion, violates any of our policies or is in any way harmful or objectionable. Unless specifically permitted by you, your use of the Services does not grant us the license to use, reproduce, adapt, modify, publish or distribute the Content created by you or stored in your user account for commercial, marketing or any similar purpose.</p>

		<h2>3 Backups</h2>
<p>We perform regular backups of the Website and its Content, however, these backups are for our own administrative purposes only and are in no way guaranteed. You are responsible for maintaining your own backups of your data. We do not provide any sort of compensation for lost or incomplete data in the event that backups do not function properly. We will do our best to ensure complete and accurate backups, but assume no responsibility for this duty.</p>

		<h2>4 Links to other resources</h2>
<p>Although the Services may link to other resources (such as websites, mobile applications, etc.), we are not, directly or indirectly, implying any approval, association, sponsorship, endorsement, or affiliation with any linked resource, unless specifically stated herein. Some of the links on the Services may be &quot;affiliate links&quot;. This means if you click on the link and purchase an item, the Operator will receive an affiliate commission. We are not responsible for examining or evaluating, and we do not warrant the offerings of, any businesses or individuals or the content of their resources. We do not assume any responsibility or liability for the actions, products, services, and content of any other third parties. You should carefully review the legal statements and other conditions of use of any resource which you access through a link on the Services. Your linking to any other off-site resources is at your own risk.</p>

		<h2>5 Changes and amendments</h2>
<p>We reserve the right to modify this Agreement or its terms relating to the Services at any time, effective upon posting of an updated version of this Agreement on the Services. 
		When we do, we will revise the updated date at the bottom of this page. Continued use of the Services after any such changes shall constitute your consent to such changes. 
		</p>


		<h2>6 Acceptance of these terms</h2>
<p>You acknowledge that you have read this Agreement and agree to all its terms and conditions. By accessing and using the Services you agree to be bound by this Agreement. If you do not agree to abide by the terms of this Agreement, you are not authorized to access or use the Services.</p>

		<h2>7 Contacting us</h2>
<p>If you would like to contact us to understand more about this Agreement or wish to contact us concerning any matter relating to it, you may send an email to $email.</p>

		<p>This document was created on $date_saying.</p>
		";
		$inp_text_mysql = quote_smart($link, $inp_text);

		mysqli_query($link, "INSERT INTO $t_pages_terms_of_use(terms_of_use_id, terms_of_use_title, terms_of_use_language, terms_of_use_text, terms_of_use_is_active, terms_of_use_created_date, terms_of_use_created_date_saying, terms_of_use_created_by_user_id, terms_of_use_created_by_user_name, terms_of_use_created_by_user_email, terms_of_use_created_by_name)
					VALUES 
					(NULL, 'Terms of Use', 'en', $inp_text_mysql, '1', '$date', '$date_saying', '1', 'Admin', '', 'Admin')
					") or die(mysqli_error());

		// Norwegian
		mysqli_query($link, "INSERT INTO $t_pages_terms_of_use(terms_of_use_id, terms_of_use_title, terms_of_use_language, terms_of_use_text, terms_of_use_is_active, terms_of_use_created_date, terms_of_use_created_date_saying, terms_of_use_created_by_user_id, terms_of_use_created_by_user_name, terms_of_use_created_by_user_email, terms_of_use_created_by_name)
					VALUES 
					(NULL, 'Vilkår for bruk', 'no', $inp_text_mysql, '1', '$date', '$date_saying', '1', 'Admin', '', 'Admin')
					") or die(mysqli_error());
	}
	echo"
	<!-- //webdesign_share_buttons -->
	";
} // access
?>