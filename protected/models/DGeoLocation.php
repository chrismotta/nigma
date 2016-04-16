<?php

/**
 * This is the model class for table "D_GeoLocation".
 *
 * The followings are the available columns in table 'D_GeoLocation':
 * @property integer $id
 * @property string $server_ip
 * @property string $country
 * @property string $carrier
 * @property string $connection_type
 *
 * The followings are the available model relations:
 * @property FImpressions[] $fImpressions
 */
class DGeoLocation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'D_GeoLocation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('server_ip, carrier', 'length', 'max'=>45),
			array('country', 'length', 'max'=>2),
			array('connection_type', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, server_ip, country, carrier, connection_type', 'safe', 'on'=>'search'),
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
			'fImpressions' => array(self::HAS_MANY, 'FImpressions', 'D_GeoLocation_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'server_ip' => 'Server Ip',
			'country' => 'Country',
			'carrier' => 'Carrier',
			'connection_type' => 'Connection Type',
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
		$criteria->compare('server_ip',$this->server_ip,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('carrier',$this->carrier,true);
		$criteria->compare('connection_type',$this->connection_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DGeoLocation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
