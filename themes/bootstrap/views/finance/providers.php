<?php
/* @var $this IosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Finance'=>'#',	
	'Providers',
);
?>
<?php 
//Totals
echo KHtml::currencyTotals($totals->getData());
	$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'date-filter-form',
		'type'                 =>'search',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'action'               => Yii::app()->getBaseUrl() . '/finance/providers',
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

			$criteria                       =new CDbCriteria;
			$criteria->select               ='entity';
			$criteria->group                ='entity';
			$criteria->addCondition('entity !=""');
			$io                             =new Ios;
			$entity                         =$io->findAll($criteria);
			$entities[0]                    ='All entities';
			foreach ($entity as $value) {
				$entities[$value->entity]=$value->entity;
			}
		echo $form->dropDownList(new DailyReport,'date',$months,array('name'=>'month', 'options' => array(intval($month)=>array('selected'=>true))));
		echo $form->dropDownList(new DailyReport,'date',$years,array('name'=>'year','options' => array($year=>array('selected'=>true))));
		
		            ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Excel Report',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'excelReportProviders?month='.$month.'&year='.$year,
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalProviders").html(dataInicial);
					$("#modalProviders").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalProviders").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'excelReportProviders','style'=>'float:right'),
		)
	); ?>
	</div>
    </fieldset>

<?php $this->endWidget(); ?>


<?php 
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'providers-grid',
	'dataProvider'             => $arrayProvider,
	'filter'                   => $filtersForm,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data["id"])',
	'template'                 => '{items} {pager} {summary}',
	'columns'=>array(
		array(
			'name'        =>'id',
			'value'       =>'$data["id"]',
			'htmlOptions' =>array('style' => 'width: 100px'),
			'header'      =>'ID',    
		),
		array(
			'name'        =>'providers_name',
			'value'       =>'$data["providers_name"]',
			'htmlOptions' =>array('style' => 'width: 100px'),
			'header'      =>'Network Name',  
		),
		array(
			'name'        =>'currency',
			'value'       =>'$data["currency"]',
			'htmlOptions' =>array('style' => 'width: 100px'),
			'header'      =>'Currency',  
		),
		array(
			'name'        =>'clics',
			'value'       =>'number_format($data["clics"],2)',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Clicks',  
		),
		array(
			'name'        =>'imp',
			'value'       =>'number_format($data["imp"],2)',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Imp.',  
		),
		array(
			'name'        =>'percent_off',
			'value'       =>'is_null($data["percent_off"]) ? "0%" : number_format($data["percent_off"]*100,0)."%"',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Percent Off',  
		),
		array(
			'name'        =>'spend',
			'header'      =>'Subtotal',
			'value'       =>'number_format($data["spend"],2)',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Spend',  
		),
		array(
			'name'        =>'off',
			'value'       =>'number_format($data["off"],2)',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Off',  
		),
		array(
			'name'        =>'subTotal',
			'value'       =>'number_format($data["total"],2)',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Sub Total',  
		),
		array(
			'type'              =>'raw',
			'header'            =>'',
			'filter'            =>false,
			'headerHtmlOptions' => array('width' => '20'),
			'name'              =>	'name',
			'value'             =>'
				CHtml::link(
					"<i class=\"icon-pencil\"></i>",
					array("finance/transactionProviders/?id=".$data["id"]."&period='.$year.'-'.$month.'-01"),
    				array("class"=>"link", "data-toggle"=>"tooltip", "data-original-title"=>"Count")


					);
				',		
		),
		array(
			'name'        =>'transaction',
			'header'      =>'Transaction',
			'value'       =>'number_format($data["transaction"],2)',
			'htmlOptions' =>array('style' => 'width: 100px;'),
		),
		array(
			'name'        =>'total',
			'value'       =>'number_format($data["total"]+$data["transaction"],2)',
			'htmlOptions' =>array('style' => 'width: 100px;'),
			'header'      =>'Total',  
		),

	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalProviders')); ?>

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
							$('#modalProviders').html(dataInicial);
							$('#modalProviders').modal('toggle');
                           $.post( link, {})
								.success(function( data ) {
									$('#modalProviders').html(data);
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
							$('#modalProviders').html(dataInicial);
							$('#modalProviders').modal('toggle');
                           $.post( link, {})
								.success(function( data ) {
									$('#modalProviders').html(data);
									//Error en modal, se cerraba luego de abrirse. Ver con Santi.
									//$('#modalProviders').modal('toggle');
                                }

					
                                );
                            
                        });
					}
                    ", CClientScript::POS_READY); ?>