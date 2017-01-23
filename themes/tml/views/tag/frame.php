<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript">
	</script>
	<style type="text/css">
		body {
	        margin: 0px;
	        padding: 0px;
	    }
	    /*
	    div#root {
	        position: fixed;
	        width: 100%;
	        height: 100%;
	    }
	    */
		iframe {
	        display: block;
	        width: 800px;
	        height: 600px;
	        border: none;
	    }
	</style>
</head>
<body>
    <div id="root">
    	test<hr>
        <iframe src="<?php echo $url; ?>" sandbox=" allow-same-origin allow-scripts">
            Your browser does not support inline frames.
        </iframe>
    </div>
	<?php 
	// echo 'Loading... ';
	// echo $url;
	// echo '<img src="https://www.planwallpaper.com/static/images/ZhGEqAP.jpg">';
	?>
</body>
</html>