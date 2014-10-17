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
 * @property string $freq_cap
 * @property integer $imp_per_day
 * @property integer $imp_total
 * @property string $targeting
 * @property string $sizes
 * @property string $channel
 * @property string $channel_description
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

	public $country_name;
	public $carrier_mobile_brand;
	public $account_manager_name;
	public $account_manager_lastname;
	public $ios_name;
	public $advertiser_name;
	public $currency;
	public $open_budget;
	public $multi_carrier;
	public $multi_rate;

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
			array('country_id, model_adv, wifi, ios_id', 'required'),
			array('carriers_id, account_manager_id, country_id, wifi, ios_id, imp_per_day, imp_total', 'numerical', 'integerOnly'=>true),
			array('rate, budget', 'length', 'max'=>11),
			array('model_adv', 'length', 'max'=>3),
			array('product, comment, targeting, sizes, channel_description', 'length', 'max'=>255),
			array('server_to_server, freq_cap', 'length', 'max'=>45),
			array('startDate, endDate', 'safe'),
			array('channel', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, advertiser_name, currency, carriers_id, country_name, carrier_mobile_brand, account_manager_name, account_manager_lastname, ios_name, rate, model_adv, product, account_manager_id, comment, country_id, wifi, budget, server_to_server, startDate, endDate, ios_id', 'safe', 'on'=>'search'),
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
			'campaigns'      => array(self::HAS_MANY, 'Campaigns', 'opportunities_id'),
			'country'        => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'carriers'       => array(self::BELONGS_TO, 'Carriers', 'carriers_id'),
			'ios'            => array(self::BELONGS_TO, 'Ios', 'ios_id'),
			'accountManager' => array(self::BELONGS_TO, 'Users', 'account_manager_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                       => 'ID',
			'carriers_id'              => 'Carrier',
			'rate'                     => 'Rate',
			'model_adv'                => 'Model',
			'product'                  => 'Product',
			'account_manager_id'       => 'Account Manager',
			'comment'                  => 'Comment',
			'country_id'               => 'Country',
			'wifi'                     => 'Wifi',
			'budget'                   => 'Budget',
			'server_to_server'         => 'Server To Server',
			'startDate'                => 'Start Date',
			'endDate'                  => 'End Date',
			'ios_id'                   => 'Ios',
			'freq_cap'                 => 'Frequency Cap',
			'imp_per_day'              => 'Impression Per Day',
			'imp_total'                => 'Impression Total',
			'targeting'                => 'Targeting',
			'sizes'                    => 'Sizes',
			'channel'                  => 'Channel',
			'channel_description'      => 'Channel Description',
			
			'country_name'             => 'Country',
			'carrier_mobile_brand'     => 'Carrier',
			'account_manager_name'     => 'Account Manager',
			'account_manager_lastname' => 'Account Manager',
			'ios_name'                 => 'IO',
			'advertiser_name'          => 'Advertiser',
			'currency'                 => 'Currency',
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
	public function search($accountManager=NULL, $advertiser=NULL, $country=NULL, $io=NULL)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('carriers_id',$this->carriers_id);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('model_adv',$this->model_adv,true);
		$criteria->compare('product',$this->product,true);
		$criteria->compare('account_manager_id',$this->account_manager_id);
		$criteria->compare('comment',$this->comment,true);
		if($country==NULL)$criteria->compare('country_id',$this->country_id);
		else $criteria->compare('t.country_id',$country);
		$criteria->compare('wifi',$this->wifi);
		$criteria->compare('budget',$this->budget,true);
		$criteria->compare('server_to_server',$this->server_to_server,true);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('ios_id',$this->ios_id);
		$criteria->compare('freq_cap',$this->freq_cap,true);
		$criteria->compare('imp_per_day',$this->imp_per_day);
		$criteria->compare('imp_total',$this->imp_total);
		$criteria->compare('targeting',$this->targeting,true);
		$criteria->compare('sizes',$this->sizes,true);
		$criteria->compare('channel',$this->channel,true);
		$criteria->compare('channel_description',$this->channel_description,true);

		$criteria->with = array( 'country', 'carriers', 'ios', 'accountManager', 'ios.advertisers');
		$criteria->compare('country.ISO2', $this->country_name, true);
		$criteria->compare('carriers.mobile_brand', $this->carrier_mobile_brand, true);
		$criteria->compare('accountManager.name', $this->account_manager_name, true);
		$criteria->compare('accountManager.lastname', $this->account_manager_lastname, true);
		$criteria->compare('ios.name', $this->ios_name, true);
		$criteria->compare('advertisers.name', $this->advertiser_name, true);
		$criteria->compare('ios.currency', $this->currency, true);
		if($accountManager != NULL)$criteria->compare('accountManager.id',$accountManager);
		if($advertiser != NULL)$criteria->compare('advertisers.id',$advertiser);
		if($io != NULL)$criteria->addCondition('t.ios_id='.$io);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
                'pageSize' => 30,
            ),
			'sort'     =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'advertiser_name'=>array(
						'asc'  =>'advertisers.name',
						'desc' =>'advertisers.name DESC',
		            ),
		            'country_name'=>array(
						'asc'  =>'country.name',
						'desc' =>'country.name DESC',
		            ),
		            'carrier_mobile_brand'=>array(
						'asc'  =>'carriers.mobile_brand',
						'desc' =>'carriers.mobile_brand DESC',
		            ),
		            'account_manager_name'=>array(
						'asc'  =>'accountManager.name',
						'desc' =>'accountManager.name DESC',
		            ),
		            'account_manager_lastname'=>array(
						'asc'  =>'accountManager.lastname',
						'desc' =>'accountManager.lastname DESC',
		            ),
		            'ios_name'=>array(
						'asc'  =>'ios.name',
						'desc' =>'ios.name DESC',
		            ),
		            'currency'=>array(
						'asc'  =>'ios.currency',
						'desc' =>'ios.currency DESC',
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
	 * @return Opportunities the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getVirtualName()
	{
		$adv = Advertisers::model()->findByPk( Ios::model()->findByPk($this->ios_id)->advertisers_id)->name;
		
		$country = '';
		if ( $this->country_id !== NULL )
			$country = '-' . GeoLocation::model()->findByPk($this->country_id)->ISO2;

		$carrier = '';
		if ( $this->carriers_id === NULL ) {
			$carrier = '-MULTI';
		} else {
			$carrier = '-' . Carriers::model()->findByPk($this->carriers_id)->mobile_brand;
		}

		$product = '';
		if ( $this->product != NULL )
			$product = '-' . $this->product;
		
		return $adv . $country . $carrier . '-' . $this->rate . $product;
	}
	public function findByIo($io)
	{		
		$criteria = new CDbCriteria;
		$criteria->addCondition("ios_id=".$io."");
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>false,
				'sort'=>array(
					'attributes'   =>array(
			            '*',
			        ),
			    ),

			));
	}

}
