<?php
/* @var $this SemController */
/* @var $model ClicksLog */

set_time_limit(1000);

$this->breadcrumbs=array(
	'SEM'=>array('index'),
	'Search Criteria',
);
?>

<?php 
	$onlyConversions = isset($_GET['only-conv']) ? $_GET['only-conv'] : false ;
	$searchCriteria  = isset($_GET['criteria']) ? $_GET['criteria'] : NULL ;
	$dateStart       = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'yesterday' ;
	$dateEnd         = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'yesterday' ;
	$submit          = isset($_GET['submit']) ? true : false ;
	
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
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'action'               =>Yii::app()->getBaseUrl() . '/sem/searchCriteria',
		'method'               =>'GET',
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

<fieldset>
	<div class="control-group">
		<?php echo CHtml::label("From:", 'dateStart', array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo KHtml::datePicker('dateStart', $dateStart); ?>
		</div>
	</div>

	<div class="control-group">
		<?php echo CHtml::label("To:", 'dateEnd', array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo KHtml::datePicker('dateEnd', $dateEnd); ?>
		</div>
	</div>

	<div class="control-group">
		<?php echo CHtml::label("Campaign:", 'campaign', array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo KHtml::filterCampaigns($campaignName, array(4, 31), 'campaign', array('class' => 'span5')); ?>
		</div>
	</div>

	<div class="control-group">
		<?php echo CHtml::label("Query:", 'criteria', array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo CHtml::textfield('criteria', $searchCriteria, array('class' => 'span5', 'placeholder' => 'Query')); ?>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<?php echo CHtml::label(CHtml::checkBox('only-conv', $onlyConversions, array()) . " Only Conversions", 'only-conv', array('class'=>'checkbox')); ?>
		</div>
	</div>

	<div class="form-actions">
    	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('name' => 'submit', 'class' => 'showLoading'))); ?>
    	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Download Excel', 'htmlOptions' => array('name' => 'submit-excel'))); ?>
    </div>

</fieldset>
<?php $this->endWidget(); ?>

<?php 
if ($submit) {	// if form not submitted then ignore gridview
	$this->widget('bootstrap.widgets.TbExtendedGridView', array(
		'id'                       => 'search-query-grid',
		'fixedHeader'              => true,
		'headerOffset'             => 50,
		'dataProvider'             => $model->searchQuery($dateStart, $dateEnd, $campaignID, $searchCriteria, $onlyConversions),
		'filter'                   => $model,
		'type'                     => 'striped condensed',
		'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
		'template'                 => '{items} {pager} {summary}',
		'columns'                  => array(
			array(
				'name'        => 'query',
				'htmlOptions' => array('style'=>'width: 500px;'),
			),
			array(
				'name'        => 'totalClicks',
				'filter'      => '',
				'htmlOptions' => array('style'=>'text-align:right; width: 100px;'),
			),
			array(
				'name'        => 'totalConv',
				'filter'      => '',
				'htmlOptions' => array('class' => 'totalConv', 'style'=>'text-align:right; width: 100px;'),
			),
			array(
				'name'        => 'CTR',
				'filter'      => '',
				'value'       => '$data->CTR . " %"',
				'htmlOptions' => array('style'=>'text-align:right; width: 100px;'),
			),
			array(
				'header'      => 'Spend',
				'htmlOptions' => array('style'=>'text-align:right; width: 100px;', 'class' => 'input-condensed'),
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
	));
} ?>