<?php

/* @var $this DailyReportController */
/* @var $model DailyReport */
$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	'View Traffic',
);
?>
<br>
<?php

	$accountManager = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
	?>
	<br>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'date-filter-form',
		'type'                 =>'search',
		'htmlOptions'          =>array('class'=>'well'),
		'enableAjaxValidation' =>true,
		'action'               => Yii::app()->getBaseUrl() . '/dailyReport/admin',
		'method'               => 'GET',
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

<fieldset class="formfilter">
	<?php
if (FilterManager::model()->isUserTotalAccess('daily'))
		KHtml::filterAccountManagersMulti($accountManager,array('id' => 'accountManager-select'),'advertisers-select','accountManager','advertisers');
	
	KHtml::filterAdvertisersMulti($accountManager,NULL,array('id' => 'advertisers-select'),'advertisers');
	KHtml::filterAdvertisersCountryMulti($accountManager,NULL,array('id' => 'advertisersCountry-select'),'advertisersCountry');
	KHtml::filterModelAdvertisersMulti($accountManager,NULL,array('id' => 'modelAdvertisers-select'),'modelAdvertisers');

?>
<hr>
	<div class="formfilter-submit">
  	  <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>
	</div>

</fieldset>
<?php $this->endWidget(); ?>
<!--### Traffic grid###-->
<?php $this->widget('bootstrap.widgets.TbGroupGridView', array(
	'id'                       => 'managersDistribution-grid',
	'dataProvider'             => $dataProvider,
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 =>'{items} {pager} {summary}',
	
	'columns'                  =>array(
		// para incluir columnas de tablas relacionadas con search y order
		// se usa la propiedad publica custom en 'name'
		// y la ruta relacional de la columna en 'value'
			array(
				'name' => 'account_manager_id',
				'value' => '$data->accountManager["lastname"]." ".$data->accountManager["name"]'
				),
			array(
				'name'              =>'advertiser_name',
				'value'             =>'$data->ios->advertisers->name',
			),
			array(
				'name' => 'name',
				'value' => '$data->getVirtualName()'
				),
			array(
				'name' => 'country_id',
				'value' => '$data->country->name'
				),
			array(
				'name' => 'model_adv',
				'value' => '$data->model_adv'
				),
		),
	'mergeColumns' => array('account_manager_id','advertiser_name'),
)); 
?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalTraffic','htmlOptions'=>array('style'=>'width: 90%;margin-left:-45%'))); //,'htmlOptions'=>array('style'=>'width: 90%;margin-left:-45%')?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalExcel')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>


<div class="row" id="blank-row">
</div>