<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */

$this->breadcrumbs=array(
	'Daily Reports'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List DailyReport', 'url'=>array('index')),
	array('label'=>'Create DailyReport', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#daily-report-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php
	$dateStart = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'yesterday' ;
	$dateEnd   = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'yesterday';
	$accountManager   = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
	$opportunitie   = isset($_GET['opportunitie']) ? $_GET['opportunitie'] : NULL;
	$networks   = isset($_GET['networks']) ? $_GET['networks'] : NULL;

	$dateStart = date('Y-m-d', strtotime($dateStart));
	$dateEnd = date('Y-m-d', strtotime($dateEnd));
	$totalsGrap=$model->getTotals($dateStart,$dateEnd,$accountManager,$opportunitie,$networks);
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
				array('name' => 'Impressions', 'data' => $totalsGrap['impressions'],),
				array('name' => 'Clicks', 'data' => $totalsGrap['clics'],),
				array('name' => 'Conv','data' => $totalsGrap['conversions'],),
				array('name' => 'Spend','data' => $totalsGrap['spends'],),
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

<div class="botonera">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Add Daily Report Manualy',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalDailyReport").html(dataInicial);
					$("#modalDailyReport").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalDailyReport").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'createAjax'),
		)
	); ?>
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
					$("#modalDailyReport").html(dataInicial);
					$("#modalDailyReport").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalDailyReport").html(data);
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
        'action' => Yii::app()->getBaseUrl() . '/dailyReport/admin',
        'method' => 'GET',
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

	<fieldset>
	From: 
	<?php 
	    $this->widget('ext.rezvan.RDatePicker',array(
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
	To:
	<?php 
	    $this->widget('ext.rezvan.RDatePicker',array(
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
<?php 
	$totals=$model->getDailyTotals($dateStart, $dateEnd, $accountManager,$opportunitie,$networks);
	$this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       => 'daily-report-grid',
	'dataProvider'             => $model->search($dateStart, $dateEnd,$accountManager,$opportunitie,$networks),
	'filter'                   => $model,
	'selectionChanged'         => 'js:selectionChangedDailyReport',
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id, "data-row-net-id" => $data->networks_id, "data-row-c-id" => $data->campaigns_id)',
	'template'                 => '{items} {pager} {summary}',
	'rowCssClassExpression'    => '$data->getCapStatus() ? "errorCap" : null',
	'columns'                  => array(
		array(
			'name'  =>	'id',
        	'headerHtmlOptions' => array('style' => 'width: 30px'),
        	'htmlOptions'	=> array( 'class' =>  'id'),
        	'footer' => 'Totals:'
		),
		array(
			'name'  => 'campaign_name',
			'value' => 'Campaigns::model()->getExternalName($data->campaigns_id)',
			'htmlOptions' => array('style' => 'width: 120px'),
		),
		array(
			'name'  =>	'network_name',
			'value'	=>	'$data->networks->name',
			'filter' => $networks_names,
		),
		array(
			'name'  => 'rate',
			'value' => '$data->campaigns->opportunities->rate ? $data->campaigns->opportunities->rate : 0',
			'htmlOptions'=>array('style'=>'width: 45px; text-align:right;'),
		),
		array(	
			'name'	=>	'imp',
			'htmlOptions'=>array('style'=>'width: 50px; text-align:right;'),
			'footer'=>$totals['imp'],
        ),
        array(	
			'name'	=>	'imp_adv',
			'type'	=>	'raw',
			'htmlOptions'=>array('style'=>'width: 85px'),
        	'value' =>	'
					CHtml::textField("row-imp" . $row, $data->imp_adv, array(
        				"style" => "width:30px; text-align:right; font-size: 11px;", 
        				"onkeydown" => "
							var r = $( \"#row-imp\" + $row ).parents( \"tr\" );
	        				r.removeClass( \"control-group success\" );
	        				r.addClass( \"control-group error\" );
        				" 
        				)) . " " .
        			CHtml::ajaxLink(
            				"<i class=\"icon-pencil\"></i>",
	            			Yii::app()->controller->createUrl("updateColumn"),
	        				array(
								"type"     => "POST",
								"dataType" => "json",
								"data"     => array( "id" => "js:$.fn.yiiGridView.getKey(\"daily-report-grid\", $row)",	 "newValue" => "js:$(\"#row-imp\" + $row).val()", "col" => "imp_adv" ) ,
								"success"  => "function( data )
									{
										$.fn.yiiGridView.update(\"daily-report-grid\", {
											complete: function(jqXHR, textStatus) {
												if (textStatus == \'success\') {
													// change css properties
													var r = $( \"#row-imp\" + $row ).parents( \"tr\" );
													r.removeClass( \"control-group error\" );
													r.addClass( \"control-group success\" );
												}
											}
										});
									}",
								),
							array(
								"style"               => "width: 20px",
								"rel"                 => "tooltip",
								"data-original-title" => "Update"
								)
						)
        	',
			'footer'=>$totals['imp_adv'],
        ),
        array(
        	'name'	=>	'clics',
        	'htmlOptions'=>array('style'=>'width: 50px; text-align:right;'),
			'footer'=>$totals['clics'],
        ),
        array(
        	'name'	=>	'conv_api',
        	'htmlOptions'=>array('style'=>'width: 50px; text-align:right;'),
			'footer'=>$totals['conv_s2s'],
        ),
		array(
			'name'        => 'conv_adv',
			'type'        => 'raw',
			'htmlOptions' => array('style'=>'width: 85px'),
			'value'       =>	'
        			CHtml::textField("row-conv" . $row, $data->conv_adv, array(
        				"style" => "width:30px; text-align:right; font-size: 11px;",
        				"onkeydown" => "
	        				var r = $( \"#row-conv\" + $row ).parents( \"tr\" );
	        				r.removeClass( \"control-group success\" );
	        				r.addClass( \"control-group error\" );
        				",
        				"readonly" => $data->campaigns->opportunities->rate === NULL && $data->campaigns->opportunities->carriers_id === NULL,
        				)) . " " .
					
					//
					// Show ajax link depending if opportunity is multi rate. The validation its done using ternary if
					//

					($data->campaigns->opportunities->rate === NULL && $data->campaigns->opportunities->carriers_id === NULL ?

					//
					// Ternary if == true then show multi rate ajax link
					//
					CHtml::link(
            				"<i class=\"icon-plus\"></i>",
	            			"javascript:;",
	        				array(
	        					"onClick" => CHtml::ajax( array(
									"type"    => "POST",
									"url"     => "multiRate/" . $data->id,
									"success" => "function( data )
										{
											$(\"#modalDailyReport\").html(data);
											$(\"#modalDailyReport\").modal(\"toggle\");
										}",
									)),
								"style"               => "width: 20px",
								"rel"                 => "tooltip",
								"data-original-title" => "Update"
								)
						) 
					: 

					//
					// Ternary if == false then show common edit ajax link
					//
        			CHtml::link(
            				"<i class=\"icon-pencil\"></i>",
	            			"javascript:;",
	        				array(
	        					"onClick" => CHtml::ajax( array(
									"url"      => "updateColumn",
									"type"     => "POST",
									"dataType" => "json",
									"data"     => array( "id" => "js:$.fn.yiiGridView.getKey(\"daily-report-grid\", $row)",	 "newValue" => "js:$(\"#row-conv\" + $row).val()", "col" => "conv_adv" ) ,
									"success"  => "function( data )
										{
											$.fn.yiiGridView.update(\"daily-report-grid\", {
											complete: function(jqXHR, textStatus) {
												if (textStatus == \'success\') {
													// change css properties
													var r = $( \"#row-conv\" + $row ).parents( \"tr\" );
													r.removeClass( \"control-group error\" );
													r.addClass( \"control-group success\" );
												}
											}
										});
										}",
									)),
								"style"               => "width: 20px;",
								"rel"                 => "tooltip",
								"data-original-title" => "Update",
								)
						))
					',
			'footer'=>$totals['conv_adv'],
        ),
        array(
        	'name' => 'revenue',
        	'value' => '$data->getRevenueUSD()',
        	'htmlOptions'=>array('style'=>'width: 70px; text-align:right;'),
			'footer'=>$totals['revenue'],
        ),
		array(
        	'name'	=>	'spend',
        	'value'	=>	'$data->getSpendUSD()',
        	'htmlOptions'=>array('style'=>'width: 60px; text-align:right;'),
			'footer'=>$totals['spend'],
        ),
		array(
			'name'  => 'profit',
			'htmlOptions'=>array('style'=>'width: 60px; text-align:right;'),
			'footer'=>$totals['profit'],
		),
		array(
			'name'  => 'profit_percent',
			'value' => '$data->profit_percent * 100 . "%"',
			'htmlOptions'=>array('style'=>'width: 30px; text-align:right;'),
		),
		array(
			'name'  => 'click_through_rate',
			'value' => '$data->click_through_rate * 100 . "%"',
			'htmlOptions'=>array('style'=>'width: 30px; text-align:right;'),
		),
		array(
			'name'  => 'conversion_rate',
			'value' => '$data->conversion_rate * 100 . "%"',
			'htmlOptions'=>array('style'=>'width: 30px; text-align:right;'),
		),
		array(
			'name'  => 'eCPM',
			'htmlOptions'=>array('style'=>'width: 45px; text-align:right;'),
		),
		array(
			'name'  => 'eCPC',
			'htmlOptions'=>array('style'=>'width: 45px; text-align:right;'),
		),
		array(
			'name'  => 'eCPA',
			'htmlOptions'=>array('style'=>'width: 45px; text-align:right;'),
		),
		array(
        	'name'	=>	'date',
        	'value'	=>	'date("d-m-Y", strtotime($data->date))',
        	'htmlOptions'=>array('class' =>  'date', 'style'=>'width: 50px; text-align:right;'),
        ),
        array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 70px"),
			'buttons'           => array(
				'delete' => array(
					'visible' => '!$data->is_from_api',
				),
				'updateAjax' => array(
					'label'   => 'Update',
					'icon'    => 'pencil',
					'visible' => '!$data->is_from_api',
					'click'   => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-c-id");
				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalDailyReport").html(data);
								$("#modalDailyReport").modal("toggle");
							}
						)
					return false;
				    }
				    ',
				),
				'updateCampaign' => array(
					'label'   => 'UpdateCampaign',
					'icon'    => 'eye-open',
					'visible' => '$data->getCapStatus()',
					'url'   => 'Yii::app()->baseUrl."/campaigns/update/".$data->campaigns_id',
				),
			),
			'template' => '{updateAjax} {delete} {updateCampaign}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalDailyReport')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<div class="row" id="blank-row">
</div>