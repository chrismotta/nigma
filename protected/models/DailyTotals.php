<?php

/**
 * This is the model class for table "daily_totals".
 *
 * The followings are the available columns in table 'daily_totals':
 * @property integer $id
 * @property string $date
 * @property integer $imp
 * @property integer $clicks
 * @property integer $conv
 * @property string $spend
 * @property string $revenue
 */
class DailyTotals extends CActiveRecord
{
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
			array('imp, clicks, conv', 'numerical', 'integerOnly'=>true),
			array('spend, revenue', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, imp, clicks, conv, spend, revenue', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'date' => 'Date',
			'imp' => 'Imp',
			'clicks' => 'Clicks',
			'conv' => 'Conv',
			'spend' => 'Spend',
			'revenue' => 'Revenue',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('imp',$this->imp);
		$criteria->compare('clicks',$this->clicks);
		$criteria->compare('conv',$this->conv);
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

	public function findByDate($date)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "DATE(date)='".$date."'";
		$criteria->limit = 0;
		return DailyTotals::model()->find($criteria);
	}

	public function getTotals($dateStart=null,$dateEnd=null)
	{		
		$spends      =array();
		$revenues    =array();
		$profits     =array();
		$impressions =array();
		$conversions =array();
		$clics       =array();
		$dates       =array();
		if(!$dateStart)	$dateStart = 'today' ;
		if(!$dateEnd) $dateEnd   = 'today';
		$dateStart = date('Y-m-d', strtotime($dateStart));
		$dateEnd = date('Y-m-d', strtotime($dateEnd));
		$criteria = new CDbCriteria;
		$criteria->addCondition("date>='".$dateStart."' AND date<='".$dateEnd."'");
		$criteria->order='date ASC';
		$totals=self::model()->findAll($criteria);
		foreach ($totals as $total) {
			$spends[]      =doubleval($total->spend);
			$revenues[]    =doubleval($total->revenue);
			$profits[]     =$total->revenue-$total->spend;
			$impressions[] =doubleval($total->imp);
			$conversions[] =doubleval($total->conv);
			$clics[]       =doubleval($total->clicks);
			$dates[]       =$total->date;
		}
		$result=array(
						'spends'      => $spends, 
						'revenues'    => $revenues, 
						'profits'     => $profits, 
						'impressions' => $impressions, 
						'conversions' => $conversions, 
						'clics'       => $clics, 
						'dates'       => $dates);           
		
		return $result;
	}

	public function consolidated($dateStart=null,$dateEnd=null)
	{
		$totals                =array();
		$totals['clicks']      =0;
		$totals['impressions'] =0;
		$totals['conversions'] =0;
		$totals['revenue']     =0;
		$totals['spend']       =0;
		$dateStart             =!$dateStart ? date('Y-m-d', strtotime('-4 day')) : $dateStart;
		$dateEnd               =!$dateEnd ? date('Y-m-d', strtotime('today')) : $dateEnd;
		$dateRange             =Utilities::dateRange($dateStart,$dateEnd);
		foreach ($dateRange as $date) {
			$totals['clicks']      =0;
			$totals['impressions'] =0;
			$totals['conversions'] =0;
			$totals['revenue']     =0;
			$totals['spend']       =0;
			$criteria              =new CDbCriteria;
			$criteria->addCondition("DATE(date)='".$date."'");
			$daily 				   =DailyReport::model()->findAll($criteria);
			foreach ($daily as $data) {				
				$totals['clicks']      +=$data->clics;
				$totals['impressions'] +=$data->imp_adv==0 ? $data->imp : $data->imp_adv;
				$totals['conversions'] +=$data->conv_adv==0 ? $data->conv_api : $data->conv_adv;
				$totals['revenue']     +=$data->getRevenueUSD();
				$totals['spend']       +=$data->getSpendUSD();
			}
			$dailyTotal = DailyTotals::model()->findByDate($date);  
			if(!$dailyTotal) $dailyTotal = new DailyTotals();
			$dailyTotal->date    =$date;
			$dailyTotal->clicks  =$totals['clicks'];
			$dailyTotal->imp     =$totals['impressions'];
			$dailyTotal->conv    =$totals['conversions'];
			$dailyTotal->revenue =$totals['revenue'];
			$dailyTotal->spend   =$totals['spend'];
			$isNew=$dailyTotal->getIsNewRecord();
			if($dailyTotal->save())
			{
				if($isNew)echo 'Daily Total: '.$dailyTotal->date.' - Clicks:'.$dailyTotal->clicks.' save!<br>';
				else echo 'Daily Total: '.$dailyTotal->date.' - Clicks:'.$dailyTotal->clicks.' update!<br>';
			}
			else 'Daily Total: '.$dailyTotal->date.' erro!<br>';
		}
		
	}
}
