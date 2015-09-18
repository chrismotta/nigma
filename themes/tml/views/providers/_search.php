<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

		<?php echo $form->dropDownListRow($model,'type',array("Network"=>"Network","Affiliate"=>"Affiliate","Publisher"=>"Publisher",),array('class'=>'input-large')); ?>

		<?php echo $form->textFieldRow($model,'prefix',array('class'=>'span5','maxlength'=>45)); ?>

		<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->dropDownListRow($model,'status',array("Active"=>"Active","Inactive"=>"Inactive","Archived"=>"Archived",),array('class'=>'input-large')); ?>

		<?php echo $form->dropDownListRow($model,'currency',array("USD"=>"USD","ARS"=>"ARS","EUR"=>"EUR","GBP"=>"GBP","BRL"=>"BRL",),array('class'=>'input-large')); ?>

		<?php echo $form->textFieldRow($model,'country_id',array('class'=>'span5')); ?>

		<?php echo $form->dropDownListRow($model,'model',array("CPA"=>"CPA","CPC"=>"CPC","CPM"=>"CPM","CPI"=>"CPI","CPV"=>"CPV","CPL"=>"CPL","RS"=>"RS",),array('class'=>'input-large')); ?>

		<?php echo $form->textFieldRow($model,'net_payment',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->dropDownListRow($model,'deal',array("PRE-PAYMENT"=>"PRE-PAYMENT","POST-PAYMENT"=>"POST-PAYMENT",),array('class'=>'input-large')); ?>

		<?php echo $form->textFieldRow($model,'post_payment_amount',array('class'=>'span5','maxlength'=>11)); ?>

		<?php echo $form->textFieldRow($model,'start_date',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'end_date',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'daily_cap',array('class'=>'span5','maxlength'=>11)); ?>

		<?php echo $form->textFieldRow($model,'sizes',array('class'=>'span5','maxlength'=>45)); ?>

		<?php echo $form->textFieldRow($model,'has_s2s',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'callback',array('class'=>'span5','maxlength'=>255)); ?>

		<?php echo $form->textFieldRow($model,'placeholder',array('class'=>'span5','maxlength'=>45)); ?>

		<?php echo $form->textFieldRow($model,'has_token',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'commercial_name',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'state',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'zip_code',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'address',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'contact_com',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'email_com',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'contact_adm',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'email_adm',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->dropDownListRow($model,'entity',array("LLC"=>"LLC",),array('class'=>'input-large')); ?>

		<?php echo $form->textFieldRow($model,'tax_id',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'prospect',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'pdf_name',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'pdf_agreement',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'phone',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->datepickerRow($model,'foundation_date',array('options'=>array(),'htmlOptions'=>array('class'=>'span5')),array('prepend'=>'<i class="icon-calendar"></i>','append'=>'Click on Month/Year at top to select a different year or type in (mm/dd/yyyy).')); ?>

		<?php echo $form->textFieldRow($model,'foundation_place',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'bank_account_name',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'bank_account_number',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'branch',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'bank_name',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'swift_code',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'percent_off',array('class'=>'span5','maxlength'=>5)); ?>

		<?php echo $form->textFieldRow($model,'url',array('class'=>'span5','maxlength'=>128)); ?>

		<?php echo $form->textFieldRow($model,'use_alternative_convention_name',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'has_api',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'use_vectors',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'query_string',array('class'=>'span5','maxlength'=>255)); ?>

		<?php echo $form->textAreaRow($model,'token1',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

		<?php echo $form->textAreaRow($model,'token2',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

		<?php echo $form->textAreaRow($model,'token3',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

		<?php echo $form->textFieldRow($model,'publisher_percentage',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'rate',array('class'=>'span5','maxlength'=>11)); ?>

		<?php echo $form->textFieldRow($model,'users_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'account_manager_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
