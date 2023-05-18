<?php
if(isset($_SESSION['user_id']) && isset($_SESSION['security']) && $get_goal_id != ""){
	echo"
	
	<!-- Charts javascript -->
		<script src=\"$root/_admin/_javascripts/amcharts4/core.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts4/charts.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts4/themes/animated.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts4/plugins/venn.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts4/maps.js\"></script>
		<script src=\"$root/_admin/_javascripts/amcharts4/geodata/worldLow.js\"></script>
	<!-- //Charts javascript -->


	<!-- Visits per month -->
		
		<script>
		am4core.ready(function() {
			var chart = am4core.create(\"chartdiv_calories_consumed\", am4charts.XYChart);
			chart.data = [";

			$x = 0;
			$query = "SELECT consumed_day_id, consumed_day_user_id, consumed_day_year, consumed_day_month, consumed_day_month_saying, consumed_day_day, consumed_day_day_saying, consumed_day_date, consumed_day_energy, consumed_day_fat, consumed_day_saturated_fat, consumed_day_monounsaturated_fat, consumed_day_polyunsaturated_fat, consumed_day_cholesterol, consumed_day_carbohydrates, consumed_day_carbohydrates_of_which_sugars, consumed_day_dietary_fiber, consumed_day_proteins, consumed_day_salt, consumed_day_sodium, consumed_day_target_sedentary_energy, consumed_day_target_sedentary_fat, consumed_day_target_sedentary_carb, consumed_day_target_sedentary_protein, consumed_day_target_with_activity_energy, consumed_day_target_with_activity_fat, consumed_day_target_with_activity_carb, consumed_day_target_with_activity_protein, consumed_day_diff_sedentary_energy, consumed_day_diff_sedentary_fat, consumed_day_diff_sedentary_carb, consumed_day_diff_sedentary_protein, consumed_day_diff_with_activity_energy, consumed_day_diff_with_activity_fat, consumed_day_diff_with_activity_carb, consumed_day_diff_with_activity_protein, consumed_day_updated_datetime, consumed_day_synchronized FROM $t_food_diary_consumed_days WHERE consumed_day_user_id=$my_user_id_mysql ORDER BY consumed_day_id ASC LIMIT 0,30";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
    				list($get_consumed_day_id, $get_consumed_day_user_id, $get_consumed_day_year, $get_consumed_day_month, $get_consumed_day_month_saying, $get_consumed_day_day, $get_consumed_day_day_saying, $get_consumed_day_date, $get_consumed_day_energy, $get_consumed_day_fat, $get_consumed_day_saturated_fat, $get_consumed_day_monounsaturated_fat, $get_consumed_day_polyunsaturated_fat, $get_consumed_day_cholesterol, $get_consumed_day_carbohydrates, $get_consumed_day_carbohydrates_of_which_sugars, $get_consumed_day_dietary_fiber, $get_consumed_day_proteins, $get_consumed_day_salt, $get_consumed_day_sodium, $get_consumed_day_target_sedentary_energy, $get_consumed_day_target_sedentary_fat, $get_consumed_day_target_sedentary_carb, $get_consumed_day_target_sedentary_protein, $get_consumed_day_target_with_activity_energy, $get_consumed_day_target_with_activity_fat, $get_consumed_day_target_with_activity_carb, $get_consumed_day_target_with_activity_protein, $get_consumed_day_diff_sedentary_energy, $get_consumed_day_diff_sedentary_fat, $get_consumed_day_diff_sedentary_carb, $get_consumed_day_diff_sedentary_protein, $get_consumed_day_diff_with_activity_energy, $get_consumed_day_diff_with_activity_fat, $get_consumed_day_diff_with_activity_carb, $get_consumed_day_diff_with_activity_protein, $get_consumed_day_updated_datetime, $get_consumed_day_synchronized) = $row;
			
				if($get_consumed_day_energy != "0"){	
					if($x > 0){
						echo",";
					}
					echo"
					{
						\"x\": \"$get_consumed_day_day_saying $get_consumed_day_day\",
						\"value\": $get_consumed_day_energy
					}";
					$x++;
				}
			} // while

			echo"
			];
			// Create axes
			var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = \"x\";
			var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
							
			// Create series
			var series1 = chart.series.push(new am4charts.ColumnSeries);
			series1.dataFields.valueY = \"value\";
			series1.dataFields.categoryX = \"x\";
			series1.name = \"$l_calories_consumed\";
			series1.tooltipText = \"$l_calories_consumed: {valueY}\";
			series1.fill = am4core.color(\"#99e4dc\");
			series1.stroke = am4core.color(\"#66d5c9\");
			series1.strokeWidth = 1;

			// Tooltips
			chart.cursor = new am4charts.XYCursor();
			chart.cursor.snapToSeries = series;
			chart.cursor.xAxis = valueAxis;
		}); // end am4core.ready()
		</script>
		<div id=\"chartdiv_calories_consumed\" style=\"height: 400px;\"></div>
	<!-- //Visits per month -->



	";

}
else{
	echo"?";
}
?>