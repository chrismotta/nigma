<?php

/**
 * This is the model class for table "validation_log".
 *
 * The followings are the available columns in table 'validation_log':
 * @property integer $id
 * @property string $status
 * @property string $date
 * @property string $ip
 * @property integer $ios_validation_id
 *
 * The followings are the available model relations:
 * @property IosValidation $iosValidation
 */
class ValidationLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'validation_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date', 'required'),
			array('ios_validation_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>255),
			array('ip', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, status, date, ip, ios_validation_id', 'safe', 'on'=>'search'),
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
			'iosValidation' => array(self::BELONGS_TO, 'IosValidation', 'ios_validation_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'status' => 'Status',
			'date' => 'Date',
			'ip' => 'Ip',
			'ios_validation_id' => 'Ios Validation',
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
		$criteria->compare('status',$this->status,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('ios_validation_id',$this->ios_validation_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ValidationLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function loadLog($ios_validation_id,$status)
	{
		$date =date('Y-m-d H:i:s', strtotime('NOW'));
		$ip   =$_SERVER['REMOTE_ADDR'];
		$log = new Self;
		$log->attributes=array('ios_validation_id'=>$ios_validation_id,'status'=>$status,'date'=>$date,'ip'=>$ip);
		if($log->save())
			return true;
		else
			return false;
	}
}
