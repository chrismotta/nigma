
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'providers-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
	)); ?>

    <fieldset>

	<?php echo $form->errorSummary($model); ?>

	<div class="divisor">Internal Settings</div>

	<?php 
	if( UserManager::model()->isUserAssignToRole('account_manager_admin') )
		$types = array("Network"=>"Network","Affiliate"=>"Affiliate","Google AdWords"=>"Google AdWords");
	else
		$types = array("Network"=>"Network","Affiliate"=>"Affiliate","Publisher"=>"Publisher","Google AdWords"=>"Google AdWords");

	echo $form->dropDownListRow($model,'type',$types,array('class'=>'input-large')); 
	?>
	<?php echo $form->textFieldRow($model,'name',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'prefix',array('class'=>'span3','maxlength'=>45)); ?>
	<?php echo $form->dropDownListRow($model,'status',array("Active"=>"Active","Inactive"=>"Inactive","Archived"=>"Archived",),array('class'=>'input-large')); ?>
	<?php // echo $form->textFieldRow($model,'account_manager_id',array('class'=>'span3')); ?>

	<div class="divisor">Commercial Settings</div>

	<?php echo $form->textFieldRow($model,'commercial_name',array('class'=>'span3','maxlength'=>128)); ?>
	<?php // echo $form->textFieldRow($model,'country_id',array('class'=>'span3')); ?>
	<?php echo $form->dropDownListRow($model, 'country_id', $countries, array('prompt' => 'Select country')); ?>

	<?php echo $form->textFieldRow($model,'state',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'zip_code',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'address',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'phone',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'contact_com',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'email_com',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'contact_adm',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'email_adm',array('class'=>'span3','maxlength'=>128)); ?>

	<div class="divisor">Finance Settings</div>

	<?php echo $form->textFieldRow($model,'tax_id',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->datepickerRow($model,'foundation_date',array('options'=>array(),'htmlOptions'=>array('class'=>'span2')),array('prepend'=>'<i class="icon-calendar"></i>')); ?>
	<?php echo $form->textFieldRow($model,'foundation_place',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'bank_account_name',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'bank_account_number',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'branch',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'bank_name',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'swift_code',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->textFieldRow($model,'percent_off',array('class'=>'span3','maxlength'=>5)); ?>

	<div class="divisor">Media Settings</div>

	<?php echo $form->dropDownListRow($model,'currency',array("USD"=>"USD","ARS"=>"ARS","EUR"=>"EUR","GBP"=>"GBP","BRL"=>"BRL",),array('class'=>'input-large')); ?>
	<?php echo $form->dropDownListRow($model,'model',array("CPA"=>"CPA","CPC"=>"CPC","CPM"=>"CPM","CPI"=>"CPI","CPV"=>"CPV","CPL"=>"CPL","RS"=>"RS",),array('class'=>'input-large')); ?>
	<?php echo $form->textFieldRow($model,'net_payment',array('class'=>'span3','maxlength'=>128)); ?>

	<?php
	echo $form->dropDownListRow($model, 'deal', KHtml::enumItem($model, 'deal'), array(
	    'onChange' => ' 
	      if ($("#Providers_deal").val() == "PRE-PAYMENT")
	        $(".post_payment_amount").show();
	      else
	        $(".post_payment_amount").hide();
	    ',
	  ));
	echo '<div style="display: ' . ($model->deal == 'PRE-PAYMENT' ? 'block' : 'none') . '" class="post_payment_amount">';
	echo $form->textFieldRow($model, 'post_payment_amount',array('class'=>'span2','maxlength'=>11), array('prepend' => '$'));
	echo '</div>';
	?>

	<?php // echo $form->dropDownListRow($model,'deal',array("PRE-PAYMENT"=>"PRE-PAYMENT","POST-PAYMENT"=>"POST-PAYMENT",),array('class'=>'input-large')); ?>
	<?php // echo $form->textFieldRow($model,'post_payment_amount',array('class'=>'span3','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'daily_cap',array('class'=>'span2','maxlength'=>11), array('prepend' => '$')); ?>
	<?php echo $form->textFieldRow($model,'sizes',array('class'=>'span3','maxlength'=>45)); ?>
	<?php echo $form->textFieldRow($model,'rate',array('class'=>'span2','maxlength'=>11), array('prepend' => '$')); ?>
	<?php echo $form->textFieldRow($model,'publisher_percentage',array('class'=>'span2','maxlength'=>11), array('append' => '%')); ?>
	<?php // echo $form->textFieldRow($model,'users_id',array('class'=>'span3')); ?>
	<?php 
	if( !UserManager::model()->isUserAssignToRole('account_manager_admin') )
		echo $form->dropDownListRow($model, 'users_id', $users, array('prompt' => 'Select a user to associate')); 
	?>
	<?php //echo $form->datepickerRow($model,'start_date',array('options'=>array(),'htmlOptions'=>array('class'=>'span3')),array('prepend'=>'<i class="icon-calendar"></i>')); ?>

	<?php //echo $form->textFieldRow($model,'end_date',array('class'=>'span3')); ?>

	<div class="divisor">Network Settings</div>

	<?php
	// S2S info
	echo $form->checkboxRow($model, 'has_s2s', array(
	        'onChange' => '
	          if (this.checked == "1")
	            $(".has_s2s").show();
	          else
	            $(".has_s2s").hide();

	          return;
	          '
	    ));
	echo '<div style="display: ' . ($model->has_s2s ? 'block' : 'none') . ';" class="has_s2s">';
	echo $form->textFieldRow($model, 'callback', array('class'=>'span3'));
	echo $form->checkboxRow($model, 'has_token', array(
	        'onChange' => '
	          if (this.checked == "1")
	            $(".has_token").show();
	          else
	            $(".has_token").hide();

	          return;
	          '
	    ));
	echo '<div style="display: ' . ($model->has_token ? 'block' : 'none') . ';" class="has_token">';
	echo $form->textFieldRow($model, 'placeholder', array('class'=>'span3'));
	echo '</div>';
	echo '</div>';
	?>

	<?php // echo $form->textFieldRow($model,'has_s2s',array('class'=>'span3')); ?>
	<?php // echo $form->textFieldRow($model,'callback',array('class'=>'span3','maxlength'=>255)); ?>
	<?php // echo $form->textFieldRow($model,'has_token',array('class'=>'span3')); ?>
	<?php // echo $form->textFieldRow($model,'placeholder',array('class'=>'span3','maxlength'=>45)); ?>
	<?php // echo $form->textFieldRow($model,'prospect',array('class'=>'span3')); ?>
	<?php // echo $form->textFieldRow($model,'pdf_name',array('class'=>'span3','maxlength'=>128)); ?>
	<?php // echo $form->textFieldRow($model,'pdf_agreement',array('class'=>'span3','maxlength'=>128)); ?>
	<?php echo $form->checkboxRow($model,'use_alternative_convention_name'); ?>
	<?php echo $form->checkboxRow($model, 'use_vectors'); ?>
	<?php // echo $form->textFieldRow($model,'has_api',array('class'=>'span3')); ?>
	<?php // echo $form->textFieldRow($model,'use_vectors',array('class'=>'span3')); ?>

    <?php 

    echo $form->checkboxRow($model, 'has_api', array(
	        'onChange' => '
	          if (this.checked == "1")
	            $(".has_api").show();
	          else
	            $(".has_api").hide();

	          return;
	          '
	    )); 
	echo '<div style="display: ' . ($model->has_token ? 'block' : 'none') . ';" class="has_api">';
	echo $form->textFieldRow($model,'query_string',array('class'=>'span3','maxlength'=>255));
	echo $form->textFieldRow($model,'token1',array('class'=>'span3'));
	echo $form->textFieldRow($model,'token2',array('class'=>'span3'));
	echo $form->textFieldRow($model,'token3',array('class'=>'span3'));
	echo '</div>';
	?>

	<div class="divisor">Google AdWords Settings</div>
	<?php echo $form->textFieldRow($model,'conversion_profile',array('class'=>'span3')); ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
	</fieldset>
	<?php $this->endWidget(); ?>
