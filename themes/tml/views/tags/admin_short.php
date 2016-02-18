<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'tags-grid',
	'dataProvider'=>$model->search(),
	'template' =>'{items}',
	'columns'=>array(
		'id',
		// 'campaigns_id',
		// 'banner_sizes_id',
		'size',
		'type',
		// 'code',
		'comment',
		// 'analyze',
		array(
            'class' => 'bootstrap.widgets.TbToggleColumn',
            'toggleAction' => 'tags/toggle',
            'name' => 'analyze',
        ),
	array(
		'class'=>'bootstrap.widgets.TbButtonColumn',
		'htmlOptions' => array('style' => "width: 80px"),
		'buttons'           => array(
			'getTag' => array(
				'label' =>'Get Tag',
				'icon'  =>'tag',
				'url'   => 'array("getTag", "id" => $data->id)',
				),
			),
		'template' => '{view} {getTag} {update} {delete}',
		),
	),
)); ?>

<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Create Tag',
		'block'       => false,
		'buttonType'  => 'linkButton',
		'url'         => array('create','cid'=>$model->campaigns_id),
		)
	); ?>