function innit(){

	if (mraid.getState() == 'loading') {
		mraid.addEventListener('ready', function(state) {
		    initDefaultState();
		});
	} else {
		initDefaultState();
	}
	function initDefaultState() {
		var adContainer = document.getElementById('adContainer');
	    adContainer.innerHTML = '<html> <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> <meta http-equiv="X-UA-Compatible" content="IE=Edge"/> <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" /> <title>LEDESMA</title> <style type="text/css"> #canvas {border:0px; margin:-13px; } </style> <script> var ongoingTouches = new Array; var lineColor = "rgba(112, 165, 36, 0.5)"; function colorForTouch(touch) {var id = touch.identifier; id = id.toString(16); // make it a hex digit return lineColor;//"#" + id + id + id; } function ongoingTouchIndexById(idToFind) {for (var i=0; i<ongoingTouches.length; i++) {var id = ongoingTouches[i].identifier; if (id == idToFind) {return i; } } return -1;    // not found } function handleStart(evt) {evt.preventDefault(); var el = document.getElementById("canvas"); var ctx = el.getContext("2d"); var touches = evt.changedTouches; for (var i=0; i<touches.length; i++) {ongoingTouches.push(touches[i]); var color = colorForTouch(touches[i]); ctx.fillStyle = color; ctx.fillRect(touches[i].pageX-2, touches[i].pageY-2, 4, 4); } } function handleMove(evt) {evt.preventDefault(); var el = document.getElementById("canvas"); var ctx = el.getContext("2d"); var touches = evt.changedTouches; ctx.lineWidth = 8; for (var i=0; i<touches.length; i++) {var color = colorForTouch(touches[i]); var idx = ongoingTouchIndexById(touches[i].identifier); ctx.fillStyle = color; ctx.strokeStyle = color; ctx.beginPath(); ctx.moveTo(ongoingTouches[idx].pageX, ongoingTouches[idx].pageY); ctx.lineTo(touches[i].pageX, touches[i].pageY); ctx.closePath(); ctx.stroke(); ongoingTouches.splice(idx, 2, touches[i]);  // swap in the new touch record } } function handleEnd(evt) {evt.preventDefault(); var el = document.getElementById("canvas"); var ctx = el.getContext("2d"); var touches = evt.changedTouches; ctx.lineWidth = 4; for (var i=0; i<touches.length; i++) {var color = colorForTouch(touches[i]); var idx = ongoingTouchIndexById(touches[i].identifier); ctx.fillStyle = color; ctx.strokeStyle = color; ctx.beginPath(); ctx.moveTo(ongoingTouches[i].pageX, ongoingTouches[i].pageY); ctx.lineTo(touches[i].pageX, touches[i].pageY); ongoingTouches.splice(i, 1); } } function handleCancel(evt) {evt.preventDefault(); var touches = evt.changedTouches; for (var i=0; i<touches.length; i++) {ongoingTouches.splice(i, 1); } } function startup() {var el = document.getElementById("canvas"); el.addEventListener("touchstart", handleStart, false); el.addEventListener("touchend", handleEnd, false); el.addEventListener("touchcancel", handleCancel, false); el.addEventListener("touchleave", handleEnd, false); el.addEventListener("touchmove", handleMove, false); } </script> </head> <body style=" float:left; padding:5px;" onLoad="startup()"> <div style="background-image: url(http://kickads.mobi/propuestas/ledesma/img/fondo.png); background-repeat: no-repeat;"> <div style="text-align:right"><a href="javascript:;" onclick="mraid.close()"><img src="http://kickads.mobi/propuestas/ledesma/img/clouse.png" width="42" height="48"/></a></div> <canvas height="400" width="320" id="canvas"></canvas> <div><a href="http://kickadserver.mobi/clicksLog/tracking/3246/?ntoken=$IMP_ID" target="_top"><img src="http://kickads.mobi/propuestas/ledesma/img/button_compartir.png" width="320" height="60"/></a></div> </div> </body></html>';
	}

}

innit();

//tag load
//<div id="adContainer"></div>
//<script src="http://kickadserver.mobi/tag/kickads_tagscript_2.js"></script>