<?php

/**
 * This is the model class for table "click_macros".
 *
 * The followings are the available columns in table 'click_macros':
 * @property integer $id
 * @property integer $clicks_log_id
 * @property string $name
 * @property string $value
 *
 * The followings are the available model relations:
 * @property ClicksLog $clicksLog
 */
class ClickMacros extends CActiveRecord
{
	public $incoming = array(
		'QS_pubid',
		);

	public function isValidMacro($macro){
		return in_array($macro, $this->incoming);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'click_macros';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clicks_log_id, name, value', 'required'),
			array('clicks_log_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('value', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, clicks_log_id, name, value', 'safe', 'on'=>'search'),
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
			'clicksLog' => array(self::BELONGS_TO, 'ClicksLog', 'clicks_log_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'clicks_log_id' => 'Clicks Log',
			'name' => 'Name',
			'value' => 'Value',
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
		$criteria->compare('clicks_log_id',$this->clicks_log_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClickMacros the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
