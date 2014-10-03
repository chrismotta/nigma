<?php

/**
 * This is the model class for table "campaigns".
 *
 * The followings are the available columns in table 'campaigns':
 * @property integer $id
 * @property string $name
 * @property integer $networks_id
 * @property integer $campaign_categories_id
 * @property integer $wifi
 * @property integer $formats_id
 * @property string $cap
 * @property string $model
 * @property integer $ip
 * @property integer $devices_id
 * @property string $url
 * @property string $status
 * @property integer $opportunities_id
 * @property boolean $post_data
 *
 * The followings are the available model relations:
 * @property Networks $networks
 * @property Devices $devices
 * @property Formats $formats
 * @property CampaignCategories $campaignCategories
 * @property Opportunities $opportunities
 * @property ConvLog[] $convLogs
 * @property DailyReport[] $dailyReports
 * @property Vectors[] $vectors
 */
class Campaigns extends CActiveRecord
{
	/**
	 * Related columns availables in search rules
	 * @var [type] advertisers_name
	 * @var [type] opportunities_rate
	 * @var [type] opportunities_carrier
	 */
	public $advertisers_name;
	public $opportunities_rate;
	public $opportunities_carrier;
	public $ios_name;
	public $vectors_id;
	public $net_currency;
	public $clicks;
	public $conv;
	public $account_manager;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'campaigns';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, networks_id, campaign_categories_id, wifi, formats_id, cap, model, devices_id, url, opportunities_id', 'required'),
			array('networks_id, campaign_categories_id, wifi, formats_id, ip, devices_id, opportunities_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('cap', 'length', 'max'=>11),
			array('model', 'length', 'max'=>3),
			array('url', 'length', 'max'=>256),
			array('status', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,account_manager, name, advertisers_name, opportunities_rate, opportunities_carrie, networks_id, campaign_categories_id, wifi, formats_id, cap, model, ip, devices_id, url, status, opportunities_id, net_currency', 'safe', 'on'=>'search'),
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
			'networks'           => array(self::BELONGS_TO, 'Networks', 'networks_id'),
			'devices'            => array(self::BELONGS_TO, 'Devices', 'devices_id'),
			'formats'            => array(self::BELONGS_TO, 'Formats', 'formats_id'),
			'campaignCategories' => array(self::BELONGS_TO, 'CampaignCategories', 'campaign_categories_id'),
			'opportunities'      => array(self::BELONGS_TO, 'Opportunities', 'opportunities_id'),
			'bannerSizes'        => array(self::BELONGS_TO, 'BannerSizes', 'banner_sizes_id'),
			'convLogs'           => array(self::HAS_MANY, 'ConvLog', 'campaign_id'),
			'clicksLogs'         => array(self::HAS_MANY, 'ClicksLog', 'campaign_id'),
			'dailyReports'       => array(self::HAS_MANY, 'DailyReport', 'campaigns_id'),
			'vectors'            => array(self::MANY_MANY, 'Vectors', 'vectors_has_campaigns(campaigns_id, vectors_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                     => 'ID',
			'name'                   => 'Name',
			'networks_id'            => 'Networks',
			'campaign_categories_id' => 'Campaign Categories',
			'wifi'                   => 'Wifi',
			'formats_id'             => 'Formats',
			'cap'                    => 'Cap',
			'model'                  => 'Model',
			'ip'                     => 'Ip',
			'devices_id'             => 'Devices',
			'url'                    => 'Url',
			'status'                 => 'Status',
			'opportunities_id'       => 'Opportunities',
			// Header names for the related columns
			'advertisers_name'       => 'Advertiser', 
			'opportunities_rate'     => 'Rate', 
			'opportunities_carrier'  => 'Carrier',
			'post_data'              => 'Post Data',
			'banner_sizes_id'        => 'Banner Sizes',
			'net_currency'           => 'Net Currency',
			'clicks'           		 => 'Clicks Log',
			'conv'		           	 => 'Convertions Log',
			'account_manager' => 'Account Manager',
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
		$criteria->compare('networks_id',$this->networks_id);
		$criteria->compare('campaign_categories_id',$this->campaign_categories_id);
		$criteria->compare('t.wifi',$this->wifi);
		$criteria->compare('formats_id',$this->formats_id);
		$criteria->compare('cap',$this->cap,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('ip',$this->ip);
		$criteria->compare('devices_id',$this->devices_id);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('t.status','Active');
		$criteria->compare('opportunities_id',$this->opportunities_id);
		$criteria->compare('post_data',$this->post_data);
		$criteria->compare('banner_sizes_id',$this->banner_sizes_id);

		//We need to list all related tables in with property
		$criteria->with = array('opportunities','opportunities.accountManager', 'opportunities.ios', 'opportunities.ios.advertisers', 'opportunities.country', 'vectors', 'networks');
		// Related search criteria items added (use only table.columnName)
		$criteria->compare('advertisers.name',$this->advertisers_name, true);
		$criteria->compare('opportunities.rate',$this->opportunities_rate, true);
		$criteria->compare('opportunities.carrier',$this->opportunities_carrier, true);
		$criteria->compare('accountManager.name',$this->account_manager, true);

		//nomenclatura
		$criteria->compare('t.id',$this->name,true);
		$criteria->compare('country.ISO2',$this->name,true,'OR');
		$criteria->compare('t.name',$this->name,true,'OR');

		// Filter depending if user has "media" or "commercial" role
		if ( in_array('commercial', Yii::app()->authManager->getRoles(Yii::app()->user->id), true) )
			FilterManager::model()->addUserFilter($criteria, 'campaign.commercial');
		else
			FilterManager::model()->addUserFilter($criteria, 'campaign.account');

		$criteria->compare('ios.name',$this->ios_name, true);
		$criteria->compare('networks.currency',$this->net_currency, true);
		// $criteria->compare('vectors_has_campaigns.vectors',$this->vectors_id, true);

		return new CActiveDataProvider($this, array(
			'criteria' =>$criteria,
			// Setting 'sort' property in order to add 
			// a sort tool in the related collumns
            'pagination'=>array(
                'pageSize'=>50,
            ),
			'sort'     =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'advertisers_name'=>array(
						'asc'  =>'advertisers.name',
						'desc' =>'advertisers.name DESC',
		            ),
		            'account_manager'=>array(
						'asc'  =>'accountManager.name',
						'desc' =>'accountManager.name DESC',
		            ),
		            'opportunities_rate'=>array(
						'asc'  =>'opportunities.rate',
						'desc' =>'opportunities.rate DESC',
		            ),
		            'opportunities_carrier'=>array(
						'asc'  =>'opportunities.carrier',
						'desc' =>'opportunities.carrier DESC',
		            ),
		            'ios_name'=>array(
						'asc'  =>'ios.id',
						'desc' =>'ios.id DESC',
		            ),
		            'net_currency'=>array(
						'asc'  =>'networks.currency',
						'desc' =>'networks.currency DESC',
		            ),
		            // Adding all the other default attributes
		            '*',
		        ),
		    ),
		));
	}

	/**
	 * Retrieves a list of models for specified network and date. Ignore the campaigns that had already info entry.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function searchByNetworkAndDate($network_id, $date)
	{
		$criteria=new CDbCriteria;

		if ( $network_id ) {
			$criteria->compare('networks_id', $network_id);
			$criteria->addCondition("t.id NOT IN (SELECT d.campaigns_id FROM daily_report d WHERE d.networks_id=". $network_id . " AND d.date='". date('Y-m-d', strtotime($date)) . "')");
		} else {
			$criteria->compare('networks_id', -1); // Select none
		}

		$criteria->with = array('opportunities', 'opportunities.country');
		
		// external name
		$criteria->compare('t.id', $this->name, true);
		$criteria->compare('country.ISO2', $this->name, true, 'OR');
		$criteria->compare('t.name', $this->name, true, 'OR');

		FilterManager::model()->addUserFilter($criteria, 'campaign.account');

		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			// Setting 'sort' property in order to add 
			// a sort tool in the related collumns
			'pagination' => false,
			'sort'       => array(
		        'attributes'=>array(
		        	'name'=>array(
		        		'asc'  =>'t.id',
						'desc' =>'t.id DESC',
		        	),
		        	// Adding all the other default attributes
		            '*',
		        ),
		    ),
		));
	}

	public function searchWidhVectors(){

	    $criteria = new CDbCriteria;
	    $criteria->with = array('vectors');
		$criteria->together = true;
	    $criteria->compare('vectors.id', $this->vectors_id);

		return new CActiveDataProvider($this, array(
			'criteria' =>$criteria,
			)
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Campaigns the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getExternalName($id)
	{
		$model = Campaigns::model()->findByPk($id);
		$opportunity = Opportunities::model()->findByPk($model->opportunities_id);
		$ios = Ios::model()->findByPk($opportunity->ios_id);
		$adv = Advertisers::model()->findByPk($ios->advertisers_id)->prefix;

		$country = '';
		if ( $opportunity->country_id !== NULL )
			$country = '-' . GeoLocation::model()->findByPk($opportunity->country_id)->ISO2;

		$carrier = '-MUL';
		if ( $opportunity->carriers_id !== NULL )
			$carrier = '-' . substr( Carriers::model()->findByPk($opportunity->carriers_id)->mobile_brand , 0 , 3);

		$wifi_ip = $model->wifi ? '-WIFI' : '';
		$wifi_ip .= $model->ip ? '-IP' : '';
		
		$device = '';
		if ( $model->devices_id !== NULL )
			$device = '-' . Devices::model()->findByPk($model->devices_id)->prefix;

		$network = '';
		if ( $model->networks_id !== NULL )
			$network = '-' . Networks::model()->findByPk($model->networks_id)->prefix;
		
		$product = $opportunity->product ? '-' . $opportunity->product : '';

		$format = '';
		if ( $model->formats_id !== NULL )
			$format = '-' . Formats::model()->findByPk($model->formats_id)->prefix;
		
		// *CID* ADV(5) COUNTRY(2) CARRIER(3) [WIFI-IP] DEVICE(1) NET(2) [PROD] FORM(3) NAME
		return $model->id . '-' . $adv . $country . $carrier . $wifi_ip . $device . $network . $product . $format . '-' . $model->name;
	}

	public function countClicks($dateStart=NULL, $dateEnd=NULL)
	{	
		if(!$dateStart)	$dateStart = 'today' ;
		if(!$dateEnd) $dateEnd   = 'today';
		
		$dateStart = date('Y-m-d', strtotime($dateStart));
		$dateEnd = date('Y-m-d', strtotime($dateEnd));
		//echo $dateStart . ' - ' . $dateEnd;
		$query = ClicksLog::model()->count("campaigns_id=:campaignid AND DATE(date)>=:dateStart AND DATE(date)<=:dateEnd", array(":campaignid"=>$this->id, ":dateStart"=>$dateStart, ":dateEnd"=>$dateEnd));
		return $query;
	}
	
	public function countConv($dateStart=NULL, $dateEnd=NULL)
	{
		$dateStart = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'today' ;
		$dateEnd   = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'today';
		if(!$dateStart)$dateStart = 'today';
		if(!$dateEnd)$dateEnd = 'today';
		$dateStart = date('Y-m-d', strtotime($dateStart));
		$dateEnd = date('Y-m-d', strtotime($dateEnd));
		$query = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)>=:dateStart AND DATE(date)<=:dateEnd", array(":campaignid"=>$this->id, ":dateStart"=>$dateStart, ":dateEnd"=>$dateEnd));
		return $query;
	}
	
	public function excel($startDate=NULL, $endDate=NULL)
	{
		$criteria=new CDbCriteria;

		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
	    }

		//$criteria->with = array( 'campaigns', 'networks' );

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	//Acción para obtener un array con rango de fechas
	function arrayRangoFechas($start, $end) {
	    $range = array();

	    if (is_string($start) === true) $start = strtotime($start);
	    if (is_string($end) === true ) $end = strtotime($end);

	    if ($start > $end) return createDateRangeArray($end, $start);

	    do {
	        $range[] = date('Y-m-d', $start);
	        $start = strtotime("+ 1 day", $start);
	    } while($start <= $end);

	    return $range;
	}
	function stringRangoFechas(array $fechas)
	{
		$stringFecha='';
		foreach ($fechas as $fecha) 
		{
			$stringFecha=$stringFecha."'".$fecha."', ";
		}
		return $stringFecha;
	}
	function clicksPorRango(array $fechas)
	{
		$strFechas='';
		foreach ($fechas as $fecha) {		
			$strFechas+="'".Campaigns::countClicks($fecha,$fecha)."', ";
		}
		return $strFechas;
	}

	function arrayCharts($dateStart, $dateEnd) {
	    $range = array();

	    if (is_string($dateStart) === true) $dateStart = strtotime($dateStart);
	    if (is_string($dateEnd) === true ) $dateEnd = strtotime($dateEnd);

	    if ($dateStart > $dateEnd) $range=createDateRangeArray($dateEnd, $dateStart);

	    do {
	        $range[] = date('Y-m-d', $dateStart);
	        $dateStart = strtotime("+ 1 day", $dateStart);
	    } while($dateStart <= $dateEnd);
	    $charts=array();
	    foreach ($range as $date) {
	    	$charts[]=array(
	    		'date'=>$date,
	    		'clicks'=>$this->countClicks($date,$date),
	    		'convs'=>$this->countConv($date,$date),
	    		);
	    	echo self::countClicks('2014-09-15','2014-09-17').'<br>';
	    }
	    return $charts;
	    
	}
	public function getTotals($startDate=NULL, $endDate=NULL,$accountManager=NULL,$opportunitie=null,$networks=null)
	{
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate   = 'today';
		$startDate = date('Y-m-d', strtotime($startDate));
		$endDate = date('Y-m-d', strtotime($endDate));
		$dataTops=array();
		$conversions=array();
		$clics=array();
		$dates=array();
		foreach (Utilities::dateRange($startDate,$endDate) as $date) {
			$dataTops[$date]['conversions']=0;
			$dataTops[$date]['clics']=0;
		}

		$criteria=new CDbCriteria;
		//$criteria->with('opportunities','networks');
		$criteria->with = array('opportunities','opportunities.accountManager','networks');
		if($accountManager!=null)$criteria->compare('opportunities.account_manager_id',$accountManager);
		if($opportunitie!=null)$criteria->compare('opportunities.id',$opportunitie);
		if($networks!=null)$criteria->compare('t.networks_id',$networks);
		$campaigns=self::model()->findAll($criteria);
		foreach ($campaigns as $campaign) 
		{
			foreach (Utilities::dateRange($startDate,$endDate) as $date) 
			{
				$dataTops[$date]['clics']+=intval(ClicksLog::model()->count("DATE(date)=':date' AND campaigns_id=:campaign", array(":campaign"=>$campaign->id,":date"=>$date)));
				$dataTops[$date]['conversions']+=intval(ConvLog::model()->count("DATE(date)=':date' AND campaign_id=:campaign", array(":campaign"=>$campaign->id,":date"=>$date)));
			}
		}

		foreach ($dataTops as $date => $data) {
			$conversions[]=$data['conversions'];
			$clics[]=$data['clics'];
			$dates[]=$date;
		}
		$result=array('conversions' => $conversions, 'clics' => $clics, 'dates' => $dates);
		
		return $result;
	}
	public function totalsTraffic($startDate=NULL, $endDate=NULL, $campaign=NULL)
	{
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate   = 'today';
		$startDate = date('Y-m-d', strtotime($startDate));
		$endDate = date('Y-m-d', strtotime($endDate));
		$dataTops=array();
		$conversions=array();
		$clics=array();
		$dates=array();

		foreach (Utilities::dateRange($startDate,$endDate) as $date) {
			$dataTops[$date]['conversions']=0;
			$dataTops[$date]['clics']=0;
		}
		$criteria=new CDbCriteria;
		$criteria->select='count(*) as clics,DATE(date) as date';
		$criteria->addCondition("DATE(date)>="."'".$startDate."'");
		$criteria->addCondition("DATE(date)<="."'".$endDate."'");
		if($campaign!=NULL)$criteria->addCondition("campaigns_id=".$campaign);
		$criteria->group='DATE(date)';
		$criteria->order='DATE(date) ASC';
		$r         = ClicksLog::model()->findAll( $criteria );
		foreach ($r as $value) {			
			$dataTops[date('Y-m-d', strtotime($value->date))]['clics']=intval($value->clics);
			if($campaign==NULL)$dataTops[date('Y-m-d', strtotime($value->date))]['conversions']=intval(ConvLog::model()->count("DATE(date)=:date", array(":date"=>date('Y-m-d', strtotime($value->date)))));
			if($campaign!=NULL)$dataTops[date('Y-m-d', strtotime($value->date))]['conversions']=intval(ConvLog::model()->count("DATE(date)=:date AND campaign_id=:campaign", array(":campaign"=>$campaign,":date"=>date('Y-m-d', strtotime($value->date)))));
		}
		
		foreach ($dataTops as $date => $data) {
			$conversions[]=$data['conversions'];
			$clics[]=$data['clics'];
			$dates[]=$date;
		}
		$result=array('conversions' => $conversions, 'clics' => $clics, 'dates' => $dates);
		
		return $result;
	}

	public function searchTraffic($accountManager=NULL,$opportunitie=null,$networks=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array('opportunities', 'opportunities.ios', 'opportunities.ios.advertisers', 'opportunities.country', 'vectors', 'networks');
		$criteria->compare('t.name',$this->name, true);
		$criteria->compare('advertisers.name',$this->advertisers_name, true);
		$criteria->compare('opportunities.rate',$this->opportunities_rate, true);
		$criteria->compare('opportunities.carrier',$this->opportunities_carrier, true);
		if($accountManager!=null)$criteria->compare('opportunities.account_manager_id',$accountManager);
		if($opportunitie!=null)$criteria->compare('opportunities.id',$opportunitie);
		if($networks!=null)$criteria->compare('t.networks_id',$networks);
		return new CActiveDataProvider($this, array(
			'criteria' =>$criteria,
			// Setting 'sort' property in order to add 
			// a sort tool in the related collumns
            'pagination'=>array(
                'pageSize'=>50,
            ),
			'sort'     =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'campaigns_name'=>array(
						'asc'  =>'campaigns.name',
						'desc' =>'campaigns.name DESC',
		            ),
		            'advertisers_name'=>array(
						'asc'  =>'advertisers.name',
						'desc' =>'advertisers.name DESC',
		            ),
		            'ios_name'=>array(
						'asc'  =>'ios.name',
						'desc' =>'ios.name DESC',
		            ),
		            // Adding all the other default attributes
		            '*',
		        ),
		    ),
		));
	}

	public function isValidId($id)
	{
		$isValid = $this->find('id=:id', array(':id' => $id));
		return $isValid ? true : false;
	}

	public function getRateUSD()
	{
		$opportunitie=$this->opportunities_id;
		$rate = Opportunities::model()->findByPk($opportunitie)->rate;
		$io_currency = Ios::model()->findByPk(Opportunities::model()->findByPk($opportunitie)->ios_id)->currency;

		if ($io_currency == 'USD') // if currency is USD dont apply type change
			return $rate;

		$currency = Currency::model()->findByDate($this->date);
		return $currency ? number_format($rate / $currency[$io_currency], 2) : 'Currency ERROR!';
	}
}
