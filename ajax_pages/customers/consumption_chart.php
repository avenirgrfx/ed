<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$building_id=$_GET['building_id'];
$parent_id=$_GET['parent_id'];
$strType=$_GET['type'];
$month = isset($_GET['month']) ? $_GET['month'] : "";
$year = isset($_GET['year']) ? $_GET['year'] : "";
?>
<script type="text/javascript">
$(function () {
    $('#CircualChart_graph').circliful();
    $.getJSON("<?php echo URL ?>ajax_pages/customers/consumption_chart_json.php?building_id=<?=$building_id?>&type=<?=$strType?>&month=<?=$month?>&year=<?=$year?>", function (data) {

        // create the chart
//        Highcharts.setOptions({
//            global: {
//                timezoneOffset: 240,
//            }
//        }); 
        
        $('#container_consumption_chart').highcharts('StockChart', {


            /*title: {
                text: 'AAPL stock price by minute'
            },

            subtitle: {
                text: 'Using ordinal X axis'
            },*/

            xAxis: {
                gapGridLineWidth: 0
            },
			
			yAxis: {
                title: {
                    text: 'MMBTU'
                },
                offset: 0
            },
			

            rangeSelector : {
                buttons : [{
                    type : 'hour',
                    count : 1,
                    text : '1h'
                }, {
                    type : 'day',
                    count : 1,
                    text : '1D'
                }, {
                    type : 'week',
                    count : 1,
                    text : '1W'    
                }, {
                    type : 'all',
                    count : 1,
                    text : 'All'
                }],
                selected : 1,
                inputEnabled : false
            },

            series : [{
                name : 'MMBTU',
                type: 'area',
                data : data,
                gapSize: 5,
                tooltip: {
                    valueDecimals: 6
                },
                fillColor : {
                    linearGradient : {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops : [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                threshold: null
            }]
        });
        $('#CircualChart_graph').hide();
    });
});
</script>
<div id="CircualChart_graph" style="margin:0px auto;" data-dimension="130" data-text="Loading..." data-info="" data-width="10" data-fontsize="14" data-percent="99" data-fgcolor="#61a9dc" data-bgcolor="#eee" data-fill="#ddd"></div>
<div id="container_consumption_chart" style="border:1px solid #CCCCCC; width:535px; height:250px;"></div>