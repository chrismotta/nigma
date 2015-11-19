<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tag_id')); ?>:</b>
	<?php echo CHtml::encode($data->tag_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('server_data')); ?>:</b>
	<?php echo CHtml::encode($data->server_data); ?>
	<br />


</div>