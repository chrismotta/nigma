<?php

/**
 * This is the model class for table "daily_publishers".
 *
 * The followings are the available columns in table 'daily_publishers':
 * @property integer $id
 * @property integer $placements_id
 * @property integer $country_id
 * @property string $imp
 * @property string $imp_adv
 * @property string $revenue
 * @property string $spend
 * @property string $profit
 * @property string $profit_percent
 * @property string $eCPM
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Placements $placements
 * @property GeoLocation $country
 */
class DailyPublishers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daily_publishers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('imp, revenue, spend, profit', 'required'),
			array('placements_id, country_id', 'numerical', 'integerOnly'=>true),
			array('imp, imp_adv, revenue, spend, profit, profit_percent, eCPM', 'length', 'max'=>11),
			array('comment', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, placements_id, country_id, imp, imp_adv, revenue, spend, profit, profit_percent, eCPM, comment', 'safe', 'on'=>'search'),
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
			'placements' => array(self::BELONGS_TO, 'Placements', 'placements_id'),
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'placements_id' => 'Placements',
			'country_id' => 'Country',
			'imp' => 'Imp',
			'imp_adv' => 'Imp Adv',
			'revenue' => 'Revenue',
			'spend' => 'Spend',
			'profit' => 'Profit',
			'profit_percent' => 'Profit Percent',
			'eCPM' => 'E Cpm',
			'comment' => 'Comment',
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
		$criteria->compare('placements_id',$this->placements_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('imp',$this->imp,true);
		$criteria->compare('imp_adv',$this->imp_adv,true);
		$criteria->compare('revenue',$this->revenue,true);
		$criteria->compare('spend',$this->spend,true);
		$criteria->compare('profit',$this->profit,true);
		$criteria->compare('profit_percent',$this->profit_percent,true);
		$criteria->compare('eCPM',$this->eCPM,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DailyPublishers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
