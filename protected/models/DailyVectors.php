<?php

/**
 * This is the model class for table "daily_vectors".
 *
 * The followings are the available columns in table 'daily_vectors':
 * @property integer $id
 * @property integer $daily_report_id
 * @property integer $vectors_id
 * @property integer $campaigns_id
 * @property string $rate
 * @property integer $conv
 *
 * The followings are the available model relations:
 * @property DailyReport $dailyReport
 * @property Vectors $vectors
 * @property Campaigns $campaigns
 */
class DailyVectors extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daily_vectors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, daily_report_id, vectors_id, campaigns_id', 'required'),
			array('id, daily_report_id, vectors_id, campaigns_id, conv', 'numerical', 'integerOnly'=>true),
			array('rate', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, daily_report_id, vectors_id, campaigns_id, rate, conv', 'safe', 'on'=>'search'),
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
			'dailyReport' => array(self::BELONGS_TO, 'DailyReport', 'daily_report_id'),
			'vectors' => array(self::BELONGS_TO, 'Vectors', 'vectors_id'),
			'campaigns' => array(self::BELONGS_TO, 'Campaigns', 'campaigns_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'daily_report_id' => 'Daily Report',
			'vectors_id' => 'Vectors',
			'campaigns_id' => 'Campaigns',
			'rate' => 'Rate',
			'conv' => 'Conv',
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
		$criteria->compare('daily_report_id',$this->daily_report_id);
		$criteria->compare('vectors_id',$this->vectors_id);
		$criteria->compare('campaigns_id',$this->campaigns_id);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('conv',$this->conv);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DailyVectors the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
