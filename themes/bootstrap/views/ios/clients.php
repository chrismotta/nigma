<?php
/* @var $this IosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ioses',
);

$this->menu=array(
	array('label'=>'Create Ios', 'url'=>array('create')),
	array('label'=>'Manage Ios', 'url'=>array('admin')),
);
$year=isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
$month=isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
//echo json_encode($model->getClients($month,$year));
$clients=$model->getClients($month,$year);

$dataProvider=new CArrayDataProvider($clients, array(
    'id'=>'clients',
    // 'sort'=>array(
    //     'attributes'=>array(
    //          'id', 'username', 'email',
    //     ),
    // ),
    'pagination'=>array(
        'pageSize'=>30,
    ),
));
// 
//echo json_encode($dataProvider);
// return;
?>
<br>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'date-filter-form',
        'type'=>'search',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'action' => Yii::app()->getBaseUrl() . '/ios/clients',
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
	'id'                       => 'clients-grid',
	//'fixedHeader'              => true,
	//'headerOffset'             => 50,
	'dataProvider'             => $dataProvider,
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	//'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id, "data-row-net-id" => $data->networks_id, "data-row-c-id" => $data->campaigns_id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'                  => array(
		array(
			'name'              =>	'id',
			'value'	=>'$data["id"]',	
			),	
		array(
			'name'              =>	'commercial_name',
			'value'	=>'$data["name"]',		
			'footer' =>'Totals:',
			),	
		array(
			'name'              =>	'model',
			'value'	=>'$data["model"]',		
			),
		array(
			'name'              =>	'entity',
			'value'	=>'$data["entity"]',		
			),	
		array(
			'name'              =>	'currency',
			'value'	=>'$data["currency"]',		
			),
		array(
			'name'              =>	'rate',
			'value'	=>'$data["rate"]',		
		),	
		array(
			'name'              =>	'conv',
			'header'=>'Clics/Imp/Conv',
			'value'	=>'$data["conv"]',		
		),
		array(
			'name'              =>	'revenue',
			'value'	=>'$data["rev"]',		
		),
		// array(
		// 	'name'              =>	'conv',
		// 	'header'			=>'conv_adv',
		// 	'value'	=>'intval($data->conv_adv)',		
		// ),
		// array(
		// 	'name'              =>	'conv',
		// 	'value'	=>'intval($data->conversions)',		
		// ),
		// array(
		// 	'name'              =>	'rate',
		// 	'value'	=>'$data->campaigns->opportunities->rate',		
		// ),
		// array(
		// 	'name'              =>	'revenue',
		// 	'value'	=>'$data->revenue',		
		// ),
	),
	'mergeColumns' => array('id','commercial_name'),
)); ?>