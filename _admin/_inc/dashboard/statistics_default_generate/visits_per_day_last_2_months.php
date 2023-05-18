<?php
/**
*
* File: _admin/_inc/dashboard/visits_per_day_last_2_months
* Version 2
* Copyright (c) 2021-2023 Sindre Andre Ditlefsen
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
var root = am5.Root.new(\"chartdiv_visits_per_day\");


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
  layout: root.verticalLayout, // Legend at bottom
  maxTooltipDistance: 0
}));

chart.get(\"colors\").set(\"step\", 3);


// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursor = chart.set(\"cursor\", am5xy.XYCursor.new(root, {}));
cursor.lineY.set(\"visible\", false);


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
  maxDeviation: 0.3,
  baseInterval: {
    timeUnit: \"day\",
    count: 1
  },
  renderer: am5xy.AxisRendererX.new(root, {}),
  tooltip: am5.Tooltip.new(root, {})
}));

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0.3,
  renderer: am5xy.AxisRendererY.new(root, {})
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.LineSeries.new(root, {
  name: \"This month\",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: \"value1\",
  valueXField: \"date\"
}));
series.strokes.template.setAll({
  strokeWidth: 2
});

var tooltip = series.set(\"tooltip\", am5.Tooltip.new(root, {}));
tooltip.get(\"background\").set(\"fillOpacity\", 0.5);
tooltip.label.set(\"text\", \"{text1}: {valueY}\\n{text2}: {value2}\");

var series2 = chart.series.push(am5xy.LineSeries.new(root, {
  name: \"Last month\",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: \"value2\",
  valueXField: \"date\"
}));
series2.strokes.template.setAll({
  strokeDasharray: [2, 2],
  strokeWidth: 2
});



// Set date fields
// https://www.amcharts.com/docs/v5/concepts/data/#Parsing_dates
root.dateFormatter.setAll({
  dateFormat: \"yyyy-MM-dd\",
  dateFields: [\"valueX\"]
});


// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legend = chart.children.push(am5.Legend.new(root, {}));
legend.data.setAll(chart.series.values);

";

/*- Visits per day -------------------------------------------------------------------------- */
$inp_data = "// Set data
var data = [";




$loop_end = new DateTime();
$loop_end->modify('+1 day'); // include today

$loop_begin = new DateTime();
$loop_begin->modify('-1 month'); // 31 days ago

$datetime_last = new DateTime();
$datetime_last->modify('-2 month'); // 31 days ago

$comma_count = 0;
for($i = $loop_begin; $i <= $loop_end; $i->modify('+1 day')){

	// This day
	$this_year  = $i->format("Y");
	$this_month = $i->format("n"); // Numeric representation of a month, without leading zeros
	$this_day   = $i->format("j");

	// Last (30 days ago)
	$last_year    = $datetime_last->format('Y');
	$last_month   = $datetime_last->format('m');
	$last_day     = $datetime_last->format('d');
	$datetime_last->modify('+1 day'); // next day

	// Echo
	/*
	echo"
	<p>
	$this_year $this_month $this_day<br />
	$last_year $last_month $last_day<br />
	</p>
	";
	*/


	// Fetch day this month
	$get_this_stats_visit_per_day_human_unique = "";
	$get_last_stats_visit_per_day_day_three = "";
	$visit_per_day_human_unique_this_year_month_for_day = 0;
	$query = "SELECT stats_visit_per_day_id, stats_visit_per_day_day, stats_visit_per_day_day_three, stats_visit_per_day_day_single, stats_visit_per_day_month, stats_visit_per_day_month_short, stats_visit_per_day_human_unique FROM $t_stats_visists_per_day WHERE stats_visit_per_day_day=$this_day AND stats_visit_per_day_month=$this_month AND stats_visit_per_day_year=$this_year";
	$result = $mysqli->query($query);
	while($row = $result->fetch_row()) {
		list($get_this_stats_visit_per_day_id, $get_this_stats_visit_per_day_day, $get_this_stats_visit_per_day_day_three, $get_this_stats_visit_per_day_day_single, $get_this_stats_visit_per_day_month, $get_this_stats_visit_per_day_month_short, $get_this_stats_visit_per_day_human_unique) = $row;
		$visit_per_day_human_unique_this_year_month_for_day = $visit_per_day_human_unique_this_year_month_for_day + $get_this_stats_visit_per_day_human_unique;
	}




	// Fetch day last month
	$visit_per_day_human_unique_last_year_month_for_day = 0;
	$query = "SELECT stats_visit_per_day_id, stats_visit_per_day_day, stats_visit_per_day_day_three, stats_visit_per_day_day_single, stats_visit_per_day_month, stats_visit_per_day_month_short, stats_visit_per_day_human_unique FROM $t_stats_visists_per_day WHERE stats_visit_per_day_day=$last_day AND stats_visit_per_day_month=$last_month AND stats_visit_per_day_year=$last_year";
	$result = $mysqli->query($query);
	while($row = $result->fetch_row()) {
		list($get_last_stats_visit_per_day_id, $get_last_stats_visit_per_day_day, $get_last_stats_visit_per_day_day_three, $get_last_stats_visit_per_day_day_single, $get_last_stats_visit_per_day_month, $get_last_stats_visit_per_day_month_short, $get_last_stats_visit_per_day_human_unique) = $row;
		$visit_per_day_human_unique_last_year_month_for_day = $visit_per_day_human_unique_last_year_month_for_day + $get_last_stats_visit_per_day_human_unique;
	}


	if($get_this_stats_visit_per_day_human_unique != ""){
		if($comma_count > 0){
			$inp_data = $inp_data . ",";
		}
		$inp_data = $inp_data . "{
			  date: new Date($this_year, $this_month, $this_day).getTime(),
			  value1: $visit_per_day_human_unique_this_year_month_for_day,
			  value2: $visit_per_day_human_unique_last_year_month_for_day,
			  text1: \"$get_this_stats_visit_per_day_day_three $get_this_stats_visit_per_day_day $get_this_stats_visit_per_day_month_short\",";

		if($get_last_stats_visit_per_day_day_three == ""){
			$inp_data = $inp_data . "
			  text2: \"30 days ago\",";
		}
		else{
			$inp_data = $inp_data . "
			  text2: \"$get_last_stats_visit_per_day_day_three $get_last_stats_visit_per_day_day $get_last_stats_visit_per_day_month_short\",";
		}
		
		$inp_data = $inp_data . "
			  previousDate: \"$last_year-$last_month-$last_day\"
		}";
		$comma_count++;
	}

} // for


