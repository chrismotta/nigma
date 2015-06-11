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

<body style="padding:0px">

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

	<?php echo $content; ?>

	<div class="clear"></div>

<div id="loader"></div>

</body>
</html>
