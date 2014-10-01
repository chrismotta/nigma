<?php
$dateStart = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'yesterday' ;
$dateEnd   = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'yesterday';

$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd = date('Y-m-d', strtotime($dateEnd));
/* @var $this CampaignsController */
/* @var $model Campaigns */

//Agrega los links de navegación
$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	'View Traffic',
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-button').click(function(){
		$('.search-form').toggle();
		return false;
	});
	$('.search-form form').submit(function(){
		$('#campaigns-grid').yiiGridView('update', {
			data: $(this).serialize()
		});
		return false;
	});
	");
?>

<hr>
<!--####Button excel report#####-->
<div class="botonera">
<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Excel Report',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'excelReport',
		'ajaxOptions' => array(
			'type'    => 'GET',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalCampaigns").html(dataInicial);
					$("#modalCampaigns").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalCampaigns").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'excelReport'),
		)
	); ?>
</div>
<br>

<!--### Date Picker ###-->
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'date-filter-form',
        'type'=>'search',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'action' => Yii::app()->getBaseUrl() . '/campaigns/traffic',
        'method' => 'GET',
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 
<fieldset>
	From:
	<div class="input-append">
		<?php 
		    $this->widget('bootstrap.widgets.TbDatePicker',array(
			'name'  => 'dateStart',
			'value' => date('d-m-Y', strtotime($dateStart)),
			'htmlOptions' => array(
				'style' => 'width: 80px',
			),
		    'options' => array(
				'autoclose'      => true,
				'format'         => 'dd-mm-yyyy',
				'viewformat'     => 'dd-mm-yyyy',
				'placement'      => 'right',
		    ),
		));
		?>
		<span class="add-on"><i class="icon-calendar"></i></span>
	</div>
	To:
	<div class="input-append">
		<?php 
		    $this->widget('bootstrap.widgets.TbDatePicker',array(
			'name'        => 'dateEnd',
			'value'       => date('d-m-Y', strtotime($dateEnd)),
			'htmlOptions' => array(
				'style' => 'width: 80px',
			),
			'options'     => array(
				'autoclose'      => true,
				'format'         => 'dd-mm-yyyy',
				'viewformat'     => 'dd-mm-yyyy',
				'placement'      => 'right',
		    ),
		));
		?>
		<span class="add-on"><i class="icon-calendar"></i></span>
	</div>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter')); ?>
</fieldset>

<?php $this->endWidget(); ?>
<!--### Traffic grid###-->
<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       => 'traffic-grid',
	'dataProvider'             => $model->searchTraffic(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 =>'{items} {pager} {summary}',
	
	'columns'                  =>array(
		// para incluir columnas de tablas relacionadas con search y order
		// se usa la propiedad publica custom en 'name'
		// y la ruta relacional de la columna en 'value'
		array(
			'name'              => 'advertisers_name',
			'value'             => '$data->opportunities->ios->advertisers->name',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'              => 'ios_name',
			'value'             => '$data->opportunities->ios->name',
			'headerHtmlOptions' => array('style' => 'width: 60px'),
        ),		
		array(
			'name'              => 'name',
			'value'             => '$data->name',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
        array(
			'name'              => 'clicks',
			'value'             => '$data->countClicks("' . $dateStart . '", "'.$dateEnd.'")',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
        array(
			'name'              => 'conv',
			'value'             => '$data->countConv("' . $dateStart . '", "'.$dateEnd.'")',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
	),
)); 
?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalCampaigns')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>