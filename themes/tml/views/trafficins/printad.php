<?php
switch($sizeID){
	//1: 300x250
	case 1:
		echo '<img src="'.Yii::app()->theme->baseUrl.'/img/Auto-Promo-300x250.gif" width="300" height="250" />';
		break;
	//2: 320x50
	case 2:
		echo '<img src="'.Yii::app()->theme->baseUrl.'/img/Auto-Promo-320x50.gif" width="320" height="50" />';
		break;
	//3: 300x50
	case 3:
		echo '<img src="'.Yii::app()->theme->baseUrl.'/img/Auto-Promo-300x50.gif" width="300" height="50" />';
		break;
	default:
		echo 'No banner match';
		break;
}

?>