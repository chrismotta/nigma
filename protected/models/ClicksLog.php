<?php

/**
 * This is the model class for table "clicks_log".
 *
 * The followings are the available columns in table 'clicks_log':
 * @property integer $id
 * @property integer $campaigns_id
 * @property integer $providers_id
 * @property string $tid
 * @property string $ext_tid
 * @property string $date
 * @property string $server_ip
 * @property string $user_agent
 * @property string $languaje
 * @property string $referer
 * @property string $ip_forwarded
 * @property string $country
 * @property string $city
 * @property string $carrier
 * @property string $device
 * @property string $os
 * @property string $app
 *
 * The followings are the available model relations:
 * @property Campaigns $campaigns
 * @property ConvLog[] $convLogs
 * @property Providers $providers
 */ 
class ClicksLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $totalClicks;
	public $totalConv;
	public $CTR;

	public $clics;

	// traffic
	public $clicks;
	public $conversions;
	public $provider;
	public $campaign;
	public $advertiser;
	public $traffic_source; // PROVIDERS_NAME
	public $convRate;
	public $revenue;
	public $country_name;
	public $conv;

	// Affiliates API
	public $provider_name;
	public $model_adv;
	public $vector_name;
	public $vector_rate;
 
	//csv
	public $dateStart;
	public $dateEnd;
	public $click_date;
	public $click_time;
	public $conv_date;
	public $conv_time;
	public $campaigns_name;
	public $traffic_source_type;
	public $product;
	public $vectors_id;
	public $only_conversions = false;
	public $spend;
	public $profit;


	public function macros()
	{
		return array(
			'{referer_domain}' => self::getDomain($this->referer),
			'{os}'             => $this->os!=' ' ? urlencode($this->os."-".$this->os_version) : '',
			'{device}'         => $this->device!=' ' ? urlencode($this->device."-".$this->device_model) : '',
			'{country}'        => $this->country ? urlencode($this->country) : '',
			'{carrier}'        => $this->carrier!='-' ? urlencode($this->carrier) : '',
			'{referer}'        => $this->referer ? urlencode($this->referer) : '',
			'{app}'            => $this->app ? urlencode($this->app) : '',
			'{keyword}'        => $this->keyword ? urlencode($this->keyword) : '',
			'{tmltoken}'       => $this->tid ? urlencode($this->tid) : '',
			);
	}

	public function tableName()
	{
		return 'clicks_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaigns_id, providers_id', 'required'),
			array('campaigns_id, providers_id', 'numerical', 'integerOnly'=>true),
			//array('tid, server_ip, user_agent, languaje, referer, ip_forwarded, country, city, carrier, device, os, app, redirect_url', 'length', 'max'=>255),
			//array('device_type', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaigns_id, providers_id, tid, ext_tid, date, server_ip, user_agent, languaje, referer, ip_forwarded, country, city, carrier, browser, device_type, device, conv_date, conv_time, os, app, redirect_url, network_type, keyword, creative, placement, campaigns_name, totalClicks, totalConv, CTR, query', 'safe', 'on'=>'search'),
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
			'providers'  => array(self::BELONGS_TO, 'Providers', 'providers_id'),
			'campaigns'  => array(self::BELONGS_TO, 'Campaigns', 'campaigns_id'),
			'convLogs'   => array(self::HAS_ONE, 'ConvLog', 'clicks_log_id'),
			'vectorsLog' => array(self::HAS_ONE, 'VectorsLog', 'clicks_log_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'             => 'ID',
			'campaigns_id'   => 'Campaign ID',
			'campaigns_name' => 'Campaign Name',
			'providers_id'   => 'Providers',
			'tid'            => 'Tid',
			'ext_tid'        => 'External Tid',
			'date'           => 'Date',
			'server_ip'      => 'Server Ip',
			'user_agent'     => 'User Agent',
			'languaje'       => 'Languaje',
			'referer'        => 'Referer',
			'ip_forwarded'   => 'Ip Forwarded',
			'country'        => 'Country',
			'city'           => 'City',
			'carrier'        => 'Carrier',
			'browser'        => 'Browser',
			'device_type'    => 'Device Type',
			'device'         => 'Device',
			'os'             => 'Os',
			'app'            => 'App',
			'clics'          => 'Clicks',
			'redirect_url'   => 'Redirect Url',
			'totalClicks'    => 'Clicks',
			'totalConv'      => 'Conversions',
			'CTR'            => 'CTR&nbsp;%',
			'conversions'    => 'Conv.',
			'convRate'       => 'CR&nbsp;%',
			'provider'       => 'Traffic Source',
			'country_name'   => 'Country',
			'conv_date'		=> 'Conversion Date',
			'conv_time'		=> 'Conversion Time'
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
		$criteria->compare('campaigns_id',$this->campaigns_id);
		$criteria->compare('providers_id',$this->providers_id);
		$criteria->compare('tid',$this->tid,true);
		$criteria->compare('ext_tid',$this->ext_tid,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('server_ip',$this->server_ip,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('languaje',$this->languaje,true);
		$criteria->compare('referer',$this->referer,true);
		$criteria->compare('ip_forwarded',$this->ip_forwarded,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('carrier',$this->carrier,true);
		$criteria->compare('browser',$this->browser,true);
		$criteria->compare('device_type',$this->device_type,true);
		$criteria->compare('device',$this->device,true);
		$criteria->compare('os',$this->os,true);
		$criteria->compare('app',$this->app,true);
		$criteria->compare('redirect_url',$this->redirect_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	// ACA HAY UN ERROR EN EL CALCULO DEL REVENUE, EN EL USO DE LOS RATES
	public function searchTraffic($dateStart='today',$dateEnd='today', $group=array(), $filters=array(), $isTest=false, $totals=false){

		$dateStart = date('Y-m-d', strtotime($dateStart)); 
		$dateEnd   = date('Y-m-d', strtotime($dateEnd));

		$criteria=new CDbCriteria;
		$criteria->with = array(
			'providers',
			'campaigns',
			'campaigns.opportunities.regions.financeEntities.advertisers', 
			'campaigns.opportunities.regions.country',
			);
		$criteria->join = 'LEFT JOIN conv_log c ON c.clicks_log_id = t.id';
		$criteria->select = array(
			'DATE(t.date) AS date',
			'count(t.id) AS clicks',
			'count(c.id) AS conversions',
			't.providers_id',
			'campaigns.name AS campaign',
			't.campaigns_id AS campaigns_id',
			'advertisers.name AS advertiser',
			'providers.name AS provider',
			'country.name as country_name',
			);
		$criteria->addCondition('DATE(t.date) BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"');
		
		if($isTest)
			$criteria->compare('t.providers_id','29');
		else
			$criteria->compare('t.providers_id','<>29');

		
		if( UserManager::model()->isUserAssignToRole('account_manager_admin') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
			$criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));

		//filters
		if(isset($filters['manager'])) 
			$criteria->compare('opportunities.account_manager_id',$filters['manager']);
		if(isset($filters['provider'])) 
			$criteria->compare('t.providers_id',$filters['provider']);
		if(isset($filters['advertiser'])) 
			$criteria->compare('advertisers.id',$filters['advertiser']);

		if(!$totals){

			$groupBy = array();
			$orderBy = array();
			
			if($group['date'] == 1) {
				$groupBy[] = 'DATE(t.date)';
				$orderBy[] = 'DATE(t.date) DESC';
			}
			if($group['prov'] == 1) {
				$groupBy[] = 't.providers_id';
				$orderBy[] = 'providers.name ASC';
			}
			if($group['adv'] == 1) {
				$groupBy[] = 'advertisers.id';
				$orderBy[] = 'advertisers.name ASC';
			}
			if($group['coun'] == 1) {
				$groupBy[] = 'country.id_location';
				$orderBy[] = 'country.name ASC';
			}
			if($group['camp'] == 1) {
				$groupBy[] = 't.campaigns_id';
				$orderBy[] = 't.campaigns_id ASC';
			}

			$criteria->group = join($groupBy,',');
			// $criteria->order = join($orderBy,',');

			return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=> KHtml::pagination(),
	            'sort'       =>array(
					'defaultOrder'=>join($orderBy,','),
			        'attributes'=>array(
						// Adding custom sort attributes
			            'date'=>array(
							'asc'  =>'date ASC',
							'desc' =>'date DESC',
			            ),
			            'provider'=>array(
							'asc'  =>'provider ASC',
							'desc' =>'provider DESC',
			            ),
			            'advertiser'=>array(
							'asc'  =>'advertiser ASC',
							'desc' =>'advertiser DESC',
			            ),
			            'country_name'=>array(
							'asc'  =>'country_name ASC',
							'desc' =>'country_name DESC',
			            ),
			            'campaign'=>array(
							'asc'  =>'campaigns_id ASC',
							'desc' =>'campaigns_id DESC',
			            ),
			        ),
			    ),
			));

		}else{
			$totalsArray = Self::model()->find($criteria);
			return $totalsArray;
		}
	}

	public function searchSem($report_type, $dateStart, $dateEnd, $campaigns_id=NULL)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.keyword',$this->keyword,true);
		$criteria->compare('t.placement',$this->placement,true);
		$criteria->compare('t.creative',$this->creative,true);

		$criteria->compare('t.match_type',$this->match_type,true);
		$criteria->compare('t.campaigns_id',$campaigns_id);

		$criteria->addCondition("DATE(t.date) BETWEEN '" . date('Y-m-d', strtotime($dateStart)) . "' AND '" . date('Y-m-d', strtotime($dateEnd)) . "'");

		// discard invalid results, this implied showing only adWords
		$criteria->addCondition("t." . $report_type . "!= ''");
		$criteria->addCondition("t." . $report_type . "!= '{" . $report_type . "}'");

		$criteria->select = array(
			't.campaigns_id',
			't.' . $report_type,
			't.match_type',
			'count(t.id) as totalClicks', 
			'count(conv_log.id) as totalConv',
			'ROUND(count(conv_log.id) / count(t.id) * 100, 2) as CTR',
		);

		$criteria->join = 'LEFT JOIN conv_log ON conv_log.clicks_log_id = t.id';
		
		$criteria->group = 't.' . $report_type . ", t.match_type";
	
		return new CActiveDataProvider($this, array(
			'criteria'   =>$criteria,
			'pagination' =>array(
                'pageSize'=>100,
            ),
			'sort'       =>array(
				'defaultOrder'=>'totalClicks DESC',
		        'attributes'=>array(
					// Adding custom sort attributes
		            'totalClicks'=>array(
						'asc'  =>'totalClicks',
						'desc' =>'totalClicks DESC',
		            ),
		            'totalConv'=>array(
						'asc'  =>'totalConv',
						'desc' =>'totalConv DESC',
		            ),
		            'CTR'=>array(
						'asc'  =>'CTR',
						'desc' =>'CTR DESC',
		            ),
		            // Adding all the other default attributes
		            '*',
		        ),
		    ),
		));
	}

	public function searchQuery($dateStart, $dateEnd, $campaigns_id=NULL, $query=NULL, $onlyConv)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.campaigns_id',$campaigns_id);
		$criteria->compare('t.query',$query,true);
		$criteria->compare('t.query',$this->query,true);

		if ($onlyConv) 
			$criteria->having = 'totalConv > 0';

		// discard invalid results, this implied showing only adWords
		$criteria->addCondition("t.query != ''"); 
		$criteria->addCondition("t.query IS NOT NULL"); 

		$criteria->addCondition("DATE(t.date) BETWEEN '" . date('Y-m-d', strtotime($dateStart)) . "' AND '" . date('Y-m-d', strtotime($dateEnd)) . "'");

		$criteria->select = array(
			't.campaigns_id',
			't.query',
			'count(t.id) as totalClicks', 
			'count(conv_log.id) as totalConv',
			'ROUND(count(conv_log.id) / count(t.id) * 100, 2) as CTR',
		);

		$criteria->join = 'LEFT JOIN conv_log ON conv_log.clicks_log_id = t.id';
		
		$criteria->group = 't.query';
	
		return new CActiveDataProvider($this, array(
			'criteria'   =>$criteria,
			'pagination' =>array(
                'pageSize'=>100,
            ),
			'sort'       =>array(
				'defaultOrder'=>'totalClicks DESC',
		        'attributes'=>array(
					// Adding custom sort attributes
		            'totalClicks'=>array(
						'asc'  =>'totalClicks',
						'desc' =>'totalClicks DESC',
		            ),
		            'totalConv'=>array(
						'asc'  =>'totalConv',
						'desc' =>'totalConv DESC',
		            ),
		            'CTR'=>array(
						'asc'  =>'CTR',
						'desc' =>'CTR DESC',
		            ),
		            // Adding all the other default attributes
		            '*',
		        ),
		    ),
		));
	}


	public function csvReport( $dateStart = 'today', $dateEnd = 'today', $provider = null, $onlyConversions = false, array $group = null, array $filters = null, $timeStart = '12:00 AM', $timeEnd = '11:59 PM', $totals = false )
	{
		$dateStart = date('Y-m-d', strtotime($dateStart)); 
		$dateEnd   = date('Y-m-d', strtotime($dateEnd));
		$timeStart = strtotime($dateStart . ' ' .$timeStart);
		$timeEnd = strtotime($dateEnd. ' ' .$timeEnd);

		$criteria = new CDbCriteria;
		$criteria->with = array(
			'campaigns',
			'campaigns.opportunities',
			'campaigns.opportunities.regions.financeEntities.advertisers',
			'campaigns.opportunities.regions.country',
			'providers',
			//'providers.country',
			'convLogs',
			'vectorsLog',
			'vectorsLog.vectors',
			);

		$grouped = false;

		if ( $group )
		{
			$groupBy = array();
			$orderBy = array();
		

			if($group['Country'] == 1) {
				$groupBy[] = 'providers.country_id';
				$orderBy[] = 'country.name ASC';
				$grouped = true;
			}			

			if($group['Campaign'] == 1) {
				$groupBy[] = 't.campaigns_id';
				$orderBy[] = 'campaigns.name ASC';
				$grouped = true;
			}

			if($group['Advertiser'] == 1) {
				$groupBy[] = 'advertisers.id';
				$orderBy[] = 'advertisers.name ASC';
				$grouped = true;
			}

			if($group['TrafficSource'] == 1) {
				$groupBy[] = 't.providers_id';
				$orderBy[] = 'providers.name ASC';
				$grouped = true;
			}

			if($group['TrafficSourceType'] == 1) {
				$groupBy[] = 'providers.type';
				$orderBy[] = 'providers.type ASC';
				$grouped = true;
			}		

			if($group['Vector'] == 1) {
				$groupBy[] = 'vectors.id';
				$orderBy[] = 'vectors.id ASC';
				$grouped = true;
			}			

			if($group['Date'] == 1) {
				$groupBy[] = 'click_date';
				$orderBy[] = 'click_date ASC';
				$grouped = true;
			}			

			if($group['Product'] == 1) {
				$groupBy[] = 'opportunities.product';
				$orderBy[] = 'opportunities.product ASC';
				$grouped = true;
			}
			if($group['ServerIP'] == 1) {
				$groupBy[] = 't.server_ip';
				$orderBy[] = 't.server_ip ASC';
				$grouped = true;
			}			
			if($group['Carrier'] == 1) {
				$groupBy[] = 't.carrier';
				$orderBy[] = 't.carrier ASC';
				$grouped = true;
			}	
			if($group['OS'] == 1) {
				$groupBy[] = 't.os';
				$orderBy[] = 't.os ASC';
				$grouped = true;
			}
			if($group['OSVersion'] == 1) {
				$groupBy[] = 't.os_version';
				$orderBy[] = 't.os_version ASC';
				$grouped = true;
			}	
			if($group['DeviceType'] == 1) {
				$groupBy[] = 't.device_type';
				$orderBy[] = 't.device_type ASC';
				$grouped = true;
			}				
			if($group['DeviceBrand'] == 1) {
				$groupBy[] = 't.device';
				$orderBy[] = 't.device ASC';
				$grouped = true;
			}		
			if($group['DeviceModel'] == 1) {
				$groupBy[] = 't.device_model';
				$orderBy[] = 't.device_model ASC';
				$grouped = true;
			}														
			if($group['Browser'] == 1) {
				$groupBy[] = 't.browser';
				$orderBy[] = 't.browser ASC';
				$grouped = true;
			}
			if($group['BrowserVersion'] == 1) {
				$groupBy[] = 't.browser_version';
				$orderBy[] = 't.browser_version ASC';
				$grouped = true;
			}	

			$criteria->group = join($groupBy,',');		
		}

		if ( !$grouped )
		{
			$criteria->group = 't.id';
		}

		if( UserManager::model()->isUserAssignToRole('account_manager_admin') || UserManager::model()->isUserAssignToRole('account_manager') )
			$criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));		

		if ( $filters )
		{
			/*
			if ( $filters['account_manager'] != NULL) {
				if(is_array($filters['account_manager']))
				{
					$query="(";
					$i=0;
					foreach ($filters['account_manager'] as $id) {	
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
					$criteria->compare('accountManager.id',$filters['account_manager']);
				}
			}
			*/
			if ( $filters['advertiser'] != NULL) {
				if(is_array($filters['advertiser']))
				{
					$query="(";
					$i=0;
					foreach ($filters['advertiser'] as $adv) {	
						if($i==0)			
							$query.="advertisers.id=".$adv;
						else
							$query.=" OR advertisers.id=".$adv;
						$i++;
					}
					$query.=")";
					$criteria->addCondition($query);				
				}
				else
				{
					$criteria->compare('advertisers.id',$filters['advertiser']);
				}
			}
			/*
			if ( $filters['opportunity'] != NULL) {
				if(is_array($filters['opportunity']))
				{
					$query="(";
					$i=0;
					foreach ($filters['opportunity'] as $opp) {	
						if($i==0)			
							$query.="opportunities.id=".$opp;
						else
							$query.=" OR opportunities.id=".$opp;
						$i++;
					}
					$query.=")";
					$criteria->addCondition($query);				
				}
				else
				{
					$criteria->compare('opportunities.id',$filters['opportunity']);
				}
			}
			*/
			if ( $filters['provider'] != NULL) {
				if(is_array($filters['provider']))
				{
					$query="(";
					$i=0;
					foreach ($filters['provider'] as $prov) {	
						if($i==0)			
							$query.="providers.id=".$prov;
						else
							$query.=" OR providers.id=".$prov;
						$i++;
					}
					$query.=")";
					$criteria->addCondition($query);				
				}
				else
				{
					$criteria->compare('providers.id',$filters['provider']);
				}
			}
			/*
			if ( $filters['carrier'] != NULL) {
				if(is_array($filters['carrier']))
				{
					$query="(";
					$i=0;
					foreach ($filters['carrier'] as $c) {	
						if($i==0)			
							$query.="carriers.id_carrier=".$c;
						else
							$query.=" OR carriers.id_carrier=".$c;
						$i++;
					}
					$query.=")";
					$criteria->addCondition($query);				
				}
				else
				{
					$criteria->compare('carriers.id_carrier',$filters['carrier']);
				}
			}		

			if ( $filters['category'] != NULL) {
				if(is_array($filters['category']))
				{
					$query="(";
					$i=0;
					foreach ($filters['category'] as $cat) {	
						if($i==0)			
							$query.="advertisers.cat='".$cat."'";
						else
							$query.=" OR advertisers.cat='".$cat."'";
						$i++;
					}
					$query.=")";
					$criteria->addCondition($query);				
				}
				else
				{
					$criteria->compare('advertisers.cat',$filters['category']);
				}
			}
			*/
			if ( $filters['country'] != NULL) {
				if(is_array($filters['country']))
				{
					$query="(";
					$i=0;
					foreach ($filters['country'] as $cat) {	
						if($i==0)			
							$query.="country.id_location='".$cat."'";
						else
							$query.=" OR country.id_location='".$cat."'";
						$i++;
					}
					$query.=")";
					$criteria->addCondition($query);				
				}
				else
				{
					$criteria->compare('country.id_location',$filters['country']);
				}
			}

			if ( $filters['campaign'] != NULL) {
				if(is_array($filters['campaign']))
				{
					$query="(";
					$i=0;
					foreach ($filters['campaign'] as $cat) {	
						if($i==0)			
							$query.="t.campaigns_id='".$cat."'";
						else
							$query.=" OR t.campaigns_id='".$cat."'";
						$i++;
					}
					$query.=")";
					$criteria->addCondition($query);				
				}
				else
				{
					$criteria->compare('t.campaigns_id',$filters['campaign']);
				}
			}

			if ( $filters['vector'] != NULL) {
				if(is_array($filters['vector']))
				{
					$query="(";
					$i=0;
					foreach ($filters['vector'] as $cat) {	
						if($i==0)			
							$query.="vectorsLog.vectors_id='".$cat."'";
						else
							$query.=" OR vectorsLog.vectors_id='".$cat."'";
						$i++;
					}
					$query.=")";
					$criteria->addCondition($query);				
				}
				else
				{
					$criteria->compare('vectorsLog.vectors_id',$filters['vector']);
				}
			}						
		}

		if ( $provider )
		{
			$criteria->compare('t.providers_id',$this->providers_id);
			$criteria->compare('providers.type', array('Google AdWords','Network','Affiliate'));			
		}

		$criteria->addBetweenCondition('DATE(t.date)', $dateStart, $dateEnd);

		if($onlyConversions)
			$criteria->addCondition('convLogs.id IS NOT NULL');

		$rev = '
			SUM(
				CASE 
					WHEN ( opportunities.model_adv="CPC" OR opportunities.model_adv="CPV" ) AND opportunities.rate IS NOT NULL THEN
						opportunities.rate 
					WHEN ( opportunities.model_adv="CPA" OR opportunities.model_adv="CPL" OR opportunities.model_adv="CPI" ) AND convLogs.id IS NOT NULL AND opportunities.rate IS NOT NULL THEN
						opportunities.rate 
					ELSE 0
				END
			) as revenue
		';

		$spnd = '
			SUM(
				CASE 
					WHEN vectorsLog.vectors_id IS NOT NULL AND vectors.rate IS NOT NULL AND convLogs.id IS NOT NULL THEN 
						vectors.rate
					WHEN convLogs.id IS NOT NULL AND campaigns.external_rate IS NOT NULL THEN 
						campaigns.external_rate
					ELSE 0
				END
			) as spend
		';

		$profit = '
			(
				SUM(
					CASE 
						WHEN ( opportunities.model_adv="CPC" OR opportunities.model_adv="CPV" ) AND opportunities.rate IS NOT NULL THEN
							opportunities.rate 
						WHEN ( opportunities.model_adv="CPA" OR opportunities.model_adv="CPL" OR opportunities.model_adv="CPI" ) AND convLogs.id IS NOT NULL AND opportunities.rate IS NOT NULL THEN
							opportunities.rate 
						ELSE 0
					END
				)
				-
				SUM(
					CASE 
						WHEN vectorsLog.vectors_id IS NOT NULL AND vectors.rate IS NOT NULL AND convLogs.id IS NOT NULL THEN 
							vectors.rate
						WHEN convLogs.id IS NOT NULL AND campaigns.external_rate IS NOT NULL THEN 
							campaigns.external_rate
						ELSE 0
					END
				)
			) as profit
		';

		$criteria->select = array(
			't.*',
			'CONCAT("\'", t.server_ip) as server_ip',
			'DATE_FORMAT(DATE(t.date), "%d-%m-%Y") as click_date',
			'TIME(t.date) as click_time',
			'DATE_FORMAT(DATE(convLogs.date), "%d-%m-%Y") as conv_date',
			'TIME(convLogs.date) as conv_time',
			'advertisers.name as advertiser',
			'providers.name as traffic_source',
			'providers.type as traffic_source_type',
			'providers.country_id as country_id', 
			'campaigns.name as campaigns_name',
			'opportunities.product as product',
			'country.name as country_name',
			'vectorsLog.vectors_id as vectors_id',
			'vectors.name as vector_name',
			'count(convLogs.id) as totalConv',
			'count(t.id) as totalClicks',
			$rev,
			$spnd,
			$profit,
		);

		// return only totals if requested
		if ( $totals )
		{
			FilterManager::model()->addUserFilter($criteria, 'search');

			$totals                =array();
			$totals['totalClicks'] =0;
			$totals['totalConv']   =0;
			$totals['revenue']     =0;
			$totals['spend']       =0;
			$totals['profit']      =0;
			if($dailys=Self::model()->findAll($criteria))
			{			
				foreach ($dailys as $data) {
					$totals['totalClicks'] +=$data->totalClicks;
					$totals['totalConv']   +=$data->totalConv;
					$totals['revenue']     +=$data->revenue;
					$totals['spend']       +=$data->spend;
					$totals['profit']      +=$data->profit;
				}
			}
			return $totals;				
		}

		$pagination = isset($_REQUEST['v']) ? null : false;

		return new CActiveDataProvider($this, array(
			'pagination'=>$pagination,
			'criteria'=>$criteria,
			'sort'       =>array(
				'defaultOrder'=>'totalClicks DESC',
		        'attributes'=>array(
					// Adding custom sort attributes
		            'totalClicks'=>array(
						'asc'  =>'totalClicks',
						'desc' =>'totalClicks DESC',
		            ),
		            'totalConv'=>array(
						'asc'  =>'totalConv',
						'desc' =>'totalConv DESC',
		            ),
		            'click_date'=>array(
						'asc'  =>'click_date',
						'desc' =>'click_date DESC',
		            ),
		            'click_time'=>array(
						'asc'  =>'click_time',
						'desc' =>'click_time DESC',
		            ),	
		            'conv_date'=>array(
						'asc'  =>'convLogs.date',
						'desc' =>'convLogs.date DESC',
		            ),
		            'conv_time'=>array(
						'asc'  =>'convLogs.date',
						'desc' =>'convLogs.date DESC',
		            ),	
		            'revenue'=>array(
						'asc'  =>'revenue',
						'desc' =>'revenue DESC',
		            ),
		            'spend'=>array(
						'asc'  =>'spend',
						'desc' =>'spend DESC',
		            ),	
		            'profit'=>array(
						'asc'  =>'profit',
						'desc' =>'profit DESC',
		            ),			    	            		            		            		            
		            'campaigns_name'=>array(
						'asc'  =>'campaign.name',
						'desc' =>'campaigns.name DESC',
		            ),			
		            'vector_name'=>array(
						'asc'  =>'vectors.name',
						'desc' =>'vectors.name DESC',
		            ),				            
		            'advertiser'=>array(
						'asc'  =>'advertisers.name',
						'desc' =>'advertisers.name DESC',
		            ),	
		            'traffic_source'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),	
		            'country_name'=>array(
						'asc'  =>'country.name',
						'desc' =>'country.name DESC',
		            ),			            		            
		            'traffic_source_type'=>array(
						'asc'  =>'providers.type',
						'desc' =>'providers.type DESC',
		            ),			            
		            // Adding all the other default attributes
		            '*',
		        ),
		    ),		
		));
	}
	


	public function getMatchType()
	{
		switch ($this->match_type) {
			case 'e':
				return 'exact';
			case 'p':
				return 'phrase';
			case 'b':
				return 'broad';
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClicksLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getDomain($url)
	{		
		preg_match('%[^http,https\:\/\/][^\/]+%', $url, $match);
		return isset($match[0]) ? $match[0] : '';
	}

	public function haveMacro($url)
	{
		preg_match('%\{[a-z \_]+\}%', $url, $match);
		return isset($match[0]) ? true : false;
	}

	public function replaceMacro($url)
	{	
		return str_replace(array_keys(self::macros()),array_values(self::macros()),$url);
	}
}
