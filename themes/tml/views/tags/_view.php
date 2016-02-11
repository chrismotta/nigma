<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('campaigns_id')); ?>:</b>
	<?php echo CHtml::encode($data->campaigns_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('banner_sizes_id')); ?>:</b>
	<?php echo CHtml::encode($data->banner_sizes_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />
   	
   	<b><?php echo CHtml::encode($data->getAttributeLabel('analyze')); ?>:</b>
    <?php echo CHtml::encode($data->analyze); ?>
    <br />

</div>