<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_templates	= $mysqlPrefixSav . "tasks_templates";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_templates") or die(mysqli_error());


$query = "SELECT * FROM $t_tasks_templates LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{


	mysqli_query($link, "CREATE TABLE $t_tasks_templates(
	   template_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(template_id), 
	   template_language VARCHAR(2),
	   template_title VARCHAR(250),
	   template_text TEXT,
	   template_active INT,
	   template_created_by_user_id INT,
	   template_created_datetime DATETIME,
	   template_updated_by_user_id INT,
	   template_updated_datetime DATETIME
	)")
	or die(mysqli_error($link));


	// Find all admins, put them into subscription list
	$datetime = date("Y-m-d H:i:s");
	
	$inp_text="<h2>1. Information</h2>
<table class=\"hor-zebra\">
 <tbody>
  <tr>
   <td style=\"text-align: right;padding-right: 6px;\"><strong>Module:</strong></td>
   <td style=\"min-width: 100px;\">
   </td>
  </tr>

  <tr>
   <td style=\"text-align: right;padding-right: 6px;\"><strong>URL:</strong></td>
   <td style=\"min-width: 100px;\">
   </td>
  </tr>

  <tr>
   <td style=\"text-align: right;padding-right: 6px;\"><strong>Error:</strong></td>
   <td style=\"min-width: 100px;\">
   </td>
  </tr>
 </tbody>
</table>

<h2>2. Description</h2>
<p></p>";

	mysqli_query($link, "INSERT INTO $t_tasks_templates
	(template_id, template_created_by_user_id, template_created_datetime, template_updated_by_user_id, template_updated_datetime, template_title, template_text, template_active, template_language) 
	VALUES 
	(NULL, 1, '$datetime', 1, '$datetime', 'Default template', '$inp_text', 1, 'en')
	")
	or die(mysqli_error($link));



}





}
?>