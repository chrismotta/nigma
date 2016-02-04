## Nigma Appserver
### Duplicate Action
#### Set controller
- Create action
```
public function actionDuplicate($id) 
{
	$old = $this->loadModel($id);

	$new = clone $old;
	unset($new->id);
	$new->unsetAttributes(array('id'));
	$new->isNewRecord = true;
	
	// Uncomment the following line if AJAX validation is needed
	$this->performAjaxValidation($new);
	if(isset($_POST['<item>']))
	{
		var_dump($_POST['Placements']);
		$model=new <item>;
		$model->attributes       = $_POST['<item>'];
		if($model->save())
			$this->redirect(array('response', 'id'=>$model->id, 'action'=>'duplicate'));
	} 
	
	$this->renderFormAjax($new, 'duplicate');
}
```
- Add action 'duplicate' in the accessRules
- If controller has renderFormAjax add attibute $action=null and pass to de view in render
#### Set view
- Set non setteables attributes under if($action=='duplicate')
