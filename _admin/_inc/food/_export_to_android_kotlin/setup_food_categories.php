<?php
/**
*
* File: _admin/_inc/food/_export_to_android_kotlin/setup_food_categories.php
* Version 10:05 18.10.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Categories -------------------------------------------------------------------------- */
echo"
<p>
import android.content.Context<br /><br />

class Setup3FoodCategories {<br />
&nbsp; &nbsp; /*- Categories -------------------------------------------------------------------------- */<br />
&nbsp; &nbsp; fun insertFoodCategories(context: Context){<br />
&nbsp; &nbsp; &nbsp; &nbsp; var db: DatabaseHelper? = DatabaseHelper(context)<br /><br />
";

// Get all categories
$q_count = 0;
$category_count = 0;
$insert_count = 0;
$transfer_main_category_id = 0;
$query = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='0'";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_main_category_id, $get_main_category_name, $get_main_category_parent_id) = $row;

	// Inp
	$inp_main_category_name_mysql = quote_smart($link, $get_main_category_name);

	if($insert_count == "0"){
		echo"&nbsp; &nbsp; &nbsp; &nbsp; val q$q_count = &quot;INSERT INTO food_categories(category_id, category_user_id, category_name, category_parent_id) \n&quot; +<br />
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;VALUES &quot; +<br />
        	";
	}
	else{
		// Next insertion before
		echo",&quot; + <br />\n";
	}

	// Insert main category
	echo"
	&nbsp; &nbsp; &nbsp; &nbsp; &quot;($get_main_category_id, '0', $inp_main_category_name_mysql, '0')";
		
	// Main count
	$insert_count++;
	$category_count++;
	$transfer_main_category_id = $category_count;

	// Get sub
	$queryb = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='$get_main_category_id'";
	$resultb = mysqli_query($link, $queryb);
	while($rowb = mysqli_fetch_row($resultb)) {
		list($get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $rowb;

		// Inp
		$inp_sub_category_name_mysql = quote_smart($link, $get_sub_category_name);

		if($insert_count == "0"){
			echo"&nbsp; &nbsp; &nbsp; &nbsp; val q$q_count =&quot;INSERT INTO food_categories(category_id, category_user_id, category_name, category_parent_id) \n&quot; +<br />
			&nbsp; &nbsp; &nbsp; &nbsp; &quot;VALUES &quot; +<br />
      			";
		}
		else{
			// Next insertion before
			echo",&quot; + <br />\n";
		}

		// Insert sub category
		echo"
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;($get_sub_category_id, '0', $inp_sub_category_name_mysql, '$get_main_category_id')";

		// Sub count
		$insert_count++;
		$category_count++;



		// End insert count
		if($insert_count > 10){
			// Insertion block finished
			echo"&quot;<br />
			&nbsp; &nbsp; &nbsp; &nbsp; db!!.query(q$q_count)<br /><br />
			";
			$insert_count = 0;
			$q_count++;
		}
	}

	// End insert count
	if($insert_count > 10){
		// Insertion block finished
		echo"&quot;<br />
		&nbsp; &nbsp; &nbsp; &nbsp; db!!.query(q$q_count)<br /><br />
		";
		$insert_count = 0;
		$q_count++;
	}
} // while categories

// End insert count
if($insert_count != 0){
	// Insertion block finished
	echo"&quot;<br />
	&nbsp; &nbsp; &nbsp; &nbsp; db!!.query(q$q_count)<br /><br />
	";
}

echo"&nbsp; &nbsp; } // insertFoodCategories<br />
</p>
";



/*- Categories Translations -------------------------------------------------------------------------- */
echo"
<p>
&nbsp; &nbsp; /*- Categories Translations ------------------------------------------------------------- */<br />
&nbsp; &nbsp; fun insertFoodCategoriesTranslations(context: Context){<br />
&nbsp; &nbsp; &nbsp; &nbsp; var db: DatabaseHelper? = DatabaseHelper(context)<br /><br />
";

// Get all translations
$q_count = 0;
$insert_count = 0;
$query = "SELECT category_translation_id, category_id, category_translation_language, category_translation_value FROM $t_food_categories_translations";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_category_translation_id, $get_category_id, $get_category_translation_language, $get_category_translation_value) = $row;

	// Inp
	$inp_translation_value_mysql = quote_smart($link, $get_category_translation_value);

	if($insert_count == "0"){
		echo"&nbsp; &nbsp; &nbsp; &nbsp; val q$q_count = &quot;INSERT INTO food_categories_translations(category_translation_id, category_id, category_translation_language, category_translation_value) \n&quot; +<br />
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;VALUES &quot; +<br />
        	";
	}
	else{
		// Next insertion before
		echo",&quot; + <br />\n";
	}

	// Insert 
	echo"
	&nbsp; &nbsp; &nbsp; &nbsp; &quot;(NULL, $get_category_id, '$get_category_translation_language', $inp_translation_value_mysql)";
	$insert_count++;


	// End insert count
	if($insert_count > 10){
		// Insertion block finished
		echo"&quot;<br />
		&nbsp; &nbsp; &nbsp; &nbsp; db!!.query(q$q_count)<br /><br />
		";
		$insert_count = 0;
		$q_count++;
	}

} // translations
// End insert count
if($insert_count != 0){
	// Insertion block finished
	echo"&quot;<br />
	&nbsp; &nbsp; &nbsp; &nbsp; db!!.query(q$q_count)<br /><br />
	";
}

echo"&nbsp; &nbsp; } // insertFoodCategoriesTranslations<br />
</p>
";

/*- End class -------------------------------------------------------------------------- */
echo"
<p>
} // class
</p>
";
?>