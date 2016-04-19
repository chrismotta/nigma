<?php

/**
 * This is the model class for table "D_Bid".
 *
 * The followings are the available columns in table 'D_Bid':
 * @property integer $F_Impressions_id
 * @property string $revenue
 * @property string $cost
 * @property string $profit
 * @property string $r_eCPM
 * @property string $c_eCPM
 *
 * The followings are the available model relations:
 * @property FImpressions $fImpressions
 */
class DBid extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'D_Bid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('F_Impressions_id', 'required'),
			array('F_Impressions_id', 'numerical', 'integerOnly'=>true),
			array('revenue, cost, profit, r_eCPM, c_eCPM', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('F_Impressions_id, revenue, cost, profit, r_eCPM, c_eCPM', 'safe', 'on'=>'search'),
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
			'fImpressions' => array(self::BELONGS_TO, 'FImpressions', 'F_Impressions_id'),
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'F_Impressions_id' => 'F Impressions',
			'revenue' => 'Revenue',
			'cost' => 'Cost',
			'profit' => 'Profit',
			'r_eCPM' => 'R E Cpm',
			'c_eCPM' => 'C E Cpm',
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

		$criteria->compare('F_Impressions_id',$this->F_Impressions_id);
		$criteria->compare('revenue',$this->revenue,true);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('profit',$this->profit,true);
		$criteria->compare('r_eCPM',$this->r_eCPM,true);
		$criteria->compare('c_eCPM',$this->c_eCPM,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DBid the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
