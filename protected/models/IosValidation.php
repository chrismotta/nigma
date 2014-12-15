<?php

/**
 * This is the model class for table "ios_validation".
 *
 * The followings are the available columns in table 'ios_validation':
 * @property integer $id
 * @property integer $ios_id
 * @property string $period
 * @property string $date
 * @property string $status
 * @property string $comment
 * @property string $validation_token
 *
 * The followings are the available model relations:
 * @property Ios $ios
 */
class IosValidation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ios_validation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('period, date, validation_token', 'required'),
			array('ios_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>8),
			array('comment', 'length', 'max'=>255),
			array('validation_token', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ios_id, period, date, status, comment, validation_token', 'safe', 'on'=>'search'),
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
			'ios_id' => 'Ios',
			'period' => 'Period',
			'date' => 'Date',
			'status' => 'Status',
			'comment' => 'Comment',
			'validation_token' => 'Validation Token',
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
		$criteria->compare('ios_id',$this->ios_id);
		$criteria->compare('period',$this->period,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('validation_token',$this->validation_token,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IosValidation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function loadModelByToken($token)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition("validation_token='".$token."'");
		if($validation = self::find($criteria))
			return $validation;
		else
			return null;
	}

	public function loadModelByIo($io,$period=null)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition("ios_id=".$io);
		if($period)
		{
			$criteria->addCondition("MONTH(period)='".date('m', strtotime($period))."'");
			$criteria->addCondition("YEAR(period)='".date('Y', strtotime($period))."'");
		}
		$criteria->order="period DESC";
		if($validation = self::find($criteria))
			return $validation;
		else
			return null;
	}

	public function checkValidationOpportunities($io,$period)
	{
		$check=false;
		$ios=new Ios;
		$opportunitiesValidation=new OpportunitiesValidation;
		$clients = $ios->getClients(date('m', strtotime($period)),date('Y', strtotime($period)),null,$io,null,null,null,null,'otro');
		// foreach ($clients as $client) {			
		// 	foreach ($client as $data) {
		// 		$opportunities[]=$data;
		// 	}
		// }
		foreach ($clients['data'] as $opportunitie) {
			if($opportunitiesValidation->checkValidation($opportunitie['opportunitie_id'],$period)==true) $check=true;
			else return false;
		}
		return $check;
	}

	public function checkValidation($io,$period)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition("ios_id=".$io);
		$criteria->addCondition("MONTH(period)='".date('m', strtotime($period))."'");
		$criteria->addCondition("YEAR(period)='".date('Y', strtotime($period))."'");
		$criteria->order="period DESC";
		if($validation = self::find($criteria))
			return true;
		else
			return false;
	}
	
	public function getStatusByIo($id,$period)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('ios_id='.$id);
		$criteria->addCondition("MONTH(period)='".date('m', strtotime($period))."'");
		$criteria->addCondition("YEAR(period)='".date('Y', strtotime($period))."'");
		if($validation = self::find($criteria))
			return $validation->status;
		else
			return 'Not Sended';
	}
}
