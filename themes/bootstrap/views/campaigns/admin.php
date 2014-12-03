<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */


// defino si estamos en Campaigns o en Archived y seteo el deleteButton
$is_archived = substr_count($_SERVER['REQUEST_URI'],'archived');
if($is_archived){
	$delete['icon']       = 'refresh';
	$delete['label']      = 'Restore';
	$delete['confirm']    = 'Are you sure you want to restore this campaign?';
	$breadcrumbs['title'] = 'Archived Campaigns';
}else{
	$delete['icon']       = 'trash';
	$delete['label']      = 'Archive';
	$delete['confirm']    = 'Are you sure you want to archive this campaign?';
	$breadcrumbs['title'] = 'Manage Campaigns';
}

$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	$breadcrumbs['title'],
);
?>
<?php
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
<?php
if(!$is_archived){
	echo '<div class="botonera">';
	$this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Create Campaign',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'createAjax',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" /></div><div class=\"modal-footer\"></div>";
						$("#modalCampaigns").html(dataInicial);
						$("#modalCampaigns").modal("toggle");
				}',
			'success' => 'function(data)
				{
	                    //console.log(this.url);
		                //alert("create");
						$("#modalCampaigns").html(data);
						//$("#modalCampaigns").modal("toggle");
				}',
			),
		'htmlOptions' => array('id' => 'createAjax'),
		)
	);
	echo '</div>';
}
?>

<br>
<?php
	$accountManager = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
	$opportunitie   = isset($_GET['opportunitie']) ? $_GET['opportunitie'] : NULL;
	$networks       = isset($_GET['networks']) ? $_GET['networks'] : NULL;
	$advertiser     = isset($_GET['cat']) ? $_GET['cat'] : NULL;
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'date-filter-form',
        'type'=>'search',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'action' => Yii::app()->getBaseUrl() . '/campaigns/admin',
        'method' => 'GET',
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

<fieldset>
	<?php 
		if (FilterManager::model()->isUserTotalAccess('campaign.account'))
			echo KHtml::filterAccountManagers($accountManager);
		
		echo KHtml::filterOpportunities($opportunitie, $accountManager);
		echo KHtml::filterNetworks($networks);
		echo KHtml::filterAdvertisersCategory($advertiser);
	?>
	  
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>

</fieldset>
<?php $this->endWidget(); ?>

