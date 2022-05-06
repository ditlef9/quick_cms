<?php
/**
*
* File: _admin/_inc/dashboard/statistics_month_generate/visits_per_day.php
* Version 1
* Date 01:56 02.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}




/*- Dates ----------------------------------------------------------------------------- */
$datetime_class = new DateTime();
$this_year = $datetime_class->format('Y');
$this_month_n = $datetime_class->format('n');
$this_month_saying = $datetime_class->format('F');

$datetime_class->modify('-1 month'); // 1 month ago
$last_year = $datetime_class->format('Y');
$last_month = $datetime_class->format('m');
$last_month_saying = $datetime_class->format('F');


/*- Header ----------------------------------------------------------------------------- */
$inp_header ="// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var rootV = am5.Root.new(\"chartdiv_visits_per_day\");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
rootV.setThemes([
  am5themes_Animated.new(rootV)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chartV = rootV.container.children.push(am5xy.XYChart.new(rootV, {
  panX: true,
  panY: true,
  layout: rootV.verticalLayout, // Legend at bottom
  maxTooltipDistance: 0
}));

chartV.get(\"colors\").set(\"step\", 3);


// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursorV = chartV.set(\"cursor\", am5xy.XYCursor.new(rootV, {}));
cursorV.lineY.set(\"visible\", false);


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxisV = chartV.xAxes.push(am5xy.DateAxis.new(rootV, {
  maxDeviation: 0.3,
  baseInterval: {
    timeUnit: \"day\",
    count: 1
  },
  renderer: am5xy.AxisRendererX.new(rootV, {}),
  tooltip: am5.Tooltip.new(rootV, {})
}));

var yAxisV = chartV.yAxes.push(am5xy.ValueAxis.new(rootV, {
  maxDeviation: 0.3,
  renderer: am5xy.AxisRendererY.new(rootV, {})
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var seriesV = chartV.series.push(am5xy.LineSeries.new(rootV, {
  name: \"$this_month_saying\",
  xAxis: xAxisV,
  yAxis: yAxisV,
  valueYField: \"value1\",
  valueXField: \"date\"
}));
seriesV.strokes.template.setAll({
  strokeWidth: 2
});

var tooltipV = seriesV.set(\"tooltip\", am5.Tooltip.new(rootV, {}));
tooltipV.get(\"background\").set(\"fillOpacity\", 0.5);
tooltipV.label.set(\"text\", \"{text1}: {valueY}\\n{text2}: {value2}\");

var seriesV2 = chartV.series.push(am5xy.LineSeries.new(rootV, {
  name: \"$last_month_saying\",
  xAxis: xAxisV,
  yAxis: yAxisV,
  valueYField: \"value2\",
  valueXField: \"date\"
}));
seriesV2.strokes.template.setAll({
  strokeDasharray: [2, 2],
  strokeWidth: 2
});



// Set date fields
// https://www.amcharts.com/docs/v5/concepts/data/#Parsing_dates
rootV.dateFormatter.setAll({
  dateFormat: \"yyyy-MM-dd\",
  dateFields: [\"valueX\"]
});


// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legendV = chartV.children.push(am5.Legend.new(rootV, {}));
legendV.data.setAll(chartV.series.values);
";

/*- Visits per year -------------------------------------------------------------------------- */
$inp_data = "// Set data
var data = [";



$x = 0;
$query_s = "SELECT stats_visit_per_day_id, stats_visit_per_day_day, stats_visit_per_day_day_full, stats_visit_per_day_day_three, stats_visit_per_day_day_single, stats_visit_per_day_month, stats_visit_per_day_month_full, stats_visit_per_day_month_short, stats_visit_per_day_year, stats_visit_per_day_human_unique, stats_visit_per_day_human_unique_diff_from_yesterday, stats_visit_per_day_human_average_duration, stats_visit_per_day_human_new_visitor_unique, stats_visit_per_day_human_returning_visitor_unique, stats_visit_per_day_unique_desktop, stats_visit_per_day_unique_mobile, stats_visit_per_day_unique_bots, stats_visit_per_day_hits_total, stats_visit_per_day_hits_human, stats_visit_per_day_hits_desktop, stats_visit_per_day_hits_mobile, stats_visit_per_day_hits_bots FROM $t_stats_visists_per_day WHERE stats_visit_per_day_month=$get_current_stats_visit_per_month_month AND stats_visit_per_day_year=$get_current_stats_visit_per_month_year AND stats_visit_per_day_language=$editor_language_mysql ORDER BY stats_visit_per_day_id";
$result_s = mysqli_query($link, $query_s);
while($row_s = mysqli_fetch_row($result_s)) {
	list($get_stats_visit_per_day_id, $get_stats_visit_per_day_day, $get_stats_visit_per_day_day_full, $get_stats_visit_per_day_day_three, $get_stats_visit_per_day_day_single, $get_stats_visit_per_day_month, $get_stats_visit_per_day_month_full, $get_stats_visit_per_day_month_short, $get_stats_visit_per_day_year, $get_stats_visit_per_day_human_unique, $get_stats_visit_per_day_human_unique_diff_from_yesterday, $get_stats_visit_per_day_human_average_duration, $get_stats_visit_per_day_human_new_visitor_unique, $get_stats_visit_per_day_human_returning_visitor_unique, $get_stats_visit_per_day_unique_desktop, $get_stats_visit_per_day_unique_mobile, $get_stats_visit_per_day_unique_bots, $get_stats_visit_per_day_hits_total, $get_stats_visit_per_day_hits_human, $get_stats_visit_per_day_hits_desktop, $get_stats_visit_per_day_hits_mobile, $get_stats_visit_per_day_hits_bots) = $row_s;
						

	// Fetch last month
	$query_l = "SELECT stats_visit_per_day_id, stats_visit_per_day_day, stats_visit_per_day_day_full, stats_visit_per_day_day_three, stats_visit_per_day_day_single, stats_visit_per_day_month, stats_visit_per_day_month_full, stats_visit_per_day_month_short, stats_visit_per_day_year, stats_visit_per_day_human_unique, stats_visit_per_day_human_unique_diff_from_yesterday, stats_visit_per_day_human_average_duration, stats_visit_per_day_human_new_visitor_unique, stats_visit_per_day_human_returning_visitor_unique, stats_visit_per_day_unique_desktop, stats_visit_per_day_unique_mobile, stats_visit_per_day_unique_bots, stats_visit_per_day_hits_total, stats_visit_per_day_hits_human, stats_visit_per_day_hits_desktop, stats_visit_per_day_hits_mobile, stats_visit_per_day_hits_bots FROM $t_stats_visists_per_day WHERE stats_visit_per_day_day=$get_stats_visit_per_day_day AND stats_visit_per_day_month=$last_month AND stats_visit_per_day_year=$last_year AND stats_visit_per_day_language=$editor_language_mysql";
	$result_l = mysqli_query($link, $query_l);
	$row_l = mysqli_fetch_row($result_l);
	list($get_stats_visit_per_day_id, $get_last_stats_visit_per_day_day, $get_last_stats_visit_per_day_day_full, $get_last_stats_visit_per_day_day_three, $get_last_stats_visit_per_day_day_single, $get_last_stats_visit_per_day_month, $get_last_stats_visit_per_day_month_full, $get_last_stats_visit_per_day_month_short, $get_last_stats_visit_per_day_year, $get_last_stats_visit_per_day_human_unique, $get_last_stats_visit_per_day_human_unique_diff_from_yesterday, $get_last_stats_visit_per_day_human_average_duration, $get_last_stats_visit_per_day_human_new_visitor_unique, $get_last_stats_visit_per_day_human_returning_visitor_unique, $get_last_stats_visit_per_day_unique_desktop, $get_last_stats_visit_per_day_unique_mobile, $get_last_stats_visit_per_day_unique_bots, $get_last_stats_visit_per_day_hits_total, $get_last_stats_visit_per_day_hits_human, $get_last_stats_visit_per_day_hits_desktop, $get_last_stats_visit_per_day_hits_mobile, $get_last_stats_visit_per_day_hits_bots) = $row_l;
	if($get_stats_visit_per_day_id == ""){
		$get_last_stats_visit_per_day_human_unique = 0;
	}

	if($x > 0){
		$inp_data = $inp_data . ",";
	}

	$day_double = "$get_stats_visit_per_day_day";
	if($x < 10){
		$day_double = "0" . $get_stats_visit_per_day_day;
	}

	$inp_data = $inp_data . "{
			  date: new Date($this_year, $this_month_n, $get_stats_visit_per_day_day).getTime(),
			  value1: $get_stats_visit_per_day_human_unique,
			  value2: $get_last_stats_visit_per_day_human_unique,
			  text1: \"$get_stats_visit_per_day_day_single $get_stats_visit_per_day_day\",
			  text2: \"$get_last_stats_visit_per_day_day_single $get_last_stats_visit_per_day_day\",
			  previousDate: \"$last_year-$last_month-$day_double\"
		}";

	$x++;
}


$inp_data = $inp_data . "]";

/*- Footer ------------------------------------------------------------------------------------ */
$inp_footer = "

seriesV.data.setAll(data);
seriesV2.data.setAll(data);


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/#Forcing_appearance_animation
seriesV.appear(1000);
seriesV2.appear(1000);
chartV.appear(1000, 100);";





/*- Write to file ----------------------------------------------------------------------------- */
if(!(is_dir("../_cache"))){
	mkdir("../_cache");

	$fp = fopen("../_cache/index.html", "w") or die("Unable to open file!");
	fwrite($fp, "Server error 403");
	fclose($fp);

}
if(!(is_dir("../_cache/stats_month"))){
	mkdir("../_cache/stats_month");

	$fp = fopen("../_cache/stats_month/index.html", "w") or die("Unable to open file!");
	fwrite($fp, "Server error 403");
	fclose($fp);
	$fp = fopen("../_cache/stats_month/index.css", "w") or die("Unable to open file!");
	fwrite($fp, "");
	fclose($fp);

}
$fp = fopen("../_cache/stats_month/$cache_file", "w") or die("Unable to open file!");
fwrite($fp, $inp_header);
fwrite($fp, $inp_data);
fwrite($fp, $inp_footer);
fclose($fp);





/*- Test ------------------------------------------------------------------------------------- */
$inp_test="<!DOCTYPE html>
<html>
  <head>
    <meta charset=\"UTF-8\" />
    <title>visits_per_day for $editor_language</title>
    <link rel=\"stylesheet\" href=\"index.css\" />
</head>
<body>
<h1>visits_per_day for $editor_language</h1>

<div id=\"chartdiv_visits_per_day\" style=\"width: 100%;height: 400px;\"></div>

<script src=\"../../_admin/_javascripts/amcharts/index.js\"></script>
<script src=\"../../_admin/_javascripts/amcharts/xy.js\"></script>
<script src=\"../../_admin/_javascripts/amcharts/themes/Animated.js\"></script>
<script src=\"$cache_file\"></script>


</body>
</html>";


$fp = fopen("../_cache/stats_month/$cache_file.html", "w") or die("Unable to open file!");
fwrite($fp, $inp_test);
fclose($fp);

?>