<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
#Yii::app()->authManager->createRole("media");
#Yii::app()->authManager->assign("admin",1);
#Yii::app()->authManager->assign("media",2);
#print(count(Yii::app()->authManager->getRoles()));
echo Yii::app()->user->id . " | ";
if(Yii::app()->authManager->checkAccess(Yii::app()->user->id, 'admin')){
	echo "--- ok ---";
}else{
	echo "--- no ---";
}

if(Yii::app()->user->checkAccess('admin')){
	echo "--- ok u ---";
}else{
	echo "--- no u ---";
}
	
	
?>
<h1>About</h1>

<p>This is a "static" page. You may change the content of this page
by updating the file <code><?php echo __FILE__; ?></code>.</p>
