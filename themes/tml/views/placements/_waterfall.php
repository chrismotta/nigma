<?php
/* @var $this PlacementsController */
/* @var $model Placements */
/* @var $form CActiveForm */
/* @var $sizes sizes[] */
/* @var $exchanges exchanges[] */
/* @var $publishers publishers[] */
?>

<?php 
/*
	if ( $model->isNewRecord ) {
        echo $form->dropDownListRow($model, 'publishers_name', $publishers, 
            array(
                'prompt'   => 'Select a publisher',
                'onChange' => '
                    if ( ! this.value) {
                        $(".sites-dropdownlist").html("<option value=\"\">Select a site</option>");
                        $(".sites-dropdownlist").prop( "disabled", true );
                        return;
                    }
                    $.post(
                        "getSites/"+this.value,
                        "",
                        function(data)
                        {
                            $(".sites-dropdownlist").html(data);
                            $(".sites-dropdownlist").prop("disabled", false);
                        }
                    )
                '
                ));
  		echo $form->dropDownListRow($model, 'sites_id', $sites, 
            array(
                'prompt'   => 'Select a site',
                'class'    => 'sites-dropdownlist',
                'disabled' => true,
                ));
  	}
    // echo $form->dropDownListRow($model, 'exchanges_id', $exchanges, array('prompt' => 'Select exchange'));
    echo $form->dropDownListRow($model, 'sizes_id', $sizes, array('prompt' => 'Select size'));
    
    echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
    echo $form->textFieldRow($model, 'product', array('class'=>'span3'));
*/    
?>

<?php
// JAVASCRIPT FUNCTIONS //

$ajaxSort = "
function ajaxSort(){
    
    // e.preventDefault();

    var pid = ".$placementsModel->id.";
    var eid = $('#waterfall-grid tbody tr').map(function(){return $(this).attr('data-row-eid');}).get();
    eid = JSON.stringify(eid);

    $.post(
        '". $this->createUrl('placements/waterfallSort/') ."',
       'pid='+pid+'&eid='+eid,
        function(data)
            {
                console.log(data);
                $.fn.yiiGridView.update('waterfall-grid');
            }
    );
};
";

Yii::app()->clientScript->registerScript('ajaxSort', $ajaxSort, CClientScript::POS_READY);

$customAdd = "
$('.customAdd').click(function(e){
    
    e.preventDefault();

    var pid = ".$placementsModel->id.";
    var eid = $(this).parents('tr').attr('data-row-eid');

    $.post(
        '". $this->createUrl('placements/waterfallAdd/') ."',
       'pid='+pid+'&eid='+eid,
        function(data)
            {
                console.log(data);
                $.fn.yiiGridView.update('exchanges-grid');
                $.fn.yiiGridView.update('waterfall-grid');
            }
    );
});
";

Yii::app()->clientScript->registerScript('customAdd', $customAdd, CClientScript::POS_READY);

$customDelete = "
$('.customDelete').click(function(e){

    e.preventDefault();

    var pid = ".$placementsModel->id.";
    var eid = $(this).parents('tr').attr('data-row-eid');

    $.post(
        '". $this->createUrl('placements/waterfallDel/') ."',
       'pid='+pid+'&eid='+eid,
        function(data)
            {
                console.log(data);
                $.fn.yiiGridView.update('exchanges-grid');
                $.fn.yiiGridView.update('waterfall-grid');
            }
    );
});
";

Yii::app()->clientScript->registerScript('customDelete', $customDelete, CClientScript::POS_READY);

?>

<div class='waterfall well'>
    <div class='custom-block1'>
        <h5>Current waterfall order:</h5>
        <?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'id'                       => 'waterfall-grid',
            'dataProvider'             => $waterfallModel->search(),
            // 'type'                     => 'condensed',
            'htmlOptions'              => array('style'=>'padding-top:0px;'),
            'rowHtmlOptionsExpression' => 'array("data-row-eid" => $data->exchanges_id)',
            'template'                 => '{items}',
            'afterAjaxUpdate'       => "function(){ ". $customDelete . "}",
            'sortableRows'             => true,
            'hideHeader'               => true,
            'afterSortableUpdate'      => 'js:function(id, position){ ajaxSort(); }',
            'columns'                  => array(
                array(
                    'type'        => 'raw',
                    'value'       => '"<i class=\"icon-resize-vertical\"></i>"',
                    'htmlOptions' => array('style' => 'width: 14px'),
                ),
                array(
                    'name'              => 'step',
                    'htmlOptions' => array('style' => 'width: 10px; text-align: right;'),
                    // 'visible'           => false,
                ),
                array(
                    'name'  => 'exchanges_name',
                    'value' => '$data->exchanges->name',
                    'htmlOptions' => array('style' => 'width: 138px;'),
                ),
                array(
                    'type'              => 'raw',
                    'header'            => '',
                    'filter'            => false,
                    'headerHtmlOptions' => array('width' => '40'),
                    'value'             => 'CHtml::ajaxLink("<i class=\"icon-trash\"></i>", "", array(), array(
                            "data-original-title" => "Delete",
                            "data-toggle"         => "tooltip",
                            "class"               => "customDelete"
                        ))',
                ),
            )
        )); ?>
    </div>
    <!-- <div class='custom-separator'></div> -->
    <div class='custom-block2'>
        <h5>Not assigned exchanges:</h5>
        <?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'id'                       => 'exchanges-grid',
            'dataProvider'             => $exchangesModel->search($placementsModel->id),
            // 'type'                     => 'condensed',
            'htmlOptions'              => array('style'=>'padding-top:0px;'),
            'rowHtmlOptionsExpression' => 'array("data-row-eid" => $data->id)',
            'template'                 => '{items}',
            'afterAjaxUpdate'       => "function(){ ". $customAdd . "}",
            'hideHeader'               => true,
            'columns'                  => array(
                array(
                    'name'  => 'name',
                    'htmlOptions' => array('style' => 'width: 138px;'),
                ),
                array(
                    'type'              => 'raw',
                    'header'            => '',
                    'filter'            => false,
                    'headerHtmlOptions' => array('width' => '40'),
                    'value'             => 'CHtml::ajaxLink(
                        "<i class=\"icon-plus\"></i>", 
                        array(), 
                        array(), 
                        array(
                            "data-original-title" => "Add Exchange",
                            "data-toggle"         => "tooltip",
                            "class"               => "customAdd",
                        ))',
                ),
            )
        )); ?>
    </div>
</div>
