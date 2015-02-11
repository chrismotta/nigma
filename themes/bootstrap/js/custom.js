$(document).ready(function(){
	hideLoading();
	$(".showLoadingMenuItem, .showLoadingMenu li").click( showLoadingNewPage );
	$(".showLoading").click( showLoading );

	$("#clients-grid td[name=nameTD]").parent().css("border-top","2px solid #ccc");

	var easterCode = "";
	$( "body" ).keypress(function(e) {
		var keyCode = e.keyCode || e.which;

		easterCode += keyCode;
	    console.log( "Handler: "+easterCode );

	    imageEgg('1109711810510097100', 'http://lh3.ggpht.com/-ONo0KPLOXN8/UJXkEBFppKI/AAAAAAAAV1g/p-l8Lo5__e0/papa1_thumb.gif?imgmax=800', 100);
	    imageEgg('10511510497112112101110105110103', 'http://static1.gamespot.com/uploads/original/1188/11888561/2562405-6086807432-Happe.gif', 200);
	    imageEgg('101109105108105111', 'http://www.100pies.net/Gifs/Nombres-Animados/E/Emilio/Emilio-17.gif', 100);
	    imageEgg('97118101110103101114115', 'http://img4.wikia.nocookie.net/__cb20140718002257/marvel/es/images/8/80/Ultron_Render.png', 600);

	    styleEgg('102108105112', 'body', {'transform': 'scale(-1, 1)'});
	    styleEgg('10397116111', 'body', {'background-image': 'url(http://i1-news.softpedia-static.com/images/extra/LINUX/large/ubuntu1204ltswallpapers-large_009.jpg)'});
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
});

function hideLoading() {
	$('#page').css('display', 'block');
	$('#loader').css('display', 'none');
}

function showLoadingNewPage() {
	$('#page').css('display', 'none');
	$('#loader').css('display', 'block');
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