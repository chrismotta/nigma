<?php
// example code for the view "_relational" that returns the HTML content
//echo CHtml::tag('h3',array(),'RELATIONAL DATA EXAMPLE ROW : "'.$id.'"');
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'type'=>'striped bordered',
	'dataProvider' => $dataProvider,
	'template' => "{items}",
	//'filter' => false,
	'columns' => array(
		array(
			'name'              => 'opportunity',
			'htmlOptions'       => array('id'=>'alignLeft'),                           
			'sortable'			=> false,
			),			
		array(
			'name'              =>'imp',				
			'headerHtmlOptions' => array('width' => '80'),	
			'htmlOptions'       => array('style'=>'text-align:right;'),	
			'sortable'			=> false,
		),
		array(
			'name'              =>'clics',
			'headerHtmlOptions' => array('width' => '80'),	
			'htmlOptions'       => array('style'=>'text-align:right;'),	
			'sortable'			=> false,
		),		
		array(
			'name'              =>'conv_adv',
			'headerHtmlOptions' => array('width' => '80'),	
			'htmlOptions'       => array('style'=>'text-align:right;'),	
			'sortable'			=> false,
		),		
		array(
			'name'              =>'revenue',
			'value'             =>'number_format($data["revenue"],2)',
			'headerHtmlOptions' => array('width' => '80'),
			'htmlOptions'       => array('style'=>'text-align:right;'),		
			'sortable'			=> false,
		),		
	),
));
?>