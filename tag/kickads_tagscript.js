var bannerLink = document.getElementById('bannerLink');
bannerLink.innerHTML += '<img src="" border=0 width=1 height=1 alt="Advertisement" />';

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
	mraid.expand("/propuestas/dermaglos/index4.html");
	console.log("= "+mraid.getState());
}

function bigClose(){
	mraid.close();
	console.log("= "+mraid.getState());
	return false;
}