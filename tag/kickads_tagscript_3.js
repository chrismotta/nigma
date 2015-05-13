if (mraid.getState() == 'loading') {
	mraid.addEventListener('ready', function(state) {
	    initDefaultState();
	});
} else {
	initDefaultState();
}

function initDefaultState() {
    mraid.setExpandProperties({
	    width : 320,
	    height : 480,
	    useCustomClose:true,
	    isModal:false
    });
}

function clickBanner(){
	mraid.expand("/propuestas/ledesma/index.html");
	console.log("= "+mraid.getState());
}

function bigClose(){
	mraid.close();
	console.log("= "+mraid.getState());
	return false;
}