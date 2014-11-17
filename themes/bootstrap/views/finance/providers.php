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
$year  =isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
$month =isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
$data  =$model->getProviders($month,$year);
?>
<br>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'date-filter-form',
		'type'                 =>'search',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'action'               => Yii::app()->getBaseUrl() . '/finance/providers',
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
			foreach (range(date('Y'), 2014) as $year) {
				$years[$year]=$year;
			}

			$criteria                       =new CDbCriteria;
			$criteria->select               ='entity';
			$criteria->group                ='entity';
			$criteria->addCondition('entity !=""');
			$io                             =new Ios;
			$entity                         =$io->findAll($criteria);
			$entities[0]                    ='All entities';
			foreach ($entity as $value) {
				$entities[$value->entity]=$value->entity;
			}
		echo $form->dropDownList(new DailyReport,'date',$months,array('name'=>'month', 'options' => array($month=>array('selected'=>true))));
		echo $form->dropDownList(new DailyReport,'date',$years,array('name'=>'year','options' => array($year=>array('selected'=>true))));
		
		            ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter')); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Excel Report',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'excelReportProviders',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalProviders").html(dataInicial);
					$("#modalProviders").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalProviders").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'excelReportProviders','style'=>'float:right'),
		)
	); ?>
	</div>
    </fieldset>

<?php $this->endWidget(); ?>


<?php 
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'advertisers-grid',
	'dataProvider'             => $data['arrayProvider'],
	'filter'                   => $data['filtersForm'],
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data["id"])',
	'template'                 => '{items} {pager} {summary}',
	'columns'=>array(
		array(
			'name'        =>'id',
			'value'       =>'$data["id"]',
			'htmlOptions' =>array('style' => 'width: 100px'),
			'header'      =>'ID',    
		),
		array(
			'name'        =>'networks_name',
			'value'       =>'$data["networks_name"]',
			'htmlOptions' =>array('style' => 'width: 100px'),
			'header'      =>'Network Name',  
		),
		array(
			'name'        =>'currency',
			'value'       =>'$data["currency"]',
			'htmlOptions' =>array('style' => 'width: 100px'),
			'header'      =>'Currency',  
		),
		array(
			'name'        =>'clics',
			'value'       =>'$data["clics"]',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Clicks',  
		),
		array(
			'name'        =>'imp',
			'value'       =>'$data["imp"]',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Imp.',  
		),
		array(
			'name'        =>'percent_off',
			'value'       =>'is_null($data["percent_off"]) ? "0%" : number_format($data["percent_off"]*100,0)."%"',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Percent Off',  
		),
		array(
			'name'        =>'spend',
			'header'      =>'Subtotal',
			'value'       =>'$data["spend"]',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Spend',  
		),
		array(
			'name'        =>'off',
			'value'       =>'$data["off"]',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Off',  
		),
		array(
			'name'        =>'total',
			'value'       =>'$data["total"]',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Total',  
		),

	),
)); ?>


<?php 
	$this->widget('yiibooster.widgets.TbGroupGridView', array(
	'id'                         => 'totals-grid',
	'dataProvider'               => $data['totalsDataProvider'],
	'type'                       => 'striped condensed',	
	'template'                   => '{items} {pager}',
	'columns'                    => array(
		array(
			'name'              =>	'currency',
			'value'             =>'$data["currency"]',	
			'headerHtmlOptions' => array('width' => '60'),
			'header'            =>'Currency',                           
			),	
		array(
			'name'              =>	'spend',
			'value'             =>'$data["spend"]',	
			'headerHtmlOptions' => array('width' => '60'),
			'header'            =>'Subtotal',                           
			),
		array(
			'name'              =>	'off',
			'value'             =>'$data["off"]',	
			'headerHtmlOptions' => array('width' => '60'),
			'header'            =>'Off',                           
			),
		array(
			'name'                =>'total',
			'value'               =>'$data["total"]',
			'header'              =>'Total',
			),			
		),
)); ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalProviders')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<div class="row" id="blank-row">
</div>