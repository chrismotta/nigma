<?php

/**
 * This is the model class for table "finance_entities".
 *
 * The followings are the available columns in table 'finance_entities':
 * @property integer $id
 * @property integer $rec
 * @property string $commercial
 * @property string $address
 * @property string $tax_id
 * @property string $tax_percent
 * @property string $zip_code
 * @property string $country
 * @property string $state
 * @property string $web
 *
 * The followings are the available model relations:
 * @property Advertisers[] $advertisers
 */
class FinanceEntities extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'finance_entities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('commercial, address, tax_id, tax_percent, zip_code, country, state, web', 'required'),
			array('rec', 'numerical', 'integerOnly'=>true),
			array('commercial, address, tax_id, tax_percent, zip_code, country, state, web', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rec, commercial, address, tax_id, tax_percent, zip_code, country, state, web', 'safe', 'on'=>'search'),
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
			'advertisers' => array(self::HAS_MANY, 'Advertisers', 'finance_entities_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'rec' => 'Rec',
			'commercial' => 'Commercial',
			'address' => 'Address',
			'tax_id' => 'Tax',
			'tax_percent' => 'Tax Percent',
			'zip_code' => 'Zip Code',
			'country' => 'Country',
			'state' => 'State',
			'web' => 'Web',
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
		$criteria->compare('rec',$this->rec);
		$criteria->compare('commercial',$this->commercial,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('tax_id',$this->tax_id,true);
		$criteria->compare('tax_percent',$this->tax_percent,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('web',$this->web,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FinanceEntities the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
