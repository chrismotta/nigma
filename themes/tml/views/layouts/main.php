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
    $cs->registerScriptFile($baseUrl.'/js/modals.js');

    $cs->registerCssFile($baseUrl.'/css/styles.css');
    ?>
</head>

<body>
<?php 
    function rol($rol){ return Yii::app()->user->checkAccess($rol); };
    // echo '===>'.rol('admin');
    
    $items=array(
        array(
            'label'=>'Dashboard', 
            'url'=>array('/site/index'), 
            'itemOptions' => array('class'=>'showLoadingMenuItem'), 
            'visible' => rol('admin') || rol('media_buyer_admin'),
            ),
        array(
            'label'=>'Demand', 
            'url'=>'#', 
            'itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'items'=>array(
                array('label'=>'Advertisers', 'url'=>array('/advertisers/admin')),
                //array('label'=>'IOs', 'url'=>array('/ios/admin')),
                array('label'=>'Finance Entities', 'url'=>array('/financeEntities/admin')),
                array('label'=>'Regions', 'url'=>array('/regions/admin')),
                array('label'=>'Opportunities', 'url'=>array('/opportunities/admin')),
                array('label'=>'IOs', 'url'=>array('/ios/admin')),
                //array('label'=>'Cierre y %', 'url'=>'#'),
                //array('label'=>'Media Kit', 'url'=>'#'),
                ), 
            'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
            ),
        array(
            'label'=>'Media', 
            'url'=>'#',
            'itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'items'=>array(
                array('label'=>'Create Daily Report', 'url'=>array('/dailyReport/createByProvider'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                array('label'=>'Reporting', 'url'=>array('/dailyReport/admin'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                array('label'=>'Campaigns', 'url'=>array('/campaigns/admin'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin') || rol('media_buyer_admin'),
                    ),
                array('label'=>'Tags', 'url'=>array('/tags/admin'),
                    'visible' => rol('admin') || rol('media_buyer_admin'),
                    ),
                array('label'=>'Traffic Log', 'url'=>array('/campaigns/traffic'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                array('label'=>'Vectors', 'url'=>array('/vectors/admin'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                array('label'=>'AM Distribution', 'url'=>array('/opportunities/managersDistribution'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
            ), 
            'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin') || rol('media_buyer_admin'),
            ),
        array(
            'label'=>'Supply', 
            'url'=>'#',
            'itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'items'=>array(
                array('label'=>'Traffic Sources', 'url'=>array('/providers/admin'),
                    // 'visible' => rol('admin') || rol('media_buyer') || rol('media_buyer_admin')
                    ),
                array('label'=>'Publishers', 'url'=>array('/providers/admin/publishers'),
                'visible' => rol('admin') || rol('media_buyer') || rol('media_buyer_admin')
                    ),
                array('label'=>'Affiliates', 'url'=>array('/providers/admin/affiliates')),
                array('label'=>'Networks', 'url'=>array('/providers/admin/networks')),
            ), 
            'visible' => rol('admin') || rol('media_buyer') || rol('media_buyer_admin') || rol('account_manager_admin'),
            ),
        array('label'=>'Exchange', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'items'=>array(
                array('label'=>'Sites', 'url'=>array('/sites/admin')),
                array('label'=>'Placements', 'url'=>array('/placements/admin')),
                array('label'=>'Reporting', 'url'=>array('/dailyPublishers')),
                array('label'=>'Upload CSV Report', 'url'=>array('/dailyPublishers/uploadCSV')),
            ), 
            'visible' => rol('admin') || rol('media_buyer') || rol('media_buyer_admin'),
            ),
        array('label'=>'SEM', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
        'items'=>array(
            array('label'=>'Creatives', 'url'=>array('/sem/creative')),
            array('label'=>'Keywords', 'url'=>array('/sem/keyword')),
            array('label'=>'Placements', 'url'=>array('/sem/placement')),
            array('label'=>'Search Query', 'url'=>array('/sem/searchCriteria')),
        ), 'visible'=>!Yii::app()->user->isGuest),
        array(
            'label'=>'Finance', 
            'url'=>'#',
            'itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'items'=>array(
                array('label'=>'Clients', 'url'=>array('/finance/clients'),
                    'visible' => rol('admin') || rol('finance') || rol('account_manager_admin'),
                    'visible' => rol('admin') || rol('finance') || rol('account_manager_admin'),
                    ),
                array('label'=>'Vendors', 'url'=>array('/finance/providers'),
                    'visible' => rol('admin') || rol('finance') || rol('media_buyer_admin'),
                    ),
                //array('label'=>'Cierre Mes', 'url'=>'#'),
                //array('label'=>'Invoices', 'url'=>'#'),
                array('label'=>'Currency', 'url'=>array('/currency/admin'),
                    'visible' => rol('admin') || rol('finance'),
                    ),
            ), 
            'visible' => rol('admin') || rol('finance') || rol('media_buyer_admin'),
            ),
        array(
            'label'=>'Archive', 
            'url'=>'#',
            'itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'items'=>array(
                array('label'=>'Advertisers', 'url'=>array('/advertisers/archived'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                array('label'=>'Finance Entities', 'url'=>array('/financeEntities/archived'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                array('label'=>'Regions', 'url'=>array('/regions/archived'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                //array('label'=>'IOs', 'url'=>array('/ios/archived')),
                array('label'=>'Opportunities', 'url'=>array('/opportunities/archived'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                array('label'=>'Campaigns', 'url'=>array('/campaigns/archived'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                array('label'=>'Vectors', 'url'=>array('/vectors/archived'),
                    'visible' => rol('admin') || rol('account_manager') || rol('account_manager_admin'),
                    ),
                array('label'=>'Publishers', 'url'=>array('/publishers/archived'),
                    'visible' => rol('admin') || rol('media_buyer') || rol('media_buyer_admin'),
                    ),
                array('label'=>'Sites', 'url'=>array('/sites/archived'),
                    'visible' => rol('admin') || rol('media_buyer') || rol('media_buyer_admin'),
                    ),
                array('label'=>'Placements', 'url'=>array('/placements/archived'),
                    'visible' => rol('admin') || rol('media_buyer') || rol('media_buyer_admin'),
                    ),
            ), 
            'visible'=>!Yii::app()->user->isGuest
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
            'label'=>'Admin', 
            'url'=>'#',
            'itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),
            'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
            'items'=>array(
                array('label'=>'Profile', 'url'=>array('/users/profile')),
                array('label'=>'Users', 'url'=>array('/users/admin'),
                    'visible' => rol('admin') || rol('media_buyer_admin'),
                    ),
                array('label'=>'API Log', 'url'=>array('/apiLog/admin'),
                    'visible' => rol('admin'),
                    ),
                array('label'=>'TimeZone ('.Yii::app()->params->dbTimeZone.')', 'url'=>array('/site/setTimeZone')),
                // array('label'=>'Configuration', 'url'=>'#'),
                // array('label'=>'Meetings', 'url'=>array('/meetingroom')),
            ), 
            'visible'=>!Yii::app()->user->isGuest
            ),
        array(
            'label'=>'Login', 
            'url'=>array('/site/login'), 
            'visible'=>Yii::app()->user->isGuest
            ),
        array(
            'label'=>'Logout ('.Yii::app()->user->name.')', 
            'url'=>array('/site/logout'), 
            'visible'=>!Yii::app()->user->isGuest
            )
    );

    
    if (FilterManager::model()->isUserTotalAccess('affiliate'))
        $items=array(  
                array('label'=>'Dashboard', 'url'=>array('/partners/affiliates')),
                array('label'=>'Admin', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Profile', 'url'=>array('/users/profile')),
                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->controller->id.'/'.Yii::app()->controller->action->id=='externalForms/revenueValidation' ? false : Yii::app()->user->isGuest),
                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            );

    if (FilterManager::model()->isUserTotalAccess('publisher'))
        $items=array(  
                array('label'=>'Publisher', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Dashboard', 'url'=>array('/partners/publishers')),
                ), 'visible'=>!Yii::app()->user->isGuest),             
                array('label'=>'Admin', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Profile', 'url'=>array('/users/profile')),
                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->controller->id.'/'.Yii::app()->controller->action->id=='externalForms/revenueValidation' ? false : Yii::app()->user->isGuest),
                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            );

    if (FilterManager::model()->isUserTotalAccess('publisherCPM'))
        $items=array(  
                array('label'=>'Publisher', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Dashboard', 'url'=>array('/partners/publishersCPM')),
                ), 'visible'=>!Yii::app()->user->isGuest),             
                array('label'=>'Admin', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Profile', 'url'=>array('/users/profile')),
                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->controller->id.'/'.Yii::app()->controller->action->id=='externalForms/revenueValidation' ? false : Yii::app()->user->isGuest),
                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            );

    if (FilterManager::model()->isUserTotalAccess('advertiser'))
        $items=array(  
                array('label'=>'Advertiser', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Dashboard', 'url'=>array('/partners/advertisers')),
                ), 'visible'=>!Yii::app()->user->isGuest),             
                array('label'=>'Admin', 'url'=>'#','itemOptions'=>array('class'=>'dropdown showLoadingMenu','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                'items'=>array(
                    array('label'=>'Profile', 'url'=>array('/users/profile')),
                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->controller->id.'/'.Yii::app()->controller->action->id=='externalForms/revenueValidation' ? false : Yii::app()->user->isGuest),
                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            );

    $this->widget('bootstrap.widgets.TbNavbar',array(
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right nav'),
            'items'=>$items,
        ),
    ),
)); ?>

<div class="container" id="page">
    <?php
    if(!Yii::app()->user->isGuest)
    {        
        $mainVar           =array();
        $mainVar['count']=0;
        $mainVar['date']   = strtotime ( '-1 month' , strtotime ( date('Y-m-d',strtotime('NOW')) ) ) ;
        $mainVar['year']   =date('Y', $mainVar['date']);
        $mainVar['month'] =date('m', $mainVar['date']);
        if (FilterManager::model()->isUserTotalAccess('alert.business')) 
        {
            $mainVar['date']   = Utilities::weekDaysSum(date('Y-m-01'),4);
            $mainVar['option']='ios';
            foreach(IosValidation::model()->findAllByAttributes(array('status'=>'Validated','period'=>$mainVar['year'].'-'.$mainVar['month'].'-01')) as $value)
            {
                $mainVar['count']++;
            }
        }elseif (FilterManager::model()->isUserTotalAccess('alert.media'))
        {
            $mainVar['date']   = Utilities::weekDaysSum(date('Y-m-01'),2);
            $mainVar['option']='opportunities';
            foreach(FinanceEntities::model()->getClients($mainVar['month'],$mainVar['year'],null,null,Yii::App()->user->getId(),null,null,null,null)['data'] as $opportunitie)
            {
                if(!$opportunitie['status_opp'])$mainVar['count']++;
            }
        }
        if($mainVar['count']>0)
            echo '<div class="alert alert-now">You have '.$mainVar['count'].' non-verificated '.$mainVar['option'].'. You must validate them before '.$mainVar['date'].'</div>';
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
                	<small>Copyright &copy; <?php echo date('Y'); ?> All Rights Reserved. Powered by <a href="http://www.themedialab.co" title="TheMediaLab.co" target="_new">TheMediaLab.co</a></small>
                </div>
            </div>
        </div>      
	</footer>

</div><!-- page -->

<div id="loader"></div>

</body>
</html>
