<?php

/**
 * This is the model class for table "D_UserAgent".
 *
 * The followings are the available columns in table 'D_UserAgent':
 * @property integer $id
 * @property string $user_agent
 * @property string $device_type
 * @property string $device_brand
 * @property string $device_model
 * @property string $os_type
 * @property string $os_version
 * @property string $browser_type
 * @property string $browser_version
 *
 * The followings are the available model relations:
 * @property FImpressions[] $fImpressions
 */
class DUserAgent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'D_UserAgent';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_agent', 'required'),
			array('user_agent', 'length', 'max'=>255),
			array('device_type', 'length', 'max'=>10),
			// array('device_brand, device_model, os_type, os_version, browser_type, browser_version', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_agent, device_type, device_brand, device_model, os_type, os_version, browser_type, browser_version', 'safe', 'on'=>'search'),
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
			'fImpressions' => array(self::HAS_MANY, 'FImpressions', 'D_UserAgent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_agent' => 'User Agent',
			'device_type' => 'Device Type',
			'device_brand' => 'Device Brand',
			'device_model' => 'Device Model',
			'os_type' => 'Os Type',
			'os_version' => 'Os Version',
			'browser_type' => 'Browser Type',
			'browser_version' => 'Browser Version',
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
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('device_type',$this->device_type,true);
		$criteria->compare('device_brand',$this->device_brand,true);
		$criteria->compare('device_model',$this->device_model,true);
		$criteria->compare('os_type',$this->os_type,true);
		$criteria->compare('os_version',$this->os_version,true);
		$criteria->compare('browser_type',$this->browser_type,true);
		$criteria->compare('browser_version',$this->browser_version,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DUserAgent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
