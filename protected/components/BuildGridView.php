<?php
class BuildGridView
{

    public static function printModal($controller, $idModal, $tittle='', $htmlOptions=array())
    {
    	$htmlOptions['data-backdrop'] = 'static';
        $controller->beginWidget('bootstrap.widgets.TbModal', 
        	array(
        		'id'=>$idModal,
        		'htmlOptions' => $htmlOptions,
        		));
            echo '<div class="modal-header"></div>';
            echo '<div class="modal-body"><h1>'.$tittle.'</h1></div>';
            echo '<div class="modal-footer"></div>';
        $controller->endWidget();
    }

    public static function createButton($controller, $url, $modalId, $gridId, $label, $isArchived=false)
    {
    	if( !$isArchived )  :
			echo '<div class="botonera">';
			
			// $controller->widget('bootstrap.widgets.TbButton', array(
			// 	'type'        => 'info',
			// 	'label'       => $label,//'Create Opportunity',
			// 	'block'       => false,
			// 	'buttonType'  => 'ajaxButton',
			// 	'url'         => $url,
			// 	'ajaxOptions' => array(
			// 		'type'    => 'POST',
			// 		'beforeSend' => 'function(data)
			// 			{
			// 		    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
			// 				$("#'.$modalId.'").html(dataInicial);
			// 				$("#'.$modalId.'").modal("toggle");
			// 			}',
			// 		'success' => 'function(data)
			// 			{
			//                     // console.log(this.url);
			// 	                //alert("create");
			// 					$("#'.$modalId.'").html(data);
			// 			}',
			// 		),
			// 	'htmlOptions' => array('id' => 'create'),
			// 	)
			// );

			$controller->widget('bootstrap.widgets.TbButton', array(
				'type'        => 'info',
				'label'       => $label,
				'block'       => false,
				'buttonType'  => 'linkButton',
				'url'         => $url,
				'htmlOptions' => array(
					"data-grid-id"      => $gridId, 
					"data-modal-id"     => $modalId, 
					"data-modal-title"  => $label, 
					'onclick'           => 'event.preventDefault(); openModal(this)',
					),
				)
			);

			echo '</div>';
		endif;
    }

    public static function buttonColumn($modalId, $gridId, $label, $isArchived=false)
    {
    	if($isArchived) {
			$delete['icon']       = 'refresh';
			$delete['label']      = 'Restore';
			$delete['confirm']    = 'Are you sure you want to restore this site?';
		} else {
			$delete['icon']       = 'trash';
			$delete['label']      = 'Archive';
			$delete['confirm']    = 'Are you sure you want to archive this site?';
		}

		// $controller = Yii::app()->controller->id;
		// $action = Yii::app()->controller->action->id;
		$baseUrl = Yii::app()->createUrl( Yii::app()->controller->getId() );

    	$return = array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 70px"),
			'htmlOptions' => array('onclick' => 'prevent=1;'),
			'afterDelete'       => 'function(link, success, data) { if(data) alert(data); }',
			'buttons'           => array(
				'viewAjax' => array(
					'label' =>'Detail',
					'icon'  =>'eye-open',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#'.$modalId.'").html(dataInicial);
						$("#'.$modalId.'").modal("toggle");

				    	$.post(
						"'.$baseUrl.'/view/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#'.$modalId.'").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'updateIframe' => array(
					'label' => 'Update',
					'icon'  => 'pencil',
					'url'     => 'array("update", "id" => $data->id)',
					'options' => array(
						"data-grid-id"      => $gridId, 
						"data-modal-id"     => $modalId, 
						"data-modal-title"  => $label, 
						'onclick'           => 'event.preventDefault(); openModal(this)',
						),
					),
				'updateAjax' => array(
					'label' => 'Update',
					'icon'  => 'pencil',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#'.$modalId.'").html(dataInicial);
						$("#'.$modalId.'").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'.$baseUrl.'/update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#'.$modalId.'").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'duplicateAjax' => array(
					'label' => 'Duplicate',
					'icon'  => 'plus-sign',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#'.$modalId.'").html(dataInicial);
						$("#'.$modalId.'").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'.$baseUrl.'duplicate/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#'.$modalId.'").html(data);
							}
						)
						return false;
				    }
				    ',
				),
			),
			'deleteButtonIcon'   => $delete['icon'],
			'deleteButtonLabel'  => $delete['label'],
			'deleteConfirmation' => $delete['confirm'],
			'template' => '{viewAjax} {duplicateAjax} {updateIframe} {delete}',
		);
		
		return $return;
    }
}
?>