<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Vector <?php echo "#".$vectorsModel->id." : ".$vectorsModel->name ?></h4>
</div>

<div class="modal-body">

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'vhc-form',
    'type'=>'horizontal',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($vhcModel); ?>
    <?php echo $form->dropDownListRow($campaignsModel, 'name', $campaignsModel, array('prompt' => 'Select a campaign')); ?>

		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Add',
		)); ?>
	<div class="form-actions">
	</div>

<?php $this->endWidget(); ?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       => 'add-campaigns-grid',
	'dataProvider'             => $campaignsModel->searchWidhVectors(),
	'type'                     => 'striped condensed',
	'template'                 =>'{items} {pager}',
	'columns'                  =>array(
		array(
			'name'  => 'Campaign',
			'value' => '$data->getExternalName($data->id)',
        ),
        array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 10px"),
			'deleteButtonUrl' => '"deleteRelation/1".$data->id',
			'buttons'           => array(
				'addCampaign' => array(
					'label' =>'Detail',
					'icon'  =>'plus',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	$.post(
						"addCampaign/"+id,
						"vector="'.$vectorsModel->id.',
						function(data)
							{
								//alert(data);
								$("#modalCampaigns").html(data);
								$("#modalCampaigns").modal("toggle");
							}
						)
				    }
				    ',
				),
			),
			'template' => '{delete}',
		),
	)
));

// funciona bien con findall (hace el join correctamente)
// no funciona con cactivedataprovider (no hace el join)

?>

<?php /* $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'vectors-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php $this->endWidget(); */ ?>

</div>

<div class="modal-footer">
    Add campaigns to this vector. Fields with <span class="required">*</span> are required.
</div>
