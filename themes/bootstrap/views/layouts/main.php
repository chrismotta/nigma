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
    
<?php 
    $items=array(
        array(
            'label'       =>'Dashboard', 
            'url'         =>array('/site/index'), 
            'itemOptions' =>array('class'=>'showLoadingMenuItem'), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('admin','business','commercial','commercial_manager','finance','media','media_manager','sem','affiliates_manager')),
        ),
        array(
            'label'       =>'Media', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('admin','business','finance','media','media_manager','sem','affiliates_manager')),
            'items'       =>array(
                array('label'=>'Create Daily Report', 'url'=>array('/dailyReport/createByProvider')),
                array('label'=>'Reporting', 'url'=>array('/dailyReport/admin')),
                array('label'=>'Campaigns', 'url'=>array('/campaigns/admin')),
                array('label'=>'Traffic', 'url'=>array('/campaigns/traffic')),
                array('label'=>'Vectors', 'url'=>array('/vectors/admin')),
                array('label'=>'Managers Distribution', 'url'=>array('/opportunities/managersDistribution')),
            ), 
        ),
        array(
            'label'       =>'Exchange', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('admin','business','finance','sem')),
            'items'       =>array(
                array('label'=>'Placements', 'url'=>array('/placements/admin')),
                array('label'=>'Reporting', 'url'=>array('/dailyPublishers/admin')),
            ),
        ), 
        array(
            'label'       =>'Providers', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('admin','commercial','commercial_manager','finance','media_manager','affiliates_manager')),
            'items'       =>array(
                array('label'=>'Affiliates', 'url'=>array('/affiliates/admin')),
                array('label'=>'Networks', 'url'=>array('/networks/admin')),
                array('label'=>'Publishers', 'url'=>array('/publishers/admin')),
                array('label'=>'Prospects', 'url'=>array('/providers/prospect')),
            ),
        ),
        array(
            'label'       =>'SEM', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('admin','sem')),
            'items'       =>array(
                array('label'=>'Creatives', 'url'=>array('/sem/creative')),
                array('label'=>'Keywords', 'url'=>array('/sem/keyword')),
                array('label'=>'Placements', 'url'=>array('/sem/placement')),
                array('label'=>'Search Query', 'url'=>array('/sem/searchCriteria')),
            ), 
        ),
        array(
            'label'       =>'Sales', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('admin','business','commercial','commercial_manager','finance','media_manager')),
            'items'       =>array(
                array('label'=>'Advertisers', 'url'=>array('/advertisers/admin')),
                array('label'=>'IOs', 'url'=>array('/ios/admin')),
                array('label'=>'Opportunities', 'url'=>array('/opportunities/admin')),
                //array('label'=>'Cierre y %', 'url'=>'#'),
                //array('label'=>'Media Kit', 'url'=>'#'),
            ), 
        ),
        array(
            'label'       =>'Finance', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('admin','business','finance','media','media_manager','affiliates_manager')),
            'items'       =>array(
                array('label'=>'Clients', 'url'=>array('/finance/clients')),
                array('label'=>'Branding Clients', 'url'=>array('/finance/brandingClients')),
                array('label'=>'Providers', 'url'=>array('/finance/providers')),
                //array('label'=>'Cierre Mes', 'url'=>'#'),
                //array('label'=>'Invoices', 'url'=>'#'),
                array('label'=>'Currency', 'url'=>array('/currency/admin')),
            ), 
        ),
        array(
            'label'       =>'Archive', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('admin','business','commercial','commercial_manager','finance','media','media_manager','affiliates_manager')),
            'items'       =>array(
                array('label'=>'Advertisers', 'url'=>array('/advertisers/archived')),
                array('label'=>'IOs', 'url'=>array('/ios/archived')),
                array('label'=>'Opportunities', 'url'=>array('/opportunities/archived')),
                array('label'=>'Campaigns', 'url'=>array('/campaigns/archived')),
                array('label'=>'Vectors', 'url'=>array('/vectors/archived')),
                array('label'=>'Publishers', 'url'=>array('/publishers/archived')),
                array('label'=>'Placements', 'url'=>array('/placements/archived')),
            ), 
        ),
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
        array(
            'label'       =>'Affiliate', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('affiliate')),
            'items'       =>array(
                array('label'=>'Dashboard', 'url'=>array('/partners/affiliates')),
            ), 
        ),
        array(
            'label'       =>'Advertiser', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>UserManager::model()->isUserAssignToRole(array('advertiser')),
            'items'       =>array(
                array('label'=>'Dashboard', 'url'=>array('/partners/advertisers')),
            ), 
        ),
        array(
            'label'       =>'Admin', 
            'url'         =>'#',
            'itemOptions' =>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions' =>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'visible'     =>!Yii::app()->user->isGuest,
            'items'       =>array(
                array('label'=>'Profile', 'url'=>array('/users/profile')),
                array(
                    'label'   =>'Users', 
                    'url'     =>array('/users/admin'), 
                    'visible' => UserManager::model()->isUserAssignToRole(array('admin','business','commercial','commercial_manager','finance','media','media_manager','sem','affiliates_manager'))
                ),
                array(
                    'label'   =>'Configuration', 
                    'url'     =>'#', 
                    'visible' => UserManager::model()->isUserAssignToRole(array('admin','business','commercial','commercial_manager','finance','media','media_manager','sem','affiliates_manager'))
                ),
                array(
                    'label'   =>'Meetings', 
                    'url'     =>array('/meetingroom'), 
                    'visible' =>UserManager::model()->isUserAssignToRole(array('admin','business','commercial','commercial_manager','finance','media','media_manager','sem','affiliates_manager'))
                ),
            ), 
        ),
        array(
            'label'   =>'Login', 
            'url'     =>array('/site/login'), 
            'visible' =>Yii::app()->user->isGuest
        ),
        array(
            'label'   =>'Logout ('.Yii::app()->user->name.')', 
            'url'     =>array('/site/logout'), 
            'visible' =>!Yii::app()->user->isGuest
        ),
    );

    $this->widget('bootstrap.widgets.TbNavbar',array(
    'items'=>array(
        array(
            'class'       =>'bootstrap.widgets.TbMenu',
            'htmlOptions' =>array('class'=>'pull-right nav'),
            'items'       =>$items,
        ),
    ),
)); ?>

<div class="container" id="page">
    <?php
    if(!Yii::app()->user->isGuest)
    {        
        KHtml::printAlerts();
    }
    ?>
    

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
