## Nigma Appserver
### Modal Iframe Implementation
#### Set buttons in Admin view
- Create
```
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'type'        => 'info',
	'label'       => 'Create <item>',
	'block'       => false,
	'buttonType'  => 'linkButton',
	'url'         => 'create',
	'htmlOptions' => array(
		"data-grid-id"      => "<item>-grid", 
		"data-modal-id"     => "modal<item>", 
		"data-modal-title"  => "Create <item>", 
		'onclick'           => 'event.preventDefault(); openModal(this)',
		),
	)
); ?>
```
- Update
```
'updateIframe' => array(
	'label' => 'Update',
	'icon'  => 'pencil',
	'url'     => 'array("update", "id" => $data->id)',
	'options' => array(
		"data-grid-id"      => "<item>-grid", 
		"data-modal-id"     => "modal<item>", 
		"data-modal-title"  => "Update <item>", 
		'onclick'           => 'event.preventDefault(); openModal(this)',
		),
	),
```
- Duplicate
```
'duplicateIframe' => array(
	'label' => 'Duplicate',
	'icon'  => 'plus-sign',
	'url'     => 'array("duplicate", "id" => $data->id)',
	'options' => array(
		"data-grid-id"      => "<item>-grid", 
		"data-modal-id"     => "modal<item>", 
		"data-modal-title"  => "Duplicate <item>", 
		'onclick'           => 'event.preventDefault(); openModal(this)',
		),
	),
```
- Add new buttons on the template and remove previous
```
'template' => '... {duplicateIframe} {updateIframe}',
```
#### Set _form.php
- Remove modal tags
	- modal-header
	- modal-body
	- modal-footer
#### Set controller action
- Set layout
```
$this->layout = '//layouts/modalIframe';
```
- Switch renderPartial() by render() 
- Set submit redirect (standard cases)
```
$this->redirect(array('response', 'id'=>$model->id, 'action'=>'[created|update|duplicate]'));
```
- Set response action
```
public function actionResponse($id){
	$action = isset($_GET['action']) ? $_GET['action'] : 'created';
	$this->layout='//layouts/modalIframe';
	$this->render('//layouts/mainResponse',array(
		'entity' => '<item>',
		'action' => $action,
		'id'    => $id,
	));
}
```
- Add action 'response' in the accessRules
