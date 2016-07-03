<?php

/**
 * This is the model class for table "daily_totals".
 *
 * The followings are the available columns in table 'daily_totals':
 * @property integer $id
 * @property string $date
 * @property integer $imp
 * @property integer $clicks
 * @property integer $clicks_redirect
 * @property integer $conv_s2s
 * @property integer $conv
 * @property string $spend
 * @property string $revenue
 */
class DailyTotals extends CActiveRecord
{
	public $clicks_redirect;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daily_totals';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date', 'required'),
			array('imp, clicks, clicks_redirect, conv, conv_s2s', 'numerical', 'integerOnly'=>true),
			array('spend, revenue', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, cat, imp, clicks, conv, spend, revenue', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'              => 'ID',
			'date'            => 'Date',
			'cat'            => 'Category',
			'imp'             => 'Imp',
			'clicks'          => 'Clicks',
			'clicks_redirect' => 'Clicks',
			'conv'            => 'Conv',
			'conv_s2s'        => 'Conv',
			'spend'           => 'Spend',
			'revenue'         => 'Revenue',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('cat',$this->cat);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('imp',$this->imp);
		$criteria->compare('clicks',$this->clicks);
		$criteria->compare('clicks_redirect',$this->clicks_redirect);
		$criteria->compare('conv',$this->conv);
		$criteria->compare('conv_s2s',$this->conv_s2s);
		$criteria->compare('spend',$this->spend,true);
		$criteria->compare('revenue',$this->revenue,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DailyTotals the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function findByDateCat($date, $cat)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "DATE(date)='".$date."'";
		$criteria->compare('cat', $cat);
		$criteria->limit = 0;
		$model = DailyTotals::model()->find($criteria);

		if(!$model){
			$model = new DailyTotals();
			$model->date =$date;
			$model->cat  =$cat;
		}

		return $model;
		
	}

	public function getTotals($dateStart, $dateEnd, $cats=null)
	{

		$dateStart = date('Y-m-d', strtotime($dateStart));
		$dateEnd   = date('Y-m-d', strtotime($dateEnd));
		$cats =! $cats ? array('Incent', 'Branding') : $cats;

		$criteria = new CDbCriteria;
		$criteria->addCondition('date BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"');
		$criteria->compare('cat', $cats);

		$criteria->select = array(
			'date',
			'SUM(revenue) AS revenue',
			'SUM(spend) AS spend',
			);
		
		$criteria->group = 'date';
		$criteria->order = 'date ASC';
		$totals = self::model()->findAll($criteria);

		foreach ($totals as $total) {

			$return['spends'][]   = floatval($total->spend);
			$return['revenues'][] = floatval($total->revenue);
			$return['profits'][]  = floatval($total->revenue - $total->spend);
			// $return['impressions'][]    =doubleval($total->imp);
			// $return['conversions'][]    =doubleval($total->conv);
			// $return['conversions_s2s'][]=doubleval($total->conv_s2s);
			// $return['clics'][]          =doubleval($total->clicks);
			// $return['clics_redirect'][] =doubleval($total->clicks_redirect);
			$return['dates'][]    = $total->date;
		}

		return $return;          
		
	}

	public function consolidated($dateStart=null,$dateEnd=null)
	{

		$dateStart =! $dateStart ? date('Y-m-d', strtotime('-4 day')) : $dateStart;
		$dateEnd   =! $dateEnd ? date('Y-m-d', strtotime('today')) : $dateEnd;
		
		/*
		$totals                    =array();
		$totals['clicks']          =0;
		$totals['clicks_redirect'] =0;
		$totals['impressions']     =0;
		$totals['conversions']     =0;
		$totals['conversions_s2s'] =0;
		$totals['revenue']         =0;
		$totals['spend']           =0;

		$dateRange =  Utilities::dateRange($dateStart,$dateEnd);

		foreach ($dateRange as $date) {	
			$criteriaConv         =new CDbCriteria;
			$criteriaConv->select ='count(*) as conv';
			$criteriaConv->addCondition("DATE(date)='".$date."'");
			
			$criteriaClicks         =new CDbCriteria;
			$criteriaClicks->select ='count(*) as clics';
			$criteriaClicks->addCondition("DATE(date)='".$date."'");

			$totals['conversions']     =0;
			$totals['clicks']          =0;
			$totals['impressions']     =0;
			$totals['revenue']         =0;
			$totals['spend']           =0;

			$totals['clicks_redirect'] =ClicksLog::model()->find($criteriaClicks)->clics;
			$totals['conversions_s2s'] =ConvLog::model()->find($criteriaConv)->conv;

			$criteria              =new CDbCriteria;
			$criteria->addCondition("DATE(date)='".$date."'");
			$daily 				   =DailyReport::model()->findAll($criteria);

			foreach ($daily as $data) {			
				$totals['conversions'] +=$data->conv_adv==0 ? $data->conv_api : $data->conv_adv;	
				$totals['clicks']      +=$data->clics;
				$totals['impressions'] +=$data->imp_adv==0 ? $data->imp : $data->imp_adv;
				$totals['revenue']     +=$data->getRevenueUSD();
				$totals['spend']       +=$data->getSpendUSD();
			}

			$cat = 'VAS';

			$dailyTotal = DailyTotals::model()->findByDateCat($date, $cat);
			$dailyTotal->clicks_redirect =$totals['clicks_redirect'];
			$dailyTotal->conv_s2s        =$totals['conversions_s2s'];
			$dailyTotal->clicks          =$totals['clicks'];
			$dailyTotal->imp             =$totals['impressions'];
			$dailyTotal->conv            =$totals['conversions'];
			$dailyTotal->revenue         =$totals['revenue'];
			$dailyTotal->spend           =$totals['spend'];
			$isNew                       =$dailyTotal->getIsNewRecord();
			
			if($dailyTotal->save())
			{
				if($isNew)echo 'Daily Total: '.$dailyTotal->date.' - Clicks:'.$dailyTotal->clicks_redirect.' save!<br>';
				else echo 'Daily Total: '.$dailyTotal->date.' - Clicks:'.$dailyTotal->clicks_redirect.' update!<br>';
			}
			else 'Daily Total: '.$dailyTotal->date.' error!<br>';
		}
		*/

		$dailySumsCriteria = new CDbCriteria();
		$dailySumsCriteria->addCondition('date BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"');
		$dailySumsCriteria->with = array('campaigns.opportunities.regions.financeEntities.advertisers');
		$dailySumsCriteria->select = array(
			'date',
			'advertisers.cat AS advertiser_cat',
			'SUM(revenue) AS revenue',
			'SUM(spend) AS spend',
			);
		$dailySumsCriteria->group = 'date, advertisers.cat';
		
		$dailySums = DailyReport::model()->findAll($dailySumsCriteria);

		foreach ($dailySums as $row) {

			echo $row->date;
			echo ' - ';
			echo $row->advertiser_cat;
			echo ' - ';
			echo $row->revenue;
			echo ' - ';
			echo $row->spend;
			echo '<br>';
			
			$dailyTotal = DailyTotals::model()->findByDateCat($row->date, $row->advertiser_cat);

			$dailyTotal->revenue         =$row->revenue;
			$dailyTotal->spend           =$row->spend;
			$isNew                       =$dailyTotal->getIsNewRecord();
			
			if($dailyTotal->save()){
				if($isNew)echo 'DailyTotal => saved!';
				else echo 'DailyTotal => updated!';
			}
			else 'DailyTotal => error!<br>'. json_encode($dailyTotal->getErrors());
			
			echo '<hr>';
		}
		
		
	}
}
