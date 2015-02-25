<?php

/**
 * This is the model class for table "geo_location".
 *
 * The followings are the available columns in table 'geo_location':
 * @property integer $id_location
 * @property string $status
 * @property string $name
 * @property string $detail
 * @property string $code
 * @property string $ISO2_CITY
 * @property string $ISO2
 * @property string $ISO3
 * @property string $type
 *
 * The followings are the available model relations:
 * @property Carriers[] $carriers
 * @property Ios[] $ioses
 * @property Opportunities[] $opportunities
 */
class GeoLocation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'geo_location';
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
			array('id_location', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>8),
			array('name, detail, code', 'length', 'max'=>255),
			array('ISO2_CITY', 'length', 'max'=>10),
			array('ISO2', 'length', 'max'=>2),
			array('ISO3', 'length', 'max'=>3),
			array('type', 'length', 'max'=>22),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_location, status, name, detail, code, ISO2_CITY, ISO2, ISO3, type', 'safe', 'on'=>'search'),
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
			'carriers' => array(self::HAS_MANY, 'Carriers', 'id_country'),
			'ioses' => array(self::HAS_MANY, 'Ios', 'country_id'),
			'opportunities' => array(self::HAS_MANY, 'Opportunities', 'country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_location' => 'Id Location',
			'status' => 'Status',
			'name' => 'Name',
			'detail' => 'Detail',
			'code' => 'Code',
			'ISO2_CITY' => 'Iso2 City',
			'ISO2' => 'Iso2',
			'ISO3' => 'Iso3',
			'type' => 'Type',
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

		$criteria->compare('id_location',$this->id_location);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('detail',$this->detail,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('ISO2_CITY',$this->ISO2_CITY,true);
		$criteria->compare('ISO2',$this->ISO2,true);
		$criteria->compare('ISO3',$this->ISO3,true);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeoLocation the static model class
	 */
	public function getNameFromISO2($iso)
	{		
		$criteria = new CDbCriteria;
		$criteria->addCondition("ISO2='".$iso."'");
		return isset(self::model()->find($criteria)->name) ? self::model()->find($criteria)->name : "Other";
	}

	public function getNameFromId($id)
	{		
		$criteria = new CDbCriteria;
		$criteria->addCondition("id_location='".$id."'");
		return isset(self::model()->find($criteria)->name) ? self::model()->find($criteria)->name : "Other";
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
