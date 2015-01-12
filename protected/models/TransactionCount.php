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
 * @property integer $ios_id
 * @property integer $carriers_id_carrier
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
			array('period, volume, rate, users_id, date, ios_id', 'required'),
			array('volume, users_id, ios_id, carriers_id_carrier', 'numerical', 'integerOnly'=>true),
			array('rate', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, period, volume, rate, users_id, date, ios_id, carriers_id_carrier', 'safe', 'on'=>'search'),
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
			'users'    => array(self::BELONGS_TO, 'Users', 'users_id'),
			'ios'      => array(self::BELONGS_TO, 'Ios', 'ios_id'),
			'carriers' => array(self::BELONGS_TO, 'Carriers', 'carriers_id_carrier'),
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                  => 'ID',
			'period'              => 'Period',
			'volume'              => 'Volume',
			'rate'                => 'Rate',
			'users_id'            => 'Users',
			'date'                => 'Date',
			'ios_id'              => 'Ios',
			'carriers_id_carrier' => 'Carriers',
			'total'               => 'Total',
			'currency'            => 'Currency',
			'country'            => 'Country',
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
		$criteria->compare('ios_id',$this->ios_id);
		$criteria->compare('carriers_id_carrier',$this->carriers_id_carrier);
		$criteria->compare('country',$this->country);

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
		// $criteria->with=array('opportunities');
		$criteria->compare('ios_id',$ios_id);
		$criteria->compare('period',$period);
		return new CActiveDataProvider($this,array(
				'criteria'=>$criteria,
			));
	}

	public function getTotalTransactions($ios_id,$period)
	{
		$criteria=new CDbCriteria;
		$criteria->select='sum(t.rate*t.volume) as total';
		// $criteria->with=array('opportunities');
		// $criteria->addCondition('t.opportunities_id=opportunities.id');
		// $criteria->addCondition('opportunities.ios_id='.$ios_id);
		$criteria->compare('t.ios_id',$ios_id);
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
		// $criteria->with  =array('opportunities','opportunities.ios');
		$criteria->with  =array('ios');
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
		// $criteria->with  =array('opportunities','opportunities.ios','opportunities.ios.iosValidation');
		$criteria->with  =array('ios','ios.iosValidation');
		$criteria->compare('t.period',$period);		
		$criteria->compare('iosValidation.status','Invoiced');
		$criteria->select ='sum(t.rate*t.volume) as total, ios.currency as currency';
		$criteria->group  ='ios.currency';
		return Self::model()->findAll($criteria);
	}

	public function getTotalsCarrier($ios_id,$period)
	{
		$criteria=new CDbCriteria;
		$criteria->select='carriers_id_carrier,rate,sum(volume) as volume,sum(rate*volume) as total, product, country';
		$criteria->compare('ios_id',$ios_id);
		$criteria->compare('period',$period);
		$criteria->group='carriers_id_carrier,rate';
		return self::model()->findAll($criteria);
		
	}

	public function getCarrier()
	{
		return Carriers::model()->findByPk($this->carriers_id_carrier) ? Carriers::model()->findByPk($this->carriers_id_carrier)->mobile_brand : 'Multi';
	}

	public function getUserName()
	{
		return Users::model()->findByPk($this->users_id) ? Users::model()->findByPk($this->users_id)->lastname.' '.Users::model()->findByPk($this->users_id)->name : 'Error';
	}

	public function getCountry()
	{
		return GeoLocation::model()->findByPk($this->country) ? GeoLocation::model()->findByPk($this->country)->name : 'Error';
	}


}
