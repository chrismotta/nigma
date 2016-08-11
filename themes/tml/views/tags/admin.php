<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	'Manage',
);
?>

<?php $this->widget('application.components.NiExtendedGridView',array(
	'id'           =>'tags-grid',
	'dataProvider' =>$model->search(),
	'filter'       =>$model,
	'template'     => '{items} {pagerExt} {summary}',
	'columns'      =>array(
			'id',
			'name',
			'campaigns_id',
			'banner_sizes_id',
			// 'code',
			'comment',
			array(
				'name'=>'freq_cap',
				'value'=>'isset($data->freq_cap) ? $data->freq_cap . " /24" : ""',
				),
			'country',
			'connection_type',
			'device_type',
			'os',
			'os_version',
			array(
	            'class' => 'bootstrap.widgets.TbToggleColumn',
	            'toggleAction' => 'tags/toggle',
	            'name' => 'analyze',
	        ),
		array(
		'class' => 'bootstrap.widgets.TbButtonColumn',
		'headerHtmlOptions' => array('style' => "width: 80px"),
		'buttons' => array(
			'updateIframe' => array(
				'label' => 'Update',
				'icon'  => 'pencil',
				'url'     => 'array("update", "id" => $data->id, "parent"=>"p")',
				'options' => array(
					"data-grid-id"      => "tags-grid", 
					"data-modal-id"     => "modalTags", 
					"data-modal-title"  => "Update Tag", 
					'onclick'           => 'event.preventDefault(); openModal(this)',
					),
				),
			'viewIframe' => array(
				'label' => 'View',
				'icon'  => 'eye-open',
				'url'     => 'array("view", "id" => $data->id)',
				'options' => array(
					"data-grid-id"      => "tags-grid", 
					"data-modal-id"     => "modalTags", 
					"data-modal-title"  => "View Tag", 
					'onclick'           => 'event.preventDefault(); openModal(this)',
					),
				),
			'getIframe' => array(
				'label' => 'Get Tag',
				'icon'  => 'tag',
				'url'     => 'array("getTag", "id" => $data->id, "parent"=>"p")',
				'options' => array(
					"data-grid-id"      => "regitags-grid", 
					"data-modal-id"     => "modalTags", 
					"data-modal-title"  => "Get Tag", 
					'onclick'           => 'event.preventDefault(); openModal(this)',
					),
				),
			),
			'template' => '{viewIframe} {updateIframe} {getIframe} {delete}',
		),
	),
)); ?>

<?php BuildGridView::printModal($this, 'modalTags', 'Region'); ?>
