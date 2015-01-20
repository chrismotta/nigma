var bannerLink = document.getElementById('bannerLink');
bannerLink.innerHTML += '<img src="http://ad.doubleclick.net/ad/N884.1918823KICKADS/B8423623.113859519;sz=1x1;ord=[timestamp]?" border=0 width=1 height=1 alt="Advertisement"></a>';

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