$inp_data = $inp_data . "]";

/*- Footer ------------------------------------------------------------------------------------ */
$inp_footer = "


series.data.setAll(data);
series2.data.setAll(data);


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/#Forcing_appearance_animation
series.appear(1000);
series2.appear(1000);
chart.appear(1000, 100);";



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
$fp = fopen("../_cache/stats_default/visits_per_day_last_2_months_$configSecurityCodeSav.js", "w") or die("Unable to open file!");
fwrite($fp, $inp_header);
fwrite($fp, $inp_data);
fwrite($fp, $inp_footer);
fclose($fp);


/*- Test ------------------------------------------------------------------------------------- */
$inp_test="<!DOCTYPE html>
<html>
  <head>
    <meta charset=\"UTF-8\" />
    <title>visits_per_day_last_2_months</title>
    <link rel=\"stylesheet\" href=\"index.css\" />
</head>
<body>
    <div id=\"chartdiv_visits_per_day\" style=\"width: 100%;height: 80vh;\"></div>

<script src=\"../_admin/_javascripts/amcharts/index.js\"></script>
<script src=\"../_admin/_javascripts/amcharts/xy.js\"></script>
<script src=\"../_admin/_javascripts/amcharts/themes/Animated.js\"></script>
<script src=\"visits_per_day_last_2_months_$configSecurityCodeSav.js\"></script>
  </body>
</html>";

$fp = fopen("../_cache/stats_default/visits_per_day_last_2_months_$configSecurityCodeSav.html", "w") or die("Unable to open file!");
fwrite($fp, $inp_test);
fclose($fp);

?>