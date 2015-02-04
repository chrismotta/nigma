<?php

/**
 * This is the model class for table "transaction_providers".
 *
 * The followings are the available columns in table 'transaction_providers':
 * @property integer $id
 * @property string $period
 * @property string $spend
 * @property string $date
 * @property integer $providers_id
 * @property integer $users_id
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Providers $providers
 * @property Users $users
 */
class TransactionProviders extends CActiveRecord
{
	public $total;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaction_providers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('period, spend, date, providers_id, users_id', 'required'),
			array('providers_id, users_id', 'numerical', 'integerOnly'=>true),
			array('spend', 'length', 'max'=>11),
			array('comment', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, period, spend, date, providers_id, users_id, comment', 'safe', 'on'=>'search'),
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
			'providers' => array(self::BELONGS_TO, 'Providers', 'providers_id'),
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'period' => 'Period',
			'spend' => 'Spend',
			'date' => 'Date',
			'providers_id' => 'Providers',
			'users_id' => 'Users',
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
		$criteria->compare('period',$this->period,true);
		$criteria->compare('spend',$this->spend,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('providers_id',$this->providers_id);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TransactionProviders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTransactions($providers_id,$period)
	{
		$criteria=new CDbCriteria;
		// $criteria->with=array('opportunities');
		$criteria->compare('providers_id',$providers_id);
		$criteria->compare('period',$period);
		return new CActiveDataProvider($this,array(
				'criteria'=>$criteria,
			));
	}

	public function getTotalTransactions($providers_id,$period)
	{
		$criteria=new CDbCriteria;
		$criteria->select='sum(t.spend) as total';
		// $criteria->with=array('opportunities');
		// $criteria->addCondition('t.opportunities_id=opportunities.id');
		// $criteria->addCondition('opportunities.ios_id='.$ios_id);
		$criteria->compare('t.providers_id',$providers_id);
		$criteria->compare('t.period',$period);
		return self::model()->find($criteria) ? self::model()->find($criteria)['total'] : 0;
		
	}
}
