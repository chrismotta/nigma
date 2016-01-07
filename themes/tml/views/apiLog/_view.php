<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('providers_id')); ?>:</b>
	<?php echo CHtml::encode($data->providers_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('exchanges_id')); ?>:</b>
	<?php echo CHtml::encode($data->exchanges_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('logtime')); ?>:</b>
	<?php echo CHtml::encode($data->logtime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('message')); ?>:</b>
	<?php echo CHtml::encode($data->message); ?>
	<br />


</div>