<?php
/* @var $this AdvertisersController */
/* @var $data Advertisers */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cat')); ?>:</b>
	<?php echo CHtml::encode($data->cat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('commercial_id')); ?>:</b>
	<?php echo CHtml::encode($data->commercial_id); ?>
	<br />


</div>