<?php

/**
 * This is the model class for table "opportunities".
 *
 * The followings are the available columns in table 'opportunities':
 * @property integer $id
 * @property integer $rec
 * @property integer $ios_id
 * @property integer $model
 * @property string $budget
 * @property string $rate
 * @property string $carrier
 * @property string $product
 * @property integer $manager_id
 *
 * The followings are the available model relations:
 * @property Campaigns[] $campaigns
 * @property Users $manager
 * @property Ios $ios
 */
class Opportunities extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'opportunities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ios_id, model, budget, rate, carrier, product', 'required'),
			array('rec, ios_id, model, manager_id', 'numerical', 'integerOnly'=>true),
			array('budget, rate', 'length', 'max'=>11),
			array('carrier, product', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rec, ios_id, model, budget, rate, carrier, product, manager_id', 'safe', 'on'=>'search'),
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
			'campaigns' => array(self::HAS_MANY, 'Campaigns', 'opportunities_id'),
			'manager' => array(self::BELONGS_TO, 'Users', 'manager_id'),
			'ios' => array(self::BELONGS_TO, 'Ios', 'ios_id'),
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
			'ios_id' => 'Ios',
			'model' => 'Model',
			'budget' => 'Budget',
			'rate' => 'Rate',
			'carrier' => 'Carrier',
			'product' => 'Product',
			'manager_id' => 'Manager',
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
		$criteria->compare('ios_id',$this->ios_id);
		$criteria->compare('model',$this->model);
		$criteria->compare('budget',$this->budget,true);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('carrier',$this->carrier,true);
		$criteria->compare('product',$this->product,true);
		$criteria->compare('manager_id',$this->manager_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Opportunities the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
