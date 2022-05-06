<?php
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>post_new_recipe_receive_test</title>
</head>
<body>



<form method=\"post\" action=\"post_new_recipe_receive.php\" enctype=\"multipart/form-data\">
	
	<p>
	<b>inp_user_id</b><br />
	<input type=\"text\" name=\"inp_user_id\" value=\"1\" size=\"30\" />
	</p>
	
	<p>
	<b>inp_title</b><br />
	<input type=\"text\" name=\"inp_title\" value=\"Eggestuing\" size=\"30\" />
	</p>
	
	<p>
	<b>inp_category_id</b><br />
	<input type=\"text\" name=\"inp_category_id\" value=\"1\" size=\"30\" />
	</p>
	
	<p>
	<b>inp_language</b><br />
	<input type=\"text\" name=\"inp_language\" value=\"en\" size=\"30\" />
	</p>
	
	<p>
	<b>inp_introduction</b><br />
	<input type=\"text\" name=\"inp_introduction\" value=\"Et fesk så føle du dæ fresk\" size=\"30\" />
	</p>
	
	
	<p>
	<b>inp_directions</b><br />
	<input type=\"text\" name=\"inp_directions\" value=\"1. Kok. 2. Spis\" size=\"30\" />
	</p>
	
	<p>
	<b>inp_calories_per_hundred</b><br />
	<input type=\"text\" name=\"inp_calories_per_hundred\" value=\"";  echo rand(0,500); echo"\" size=\"30\" />
	</p>
	
	<p>
	<b>inp_proteins_per_hundred</b><br />
	<input type=\"text\" name=\"inp_proteins_per_hundred\" value=\"";  echo rand(0,500); echo"\" size=\"30\" />
	</p>
	
	<p>
	<b>inp_fat_per_hundred</b><br />
	<input type=\"text\" name=\"inp_fat_per_hundred\" value=\"";  echo rand(0,500); echo"\" size=\"30\" />
	</p>
	
	<p>
	<b>inp_carbs_per_hundred</b><br />
	<input type=\"text\" name=\"inp_carbs_per_hundred\" value=\"";  echo rand(0,500); echo"\" size=\"30\" />
	</p>
			
	
	<p>
	<b>inp_total_weight</b><br />
	<input type=\"text\" name=\"inp_total_weight\" value=\"";  echo rand(0,500); echo"\" size=\"30\" />
	</p>

	<p>
	<b>inp_total_calories</b><br />
	<input type=\"text\" name=\"inp_total_calories\" value=\"";  echo rand(0,500); echo"\" size=\"30\" />
	</p>		
	
	<p>
	<b>inp_total_proteins</b><br />
	<input type=\"text\" name=\"inp_total_proteins\" value=\"";  echo rand(0,500); echo"\" size=\"30\" />
	</p>

	<p>
	<b>inp_total_fat</b><br />
	<input type=\"text\" name=\"inp_total_fat\" value=\"";  echo rand(0,500); echo"\" size=\"30\" />
	</p>
		
	<p>
	<b>inp_total_carbs</b><br />
	<input type=\"text\" name=\"inp_total_carbs\" value=\"";  echo rand(0,500); echo"\" size=\"30\" />
	</p>
		
	<p>
	<b>inp_servings</b><br />
	<input type=\"text\" name=\"inp_servings\" value=\"";  echo rand(0,20); echo"\" size=\"30\" />
	</p>
		
	<p>
	<b>inp_cook_time</b><br />
	<input type=\"text\" name=\"inp_cook_time\" value=\"";  echo rand(0,90); echo"\" size=\"30\" />
	</p>
		
	<p>
	<b>inp_prep_time</b><br />
	<input type=\"text\" name=\"inp_prep_time\" value=\"";  echo rand(0,90); echo"\" size=\"30\" />
	</p>
		
	<p>
	<b>inp_tags</b><br />
	<input type=\"text\" name=\"inp_tags\" value=\"food mat chicken\" size=\"30\" />
	</p>
		
		
	<p>
	<b>inp_password</b><br />
	<input type=\"text\" name=\"inp_password\" value=\"1234\" size=\"30\" />
	</p>
		
							
	<p>
	<input type=\"submit\" value=\"Send\" />
	</p>
</form>

</body>
</html>";
?>