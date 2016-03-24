<?php
echo CHtml::beginForm('', 'POST', array(
	'id'    =>'filter-form',
	'class' =>'well form-search',
	));

$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : 'yesterday' ;
$tagid = isset($_REQUEST['tagid']) ? $_REQUEST['tagid'] : '' ;
$cpm = isset($_REQUEST['cpm']) ? $_REQUEST['cpm'] : '' ;
$pubid = isset($_REQUEST['pubid']) ? $_REQUEST['pubid'] : '' ;

echo KHtml::datePicker('date', $date, array(), array('style'=>'width:100px'), 'Date');

echo "<span class='formfilter-space'></span>";

echo '<label><div class="input-append input-prepend">';
echo '<span class=" btn btn-info disabled" style="width:35px">TagID</span>';
echo CHtml::textField('tagid', $tagid, array('style'=>'width:100px'));
echo '</div></label>';

echo "<span class='formfilter-space'></span>";

echo '<label><div class="input-append input-prepend">';
echo '<span class=" btn btn-info disabled" style="width:35px">CPM $</span>';
echo CHtml::textField('cpm', $cpm, array('style'=>'width:100px'));
echo '</div></label>';

echo "<span class='formfilter-space'></span>";

echo '<label><div class="input-append input-prepend">';
echo '<span class=" btn btn-info disabled" style="width:35px">PubID</span>';
echo '<span class=" add-on" style="width:35px">';
echo CHtml::checkBox('pubid', $pubid, array('style'=>'vertical-align: top;'));
echo '</span>';
echo '</div></label>';

echo "<span class='formfilter-space'></span>";

$this->widget('bootstrap.widgets.TbButton', 
		array(
			'buttonType'=>'submit', 
			'label'=>'Submit', 
			'type' => 'success', 
			'htmlOptions' => array('class' => 'showLoading')
			)
		); 


echo CHtml::endForm(); ?>

<br/>

<?php

function build_table($array){

    // start table
    $html = '<table border="1" style="border: 1px solid black">';
    // header row
    $html .= '<tr>';
    foreach($array[0] as $key=>$value){
            $html .= '<th>' . $key . '</th>';
        }
    $html .= '</tr>';

    // data rows
    foreach( $array as $key=>$value){
        $html .= '<tr>';
        foreach($value as $key2=>$value2){
            $html .= '<td>' . $value2 . '</td>';
        }
        $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

if(isset($data)) 
	if(is_array($data))
		if($data)
			echo build_table($data);
		else 
			echo 'No data found';
	else if(is_string($data))
		echo $data;
?>

<br/>
<br/>