<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <title>Promo</title>

    <?php 
    $baseUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($baseUrl.'/css/landing.css');
    ?>

    <link rel="shortcut icon" href="<?php echo $baseUrl ?>/img/favicon.ico" />
</head>

<body style="padding:0px">
    <?php echo $content; ?>
</body>
</html>
