<?php
/* @var $this ExternalFormsController */
/* @var $model RevenueValidation */
$io_id =$model->ios_id;
$month =date('m', strtotime($model->period));
$year  =date('Y', strtotime($model->period));
$ios=new Ios;
$io=$ios->findByPk($io_id);
$clients =$ios->getClients($month,$year,null,$io_id);

?>
<h4><?php echo $io->commercial_name; ?></h4>

<h5>Commercial Contact: <?php echo $io->contact_com; ?></h5>
<h5>Administrative Contact: <?php echo $io->contact_adm; ?></h5>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eleifend magna libero, suscipit vehicula ex laoreet eleifend. Morbi gravida ipsum augue, quis bibendum odio viverra eu. Ut efficitur sem at lacus interdum euismod. Vivamus et lacinia ante. </p>
<?php
$dataProvider=new CArrayDataProvider($clients, array(
    'id'=>'clients',
    'sort'=>array(
        'attributes'=>array(
             'id', 'name', 'model', 'entity', 'currency', 'rate', 'conv','revenue', 'carrier'
        ),
    ),
    'pagination'=>array(
        'pageSize'=>30,
    ),
));
    $this->widget('yiibooster.widgets.TbGroupGridView', array(
    'id'                         => 'revenue-validation-grid',
    //'fixedHeader'              => true,
    //'headerOffset'             => 50,
    'dataProvider'               => $dataProvider,
    //'filter'                     => $filtersForm,
    //'filter'                   => $model,
    'type'                       => 'striped condensed',    
    //'rowHtmlOptionsExpression'   => 'array("data-row-id" => "1")',
    //'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id, "data-row-net-id" => $data->networks_id, "data-row-c-id" => $data->campaigns_id)',
    'template'                   => '{items} {pager} {summary}',
    'columns'                    => array(
        array(
            'name'              =>'model',
            'value'             =>'$data["model"]', 
            'headerHtmlOptions' => array('width' => '80'),
            'header'            =>'Model',      
            ),
        array(
            'name'              =>'entity',
            'value'             =>'$data["entity"]',
            'headerHtmlOptions' => array('width' => '80'),  
            'header'            =>'Entity',    
            ),  
        array(
            'name'              =>'currency',
            'value'             =>'$data["currency"]',
            'headerHtmlOptions' => array('width' => '80'),      
            'header'            =>'Currency',   
            ),
        array(
            'name'              =>'rate',
            'value'             =>'$data["rate"] ? $data["rate"] : "Multi"',
            'headerHtmlOptions' => array('width' => '80'),  
            'htmlOptions'       => array('style'=>'text-align:right;'), 
            //'footer'          => $totals['rate'],
            'header'            =>'Rate',   
        ),  
        array(
            'name'              =>'conv',
            'header'            =>'Clics/Imp/Conv',
            'value'             =>'$data["conv"]',  
            'headerHtmlOptions' => array('width' => '80'),  
            'htmlOptions'       => array('style'=>'text-align:right;'), 
            //'footer'          => $totals['conv'],
        ),
        array(
            'name'              =>'revenue',
            'header'            =>'Revenue',
            'value'             =>'$data["revenue"]',
            'headerHtmlOptions' => array('width' => '80'),
            'htmlOptions'       => array('style'=>'text-align:right;'),     
            //'footer'          => $totals['revenue'],
        ),
    ),
));
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'revenue-validation-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
<fieldset>
	<?php
	echo $form->textArea(new RevenueValidation,
            'comment',
            array(
                'name'=>'comment',
                //'style' => "width: 140px; margin-left: 1em",
                //'options' => array(isset($_GET['entity']) ? $_GET['entity'] : 0=>array('selected'=>true))
                )
            );
	?>
<div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit', 'htmlOptions' => array('name' => 'revenue-validation-form'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'No-Submit', 'htmlOptions' => array('name' => 'revenue-validation-form'))); ?>
    </div>

</fieldset>    
<?php $this->endWidget(); ?>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'mail-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
<fieldset>
<p>Si no eres el responsable de esta información brindanos el e-mail del responsable</p>
	<?php
	echo $form->textField(new Ios,
            'email_validation',
            array(
                'name'=>'email_validation',
                //'style' => "width: 140px; margin-left: 1em",
                //'options' => array(isset($_GET['entity']) ? $_GET['entity'] : 0=>array('selected'=>true))
                )
            );
	?>
<div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit', 'htmlOptions' => array('name' => 'mail-form'))); ?>
    </div>

</fieldset>   
<?php $this->endWidget(); ?>