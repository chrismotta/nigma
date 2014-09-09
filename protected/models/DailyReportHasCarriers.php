<?php

/**
 * This is the model class for table "daily_report_has_carriers".
 *
 * The followings are the available columns in table 'daily_report_has_carriers':
 * @property integer $daily_report_id
 * @property integer $carriers_id_carrier
 * @property string $rate
 * @property integer $conv
 */
class DailyReportHasCarriers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daily_report_has_carriers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('daily_report_id, carriers_id_carrier', 'required'),
			array('daily_report_id, carriers_id_carrier, conv', 'numerical', 'integerOnly'=>true),
			array('rate', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('daily_report_id, carriers_id_carrier, rate, conv', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'daily_report_id' => 'Daily Report',
			'carriers_id_carrier' => 'Carriers Id Carrier',
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

		$criteria->compare('daily_report_id',$this->daily_report_id);
		$criteria->compare('carriers_id_carrier',$this->carriers_id_carrier);
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
	 * @return DailyReportHasCarriers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
