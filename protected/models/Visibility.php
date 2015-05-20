<?php

/**
 * This is the model class for table "visibility".
 *
 * The followings are the available columns in table 'visibility':
 * @property integer $id
 * @property integer $users_id
 * @property integer $country
 * @property integer $carrier
 * @property integer $rate
 * @property integer $imp
 * @property integer $clicks
 * @property integer $conv
 * @property integer $spend
 *
 * The followings are the available model relations:
 * @property Users $users
 */
class Visibility extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'visibility';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('users_id, country, carrier, rate, imp, clicks, conv, spend', 'required'),
			array('users_id, country, carrier, rate, imp, clicks, conv, spend', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, users_id, country, carrier, rate, imp, clicks, conv, spend', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'users_id' => 'Users',
			'country' => 'Country',
			'carrier' => 'Carrier',
			'rate' => 'Rate',
			'imp' => 'Impressions',
			'clicks' => 'Clicks',
			'conv' => 'Conversions',
			'spend' => 'Spend',
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
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('country',$this->country);
		$criteria->compare('carrier',$this->carrier);
		$criteria->compare('rate',$this->rate);
		$criteria->compare('imp',$this->imp);
		$criteria->compare('clicks',$this->clicks);
		$criteria->compare('conv',$this->conv);
		$criteria->compare('spend',$this->spend);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Visibility the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
