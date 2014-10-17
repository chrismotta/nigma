<?php
/* @var $this VectorsController */
/* @var $vectorsModel Vectors */
/* @var $campaignsModel Campaigns */
/* @var $campaignsModel Campaigns[] */
?>


<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Vector <?php echo "#".$vectorsModel->id." : ".$vectorsModel->name ?></h4>
</div>

<div class="modal-body">

	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'          =>'update-relation-form',
		'type'        =>'inline',
		'htmlOptions' =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
	)); ?>
	<fieldset>
		<?php echo $form->dropDownListRow($campaignsModel, 'name', $campaigns, array('prompt' => 'Select a campaign')); ?>
	
 		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'  => 'ajaxSubmit',
			'type'        => 'primary',
			'label'       => 'Add',
			'htmlOptions' => array('name' => 'submit'),
			'ajaxOptions' => array(
					'type'   => 'post',
					// 'data'   => "javascript:$('#update-relation-form').serialize();",
					'data'   => array('submit'=>'', 'Campaigns'=>'js:$("Campaigns_name").val()'),
					// 'beforeSend' => 'function(data)
					// 	{
					//     	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					// 		$("#modalVectors").html(dataInicial);
					// 		$("#modalVectors").modal("toggle");
					// 	}',
					'success' => 'js:function(data){
						console.log(data);
	                	$.fn.yiiGridView.update("update-relation-grid");
	            	}',
	        )

		)); ?>
	</fieldset>

<br/><hr/>
<h6>Currents campaigns already associated with this vector:</h6>
	<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
		'id'                       => 'update-relation-grid',
		'dataProvider'             => $campaignsModel->searchByVectors($vectorsModel->id),
		'type'                     => 'striped condensed',
		'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
		'template'                 => '{items} {pager}',
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
				'class'             => 'bootstrap.widgets.TbButtonColumn',
				'headerHtmlOptions' => array('style' => "width: 10px"),
				'deleteButtonUrl'   => '$this->grid->controller->createUrl("deleteRelation", array("cid"=>$data->id, "vid"=>'.$vectorsModel->id.', "ajax"=>""))',
				// 'afterDelete' => 'function(id, data){ $.fn.yiiGridView.update("update-relation-grid"); }',
				// 'deleteButtonUrl'   => '$this->createUrl("deleteRelation/.$data->id.?cid=.$data->id.&vid=".$vectorsModel->id),
				'buttons'           => array(
					// 'addCampaign' => array(
					// 	'label' =>'Detail',
					// 	'icon'  =>'plus',
					// 	'click' =>'
					//     function(){
					//     	var id = $(this).parents("tr").attr("data-row-id");
					//     	$.post(
					// 		"addCampaign/"+id,
					// 		"vector="'.$vectorsModel->id.',
					// 		function(data)
					// 			{
					// 				//alert(data);
					// 				$("#modalCampaigns").html(data);
					// 				$("#modalCampaigns").modal("toggle");
					// 			}
					// 		)
					//     }
					//     ',
					// ),
				),
				'template' => '{delete}',
			),
		)
	));

	// funciona bien con findall (hace el join correctamente)
	// no funciona con cactivedataprovider (no hace el join)

	?>

</div>
<?php $this->endWidget(); ?>

<div class="modal-footer">
    Add campaigns to this vector. Fields with <span class="required">*</span> are required.
</div>
