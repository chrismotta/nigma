if (mraid.getState() == 'loading') {
	mraid.addEventListener('ready', function(state) {
	    initDefaultState();
	});
} else {
	initDefaultState();
}

function initDefaultState() {
    mraid.setExpandProperties({
	    width : 300,
	    height : 500,
	    useCustomClose:true,
	    isModal:false
    });
}

function clickBanner(){
	mraid.expand("http://www.kickads.mobi/propuestas/dermaglos/index4.html");
	console.log("= "+mraid.getState());
}

function bigClose(){
	mraid.close();
	console.log("= "+mraid.getState());
	return false;
}