<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php 
    $baseUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    // custom
    $cs->registerScriptFile($baseUrl.'/js/custom.js');
    $cs->registerCssFile($baseUrl.'/css/styles.css');
    ?>
</head>

<body style="padding:0px">

<!-- <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Transaction Count</h4>
</div>
<div class="modal-body">

 -->
    <?php echo $content; ?>


<!-- <div id="loader"></div>

</div>
<div class="modal-footer">
    Fields with <span class="required">*</span> are required.
</div>
 -->
</body>
</html>
