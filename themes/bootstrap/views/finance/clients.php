<?php
/* @var $this IosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Finance'=>'#',	
	'Clients',
);

$this->menu=array(
	array('label'=>'Create Ios', 'url'=>array('create')),
	array('label'=>'Manage Ios', 'url'=>array('admin')),
);
$year=isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
$month=isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
$totals['revenue']=0;
$totals['rate']=0;
$totals['conv']=0;
foreach ($clients as $client) {
	$totals['revenue']+=$client['revenue'];
	$totals['rate']+=$client['rate'];
	$totals['conv']+=$client['conv'];
}
?>
<hr>

<div class="botonera">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Excel Report',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'excelReport',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalClients").html(dataInicial);
					$("#modalClients").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalClients").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'excelReport'),
		)
	); ?>
</div>
<br>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'date-filter-form',
        'type'=>'search',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'action' => Yii::app()->getBaseUrl() . '/finance/clients',
        'method' => 'GET',
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
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
			foreach (range(date('Y'), 2014) as $year) {
				$years[$year]=$year;
			}

		echo $form->dropDownList(new DailyReport,'date',$months,array('name'=>'month', 'options' => array(isset($_GET['month']) ? $_GET['month'] : 0=>array('selected'=>true))));
		echo $form->dropDownList(new DailyReport,'date',$years,array('name'=>'year','options' => array(isset($_GET['year']) ? $_GET['year'] : 0=>array('selected'=>true))));
		//echo CHtml::dropDownList($years,'year',$years);
		            ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter')); ?>

	</div>
    </fieldset>

<?php $this->endWidget(); ?>
<?php 
	$this->widget('yiibooster.widgets.TbGroupGridView', array(
	'id'                         => 'clients-grid',
	//'fixedHeader'              => true,
	//'headerOffset'             => 50,
	'dataProvider'               => $dataProvider,
	'filter'                     => $filtersForm,
	//'filter'                   => $model,
	'type'                       => 'striped condensed',	
	'rowHtmlOptionsExpression'   => 'array("data-row-id" => "1")',
	//'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id, "data-row-net-id" => $data->networks_id, "data-row-c-id" => $data->campaigns_id)',
	'template'                   => '{items} {pager} {summary}',
	'columns'                    => array(
		array(
			'name'              =>	'id',
			'value'             =>'$data["id"]',	
			'headerHtmlOptions' => array('width' => '60'),
			'header'            =>'ID',                           
			),	
		array(
			'name'                =>'name',
			'value'               =>'$data["name"]',
			'htmlOptions'       => array('style'=>'text-align:left;'),		
			'header'              =>'Commercial Name',
			//'footer'              =>'Totals:',      
			),	
		array(
			'name'              =>'model',
			'value'             =>'$data["model"]',	
			'headerHtmlOptions' => array('width' => '80'),
			'header'            =>'Model',		
			),
		array(
			'name'              =>'entity',
			'value'             =>'$data["entity"]',
			'headerHtmlOptions' => array('width' => '80'),	
			'header'            =>'Entity',    
			),	
		array(
			'name'              =>'currency',
			'value'             =>'$data["currency"]',
			'headerHtmlOptions' => array('width' => '80'),		
			'header'            =>'Currency',	
			),
		array(
			'name'              =>'rate',
			'value'             =>'$data["rate"]',
			'headerHtmlOptions' => array('width' => '80'),	
			'htmlOptions'       => array('style'=>'text-align:right;'),	
			//'footer'			=> $totals['rate'],
			'header'            =>'Rate',	
		),	
		array(
			'name'              =>'conv',
			'header'            =>'Clics/Imp/Conv',
			'value'             =>'$data["conv"]',	
			'headerHtmlOptions' => array('width' => '80'),	
			'htmlOptions'       => array('style'=>'text-align:right;'),	
			//'footer'			=> $totals['conv'],
		),
		array(
			'name'              =>'revenue',
			'header'            =>'Revenue',
			'value'             =>'$data["revenue"]',
			'headerHtmlOptions' => array('width' => '80'),
			'htmlOptions'       => array('style'=>'text-align:right;'),		
			//'footer'			=> $totals['revenue'],
		),
		array(
			'type'              =>'raw',
			'header'            =>'',
			'filter'            =>false,
			'headerHtmlOptions' => array('width' => '40'),
			'name'              =>	'name',
			'value'             =>'CHtml::ajaxLink(
				"<i class=\"icon-eye-open\"></i>", 
				"view/".$data["id"], 
			    array (
			        "type"    => "POST",
			        "beforeSend"=>"function(){
		 				$(\"#modalClients\").modal(\"toggle\");
	
			        }",
			        "success" => "function(data){
			        	$(\"#modalClients\").html(data)
			        	//alert(data);
			        }"
			    ), 
			    array ()
			);',		
		),
	),
	'mergeColumns' => array('id','name'),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalClients')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<div class="row" id="blank-row">
</div>