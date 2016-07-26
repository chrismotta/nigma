<?php
/* @var $this ClickMacrosController */
$model = new ClickMacros;
$this->breadcrumbs=array(
	'Click Macros',
);
?>
<h3>List PubIDs</h3>


<?php
if(isset($msg)){
	echo $msg . '<br/>';
	echo CHtml::link('<-- Back',array('clickMacros/index'));
}else{

echo '<div class="form">';
echo CHtml::beginForm('', 'POST', array(
	'id'    =>'filter-form',
	'class' =>'well form-search',
	));


$dateStart = isset($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : 'today -7 days' ;
$dateEnd = isset($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : 'today' ;

echo KHtml::datePicker('ClickMacros[date_start]', $dateStart, array(), array('style'=>'width:100px'), 'From');
echo KHtml::datePicker('ClickMacros[date_end]', $dateEnd, array(), array('style'=>'width:100px'), 'To');
echo '<br>';
echo '<br>';

/*
$this->widget(
    'bootstrap.widgets.TbButtonGroup',
    array(
        'toggle' => 'radio',
        'buttons' => array(
        	array('label' => 'Type', 'disabled' => 'disabled', 'type' => 'info'),
            array('label' => 'WhiteList', 'active'=>true, 
            	'htmlOptions'=>array('onclick' => '$("#type").val("WhiteList");')),
            array('label' => 'BlackList', 'active'=>false, 
            	'htmlOptions'=>array('onclick' => '$("#type").val("BlackList");')),
        ),
    )
);
echo CHtml::hiddenField('ClickMacros[type]', 'WhiteList', array('id'=>'type'));
echo '<br>';
echo '<br>';
*/

echo '<label><div class="input-append input-prepend">';
echo '<span class=" btn btn-info disabled" style="width:35px">List</span>';
echo CHtml::textArea('ClickMacros[list]', '', array('style'=>'width:320px;height:200px;'));
echo '</div></label>';
echo '<br>';
echo '<br>';

$this->widget('bootstrap.widgets.TbButton', 
		array(
			'buttonType'=>'submit', 
			'label'=>'Submit', 
			'type' => 'success', 
			'htmlOptions' => array('class' => 'showLoading')
			)
		); 


echo CHtml::endForm(); 
echo '</div>';
}
?>
