<?php

/**
 * This is the model class for table "carriers".
 *
 * The followings are the available columns in table 'carriers':
 * @property integer $id_carrier
 * @property string $status
 * @property integer $id_country
 * @property string $mobile_brand
 * @property string $isp
 * @property string $domain
 *
 * The followings are the available model relations:
 * @property GeoLocation $idCountry
 * @property Opportunities[] $opportunities
 * @property MultiRate[] $multiRates
 */



class Carriers extends CActiveRecord
{

	public $country_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'carriers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'required'),
			array('id_country', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>8),
			array('mobile_brand, domain', 'length', 'max'=>128),
			array('isp', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_carrier, status, id_country, mobile_brand, isp, domain', 'safe', 'on'=>'search'),
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
			'idCountry' => array(self::BELONGS_TO, 'GeoLocation', 'id_country'),
			'opportunities' => array(self::HAS_MANY, 'Opportunities', 'carriers_id'),
			'multiRates' => array(self::HAS_MANY, 'MultiRate', 'carriers_id_carrier'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_carrier' => 'Id Carrier',
			'status' => 'Status',
			'id_country' => 'Id Country',
			'mobile_brand' => 'Mobile Brand',
			'isp' => 'Isp',
			'domain' => 'Domain',
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

		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('id_country',$this->id_country);
		$criteria->compare('mobile_brand',$this->mobile_brand,true);
		$criteria->compare('isp',$this->isp,true);
		$criteria->compare('domain',$this->domain,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function getCountryById($id)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("id_carrier='".$id."'");
		return self::model()->find($criteria)->id_country;
	}
	public function getMobileBrandById($id)
	{
		if ($id == NULL) {
			return 'MULTI';
		}
		$criteria = new CDbCriteria;
		$criteria->addCondition("id_carrier='".$id."'");
		return self::model()->find($criteria)->mobile_brand;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Carriers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
