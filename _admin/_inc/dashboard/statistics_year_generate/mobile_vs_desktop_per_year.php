<?php
/**
*
* File: _admin/_inc/_dasboard/statistics_year_generate/mobile_vs_desktop_per_year.php
* Version 1
* Date 00:47 02.04.2022
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
var root = am5.Root.new(\"chartdiv_mobile_vs_desktop_per_year\");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
var chart = root.container.children.push(am5percent.PieChart.new(root, {
  layout: root.verticalLayout
}));


// Create series
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
var series = chart.series.push(am5percent.PieSeries.new(root, {
  valueField: \"value\",
  categoryField: \"category\"
}));

";

/*- Visits per year -------------------------------------------------------------------------- */
$inp_data = "// Set data
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
series.data.setAll([";


$inp_data = $inp_data  . "{ value: $get_current_stats_visit_per_year_unique_desktop, category: \"Desktop\" },
{ value: $get_current_stats_visit_per_year_unique_mobile, category: \"Mobile\" },";



$inp_data = $inp_data . "]);
";

/*- Footer ------------------------------------------------------------------------------------ */
$inp_footer = "


// Play initial series animation
// https://www.amcharts.com/docs/v5/concepts/animations/#Animation_of_series
series.appear(1000, 100);";



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
    <title>mobile_vs_desktop_per_year</title>
    <link rel=\"stylesheet\" href=\"index.css\" />
</head>
<body>
    <div id=\"chartdiv_mobile_vs_desktop_per_year\" style=\"width: 100%;height: 80vh;\"></div>

<script src=\"../../_admin/_javascripts/amcharts/index.js\"></script>
<script src=\"../../_admin/_javascripts/amcharts/percent.js\"></script>
<script src=\"../../_admin/_javascripts/amcharts/themes/Animated.js\"></script>
<script src=\"$cache_file\"></script>
  </body>
</html>";


$fp = fopen("../_cache/stats_year/$cache_file.html", "w") or die("Unable to open file!");
fwrite($fp, $inp_test);
fclose($fp);

?>