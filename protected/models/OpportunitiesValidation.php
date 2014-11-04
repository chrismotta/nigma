<?php

/**
 * This is the model class for table "opportunities_validation".
 *
 * The followings are the available columns in table 'opportunities_validation':
 * @property integer $id
 * @property integer $opportunities_id
 * @property string $date
 * @property string $period
 *
 * The followings are the available model relations:
 * @property Opportunities $opportunities
 */
class OpportunitiesValidation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'opportunities_validation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, period', 'required'),
			array('opportunities_id', 'numerical', 'integerOnly'=>true),
			array('period', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, opportunities_id, date, period', 'safe', 'on'=>'search'),
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
			'opportunities' => array(self::BELONGS_TO, 'Opportunities', 'opportunities_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'opportunities_id' => 'Opportunities',
			'date' => 'Date',
			'period' => 'Period',
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
		$criteria->compare('opportunities_id',$this->opportunities_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('period',$this->period,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OpportunitiesValidation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function checkValidation($opportunitie,$period)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition("opportunities_id=".$opportunitie);
		$criteria->addCondition("MONTH(period)='".date('m', strtotime($period))."'");
		$criteria->addCondition("YEAR(period)='".date('Y', strtotime($period))."'");
		$criteria->order="period DESC";
		if($validation = self::find($criteria))
			return true;
		else
			return false;
	}

	public function checkValidationIo($io,$period)
	{
		$check=false;
		$ios=new Ios;
		$opportunities = $ios->getClients($month,$year,null,$io);
		foreach ($opportunities as $opportunitie) {
			if(checkValidation($opportunitie['opportunitie'],$period)==true) $check=true;
			else return false;
		}
		return $check;
	}
}
