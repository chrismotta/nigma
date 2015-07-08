<?php

/**
 * This is the model class for table "exchanges".
 *
 * The followings are the available columns in table 'exchanges':
 * @property integer $id
 * @property string $prefix
 * @property string $name
 * @property integer $has_api
 * @property string $currency
 * @property string $token1
 * @property string $token2
 * @property string $token3
 *
 * The followings are the available model relations:
 * @property Placements[] $placements
 */
class Exchanges extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exchanges';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prefix, name, currency', 'required'),
			array('has_api', 'numerical', 'integerOnly'=>true),
			array('prefix', 'length', 'max'=>45),
			array('name', 'length', 'max'=>128),
			array('currency', 'length', 'max'=>3),
			array('token1, token2, token3', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prefix, name, has_api, currency, token1, token2, token3', 'safe', 'on'=>'search'),
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
			'p_e' => array(self::HAS_MANY, 'PlacementsHasExchanges', 'exchanges_id'),
			'placements' => array(self::MANY_MANY, 'Placements', 'placements_has_exchanges(exchanges_id, placements_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'prefix' => 'Prefix',
			'name' => 'Name',
			'has_api' => 'Has Api',
			'currency' => 'Currency',
			'token1' => 'Token1',
			'token2' => 'Token2',
			'token3' => 'Token3',
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
	public function search($placement=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('prefix',$this->prefix,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('has_api',$this->has_api);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('token1',$this->token1,true);
		$criteria->compare('token2',$this->token2,true);
		$criteria->compare('token3',$this->token3,true);

		if(isset($placement)){
			$criteria->with = array('p_e');
			// $criteria->together = true;
			// $criteria->addCondition('p_e.exchanges_id IS NULL AND p_e.placements_id != '.$placement);
			
			$list = CHtml::listData(
				PlacementsHasExchanges::model()->findAll(
					array('condition'=>'placements_id = '.$placement) 
					), 
				'exchanges_id','exchanges_id' );

			$criteria->addNotInCondition('id', $list);
			// $criteria->addCondition('(p_e.exchanges_id != t.id AND p_e.placements_id != '.$placement.')', 'OR');
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Exchanges the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
