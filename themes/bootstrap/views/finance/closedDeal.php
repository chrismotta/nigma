<?php
/* @var $this IosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Finance'=>'#',	
	'Clients',
);
 Yii::app()->clientScript->registerScript("", "$('.ipopover').popover({'placement':'left'});", CClientScript::POS_READY);
?>

<?php
//Totals
// echo KHtml::currencyTotalsClients($totals->getData());

$this->menu=array(
	array('label'=>'Create Ios', 'url'=>array('create')),
	array('label'=>'Manage Ios', 'url'=>array('admin')),
);
$log    =new ValidationLog;
$ios    =new Ios;

?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'date-filter-form',
		'type'                 =>'search',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>false,
		'action'               => Yii::app()->getBaseUrl() . '/finance/closedDeal',
		'method'               => 'GET',
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

	<fieldset>
		<?php
			$months[0]	='Select a month';
			$months[1]  ='January';
			$months[2]  ='February';
			$months[3]  ='March';
			$months[4]  ='April';
			$months[5]  ='May';
			$months[6]  ='June';
			$months[7]  ='July';
			$months[8]  ='August';
			$months[9]  ='September';
			$months[10] ='October';
			$months[11] ='November';
			$months[12] ='December';
			$years[0]   ='Select a year';
			foreach (range(date('Y'), 2014) as $y) {
				$years[$y]=$y;
			}

			$entities=KHtml::enumItem(new Ios,'entity');
			$entities[0]='All Entities';
			$categories=KHtml::enumItem(new Advertisers,'cat');
			$categories[0]='All Categories';
			$status=KHtml::enumItem(new IosValidation,'status');
			foreach ($status as $key => $value) {
				if($value=='Approved' || $value=='Expired')unset($status[$key]);
			}
			$status['ok']='Approved/Expired';
			$status['Not Sent']='Not Sent';
			$status[0]='All Status';
			echo $form->dropDownList(new DailyReport,'date',$months,array('name'=>'month', 'style'=>'width:15%;', 'options' => array(intval($month)=>array('selected'=>true))));
			echo $form->dropDownList(new DailyReport,'date',$years,array('name'=>'year', 'style'=>'width:15%; margin-left:1em;','options' => array($year=>array('selected'=>true))));
			echo $form->dropDownList(new Ios,'entity',$entities,array('name'=>'entity', 'style'=>'width:15%; margin-left:1em;','options' => array(isset($_GET['entity']) ? $_GET['entity'] : 0=>array('selected'=>true))));
			echo $form->dropDownList(new Advertisers,'cat',$categories,array('name'=>'cat', 'style'=>'width:15%; margin-left:1em;','options' => array(isset($_GET['cat']) ? $_GET['cat'] : 0=>array('selected'=>true))));
			echo $form->dropDownList(new IosValidation,'status',$status,array('name'=>'status', 'style'=>'width:15%; margin-left:1em;','options' => array(isset($_GET['status']) ? $_GET['status'] : 0=>array('selected'=>true))));
		
	?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Excel Report',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'excelReport?month='.$month.'&year='.$year.'&entity='.$entity.'&status='.$stat,
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalClients").html(dataInicial);
					$("#modalClients").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalClients").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'excelReport','style'=>'float:right'),
		)
	); ?>
	</div>
    </fieldset>

<?php $this->endWidget(); ?>
<?php 
	$this->widget('yiibooster.widgets.TbGroupGridView', array(
	'id'              => 'clients-grid',
	'dataProvider'    => $dataProvider,
	'filter'          => $filtersForm,
	'afterAjaxUpdate' =>'verifedIcon',
	'type'            => 'condensed',	 
	'template'        => '{items} {pager} {summary}',
	'columns'         => array(		
		array(
			'name'              => 'name',
			'value'             => '$data["id"] . " - " . $data["name"]',
			'htmlOptions'       => array('id'=>'alignLeft'),
			'headerHtmlOptions' => array('width' => '150'),
			'header'            => 'IO - Commercial Name',
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
			'name'              =>'conv',
			'header'            =>'Conversions',
			'value'             =>'number_format($data["conv"])',	
			'headerHtmlOptions' => array('width' => '80','style'=>'text-align:right;'),	
			'htmlOptions'       => array('style'=>'text-align:right;'),	
		),
		array(
			'name'              =>'imp',
			'header'            =>'Impressions',
			'value'             =>'number_format($data["imp"])',	
			'headerHtmlOptions' => array('width' => '80','style'=>'text-align:right;'),	
			'htmlOptions'       => array('style'=>'text-align:right;'),	
		),
		array(
			'name'              =>'clics',
			'header'            =>'Clicks',
			'value'             =>'number_format($data["clics"])',	
			'headerHtmlOptions' => array('width' => '80','style'=>'text-align:right;'),	
			'htmlOptions'       => array('style'=>'text-align:right;'),	
		),
		array(
			'name'              =>'name',
			'header'            =>'Total',
			'filter'			=>false,
			'value'             =>'number_format($data["total"],2)',
			'headerHtmlOptions' => array('width' => '80','style'=>'text-align:right;'),
			'htmlOptions'       => array('style'=>'text-align:right;'),	
		),
	),
	// 'mergeColumns' => array('name','opportunitie'),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalClients')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<div class="row" id="blank-row">
</div>

<?php Yii::app()->clientScript->registerScript('verifedIcon', "
						$('.link').click(function(e){
                            e.preventDefault();
                            var that = $(this);
							var link = that.attr('href');
							
							var dataInicial = '<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"".  Yii::app()->theme->baseUrl ."/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>';
							$('#modalClients').html(dataInicial);
							$('#modalClients').modal('toggle');
                           $.post( link, {})
								.success(function( data ) {
									$('#modalClients').html(data);
                                }

					
                                );
                            
                        });
					$('.linkinvoiced').click(function(e){
                            e.preventDefault();
                            
                        });
					function verifedIcon(){
                        $('.link').click(function(e){
                            e.preventDefault();
                            var that = $(this);
							var link = that.attr('href');

							var dataInicial = '<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"".  Yii::app()->theme->baseUrl ."/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>';
							$('#modalClients').html(dataInicial);
							$('#modalClients').modal('toggle');
                           $.post( link, {})
								.success(function( data ) {
									$('#modalClients').html(data);
									//Error en modal, se cerraba luego de abrirse. Ver con Santi.
									//$('#modalClients').modal('toggle');
                                }

					
                                );
                            
                        });
					}
                    ", CClientScript::POS_READY); ?>