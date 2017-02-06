<?php 
    echo ('url = "'.$url.'";');
    echo ('pixelUrl = "'.$status_pixel_url.'";');
?>

wHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
wWidth  = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

desc = 'height='+wHeight+',width='+wWidth;

if ( window.self !== window.top  )
{
        document.write('<img src="'+pixelUrl+'?status=render_iframe&description='+desc+'" />');
        window.top.location.replace(url);
}
else
{
        document.write('<img src="'+pixelUrl+'?status=render&description='+desc+'" />');
        window.location.replace(url);
}