<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'campaigns-grid',
	'dataProvider'             => $model->search($accountManager, $opportunitie, $networks, $advertiser),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'fixedHeader'              => true,
	'headerOffset'             => 50,
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 =>'{items} {pager} {summary}',
	
	'columns'                  =>array(
		// para incluir columnas de tablas relacionadas con search y order
		// se usa la propiedad publica custom en 'name'
		// y la ruta relacional de la columna en 'value'
		array(
			'name'  => 'account_manager',
			'value' => '$data->opportunities->accountManager ? $data->opportunities->accountManager->lastname . " " . $data->opportunities->accountManager->name : ""',
        	'htmlOptions'	=> array('style' => 'width: 120px'),
		),
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
        /*
		array(
			'name'              => 'opportunities_id',
			'headerHtmlOptions' => array('style' => 'width: 60px'),
        ),
        */
		array(
			'name'              => 'name',
			'value'             => '$data->getExternalName($data->id)',
			'headerHtmlOptions' => array('style' => 'width: 300px'),
        ),
		/*
		// ejemplos de como setear correctamente columnas relacionadas
		array(
			'name'   => 'opportunities_rate',
			'value'  => '$data->opportunities->rate',
        	'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'   => 'opportunities_carrier',
			'value'  => '$data->opportunities->carrier',
        	'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		'offer_type',
		'currency',
		'budget_type',
		'budget', //sacar
		'model', //sacar
		'bid', //sacar
		'comment',
		*/
		array(
			'name'              => 'net_currency',
			'headerHtmlOptions' => array('style' => 'width: 20px'),
			'value'             => '$data->networks->currency',
        ),
		array(
			'name'              => 'cap',
			'headerHtmlOptions' => array('style' => 'width: 60px'),
			'value'             => '$data->cap',
        ),
		// array(
		// 	'name'              => 'status',
		// 	'value'             => '$data->status', //== 0 ? "Active" : "Paused"',
		// 	'headerHtmlOptions' => array('style' => 'width: 60px'),
  		//  ),
		// array(
		// 	'name'              => 'date_start',
		// 	'value'             => 'date("d-m-Y", strtotime($data->date_start))',
		// 	'headerHtmlOptions' => array('style' => 'width: 70px'),
  //       ),
		// array(

		// 	'name'              => 'date_end',
		// 	'value'             => 'date("d-m-Y", strtotime($data->date_end))',
		// 	'headerHtmlOptions' => array('style' => 'width: 70px'),
  //       ),
		/*
		//ajax using CHtml::ajaxLink
		array(
            'type'=>'raw',
            'value'=>	'
            			CHtml::ajaxLink(
            				"<i class=\"icon-hand-up\"></i>",
	            			Yii::app()->controller->createUrl("redirectAjax"),
	        				array(
								"type" => "POST",
								"dataType" => "json",
								"data" => array("cid" => $data->primaryKey),
								"success" => "function( data )
									{
										$(\"#modalCampaigns .modal-header\").html(data[\"header\"]);
										$(\"#modalCampaigns .modal-body\").html(data[\"body\"]);
										$(\"#modalCampaigns .modal-footer\").html(data[\"footer\"]);
										$(\"#modalCampaigns\").modal(\"toggle\");
									}",
								),
							array(
								"class"=>"single-button-column",
								"rel"=>"tooltip",
								"data-original-title"=>"Redirects"
								)
						) . 
						CHtml::ajaxLink(
							"<i class=\"icon-pencil\"></i>",
	        				Yii::app()->controller->createUrl("updateAjax"),
	        				array(
								"type" => "POST",
								"dataType" => "json",
								"data" => array("cid" => $data->primaryKey),
								"success" => "function( data )
									{
										$(\"#modalCampaigns .modal-header\").html(data[\"header\"]);
										$(\"#modalCampaigns .modal-body\").html(data[\"body\"]);
										$(\"#modalCampaigns .modal-footer\").html(data[\"footer\"]);
										$(\"#modalCampaigns\").modal(\"toggle\");
									}",
								),
							array(
								"class"=>"single-button-column",
								"rel"=>"tooltip",
								"data-original-title"=>"Update"
								)
						);
						',
        	'headerHtmlOptions' => array('style' => 'width: 40px'),

        ),
        */
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 80px"),
			'afterDelete'       => 'function(link, success, data) { if(data) alert(data); }',
			'buttons'           => array(
				'viewAjax' => array(
					'label' =>'Detail',
					'icon'  =>'eye-open',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	$.post(
						"viewAjax/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalCampaigns").html(data);
								$("#modalCampaigns").modal("toggle");
							}
						)
						return false;
				    }
				    ',
				),
				'redirects' => array(
					'label' =>'Redirects',
					'icon'  =>'repeat',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalCampaigns").html(dataInicial);
						$("#modalCampaigns").modal("toggle");

				    	$.post(
						"redirectAjax/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalCampaigns").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'updateAjax' => array(
					'label' => 'Update',
					'icon'  => 'pencil',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalCampaigns").html(dataInicial);
						$("#modalCampaigns").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"updateAjax/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalCampaigns").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'duplicateAjax' => array(
					'label' => 'Duplicate',
					'icon'  => 'plus-sign',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalCampaigns").html(dataInicial);
						$("#modalCampaigns").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"duplicate/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalCampaigns").html(data);
							}
						)
						return false;
				    }
				    ',
				),
			),
			'deleteButtonIcon' => $delete['icon'],
			'deleteButtonLabel' => $delete['label'],
			'deleteConfirmation' => $delete['confirm'],
			'template' => '{viewAjax} {duplicateAjax} {redirects} {updateAjax} {delete}',
		),
	),
)); ?>


<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php /* $this->renderPartial('_search',array(
	'model'=>$model,
)); */?>
</div><!-- search-form -->

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalCampaigns')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>