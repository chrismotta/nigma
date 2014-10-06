<?php

$basePath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions/highcharts/assets' . DIRECTORY_SEPARATOR;
$baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
$scriptFile = YII_DEBUG ? '/highcharts.src.js' : '/highcharts.js';

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerScriptFile($baseUrl . $scriptFile);