<?php
/* @var $this SemController */
/* @var $model ClicksLog */
/* @var $report_type String */

set_time_limit(1000);

$this->breadcrumbs=array(
	'SEM'=>array('index'),
	ucwords($report_type),
);
?>

<div class="botonera">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type'        => 'info',
			'label'       => 'Excel Report',
			'block'       => false,
			'buttonType'  => 'ajaxButton',
			'url'         => 'excelReport',
			'ajaxOptions' => array(
				'type'       => 'POST',
				'data'       => array('report_type' => $report_type),
				'beforeSend' => 'function(data)
					{
				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalSem").html(dataInicial);
						$("#modalSem").modal("toggle");
					}',
				'success' => 'function(data)
					{
						$("#modalSem").html(data);
					}',
				),
			'htmlOptions' => array('id' => 'excelReport'),
			)
		); ?>
</div>
<br/>

<?php 
	$dateStart = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'yesterday' ;
	$dateEnd   = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'yesterday' ;
	
	$dateStart = date('Y-m-d', strtotime($dateStart));
	$dateEnd   = date('Y-m-d', strtotime($dateEnd));
	
	$campaignName = isset($_GET['campaign']) ? $_GET['campaign'] : NULL;
	
	if ($campaignName != NULL) { // get campaigns ID from campaign external name
		$end        = strpos($campaignName, "-");
		$campaignID = substr($campaignName, 0,  $end);
	} else {
		$campaignID = NULL;
	}
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'date-filter-form',
		'type'                 =>'search',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'action'               =>Yii::app()->getBaseUrl() . '/sem/' . $report_type,
		'method'               =>'GET',
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

<fieldset>
	From: <?php echo KHtml::datePicker('dateStart', $dateStart); ?>
	To: <?php echo KHtml::datePicker('dateEnd', $dateEnd); ?>

	<?php echo KHtml::filterCampaigns($campaignName, array(4, 31)); ?>
		
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter')); ?>
</fieldset>
<?php $this->endWidget(); ?>


<?php $this->widget('bootstrap.widgets.TbGroupGridView', array(
	'filter'                   => $model,
	'dataProvider'             => $model->searchSem($report_type, $dateStart, $dateEnd, $campaignID),
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'                  => array(
		array(
			'name'        => $report_type,
			'htmlOptions' => array('style'=>'width: 500px;'),
		),
		array(
			'name'        => 'totalClicks',
			'htmlOptions' => array('style'=>'text-align:right; width: 100px;'),
		),
		array(
			'name'        => 'totalConv',
			'htmlOptions' => array('class' => 'totalConv', 'style'=>'text-align:right; width: 100px;'),
		),
		array(
			'name'        => 'CTR',
			'value'       => '$data->CTR . " %"',
			'htmlOptions' => array('style'=>'text-align:right; width: 100px;'),
		),
		array(
			'header'      => 'Spend',
			'htmlOptions' => array('style'=>'text-align:right; width: 100px;'),
			'type'        =>	'raw',
			'value'       => 'CHtml::textField("row-spend-" . $row, 0, array(
								"style"    => "width:30px; text-align:right; font-size: 11px;", 
								"onChange" => "
									var totalConv = $(this).parents(\"tr\").find(\".totalConv\").text();
									var spend     = $(this).val();
									var eCPA      = $(this).parents(\"tr\").find(\".eCPA\");
									
									// alert(\"spend: \" + spend);
									// alert(\"totalConv: \" + totalConv);
									// alert(\"eCPA: \" + Math.round(spend / totalConv));

									if (totalConv == \"0\")
										eCPA.html( \"0.00\" );
									else
										eCPA.html( (spend / totalConv).toFixed(2) );
		        				" 
	        				))',
        ),
		array(
			'header'      => 'eCPA',
			'value'       => '"0.00"',
			'htmlOptions' => array('class' => 'eCPA', 'style'=>'text-align:right; width: 100px;'),
		),
	),
	'mergeColumns' => array('campaigns_id')
)); ?> 

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalSem')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>