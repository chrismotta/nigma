<?php

/**
 * This is the model class for table "D_Supply".
 *
 * The followings are the available columns in table 'D_Supply':
 * @property integer $placement_id
 * @property string $provider
 * @property string $site
 * @property string $placement
 * @property string $rate
 *
 * The followings are the available model relations:
 * @property FImpressions[] $fImpressions
 */
class DSupply extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'D_Supply';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('placement_id', 'required'),
			array('placement_id', 'numerical', 'integerOnly'=>true),
			array('provider, site, placement', 'length', 'max'=>255),
			array('rate', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('placement_id, provider, site, placement, rate', 'safe', 'on'=>'search'),
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
			'fImpressions' => array(self::HAS_MANY, 'FImpressions', 'D_Supply_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'placement_id' => 'Placement',
			'provider' => 'Provider',
			'site' => 'Site',
			'placement' => 'Placement',
			'rate' => 'Rate',
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

		$criteria->compare('placement_id',$this->placement_id);
		$criteria->compare('provider',$this->provider,true);
		$criteria->compare('site',$this->site,true);
		$criteria->compare('placement',$this->placement,true);
		$criteria->compare('rate',$this->rate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DSupply the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
