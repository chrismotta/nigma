<?php

?>
<h1>Transaction Detail #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	/*'attributes'=>array(
		'id',
	),*/
)); ?>
