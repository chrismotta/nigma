<?php
/* @var $model TransactionProviders */
?>

<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h4>Transaction Count Providers</h4>
</div>
<div class="modal-body">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'                   =>'transactionProviders-add-form',
		'enableAjaxValidation' =>false,
		'htmlOptions'          =>array(
			'onsubmit'   =>"return false;",/* Disable normal form submit */
			'onkeypress' =>" if(event.keyCode == 13){ send(); } ", /* Do ajax call when user presses enter key */
			'class'      =>'well'
			),
		)); 
	?>
 
 
	<fieldset>
	    <?php echo $form->errorSummary($model); ?> 	
	        <?php echo $form->labelEx($model,'spend'); ?>
	        <?php echo $form->textField($model,'spend'); ?>
	        <?php echo $form->error($model,'spend'); ?>
	        <?php echo $form->labelEx($model,'comment'); ?>
	        <?php echo $form->textField($model,'comment'); ?>
	        <?php echo $form->error($model,'comment'); ?>
	    <div clss="hiddefields">
			<?php
				echo $form->hiddenField($model, 'providers_id',array('value'=>$id)); 
				echo $form->hiddenField($model, 'period',array('value'=>$period)); 
				echo $form->hiddenField($model, 'date',array('value'=>date('Y-m-d H:i:s', strtotime('NOW')))); 
				echo $form->hiddenField($model, 'users_id',array('value'=>Yii::App()->user->getId())); 
			?>
	    </div>  
 
        <?php echo CHtml::Button('Add',array('onclick'=>'send();','class'=>'btn btn-primary')); ?> 
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
                array('name'              =>'providers_id'),
                array('name'              =>'period'),
                array(
                	'name'              =>'spend',
                	'value'=>'number_format($data->spend,2)'
                	),
                array('name'              =>'comment'),
                array('name'              =>'users_id', 'value'=>'Users::model()->findByPk($data->users_id)->getFullName()'),
                array('name'              =>'date'),
                array('name'              =>'delete', 
                	  'type'			  =>'raw', 
					  'header'            =>false,
				      'filter'            =>false,
					  'headerHtmlOptions' => array('width' => '5'),
					  'htmlOptions'		=>array('style'=>'text-align:left !important'),
                	  'value' 			  =>'CHtml::link(
												"<i class=\"icon-remove\"></i>",
												array(),
							    				array("data-toggle"=>"tooltip", "data-original-title"=>"Delete", "class"=>"linkinvoiced",  
							    					"onclick" => 
							    					"js:bootbox.confirm(\"Are you sure?\", function(confirmed){
							    						if(confirmed){
									    					$.post(\"deleteTransactionProviders/".$data["id"]."\",{})
									                            .success(function( data ) {
									                				$.fn.yiiGridView.update(\"transaction-count-grid\");
									                				// alert(data );
										                            // window.location = document.URL;
									                            });
															}
														 })
													")
												);',
                ),
            ),
        )); ?>



<script type="text/javascript">
$('.linkinvoiced').click(function(e){
        e.preventDefault();        
    });
function send()
 { 
	var data=$("#transactionProviders-add-form").serialize();


	$.ajax(
	{
		type: 'POST',
		url: '<?php echo Yii::app()->createAbsoluteUrl("finance/transactionProviders"); ?>',
		data:data,
		success:function(data){
			// alert(data); 
			$.fn.yiiGridView.update('transaction-count-grid');
		},
		error: function(data) { // if error occured
			alert("Error occured.please try again");
			alert(data);
		},

		dataType:'html'
	});

}

</script>
</div>
<div class="modal-footer">
	Fields with <span class="required">*</span> are required.
</div>