<?php

/**
 * This is the model class for table "transaction_count".
 *
 * The followings are the available columns in table 'transaction_count':
 * @property integer $id
 * @property string $period
 * @property integer $volume
 * @property string $rate
 * @property integer $users_id
 * @property string $date
 * @property integer $opportunities_id
 *
 * The followings are the available model relations:
 * @property Users $users
 * @property Opportunities $opportunities
 */
class TransactionCount extends CActiveRecord
{
	public $total;
	public $currency;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaction_count';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('period, volume, rate, users_id, date, opportunities_id', 'required'),
			array('volume, users_id, opportunities_id', 'numerical', 'integerOnly'=>true),
			array('rate', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, period, volume, rate, users_id, date, opportunities_id', 'safe', 'on'=>'search'),
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
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
			'opportunities' => array(self::BELONGS_TO, 'Opportunities', 'opportunities_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'               => 'ID',
			'period'           => 'Period',
			'volume'           => 'Volume',
			'rate'             => 'Rate',
			'users_id'         => 'Users',
			'date'             => 'Date',
			'opportunities_id' => 'Opportunities',
			'total'            => 'Total',
			'currency'         => 'Currency',
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
		$criteria->compare('period',$this->period,true);
		$criteria->compare('volume',$this->volume);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('opportunities_id',$this->opportunities_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TransactionCount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTransactions($ios_id,$period)
	{
		$criteria=new CDbCriteria;
		$criteria->with=array('opportunities');
		$criteria->addCondition('t.opportunities_id=opportunities.id');
		$criteria->addCondition('opportunities.ios_id='.$ios_id);
		$criteria->compare('period',$period);
		return new CActiveDataProvider($this,array(
				'criteria'=>$criteria,
			));
	}

	public function getTotalTransactions($ios_id,$period)
	{
		$criteria=new CDbCriteria;
		$criteria->select='sum(t.rate*t.volume) as total';
		$criteria->with=array('opportunities');
		$criteria->addCondition('t.opportunities_id=opportunities.id');
		$criteria->addCondition('opportunities.ios_id='.$ios_id);
		$criteria->compare('t.period',$period);
		return self::model()->find($criteria) ? number_format(self::model()->find($criteria)['total'],2) : 0;
		
	}

	public function getTotalsCurrency($period)
	{
		// SELECT sum(t.rate*t.volume),i.currency FROM transaction_count t
		// inner join opportunities o on t.opportunities_id=o.id
		// inner join ios i on o.ios_id=i.id
		// group by i.currency;

		$criteria         =new CDbCriteria;
		$criteria->with  =array('opportunities','opportunities.ios');
		$criteria->compare('t.period',$period);		
		$criteria->select ='sum(t.rate*t.volume) as total, ios.currency as currency';
		$criteria->group  ='ios.currency';
		return Self::model()->findAll($criteria);
	}

	public function getTotalsInvoicedCurrency($period)
	{
		// SELECT sum(t.rate*t.volume),i.currency FROM transaction_count t
		// inner join opportunities o on t.opportunities_id=o.id
		// inner join ios i on o.ios_id=i.id
		// group by i.currency;

		$criteria         =new CDbCriteria;
		$criteria->with  =array('opportunities','opportunities.ios','opportunities.ios.iosValidation');
		$criteria->compare('t.period',$period);		
		$criteria->compare('iosValidation.status','Invoiced');
		$criteria->select ='sum(t.rate*t.volume) as total, ios.currency as currency';
		$criteria->group  ='ios.currency';
		return Self::model()->findAll($criteria);
	}
}
