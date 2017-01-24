<?php
$pixel = Yii::app()->getBaseUrl() . '/tag/pixel/'.$pixel_id;
?>
<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript">
    <?php 
        echo ('document.write(\'<img src="'.$pixel.'?status=script_exec" />\');');
    ?>
    
	console.log('Loading frame');

function inIframe() {
    try {
        return (window.self !== window.top) ? 1 : 0;
    }
    catch (e) {
        return 1;
    }
}
function ReopenUrlBuilder(baseUrl) {

    this.baseUrl = baseUrl;

    /**
     * Get value of content attribute of meta tag with name attribute = name
     * Fallback to top if possible
     *
     * @return string
     */
    this._getMetaContent = function (name) {
        try {
            var meta = window.top.document.getElementsByTagName('meta');
            for (var i = 0; i < meta.length; i++) {
                if (meta[i].hasAttribute('name') && meta[i].getAttribute('name').toLowerCase() === name) {
                    var info = meta[i].getAttribute('content');
                    var indexToCut = Math.max(info.indexOf(' ', 256), info.indexOf(',', 256));
                    if (indexToCut > 384 || indexToCut < 20) {
                        indexToCut = 256;
                    }
                    return info.substring(0, indexToCut);
                }
            }
        } catch (e) { 
            <?php 
                echo ('document.write(\'<img src="'.$pixel.'?status=meta_content" />\');');
            ?>                   
        }

        return '';
    };

    this._getWidth = function () {
        return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    };

    this._getHeight = function () {
        return window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    };

    this._getTitle = function () {
        var title = document.title;

        if (inIframe()) {
            try {
                title = window.top.document.title;
            }
            catch (e) {
                title = '';
            }
        }

        return title;
    };

    this.build = function () {
            params = '&cbrandom=' + Math.random()
            + '&cbtitle=' + encodeURIComponent(this._getTitle())
            + '&cbiframe=' + inIframe()
            + '&cbWidth=' + this._getWidth()
            + '&cbHeight=' + this._getHeight()
            + '&cbdescription=' + encodeURIComponent(this._getMetaContent('description'))
            + '&cbkeywords=' + encodeURIComponent(this._getMetaContent('keywords'))

            
            params2 = params.replace( /(&)/g, "," );
            <?php 
                
                echo ('document.write(\'<img src="'.$pixel.'?status=build&description=\'+params2+\'" />\');');
            ?>         
            return this.baseUrl + params;
    };
}

    // var builder = new ReopenUrlBuilder("http:\/\/www.superadexchange.com\/a\/display.php?r=1467351&treqn=867143434&runauction=1&crr=1aaa0317c1ac307091a9,AzNHNDJtNHdzRWZvR2cHNDJmBWdHNDJgxmZo92RzQSdy5WatBmYu12RzQyRzQCQyQSc1VXa9267ec492b0308ccb44e");
    var builder = new ReopenUrlBuilder("http:\/\/www.themedialab.co\/mobile\/?");
    var url = builder.build();

    if (true && inIframe()) {
        <?php 
            echo ('document.write(\'<img src="'.$pixel.'?status=iframe_top" />\');');
        ?>            
        window.top.location.replace(url);
                        
                setTimeout(function () {
                <?php 
                    echo ('document.write(\'<img src="'.$pixel.'?status=iframe_timeout" />\');');
                ?>                        
                //window.location.replace("http:\/\/www.superadexchange.com\/ad\/display.php?stamat=j%7C%2CEsnE2t2dT4jeaonU7JkESoheCtnQ6p1eaonP75x.9f2%2CJtMKVa2JCJLXwPPM-w00VcXXmfVBNIqaS8WEvYCOyCHI2dp-9U0wD2AkQ7FKmhhJ1Mf2UlGaO-kAtb6Th4qAsg%2C%2C");
                window.location.replace("http:\/\/www.themedialab.co\/apps\/?");
            }, 2500);
            }
    else {
        <?php 
            echo ('document.write(\'<img src="'.$pixel.'?status=not_iframe" />\');');
        ?>          
        window.location.replace(url);
    }


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
		iframe {
	        display: block;
	        width: 800px;
	        height: 600px;
	        border: none;
	    }
	    */
	</style>
</head>
<body>
    Loading...
        <?php echo ('<img src="'.$pixel.'?status=document_loaded" />' );?>
    <hr>
</body>
</html>