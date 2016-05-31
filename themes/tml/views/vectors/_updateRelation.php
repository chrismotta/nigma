<?php
/* @var $this VectorsController */
/* @var $vectorsModel Vectors */
/* @var $campaignsModel Campaigns */
/* @var $campaignsModel Campaigns[] */
?>

<?php $customDeleteEvent = "
    $('.customDelete').click(function(e){
		e.preventDefault();
		if ( !confirm('Are you sure you want to delete this item?') ) 
			return false;

		var cid = $(this).parents('tr').attr('data-row-cid');
		var vid = $(this).parents('tr').attr('data-row-vid');

		$.post(
			'../deleteRelation?cid='+cid+'&vid='+vid,
			'',
			function(data)
				{
					//alert(data);
					$.fn.yiiGridView.update('update-relation-grid');
					$('#Campaigns_name').html(data);
				}
		);
    });
	";

Yii::app()->clientScript->registerScript('customDelete', $customDeleteEvent, CClientScript::POS_READY); ?>



<?php Yii::app()->clientScript->registerScript('customAdd', "
	$('#customAdd').click(function(e){
	    e.preventDefault();
	    $.post(
	    	'../createRelation/".$vectorsModel->id."', 
	    	$('#update-relation-form').serialize(),
	        function(data) 
		        {
		        	// alert(data);
		        	$.fn.yiiGridView.update('update-relation-grid');
		        	$('#Campaigns_name').html(data);
		        }
	    );
	});
", CClientScript::POS_READY); ?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'                   =>'update-relation-form',
	'type'                 =>'inline',
	'htmlOptions'          =>array('class'=>'well'),
	// to enable ajax validation
	'enableAjaxValidation' =>true,
	'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
)); ?>

<fieldset>
	<?php echo $form->dropDownListRow($campaignsModel, 'name', $campaigns, array('prompt' => 'Select a campaign')); ?>
	<?php echo CHtml::htmlButton('Add', array('id'=>'customAdd', 'name' => 'submit', 'class'=>'btn btn-primary')); ?>
</fieldset>



<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'update-relation-grid',
	'dataProvider'             => $vhc->search(),
	'type'                     => 'striped condensed',
	'template'                 => '{items} {pager}',
	'afterAjaxUpdate'          => "function(){ ". $customDeleteEvent . "}",
	'rowHtmlOptionsExpression' => 'array("data-row-cid" => $data->campaigns_id, "data-row-vid" => $data->vectors_id)',
	'columns' => array(
		'vectors_id',
		'campaigns_id',
		'connection',
		'carrier',

        array(	
			'name'              => 'freq',
			'class'             => 'bootstrap.widgets.TbEditableColumn',
			'editable'          => array(
				'title'      => 'Frequency',
				'type'       => 'text',
				'url'        => array('vectors/updateEditable'),
				'emptytext'  => 'open',
				'inputclass' => 'input-mini',
				// 'params'	 => array('vectors_id'=>'1', 'campaigns_id'=>'$data->campaigns_id'),
				'success'    => 'js: function(response, newValue) {
					  	if (!response.success) {
							$.fn.yiiGridView.update("update-relation-grid");
					  	}
					}',
            ),
        ),

		array(
			'type'              =>'raw',
			'header'            =>'',
			'filter'            =>false,
			'headerHtmlOptions' => array('width' => '40'),
			'value'             =>'CHtml::ajaxLink("<i class=\"icon-trash\"></i>", "", array(), array(
					"data-original-title" => "Delete",
					"data-toggle"         => "tooltip",
					"class"               => "customDelete"
				))',
	        ),
		),
));	?>

<?php $this->endWidget(); ?>

