<?php
/* @var $this AffiliatesController */

$this->breadcrumbs=array(
	'Advertisers',
);
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'date-filter-form',
		'type'                 =>'search',
		'htmlOptions'          =>array('class'=>'well'),
		'enableAjaxValidation' =>true,
		'action'               => Yii::app()->getBaseUrl() . '/partners/advertisers',
		'method'               => 'GET',
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

<fieldset>
	<?php
		$months[0]	='Select a month';
		$months[1]  ='January';
		$months[2]  ='February';
		$months[3]  ='March';
		$months[4]  ='April';
		$months[5]  ='May';
		$months[6]  ='June';
		$months[7]  ='July';
		$months[8]  ='August';
		$months[9]  ='September';
		$months[10] ='October';
		$months[11] ='November';
		$months[12] ='December';
		$years[0]   ='Select a year';
		foreach (range(date('Y'), 2014) as $y) {
			$years[$y]=$y;
		}

		echo $form->dropDownList(new DailyReport,'date',$months,array('name'=>'month', 'style'=>'width:15%;', 'options' => array(intval($month)=>array('selected'=>true))));
		echo $form->dropDownList(new DailyReport,'date',$years,array('name'=>'year', 'style'=>'width:15%; margin-left:1em;','options' => array($year=>array('selected'=>true))));
	?>		
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>

</fieldset>
<?php $this->endWidget(); ?>

<?php $this->widget('yiibooster.widgets.TbGroupGridView', array(
    'id'                         => 'revenue-validation-grid',
    'dataProvider'               => $dataProvider,
    'type'                       => 'striped condensed',    
    'template'                   => '{items} {pager}',
    'columns'                    => array(
        array(
            'name'              =>'country',
            'value'             =>'$data["country"]', 
            'headerHtmlOptions' => array('width' => '100'),
            'header'            =>'Country',      
            'footer'            =>'Totals:',      
            ),
        array(
            'name'              =>'product',
            'value'             =>'$data["product"]', 
            'headerHtmlOptions' => array('width' => '200'),
            'header'            =>'Product',      
            ),
        array(
            'name'              =>'mobileBrand',
            'value'             =>'$data["mobileBrand"]', 
            'headerHtmlOptions' => array('width' => '150'),
            'header'            =>'Carrier',      
            ),
        array(
            'name'              =>'model',
            'value'             =>'$data["model"]', 
            'headerHtmlOptions' => array('width' => '100'),
            'header'            =>'Model',    
            ),
        // array(
        //     'name'              =>'entity',
        //     'value'             =>'$data["entity"]',
        //     'headerHtmlOptions' => array('width' => '80'),  
        //     'header'            =>'Entity',    
        //     ),  
        array(
            'name'              =>'currency',
            'value'             =>'$data["currency"]',
            'headerHtmlOptions' => array('width' => '100'),      
            'header'            =>'Currency',   
            ),
        array(
            'name'              =>'rate',
            'value'             =>'$data["rate"] ? $data["rate"] : "Multi"',
            'headerHtmlOptions' => array('width' => '30'),  
            'htmlOptions'       => array('style'=>'text-align:right;'), 
            'header'            =>'Rate',   
        ),  
        array(
            'name'              =>'conv',
            'header'            =>'Clics/Imp/Conv',
            'value'             =>'number_format($data["conv"])',  
            'headerHtmlOptions' => array('width' => '30'),  
            'htmlOptions'       => array('style'=>'text-align:right;'),
            'footerHtmlOptions' => array('style'=>'text-align:right;'),
            'footer'            => number_format($totals['conv'], 2),   
        ),
        array(
            'name'              =>'revenue',
            'header'            =>'Spend',
            'value'             =>'number_format($data["revenue"],2)',
            'headerHtmlOptions' => array('width' => '50'),
            'htmlOptions'       => array('style'=>'text-align:right;'),   
            'footerHtmlOptions' => array('style'=>'text-align:right;'),    
            // 'footer'            => number_format($totals['revenue'], 2),   
        ),
    ),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalAdvertisers')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<div class="row" id="blank-row">
</div>