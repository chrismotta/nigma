$(document).ready(function(){
	hideLoading();
	$(".showLoadingMenuItem, .showLoadingMenu li").click( showLoadingNewPage );
	$(".showLoading").click( showLoading );

	var easterCode = "";
	$( "body" ).keypress(function(e) {
		var keyCode = e.keyCode || e.which;

		easterCode += keyCode;
	    //* console.log( "Handler: "+easterCode );

	    // imageEgg('1109711810510097100', 'http://lh3.ggpht.com/-ONo0KPLOXN8/UJXkEBFppKI/AAAAAAAAV1g/p-l8Lo5__e0/papa1_thumb.gif?imgmax=800', 100);
	    // imageEgg('10511510497112112101110105110103', 'http://static1.gamespot.com/uploads/original/1188/11888561/2562405-6086807432-Happe.gif', 200);
	    // imageEgg('101109105108105111', 'http://www.100pies.net/Gifs/Nombres-Animados/E/Emilio/Emilio-17.gif', 100);
	    // imageEgg('97118101110103101114115', 'http://img4.wikia.nocookie.net/__cb20140718002257/marvel/es/images/8/80/Ultron_Render.png', 600);

	    //* styleEgg('102108105112', 'body', {'transform': 'scale(-1, 1)'});
	    // styleEgg('10397116111', 'body', {'background-image': 'url(http://i1-news.softpedia-static.com/images/extra/LINUX/large/ubuntu1204ltswallpapers-large_009.jpg)'});
	    //http://i1-news.softpedia-static.com/images/extra/LINUX/large/ubuntu1204ltswallpapers-large_009.jpg

		if(easterCode.indexOf('11411111697114') != -1) {
	    	$("body").animateRotate(720, 4000);
		}
	});

	$.fn.animateRotate = function(angle, duration) {
	  return this.each(function() {
	    $(this).animate({deg: angle}, {
	      duration: duration,
	      step: function(now) { $(this).css({ transform: 'rotate(' + now + 'deg)' }); },
	    });
	  });
	};

	if(window['Highcharts'] != undefined)
		chartsTheme();

	$('tr:has(td#date)').addClass('merged-td');
});

function imageEgg(word, image, height){
	if(easterCode.indexOf(word) != -1) {
		$('.easter-footer').html('<img src="'+image+'" style="height:'+height+'px" />');
		$('.easter-footer').show();
		easterCode = "";
    }
}
function styleEgg(word, selector, styles){
	if(easterCode.indexOf(word) != -1){
		$(selector).css(styles);
		easterCode = "";
	}
}

