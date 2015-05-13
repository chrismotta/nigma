function innit(){

	if (mraid.getState() == 'loading') {
		mraid.addEventListener('ready', function(state) {
		    initDefaultState();
		});
	} else {
		initDefaultState();
	}
	function initDefaultState() {
		mraid.useCustomClose(true);
		var adContainer = document.getElementById('adContainer');
	    adContainer.innerHTML = '';
	}

}

innit();

//tag load
//<div id="adContainer"></div>