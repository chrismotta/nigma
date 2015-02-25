<?php

/**
 * This is the model class for table "finance_entities".
 *
 * The followings are the available columns in table 'finance_entities':
 * @property integer $id
 * @property integer $advertisers_id
 * @property integer $commercial_id
 * @property string $name
 * @property string $commercial_name
 * @property integer $prospect
 * @property string $status
 * @property string $address
 * @property string $state
 * @property string $zip_code
 * @property string $phone
 * @property string $contact_com
 * @property string $email_com
 * @property string $contact_adm
 * @property string $email_adm
 * @property string $email_validation
 * @property string $currency
 * @property string $ret
 * @property string $tax_id
 * @property string $entity
 * @property string $net_payment
 * @property string $pdf_name
 * @property string $description
 * @property integer $country_id
 *
 * The followings are the available model relations:
 * @property Advertisers $advertisers
 * @property GeoLocation $country
 * @property Users $commercial
 * @property IosValidation[] $iosValidations
 * @property Regions[] $regions
 * @property TransactionCount[] $transactionCounts
 */
class FinanceEntities extends CActiveRecord
{
	public $country_name;
	public $com_name;
	public $com_lastname;
	public $advertiser_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'finance_entities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('advertisers_id, name, commercial_name, status, address, state, zip_code, currency, tax_id, entity, net_payment', 'required'),
			array('advertisers_id, commercial_id, prospect, country_id', 'numerical', 'integerOnly'=>true),
			array('name, commercial_name, address, state, zip_code, phone, contact_com, email_com, contact_adm, email_adm, email_validation, ret, tax_id, net_payment, pdf_name', 'length', 'max'=>128),
			array('status', 'length', 'max'=>8),
			array('currency, entity', 'length', 'max'=>3),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, advertisers_id, commercial_id, name, commercial_name, prospect, status, address, state, zip_code, phone, contact_com, email_com, contact_adm, email_adm, email_validation, currency, ret, tax_id, entity, net_payment, pdf_name, description, country_id', 'safe', 'on'=>'search'),
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
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'commercial' => array(self::BELONGS_TO, 'Users', 'commercial_id'),
			'iosValidations' => array(self::HAS_MANY, 'IosValidation', 'finance_entities_id'),
			'regions' => array(self::HAS_MANY, 'Regions', 'finance_entities_id'),
			'transactionCounts' => array(self::HAS_MANY, 'TransactionCount', 'finance_entities_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'advertisers_id' => 'Advertisers',
			'commercial_id' => 'Commercial',
			'name' => 'Name',
			'commercial_name' => 'Commercial Name',
			'prospect' => 'Prospect',
			'status' => 'Status',
			'address' => 'Address',
			'state' => 'State',
			'zip_code' => 'Zip Code',
			'phone' => 'Phone',
			'contact_com' => 'Contact Com',
			'email_com' => 'Email Com',
			'contact_adm' => 'Contact Adm',
			'email_adm' => 'Email Adm',
			'email_validation' => 'Email Validation',
			'currency' => 'Currency',
			'ret' => 'Ret',
			'tax_id' => 'Tax',
			'entity' => 'Entity',
			'net_payment' => 'Net Payment',
			'pdf_name' => 'Pdf Name',
			'description' => 'Description',
			'country_id' => 'Country',
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
		$criteria->compare('advertisers_id',$this->advertisers_id);
		$criteria->compare('commercial_id',$this->commercial_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('commercial_name',$this->commercial_name,true);
		$criteria->compare('prospect',$this->prospect);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('contact_com',$this->contact_com,true);
		$criteria->compare('email_com',$this->email_com,true);
		$criteria->compare('contact_adm',$this->contact_adm,true);
		$criteria->compare('email_adm',$this->email_adm,true);
		$criteria->compare('email_validation',$this->email_validation,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('ret',$this->ret,true);
		$criteria->compare('tax_id',$this->tax_id,true);
		$criteria->compare('entity',$this->entity,true);
		$criteria->compare('net_payment',$this->net_payment,true);
		$criteria->compare('pdf_name',$this->pdf_name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('country_id',$this->country_id);

		$criteria->with=array('advertisers','country','commercial');
		$criteria->compare('advertisers.name', $this->advertiser_name, true);
		//$criteria->compare('advertisers.cat', $cat);
		$criteria->compare('commercial.name', $this->com_name, true);
		$criteria->compare('commercial.lastname', $this->com_lastname, true);
		$criteria->compare('country.name', $this->country_name, true);
		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			'pagination' => array(
                'pageSize' => 30,
            ),
			'sort'       => array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'advertiser_name'=>array(
						'asc'  =>'advertisers.name',
						'desc' =>'advertisers.name DESC',
		            ),
		            'com_name'=>array(
						'asc'  =>'commercial.name',
						'desc' =>'commercial.name DESC',
		            ),
		            'com_lastname'=>array(
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
	 * @return FinanceEntities the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
