<?php

/**
 * This is the model class for table "ios".
 *
 * The followings are the available columns in table 'ios':
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property string $address
 * @property integer $country_id
 * @property string $state
 * @property string $zip_code
 * @property string $phone
 * @property string $email
 * @property string $contact_adm
 * @property string $currency
 * @property string $ret
 * @property string $tax_id
 * @property integer $commercial_id
 * @property string $entity
 * @property string $net_payment
 * @property integer $advertisers_id
 *
 * The followings are the available model relations:
 * @property GeoLocation $country
 * @property Advertisers $advertisers
 * @property Users $commercial
 * @property Opportunities[] $opportunities
 */
class Ios extends CActiveRecord
{

	public $country_name;
	public $commercial_name;
	public $commercial_lastname;
	public $advertiser_name;

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
			array('name, address, country_id, state, zip_code, currency, tax_id, commercial_id, entity, net_payment, advertisers_id', 'required'),
			array('status, country_id, commercial_id, advertisers_id', 'numerical', 'integerOnly'=>true),
			array('name, address, state, zip_code, phone, email, contact_adm, ret, tax_id, net_payment', 'length', 'max'=>128),
			array('currency', 'length', 'max'=>6),
			array('entity', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, country_name, commercial_lastname, commercial_name, advertiser_name, name, address, status, country_id, state, zip_code, phone, email, contact_adm, currency, ret, tax_id, commercial_id, entity, net_payment, advertisers_id', 'safe', 'on'=>'search'),
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
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'advertisers' => array(self::BELONGS_TO, 'Advertisers', 'advertisers_id'),
			'commercial' => array(self::BELONGS_TO, 'Users', 'commercial_id'),
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
			'name' => 'Name',
			'address' => 'Address',
			'status' => 'Status',
			'country_id' => 'Country',
			'state' => 'State',
			'zip_code' => 'Zip Code',
			'phone' => 'Phone',
			'email' => 'Email',
			'contact_adm' => 'Contact Adm',
			'currency' => 'Currency',
			'ret' => 'Ret',
			'tax_id' => 'Tax',
			'commercial_id' => 'Commercial',
			'entity' => 'Entity',
			'net_payment' => 'Net Payment',
			'advertisers_id' => 'Advertisers',
			// Custom attributes
			'country_name' => 'Country',
			'commercial_name' => 'Commercial',
			'commercial_lastname' => 'Commercial',
			'advertiser_name' => 'Advertiser',
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
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.status',$this->status, true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('contact_adm',$this->contact_adm,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('ret',$this->ret,true);
		$criteria->compare('tax_id',$this->tax_id,true);
		$criteria->compare('commercial_id',$this->commercial_id);
		$criteria->compare('entity',$this->entity,true);
		$criteria->compare('net_payment',$this->net_payment,true);
		$criteria->compare('advertisers_id',$this->advertisers_id);

		$criteria->with = array( 'advertisers', 'commercial', 'country');
		$criteria->compare('advertisers.name', $this->advertiser_name, true);
		$criteria->compare('commercial.name', $this->commercial_name, true);
		$criteria->compare('commercial.lastname', $this->commercial_lastname, true);
		$criteria->compare('country.name', $this->country_name, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'     =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'advertiser_name'=>array(
						'asc'  =>'advertisers.name',
						'desc' =>'advertisers.name DESC',
		            ),
		            'commercial_name'=>array(
						'asc'  =>'commercial.name',
						'desc' =>'commercial.name DESC',
		            ),
		            'commercial_lastname'=>array(
						'asc'  =>'commercial.lastname',
						'desc' =>'commercial.lastname DESC',
		            ),
					'country_name'=>array(
						'asc'  =>'country.name',
						'desc' =>'country.name DESC',
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
