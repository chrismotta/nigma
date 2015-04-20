<?php

/**
 * This is the model class for table "publishers".
 *
 * The followings are the available columns in table 'publishers':
 * @property integer $providers_id
 * @property string $status
 * @property string $name
 * @property string $commercial_name
 * @property integer $country_id
 * @property string $state
 * @property string $zip_code
 * @property string $address
 * @property string $phone
 * @property string $contact_com
 * @property string $email_com
 * @property string $contact_adm
 * @property string $email_adm
 * @property string $entity
 * @property string $tax_id
 * @property string $net_payment
 * @property integer $account_manager_id
 * @property string $model
 * @property string $RS_perc
 * @property string $rate
 *
 * The followings are the available model relations:
 * @property Providers $providers
 * @property GeoLocation $country
 * @property Users $accountManager
 */
class Publishers extends CActiveRecord
{
	public $providers_name;
	public $account_name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'publishers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_manager_id', 'numerical', 'integerOnly'=>true),
			// array('prefix','unique', 'message'=>'This prefix already exists.'),
			array('RS_perc, rate', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('providers_id, account_manager_id, RS_perc, rate', 'safe', 'on'=>'search'),
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
			'placements'     => array(self::HAS_MANY, 'Sites', 'sites_id'),
			'country'        => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'accountManager' => array(self::BELONGS_TO, 'Users', 'account_manager_id'),
			'providers'      => array(self::BELONGS_TO, 'Providers', 'providers_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'providers_id'       => 'ID',
			'providers_name'     => 'Name',
			'RS_perc'            => 'Revenue Share',
			'rate'               => 'Rate',
			'account_name'       => 'Account Name'
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
		$criteria->with = array('providers');

		$criteria->compare('t.providers_id',$this->providers_id);
		$criteria->compare('providers.status','Active',true);
		// $criteria->compare('t.name',$this->name,true);
		$criteria->compare('account_manager_id',$this->account_manager_id);
		$criteria->compare('RS_perc',$this->RS_perc,true);
		$criteria->compare('rate',$this->rate,true);

		$criteria->compare('accountManager.name',$this->account_name,true);
		$criteria->compare('accountManager.lastname',$this->account_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'   =>$criteria,
			'pagination' =>array(
                'pageSize' => 30,
            ),
			'sort'     	 =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'providers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            '*',
		        ),
		    ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Publishers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
