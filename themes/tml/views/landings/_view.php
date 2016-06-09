<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_color')); ?>:</b>
	<?php echo CHtml::encode($data->default_color); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('highlight_color')); ?>:</b>
	<?php echo CHtml::encode($data->highlight_color); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('background_color')); ?>:</b>
	<?php echo CHtml::encode($data->background_color); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('background_images_id')); ?>:</b>
	<?php echo CHtml::encode($data->background_images_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('headline')); ?>:</b>
	<?php echo CHtml::encode($data->headline); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('byline')); ?>:</b>
	<?php echo CHtml::encode($data->byline); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('input_legend')); ?>:</b>
	<?php echo CHtml::encode($data->input_legend); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('input_label')); ?>:</b>
	<?php echo CHtml::encode($data->input_label); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('input_eg')); ?>:</b>
	<?php echo CHtml::encode($data->input_eg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('select_label')); ?>:</b>
	<?php echo CHtml::encode($data->select_label); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('select_options')); ?>:</b>
	<?php echo CHtml::encode($data->select_options); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyc_headline')); ?>:</b>
	<?php echo CHtml::encode($data->tyc_headline); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyc_body')); ?>:</b>
	<?php echo CHtml::encode($data->tyc_body); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('checkbox_label')); ?>:</b>
	<?php echo CHtml::encode($data->checkbox_label); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('button_label')); ?>:</b>
	<?php echo CHtml::encode($data->button_label); ?>
	<br />

	*/ ?>

</div>