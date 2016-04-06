<?php 

/**
 * This is the model class for table "daily_report".
 *
 * The followings are the available columns in table 'daily_report':
 * @property integer $id
 * @property integer $campaigns_id
 * @property integer $providers_id
 * @property integer $imp
 * @property integer $imp_adv
 * @property integer $clics
 * @property integer $conv_api
 * @property integer $conv_adv
 * @property integer $spend
 * @property integer $revenue
 * @property string $date
 * @property integer $is_from_api
 * @property string $profit
 * @property string $profit_percent
 * @property string $click_through_rate
 * @property string $conversion_rate
 * @property string $eCPM
 * @property string $eCPC
 * @property string $eCPA
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Campaigns $campaigns
 * @property Vectors $vectors
 * @property Providers $providers
 * @property DailyVectors[] $dailyVectors
 * @property MultiRate[] $multiRates
 */
class DailyReport extends CActiveRecord
{

	public $providers_name;
	public $advertisers_name;
	public $country_name;
	// public $providers_hasApi;
	public $account_manager;
	public $campaign_name;
	public $conversions;
	public $convrate;
	public $rate;
	public $mr;
	public $currency;
	public $percent_off;
	public $off;
	public $total;

	public $io_id;
	public $opp_id;
	public $model;
	public $carrier;
	public $entity;
	public $commercial_name;
	public $product;
	public $country;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daily_report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaigns_id, imp, clics, conv_api, spend, date', 'required'),
			array('campaigns_id, providers_id, imp, imp_adv, clics, conv_api, conv_adv, is_from_api', 'numerical', 'integerOnly'=>true),
			array('spend, revenue, profit, profit_percent, click_through_rate, conversion_rate, eCPM, eCPC, eCPA', 'length', 'max'=>11),
			array('comment', 'length', 'max'=>255),
			array('date', 'date',  'format'=>'yyyy-M-d'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaigns_id, providers_id, providers_name, campaign_name, account_manager, rate, imp, imp_adv, clics, conv_api, conv_adv, spend, revenue, date, is_from_api, profit, profit_percent, click_through_rate, conversion_rate, eCPM, eCPC, eCPA, comment, product, carrier, country', 'safe', 'on'=>'search'),
			// array('imp, clics, conv_api, revenue, rate', 'safe', 'on'=>'searchAdvertisers'),
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
			'providers'    => array(self::BELONGS_TO, 'Providers', 'providers_id'),
			'campaigns'    => array(self::BELONGS_TO, 'Campaigns', 'campaigns_id'),
			'multiRates'   => array(self::HAS_MANY, 'MultiRate', 'daily_report_id'),
			'dailyVectors' => array(self::HAS_MANY, 'DailyVectors', 'daily_report_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                 => 'ID',
			'campaigns_id'       => 'Campaigns',
			'providers_id'       => 'Providers',
			'imp'                => 'Imp.',
			'imp_adv'            => 'Imp. Adv.',
			'clics'              => 'Clicks',
			'conv_api'           => 'Conv.',
			'conv_adv'           => 'Conv. Adv.',
			'spend'              => 'Spend',
			'revenue'            => 'Revenue',
			'date'               => 'Date',
			'is_from_api'        => 'Is From Api',
			'providers_name'     => 'Traffic Source',
			'advertisers_name'   => 'Advertiser',
			'country_name'       => 'Country',
			'account_manager'    => 'Account Manager',
			'campaign_name'      => 'Campaign',
			'profit'             => 'Profit',
			'profit_percent'     => 'Profit&nbsp;%',
			'rate'               => 'Rate',
			'click_through_rate' => 'CTR&nbsp;%',
			'conversion_rate'    => 'CR&nbsp;%',
			'eCPM'               => 'eCPM',
			'eCPC'               => 'eCPC',
			'eCPA'               => 'eCPA',
			'comment'            => 'Com.',
			'mr'                 => '',
			'currency'           => 'Currency',
			'percent_off'        => 'Percent Off',
			'off'                => 'Off',
			'total'              => 'Total',
			'product'            => 'Name',
			'country'            => 'Country',
			'carrier'            => 'Carrier',
		);
	}

	/**
	 * Returns a list of daily rows with the amount of impresions, clicks and conversions
	 * for one click opportunities campaigns
	 * @param  [type] $hash      [description] md5(opportunities_id + ios_id)
	 * @param  [type] $startDate [description] query start date
	 * @param  [type] $endDate   [description] query end date
	 * @return [type] Array      [description] 
	 */
	public function trafficReport($hash, $startDate, $endDate)
	{	
		// get opportunities id from hash
		$oppModel   = Opportunities::model()->find('md5(id*id)="'.$hash.'"');
		$oppID      = isset($oppModel->id) ? $oppModel->id : 0;

		// make a criteria from opportunities_id
		$criteria = new CDbCriteria;
		$criteria->select = array(
						't.date as date', 
						'sum(CASE 
				           WHEN t.imp > 0 
				           THEN t.imp 
				           ELSE ROUND(clics * 100 / 1.5) 
				        END) as imp',
						/*
						'sum(CASE 
				           WHEN t.imp > 0 
				           THEN 0 
				           ELSE ROUND(clics * 100 / 1.5) 
				        END) as conv_adv',
						'sum(t.imp) as imp_adv',
						 */
						'sum(t.clics) as clics', 
						'sum(t.conv_api) as conv_api', 
						); 
		$criteria->addCondition('date(t.date) BETWEEN "'.$startDate.'" AND "'.$endDate.'"');
		$criteria->with = array('campaigns');
		$criteria->compare('campaigns.opportunities_id', $oppID);
		$criteria->group = 't.date, campaigns.url';
		$criteria->order = 't.date asc, campaigns.url asc';

