<?php
echo '<div style="text-align: center; margin-top: 150px;" >';

echo '<div class="response">';
echo $entity.' #'.$id.' succesfully '.$action;
echo '</div>';

echo '<div class="response">';
$this->widget(
    'bootstrap.widgets.TbButtonGroup',
    array(
        'buttons' => array(
            array(
            	'url' => array('create'), 
            	'icon' => 'plus', 
            	'htmlOptions' => array('title' => 'Click to create a new '.$entity),
            	),
            array(
            	'url' => array('update', 'id' => $id), 
            	'icon' => 'pencil', 
            	'htmlOptions' => array('title' => 'Click to update '.$entity.' #'.$id),
            	),
            array(
            	'url' => array('duplicate', 'id' => $id), 
            	'icon' => 'plus-sign', 
            	'htmlOptions' => array('title' => 'Click to duplicate '.$entity.' #'.$id),
            	),
        ),
    )
);
echo '</div>';

echo '</div>';
?>