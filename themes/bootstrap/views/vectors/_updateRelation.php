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
			'deleteRelation?cid='+cid+'&vid='+vid,
			'',
			function(data)
				{
					//alert(data);
					$.fn.yiiGridView.update('update-relation-grid');
				}
		);
    });
	";

Yii::app()->clientScript->registerScript('customDelete', $customDeleteEvent, CClientScript::POS_READY); ?>

<?php Yii::app()->clientScript->registerScript('customAdd', "
	$('#customAdd').click(function(e){
	    e.preventDefault();
	    $.post(
	    	'', 
	    	$('#update-relation-form').serialize(),
	        function(data) 
		        {
		        	// alert(data);
		        	$.fn.yiiGridView.update('update-relation-grid');
		        }
	    );
	});
", CClientScript::POS_READY); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Vector <?php echo "#".$vectorsModel->id." : ".$vectorsModel->name ?></h4>
</div>

<div class="modal-body">

	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'                   =>'update-relation-form',
		'type'                 =>'inline',
		'htmlOptions'          =>array('class'=>'well'),
		'action'               =>$this->createUrl('vectors/createRelation/' . $vectorsModel->id),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
	)); ?>
	<fieldset>
		<?php echo $form->dropDownListRow($campaignsModel, 'name', $campaigns, array('prompt' => 'Select a campaign')); ?>
	
		<?php 
			echo CHtml::htmlButton('Add', array('id'=>'customAdd', 'name' => 'submit', 'class'=>'btn btn-primary'));
		?>
	</fieldset>

<br/><hr/>
<h6>Currents campaigns already associated with this vector:</h6>
	<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
		'id'                       => 'update-relation-grid',
		'dataProvider'             => $campaignsModel->searchByVectors($vectorsModel->id),
		'type'                     => 'striped condensed',
		'rowHtmlOptionsExpression' => 'array("data-row-cid" => $data->id, "data-row-vid" => '.$vectorsModel->id.')',
		'template'                 => '{items} {pager}',
		'afterAjaxUpdate'          => "function(){ ". $customDeleteEvent . "}",
		'columns'                  => array(
			array(
				'name' => 'Vector ID',
				'value' => $vectorsModel->id,
			),
			array(
				'name' => 'Campaign ID',
				'value' => '$data->id',
			),
			array(
				'name'  => 'Campaign External Name',
				'value' => '$data->getExternalName($data->id)',
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
		)
	));	?>

</div>
<?php $this->endWidget(); ?>

<div class="modal-footer">
    Add campaigns to this vector. Fields with <span class="required">*</span> are required.
</div>
