<?php

/**
 * This is the model class for table "providers".
 *
 * The followings are the available columns in table 'providers':
 * @property integer $id
 * @property string $prefix
 * @property string $name
 * @property string $currency
 *
 * The followings are the available model relations:
 * @property Affiliates $affiliates
 * @property ApiCronLog[] $apiCronLogs
 * @property Campaigns[] $campaigns
 * @property ClicksLog[] $clicksLogs
 * @property DailyReport[] $dailyReports
 * @property Networks $networks
 * @property Publishers $publishers
 * @property Vectors[] $vectors
 */
class Providers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'providers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('prefix', 'length', 'max'=>45),
			array('name', 'length', 'max'=>128),
			array('currency', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prefix, name, currency', 'safe', 'on'=>'search'),
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
			'affiliates'   => array(self::HAS_ONE, 'Affiliates', 'providers_id'),
			'apiCronLogs'  => array(self::HAS_MANY, 'ApiCronLog', 'providers_id'),
			'campaigns'    => array(self::HAS_MANY, 'Campaigns', 'providers_id'),
			'clicksLogs'   => array(self::HAS_MANY, 'ClicksLog', 'providers_id'),
			'dailyReports' => array(self::HAS_MANY, 'DailyReport', 'providers_id'),
			'networks'     => array(self::HAS_ONE, 'Networks', 'providers_id'),
			'publishers'   => array(self::HAS_ONE, 'Publishers', 'providers_id'),
			'vectors'      => array(self::HAS_MANY, 'Vectors', 'providers_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'       => 'ID',
			'prefix'   => 'Prefix',
			'name'     => 'Name',
			'currency' => 'Currency',
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
		$criteria->compare('prefix',$this->prefix,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('currency',$this->currency,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Providers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function isNetwork()
	{
		return Networks::model()->exists('providers_id=:id', array(':id' => $this->id));
	}

	public function isProvider()
	{
		return Providers::model()->exists('providers_id=:id', array(':id' => $this->id));
	}

	public function isAffiliate()
	{
		return Affiliates::model()->exists('providers_id=:id', array(':id' => $this->id));
	}
}
