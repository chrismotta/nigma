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
		$criteria->order = 'date DESC';
		$criteria->condition = 'date<=Date(:date)';
		$criteria->limit = 0;
		$criteria->params = array(':date' => $date);
		return DailyTotals::model()->find( $criteria );
	}
	public function consolidated()
	{
		$day=0;
		$find_data=false;
		$totals=array();
		$totals['clicks']=0;
		$totals['impressions']=0;
		$totals['conversions']=0;
		$totals['revenue']=0;
		$totals['spend']=0;
		$dateStart=null;
		while (!$find_data) {
			$day++;			
			$dateStart=date('Y-m-d', strtotime('-'.$day.' day'));
			//SELECT sum(conv_adv) FROM `daily_report` WHERE DATE(date)='2014-10-06'
			$criteria=new CDbCriteria;
			$criteria->select='sum(conv_adv) as conversions';
			$criteria->addCondition("DATE(date)='".$dateStart."'");
			$daily=DailyReport::model()->find($criteria);
			if($daily->conversions>0)$find_data=true;
		}
		$dateRange=Utilities::dateRange($dateStart,date('Y-m-d', strtotime('yesterday')));
		foreach ($dateRange as $date) {
			$totals['clicks']=0;
			$totals['impressions']=0;
			$totals['conversions']=0;
			$totals['revenue']=0;
			$totals['spend']=0;
			$criteria=new CDbCriteria;
			$criteria->addCondition("DATE(date)='".$date."'");
			$daily=DailyReport::model()->findAll($criteria);
			foreach ($daily as $data) {				
				$totals['clicks']+=$data->clics;
				$totals['impressions']+=$data->imp_adv==0 ? $data->imp : $data->imp_adv;
				$totals['conversions']+=$data->conv_adv==0 ? $data->conv_api : $data->conv_adv;
				$totals['revenue']+=$data->getRevenueUSD();
				$totals['spend']+=$data->getSpendUSD();
			}
			$dailyTotal=DailyTotals::model()->findByDate($date) ? DailyTotals::model()->findByDate($date) : new DailyTotals;
			$dailyTotal->date=$date;
			$dailyTotal->clicks=$totals['clicks'];
			$dailyTotal->imp=$totals['impressions'];
			$dailyTotal->conv=$totals['conversions'];
			$dailyTotal->revenue=$totals['revenue'];
			$dailyTotal->spend=$totals['spend'];
			$dailyTotal->save();
		}
		
	}
}
