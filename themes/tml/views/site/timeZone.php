<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Set TimeZone';
$this->breadcrumbs=array(
	'TimeZone',
);
?>

<h1>Set TimeZone</h1>

<p>Please select the TimeZone you want to see the reporting stats</p>

<?php

$dbTimeZone= Yii::app()->params->dbTimeZone;

echo CHtml::beginForm('', 'POST', array(
	'id'    =>'filter-form',
	'class' =>'well form-search',
	));

echo CHtml::dropDownList('dbTimeZone', $dbTimeZone, 
              array(
              	'-12:00' => '-12:00', 
              	'-11:00' => '-11:00', 
              	'-10:00' => '-10:00', 
              	'-09:00' => '-09:00', 
              	'-08:00' => '-08:00', 
              	'-07:00' => '-07:00', 
              	'-06:00' => '-06:00', 
              	'-05:00' => '-05:00', 
              	'-04:00' => '-04:00', 
              	'-03:00' => '-03:00', 
              	'-02:00' => '-02:00', 
              	'-01:00' => '-01:00', 
              	'+00:00' => '+00:00', 
              	'+01:00' => '+01:00', 
              	'+02:00' => '+02:00', 
              	'+03:00' => '+03:00', 
              	'+04:00' => '+04:00', 
              	'+05:00' => '+05:00', 
              	'+06:00' => '+06:00', 
              	'+07:00' => '+07:00', 
              	'+08:00' => '+08:00', 
              	'+09:00' => '+09:00', 
              	'+10:00' => '+10:00', 
              	'+11:00' => '+11:00', 
              	'+12:00' => '+12:00', 
              	));

echo "<span class='formfilter-space'></span>";

$this->widget('bootstrap.widgets.TbButton', 
		array(
			'buttonType'=>'submit', 
			'label'=>'Submit', 
			'type' => 'success', 
			'htmlOptions' => array('class' => 'showLoading')
			)
		); 

echo CHtml::endForm();
?>
