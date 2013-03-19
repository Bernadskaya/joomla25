/**
 * @version		$Id: script.jquery.js 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

var $K2mart = jQuery.noConflict();

var k2martChart;
var k2martChartType;
function renderK2martChart() {
	if($K2mart('#k2martChart').width()<1)
	{
		$K2mart('#k2martChart').css('width', $K2mart('#k2martChart').closest('.panel').width()-2+'px');
	}
	if( typeof (k2martChart) != 'undefined') {
		k2martChart.destroy();
	}
	if(k2martChartType == 'products') {
		options = k2martProductsChartOptions;
	} else {
		options = k2martSalesChartOptions;
	}
	k2martChart = new Highcharts.Chart(options);
}


$K2mart(document).ready(function() {
	k2martSalesChartOptions = {
		chart : {
			renderTo : 'k2martChart',
			zoomType : 'x',
			borderWidth : 0,
			borderColor : '',
			backgroundColor : '',
			style : {
				fontFamily : 'Arial,Helvetica,sans-serif'
			},
			spacingBottom: 50 
		},
		title : {
			text : '',
			style : {
				fontFamily : 'Arial,Helvetica,sans-serif',
				fontWeight : 'bold'
			},
			y : 30
		},
		subtitle : {
			text : '',
			align : 'right',
			verticalAlign: 'bottom',
			style : {
				fontFamily : 'Arial,Helvetica,sans-serif',
				fontStyle : 'italic'
			}
		},
		xAxis : {
			type : 'datetime',
			minRange : 7 * 24 * 3600000,
			title : {
				text : null
			},
			labels : {
				style : {
					fontFamily : 'Arial,Helvetica,sans-serif'
				}
			}

		},
		yAxis : {
			title : {
				text : '',
				style : {
					fontFamily : 'Arial,Helvetica,sans-serif'
				}
			},
			allowDecimals : false
		},
		tooltip : {
			formatter : function() {
				return Highcharts.dateFormat('%B %e %Y', this.x) + ':' + '<b>' + this.y + '</b>';
			}
		},
		legend : {
			enabled : false
		},
		credits : {
			enabled : false
		},
		plotOptions : {
			areaspline : {
				lineWidth : 1,
				marker : {
					enabled : false,
					states : {
						hover : {
							enabled : true,
							radius : 5
						}
					}
				},
				shadow : false,
				states : {
					hover : {
						lineWidth : 1
					}
				}
			}
		},
		series : [{
			type : 'areaspline',
			pointInterval : 24 * 3600 * 1000,
			pointStart : null,
			data : []
		}]
	};
	k2martProductsChartOptions = {
		chart : {
			renderTo : 'k2martChart',
			marginLeft : 120,
			borderWidth : 0,
			borderColor : '',
			backgroundColor : '',
			style : {
				fontFamily : 'Arial,Helvetica,sans-serif'
			}
		},
		title : {
			text : '',
			style : {
				fontFamily : 'Arial,Helvetica,sans-serif',
				fontWeight : 'bold'
			},
			y : 30
		},
		xAxis : {
			categories : null,
			title : {
				text : null
			},
			labels : {
				style : {
					fontFamily : 'Arial,Helvetica,sans-serif'
				}
			}
		},
		yAxis : {
			title : {
				text : '',
				style : {
					fontFamily : 'Arial,Helvetica,sans-serif'
				}
			},
			allowDecimals : false
		},
		tooltip : {
			formatter : function() {
				return this.x + ':' + '<b>' + this.y + '</b>';
			}
		},
		legend : {
			enabled : false
		},
		credits : {
			enabled : false
		},
		series : [{
			type : 'bar',
			data : []
		}]
	};

	// Initiate the 3rd option of the first tab
	$K2mart('#k2martChartsNavigation div div:first ul li:nth-child(2)').addClass('active');

	$K2mart.ajax({
		type : 'GET',
		url : $K2mart('#k2martChartsNavigation div ul.simpleTabsNavigation li:first > a').attr('href'),
		dataType : 'script',
		success : function(data) {
			renderK2martChart();
		}
	});

	$K2mart('#k2martChartsNavigation div a').click(function(e) {
		e.preventDefault();
		$K2mart.ajax({
			type : 'GET',
			url : $K2mart(this).attr('href'),
			dataType : 'script',
			success : function(data) {
				renderK2martChart();
			}
		});

		$K2mart('#k2martChartsNavigation div div ul li').removeClass('active');

		$K2mart(this).parent().addClass('active');

		$K2mart('#k2martChartsNavigation li a[href="' + $K2mart(this).attr('href') + '"]').each(function(index, element) {
			$K2mart(element).parent().addClass('active');
		});
	});
});
