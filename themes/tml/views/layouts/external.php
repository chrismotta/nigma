<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php 
    //
    $baseUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    // datepicker
    //$cs->registerScriptFile($baseUrl.'/js/bootstrap-datepicker.js');
    //$cs->registerCssFile($baseUrl.'/css/datepicker3.css');
    // custom
    $cs->registerScriptFile($baseUrl.'/js/custom.js');
    $cs->registerCssFile($baseUrl.'/css/styles.css');
    ?>
</head>

<body>
    
<?php $this->widget('bootstrap.widgets.TbNavbar',array(
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right nav'),
            'items'=>array(
                array('label'=>'External form for Guest users', 'url'=>'#', 'visible'=>true),
            ),
        ),
    ),
)); ?>

<div class="container" id="page">

	<?php echo $content; ?>

	<div class="clear"></div>

	<footer>
        <div class="subnav navbar navbar-fixed-bottom">
            <div class="navbar-inner">
                <div class="container text-center">
                	<small>Copyright &copy; <?php echo date('Y'); ?> All Rights Reserved. Powered by <a href="http://www.kickads.mobi" title="Kickads.mobi" target="_new">Kickads.mobi</a></small>
                </div>
            </div>
        </div>      
	</footer>

</div><!-- page -->

</body>
</html>
