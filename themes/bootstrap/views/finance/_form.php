<?php
/* @var $model TransactionCount */
?>

<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
</div>


<div class="modal-body">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'                   =>'transaction-count-form',
		// 'type'                 =>'inline',
		'htmlOptions'          =>array('class'=>'well'),
		'action'               =>$this->createUrl('finance/addTransaction/'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
	)); ?>
	<fieldset>
	<?php 
		$month=date('m', strtotime($period));
		$year=date('Y', strtotime($period));
		$startDate=date('Y-m-d', strtotime($year.'-'.$month.'-01'));
		$endDate=date('Y-m-d', strtotime($year.'-'.$month.'-31'));
		 
		echo KHtml::filterOpportunitiesDate('opportunities_id',NULL,array(),$id,$startDate,$endDate);
		echo $form->textFieldRow($model, 'volume', array('class'=>'span3')); 
		echo $form->textFieldRow($model, 'rate', array('class'=>'span3')); 
		// echo $form->hiddenField($model, 'opportunities_id',array('value'=>$id)); 
		echo $form->hiddenField($model, 'period',array('value'=>$period)); 
		echo $form->hiddenField($model, 'date',array('value'=>date('Y-m-d H:i:s', strtotime('NOW')))); 
		echo $form->hiddenField($model, 'users_id',array('value'=>Yii::App()->user->getId())); 
	?>
	<br>
 		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'  => 'ajaxSubmit',
			'type'        => 'primary',
			'label'       => 'Add',
			'htmlOptions' => array('name' => 'submit'),
			'ajaxOptions' => array(
					'type'   => 'post',
					'data'   => "javascript:$('#transaction-count-form').serialize();",
					'success' => 'js:function(data){
						// console.log(data);
	                	$.fn.yiiGridView.update("transaction-count-grid");
	            	}',
	        )

		)); ?>
	</fieldset>
	<?php $this->endWidget(); ?>

	<?php 
            $this->widget('yiibooster.widgets.TbExtendedGridView', array(
            'id'                         => 'transaction-count-grid',
            'dataProvider'               => $model->getTransactions($id,$period),
            'type'                       => 'striped bordered',    
            'template'                   => '{items} {pager} {summary}',
            'columns'                    => array(
                array('name'              =>'id'),
                array('name'              =>'opportunities_id'),
                array('name'              =>'period'),
                array('name'              =>'volume'),
                array('name'              =>'rate'),
                array('name'              =>'users_id'),
                array('name'              =>'date'),
            ),
        )); ?>
</div>

<div class="modal-footer">
	Fields with <span class="required">*</span> are required.
</div>