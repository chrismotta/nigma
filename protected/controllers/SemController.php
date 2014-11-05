<?php

class SemController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('keywords','placement'),
				'roles'=>array('admin', 'sem'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionKeywords()
	{
		$this->renderPartial('report', array(
			'model'  => new ClicksLog,
			'report' => 'keyword',
		), false, true);
	}

	public function actionPlacement()
	{
		$this->renderPartial('report', array(
			'model'  => new ClicksLog,
			'report' => 'placement',
		), false, true);
	}
}