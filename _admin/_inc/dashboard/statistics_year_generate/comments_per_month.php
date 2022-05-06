<?php
/**
*
* File: _stats/_pages/stats/statistics_year_generate/comments_per_month.php
* Version 1
* Date 00:55 02.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
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
var root = am5.Root.new(\"chartdiv_comments_per_month\");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: false,
  panY: false,
  layout: root.verticalLayout
}));


// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legend = chart.children.push(
  am5.Legend.new(root, {
    centerX: am5.p50,
    x: am5.p50
  })
);


";

/*- Visits per year -------------------------------------------------------------------------- */
$inp_data = "// Set data
var data = [";

$x=0;
$query = "SELECT stats_comments_id, stats_comments_month_short, stats_comments_comments_written FROM $t_stats_comments_per_month WHERE stats_comments_year=$get_current_stats_visit_per_year_year AND stats_comments_language=$editor_language_mysql ORDER BY stats_comments_month";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_stats_comments_id, $get_stats_comments_month_short, $get_stats_comments_comments_written) = $row;

	if($x > 0){
		$inp_data = $inp_data . ",";
	}

	$inp_data = $inp_data . "{
			  xlabel: \"$get_stats_comments_month_short\",
			  value1: $get_stats_comments_comments_written
		}";

	$x++;
} // while


$inp_data = $inp_data . "]";

/*- Footer ------------------------------------------------------------------------------------ */
$inp_footer = "

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  categoryField: \"xlabel\",
  renderer: am5xy.AxisRendererX.new(root, {
    cellStartLocation: 0.1,
    cellEndLocation: 0.9
  }),
  tooltip: am5.Tooltip.new(root, {})
}));

xAxis.data.setAll(data);

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  renderer: am5xy.AxisRendererY.new(root, {})
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
function makeSeries(name, fieldName) {
  var series = chart.series.push(am5xy.ColumnSeries.new(root, {
    name: name,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: fieldName,
    categoryXField: \"xlabel\"
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
    return am5.Bullet.new(root, {
      locationY: 0,
      sprite: am5.Label.new(root, {
        text: \"{valueY}\",
        fill: root.interfaceColors.get(\"alternativeText\"),
        centerY: 0,
        centerX: am5.p50,
        populateText: true
      })
    });
  });

  legend.data.push(series);
}


makeSeries(\"$stats_year\", \"value1\");


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/#Forcing_appearance_animation
chart.appear(1000, 100);";



/*- Write to file ----------------------------------------------------------------------------- */
if(!(is_dir("../_cache"))){
	mkdir("../_cache");

	$fp = fopen("../_cache/index.html", "w") or die("Unable to open file!");
	fwrite($fp, "Server error 403");
	fclose($fp);

}
if(!(is_dir("../_cache/stats_year"))){
	mkdir("../_cache/stats_year");

	$fp = fopen("../_cache/stats_year/index.html", "w") or die("Unable to open file!");
	fwrite($fp, "Server error 403");
	fclose($fp);
	$fp = fopen("../_cache/stats_year/index.css", "w") or die("Unable to open file!");
	fwrite($fp, "");
	fclose($fp);

}
$fp = fopen("../_cache/stats_year/$cache_file", "w") or die("Unable to open file!");
fwrite($fp, $inp_header);
fwrite($fp, $inp_data);
fwrite($fp, $inp_footer);
fclose($fp);





/*- Test ------------------------------------------------------------------------------------- */
$inp_test="<!DOCTYPE html>
<html>
  <head>
    <meta charset=\"UTF-8\" />
    <title>comments_per_month for $editor_language</title>
    <link rel=\"stylesheet\" href=\"index.css\" />
</head>
<body>
<h1>comments_per_month for $editor_language</h1>

<div id=\"chartdiv_visits_per_month\" style=\"width: 100%;height: 80vh;\"></div>

<script src=\"../../_admin/_javascripts/amcharts/index.js\"></script>
<script src=\"../../_admin/_javascripts/amcharts/xy.js\"></script>
<script src=\"../../_admin/_javascripts/amcharts/themes/Animated.js\"></script>
<script src=\"$cache_file\"></script>
</body>
</html>";


$fp = fopen("../_cache/stats_year/$cache_file.html", "w") or die("Unable to open file!");
fwrite($fp, $inp_test);
fclose($fp);

?>