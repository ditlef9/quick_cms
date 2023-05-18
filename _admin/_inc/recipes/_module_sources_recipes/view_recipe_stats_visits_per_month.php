<?php 
/**
*
* File: recipes/view_recipe_stats_visits_per_month.php
* Version 4.0
* Date 02:09 02.04.2022
* Copyright (c) 2022 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Header ----------------------------------------------------------------------------- */
$inp_header ="// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var rootA = am5.Root.new(\"chartdiv_visits_per_month\");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
rootA.setThemes([
  am5themes_Animated.new(rootA)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chartA = rootA.container.children.push(am5xy.XYChart.new(rootA, {
  panX: false,
  panY: false,
  layout: rootA.verticalLayout
}));


// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legendA = chartA.children.push(
  am5.Legend.new(rootA, {
    centerX: am5.p50,
    x: am5.p50
  })
);


";

/*- Visits per year -------------------------------------------------------------------------- */
$inp_data = "// Set data
var data = [";

$datetime_class = new DateTime();
$datetime_class->modify('-1 year'); // 1 year ago
$datetime_class->modify('+1 month');
$stats_year_minus_one = $year - 1;
// echo $datetime_class->format('Y-m-d'); // 2021-10-19

for($x=0;$x<12;$x++){
	$this_year_lookup = $datetime_class->format('Y');
	$last_year_lookup = $this_year_lookup-1;
	$month_lookup = $datetime_class->format('m');
	$month_n_lookup = $datetime_class->format('n');

	
	// Fetch this year
	$query = "SELECT stats_visit_per_month_id, stats_visit_per_month_month, stats_visit_per_month_month_full, stats_visit_per_month_month_short, stats_visit_per_month_year, stats_visit_per_month_recipe_id, stats_visit_per_month_recipe_title, stats_visit_per_month_recipe_image_path, stats_visit_per_month_recipe_thumb_278x156, stats_visit_per_month_recipe_language, stats_visit_per_month_count FROM $t_recipes_stats_views_per_month WHERE stats_visit_per_month_recipe_id=$get_recipe_id AND stats_visit_per_month_month=$month_lookup AND stats_visit_per_month_year=$this_year_lookup";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_this_stats_visit_per_month_id, $get_this_stats_visit_per_month_month, $get_this_stats_visit_per_month_month_full, $get_this_stats_visit_per_month_month_short, $get_this_stats_visit_per_month_year, $get_this_stats_visit_per_month_recipe_id, $get_this_stats_visit_per_month_recipe_title, $get_this_stats_visit_per_month_recipe_image_path, $get_this_stats_visit_per_month_recipe_thumb_278x156, $get_this_stats_visit_per_month_recipe_language, $get_this_stats_visit_per_month_count) = $row;
	if($get_this_stats_visit_per_month_count == ""){
		$get_this_stats_visit_per_month_count = 0;
	}

	// Fetch last year
	$query = "SELECT stats_visit_per_month_id, stats_visit_per_month_month, stats_visit_per_month_month_full, stats_visit_per_month_month_short, stats_visit_per_month_year, stats_visit_per_month_recipe_id, stats_visit_per_month_recipe_title, stats_visit_per_month_recipe_image_path, stats_visit_per_month_recipe_thumb_278x156, stats_visit_per_month_recipe_language, stats_visit_per_month_count FROM $t_recipes_stats_views_per_month WHERE stats_visit_per_month_recipe_id=$get_recipe_id AND stats_visit_per_month_month=$month_lookup AND stats_visit_per_month_year=$last_year_lookup";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_last_stats_visit_per_month_id, $get_last_stats_visit_per_month_month, $get_last_stats_visit_per_month_month_full, $get_last_stats_visit_per_month_month_short, $get_last_stats_visit_per_month_year, $get_last_stats_visit_per_month_recipe_id, $get_last_stats_visit_per_month_recipe_title, $get_last_stats_visit_per_month_recipe_image_path, $get_last_stats_visit_per_month_recipe_thumb_278x156, $get_last_stats_visit_per_month_recipe_language, $get_last_stats_visit_per_month_count) = $row;
	if($get_last_stats_visit_per_month_count == ""){
		$get_last_stats_visit_per_month_count = 0;
	}

	if($x > 0){
		$inp_data = $inp_data . ",";
	}
	$inp_data = $inp_data . "{
			  xlabelXYChart: \"$get_this_stats_visit_per_month_month_short\",
			  value1: $get_this_stats_visit_per_month_count,
			  value2: $get_last_stats_visit_per_month_count
		}";

	// Modify
	$datetime_class->modify('+1 month');
} // for


