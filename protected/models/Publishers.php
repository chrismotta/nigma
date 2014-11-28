<?php

/**
 * This is the model class for table "publishers".
 *
 * The followings are the available columns in table 'publishers':
 * @property integer $id
 * @property string $status
 * @property string $name
 * @property string $commercial_name
 * @property integer $country_id
 * @property string $state
 * @property string $zip_code
 * @property string $address
 * @property string $phone
 * @property string $currency
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
 * @property Placements[] $placements
 * @property GeoLocation $country
 * @property Users $accountManager
 */
class Publishers extends CActiveRecord
{
	public $country_name;
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
			array('name, commercial_name, state, zip_code, address, currency, entity, tax_id, model', 'required'),
			array('country_id, account_manager_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>8),
			array('name, commercial_name, state, zip_code, address, phone, contact_com, email_com, contact_adm, email_adm, tax_id, net_payment', 'length', 'max'=>128),
			array('email_com, email_adm', 'email'),
			array('currency, entity, model', 'length', 'max'=>3),
			array('RS_perc, rate', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, status, name, commercial_name, country_id, state, zip_code, address, phone, currency, contact_com, email_com, contact_adm, email_adm, entity, tax_id, net_payment, account_manager_id, model, RS_perc, rate', 'safe', 'on'=>'search'),
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
			'placements'     => array(self::HAS_MANY, 'Placements', 'publishers_id'),
			'country'        => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'accountManager' => array(self::BELONGS_TO, 'Users', 'account_manager_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                 => 'ID',
			'status'             => 'Status',
			'name'               => 'Name',
			'commercial_name'    => 'Legal Name',
			'country_id'         => 'Country',
			'state'              => 'State',
			'zip_code'           => 'Zip Code',
			'address'            => 'Address',
			'phone'              => 'Phone',
			'currency'           => 'Currency',
			'contact_com'        => 'Com Contact Name',
			'email_com'          => 'Com Contact Email',
			'contact_adm'        => 'Adm Contact Name',
			'email_adm'          => 'Adm Contact Email',
			'entity'             => 'Entity',
			'tax_id'             => 'Tax',
			'net_payment'        => 'Net Payment',
			'account_manager_id' => 'Account Manager',
			'model'              => 'Model',
			'RS_perc'            => 'Revenue Share',
			'rate'               => 'Rate',
			'country_name'       => 'Country',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('contact_com',$this->contact_com,true);
		$criteria->compare('email_com',$this->email_com,true);
		$criteria->compare('contact_adm',$this->contact_adm,true);
		$criteria->compare('email_adm',$this->email_adm,true);
		$criteria->compare('entity',$this->entity,true);
		$criteria->compare('tax_id',$this->tax_id,true);
		$criteria->compare('net_payment',$this->net_payment,true);
		$criteria->compare('account_manager_id',$this->account_manager_id);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('RS_perc',$this->RS_perc,true);
		$criteria->compare('rate',$this->rate,true);

		$criteria->with = array('country', 'accountManager');

		$criteria->compare('country.name',$this->country_name);
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
		            'country_name'=>array(
						'asc'  =>'country.name',
						'desc' =>'country.name DESC',
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
