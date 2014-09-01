$(document).ready(function(){

	//$('.datepicker').datepicker();

});

var selectionChangedDailyReport = function(id) {
	var rowId = $.fn.yiiGridView.getSelection(id);

	// if rowId is null exit.
	if ( rowId.length == 0 ) {
		return;
	}

	var c_id   = $( "#" + id + " table tbody .selected" ).attr("data-row-c-id");
	var net_id = $( "#" + id + " table tbody .selected" ).attr("data-row-net-id");

	// parsing data into corresponding format "Y-m-d"
	var tmp    = $( "#" + id + " table tbody .selected" ).children(".date").text();
	var y = tmp.substring(tmp.lastIndexOf("-") + 1);
	var m = tmp.substring(tmp.indexOf("-") + 1, tmp.lastIndexOf("-"));
	var d = tmp.substring(0, tmp.indexOf("-"));
	var endDate = y + "-" + m + "-" + d;

	var chart = Highcharts.charts[0];
	chart.showLoading("Loading data from server..");
	$.post(
		"graphic",
		"c_id=" + c_id + "&net_id=" + net_id + "&endDate=" + endDate,
		function(data) {
				var chart = Highcharts.charts[0];
				chart.series[0].setData(data[0]);	// Spend
				chart.series[1].setData(data[1]);	// Conv
				chart.series[2].setData(data[2]);	// Impressions
				chart.series[3].setData(data[3]);	// Clicks
				chart.xAxis[0].setCategories(data[4]);	// xAxis
				chart.redraw();
				chart.hideLoading();
			},
		"json"
		)
}