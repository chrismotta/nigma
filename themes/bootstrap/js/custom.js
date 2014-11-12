$(document).ready(function(){
	hideLoading();
	$(".showLoadingMenuItem, .showLoadingMenu li").click( showLoadingNewPage );
	$(".showLoading").click( showLoading );

	var easterCode = "";
	$( "body" ).keypress(function(e) {
		var keyCode = e.keyCode || e.which;
		if(keyCode == "110") easterCode = "";
		easterCode += keyCode;
	    //console.log( "Handler: "+easterCode );

	    if(easterCode=='1109711810510097100'){
			$('.easter-footer').show();
	    }
	});
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