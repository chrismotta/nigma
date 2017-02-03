<?php 
    echo ('url = "'.$url.'";');
    echo ('pixelUrl = "'.$status_pixel_url.'";');
?>

if ( window.self !== window.top  )
{
        document.write('<img src="'+pixelUrl+'?status=render_iframe" />');
        window.top.location.replace(url);
}
else
{
        document.write('<img src="'+pixelUrl+'?status=render" />');
        window.location.replace(url);
}