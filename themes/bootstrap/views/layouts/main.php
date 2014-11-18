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
                array('label'=>'Dashboard', 'url'=>array('/site/index'), 'itemOptions' => array('class'=>'showLoadingMenuItem'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Media', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Create Daily Report', 'url'=>array('/dailyReport/createByNetwork')),
                    array('label'=>'Reporting', 'url'=>array('/dailyReport/admin')),
                    array('label'=>'Campaigns', 'url'=>array('/campaigns/admin')),
                    array('label'=>'Traffic', 'url'=>array('/campaigns/traffic')),
                    array('label'=>'Vectors', 'url'=>array('/vectors/admin')),
                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'SEM', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Creatives', 'url'=>array('/sem/creative')),
                    array('label'=>'Keywords', 'url'=>array('/sem/keyword')),
                    array('label'=>'Placements', 'url'=>array('/sem/placement')),
                    array('label'=>'Search Query', 'url'=>array('/sem/searchCriteria')),
                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Sales', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Advertisers', 'url'=>array('/advertisers/admin')),
                    array('label'=>'IOs', 'url'=>array('/ios/admin')),
                    array('label'=>'Opportunities', 'url'=>array('/opportunities/admin')),
                    //array('label'=>'Cierre y %', 'url'=>'#'),
                    //array('label'=>'Media Kit', 'url'=>'#'),
                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Finance', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Clients', 'url'=>array('/finance/clients')),
                    array('label'=>'Providers', 'url'=>array('/finance/providers')),
                    //array('label'=>'Cierre Mes', 'url'=>'#'),
                    //array('label'=>'Invoices', 'url'=>'#'),
                    array('label'=>'Currency', 'url'=>array('/currency/admin')),
                ), 'visible'=>!Yii::app()->user->isGuest),

                array('label'=>'Archive', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Advertisers', 'url'=>array('/advertisers/archived')),
                    array('label'=>'IOs', 'url'=>array('/ios/archived')),
                    array('label'=>'Opportunities', 'url'=>array('/opportunities/archived')),
                    array('label'=>'Campaigns', 'url'=>array('/campaigns/archived')),
                    array('label'=>'Vectors', 'url'=>array('/vectors/archived')),
                ), 'visible'=>!Yii::app()->user->isGuest),
                /*
                array('label'=>'Daily', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Clients', 'url'=>'#'),
                    array('label'=>'Networks', 'url'=>'#'),
                    array('label'=>'Regions', 'url'=>'#'),
                    array('label'=>'PNL', 'url'=>'#'),
                    array('label'=>'AM', 'url'=>'#'),
                    array('label'=>'Daily Revenue', 'url'=>'#'),
                    array('label'=>'Budget', 'url'=>'#'),
                ), 'visible'=>!Yii::app()->user->isGuest),
                */
                array('label'=>'Admin', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Profile', 'url'=>array('/users/profile')),
                    array('label'=>'Users', 'url'=>array('/users/admin')),
                    array('label'=>'Configuration', 'url'=>'#'),
                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            ),
        ),
    ),
)); ?>

<div class="container" id="page">

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<footer>
        <div class="subnav navbar navbar-fixed-bottom">
            <div class="easter-footer"></div>
            <div class="navbar-inner">
                <div class="container text-center">
                	<small>Copyright &copy; <?php echo date('Y'); ?> All Rights Reserved. Powered by <a href="http://www.kickads.mobi" title="Kickads.mobi" target="_new">Kickads.mobi</a></small>
                </div>
            </div>
        </div>      
	</footer>

</div><!-- page -->

<div id="loader"></div>

</body>
</html>
