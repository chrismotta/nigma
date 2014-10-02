<?php
$dateStart = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'yesterday' ;
$dateEnd   = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'yesterday';

$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd = date('Y-m-d', strtotime($dateEnd));

$accountManager   = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
$opportunitie   = isset($_GET['opportunitie']) ? $_GET['opportunitie'] : NULL;
$networks   = isset($_GET['networks']) ? $_GET['networks'] : NULL;
$totalsGrap=Campaigns::model()->totalsTraffic($dateStart,$dateEnd);
$totals=Campaigns::getTotals($dateStart, $dateEnd,null,$accountManager,$opportunitie,$networks);
// print_r($totals);
// return;
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
<div class="row">
	<div id="container-highchart" class="span12">
	<?php

	$this->Widget('ext.highcharts.HighchartsWidget', array(
		'options'=>array(
			'chart' => array('type' => 'area'),
			'title' => array('text' => ''),
			'xAxis' => array(
				'categories' => $totalsGrap['dates']
				),
			'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
			'yAxis' => array(
				'title' => array('text' => '')
				),
			'series' => array(
				array('name' => 'Clicks', 'data' => $totalsGrap['clics'],),
				array('name' => 'Conversions', 'data' => $totalsGrap['conversions'],),
				),
	        'legend' => array(
	            'layout' => 'vertical',
	            'align' =>  'left',
	            'verticalAlign' =>  'top',
	            'x' =>  40,
	            'y' =>  3,
	            'floating' =>  true,
	            'borderWidth' =>  1,
	            'backgroundColor' => '#FFFFFF'
	        	)
			),
		)
	);
	?>
			
	</div>
</div>
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
	<?php
	$roles = Yii::app()->authManager->getRoles(Yii::app()->user->id);
	//Filtro por role
	$filter = false;
	foreach ($roles as $role => $value) {
		if ( $role == 'admin' or $role == 'media_manager' or $role =='bussiness') {
			$filter = true;
			break;
		}
	}
	if ( $filter ){
	$models = Users::model()->findUsersByRole('media');
	$list = CHtml::listData($models, 
                'id', 'FullName');
	echo CHtml::dropDownList('accountManager', $accountManager, 
              $list,
              array('empty' => 'All account managers','onChange' => '
                  // if ( ! this.value) {
                  //   return;
                  // }
                  $.post(
                      "getOpportunities/"+this.value,
                      "",
                      function(data)
                      {
                          // alert(data);
                        $(".opportunitie-dropdownlist").html(data);
                      }
                  )
                  '));
	if(!$accountManager){
		$models = Opportunities::model()->findAll();
		$list = CHtml::listData($models, 
	                'id', 'virtualName');
		echo CHtml::dropDownList('opportunitie', $opportunitie, 
	              $list,
	              array('empty' => 'All opportunities','class'=>'opportunitie-dropdownlist',));
	}
	else
	{
		$models = Opportunities::model()->findAll( "account_manager_id=:accountManager", array(':accountManager'=>$accountManager) );
		$list = CHtml::listData($models, 
	                'id', 'virtualName');
		echo CHtml::dropDownList('opportunitie', $opportunitie, 
	              $list,
	              array('empty' => 'All opportunities','class'=>'opportunitie-dropdownlist',));
	}
       }
       else{
       		$models = Opportunities::model()->findAll( "account_manager_id=:accountManager", array(':accountManager'=>Yii::app()->user->id) );
			$list = CHtml::listData($models, 
		                'id', 'virtualName');
			echo CHtml::dropDownList('opportunitie', $opportunitie, 
		              $list,
		              array('empty' => 'All opportunities',));

       }
       $models = Networks::model()->findAll();
		$list = CHtml::listData($models, 
	                'id', 'name');
		echo CHtml::dropDownList('networks', $networks, 
	              $list,
	              array('empty' => 'All networks',));
	       
		
	?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter')); ?>
</fieldset>

<?php $this->endWidget(); ?>
<!--### Traffic grid###-->
<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       => 'traffic-grid',
	'dataProvider'             => $model->searchTraffic($accountManager,$opportunitie,$networks),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'selectionChanged'         => 'js:selectionChangedTraffic',
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
			'footer'			=> 'Totals:'
        ),
		array(
			'name'              => 'ios_name',
			'value'             => '$data->opportunities->ios->name',
			'headerHtmlOptions' => array('style' => 'width: 60px'),
        ),		
		array(
			'name'              => 'name',
			'value'             => 'Campaigns::model()->getExternalName($data->id)',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
        array(
			'name'              => 'clicks',
			'value'             => '$data->countClicks("' . $dateStart . '", "'.$dateEnd.'")',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
			'footer'			=> array_sum($totals["clics"]),
        ),
        array(
			'name'              => 'conv',
			'value'             => '$data->countConv("' . $dateStart . '", "'.$dateEnd.'")',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
			'footer'			=>array_sum($totals["conversions"]),
        ),
        array(
			'name'              => 'rate',
			'value'             => '$data->getRateUSD("'.$dateEnd.'")',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
        array(
			'name'              => 'revenue',
			'value'             => '($data->countConv("' . $dateStart . '", "'.$dateEnd.'")*$data->getRateUSD("'.$dateEnd.'"))',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
        array(
			'name'              => 'spend',
			'type'	=>	'raw',
			'value'             => 'CHtml::textField("row-spend" . $row, 0, array(
			        				"style" => "width:30px; text-align:right; font-size: 11px;", 
			        				"onChange" => "
			        					var profit= $( \"#row-spend$row\" ).parent().parent().children().eq(8);
			        					var revenue= $( \"#row-spend$row\" ).parent().parent().children().eq(6);
			        					var spend=$( \"#row-spend$row\" ).val();
										profit.html(revenue.html()-spend);
			        				" 
			        				))',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
        array(
			'name'              => 'profit',
			'value'             => '0',
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