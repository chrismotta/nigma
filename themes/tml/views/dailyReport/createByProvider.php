<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */
/* @var $providers Providers[] */
/* @var $campaign Campaign */
/* @var $date Date */
/* @var $currentProvider providers_id */

$this->breadcrumbs=array(
	'Daily Reports'=>array('index'),
	'Create by Traffic Source',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#massivecreate-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   => 'providers-filter-form',
		'type'                 => 'search',
		'htmlOptions'          => array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' => true,
		'action'               => Yii::app()->getBaseUrl() . '/dailyReport/createByProvider',
		'method'               => 'GET',
		'clientOptions'        => array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

<fieldset>
	Date: <?php echo KHtml::datePicker('date', $date); ?> 
	Traffic Source: <?php echo KHtml::filterProviders($currentProvider, $providers, array('empty' => 'Select traffic source')); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Load Campaigns', 'htmlOptions' => array('name' => 'providersSubmit', 'class' => 'showLoading'))); ?>
</fieldset>

<?php $this->endWidget(); ?>

<hr>

<?php 
	if ( $currentProvider != NULL ) {
		$tmp = Providers::model()->findByPk($currentProvider);
		if ( $tmp->isNetwork() && $tmp->networks->use_vectors )
			$dataProvider = $vector->searchByProviderAndDate($currentProvider, $date);
		else
			$dataProvider = $campaign->searchByProviderAndDate($currentProvider, $date);
	} else {
		$dataProvider = $campaign->searchByProviderAndDate($currentProvider, $date);
	}
?>

<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'massivecreate-grid',
	'dataProvider'             => $dataProvider,
	// 'filter'                   => $campaign,
	'type'                     => 'striped condensed',
	'fixedHeader'              => true,
	'headerOffset'             => 50,
	'selectableRows'           => 0,
	'rowHtmlOptionsExpression' => 'array("id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'                  => array(
			array(
				'name'        => 'name',
				'value'       => '$data->getExternalName($data->id)',
				'htmlOptions' => array('class' => 'span4', 'id' => 'external_name'),
			),
			array(
				'header'      => $model->getAttributeLabel('imp'),
				'type'        => 'raw',
				'htmlOptions' => array('class'=>'span1'),
				'value'       => 'CHtml::textField("row-imp", "", array(
					"style" => "width:80%; margin-bottom: 0px;",
    				"onkeydown" => "
        				var r = $( this ).parents( \"tr\" );
        				r.removeClass( \"control-group success\" );
        				r.addClass( \"control-group error\" );
        				r.find( \"#labelSubmit\" ).removeClass( \"label-success\" );
        				r.find( \"#labelSubmit\" ).addClass( \"label-important\" );
    				"
    				));',
			),
			array(
				'header'      => $model->getAttributeLabel('imp_adv'),
				'type'        => 'raw',
				'htmlOptions' => array('style'=>'width: 50px'),
				'value'       => 'CHtml::textField("row-imp_adv", "", array(
					"style" => "width:80%; margin-bottom: 0px;",
    				"onkeydown" => "
        				var r = $( this ).parents( \"tr\" );
        				r.removeClass( \"control-group success\" );
        				r.addClass( \"control-group error\" );
        				r.find( \"#labelSubmit\" ).removeClass( \"label-success\" );
        				r.find( \"#labelSubmit\" ).addClass( \"label-important\" );
    				"
    				));',
			),
			array(
				'header'      => $model->getAttributeLabel('clics'),
				'type'        => 'raw',
				'htmlOptions' => array('style'=>'width: 50px'),
				'value'       => 'CHtml::textField("row-clics", "", array(
					"style" => "width:80%; margin-bottom: 0px;",
    				"onkeydown" => "
        				var r = $( this ).parents( \"tr\" );
        				r.removeClass( \"control-group success\" );
        				r.addClass( \"control-group error\" );
						r.find( \"#labelSubmit\" ).removeClass( \"label-success\" );
        				r.find( \"#labelSubmit\" ).addClass( \"label-important\" );
    				"
    				));',
			),
			array(
				'header'      => $model->getAttributeLabel('spend'),
				'type'        => 'raw',
				'htmlOptions' => array('style'=>'width: 50px'),
				'value'       => 'CHtml::textField("row-spend", "", array(
					"style" => "width:80%; margin-bottom: 0px;",
    				"onkeydown" => "
        				var r = $( this ).parents( \"tr\" );
        				r.removeClass( \"control-group success\" );
        				r.addClass( \"control-group error\" );
        				r.find( \"#labelSubmit\" ).removeClass( \"label-success\" );
        				r.find( \"#labelSubmit\" ).addClass( \"label-important\" );
    				"
    				));',
			),
			array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 50px; vertical-align: middle;"),
			'template'          => '{submit}',
			'buttons'           => array(
				'submit' => array(
					'label' => 'Save',
					// 'icon'  => 'upload',
					'options' => array('class' => 'label', 'rel' => '', 'id'=>'labelSubmit'),
					'click' => '
				    function() {
				    	// Create parameters
						var tr                          = $( this ).parents( "tr" );
						var params                      = new Object();
						params.saveSubmit               = "";
						params.DailyReport              = new Object();
						params.DailyReport.providers_id  = $( "#providers" ).val();
						params.DailyReport.imp          = tr.find( "#row-imp" ).val();
						params.DailyReport.imp_adv      = tr.find( "#row-imp_adv" ).val();
						params.DailyReport.clics        = tr.find( "#row-clics" ).val();
						params.DailyReport.spend        = tr.find( "#row-spend" ).val();
						
						var externalName                = tr.find( "#external_name" ).text();
						params.DailyReport.campaigns_id = externalName.substring(0, externalName.indexOf("-"));
						
						var tmp = $( "#date" ).val();
						var y = tmp.substring(tmp.lastIndexOf("-") + 1);
						var m = tmp.substring(tmp.indexOf("-") + 1, tmp.lastIndexOf("-"));
						var d = tmp.substring(0, tmp.indexOf("-"));
						params.DailyReport.date = y + "-" + m + "-" + d;

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
							"createByProvider",
							params,
							function(data) {
								console.log(data);
								var c_id = data.c_id.substring(1);
								// console.log(c_id);

								if (data.result == "OK") {
									var r = $( "#" + c_id );
 									r.removeClass( "control-group error" );
									r.addClass( "control-group success" );
									var l = r.find("#labelSubmit");
									l.removeClass( "label-important" );
									l.addClass( "label-success" );
									l.text("Update");
								}
								if (data.result == "ERROR") {
									var r = $( "#" + c_id );
									r.addClass( "control-group error" );
									r.find("#labelSubmit").removeClass( "label-success" );
									r.find("#labelSubmit").addClass( "label-important" );
								}
							},
							"json"
						)
						return false;
				    }
				    ',
				),
			),
		),
		),
)); ?>


<div class="row" id="blank-row"></div>
