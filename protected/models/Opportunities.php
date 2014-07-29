<?php

/**
 * This is the model class for table "opportunities".
 *
 * The followings are the available columns in table 'opportunities':
 * @property integer $id
 * @property integer $carriers_id
 * @property string $rate
 * @property string $model_adv
 * @property string $product
 * @property integer $account_manager_id
 * @property string $comment
 * @property integer $country_id
 * @property integer $wifi
 * @property string $budget
 * @property string $server_to_server
 * @property string $startDate
 * @property string $endDate
 * @property integer $ios_id
 *
 * The followings are the available model relations:
 * @property Campaigns[] $campaigns
 * @property GeoLocation $country
 * @property Carriers $carriers
 * @property Ios $ios
 * @property Users $accountManager
 */
class Opportunities extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'opportunities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('carriers_id, rate, model_adv, wifi, budget, ios_id', 'required'),
			array('carriers_id, account_manager_id, country_id, wifi, ios_id', 'numerical', 'integerOnly'=>true),
			array('rate, budget', 'length', 'max'=>11),
			array('model_adv', 'length', 'max'=>3),
			array('product, comment', 'length', 'max'=>255),
			array('server_to_server', 'length', 'max'=>45),
			array('startDate, endDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, carriers_id, rate, model_adv, product, account_manager_id, comment, country_id, wifi, budget, server_to_server, startDate, endDate, ios_id', 'safe', 'on'=>'search'),
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
			'campaigns' => array(self::HAS_MANY, 'Campaigns', 'opportunities_id'),
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'carriers' => array(self::BELONGS_TO, 'Carriers', 'carriers_id'),
			'ios' => array(self::BELONGS_TO, 'Ios', 'ios_id'),
			'accountManager' => array(self::BELONGS_TO, 'Users', 'account_manager_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'carriers_id' => 'Carriers',
			'rate' => 'Rate',
			'model_adv' => 'Model Adv',
			'product' => 'Product',
			'account_manager_id' => 'Account Manager',
			'comment' => 'Comment',
			'country_id' => 'Country',
			'wifi' => 'Wifi',
			'budget' => 'Budget',
			'server_to_server' => 'Server To Server',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'ios_id' => 'Ios',
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
		$criteria->compare('carriers_id',$this->carriers_id);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('model_adv',$this->model_adv,true);
		$criteria->compare('product',$this->product,true);
		$criteria->compare('account_manager_id',$this->account_manager_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('wifi',$this->wifi);
		$criteria->compare('budget',$this->budget,true);
		$criteria->compare('server_to_server',$this->server_to_server,true);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('ios_id',$this->ios_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Opportunities the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
