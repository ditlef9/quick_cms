<?php
/**
*
* File: _admin/_inc/_dashboard/comments_per_week_last_2_years.php
* Version 1
* Date 12:39 02.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
* This will be for all languages as it is on the front page
* 
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Header ----------------------------------------------------------------------------- */
$inp_header ="// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var rootA = am5.Root.new(\"chartdiv_comments_per_week_last_two_years\");


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
// var legendA = chartA.children.push(
//   am5.Legend.new(rootA, {
//     centerX: am5.p50,
//     x: am5.p50
//   })
// );


";

/*- Visits per year -------------------------------------------------------------------------- */
$inp_data_header = "// Set data
var data = [";

$datetime_class = new DateTime();
// echo $datetime_class->format('Y-m-d'); // 2022-04-02
$y = 0;
$inp_data_body = "";
for($x=0;$x<12;$x++){
	$this_year_lookup = $datetime_class->format('Y');
	$last_year_lookup = $this_year_lookup-1;
	$week_lookup = $datetime_class->format('W');

	
	// Fetch this year
	$comments_written_this_year_for_week = 0;
	$query = "SELECT stats_comments_id, stats_comments_week, stats_comments_month, stats_comments_year, stats_comments_comments_written, stats_comments_comments_written_diff_from_last_week FROM $t_stats_comments_per_week WHERE stats_comments_week=$week_lookup AND stats_comments_year=$this_year_lookup";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_this_stats_comments_id, $get_this_stats_comments_week, $get_this_stats_comments_month, $get_this_stats_comments_year, $get_this_stats_comments_comments_written, $get_this_stats_comments_comments_written_diff_from_last_week) = $row;
		$comments_written_this_year_for_week = $comments_written_this_year_for_week + $get_this_stats_comments_comments_written;
	}

	// Fetch last year
	$comments_written_last_year_for_week = 0;
	$query = "SELECT stats_comments_id, stats_comments_week, stats_comments_month, stats_comments_year, stats_comments_comments_written, stats_comments_comments_written_diff_from_last_week FROM $t_stats_comments_per_week WHERE stats_comments_week=$week_lookup AND stats_comments_year=$last_year_lookup";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_last_stats_comments_id, $get_last_stats_comments_week, $get_last_stats_comments_month, $get_last_stats_comments_year, $get_last_stats_comments_comments_written, $get_last_stats_comments_comments_written_diff_from_last_week) = $row;
		$comments_written_last_year_for_week = $comments_written_last_year_for_week + $get_last_stats_comments_comments_written;
	}



	if($get_this_stats_comments_id != ""){
		if($inp_data_body == ""){
			$inp_data_body = "{
			  xlabelXYChart: \"$week_lookup\",
			  value1: $comments_written_this_year_for_week,
			  value2: $comments_written_last_year_for_week
			}";
		}
		else{
			$inp_data_body = "{
			  xlabelXYChart: \"$week_lookup\",
			  value1: $comments_written_this_year_for_week,
			  value2: $comments_written_last_year_for_week
			}" . "," . $inp_data_body;
		}
		$y++;
	}

	// Modify
	$datetime_class->modify('-1 week');
} // for


$inp_data = $inp_data_header . $inp_data_body . "]";

/*- Footer ------------------------------------------------------------------------------------ */
$year_minus_one =  $year-1;
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

var yRenderer = am5xy.AxisRendererY.new(rootA, {})
yRenderer.labels.template.set('visible', false)

var yAxis = chartA.yAxes.push(am5xy.ValueAxis.new(rootA, {
  renderer: yRenderer
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
    tooltipText: \"{categoryX}/{name}: {valueY}\",
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
makeSeries(\"$year_minus_one\", \"value2\");


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
if(!(is_dir("../_cache/stats_default"))){
	mkdir("../_cache/stats_default");

	$fp = fopen("../_cache/stats_default/index.html", "w") or die("Unable to open file!");
	fwrite($fp, "Server error 403");
	fclose($fp);
}
$fp = fopen("../_cache/stats_default/comments_per_week_last_2_years_$configSecurityCodeSav.js", "w") or die("Unable to open file!");
fwrite($fp, $inp_header);
fwrite($fp, $inp_data);
fwrite($fp, $inp_footer);
fclose($fp);





/*- Test ------------------------------------------------------------------------------------- */
$inp_test="<!DOCTYPE html>
<html>
  <head>
    <meta charset=\"UTF-8\" />
    <title>comments_per_week_last_2_years</title>
    <link rel=\"stylesheet\" href=\"index.css\" />
</head>
<body>
    <div id=\"chartdiv_comments_per_week_last_two_years\" style=\"width: 100%;height: 80vh;\"></div>

<script src=\"../../_admin/_javascripts/amcharts/index.js\"></script>
<script src=\"../../_admin/_javascripts/amcharts/xy.js\"></script>
<script src=\"../../_admin/_javascripts/amcharts/themes/Animated.js\"></script>
<script src=\"comments_per_week_last_2_years_$configSecurityCodeSav.js\"></script>
  </body>
</html>";

$fp = fopen("../_cache/stats_default/comments_per_week_last_2_years_$configSecurityCodeSav.html", "w") or die("Unable to open file!");
fwrite($fp, $inp_test);
fclose($fp);

?>