function chartsTheme(){

	// Load the fonts
	Highcharts.createElement('link', {
	   // href: '//fonts.googleapis.com/css?family=Unica+One',
	   rel: 'stylesheet',
	   type: 'text/css'
	}, null, document.getElementsByTagName('head')[0]);

	Highcharts.theme = {
	   colors: ["#dfa745", "#e92b41", "#0d8087", "#2f3148", "#aa78ae", "#51a630", "#eeaaee",
	      "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
	   // chart: {
	   //    backgroundColor: {
	   //       linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
	   //       stops: [
	   //          [0, '#2a2a2b'],
	   //          [1, '#3e3e40']
	   //       ]
	   //    },
	   //    style: {
	   //       fontFamily: "'Unica One', sans-serif"
	   //    },
	   //    plotBorderColor: '#606063'
	   // },
	   // title: {
	   //    style: {
	   //       color: '#E0E0E3',
	   //       textTransform: 'uppercase',
	   //       fontSize: '20px'
	   //    }
	   // },
	   // subtitle: {
	   //    style: {
	   //       color: '#E0E0E3',
	   //       textTransform: 'uppercase'
	   //    }
	   // },
	   // xAxis: {
	   //    gridLineColor: '#707073',
	   //    labels: {
	   //       style: {
	   //          color: '#E0E0E3'
	   //       }
	   //    },
	   //    lineColor: '#707073',
	   //    minorGridLineColor: '#505053',
	   //    tickColor: '#707073',
	   //    title: {
	   //       style: {
	   //          color: '#A0A0A3'

	   //       }
	   //    }
	   // },
	   // yAxis: {
	   //    gridLineColor: '#707073',
	   //    labels: {
	   //       style: {
	   //          color: '#E0E0E3'
	   //       }
	   //    },
	   //    lineColor: '#707073',
	   //    minorGridLineColor: '#505053',
	   //    tickColor: '#707073',
	   //    tickWidth: 1,
	   //    title: {
	   //       style: {
	   //          color: '#A0A0A3'
	   //       }
	   //    }
	   // },
	   // tooltip: {
	   //    backgroundColor: 'rgba(0, 0, 0, 0.85)',
	   //    style: {
	   //       color: '#F0F0F0'
	   //    }
	   // },
	   // plotOptions: {
	   //    series: {
	   //       dataLabels: {
	   //          color: '#B0B0B3'
	   //       },
	   //       marker: {
	   //          lineColor: '#333'
	   //       }
	   //    },
	   //    boxplot: {
	   //       fillColor: '#505053'
	   //    },
	   //    candlestick: {
	   //       lineColor: 'white'
	   //    },
	   //    errorbar: {
	   //       color: 'white'
	   //    }
	   // },
	   // legend: {
	   //    itemStyle: {
	   //       color: '#E0E0E3'
	   //    },
	   //    itemHoverStyle: {
	   //       color: '#FFF'
	   //    },
	   //    itemHiddenStyle: {
	   //       color: '#606063'
	   //    }
	   // },
	   // credits: {
	   //    style: {
	   //       color: '#666'
	   //    }
	   // },
	   // labels: {
	   //    style: {
	   //       color: '#707073'
	   //    }
	   // },

	   // drilldown: {
	   //    activeAxisLabelStyle: {
	   //       color: '#F0F0F3'
	   //    },
	   //    activeDataLabelStyle: {
	   //       color: '#F0F0F3'
	   //    }
	   // },

	   // navigation: {
	   //    buttonOptions: {
	   //       symbolStroke: '#DDDDDD',
	   //       theme: {
	   //          fill: '#505053'
	   //       }
	   //    }
	   // },

	   // // scroll charts
	   // rangeSelector: {
	   //    buttonTheme: {
	   //       fill: '#505053',
	   //       stroke: '#000000',
	   //       style: {
	   //          color: '#CCC'
	   //       },
	   //       states: {
	   //          hover: {
	   //             fill: '#707073',
	   //             stroke: '#000000',
	   //             style: {
	   //                color: 'white'
	   //             }
	   //          },
	   //          select: {
	   //             fill: '#000003',
	   //             stroke: '#000000',
	   //             style: {
	   //                color: 'white'
	   //             }
	   //          }
	   //       }
	   //    },
	   //    inputBoxBorderColor: '#505053',
	   //    inputStyle: {
	   //       backgroundColor: '#333',
	   //       color: 'silver'
	   //    },
	   //    labelStyle: {
	   //       color: 'silver'
	   //    }
	   // },

	   // navigator: {
	   //    handles: {
	   //       backgroundColor: '#666',
	   //       borderColor: '#AAA'
	   //    },
	   //    outlineColor: '#CCC',
	   //    maskFill: 'rgba(255,255,255,0.1)',
	   //    series: {
	   //       color: '#7798BF',
	   //       lineColor: '#A6C7ED'
	   //    },
	   //    xAxis: {
	   //       gridLineColor: '#505053'
	   //    }
	   // },

	   // scrollbar: {
	   //    barBackgroundColor: '#808083',
	   //    barBorderColor: '#808083',
	   //    buttonArrowColor: '#CCC',
	   //    buttonBackgroundColor: '#606063',
	   //    buttonBorderColor: '#606063',
	   //    rifleColor: '#FFF',
	   //    trackBackgroundColor: '#404043',
	   //    trackBorderColor: '#404043'
	   // },

	   // // special colors for some of the
	   // legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
	   // background2: '#505053',
	   // dataLabelsColor: '#B0B0B3',
	   // textColor: '#C0C0C0',
	   // contrastTextColor: '#F0F0F3',
	   // maskColor: 'rgba(255,255,255,0.3)'
	};

	// Apply the theme
	Highcharts.setOptions(Highcharts.theme);
}


function hideLoading() {
	$('#page').css('display', 'block');
	$('#loader').css('display', 'none');
}

function showLoadingNewPage() {
	// anti functional
	// $('#page').css('display', 'none');
	// $('#loader').css('display', 'block');
}

function showLoading() {
	$('#loader').css('display', 'block');
}

var selectionChangedDailyReport = function(id) {
	var rowId = $.fn.yiiGridView.getSelection(id);

	// if rowId is null exit.
	if ( rowId.length == 0 ) {
		return;
	}

	var c_id   = $( "#" + id + " table tbody .selected" ).attr("data-row-c-id");
	var net_id = $( "#" + id + " table tbody .selected" ).attr("data-row-net-id");

	// parsing data into corresponding format "Y-m-d"
	var tmp    = $( "#dateEnd").attr('value');
	var y = tmp.substring(tmp.lastIndexOf("-") + 1);
	var m = tmp.substring(tmp.indexOf("-") + 1, tmp.lastIndexOf("-"));
	var d = tmp.substring(0, tmp.indexOf("-"));
	var endDate = y + "-" + m + "-" + d;

	var tmp    = $( "#dateStart").attr('value');
	var y = tmp.substring(tmp.lastIndexOf("-") + 1);
	var m = tmp.substring(tmp.indexOf("-") + 1, tmp.lastIndexOf("-"));
	var d = tmp.substring(0, tmp.indexOf("-"));
	var startDate = y + "-" + m + "-" + d;

	var chart = Highcharts.charts[0];
	chart.showLoading("Loading data from server..");
	$.post(
		"graphic",
		"c_id=" + c_id + "&net_id=" + net_id + "&endDate=" + endDate + "&startDate=" + startDate,
		function(data) {
				var chart = Highcharts.charts[0];
				chart.series[0].setData(data['imp']);	// Impressions
				chart.series[1].setData(data['click']);	// Clicks
				chart.series[2].setData(data['conv']);	// Conv
				chart.series[3].setData(data['revenue']);	// Clicks
				chart.series[4].setData(data['spend']);	// Spend
				chart.series[5].setData(data['profit']);	// Clicks
				chart.xAxis[0].setCategories(data['date']);	// xAxis
				chart.redraw();
				chart.hideLoading();
			},
		"json"
		)
}

var selectionChangedTraffic = function(id) {
	var c_id = $.fn.yiiGridView.getSelection(id);
	// if rowId is null exit.
	if ( c_id.length == 0 ) {
		return;
	}
	//var c_id   = $( "#" + id + " table tbody .selected" ).attr("data-row-id");

	// parsing data into corresponding format "Y-m-d"
	var tmp    = $( "#dateEnd").attr('value');
	var y = tmp.substring(tmp.lastIndexOf("-") + 1);
	var m = tmp.substring(tmp.indexOf("-") + 1, tmp.lastIndexOf("-"));
	var d = tmp.substring(0, tmp.indexOf("-"));
	var endDate = y + "-" + m + "-" + d;

	var tmp    = $( "#dateStart").attr('value');
	var y = tmp.substring(tmp.lastIndexOf("-") + 1);
	var m = tmp.substring(tmp.indexOf("-") + 1, tmp.lastIndexOf("-"));
	var d = tmp.substring(0, tmp.indexOf("-"));
	var startDate = y + "-" + m + "-" + d;

	var chart = Highcharts.charts[0];
	chart.showLoading("Loading data from server..");
	$.post(
		"graphic",
		"c_id=" + c_id + "&endDate=" + endDate + "&startDate=" + startDate,
		function(data) {
				var chart = Highcharts.charts[0];
				chart.series[1].setData(data['conversions']);	// Conv
				chart.series[0].setData(data['clics']);	// Clicks
				chart.xAxis[0].setCategories(data['dates']);	// xAxis
				chart.redraw();
				chart.hideLoading();
			},
		"json"
		)
}