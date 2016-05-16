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
 * @property string $status
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
	public $country_id;
	public $carrier_mobile_brand;
	public $account_manager_name;
	public $account_manager_lastname;
	public $regions_name;
	public $finance_entities_name;
	public $advertiser_name;
	public $currency;
	public $open_budget;
	public $multi_carrier;
	public $multi_rate;
	public $name;

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
			array('model_adv, wifi, regions_id, account_manager_id', 'required'),
			array('carriers_id, account_manager_id, country_id, wifi, regions_id, imp_per_day, imp_total, closed_deal', 'numerical', 'integerOnly'=>true),
			array('close_amount, agency_commission', 'numerical', 'integerOnly'=>false),
			array('rate, budget', 'length', 'max'=>11),
			//array('comment', 'length', 'max'=>500),
			array('model_adv', 'length', 'max'=>3),
			array('product, targeting, sizes, channel_description', 'length', 'max'=>255),
			array('server_to_server, freq_cap', 'length', 'max'=>45),
			array('status', 'length', 'max'=>8),
			array('startDate, endDate, comment', 'safe'),
			array('channel', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, advertiser_name, currency, carriers_id, country_name, carrier_mobile_brand, account_manager_name, account_manager_lastname, ios_name, rate, model_adv, product, account_manager_id, comment, country_id, wifi, budget, server_to_server, startDate, endDate, regions_id, status, close_amount, agency_commission, closed_deal', 'safe', 'on'=>'search'),
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
			'regions'        => array(self::BELONGS_TO, 'Regions', 'regions_id'),
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
			'rate'                     => 'Adv. Rate',
			'model_adv'                => 'Adv. Model',
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
			'regions_id'               => 'Regions',
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
			'regions_name'             => 'Region',
			'advertiser_name'          => 'Advertiser',
			'currency'                 => 'Currency',
			'status'                   => 'Status',
			'name'                     => 'Opportunity',
		);
	}

	public function behaviors()
	{
		return array(
	    		'modelVersioning' => array(
	          		'class' => 'SAModelVersioning',
	       		)
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
	public function search($accountManager=NULL, $advertiser=NULL, $country=NULL, $io=NULL, $region=NULL)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		if(isset($region))
			$criteria->compare('regions_id',$region);
		else
			$criteria->compare('regions_id',$this->regions_id);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('carriers_id',$this->carriers_id);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('model_adv',$this->model_adv,true);
		$criteria->compare('product',$this->product,true);
		$criteria->compare('account_manager_id',$this->account_manager_id);
		$criteria->compare('comment',$this->comment,true);
		if($country==NULL)$criteria->compare('regions.country_id',$this->country_id);
		else $criteria->compare('regions.country_id',$country);
		$criteria->compare('wifi',$this->wifi);
		$criteria->compare('budget',$this->budget,true);
		$criteria->compare('server_to_server',$this->server_to_server,true);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('regions.region',$this->regions_name);
		$criteria->compare('freq_cap',$this->freq_cap,true);
		$criteria->compare('imp_per_day',$this->imp_per_day);
		$criteria->compare('imp_total',$this->imp_total);
		$criteria->compare('targeting',$this->targeting,true);
		$criteria->compare('sizes',$this->sizes,true);
		$criteria->compare('channel',$this->channel,true);
		$criteria->compare('channel_description',$this->channel_description,true);
		$criteria->compare('t.status',$this->status,true);

		$criteria->with = array('regions', 'regions.country', 'regions.financeEntities', 'regions.financeEntities.advertisers', 'carriers', 'accountManager');
		$criteria->compare('country.name', $this->country_name, true);
		$criteria->compare('carriers.mobile_brand', $this->carrier_mobile_brand, true);
		$criteria->compare('accountManager.name', $this->account_manager_name, true);
		$criteria->compare('accountManager.lastname', $this->account_manager_lastname, true);
		$criteria->compare('financeEntities.name', $this->finance_entities_name, true);
		$criteria->compare('advertisers.name', $this->advertiser_name, true);
		$criteria->compare('financeEntities.currency', $this->currency, true);

		if( UserManager::model()->isUserAssignToRole('account_manager_admin') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
			$criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));

		if($accountManager != NULL)
			$criteria->compare('accountManager.id',$accountManager);
		if($advertiser != NULL)
			$criteria->compare('advertisers.id',$advertiser);
		if($io != NULL)
			$criteria->addCondition('t.ios_id='.$io);

		FilterManager::model()->addUserFilter($criteria, 'opportunities');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> KHtml::pagination(),
			'sort'     =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'regions_name'=>array(
						'asc'  =>'regions.region',
						'desc' =>'regions.region DESC',
		            ),
		            'advertiser_name'=>array(
						'asc'  =>'advertisers.name',
						'desc' =>'advertisers.name DESC',
		            ),
		            'finance_entities_name'=>array(
						'asc'  =>'financeEntities.name',
						'desc' =>'financeEntities.name DESC',
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
		$adv = $this->regions->financeEntities->advertisers->name;
		
		$country = '';
		if ( $this->regions->country_id !== NULL )
			$country = '-' . $this->regions->country->ISO2;

		$carrier = '';
		if ( $this->carriers_id === NULL ) {
			$carrier = '-MULTI';
		} else {
			$carrier = '-' . $this->carriers->mobile_brand;
		}

		$product = '';
		if ( $this->product != NULL )
			$product = '-' . $this->product;
		
		return $this->id . '-' . $adv . $country . $carrier . '-' . $this->rate . $product;
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
	
	public function getManagersDistribution($accountManager=NULL,$advertisers=NULL,$countries=NULL,$models=NULL)
	{
		/*
		
		SELECT u.username,a.name,o.id,g.name,o.model_adv FROM opportunities o 
		inner join users u on o.account_manager_id=u.id
		inner join ios i on o.ios_id=i.id
		inner join advertisers a on i.advertisers_id=a.id
		inner join geo_location g on o.country_id=g.id_location
		group by o.id
		 */

		$criteria=new CDbCriteria;
		$criteria->with=array('accountManager','country','regions','regions.financeEntities','regions.financeEntities.advertisers');

		if( UserManager::model()->isUserAssignToRole('account_manager_admin') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
			$criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));


		if ( $accountManager != NULL) {
			if(is_array($accountManager))
			{
				$query="(";
				$i=0;
				foreach ($accountManager as $id) {	
					if($i==0)			
						$query.="accountManager.id=".$id;
					else
						$query.=" OR accountManager.id=".$id;
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('accountManager.id',$accountManager);
			}
		}

		if ( $advertisers != NULL) {
			if(is_array($advertisers))
			{
				$query="(";
				$i=0;
				foreach ($advertisers as $id) {	
					if($i==0)			
						$query.="financeEntities.advertisers_id=".$id;
					else
						$query.=" OR financeEntities.advertisers_id=".$id;
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('financeEntities.advertisers_id',$advertisers);
			}
		}

		if ( $countries != NULL) {
			if(is_array($countries))
			{
				$query="(";
				$i=0;
				foreach ($countries as $id) {	
					if($i==0)			
						$query.="t.country_id=".$id;
					else
						$query.=" OR t.country_id=".$id;
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('t.country_id',$countries);
			}
		}

		if ( $models != NULL) {
			if(is_array($models))
			{
				$query="(";
				$i=0;
				foreach ($models as $id) {	
					if($i==0)			
						$query.="t.model_adv='".$id."'";
					else
						$query.=" OR t.model_adv='".$id."'";
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('t.model_adv',$models);
			}
		}
		$criteria->compare('t.status','Active');
		$criteria->order = 'accountManager.lastname ASC, advertisers.name ASC';

		// $criteria->compare('advertisers.name', $this->advertiser_name, true);
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>false,
				'sort'=>array(
					'attributes'   =>array(						
			            'advertiser_name'=>array(
							'asc'  =>'advertisers.name',
							'desc' =>'advertisers.name DESC',
			            ),				
			            'name'=>array(
							'asc'  =>'advertisers.name',
							'desc' =>'advertisers.name DESC',
			            ),
			            '*',
			        ),
			    ),

			));
	}

	public function getRate($date='today')
	{
		$q = Yii::app()->db->createCommand()
                    ->select('rate')
                    ->from('opportunities_version')
                    ->where("id=:id AND DATE(created_time)<=:date", array(':date' => date('Y-m-d', strtotime($date)), ":id" => $this->id))
                    ->order('created_time DESC')
                    ->queryAll(false);
        // return first column of first register if exists
        return isset($q[0][0]) ? $q[0][0] : NULL; 
	}

	public function getTotalAgencyCommission()
	{
		return ($this->agency_commission/100)*$this->close_amount;
	}

	public function getTotalCloseDeal()
	{
		return $this->close_amount - $this->getTotalAgencyCommission();
	}

	public function checkIsAbleInvoice()
	{
		if($this->closed_deal==1)
		{
			$endDate=date('Y-m-d',strtotime($this->endDate));
			$now=date('Y-m-d',strtotime('NOW'));
			return $endDate <= $now ? true : false;			
		}
		else
			return false;
	}
}
