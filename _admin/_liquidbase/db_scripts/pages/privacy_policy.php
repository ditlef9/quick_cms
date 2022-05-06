<?php
/**
*
* File: _admin/_liquidbase/db_scripts/webdesign/privacy_policy.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){

	/*- Tables ---------------------------------------------------------------------------- */


	$t_pages_privacy_policy = $mysqlPrefixSav . "pages_privacy_policy";


	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_pages_privacy_policy") or die(mysqli_error($link)); 



	echo"

	<!-- webdesign_share_buttons -->
	";

	$query = "SELECT * FROM $t_pages_privacy_policy LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_pages_privacy_policy: $row_cnt</p>
		";
		}
		else{

		mysqli_query($link, "CREATE TABLE $t_pages_privacy_policy(
		  privacy_policy_id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(privacy_policy_id), 
		   privacy_policy_title VARCHAR(200), 
		   privacy_policy_language VARCHAR(200), 
		   privacy_policy_text TEXT,
		   privacy_policy_is_active INT,
		   privacy_policy_created_date DATE,
		   privacy_policy_created_date_saying VARCHAR(200), 
		   privacy_policy_created_by_user_id INT,
		   privacy_policy_created_by_user_name VARCHAR(200), 
		   privacy_policy_created_by_user_email VARCHAR(200), 
		   privacy_policy_created_by_name VARCHAR(200), 
		   privacy_policy_updated_date DATE,
		   privacy_policy_updated_date_saying VARCHAR(200), 
		   privacy_policy_updated_by_user_id INT,
		   privacy_policy_updated_by_user_name VARCHAR(200), 
		   privacy_policy_updated_by_user_email VARCHAR(200), 
		   privacy_policy_updated_by_name VARCHAR(200)
		   )")
		   or die(mysqli_error());

		$date = date("Y-m-d");
		$date_saying = date("j F Y");

		$server_name = $_SERVER['SERVER_NAME'];
		$email = "$server_name at $server_name";

		// English text
$inp_text = "<p>In order to receive information about your Personal Data, the purposes and the parties the Data is shared with, contact the Owner.</p>

<h2 id=\"owner_of_the_data\">1. Owner and Data Controller</h2>
	<p>$server_name</p>
	<p><strong>Owner contact email:</strong> $email</p>

	<hr />

<h2 id=\"types_of_data\">2. Types of Data collected</h2>

	<p>The owner does not provide a list of Personal Data types collected.</p>

	<p>Complete details on each type of Personal Data collected are provided in the dedicated sections of this privacy policy 
or by specific explanation texts displayed prior to the Data collection.<br />
Personal Data may be freely provided by the User, or, in case of Usage Data, collected automatically when using this Application.</p>

	<p>Unless specified otherwise, all Data requested by this Application is mandatory and failure to provide this Data may make it impossible for this Application to provide its services. In cases where this Application specifically states that some Data is not mandatory, Users are free not to communicate this Data without consequences to the availability or the functioning of the Service.</p>\r\n<p>Users who are uncertain about which Personal Data is mandatory are welcome to contact the Owner.<br />Any use of Cookies &ndash; or of other tracking tools &ndash; by this Application or by the owners of third-party services used by this Application serves the purpose of providing the Service required by the User, in addition to any other purposes described in the present document and in the Cookie Policy, if available.</p>\r\n<p>Users are responsible for any third-party Personal Data obtained, published or shared through this Application and confirm that they have the third party\'s consent to provide the Data to the Owner.</p>

	<hr />

<h2 id=\"place_of_processing\">3. Mode and place of processing the Data</h2>
	<h3>Methods of processing</h3>

		<p>The Owner takes appropriate security measures to prevent unauthorized access, disclosure, modification, 
		or unauthorized destruction of the Data.<br />
		The Data processing is carried out using computers and/or IT enabled tools, 
		following organizational procedures and modes strictly related to the purposes indicated. 
		In addition to the Owner, in some cases, the Data may be accessible to certain types of persons in charge, 
		involved with the operation of this Application (administration, sales, marketing, legal, system administration) 
		or external parties (such as third-party technical service providers, mail carriers, hosting providers, IT companies, 
		communications agencies) appointed, if necessary, as Data Processors by the Owner. The updated list of these parties may be requested from the Owner at any time.</p>

	<h3>Legal basis of processing</h3>
		<p>The Owner may process Personal Data relating to Users if one of the following applies:</p>

		<ul>
			<li><p>Users have given their consent for one or more specific purposes. 
			Note: Under some legislations the Owner may be allowed to process Personal Data until the User objects to such processing (&ldquo;opt-out&rdquo;), 
			without having to rely on consent or any other of the following legal bases. This, however, does not apply, whenever the processing of Personal 
			Data is subject to European data protection law;</p></li>

			<li><p>provision of Data is necessary for the performance of an agreement with the User and/or for any pre-contractual obligations thereof;</p></li>

			<li><p>processing is necessary for compliance with a legal obligation to which the Owner is subject;</p></li>

			<li><p>processing is related to a task that is carried out in the public interest or in the exercise of official authority vested in the Owner;</p></li>

			<li><p>processing is necessary for the purposes of the legitimate interests pursued by the Owner or by a third party.</p></li>
		</ul>

		<p>In any case, the Owner will gladly help to clarify the specific legal basis that applies to the processing, 
		and in particular whether the provision of Personal Data is a statutory or contractual requirement, or a requirement necessary to enter into a contract.</p>

	<h3>Place</h3>

		<p>The Data is processed at the Owner\'s operating offices and in any other places where the parties involved in the processing are located.<br /><br />
		Depending on the User\'s location, data transfers may involve transferring the User\'s Data to a country other than their own. 
		To find out more about the place of processing of such transferred Data, Users can check the section containing details about the processing of Personal Data.
		</p>

		<p>Users are also entitled to learn about the legal basis of Data transfers to a country outside the European Union or to any international organization governed 
		by public international law or set up by two or more countries, such as the UN, and about the security measures taken by the Owner to safeguard their Data.<br /><br />
		
		If any such transfer takes place, Users can find out more by checking the relevant sections of this document or inquire with the Owner using the information provided 
		in the contact section.</p>

	<h3>Retention time</h3>
		<p>Personal Data shall be processed and stored for as long as required by the purpose they have been collected for.</p>

		<p>Therefore:</p>

		<ul>
			<li><p>Personal Data collected for purposes related to the performance of a contract between the Owner and the User 
			shall be retained until such contract has been fully performed.</p></li>

			<li><p>Personal Data collected for the purposes of the Owner&rsquo;s legitimate interests shall be retained as long 
			as needed to fulfill such purposes. Users may find specific information regarding the legitimate interests pursued by 
			the Owner within the relevant sections of this document or by contacting the Owner.</p></li>
		</ul>


		<p>The Owner may be allowed to retain Personal Data for a longer period whenever the User has given consent to such processing, 
		as long as such consent is not withdrawn. Furthermore, the Owner may be obliged to retain Personal Data for a longer period whenever required 
		to do so for the performance of a legal obligation or upon order of an authority.<br /><br />Once the retention period expires, 
		Personal Data shall be deleted. Therefore, the right to access, the right to erasure, the right to rectification and the right to 
		data portability cannot be enforced after expiration of the retention period.</p>

	<hr />

<h2 id=\"rights_subjects\">4. The rights of Users</h2>

	<p>Users may exercise certain rights regarding their Data processed by the Owner.</p>

	<p>In particular, Users have the right to do the following:</p>

	<ul>

		<li><p><strong>Withdraw their consent at any time.</strong> Users have the right to withdraw consent where they have previously given their 
		consent to the processing of their Personal Data.</p></li>

		<li><p><strong>Object to processing of their Data.</strong> Users have the right to object to the processing of their Data if 
		the processing is carried out on a legal basis other than consent. Further details are provided in the dedicated section below.</p></li>

		<li><p><strong>Access their Data.</strong> Users have the right to learn if Data is being processed by the Owner, obtain disclosure regarding 
		certain aspects of the processing and obtain a copy of the Data undergoing processing.</p></li>

		<li><p><strong>Verify and seek rectification.</strong> Users have the right to verify the accuracy of their Data and ask for it to be updated or corrected.</p></li>

		<li><p><strong>Restrict the processing of their Data.</strong> Users have the right, under certain circumstances, to restrict the processing of their Data. 
		In this case, the Owner will not process their Data for any purpose other than storing it.</p></li>

		<li><p><strong>Have their Personal Data deleted or otherwise removed.</strong> Users have the right, under certain circumstances, 
		to obtain the erasure of their Data from the Owner.</p></li>

		<li><p><strong>Receive their Data and have it transferred to another controller.</strong> Users have the right to receive their Data in a structured, 
		commonly used and machine readable format and, if technically feasible, to have it transmitted to another controller without any hindrance. 
		This provision is applicable provided that the Data is processed by automated means and that the processing is based on the User\'s consent, on a 
		contract which the User is part of or on pre-contractual obligations thereof.</p></li>

		<li><p><strong>Lodge a complaint.</strong> Users have the right to bring a claim before their competent data protection authority.</p></li>
	</ul>

	<h3>Details about the right to object to processing</h3>

		<p>Where Personal Data is processed for a public interest, in the exercise of an official authority vested in the Owner or for the purposes of the 
		legitimate interests pursued by the Owner, Users may object to such processing by providing a ground related to their particular situation to justify the objection.</p>

		<p>Users must know that, however, should their Personal Data be processed for direct marketing purposes, they can object to that processing at any time without 
		providing any justification. To learn, whether the Owner is processing Personal Data for direct marketing purposes, Users may refer to the relevant sections of 
		this document.</p>

	<h3>How to exercise these rights</h3>

		<p>Any requests to exercise User rights can be directed to the Owner through the contact details provided in this document. 
		These requests can be exercised free of charge and will be addressed by the Owner as early as possible and always within one month.</p>

	<hr />

<h2 id=\"further_data_processing_info\">5. Additional information about Data collection and processing</h2>

	<h3>Legal action</h3>
		<p>The User\'s Personal Data may be used for legal purposes by the Owner in Court or in the stages leading to possible legal action 
		arising from improper use of this Application or the related Services.<br />The User declares to be aware that the Owner may be required to 
		reveal personal data upon request of public authorities.</p>

	<h3>Additional information about User\'s Personal Data</h3>

		<p>In addition to the information contained in this privacy policy, this Application may provide the User 
		with additional and contextual information concerning particular Services or the collection and processing of Personal Data upon request.</p>

	<h3>System logs and maintenance</h3>
		<p>For operation and maintenance purposes, this Application and any third-party services may collect files that record interaction with this 
		Application (System logs) use other Personal Data (such as the IP Address) for this purpose.</p>

	<h3>Information not contained in this policy</h3>
		<p>More details concerning the collection or processing of Personal Data may be requested from the Owner at any time. 
		Please see the contact information at the beginning of this document.</p>

	<h3>How &ldquo;Do Not Track&rdquo; requests are handled</h3>

		<p>This Application does not support &ldquo;Do Not Track&rdquo; requests.<br />
		To determine whether any of the third-party services it uses honor the &ldquo;Do Not Track&rdquo; requests, please read their privacy policies.</p>

	<h3>Changes to this privacy policy</h3>
		<p>The Owner reserves the right to make changes to this privacy policy at any time by notifying its Users on this page and possibly within this Application and/or - 
		as far as technically and legally feasible - sending a notice to Users via any contact information available to the Owner. It is strongly recommended to check this 
		page often, referring to the date of the last modification listed at the bottom. <br /><br />
	
		Should the changes affect processing activities performed on the basis of the User&rsquo;s consent, the Owner shall collect new consent from the User, where required.</p>


	<hr />

<h2 id=\"definitions_and_legal_references\">6. Definitions and legal references</h3>
	<h3>Personal Data (or Data)</h3>

		<p>Any information that directly, indirectly, or in connection with other information &mdash; including a personal identification number &mdash; 
		allows for the identification or identifiability of a natural person.</p>\r\n<h4>Usage Data</h4>\r\n<p>Information collected automatically through this 
		Application (or third-party services employed in this Application), which can include: the IP addresses or domain names of the computers utilized by the 
		Users who use this Application, the URI addresses (Uniform Resource Identifier), the time of the request, the method utilized to submit the request 
		to the server, the size of the file received in response, the numerical code indicating the status of the server\'s answer (successful outcome, error, etc.), 
		the country of origin, the features of the browser and the operating system utilized by the User, the various time details per visit (e.g., the time spent on 
		each page within the Application) and the details about the path followed within the Application with special reference to the sequence of pages visited, 
		and other parameters about the device operating system and/or the User\'s IT environment.</p>

	<h3>User</h3>
		<p>The individual using this Application who, unless otherwise specified, coincides with the Data Subject.</p>

	<h3>Data Subject</h3>
		<p>The natural person to whom the Personal Data refers.</p>

	<h3>Data Processor (or Data Supervisor)</h3>
		<p>The natural or legal person, public authority, agency or other body which processes Personal Data on behalf of the Controller, as described in this privacy policy.</p>

	<h3>Data Controller (or Owner)</h3>
		<p>The natural or legal person, public authority, agency or other body which, alone or jointly with others, determines the purposes and means of the processing of 
		Personal Data, including the security measures concerning the operation and use of this Application. The Data Controller, unless otherwise specified, is the Owner 
		of this Application.</p>

	<h3>This Application</h3>
		<p>The means by which the Personal Data of the User is collected and processed.</p>

	<h3>Service</h3>
		<p>The service provided by this Application as described in the relative terms (if available) and on this site/application.</p>

	<h3>European Union (or EU)</h3>
		<p>Unless otherwise specified, all references made within this document to the European Union include all current member states to the 
		European Union and the European Economic Area.</p>

		<hr />

<h2>7. Legal information</h2>

		<p>This privacy statement has been prepared based on provisions of multiple legislations, including Art. 13/14 of Regulation (EU) 2016/679 
		(General Data Protection Regulation).</p>

		<p>This privacy policy relates solely to this Application, if not stated otherwise within this document.";

	$inp_text_mysql = quote_smart($link, $inp_text);

		mysqli_query($link, "INSERT INTO $t_pages_privacy_policy (privacy_policy_id, privacy_policy_title, privacy_policy_language, privacy_policy_text, privacy_policy_is_active, 
					privacy_policy_created_date, privacy_policy_created_date_saying, privacy_policy_created_by_user_id, privacy_policy_created_by_user_name, privacy_policy_created_by_user_email, 
					privacy_policy_created_by_name)
					VALUES 
					(NULL, 'Privacy Policy', 'en', $inp_text_mysql, '1',
					'$date', '$date_saying', '1', 'Admin', '', 
					'Admin')
					") or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_pages_privacy_policy (privacy_policy_id, privacy_policy_title, privacy_policy_language, privacy_policy_text, privacy_policy_is_active, 
					privacy_policy_created_date, privacy_policy_created_date_saying, privacy_policy_created_by_user_id, privacy_policy_created_by_user_name, privacy_policy_created_by_user_email, 
					privacy_policy_created_by_name)
					VALUES 
					(NULL, 'Personvernregler', 'no', '<a href=\"\index.php?doc=privacy_policy&amp;l=en\">Privacy polcy</a>', '1',
					'$date', '$date_saying', '1', 'Admin', '', 
					'Admin')
					") or die(mysqli_error());
	}
	echo"
	<!-- //webdesign_share_buttons -->
	";
} // access
?>