$inp_data = $inp_data . "]";

/*- Footer ------------------------------------------------------------------------------------ */
$inp_footer = "

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chartA.xAxes.push(am5xy.CategoryAxis.new(rootA, {
  categoryField: \"xlabelXYChart\",
  renderer: am5xy.AxisRendererX.new(rootA, {
    cellStartLocation: 0.1,
    cellEndLocation: 0.9
  }),
  tooltip: am5.Tooltip.new(rootA, {})
}));

xAxis.data.setAll(data);

var yAxis = chartA.yAxes.push(am5xy.ValueAxis.new(rootA, {
  renderer: am5xy.AxisRendererY.new(rootA, {})
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
function makeSeries(name, fieldName) {
  var series = chartA.series.push(am5xy.ColumnSeries.new(rootA, {
    name: name,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: fieldName,
    categoryXField: \"xlabelXYChart\"
  }));

  series.columns.template.setAll({
    tooltipText: \"{categoryX} {name}: {valueY}\",
    width: am5.percent(90),
    tooltipY: 0
  });

  series.data.setAll(data);

  // Make stuff animate on load
  // https://www.amcharts.com/docs/v5/concepts/animations/
  series.appear();

  series.bullets.push(function () {
    return am5.Bullet.new(rootA, {
      locationY: 0,
      sprite: am5.Label.new(rootA, {
        text: \"{valueY}\",
        fill: rootA.interfaceColors.get(\"alternativeText\"),
        centerY: 0,
        centerX: am5.p50,
        populateText: true
      })
    });
  });

  legendA.data.push(series);
}


makeSeries(\"$year\", \"value1\");
makeSeries(\"$stats_year_minus_one\", \"value2\");


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/#Forcing_appearance_animation
chartA.appear(1000, 100);";



/*- Write to file ----------------------------------------------------------------------------- */
if(!(is_dir("../_cache"))){
	mkdir("../_cache");

	$fp = fopen("../_cache/index.html", "w") or die("Unable to open file!");
	fwrite($fp, "Server error 403");
	fclose($fp);

}
if(!(is_dir("../_cache/recipes"))){
	mkdir("../_cache/recipes");

	$fp = fopen("../_cache/recipes/index.html", "w") or die("Unable to open file!");
	fwrite($fp, "Server error 403");
	fclose($fp);
}
if(!(is_dir("../_cache/recipes/stats"))){
	mkdir("../_cache/recipes/stats");

	$fp = fopen("../_cache/recipes/stats/index.html", "w") or die("Unable to open file!");
	fwrite($fp, "Server error 403");
	fclose($fp);
}
$fp = fopen("../_cache/recipes/stats/$cache_file", "w") or die("Unable to open file!");
fwrite($fp, $inp_header);
fwrite($fp, $inp_data);
fwrite($fp, $inp_footer);
fclose($fp);





/*- Test ------------------------------------------------------------------------------------- */
$inp_test="<!DOCTYPE html>
<html>
  <head>
    <meta charset=\"UTF-8\" />
    <title>visits_per_month</title>
    <link rel=\"stylesheet\" href=\"index.css\" />
</head>
<body>
    <div id=\"chartdiv_visits_per_month\" style=\"width: 100%;height: 400px;\"></div>

<script src=\"../../../_admin/_javascripts/amcharts/index.js\"></script>
<script src=\"../../../_admin/_javascripts/amcharts/xy.js\"></script>
<script src=\"../../../_admin/_javascripts/amcharts/themes/Animated.js\"></script>
<script src=\"$cache_file\"></script>
  </body>
</html>";


$fp = fopen("../_cache/recipes/stats/$cache_file.html", "w") or die("Unable to open file!");
fwrite($fp, $inp_test);
fclose($fp);

?>