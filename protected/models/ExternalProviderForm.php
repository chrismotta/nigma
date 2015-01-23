<?php

/**
 * This is the model class for table "external_provider_form".
 *
 * The followings are the available columns in table 'external_provider_form':
 * @property integer $id
 * @property integer $providers_id
 * @property string $hash
 * @property string $status
 * @property string $type
 * @property string $create_date
 *
 * The followings are the available model relations:
 * @property Providers $providers
 */
class ExternalProviderForm extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'external_provider_form';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('providers_id, hash', 'required'),
			array('providers_id', 'numerical', 'integerOnly'=>true),
			array('hash', 'length', 'max'=>255),
			array('type','max'=>45),
			array('status', 'length', 'max'=>9),
			array('create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.255
			array('id, providers_id, hash, status, create_date, type', 'safe', 'on'=>'search'),
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
			'providers' => array(self::BELONGS_TO, 'Providers', 'providers_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'providers_id' => 'Providers',
			'hash' => 'Hash',
			'status' => 'Status',
			'create_date' => 'Create Date',
			'type'=>'Type',
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
		$criteria->compare('providers_id',$this->providers_id);
		$criteria->compare('hash',$this->hash,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExternalProviderForm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getExpirationHashTime()
	{
		return 86400; // 60 * 60 * 24 = 24 hs.
	}
}
