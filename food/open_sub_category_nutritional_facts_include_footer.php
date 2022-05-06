<?php
/**
*
* File: food/open_sub_category_nutritional_facts_include_footer.php
* Version 1.0.0.
* Date 09:51 10.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if(!(isset($website_title))){
	echo"Error";
	die;
}
echo"

</main>

<footer>
	<p>&copy; $year $configWebsiteTitleSav</p>
</footer>
</body>
</html>";
?>