		return $this::model()->findAll($criteria);
	}

	/**
	 * [excel description]
	 * @param  [type]  $startDate      [description]
	 * @param  [type]  $endDate        [description]
	 * @param  [type]  $accountManager [description]
	 * @param  [type]  $opportunities  [description]
	 * @param  [type]  $networks       [description]
	 * @param  integer $sum            [description]
	 * @param  [type]  $adv_categories [description]
	 * @return [type]                  [description]
	 */
	public function excel($startDate=NULL, $endDate=NULL, $accountManager=NULL,$opportunities=null,$providers=null,$sum=0,$adv_categories=null)
	{
		$criteria=new CDbCriteria;
		//$criteria->compare('t.id',$this->id);
		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
	    }
	    if ( $providers != NULL) {
			if(is_array($providers))
			{
				$query="(";
				$i=0;
				foreach ($providers as $prov) {	
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
				$criteria->compare('providers.id',$providers);
			}
		}
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
		if ( $opportunities != NULL) {
			if(is_array($opportunities))
			{
				$query="(";
				$i=0;
				foreach ($opportunities as $opp) {	
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
				$criteria->compare('opportunities.id',$opportunities);
			}
		}
		if ( $adv_categories != NULL) {
			if(is_array($adv_categories))
			{
				$query="(";
				$i=0;
				foreach ($adv_categories as $cat) {	
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
				$criteria->compare('advertisers.cat',$adv_categories);
			}
		}
		//sumas
		if($sum==1){
			$criteria->group  = 'campaigns_id';
			$criteria->select = array(
				'*', 
				'sum(imp) as imp',
				'sum(imp_adv) as imp_adv',
				'sum(clics) as clics',
				'sum(conv_api) as conv_api',
				'sum(conv_adv) as conv_adv',
				'sum(revenue) as revenue',
				'sum(spend) as spend',
				'sum(profit) as profit',
				'round( avg(profit_percent), 2 ) as profit_percent',
				'round( avg(click_through_rate), 2 ) as click_through_rate',
				'round( avg(conversion_rate), 2 ) as conversion_rate',
				'round( avg(eCPM), 2 ) as eCPM',
				'round( avg(eCPC), 2 ) as eCPC',
				'round( avg(eCPA), 2 ) as eCPA'
				);
		}

		//$criteria->with = array( 'campaigns', 'providers' );

		$criteria->with = array( 
				'providers', 
				'providers.affiliates', 
				'campaigns', 
				'campaigns.opportunities', 
				'campaigns.opportunities.regions',
				'campaigns.opportunities.regions.financeEntities',
				'campaigns.opportunities.regions.financeEntities.advertisers' ,
				'campaigns.opportunities.accountManager',
				'campaigns.opportunities.regions.country' 
			);
		$criteria->compare('providers.name',$this->providers_name, true);
		// if ( $providers->isNetwork() )
		// 	$criteria->compare('providers.networks.has_api',$this->providers_hasApi, true);
		$criteria->compare('accountManager.name',$this->account_manager, true);
		$criteria->compare('campaigns.id',$this->campaign_name, true);
		
		$roles = array_keys(Yii::app()->authManager->getRoles(Yii::app()->user->id));
		if ( in_array('commercial', $roles, true) )
			FilterManager::model()->addUserFilter($criteria, 'daily.commercial');
		else if (in_array('affiliates_manager', $roles, true))
			$criteria->addCondition('affiliates.providers_id IS NOT NULL');
		else
			FilterManager::model()->addUserFilter($criteria, 'daily');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	//graph
	//graph
	//graph

	public function advertiserGetTotals($advertiser=null, $startDate=NULL, $endDate=NULL){
		return null;
	}


	/**
	 * [getTotals description]
	 * @param  [type] $startDate      [description]
	 * @param  [type] $endDate        [description]
	 * @param  [type] $accountManager [description]
	 * @param  [type] $opportunities  [description]
	 * @param  [type] $networks       [description]
	 * @param  [type] $adv_categories [description]
	 * @return [type]                 [description]
	 */
	public function getTotals($startDate=null, $endDate=null,$accountManager=NULL,$opportunities=null,$providers=null,$adv_categories=null) {
			
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate     = 'today';
		$startDate                 = date('Y-m-d', strtotime($startDate));
		$endDate                   = date('Y-m-d', strtotime($endDate));
		$dataTops                  =array();
		$spends                    =array();
		$revenues                  =array();
		$profits                   =array();
		$conversions               =array();
		$impressions               =array();
		$clics                     =array();
		$dates                     =array();

		foreach (Utilities::dateRange($startDate,$endDate) as $date) {
			$dataTops[$date]['spends']      =0;
			$dataTops[$date]['revenues']    =0;
			$dataTops[$date]['profits']     =0;
			$dataTops[$date]['conversions'] =0;
			$dataTops[$date]['impressions'] =0;
			$dataTops[$date]['clics']       =0;
		}
		$criteria=new CDbCriteria;
		$criteria->addCondition("DATE(date)>="."'".$startDate."'");
		$criteria->addCondition("DATE(date)<="."'".$endDate."'");
		$criteria->with = array( 
				'providers', 
				'campaigns', 
				'campaigns.opportunities',
				'campaigns.opportunities.accountManager', 
				'campaigns.opportunities.regions', 
				'campaigns.opportunities.regions.country', 
				'campaigns.opportunities.regions.financeEntities', 
				'campaigns.opportunities.regions.financeEntities.advertisers', 
				'campaigns.opportunities.carriers' 
			);
		if ( $providers != NULL) {
			if(is_array($providers))
			{
				$query="(";
				$i=0;
				foreach ($providers as $prov) {	
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
				$criteria->compare('providers.id',$providers);
			}
		}
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
		if ( $opportunities != NULL) {
			if(is_array($opportunities))
			{
				$query="(";
				$i=0;
				foreach ($opportunities as $opp) {	
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
				$criteria->compare('opportunities.id',$opportunities);
			}
		}
		if ( $adv_categories != NULL) {
			if(is_array($adv_categories))
			{
				$query="(";
				$i=0;
				foreach ($adv_categories as $cat) {	
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
				$criteria->compare('advertisers.cat',$adv_categories);
			}
		}
		$r         = DailyReport::model()->findAll( $criteria );
		foreach ($r as $value) {
			$dataTops[date('Y-m-d', strtotime($value->date))]['spends']      +=doubleval($value->getSpendUSD());	
			$dataTops[date('Y-m-d', strtotime($value->date))]['revenues']    +=doubleval($value->getRevenueUSD());
			$dataTops[date('Y-m-d', strtotime($value->date))]['profits']     +=doubleval($value->profit);
			$dataTops[date('Y-m-d', strtotime($value->date))]['conversions'] +=$value->conv_adv ? intval($value->conv_adv) : intval($value->conv_api);
			$dataTops[date('Y-m-d', strtotime($value->date))]['impressions'] +=$value->imp;
			$dataTops[date('Y-m-d', strtotime($value->date))]['clics']       +=$value->clics;
		}
		
		foreach ($dataTops as $date => $data) {
			$spends[]      =$data['spends'];
			$revenues[]    =$data['revenues'];
			$profits[]     =$data['profits'];
			$impressions[] =$data['impressions'];
			$conversions[] =$data['conversions'];
			$clics[]       =$data['clics'];
			$dates[]       =$date;
		}
		$result=array('spends' => $spends, 'revenues' => $revenues, 'profits' => $profits, 'impressions' => $impressions, 'conversions' => $conversions, 'clics' => $clics, 'dates' => $dates);
		
		return $result;
	}

	/**
	 * [getDailyTotals description]
	 * @param  [type] $startDate      [description]
	 * @param  [type] $endDate        [description]
	 * @param  [type] $accountManager [description]
	 * @param  [type] $opportunities  [description]
	 * @param  [type] $networks       [description]
	 * @param  [type] $adv_categories [description]
	 * @return [type]                 [description]
	 */
	public function getDailyTotals($startDate=null, $endDate=null, $accountManager=NULL,$opportunities=null,$providers=null,$adv_categories=null) {
			
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate     = 'today';
		$startDate  = date('Y-m-d', strtotime($startDate));
		$endDate    = date('Y-m-d', strtotime($endDate));
		$imp        = 0;
		$imp_adv    = 0;
		$clics      = 0;
		$conv_s2s   = 0;
		$conv_adv   = 0;
		$spend      = 0;
		$revenue    = 0;
		$profit     = 0;
		$ctr        = 0;
		$cr         = 0;
		$profitperc = 0;
		$ecpm       = 0;
		$ecpc       = 0;
		$ecpa       = 0;

		$criteria = new CDbCriteria;
		$criteria->addCondition("DATE(date)>="."'".$startDate."'");
		$criteria->addCondition("DATE(date)<="."'".$endDate."'");
		$criteria->with = array( 
				'providers', 
				'campaigns', 
				'campaigns.opportunities',
				'campaigns.opportunities.accountManager', 
				'campaigns.opportunities.regions', 
				'campaigns.opportunities.regions.country', 
				'campaigns.opportunities.regions.financeEntities', 
				'campaigns.opportunities.regions.financeEntities.advertisers', 
				'campaigns.opportunities.carriers' 
			);
		
		if ( $providers != NULL) {
			if(is_array($providers))
			{
				$query="(";
				$i=0;
				foreach ($providers as $prov) {	
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
				$criteria->compare('providers.id',$providers);
			}
		}
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
		if ( $opportunities != NULL) {
			if(is_array($opportunities))
			{
				$query="(";
				$i=0;
				foreach ($opportunities as $opp) {	
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
				$criteria->compare('opportunities.id',$opportunities);
			}
		}
		if ( $adv_categories != NULL) {
			if(is_array($adv_categories))
			{
				$query="(";
				$i=0;
				foreach ($adv_categories as $cat) {	
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
				$criteria->compare('advertisers.cat',$adv_categories);
			}
		}
		$r = DailyReport::model()->findAll( $criteria );
		foreach ($r as $value) {
			$imp      += $value->imp;
			$imp_adv  += $value->imp_adv;
			$clics    += $value->clics;
			$conv_s2s += $value->conv_api;
			$conv_adv += $value->conv_adv;
			$spend    += doubleval($value->getSpendUSD());	
			$revenue  += doubleval($value->getRevenueUSD());
			$profit   += $value->profit;
		}

		$impt       = $imp_adv == 0 ? $imp : $imp_adv;
		$convt      = $conv_adv == 0 ? $conv_s2s : $conv_adv;
		$ctr        = $impt == 0 ? 0 : number_format($clics / $impt, 2);
		$cr         =$clics == 0 ? 0 : number_format( $convt / $clics, 2 );
		$profitperc =$revenue == 0 ? 0 : number_format($profit / $revenue, 2);
		$ecpm       =$impt == 0 ? 0 : number_format($spend * 1000 / $impt, 2);
		$ecpc       =$clics == 0 ? 0 : number_format($spend / $clics, 2);
		$ecpa       =$convt == 0 ? 0 : number_format($spend / $convt, 2);

		$result=array(
			'imp'		=> $imp,
			'imp_adv'	=> $imp_adv,
			'clics'		=> $clics,
			'conv_s2s'	=> $conv_s2s,
			'conv_adv'	=> $conv_adv,
			'spend'		=> $spend,
			'revenue'	=> $revenue,
			'profit'	=> $profit,
			'ctr'		=> $ctr,
			'cr'		=> $cr,
			'profitperc'=> $profitperc,
			'ecpm'		=> $ecpm,
			'ecpc'		=> $ecpc,
			'ecpa'		=> $ecpa,
		);
		
		return $result;
	}

	/**
	 * [getTops description]
	 * @param  [type] $startDate [description]
	 * @param  [type] $endDate   [description]
	 * @param  [type] $order     [description]
	 * @return [type]            [description]
	 */
	public function getTops($startDate=null, $endDate=null,$order) {			
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate     = 'today';
		$startDate    = date('Y-m-d', strtotime($startDate));
		$endDate      = date('Y-m-d', strtotime($endDate));
		
		$dataTops     =array();
		$totals       =array();
		$campaigns_id =array();

		$criteria=new CDbCriteria;
		$criteria->condition = "date(t.date) BETWEEN '" . date('Y-m-d', strtotime($startDate)) . "' AND '" . date('Y-m-d', strtotime($endDate)) . "'";
		switch ($order) {
			case 'spend':
				$select="campaigns_id, ";
				$orderby = "sum(t.spend / 
						(
							SELECT (
									CASE (
										SELECT providers.currency
										FROM providers
										WHERE t.providers_id=providers.id
									) WHEN 'USD' THEN (
										SELECT 1
									)"; 
			$currencyModel = new Currency;
			$currency = $currencyModel->attributes;
			array_pop($currency); // remove id
			array_pop($currency); // remove date
			foreach ($currency as $key => $value) {
				$orderby .= " WHEN '" . $key . "' THEN ( SELECT " . $key . ")";
			}

			$orderby .= 		" END
								)
							FROM currency c 
							WHERE date(c.date)<=t.date
							ORDER BY c.date DESC
							LIMIT 1
						)
					)";
			$select.=$orderby." as total";
			$criteria->select    = $select;
			//$criteria->alias     = "d";
			$criteria->group     = "t.campaigns_id";
			$criteria->order     = $orderby." DESC";
				break;

			case 'profit':
				$criteria->select = array(
							'campaigns_id', 
							'sum(profit) as total'
							);
				$criteria->order='SUM(profit) DESC';
				$criteria->group='campaigns_id,providers_id';
				break;

			case 'conversions':
				$criteria->select='campaigns_id, case SUM(conv_adv) when 0 then SUM(conv_api) else SUM(conv_adv) end as total';
				$criteria->order='case SUM(conv_adv) when 0 then SUM(conv_api) else SUM(conv_adv) end DESC';
		$criteria->group='campaigns_id';
				break;

			case 'convrate':				
				$criteria->select='campaigns_id, ROUND(((case SUM(conv_adv) when 0 then SUM(conv_api) else SUM(conv_adv) end/SUM(clics))*100)) as total';
				$criteria->order='ROUND(((case SUM(conv_adv) when 0 then SUM(conv_api) else SUM(conv_adv) end/SUM(clics))*100)) DESC';
		$criteria->group='campaigns_id';
				break;
		}

		$criteria->limit=6;

		$r         = DailyReport::model()->findAll( $criteria );
		foreach ($r as $value) {
			$totals[]       = doubleval($value->total);	
			$campaigns_id[] = $value->campaigns->id;		
		}
		
		$result=array(
			'totals' => $totals, 
			'ids'    => $campaigns_id,
			'types'  => 'campaign',
			);
		$dataTops['array']        = $result;
		$dataTops['dataProvider'] = new CActiveDataProvider($this, array(
				'criteria'   =>$criteria,
				'pagination' =>false,
				'sort'=>array(
					'attributes'   =>array(
			            // Adding all the other default attributes
			            '*',
			        ),
			    ),

			));
		return $dataTops;
	}
	
	/**
	 * Search stats for advertisers report page, grouped by opportunities
	 * @param  [type]  $advertiser [description]
	 * @param  [type]  $startDate  [description]
	 * @param  [type]  $endDate    [description]
	 * @param  integer $sum        [description]
	 * @param  boolean $totals     [description]
	 * @return [type]              [description]
	 */
	public function advertiserSearch($advertiser=null, $startDate=NULL, $endDate=NULL, $sum=0, $totals=false){

		$criteria = new CDbCriteria;
		// Related search criteria items added (use only table.columnName)
		$criteria->with = array( 
			'campaigns.opportunities',
			'campaigns.opportunities.carriers',
			'campaigns.opportunities.regions',
			'campaigns.opportunities.regions.country',
			'campaigns.opportunities.regions.financeEntities.advertisers', 
		);
		
		$criteria->compare('opportunities.product', $this->product);
		$criteria->compare('opportunities.rate', $this->rate);
		$criteria->compare('regions.region', $this->country);
		$criteria->compare('carriers.mobile_brand', $this->carrier);
		$criteria->compare('advertisers.id',$advertiser);
		
		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
		}

		$select = array(
			'SUM(IF(imp_adv IS NULL,imp,imp_adv)) AS imp',
			'SUM(clics) AS clics',
			'SUM(IF(conv_adv IS NULL,conv_api,conv_adv)) AS conv_api',
			'SUM(revenue) AS revenue'
			);
		
		if(!$totals){

			$criteria->group = '';
			if(!$sum) $select[] = 'date';
			if(!$sum) $criteria->group  .= 'date(t.date), ';
			
			$criteria->group .= 'opportunities.id';
			
		}
				
		$criteria->select = $select;

		if($totals){
			return Self::model()->find($criteria);
		}else{
			return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			    'pagination'=>array(
			        'pageSize'=>50,
			    ),
				'sort'=>array(
					'defaultOrder' => 't.id DESC',
					'attributes'   => array(
						'product' => array(
							'asc'  =>'opportunities.product',
							'desc' =>'opportunities.product DESC',
			            ),
						'country' => array(
							'asc'  =>'country.name',
							'desc' =>'country.name DESC',
			            ),
						'carrier' => array(
							'asc'  =>'carriers.mobile_brand',
							'desc' =>'carriers.mobile_brand DESC',
			            ),
						'rate' => array(
							'asc'  =>'opportunities.rate',
							'desc' =>'opportunities.rate DESC',
			            ),
						'imp' => array(
							'asc'  =>'SUM(imp)',
							'desc' =>'SUM(imp) DESC',
			            ),
						'clics' => array(
							'asc'  =>'SUM(clics)',
							'desc' =>'SUM(clics) DESC',
			            ),
						'conv_api' => array(
							'asc'  =>'SUM(conv_api)',
							'desc' =>'SUM(conv_api) DESC',
			            ),
						'revenue' => array(
							'asc'  =>'SUM(revenue)',
							'desc' =>'SUM(revenue) DESC',
			            ),
			            // Adding all the other default attributes
			            '*',
					),
			    ),
			));
		}
	}

	/**
	 * DEPRECATED
	 * @param  [type] $advertiser [description]
	 * @param  [type] $startDate  [description]
	 * @param  [type] $endDate    [description]
	 * @return [type]             [description]
	 */
	public function advertiserSearchTotals($advertiser=null, $startDate=NULL, $endDate=NULL){
		$criteria=new CDbCriteria;
		// Related search criteria items added (use only table.columnName)
		$criteria->with = array( 
			'campaigns.opportunities',
			'campaigns.opportunities.regions.country',
			'campaigns.opportunities.regions.financeEntities.advertisers', 
		);
		
		$criteria->compare('advertisers.id',$advertiser);
		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
		}

		$totals             =array();
		$totals['imp']      =0;
		$totals['imp_adv']  =0;
		$totals['clics']    =0;
		$totals['conv_api'] =0;
		$totals['conv_adv'] =0;
		$totals['conv']     =0;
		$totals['revenue']  =0;
		$totals['spend']    =0;
		$totals['profit']   =0;

		if($dailys=Self::model()->findAll($criteria))
		{			
			foreach ($dailys as $data) {
				$totals['imp']      +=$data->imp;
				$totals['imp_adv']  +=$data->imp_adv;
				$totals['clics']    +=$data->clics;
				$totals['conv_api'] +=$data->conv_api;
				$totals['conv_adv'] +=$data->conv_adv;
				$totals['conv']     +=$data->getConv();
				$totals['revenue']  +=$data->getRevenueUSD();
				$totals['spend']    +=$data->getSpendUSD();
				$totals['profit']   +=$data->getProfit();
			}
		}
		return $totals;
	}

	/**
	 * [search description]
	 * @param  [type]  $startDate      [description]
	 * @param  [type]  $endDate        [description]
	 * @param  [type]  $accountManager [description]
	 * @param  [type]  $opportunities  [description]
	 * @param  [type]  $providers      [description]
	 * @param  integer $sum            [description]
	 * @param  [type]  $adv_categories [description]
	 * @return [type]                  [description]
	 */
	public function search($startDate=NULL, $endDate=NULL, $accountManager=NULL, $opportunities=null, $providers=null, $sum=0, $adv_categories=null, $group=array(), $sums=array(), $advertisers=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$sumArray=array(
					// Adding custom sort attributes
		            'providers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            'advertisers_name'=>array(
						'asc'  =>'advertisers.name',
						'desc' =>'advertisers.name DESC',
		            ),
		            'account_manager'=>array(
						'asc'  =>'accountManager.name',
						'desc' =>'accountManager.name DESC',
		            ),
		            'campaign_name'=>array(
						'asc'  =>'campaigns.id',
						'desc' =>'campaigns.id DESC',
		            ),
		            'rate'=>array(
						'asc'  =>'opportunities.rate',
						'desc' =>'opportunities.rate DESC',
		            ),
		            // Adding all the other default attributes
		            '*',
		        );
	
		$groupBy = array();
		$orderBy = array();

		if($group['Date'] == 1) {
			$groupBy[] = 'DATE(t.date)';
			$orderBy[] = 'DATE(t.date) DESC';
		}
		if($group['TrafficSource'] == 1) {
			$groupBy[] = 't.providers_id';
			$orderBy[] = 'providers.name ASC';
		}
		if($group['Advertiser'] == 1) {
			$groupBy[] = 'advertisers.id';
			$orderBy[] = 'advertisers.name ASC';
		}
		if($group['Country'] == 1) {
			$groupBy[] = 'country.id_location';
			$orderBy[] = 'country.name ASC';
		}
		if($group['Campaign'] == 1) {
			$groupBy[] = 't.campaigns_id';
			$orderBy[] = 't.campaigns_id ASC';
		}

		$criteria->group = join($groupBy,',');
		$criteria->select = array(
			'*', 
			'advertisers.name AS advertisers_name',
			'country.name AS country_name',
			'sum(imp) as imp',
			'sum(imp_adv) as imp_adv',
			'sum(clics) as clics',
			'sum(conv_api) as conv_api',
			'sum(conv_adv) as conv_adv',
			'sum(revenue) as revenue',
			'sum(spend) as spend',
			'sum(profit) as profit',
			// 'revenue as profit_percent',
			'round( avg(click_through_rate), 2 ) as click_through_rate',
			'round( avg(conversion_rate), 2 ) as conversion_rate',
			'round( avg(eCPM), 2 ) as eCPM',
			'round( avg(eCPC), 2 ) as eCPC',
			'round( avg(eCPA), 2 ) as eCPA'
			);

		// if($sum==1){
		// 	$criteria->group  = 'campaigns_id';
		// 	$criteria->select = array(
		// 		'*', 
		// 		'sum(imp) as imp',
		// 		'sum(imp_adv) as imp_adv',
		// 		'sum(clics) as clics',
		// 		'sum(conv_api) as conv_api',
		// 		'sum(conv_adv) as conv_adv',
		// 		'sum(revenue) as revenue',
		// 		'sum(spend) as spend',
		// 		'sum(profit) as profit',
		// 		// 'revenue as profit_percent',
		// 		'round( avg(click_through_rate), 2 ) as click_through_rate',
		// 		'round( avg(conversion_rate), 2 ) as conversion_rate',
		// 		'round( avg(eCPM), 2 ) as eCPM',
		// 		'round( avg(eCPC), 2 ) as eCPC',
		// 		'round( avg(eCPA), 2 ) as eCPA'
		// 		);

		// 	$sumArray['profit'] = array(
		// 			'asc'  =>'sum(profit)',
		// 			'desc' =>'sum(profit) DESC',
	 //            );
		// 	$sumArray['imp'] = array(
		// 			'asc'  =>'sum(imp)',
		// 			'desc' =>'sum(imp) DESC',
	 //            );
		// 	$sumArray['imp_adv'] = array(
		// 			'asc'  =>'sum(imp_adv)',
		// 			'desc' =>'sum(imp_adv) DESC',
	 //            );
		// 	$sumArray['clics'] = array(
		// 			'asc'  =>'sum(clics)',
		// 			'desc' =>'sum(clics) DESC',
	 //            );
		// 	$sumArray['conv_api'] = array(
		// 			'asc'  =>'sum(conv_api)',
		// 			'desc' =>'sum(conv_api) DESC',
	 //            );
		// 	$sumArray['conv_adv'] = array(
		// 			'asc'  =>'sum(conv_adv)',
		// 			'desc' =>'sum(conv_adv) DESC',
	 //            );
		// 	$sumArray['revenue'] = array(
		// 			'asc'  =>'sum(revenue)',
		// 			'desc' =>'sum(revenue) DESC',
	 //            );
		// 	$sumArray['spend'] = array(
		// 			'asc'  =>'sum(spend)',
		// 			'desc' =>'sum(spend) DESC',
	 //            );
		// 	$sumArray['profit_percent'] = array(
		// 			'asc'  =>'round( avg(profit_percent), 2 )',
		// 			'desc' =>'round( avg(profit_percent), 2 ) DESC',
	 //            );
		// 	$sumArray['click_through_rate'] = array(
		// 			'asc'  =>'round( avg(click_through_rate), 2 )',
		// 			'desc' =>'round( avg(click_through_rate), 2 ) DESC',
	 //            );
		// 	$sumArray['conversion_rate'] = array(
		// 			'asc'  =>'round( avg(conversion_rate), 2 )',
		// 			'desc' =>'round( avg(conversion_rate), 2 ) DESC',
	 //            );
		// 	$sumArray['eCPM'] = array(
		// 			'asc'  =>'round( avg(eCPM), 2 )',
		// 			'desc' =>'round( avg(eCPM), 2 ) DESC',
	 //            );
		// 	$sumArray['eCPC'] = array(
		// 			'asc'  =>'round( avg(eCPC), 2 )',
		// 			'desc' =>'round( avg(eCPC), 2 ) DESC',
	 //            );
		// 	$sumArray['eCPA'] = array(
		// 			'asc'  =>'round( avg(eCPA), 2 )',
		// 			'desc' =>'round( avg(eCPA), 2 ) DESC',
	 //            );
		// }

		//search
		$criteria->compare('t.id',$this->id);
		$criteria->compare('campaigns_id',$this->campaigns_id);
		//if ( $providers == NULL) $criteria->compare('providers_id',$this->providers_id);
		$criteria->compare('opportunities.rate',$this->rate,true);
		$criteria->compare('imp',$this->imp,true);
		$criteria->compare('imp_adv',$this->imp_adv,true);
		$criteria->compare('clics',$this->clics,true);
		$criteria->compare('conv_api',$this->conv_api,true);
		$criteria->compare('conv_adv',$this->conv_adv,true);
		$criteria->compare('spend',$this->spend,true);
		$criteria->compare('revenue',$this->revenue,true);
		$criteria->compare('is_from_api',$this->is_from_api);
		//$criteria->compare('comment',$this->comment);

		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
		}
		
		// Related search criteria items added (use only table.columnName)
		$criteria->with = array( 
			'providers', 
			'providers.affiliates', 
			'campaigns', 
			'campaigns.opportunities',
			'campaigns.opportunities.accountManager', 
			'campaigns.opportunities.regions',
			'campaigns.opportunities.regions.country', 
			'campaigns.opportunities.regions.financeEntities',
			'campaigns.opportunities.regions.financeEntities.advertisers', 
			'campaigns.opportunities.carriers' 
			);
		$criteria->compare('opportunities.rate',$this->rate);
		$criteria->compare('providers.name',$this->providers_name, true);
		// if ($providers->isNetwork())
		// 	$criteria->compare('providers.networks.has_api',$this->providers_hasApi, true);
		//if ( $providers != NULL)$criteria->compare('providers.id',$providers);
		$criteria->compare('accountManager.name',$this->account_manager, true);
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
				foreach ($advertisers as $adv) {	
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
				$criteria->compare('advertisers.id',$advertisers);
			}
		}

		if ( $opportunities != NULL) {
			if(is_array($opportunities))
			{
				$query="(";
				$i=0;
				foreach ($opportunities as $opp) {	
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
				$criteria->compare('opportunities.id',$opportunities);
			}
		}

		if ( $providers != NULL) {
			if(is_array($providers))
			{
				$query="(";
				$i=0;
				foreach ($providers as $prov) {	
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
				$criteria->compare('providers.id',$providers);
			}
		}

		if ( $adv_categories != NULL) {
			if(is_array($adv_categories))
			{
				$query="(";
				$i=0;
				foreach ($adv_categories as $cat) {	
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
				$criteria->compare('advertisers.cat',$adv_categories);
			}
		}

		// if ( $advertiser != NULL ){
		// 	$criteria->addCondition('advertisers.cat="'.$advertiser.'"');
		// }
		// external name
		$tmp = new CDbCriteria;
		$tmp->compare('t.campaigns_id',$this->campaign_name,true);
		$tmp->compare('carriers.mobile_brand',$this->campaign_name,true,'OR');
		$tmp->compare('country.ISO2',$this->campaign_name,true,'OR');
		$tmp->compare('advertisers.prefix',$this->campaign_name,true,'OR');
		$tmp->compare('opportunities.product',$this->campaign_name,true,'OR');
		$tmp->compare('campaigns.name',$this->campaign_name,true,'OR');
		$criteria->mergeWith($tmp);

		$roles = array_keys(Yii::app()->authManager->getRoles(Yii::app()->user->id));
		if ( in_array('commercial', $roles, true) )
			FilterManager::model()->addUserFilter($criteria, 'daily.commercial');
		else if (in_array('affiliates_manager', $roles, true))
			$criteria->addCondition('affiliates.providers_id IS NOT NULL');
		else
			FilterManager::model()->addUserFilter($criteria, 'daily');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			// Setting 'sort' property in order to add 
			// a sort tool in the related collumns
			'pagination'=> KHtml::pagination(),
			'sort'=>array(
				'defaultOrder' => 't.id DESC',
				'attributes'   =>$sumArray,
		    ),
		));
	}


	public function searchTotals($startDate=NULL, $endDate=NULL, $accountManager=NULL, $opportunities=null, $providers=null, $sum=0, $adv_categories=null, $advertisers=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$sumArray=array(
					// Adding custom sort attributes
		            'providers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            'account_manager'=>array(
						'asc'  =>'accountManager.name',
						'desc' =>'accountManager.name DESC',
		            ),
		            'campaign_name'=>array(
						'asc'  =>'campaigns.id',
						'desc' =>'campaigns.id DESC',
		            ),
		            'rate'=>array(
						'asc'  =>'opportunities.rate',
						'desc' =>'opportunities.rate DESC',
		            ),
		            // Adding all the other default attributes
		            '*',
		        );
		if($sum==1){
			$criteria->group  = 'campaigns_id';
			$criteria->select = array(
				'*', 
				'sum(imp) as imp',
				'sum(imp_adv) as imp_adv',
				'sum(clics) as clics',
				'sum(conv_api) as conv_api',
				'sum(conv_adv) as conv_adv',
				'sum(revenue) as revenue',
				'sum(spend) as spend',
				'sum(profit) as profit',
				// 'revenue as profit_percent',
				'round( avg(click_through_rate), 2 ) as click_through_rate',
				'round( avg(conversion_rate), 2 ) as conversion_rate',
				'round( avg(eCPM), 2 ) as eCPM',
				'round( avg(eCPC), 2 ) as eCPC',
				'round( avg(eCPA), 2 ) as eCPA'
				);

			$sumArray['profit'] = array(
					'asc'  =>'sum(profit)',
					'desc' =>'sum(profit) DESC',
	            );
			$sumArray['imp'] = array(
					'asc'  =>'sum(imp)',
					'desc' =>'sum(imp) DESC',
	            );
			$sumArray['imp_adv'] = array(
					'asc'  =>'sum(imp_adv)',
					'desc' =>'sum(imp_adv) DESC',
	            );
			$sumArray['clics'] = array(
					'asc'  =>'sum(clics)',
					'desc' =>'sum(clics) DESC',
	            );
			$sumArray['conv_api'] = array(
					'asc'  =>'sum(conv_api)',
					'desc' =>'sum(conv_api) DESC',
	            );
			$sumArray['conv_adv'] = array(
					'asc'  =>'sum(conv_adv)',
					'desc' =>'sum(conv_adv) DESC',
	            );
			$sumArray['revenue'] = array(
					'asc'  =>'sum(revenue)',
					'desc' =>'sum(revenue) DESC',
	            );
			$sumArray['spend'] = array(
					'asc'  =>'sum(spend)',
					'desc' =>'sum(spend) DESC',
	            );
			$sumArray['profit_percent'] = array(
					'asc'  =>'round( avg(profit_percent), 2 )',
					'desc' =>'round( avg(profit_percent), 2 ) DESC',
	            );
			$sumArray['click_through_rate'] = array(
					'asc'  =>'round( avg(click_through_rate), 2 )',
					'desc' =>'round( avg(click_through_rate), 2 ) DESC',
	            );
			$sumArray['conversion_rate'] = array(
					'asc'  =>'round( avg(conversion_rate), 2 )',
					'desc' =>'round( avg(conversion_rate), 2 ) DESC',
	            );
			$sumArray['eCPM'] = array(
					'asc'  =>'round( avg(eCPM), 2 )',
					'desc' =>'round( avg(eCPM), 2 ) DESC',
	            );
			$sumArray['eCPC'] = array(
					'asc'  =>'round( avg(eCPC), 2 )',
					'desc' =>'round( avg(eCPC), 2 ) DESC',
	            );
			$sumArray['eCPA'] = array(
					'asc'  =>'round( avg(eCPA), 2 )',
					'desc' =>'round( avg(eCPA), 2 ) DESC',
	            );
		}

		//search
		$criteria->compare('t.id',$this->id);
		$criteria->compare('campaigns_id',$this->campaigns_id);
		//if ( $networks == NULL) $criteria->compare('networks_id',$this->networks_id);
		$criteria->compare('imp',$this->imp);
		$criteria->compare('imp_adv',$this->imp_adv);
		$criteria->compare('clics',$this->clics);
		$criteria->compare('conv_api',$this->conv_api);
		$criteria->compare('conv_adv',$this->conv_adv);
		$criteria->compare('spend',$this->spend,true);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('is_from_api',$this->is_from_api);
		//$criteria->compare('comment',$this->comment);

		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
		}
		
		// Related search criteria items added (use only table.columnName)
		$criteria->with = array( 
				'providers',
				'campaigns', 
				'campaigns.opportunities',
				'campaigns.opportunities.accountManager', 
				'campaigns.opportunities.regions', 
				'campaigns.opportunities.regions.country', 
				'campaigns.opportunities.regions.financeEntities', 
				'campaigns.opportunities.regions.financeEntities.advertisers', 
				'campaigns.opportunities.carriers' 
			);
		$criteria->compare('opportunities.rate',$this->rate);
		$criteria->compare('providers.name',$this->providers_name, true);
		// $criteria->compare('providers.has_api',$this->network_hasApi, true);
		//if ( $networks != NULL)$criteria->compare('networks.id',$networks);
		$criteria->compare('accountManager.name',$this->account_manager, true);
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

		if ( $opportunities != NULL) {
			if(is_array($opportunities))
			{
				$query="(";
				$i=0;
				foreach ($opportunities as $opp) {	
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
				$criteria->compare('opportunities.id',$opportunities);
			}
		}

		if ( $providers != NULL) {
			if(is_array($providers))
			{
				$query="(";
				$i=0;
				foreach ($providers as $net) {	
					if($i==0)			
						$query.="providers.id=".$net;
					else
						$query.=" OR providers.id=".$net;
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('providers.id',$providers);
			}
		}

		if ( $adv_categories != NULL) {
			if(is_array($adv_categories))
			{
				$query="(";
				$i=0;
				foreach ($adv_categories as $cat) {	
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
				$criteria->compare('advertisers.cat',$adv_categories);
			}
		}


		if ( $advertisers != NULL) {
			if(is_array($advertisers))
			{
				$query="(";
				$i=0;
				foreach ($advertisers as $adv) {	
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
				$criteria->compare('advertisers.id',$advertisers);
			}
		}
		
		$tmp = new CDbCriteria;
		$tmp->compare('t.campaigns_id',$this->campaign_name,true);
		$tmp->compare('carriers.mobile_brand',$this->campaign_name,true,'OR');
		$tmp->compare('country.ISO2',$this->campaign_name,true,'OR');
		$tmp->compare('advertisers.prefix',$this->campaign_name,true,'OR');
		$tmp->compare('opportunities.product',$this->campaign_name,true,'OR');
		$tmp->compare('campaigns.name',$this->campaign_name,true,'OR');
		$criteria->mergeWith($tmp);
		
		FilterManager::model()->addUserFilter($criteria, 'daily');

		$totals             =array();
		$totals['imp']      =0;
		$totals['imp_adv']  =0;
		$totals['clics']    =0;
		$totals['conv_api'] =0;
		$totals['conv_adv'] =0;
		$totals['conv']     =0;
		$totals['revenue']  =0;
		$totals['spend']    =0;
		$totals['profit']   =0;
		if($dailys=Self::model()->findAll($criteria))
		{			
			foreach ($dailys as $data) {
				$totals['imp']      +=$data->imp;
				$totals['imp_adv']  +=$data->imp_adv;
				$totals['clics']    +=$data->clics;
				$totals['conv_api'] +=$data->conv_api;
				$totals['conv_adv'] +=$data->conv_adv;
				$totals['conv']     +=$data->getConv();
				$totals['revenue']  +=$data->getRevenueUSD();
				$totals['spend']    +=$data->getSpendUSD();
				$totals['profit']   +=$data->getProfit();
			}
		}
		return $totals;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DailyReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Get graphic info for the date specified. $startDate and $endDate must be in DB date format
	 * 
	 * @param  $c_id      campaign id
	 * @param  $prov_id    providers id
	 * @param  $startDate start date
	 * @param  $endDate   end date
	 * @return 
	 */
	public function getGraphicDateRangeInfo($c_id, $prov_id, $startDate, $endDate) {
		
		$condition = 'campaigns_id=:campaignid AND providers_id=:providersid AND DATE(date) >= :startDate and DATE(date) <= :endDate ORDER BY date';
		$params    = array(":campaignid"=>$c_id, ":providersid"=>$prov_id, ":startDate"=>$startDate, ":endDate"=>$endDate);
		$r         = DailyReport::model()->findAll( $condition, $params );

		if ( empty($r) ) {
			return "No results.";
		} 
		foreach (Utilities::dateRange($startDate,$endDate) as $date) {
			$dataTops[$date]['spends']      =0;
			$dataTops[$date]['conversions'] =0;
			$dataTops[$date]['impressions'] =0;
			$dataTops[$date]['clics']       =0;
			$dataTops[$date]['revenues']    =0;
			$dataTops[$date]['profits']     =0;
		}
		foreach ($r as $value) {
			$dataTops[date('Y-m-d', strtotime($value->date))]['spends']      +=doubleval($value->getSpendUSD());	
			$dataTops[date('Y-m-d', strtotime($value->date))]['conversions'] +=$value->conv_adv ? intval($value->conv_adv) : intval($value->conv_api);
			$dataTops[date('Y-m-d', strtotime($value->date))]['impressions'] +=$value->imp;
			$dataTops[date('Y-m-d', strtotime($value->date))]['clics']       +=$value->clics;
			$dataTops[date('Y-m-d', strtotime($value->date))]['revenues']    +=doubleval($value->getRevenueUSD());
			$dataTops[date('Y-m-d', strtotime($value->date))]['profits']     +=$value->profit;
		}
		
		foreach ($dataTops as $date => $data) {
			$spends[]      =$data['spends'];
			$impressions[] =$data['impressions'];
			$conversions[] =$data['conversions'];
			$clics[]       =$data['clics'];
			$profits[]     =$data['profits'];
			$revenues[]    =$data['revenues'];
			$dates[]       =$date;
		}
		$result = array(
			'spend'    => $spends, 
			'conv'    => $conversions, 
			'imp'     => $impressions, 
			'click'   => $clics, 
			'date'    => $dates,
			'revenue' => $revenues,
			'profit'  => $profits,
		);
		return $result;
	}

	/**
	 * [updateRevenue description]
	 * @param  [type] $custom_rate [description]
	 * @return [type]              [description]
	 */
	public function updateRevenue($custom_rate=NULL)
	{
		$c    = Campaigns::model()->findByPk($this->campaigns_id);
		$opp  = Opportunities::model()->findByPk($c->opportunities_id);


		// update revenue for multi carriers
		if ($opp->rate == NULL && $opp->carriers_id == NULL) {
			$multi_rates = MultiRate::model()->findAll(array('order'=>'daily_report_id', 'condition'=>'daily_report_id=:id', 'params'=>array(':id'=>$this->id)));
			$this->revenue = 0;
			foreach ($multi_rates as $multi_rate) {
				$this->revenue += ($multi_rate->conv * $multi_rate->rate);
			}
			return "ok multi";
		}

		// update revenue for single rate
		$rate = $custom_rate == NULL ? $opp->getRate($this->date) : $custom_rate;
		switch ($opp->model_adv) {
			case 'CPM':
				$this->revenue = $this->imp_adv != NULL ? $this->imp_adv * $rate / 1000 : $this->imp * $rate / 1000;
				return "ok rate: ".$opp->getRate($this->date);
				break;
			case 'CPC':
			case 'CPV':
				$this->revenue = $this->clics * $rate;
				break;
			case 'CPA':
			case 'CPL':
			case 'CPI':
				$this->revenue = $this->conv_adv != NULL ? $this->conv_adv * $rate : $this->conv_api * $rate;
				break;
		}
	}

	/**
	 * [updateSpendAffiliates description]
	 * @return [type] [description]
	 */
	public function updateSpendAffiliates($custom_rate=NULL)
	{
		if ($custom_rate == NULL)
			$rateAffiliate = $this->campaigns->external_rate;
		else 
			$rateAffiliate = $custom_rate;

		$this->spend = $this->conv_adv != NULL ? $this->conv_adv * $rateAffiliate : $this->conv_api * $rateAffiliate;	
	}

	/**
	 * [getRevenueUSD description]
	 * @return [type] [description]
	 */
	public function getRevenueUSD()
	{
		$camp         = Campaigns::model()->with('opportunities','opportunities.regions', 'opportunities.regions.financeEntities')->findByPk($this->campaigns_id);
		$financeEntities_currency = $camp->opportunities->regions->financeEntities->currency;
	
		if ($financeEntities_currency == 'USD')	// if currency is USD dont apply type change
			return $this->revenue;

		$currency = Currency::model()->findByDate($this->date);
		return $currency ? round($this->revenue / $currency[$financeEntities_currency], 2) : 'Currency ERROR!';
	}

	/**
	 * [getSpendUSD description]
	 * @return [type] [description]
	 */
	public function getSpendUSD()
	{
		$net_currency = Providers::model()->findByPk($this->providers_id)->currency;

		if ($net_currency == 'USD') // if currency is USD dont apply type change
			return $this->spend;

		$currency = Currency::model()->findByDate($this->date);
		return $currency ? round($this->spend / $currency[$net_currency], 2) : 'Currency ERROR!';
	}

	/**
	 * [getProfit description]
	 * @return [type] [description]
	 */
	public function getProfit()
	{
		return round($this->getRevenueUSD() - $this->getSpendUSD(), 2);
	}

	/**
	 * [getProfits description]
	 * @return [type] [description]
	 */
	public function getProfits()
	{
		return $this->profit;
	}

	/**
	 * [getCtr description]
	 * @return [type] [description]
	 */
	public function getCtr()
	{
		$imp = $this->imp_adv == 0 ? $this->imp : $this->imp_adv;
		$r = $imp == 0 ? 0 : round($this->clics / $imp, 4);
		return $r;
	}

	/**
	 * [getConvRate description]
	 * @return [type] [description]
	 */
	public function getConvRate()
	{
		$conv = $this->conv_adv == 0 ? $this->conv_api : $this->conv_adv;
		$r = $this->clics == 0 ? 0 : round( $conv / $this->clics, 4 );
		return $r;
	}

	/**
	 * [getProfitPerc description]
	 * @return [type] [description]
	 */
	public function getProfitPerc()
	{
		$revenue = $this->getRevenueUSD();
		$r = $revenue == 0 ? 0 : round($this->getProfit() / $revenue, 2);
		return $r;
	}

	/**
	 * [getECPM description]
	 * @return [type] [description]
	 */
	public function getECPM()
	{
		$imp = $this->imp_adv == 0 ? $this->imp : $this->imp_adv;
		$r = $imp == 0 ? 0 : round($this->getSpendUSD() * 1000 / $imp, 2);
		return $r;
	}

	/**
	 * [getECPC description]
	 * @return [type] [description]
	 */
	public function getECPC()
	{
		$r = $this->clics == 0 ? 0 : round($this->getSpendUSD() / $this->clics, 2);
		return $r;
	}

	/**
	 * [getECPA description]
	 * @return [type] [description]
	 */
	public function getECPA()
	{
		$conv = $this->conv_adv == 0 ? $this->conv_api : $this->conv_adv;
		$r = $conv == 0 ? 0 : round($this->getSpendUSD() / $conv, 2);
		return $r;
	}

	/**
	 * [getConversions description]
	 * @return [type] [description]
	 */
	public function getConversions()
	{		
		return $this->conv_adv == 0 ? $this->conv_api : $this->conv_adv;
	}

	/**
	 * [getCapUSD description]
	 * @return [type] [description]
	 */
	public function getCapUSD()
	{
		$net_currency = Providers::model()->findByPk(Campaigns::model()->findByPk($this->campaigns_id)->providers_id)->currency;
		$cap = Campaigns::model()->findByPk($this->campaigns_id)->cap;
		if ($net_currency == 'USD') // if currency is USD dont apply type change
			return $cap;

		$currency = Currency::model()->findByDate($this->date);
		return $currency ? round($cap / $currency[$net_currency], 2) : 'Currency ERROR!';
	}

	/**
	 * [getCapStatus description]
	 * @return [type] [description]
	 */
	public function getCapStatus()
	{
		if(strtotime($this->date) == strtotime('yesterday') || $this->providers_name=='Adwords')
		{
			//$cap = Campaigns::model()->findByPk($this->campaigns_id)->cap;
			return $this->getSpendUSD()>=$this->getCapUSD() ? TRUE : FALSE;
		}
		else return false;
	}

	/**
	 * [setNewFields description]
	 */
	public function setNewFields()
	{
		// update spend only for affiliates
		if($this->providers->getType() == 1)
			$this->updateSpendAffiliates();

		$this->profit             = $this->getProfit();
		$this->profit_percent     = $this->getProfitPerc();
		$this->click_through_rate = $this->getCtr();
		$this->conversion_rate    = $this->getConvRate();
		$this->eCPM               = $this->getECPM();
		$this->eCPC               = $this->getECPC();
		$this->eCPA               = $this->getECPA();
	}

	/**
	 * [getRateUSD description]
	 * @return [type] [description]
	 */
	public function getRateUSD()
	{
		$campaign     = Campaigns::model()->with('opportunities','opportunities.regions', 'opportunities.regions.financeEntities')->findByPk($this->campaigns_id);
		$financeEntities_currency  = $campaign->opportunities->regions->financeEntities->currency;
		$rate         = $campaign->opportunities->getRate($this->date);

		switch ($campaign->opportunities->model_adv) 
		{	
			case 'CPI':
			case 'CPL':
			case 'CPA':
				$rate=$this->getConv()!=0 ? round($this->revenue/$this->getConv(), 2) : $rate;
				break;
			case 'CPM':
				$rate=$this->imp!=0 ? round($this->revenue/($this->imp/1000), 2) : $rate;
				break;
			case 'CPV':
			case 'CPC':
				$rate=$this->clics!= 0 ? round($this->revenue/$this->clics, 2) : $rate;
				break;
			
		}

		if ($financeEntities_currency == 'USD') // if currency is USD dont apply type change
			return round($rate,2);

		$currency = Currency::model()->findByDate($this->date);
		return $currency ? round($rate / $currency[$financeEntities_currency], 2) : 'Currency ERROR!';
	}

	/**
	 * [getConv description]
	 * @return [type] [description]
	 */
	public function getConv()
	{
		return $this->conv_adv==null ? $this->conv_api : $this->conv_adv; 
	}

	/**
	 * [getImp description]
	 * @return [type] [description]
	 */
	public function getImp()
	{
		return $this->imp_adv==null ? $this->imp : $this->imp_adv; 
	}

	/**
	 * [createByNetwork description]
	 * @return [type] [description]
	 */
	public function createByProvider()
	{
		$this->is_from_api = 0;
		$this->conv_api    = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$this->campaigns_id, ":date"=>$this->date));
		$this->updateRevenue();
		$this->setNewFields();
			
		// Validate if record has already been entry
		$existingModel = DailyReport::model()->find('campaigns_id=:cid AND providers_id=:nid AND date=:date', array(':cid' => $this->campaigns_id, ':nid' => $this->providers_id, ':date' => $this->date));
		if ( $existingModel ) {
			$this->isNewRecord = false;
			$this->id = $existingModel->id;
		}

		$r = new stdClass();
		$r->c_id = $this->campaigns_id;
		if ( $this->save() ) {
			$r->result = "OK";
		} else {
			$r->result  = "ERROR";
			$r->message = $this->getErrors();
		}
		return $r;
	}

	/**
	 * [isFromVector description]
	 * @return boolean [description]
	 */
	public function isFromVector()
	{
		return VectorsHasCampaigns::model()->exists('campaigns_id=:cid', array(':cid'=>$this->campaigns_id));
	}

	/**
	 * [getClicksRedirect description]
	 * @return [type] [description]
	 */
	public function getClicksRedirect()
	{
		$criteria=new CDbCriteria;
		$criteria->select                        ='count(*) as clics';
		$criteria->addCondition("DATE(date) = '".$this->date."' AND campaigns_id=".$this->campaigns_id);
		$clicksLogs                              = ClicksLog::model()->find($criteria)->clics;
		return $clicksLogs;
	}

	public static function getTotalsData($dataProvider)
	{
		$totals             =array();
		$totals['imp']      =0;
		$totals['imp_adv']  =0;
		$totals['clics']    =0;
		$totals['conv_api'] =0;
		$totals['conv_adv'] =0;
		$totals['revenue']  =0;
		$totals['spend']    =0;
		$totals['profit']    =0;
		if($dataProvider->getData())
		{			
			foreach ($dataProvider->getData() as $data) {
				$totals['imp']      +=$data->imp;
				$totals['imp_adv']  +=$data->imp_adv;
				$totals['clics']    +=$data->clics;
				$totals['conv_api'] +=$data->conv_api;
				$totals['conv_adv'] +=$data->conv_adv;
				$totals['revenue']  +=$data->getRevenueUSD();
				$totals['spend']    +=$data->getSpendUSD();
				$totals['profit']   +=$data->getProfit();
			}
		}
		return $totals;
	}
}
