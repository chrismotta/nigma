<?php 
/* @var $this ProvidersController */
/* @var $model Providers */
/* @var $action */

$this->layout='external';

$this->breadcrumbs=array();

$this->menu=array();

 ?>


<?php if ($action == 'alreadySubmitted') : ?>
	<h2>Provider has been already submitted. Contact for modifications.</h2>
<?php endif; ?>


<?php if ($action == 'submit') : ?>
	<h2>Provider submitted succesfully.</h2>
<?php endif; ?>


<?php if ($action == 'expire') : ?>
	<h2>Session expired, please contact for new url.</h2>
<?php endif; ?>


<?php if ( $action == 'generalForm') : ?>
	
	<hr>
	<h4>Create Provider</h4>

	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id'                   =>'providers-form',
			'type'                 =>'horizontal',
			'htmlOptions'          =>array('class'=>'well'),
			// to enable ajax validation
			'enableAjaxValidation' =>true,
			'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
	    )); ?>
	    <fieldset>

		<?php 

			echo $form->textFieldRow($model, 'prefix', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'commercial_name', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'state', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'zip_code', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'address', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'contact_com', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'email_com', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'contact_adm', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'email_adm', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'phone', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'entity', KHtml::enumItem($model, 'entity'));
			echo $form->textFieldRow($model, 'tax_id', array('class'=>'span3'));

			// Provider info
			echo $form->dropDownListRow($model, 'country_id', CHtml::listData(GeoLocation::model()->findAll( array('order'=>'name', "condition"=>"status='Active' AND type='Country'") ), 'id_location', 'name'));
			echo $form->dropDownListRow($model, 'model', KHtml::enumItem($model, 'model'));
			echo $form->textFieldRow($model, 'net_payment', array('class'=>'span3'));
			echo $form->dropDownListRow($model, 'deal', KHtml::enumItem($model, 'deal'), array(
			    'onChange' => ' 
			      if ($("#Providers_deal").val() == "POST-PAYMENT")
			        $(".post_payment_amount").show();
			      else
			        $(".post_payment_amount").hide();
			    ',
			  ));
			echo '<div style="display: ' . ($model->deal == 'POST-PAYMENT' ? 'block' : 'none') . '" class="post_payment_amount">';
			echo $form->textFieldRow($model, 'post_payment_amount', array('class'=>'span3'));
			echo '</div>';
			echo $form->datepickerRow($model, 'start_date', array(
			        'options' => array(
			            'autoclose'      => true,
			            'todayHighlight' => true,
			            'clearBtn'       => true,
			            'format'         => 'yyyy-mm-dd',
			            'viewformat'     => 'dd-mm-yyyy',
			            'placement'      => 'right',
			        ),
			        'htmlOptions' => array(
			            'class' => 'span3',
			        )),
			        array(
			            'append' => '<label for="Providers_start_date"><i class="icon-calendar"></i></label>',
			        )
			);
			echo $form->datepickerRow($model, 'end_date', array(
			        'options' => array(
			            'autoclose'      => true,
			            'todayHighlight' => true,
			            'clearBtn'       => true,
			            'format'         => 'yyyy-mm-dd',
			            'viewformat'     => 'dd-mm-yyyy',
			            'placement'      => 'right',
			        ),
			        'htmlOptions' => array(
			            'class' => 'span3',
			        )),
			        array(
			            'append' => '<label for="Providers_end_date"><i class="icon-calendar"></i></label>',
			        )
			);
			echo $form->textFieldRow($model, 'daily_cap', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'sizes', array('class'=>'span3'));

			// S2S info
			echo $form->dropDownListRow($model, 'currency', KHtml::enumItem($model, 'currency'), array('prompt' => 'Select a currency'));
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
			echo $form->hiddenField($model, 'id',array('value'=>$id)); 
			echo '</div>';
			echo '</div>';
			echo '<hr/>';

		?>

	    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	    <div class="form-actions">
	        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
	        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
	    </div>
	    </fieldset>

	<?php $this->endWidget(); ?>

<?php endif; ?>
<?php if ( $action == 'financeForm') : ?>
	
	<hr>
	<h4>Create Provider</h4>

	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id'                   =>'providers-form',
			'type'                 =>'horizontal',
			'htmlOptions'          =>array('class'=>'well'),
			// to enable ajax validation
			'enableAjaxValidation' =>true,
			'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
	    )); ?>
	    <fieldset>

		<?php 

			echo $form->textFieldRow($model, 'commercial_name', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'zip_code', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'address', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'contact_adm', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'email_adm', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'tax_id', array('class'=>'span3'));

			// Provider info
			echo $form->dropDownListRow($model, 'country_id', CHtml::listData(GeoLocation::model()->findAll( array('order'=>'name', "condition"=>"status='Active' AND type='Country'") ), 'id_location', 'name'));
			echo $form->datepickerRow($model, 'foundation_date', array(
			        'options' => array(
			            'autoclose'      => true,
			            'todayHighlight' => true,
			            'clearBtn'       => true,
			            'format'         => 'yyyy-mm-dd',
			            'viewformat'     => 'dd-mm-yyyy',
			            'placement'      => 'right',
			        ),
			        'htmlOptions' => array(
			            'class' => 'span3',
			        )),
			        array(
			            'append' => '<label for="Providers_foundation_date"><i class="icon-calendar"></i></label>',
			        )
			);		
			echo $form->textFieldRow($model, 'foundation_place', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'ada_name', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'ada_number', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'branch', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'bank_name', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'swift_code', array('class'=>'span3'));
			echo $form->hiddenField($model, 'id',array('value'=>$id)); 
			echo '</div>';
			echo '</div>';
			echo '<hr/>';

		?>

	    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	    <div class="form-actions">
	        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
	        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
	    </div>
	    </fieldset>

	<?php $this->endWidget(); ?>

<?php endif; ?>