<?php 
/* @var $this IosController */
/* @var $model Ios */
/* @var $action */

$this->layout='external';

$this->breadcrumbs=array();

$this->menu=array();

 ?>


<?php if ($action == 'alreadySubmitted') : ?>
	<h2>Insertion Order has been already submitted. Contact for modifications.</h2>
<?php endif; ?>


<?php if ($action == 'submit') : ?>
	<h2>Insertion Order submitted succesfully.</h2>
<?php endif; ?>


<?php if ($action == 'expire') : ?>
	<h2>Session expired, please contact for new url.</h2>
<?php endif; ?>


<?php if ( $action == 'form') : ?>
	
	<h2>Advertiser #<?php echo $advertiser->id ?> - <?php echo $advertiser->name ?></h2>
	<hr>
	<h4>Create Insertion Order</h4>

	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id'                   =>'ios-form',
			'type'                 =>'horizontal',
			'htmlOptions'          =>array('class'=>'well'),
			// to enable ajax validation
			'enableAjaxValidation' =>true,
			'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
	    )); ?>
	    <fieldset>

		<?php 

			//echo $form->textFieldRow($model, 'status', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'commercial_name', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'tax_id', array('class'=>'span3'));
			echo "<hr/>";
			echo $form->dropDownListRow($model, 'country_id', $country, array('prompt' => 'Select a country'));
			echo $form->textFieldRow($model, 'address', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'state', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'zip_code', array('class'=>'span3'));
			echo $form->textFieldRow($model, 'phone', array('class'=>'span3'));
			echo "<hr/>";
	        echo $form->textFieldRow($model, 'contact_com', array('class'=>'span3'));
	        echo $form->textFieldRow($model, 'email_com', array('class'=>'span3'));
	        echo $form->textFieldRow($model, 'contact_adm', array('class'=>'span3'));
	        echo $form->textFieldRow($model, 'email_adm', array('class'=>'span3'));
			echo "<hr/>";
	        echo $form->dropDownListRow($model, 'currency', $currency, array('prompt' => 'Select a currency'));
			echo $form->textFieldRow($model, 'ret', array('class'=>'span3'));
			echo $form->hiddenField($model, 'commercial_id', array('type'=>"hidden") );
	        //echo $form->textFieldRow($commercial, 'username', array('class'=>'span3', 'readonly'=>true, 'labelOptions'=>array('label'=>$model->getAttributeLabel('commercial_id'))) );
	        echo $form->dropDownListRow($model, 'entity', $entity, array('prompt' => 'Select an entity'));
	    	echo $form->textFieldRow($model, 'net_payment', array('class'=>'span3'));
	    	//echo $form->textFieldRow($model, 'advertisers_id', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true));
			//echo $form->textFieldRow($advertiser, 'name', array('class'=>'span3', 'readonly'=>true));

		?>

	    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	    <div class="form-actions">
	        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
	        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
	    </div>
	    </fieldset>

	<?php $this->endWidget(); ?>

<?php endif; ?>