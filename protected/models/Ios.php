<?php

/**
 * This is the model class for table "ios".
 *
 * The followings are the available columns in table 'ios':
 * @property integer $id
 * @property integer $rec
 * @property integer $advertisers_id
 * @property integer $user_id
 * @property string $name
 * @property integer $offer_type
 * @property integer $currency
 * @property integer $budget_type
 * @property string $budget
 * @property integer $model
 * @property string $bid
 * @property integer $invoice_type
 * @property string $net
 * @property string $comment
 * @property integer $status
 * @property string $date_start
 * @property string $date_end
 *
 * The followings are the available model relations:
 * @property Advertisers $advertisers
 * @property Users $user
 * @property Opportunities[] $opportunities
 */
class Ios extends CActiveRecord
{
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
			array('advertisers_id, user_id, name, budget, bid, net, comment, date_start, date_end', 'required'),
			array('rec, advertisers_id, user_id, offer_type, currency, budget_type, model, invoice_type, status', 'numerical', 'integerOnly'=>true),
			array('name, comment', 'length', 'max'=>128),
			array('budget, bid, net', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rec, advertisers_id, user_id, name, offer_type, currency, budget_type, budget, model, bid, invoice_type, net, comment, status, date_start, date_end', 'safe', 'on'=>'search'),
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
			'advertisers' => array(self::BELONGS_TO, 'Advertisers', 'advertisers_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'opportunities' => array(self::HAS_MANY, 'Opportunities', 'ios_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'rec' => 'Rec',
			'advertisers_id' => 'Advertisers',
			'user_id' => 'User',
			'name' => 'Name',
			'offer_type' => 'Offer Type',
			'currency' => 'Currency',
			'budget_type' => 'Budget Type',
			'budget' => 'Budget',
			'model' => 'Model',
			'bid' => 'Bid',
			'invoice_type' => 'Invoice Type',
			'net' => 'Net',
			'comment' => 'Comment',
			'status' => 'Status',
			'date_start' => 'Date Start',
			'date_end' => 'Date End',
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
		$criteria->compare('rec',$this->rec);
		$criteria->compare('advertisers_id',$this->advertisers_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('offer_type',$this->offer_type);
		$criteria->compare('currency',$this->currency);
		$criteria->compare('budget_type',$this->budget_type);
		$criteria->compare('budget',$this->budget,true);
		$criteria->compare('model',$this->model);
		$criteria->compare('bid',$this->bid,true);
		$criteria->compare('invoice_type',$this->invoice_type);
		$criteria->compare('net',$this->net,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_end',$this->date_end,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
