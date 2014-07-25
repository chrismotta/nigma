<?php
/* @var $this DailyReportController */
/* @var $data DailyReport */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('campaigns_id')); ?>:</b>
	<?php echo CHtml::encode($data->campaigns_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('networks_id')); ?>:</b>
	<?php echo CHtml::encode($data->networks_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('imp')); ?>:</b>
	<?php echo CHtml::encode($data->imp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('clics')); ?>:</b>
	<?php echo CHtml::encode($data->clics); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('conv_api')); ?>:</b>
	<?php echo CHtml::encode($data->conv_api); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('conv_adv')); ?>:</b>
	<?php echo CHtml::encode($data->conv_adv); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('spend')); ?>:</b>
	<?php echo CHtml::encode($data->spend); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('model')); ?>:</b>
	<?php echo CHtml::encode($data->model); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('value')); ?>:</b>
	<?php echo CHtml::encode($data->value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	*/ ?>

</div>