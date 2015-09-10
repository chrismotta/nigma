<?php

/**
 * This is the model class for table "ios".
 *
 * The followings are the available columns in table 'ios':
 * @property integer $id
 * @property integer $finance_entities_id
 * @property string $date
 * @property string $status
 *
 * The followings are the available model relations:
 * @property FinanceEntities $financeEntities
 * @property Opportunities[] $opportunities
 */
class Ios extends CActiveRecord
{
	public $financeEntitiesName;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ios';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('finance_entities_id, date', 'required'),
			array('id, finance_entities_id', 'numerical', 'integerOnly'=>true),
			array('budget', 'numerical', 'integerOnly'=>false),
			array('status', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, finance_entities_id, date, status, financeEntitiesName, budget', 'safe', 'on'=>'search'),
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
			'financeEntities' => array(self::BELONGS_TO, 'FinanceEntities', 'finance_entities_id'),
			'opportunities' => array(self::MANY_MANY, 'Opportunities', 'ios_has_opportunities(ios_id, opportunities_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'finance_entities_id' => 'Finance Entities',
			'date' => 'Date',
			'budget' => 'Budget',
			'status' => 'Status',
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

		$criteria->with = array('financeEntities');
		$criteria->compare('id',$this->id);
		$criteria->compare('finance_entities_id',$this->finance_entities_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('budget',$this->budget,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('financeEntities.name',$this->financeEntitiesName,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
                'pageSize' => 30,
            ),'sort'     => array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'financeEntitiesName'=>array(
						'asc'  =>'financeEntities.name',
						'desc' =>'financeEntities.name DESC',
		            ),
		            // Adding all the other default attributes
		            '*',
		        ),
		    ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ios the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
