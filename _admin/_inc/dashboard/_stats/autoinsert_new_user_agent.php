<?php
/**
*
* File: _admin/_functions/autoinsert_new_user_agent.php
* Version 4
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Find me based on user ------------------------------------------------------------------- */
if(isset($my_user_agent) && $get_stats_user_agent_id == ""){

	if($test == "1"){
		echo"<span><hr />\nStarting registrer_stats_autoinsert_new_user_agent.php<br />
		My user agent = $my_user_agent<br /></span>\n";
	}

	// Visitor type
	$visitor_type = "";

	// A B C D E F G H I J K L M N O P Q R S T U V W X Y Z � � �
	$robots = array(
		'Apache-HttpClient', 'archive.org_bot', 'AhrefsBot', 'AlphaBot', 'aboundex', 'altavista', 'appengine-google',
		'Baiduspider', 'browsershot', 'botje.com', 'bing', 'bingbot', 
		'CheckMarkNetwork', 'Cliqzbot', 'curl', 'CensysInspect',
		'Dataprovider.com', 'discobot', 'Dispatch', 'edisterbot', 'DuckDuckGo-Favicons-Bot', 'dotbot', 
		'exabot', 'ExtLinksBot', 'evc-batch', 'Embarcadero',
		'facebook', 
		'gigabot', 'G-i-g-a-b-o-t', 'Grammarly', 'googlebot-mobile', 'Googlebot-Image', 'gigabot', 'gidbot', 'googlebot', 'mediapartners-google', 'google-site-verification', 'googlebot-image', 
		'Go-http-client', 'GuzzleHttp', 'Google-Youtube-Links', 
		'ia_archiver', 'ics', 
		'jyxobot',  'lycos', 'J2ME/MIDP',
		'HTTrack', 'Hstpnetwork.com', 
		'linkdexbot', 'iubenda-radar', 'libwww-FM',
		'MJ12bot', 'mail.ru_bot', 'meanpathbo', 'mlbot', 'msnbot', 'MSIECrawler',
		'node-fetch', 
		 'openbot', 'one.com-cms-scanner',
		'Qwantify', 
		'PageThing', 'panscient.com', 'PetalBot', 'proximic', 'PocketParser', 'python-requests', 'Python-urllib', 'Python', 
		'Screaming Frog SEO Spider', 'SeznamBot', 'serpstatbot', 'semrushbot', 'SEOkicks-Robot', 'SMTBot','slurp', 'scooter', 'SiteExplorer', 'sqlmap',
		'startsite.com', 'synapse', 'Snapchat', 'Sogou web spider', 'spbot', 'Scrapy', 
		'teoma', 'openacoon', 'twitter', 'temnos', 
		'unikstart', 'Uptimebot',
		'vbseo', 
		'Yandexnot', 'yammybot', 'YandexMobileBot', 'YandexBot', 'yahoo', 
		'zgrab',
		'QuickCMS', 
		'Wappalyzer', 'w3c_validator', 'windows-live', 'WhatsApp', 'WordPress');


	foreach($robots as $r){
		$r_position = stripos($my_user_agent, $r);
 		if($r_position !== false ){

			// Visitor type
			$visitor_type = "bot";
			if($test == "1"){
				echo"<span>Visitor type = bot<br /></span>\n";
			}


			// URL
			$inp_stats_user_agent_url = "http://" . $r . ".com";
			$inp_stats_user_agent_url = output_html($inp_stats_user_agent_url);

			// Bot version
			$inp_stats_user_agent_bot_version = get_between($my_user_agent, $r, ';');
			if($inp_stats_user_agent_bot_version == ""){
				$inp_stats_user_agent_bot_version = get_between($my_user_agent, ucfirst($r), ';');
			}
			$inp_stats_user_agent_bot_version = str_replace("/", "", $inp_stats_user_agent_bot_version);
			$inp_stats_user_agent_bot_version = output_html($inp_stats_user_agent_bot_version);
			if($inp_stats_user_agent_bot_version > 10){
				$inp_stats_user_agent_bot_version = substr($inp_stats_user_agent_bot_version, 0, 10);
			}
			//echo"Agent: $my_user_agent<br />get_between($my_user_agent, $r, ';')<br />Bot ver: $inp_stats_user_agent_bot_version";




			// Agent Name
			$inp_stats_user_agent_bot = ucfirst($r);
			$inp_stats_user_agent_bot = output_html($inp_stats_user_agent_bot);

			// Icon
			$inp_stats_user_agent_bot_icon = $r . ".png";
			$inp_stats_user_agent_bot_icon = output_html($inp_stats_user_agent_bot_icon);
			
			// Insert new bot
			if($test == "1"){
				echo"
				<h2>Autoinsert new user agent: Bot</h2>
				<p><b>Visitor type:</b> $visitor_type<br />
				<b>Bot:</b> $inp_stats_user_agent_bot<br />
				<b>Bot version:</b> $inp_stats_user_agent_bot_version<br />
				<b>URL:</b> <a href=\"$inp_stats_user_agent_url\">$inp_stats_user_agent_url</a><br />
				<b>Icon:</b> $inp_stats_user_agent_bot_icon
				</p>
				<pre>INSERT INTO $t_stats_user_agents_index
				(stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, 
				stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, 
				stats_user_agent_bot_version, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned) 
				VALUES
				(NULL, $my_user_agent_mysql, 'bot', '', '', 
				'', '', '', '', $inp_stats_user_agent_bot_mysql, 
				$inp_stats_user_agent_bot_version_mysql, $inp_stats_user_agent_bot_icon_mysql, $inp_stats_user_agent_url_mysql, '0')</pre>
				";
			}
			$inp_user_agent_type = "bot";
			$blank = "";
			$zero = 0;
			$stmt = $mysqli->prepare("INSERT INTO $t_stats_user_agents_index
				(stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, 
				stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, 
				stats_user_agent_bot_version, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned) 
				VALUES 
				(NULL,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?)");
			$stmt->bind_param("sssssssssssss", $my_user_agent, $inp_user_agent_type, $blank, $blank, 
				$blank, $blank, $blank, $blank, $inp_stats_user_agent_bot, 
				$inp_stats_user_agent_bot_version, $inp_stats_user_agent_bot_icon, $inp_stats_user_agent_url, $zero); 
			$stmt->execute();


			break;
		}
	}


	// Mobile
	if($visitor_type == ""){
		$mobile_os = array('Android', 'Blackberry', 'iPhone', 'iPad', 'Nokia', 'Samsung');
		
		foreach($mobile_os as $m_os){

			$m_os_position = stripos($my_user_agent, $m_os);
 			if($m_os_position !== false ){

				// Visitor type
				$visitor_type = "mobile";
				if($test == "1"){
					echo"<span>Visitor type = mobile<br /></span>\n";
				}


				// Browser checkup
				// A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
				$mobile_browsers = array('AppleWebKit', 'Dalvik', 'Mobile Safari', 'Minefield', 'Safari', 'Chrome', 'Firefox', 'Opera', 'OPR', 
							'SamsungBrowser', 'UCBrowser');
				$inp_stats_user_agent_browser = "";
				foreach($mobile_browsers as $m_b){
 					if(stripos($my_user_agent, $m_b) !== false ){
						$inp_stats_user_agent_browser = "$m_b";
					}
				}

				$inp_stats_user_agent_browser_icon = clean($inp_stats_user_agent_browser);
				$inp_stats_user_agent_browser_icon = $inp_stats_user_agent_browser_icon . ".png";
				$inp_stats_user_agent_browser_icon = output_html($inp_stats_user_agent_browser_icon);

				// Browser version
				$inp_stats_user_agent_browser_version = 0;
				if($inp_stats_user_agent_browser != ""){
					$inp_stats_user_agent_browser_version = substr($my_user_agent, strrpos($my_user_agent, '/') + 1);
					$inp_stats_user_agent_browser_version_len = strlen($inp_stats_user_agent_browser_version);
					if($inp_stats_user_agent_browser_version_len > 10){
						$inp_stats_user_agent_browser_version = substr($inp_stats_user_agent_browser_version, 0, 10);
					}
				}
				//echo"Agent: $my_user_agent<br />Browser ver: $inp_stats_user_agent_browser_version ";
				$inp_stats_user_agent_browser_version = output_html($inp_stats_user_agent_browser_version);


				// Browser
				$inp_stats_user_agent_browser = output_html(ucfirst($inp_stats_user_agent_browser));


				// OS 
				$inp_stats_user_agent_os = ucfirst($m_os);
				$inp_stats_user_agent_os = output_html($inp_stats_user_agent_os);
				$inp_stats_user_agent_os = str_replace("IPhone", "iPhone", $inp_stats_user_agent_os);
				$inp_stats_user_agent_os = str_replace("IPad", "iPad", $inp_stats_user_agent_os);

				// OS Icon
				$inp_stats_user_agent_os_icon = clean($m_os);
				$inp_stats_user_agent_os_icon =  $inp_stats_user_agent_os_icon. ".png";
				$inp_stats_user_agent_os_icon = output_html($inp_stats_user_agent_os_icon);

				// OS Version
				// my_user_agent = Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:87.0) Gecko/20100101 Firefox/87.0
				// os = Windows NT
				// os_position = 13
				$inp_stats_user_agent_os_version = get_between($my_user_agent, $m_os, ';');
				$inp_stats_user_agent_os_version = str_replace(" ", "", $inp_stats_user_agent_os_version);
				$inp_stats_user_agent_os_version = output_html($inp_stats_user_agent_os_version);
				if($inp_stats_user_agent_os_version == ""){
					// my_user_agent = Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/173.0.391310503 Mobile/15E148 Safari/604.1
					// os = CPU iPhone OS
					// os_position = 14_4
					$agent = str_replace("_", ".", $my_user_agent);
					$split_agent_on_space = explode(" ", $agent);
					$split_agent_on_space_size = sizeof($split_agent_on_space);

					$found_os_look_for_version = 0;

					for($x=0;$x<$split_agent_on_space_size;$x++){

						// First look for OS name, then version
						$word = $split_agent_on_space[$x];
						$word = str_replace("(", "", $word);
						$word = str_replace(";", "", $word);
						$word = str_replace(")", "", $word);
						// echo"$word<br />";
						if($word == "$inp_stats_user_agent_os"){
							// echo"FOUND OS $word<br />";
							$found_os_look_for_version = 1;
						}

						// Look for version
						if($found_os_look_for_version == "1" && is_numeric($word)){
							// echo"FOUND VERSION $word<br />";
							$inp_stats_user_agent_os_version = "$word";
							$inp_stats_user_agent_os_version = output_html($inp_stats_user_agent_os_version);
							break;
						}
					}
				}
				if($inp_stats_user_agent_os_version == ""){
					$inp_stats_user_agent_os_version = "N/A";
				}
				
				// OS :: Look for ) Example 12)AppleWebKit/537.36(KHTML,likeGecko)Chrome/99.0.4844.88MobileSafari/537.36
 				if (strpos($inp_stats_user_agent_os_version, ')') !== false) {
					$os_version_explode = explode(")", $inp_stats_user_agent_os_version);
					$inp_stats_user_agent_os_version = $os_version_explode[0];
				}

				$inp_stats_user_agent_os_version_len = strlen($inp_stats_user_agent_os_version);
				if($inp_stats_user_agent_os_version_len > 10){
					$inp_stats_user_agent_os_version = substr($inp_stats_user_agent_os_version, 0, 10);
				}
				if($inp_stats_user_agent_os_version == ""){
					$inp_stats_user_agent_os_version = "N/A";
				}
				// echo"Agent: $my_user_agent<br />OS: $inp_stats_user_agent_os <br />OS ver: $inp_stats_user_agent_os_version ";


				// Insert new mobile
				if($test == "1"){
					echo"<p><hr /></p>
					<h2>Autoinsert new user agent: Mobile</h2>
						
					<p><b>User agent:</b> $my_user_agent<br />
					<b>Visitor type:</b> $visitor_type<br />

					<b>Browser:</b> $inp_stats_user_agent_browser<br />
					<b>Browser version:</b> $inp_stats_user_agent_browser_version<br />
					<b>Browser icon:</b> $inp_stats_user_agent_browser_icon<br />

					<b>OS:</b> $inp_stats_user_agent_os<br />
					<b>OS version:</b> $inp_stats_user_agent_os_version<br />
					<b>OS version strlen:</b> $inp_stats_user_agent_os_version_len<br />
					<b>OS icon:</b> $inp_stats_user_agent_os_icon<br />
					</p>

					<pre>INSERT INTO $t_stats_user_agents_index
					(stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, 
					stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, 
					stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned) 
					VALUES
					(NULL, $my_user_agent_mysql, 'mobile', $inp_stats_user_agent_browser_mysql, $inp_stats_user_agent_browser_version_mysql,
					'', $inp_stats_user_agent_os_mysql, $inp_stats_user_agent_os_version_mysql, $inp_stats_user_agent_browser_icon_mysql, '',
					'', '', '0')</pre>
					";
				}
				$inp_user_agent_type = "mobile";
				$blank = "";
				$zero = 0;
				$stmt = $mysqli->prepare("INSERT INTO $t_stats_user_agents_index
					(stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, 
					stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, 
					stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned) 
					VALUES 
					(NULL,?,?,?,?,
					?,?,?,?,?,
					?,?,?)");
				$stmt->bind_param("ssssssssssss", $my_user_agent, $inp_user_agent_type, $inp_stats_user_agent_browser, $inp_stats_user_agent_browser_version,
					$blank, $inp_stats_user_agent_os, $inp_stats_user_agent_os_version, $inp_stats_user_agent_browser_icon, $blank,
					$blank, $blank, $zero); 
				$stmt->execute();


				break;
			}
		}
	}

	


	// Desktop
	if($visitor_type == ""){


		// A B C D E F G H I J K L M N O P Q R S T U V W X Y Z � � �
		$desktop_os = array('CrOS', 'Freebsd', 'Fedora', 'Linux x86.64', 'Linux x86_64', 'Linux i686', 'linux-gnu', 'Mac OS X', 'SunOS', 'Windows NT', 'Ubuntu', 'X11');
		foreach($desktop_os as $os){
			$os_position = stripos($my_user_agent, $os);
 			if($os_position !== false ){
				
				// Visitor type
				$visitor_type = "desktop";
				if($test == "1"){
					echo"<span>Visitor type = desktop<br /></span>\n";
				}

				// OS
				$inp_stats_user_agent_os = ucfirst($os);
				$inp_stats_user_agent_os = output_html($inp_stats_user_agent_os);

				// OS Icon
				$inp_stats_user_agent_os_icon = clean($os);
				$inp_stats_user_agent_os_icon = $inp_stats_user_agent_os_icon . ".png";
				$inp_stats_user_agent_os_icon = output_html($inp_stats_user_agent_os_icon);

				// OS Version
				// my_user_agent = Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:87.0) Gecko/20100101 Firefox/87.0
				// os = Windows NT
				// os_position = 13
				$inp_stats_user_agent_os_version = get_between($my_user_agent, $os, ';');
				$inp_stats_user_agent_os_version = str_replace(" ", "", $inp_stats_user_agent_os_version);
				$inp_stats_user_agent_os_version = output_html($inp_stats_user_agent_os_version);
				$inp_stats_user_agent_os_version = trim($inp_stats_user_agent_os_version);

				// Check for ) in OS version
				if (strpos($inp_stats_user_agent_os_version, ')') !== false) {
					$os_version_explode = explode(")", $inp_stats_user_agent_os_version);
					$inp_stats_user_agent_os_version = $os_version_explode[0];
				}
				if($inp_stats_user_agent_os_version > 10){
					$inp_stats_user_agent_os_version = substr($inp_stats_user_agent_os_version, 0, 10);
				}
				if($inp_stats_user_agent_os_version == ""){
					$inp_stats_user_agent_os_version = "N/A";
				}
				
				

				// Browser checkup (desktop)
				$desktop_browsers = array('AppleWebKit', 'Safari', 'Chrome', 'Edge', 'Edg', 'Firefox', 'Galeon', 'Minefield', 'MSIE', 'Opera', 
							  'SeaMonkey', 'SkypeUriPreview', 'Trident', 'Thunderbird', 'Qt', 'Wget');
				$inp_stats_user_agent_browser = "";
				foreach($desktop_browsers as $d_b){
 					if(stripos($my_user_agent, $d_b) !== false ){
						$inp_stats_user_agent_browser = "$d_b";
					}
				}

				// Browser icon
				$inp_stats_user_agent_browser_icon = clean($inp_stats_user_agent_browser);
				$inp_stats_user_agent_browser_icon = $inp_stats_user_agent_browser_icon . ".png";
				$inp_stats_user_agent_browser_icon = output_html($inp_stats_user_agent_browser_icon);


				// Browser version
				$inp_stats_user_agent_browser_version = 0;
				if($inp_stats_user_agent_browser != ""){
					$inp_stats_user_agent_browser_version = substr($my_user_agent, strrpos($my_user_agent, '/') + 1);
					$inp_stats_user_agent_browser_version_len = strlen($inp_stats_user_agent_browser_version);
					if($inp_stats_user_agent_browser_version_len > 10){
						$inp_stats_user_agent_browser_version = substr($inp_stats_user_agent_browser_version, 0, 10);
					}
				}
				// echo"Agent: $my_user_agent<br />Browser ver: $inp_stats_user_agent_browser_version ";

				// Browser version: check for ;  example 4.0; GTB7.2; SLCC1; .NET CLR 2.0.50727; InfoPath.1; .NET CLR 3.5.30729; .NET CLR 3.0.30618; .NET4.0C)
				if (strpos($inp_stats_user_agent_browser_version, ';') !== false) {
					$browser_version_explode = explode(";", $inp_stats_user_agent_browser_version);
					$inp_stats_user_agent_browser_version = $browser_version_explode[0];
				}

				// Browser version: check for ) example 522.12.1) AND 7184=6517-- QBxL
				if (strpos($inp_stats_user_agent_browser_version, ')') !== false) {
					$browser_version_explode = explode(")", $inp_stats_user_agent_browser_version);
					$inp_stats_user_agent_browser_version = $browser_version_explode[0];
				}

				// Browser version: check for   example 522.12.1) AND 7184=6517-- QBxL
				if (strpos($inp_stats_user_agent_browser_version, ' ') !== false) {
					$browser_version_explode = explode(" ", $inp_stats_user_agent_browser_version);
					$inp_stats_user_agent_browser_version = $browser_version_explode[0];
				}

				// Browser version: check for ( example 10_15_5(Erg&#228
				if (strpos($inp_stats_user_agent_browser_version, '(') !== false) {
					$browser_version_explode = explode("(", $inp_stats_user_agent_browser_version);
					$inp_stats_user_agent_browser_version = $browser_version_explode[0];
				}
				$inp_stats_user_agent_browser_version = output_html($inp_stats_user_agent_browser_version);
				$inp_stats_user_agent_browser_version = str_replace("&amp;#039", "", $inp_stats_user_agent_browser_version);


				// Browser
				$inp_stats_user_agent_browser = output_html(ucfirst($inp_stats_user_agent_browser));


				// Insert new desktop
				if($test == "1"){
					echo"<p><hr /></p>
					<h2>Autoinsert new user agent: Desktop</h2>
					
					<p><b>Visitor type:</b> $visitor_type<br />
					<b>My user agent:</b> $my_user_agent<br />
					<b>Browser:</b> $inp_stats_user_agent_browser<br />
					<b>Browser version:</b> $inp_stats_user_agent_browser_version<br />
					<b>Browser icon:</b> $inp_stats_user_agent_browser_icon<br />

					<b>OS:</b> $inp_stats_user_agent_os<br />
					<b>OS version:</b> $inp_stats_user_agent_os_version<br />
					<b>OS icon:</b> $inp_stats_user_agent_os_icon<br />
					</p>
					<pre>INSERT INTO $t_stats_user_agents_index
					(stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, 
					stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, 
					stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned) 
					VALUES
					(NULL, $my_user_agent_mysql, 'desktop', $inp_stats_user_agent_browser_mysql, $inp_stats_user_agent_browser_version_mysql, 
					$inp_stats_user_agent_browser_icon_mysql, $inp_stats_user_agent_os_mysql, $inp_stats_user_agent_os_version_mysql, $inp_stats_user_agent_os_icon_mysql, '', 
					'', '', '0')</pre>";
				}
				
				$inp_user_agent_type = "desktop";
				$blank = "";
				$zero = 0;

				$stmt = $mysqli->prepare("INSERT INTO $t_stats_user_agents_index
					(stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, 
					stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, 
					stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned) 
					VALUES 
					(NULL,?,?,?,?,
					?,?,?,?,?,
					?,?,?)");
				$stmt->bind_param("ssssssssssss", $my_user_agent, $inp_user_agent_type, $inp_stats_user_agent_browser, $inp_stats_user_agent_browser_version, 
					$inp_stats_user_agent_browser_icon, $inp_stats_user_agent_os, $inp_stats_user_agent_os_version, $inp_stats_user_agent_os_icon, $blank, 
					$blank, $blank, $zero); 
				$stmt->execute();


				break;
			}
		}

	}

	// Unknown - Is it a bot?
	if($visitor_type == ""){

		// Search for "Crawler", "Bot"
		$crawlers = array(
			'bot', 'crawler');

		foreach($crawlers as $c){
 			if(stripos($my_user_agent, $c) !== false ){



				// Agent name
				$array = explode(" ", $c);
				$array_size = sizeof($array);
				for($x=0;$x<$array_size;$x++){
					if(stripos($array[$x], $c) !== false ){

						// Visitor type
						$visitor_type = "bot";

						// URL
						$inp_stats_user_agent_url = "http://" . $my_user_agent . ".com";
						$inp_stats_user_agent_url = output_html($inp_stats_user_agent_url);

						// Agent Name
						$inp_stats_user_agent_bot = ucfirst($my_user_agent);
						$inp_stats_user_agent_bot = output_html($inp_stats_user_agent_bot);

						// Icon
						$inp_stats_user_agent_bot_icon = $array[$x] . ".png";
						$inp_stats_user_agent_bot_icon = output_html($inp_stats_user_agent_bot_icon);
			
						// Insert unknown bot
						if($test == "1"){
							echo"<p><hr /></p>
							<h2>Autoinsert new user agent: Bot</h2>
						
							<pre>INSERT INTO $t_stats_user_agents_index
							(stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, 
							stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, 
							stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned) 
							VALUES
							(NULL, $my_user_agent_mysql, 'bot', '', 0, 
							'', '', 0, '' $inp_stats_user_agent_bot_mysql, 
							$inp_stats_user_agent_bot_icon_mysql, $inp_stats_user_agent_url_mysql, '0'</pre>";
						}

						$inp_user_agent_type = "bot";
						$blank = "";
						$zero = 0;
						$stmt = $mysqli->prepare("INSERT INTO $t_stats_user_agents_index
							(stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, 
							stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, 
							stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned) 
							VALUES 
							(NULL,?,?,?,?,
							?,?,?,?,?,
							?,?,?)");
						$stmt->bind_param("ssssssssssss", $my_user_agent, $inp_user_agent_type, $blank, $zero, 
							$blank, $blank, $zero, $blank, $inp_stats_user_agent_bot, 
							$inp_stats_user_agent_bot_icon, $inp_stats_user_agent_url, $zero); 
						$stmt->execute();




						break;
					}
				}
			}
		}
	}

	// Unknown
	if($visitor_type == ""){
		if($test == "1"){
			echo"<span>Visitor type = unknown<br />
			User agent = $my_user_agent</span>\n";
		}

		// New visitor
		$inp_user_agent_type = "unknown";
		$zero = 0;

		$stmt = $mysqli->prepare("INSERT INTO $t_stats_user_agents_index
			(stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_banned) 
			VALUES 
			(NULL,?,?,?)");
		$stmt->bind_param("sss", $my_user_agent, $inp_user_agent_type, $zero); 
		$stmt->execute();

	}
}
else{
	echo"
	<p>Missing $get_stats_user_agent_id && $get_stats_user_agent_id == </p>
	";
}
?>