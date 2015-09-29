<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage Users',
);

$this->menu=array(
	// array('label'=>'List Users', 'url'=>array('index')),
	// array('label'=>'Create Users', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#users-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="botonera">
<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'type'        => 'info',
	'label'       => 'Create User',
	'block'       => false,
	'buttonType'  => 'ajaxButton',
	'url'         => 'create',
	'ajaxOptions' => array(
		'type'    => 'POST',
		'beforeSend' => 'function(data)
			{
		    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
				$("#modalUser").html(dataInicial);
				$("#modalUser").modal("toggle");
			}',
		'success' => 'function(data)
			{
                    console.log(data);
	                // alert("create");
					$("#modalUser").html(data);
			}',
		),
	'htmlOptions' => array('id' => 'create'),
	)
);
?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'users-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'=>array(
		array(
			'name' => 'id',
			'headerHtmlOptions' => array('style' => 'width: 65px;'),
			),
		'username',
		// 'password',
		'email',
		'name',
		'lastname',
		array(
			'name' => 'status',
			'headerHtmlOptions' => array('style' => 'width: 45px;'),
			),
		array(
			'name'  => 'provider_external_access',
			'value' => 'Providers::model()->getExternalUser($data->id)',
			'filter' => false,
			),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 100px"),
			'buttons'           => array(
				'viewAjax' => array(
					'label' =>'Detail',
					'icon'  =>'eye-open',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalUser").html(dataInicial);
						$("#modalUser").modal("toggle");

				    	$.post(
						"view/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalUser").html(data);
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
						$("#modalUser").html(dataInicial);
						$("#modalUser").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalUser").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'permissions' => array(
					'label' => 'Permissions',
					'icon'  => 'lock',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalUser").html(dataInicial);
						$("#modalUser").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"adminRoles/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalUser").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'visibility' => array(
					'label' => 'Visibility',
					'icon'  => 'th-list',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalUser").html(dataInicial);
						$("#modalUser").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"visibility/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalUser").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'preview' => array(
					'label'   => 'Preview External Login',
					'icon'    => 'eye-close',
					'url'     => 'Yii::app()->createUrl(Users::getPartnerPreview($data->primaryKey),array("id"=>$data->primaryKey))',
					'options' => array('target'=>'_blank'),
				),
			),
			'template' => '{viewAjax} {updateAjax} {permissions} {visibility} {preview} {delete}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalUser')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"><h1>Advertiser</h1></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>