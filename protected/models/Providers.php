<?php

/**
 * This is the model class for table "providers".
 *
 * The followings are the available columns in table 'providers':
 * @property integer $id
 * @property string $prefix
 * @property string $name
 * @property string $status
 * @property string $currency
 * @property integer $has_s2s
 * @property integer $has_token
 * @property string $callback
 * @property string $placeholder
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
			array('name, status', 'required'),
			array('id, has_s2s, has_token', 'numerical', 'integerOnly'=>true),
			array('prefix, placeholder', 'length', 'max'=>45),
			array('name', 'length', 'max'=>128),
			array('status', 'length', 'max'=>8),
			array('callback', 'url'),
			array('currency', 'length', 'max'=>3),
			array('callback', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prefix, name, status, currency, has_s2s, has_token, callback, placeholder', 'safe', 'on'=>'search'),
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
			'id'          => 'ID',
			'prefix'      => 'Prefix',
			'name'        => 'Name',
			'status'      => 'Status',
			'currency'    => 'Currency',
			'has_s2s'     => 'Has s2s',
			'has_token'   => 'Has Token',
			'callback'    => 'Callback',
			'placeholder' => 'Placeholder',
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
		$criteria->compare('status',$this->status,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('has_s2s',$this->has_s2s);
		$criteria->compare('has_token',$this->has_token);
		$criteria->compare('callback',$this->callback,true);
		$criteria->compare('placeholder',$this->placeholder,true);

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


	public function isPublisher()
	{
		return Publihsers::model()->exists('providers_id=:id', array(':id' => $this->id));
	}


	public function isAffiliate()
	{
		return Affiliates::model()->exists('providers_id=:id', array(':id' => $this->id));
	}


	public function getType()
	{
		if ($this->isAffiliate())
			return 1;
		if ($this->isNetwork())
			return 2;
		if ($this->isProvider())
			return 3;
		return NULL;
	}


	public function findAllByType($type)
	{
		switch ($type) {
			case 1:
				return Affiliates::model()->with('providers')->findAll();
			case 2:
				return Networks::model()->with('providers')->findAll();
			case 3:
				return Publishers::model()->with('providers')->findAll();
		}
		return array();
	}


	public function getAllTypes()
	{
		return array(
			1 => 'Affiliates', 
			2 => 'Networks', 
			3 => 'Publishers',
		);
	}
}
