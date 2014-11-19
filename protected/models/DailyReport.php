<?php 

/**
 * This is the model class for table "daily_report".
 *
 * The followings are the available columns in table 'daily_report':
 * @property integer $id
 * @property integer $campaigns_id
 * @property integer $networks_id
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
 * @property Networks $networks
 * @property DailyVectors[] $dailyVectors
 * @property MultiRate[] $multiRates
 */
class DailyReport extends CActiveRecord
{

	public $network_name;
	public $network_hasApi;
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
			array('campaigns_id, networks_id, imp, imp_adv, clics, conv_api, conv_adv, is_from_api', 'numerical', 'integerOnly'=>true),
			array('spend, revenue, profit, profit_percent, click_through_rate, conversion_rate, eCPM, eCPC, eCPA', 'length', 'max'=>11),
			array('comment', 'length', 'max'=>255),
			array('date', 'date',  'format'=>'yyyy-M-d'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaigns_id, networks_id, network_name, campaign_name, account_manager, imp, imp_adv, clics, conv_api, conv_adv, spend, revenue, date, is_from_api, profit, profit_percent, click_through_rate, conversion_rate, eCPM, eCPC, eCPA, comment', 'safe', 'on'=>'search'),
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
			'networks'     => array(self::BELONGS_TO, 'Networks', 'networks_id'),
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
			'networks_id'        => 'Networks',
			'imp'                => 'Imp',
			'imp_adv'            => 'Imp Adv',
			'clics'              => 'Clics',
			'conv_api'           => 'Conv s2s',
			'conv_adv'           => 'Conv Adv',
			'spend'              => 'Spend',
			'revenue'            => 'Revenue',
			'date'               => 'Date',
			'is_from_api'        => 'Is From Api',
			'network_name'       => 'Network Name',
			'account_manager'    => 'Account Manager',
			'campaign_name'      => 'Campaign Name',
			'profit'             => 'Profit',
			'profit_percent'     => 'Profit %',
			'rate'               => 'Rate',
			'click_through_rate' => 'CTR',
			'conversion_rate'    => 'CR',
			'eCPM'               => 'eCPM',
			'eCPC'               => 'eCPC',
			'eCPA'               => 'eCPA',
			'comment'            => 'Com.',
			'mr'				 => '',
			'currency'			 => 'Currency',
			'percent_off'		 => 'Percent Off',
			'off'		 		 => 'Off',
			'total'		 		 => 'Total',
		);
	}


	public function excel($startDate=NULL, $endDate=NULL, $accountManager=NULL,$opportunities=null,$networks=null,$sum=0,$adv_categories=null)
	{
		$criteria=new CDbCriteria;
		//$criteria->compare('t.id',$this->id);
		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
	    }
	    if ( $networks != NULL) {
			if(is_array($networks))
			{
				$query="(";
				$i=0;
				foreach ($networks as $net) {	
					if($i==0)			
						$query.="networks.id=".$net;
					else
						$query.=" OR networks.id=".$net;
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('networks.id',$networks);
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

		//$criteria->with = array( 'campaigns', 'networks' );

		$criteria->with = array( 'networks', 'campaigns', 'campaigns.opportunities', 'campaigns.opportunities.ios', 'campaigns.opportunities.ios.advertisers' ,'campaigns.opportunities.accountManager','campaigns.opportunities.country' );
		$criteria->compare('networks.name',$this->network_name, true);
		$criteria->compare('networks.has_api',$this->network_hasApi, true);
		$criteria->compare('accountManager.name',$this->account_manager, true);
		$criteria->compare('campaigns.id',$this->campaign_name, true);
		
		FilterManager::model()->addUserFilter($criteria, 'daily');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getTotals($startDate=null, $endDate=null,$accountManager=NULL,$opportunities=null,$networks=null,$adv_categories=null) {
			
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
		$criteria->with = array( 'networks', 'campaigns', 'campaigns.opportunities','campaigns.opportunities.accountManager', 'campaigns.opportunities.country', 'campaigns.opportunities.ios.advertisers', 'campaigns.opportunities.carriers' );
		if ( $networks != NULL) {
			if(is_array($networks))
			{
				$query="(";
				$i=0;
				foreach ($networks as $net) {	
					if($i==0)			
						$query.="networks.id=".$net;
					else
						$query.=" OR networks.id=".$net;
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('networks.id',$networks);
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

	public function getDailyTotals($startDate=null, $endDate=null, $accountManager=NULL,$opportunities=null,$networks=null,$adv_categories=null) {
			
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
		$criteria->with = array( 'networks', 'campaigns', 'campaigns.opportunities','campaigns.opportunities.accountManager', 'campaigns.opportunities.country', 'campaigns.opportunities.ios.advertisers', 'campaigns.opportunities.carriers' );
		
		if ( $networks != NULL) {
			if(is_array($networks))
			{
				$query="(";
				$i=0;
				foreach ($networks as $net) {	
					if($i==0)			
						$query.="networks.id=".$net;
					else
						$query.=" OR networks.id=".$net;
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('networks.id',$networks);
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

	public function getTops($startDate=null, $endDate=null,$order) {
			
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate     = 'today';
		$startDate    = date('Y-m-d', strtotime($startDate));
		$endDate      = date('Y-m-d', strtotime($endDate));
		
		$dataTops     =array();
		$spends       =array();
		$revenues     =array();
		$profits      =array();
		$campaigns    =array();	
		$campaigns_id =array();

		$criteria=new CDbCriteria;
		$criteria->addCondition("DATE(date)>="."'".$startDate."'");
		$criteria->addCondition("DATE(date)<="."'".$endDate."'");
		$criteria->select = array(
							'campaigns_id', 
							'networks_id', 
							'SUM(spend) as spend', 
							'SUM(revenue) revenue', 
							'date, 
							sum(profit) as profit'
							);
		if($order=='profit')$criteria->order='SUM(profit) DESC';
		$criteria->group='campaigns_id,networks_id';
		
		if($order=='spend') {
			$select = "
				*, sum(d.spend / 
						(
							SELECT (
									CASE (
										SELECT networks.currency
										FROM networks
										WHERE d.networks_id=networks.id
									) WHEN 'USD' THEN (
										SELECT 1
									)"; 
			$currencyModel = new Currency;
			$currency = $currencyModel->attributes;
			array_pop($currency); // remove id
			array_pop($currency); // remove date
			foreach ($currency as $key => $value) {
				$select .= " WHEN '" . $key . "' THEN ( SELECT " . $key . ")";
			}

			$select .= 		" END
								)
							FROM currency c 
							WHERE date(c.date)<=d.date
							ORDER BY c.date DESC
							LIMIT 1
						)
					) as spend";
			$criteria->select    = $select;
			$criteria->alias     = "d";
			$criteria->condition = "date(d.date) BETWEEN '" . date('Y-m-d', strtotime($startDate)) . "' AND '" . date('Y-m-d', strtotime($endDate)) . "'";
			$criteria->group     = "d.campaigns_id";
			$criteria->order     = "spend DESC";
		}
		$criteria->limit=6;

		$r         = DailyReport::model()->findAll( $criteria );
		foreach ($r as $value) {
			$spends[]       = doubleval($value->spend);
			$revenues[]     = doubleval($value->getRevenueUSD());
			$profits[]      = doubleval($value->profit);
			$campaigns[]    = $value->campaigns->name;		
			$campaigns_id[] = $value->campaigns->id;		
		}
		
		$result=array(
			'spends'       => $spends,
			'revenues'     => $revenues, 
			'profits'      => $profits, 
			'campaigns'    => $campaigns, 
			'campaigns_id' => $campaigns_id
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

	public function getDataDash($startDate=NULL, $endDate=NULL, $order)
	{
		$criteria=new CDbCriteria;
		$criteria->select='case SUM(conv_adv) when 0 then SUM(conv_api) else SUM(conv_adv) end as conversions,
						  ROUND(((case SUM(conv_adv) when 0 then SUM(conv_api) else SUM(conv_adv) end/SUM(clics))*100)) as convrate';
		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
		}
		$criteria->group='campaigns_id';
		if($order=='conversions')$criteria->order='conversions DESC';
		if($order=='convrate')$criteria->order='convrate DESC';
		$criteria->with=array('campaigns', );
		$criteria->limit=6;
		$dataDash=array();
			$campaigns        =array();
			$conversions      =array();
			$campaigns_id     =array();
			$conversions_rate =array();
			$r                = self::model()->findAll($criteria);
			
			foreach ($r as $value) {
				$conversions[]      =intval($value->conversions);
				$conversions_rate[] =intval($value->convrate);
				$campaigns[]        =$value->campaigns->name;	
				$campaigns_id[]     =$value->campaigns->id;
			}
			$result=array(
				'conversions'      => $conversions,
				'campaigns_id'     => $campaigns_id, 
				'campaigns'        => $campaigns, 
				'conversions_rate' => $conversions_rate
				);
			$dataDash['array']=$result;
			$dataDash['dataProvider']= new CActiveDataProvider($this, array(
				'criteria'   =>$criteria,
				'pagination' =>false,
				'sort'       =>array(
					'attributes'   =>array(
			            '*',
			        ),
			    ),

			));
		return $dataDash;
	}

	public function search($startDate=NULL, $endDate=NULL, $accountManager=NULL,$opportunities=null,$networks=null,$sum=0,$adv_categories=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$sumArray=array(
					// Adding custom sort attributes
		            'network_name'=>array(
						'asc'  =>'networks.name',
						'desc' =>'networks.name DESC',
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
		$criteria->with = array( 'networks', 'campaigns', 'campaigns.opportunities','campaigns.opportunities.accountManager', 'campaigns.opportunities.country', 'campaigns.opportunities.ios.advertisers', 'campaigns.opportunities.carriers' );
		$criteria->compare('opportunities.rate',$this->rate);
		$criteria->compare('networks.name',$this->network_name, true);
		$criteria->compare('networks.has_api',$this->network_hasApi, true);
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

		if ( $networks != NULL) {
			if(is_array($networks))
			{
				$query="(";
				$i=0;
				foreach ($networks as $net) {	
					if($i==0)			
						$query.="networks.id=".$net;
					else
						$query.=" OR networks.id=".$net;
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('networks.id',$networks);
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
		$criteria->compare('t.campaigns_id',$this->campaign_name,true);
		$criteria->compare('carriers.mobile_brand',$this->campaign_name,true,'OR');
		$criteria->compare('country.ISO2',$this->campaign_name,true,'OR');
		$criteria->compare('advertisers.prefix',$this->campaign_name,true,'OR');
		$criteria->compare('opportunities.product',$this->campaign_name,true,'OR');
		$criteria->compare('campaigns.name',$this->campaign_name,true,'OR');
		
		FilterManager::model()->addUserFilter($criteria, 'daily');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			// Setting 'sort' property in order to add 
			// a sort tool in the related collumns
			'pagination'=>array(
                'pageSize'=>30,
            ),
			'sort'=>array(
				'defaultOrder' => 't.id DESC',
				'attributes'   =>$sumArray,
		    ),
		));
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
	 * @param  $net_id    network id
	 * @param  $startDate start date
	 * @param  $endDate   end date
	 * @return 
	 */
	public function getGraphicDateRangeInfo($c_id, $net_id, $startDate, $endDate) {
		
		$condition = 'campaigns_id=:campaignid AND networks_id=:networkid AND DATE(date) >= :startDate and DATE(date) <= :endDate ORDER BY date';
		$params    = array(":campaignid"=>$c_id, ":networkid"=>$net_id, ":startDate"=>$startDate, ":endDate"=>$endDate);
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

	public function updateRevenue()
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
			return;
		}

		// update revenue for single rate
		$rate = $opp->rate;
		switch ($opp->model_adv) {
			case 'CPM':
				$this->revenue = $this->imp_adv != NULL ? $this->imp_adv * $rate / 1000 : $this->imp * $rate / 1000;
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

	public function getRevenueUSD()
	{
		$camp         = Campaigns::model()->findByPk($this->campaigns_id);
		$opp          = Opportunities::model()->findByPk($camp->opportunities_id);
		$ios_currency = Ios::model()->findByPk($opp->ios_id)->currency;
	
		if ($ios_currency == 'USD')	// if currency is USD dont apply type change
			return $this->revenue;

		$currency = Currency::model()->findByDate($this->date);
		return $currency ? number_format($this->revenue / $currency[$ios_currency], 2) : 'Currency ERROR!';
	}

	public function getSpendUSD()
	{
		$net_currency = Networks::model()->findByPk($this->networks_id)->currency;

		if ($net_currency == 'USD') // if currency is USD dont apply type change
			return $this->spend;

		$currency = Currency::model()->findByDate($this->date);
		return $currency ? number_format($this->spend / $currency[$net_currency], 2) : 'Currency ERROR!';
	}

	public function getProfit()
	{
		return number_format($this->getRevenueUSD() - $this->getSpendUSD(), 2);
	}
	public function getProfits()
	{
		return $this->profit;
	}
	public function getCtr()
	{
		$imp = $this->imp_adv == 0 ? $this->imp : $this->imp_adv;
		$r = $imp == 0 ? 0 : number_format($this->clics / $imp, 4);
		return $r;
	}

	public function getConvRate()
	{
		$conv = $this->conv_adv == 0 ? $this->conv_api : $this->conv_adv;
		$r = $this->clics == 0 ? 0 : number_format( $conv / $this->clics, 4 );
		return $r;
	}

	public function getProfitPerc()
	{
		$revenue = $this->getRevenueUSD();
		$r = $revenue == 0 ? 0 : number_format($this->getProfit() / $revenue, 2);
		return $r;
	}

	public function getECPM()
	{
		$imp = $this->imp_adv == 0 ? $this->imp : $this->imp_adv;
		$r = $imp == 0 ? 0 : number_format($this->getSpendUSD() * 1000 / $imp, 2);
		return $r;
	}

	public function getECPC()
	{
		$r = $this->clics == 0 ? 0 : number_format($this->getSpendUSD() / $this->clics, 2);
		return $r;
	}

	public function getECPA()
	{
		$conv = $this->conv_adv == 0 ? $this->conv_api : $this->conv_adv;
		$r = $conv == 0 ? 0 : number_format($this->getSpendUSD() / $conv, 2);
		return $r;
	}

	public function getConversions()
	{		
		return $this->conv_adv == 0 ? $this->conv_api : $this->conv_adv;
	}

	public function getCapUSD()
	{
		$net_currency = Networks::model()->findByPk(Campaigns::model()->findByPk($this->campaigns_id)->networks_id)->currency;
		$cap = Campaigns::model()->findByPk($this->campaigns_id)->cap;
		if ($net_currency == 'USD') // if currency is USD dont apply type change
			return $cap;

		$currency = Currency::model()->findByDate($this->date);
		return $currency ? number_format($cap / $currency[$net_currency], 2) : 'Currency ERROR!';
	}

	public function getCapStatus()
	{
		if(strtotime($this->date) == strtotime('yesterday') || $this->network_name=='Adwords')
		{
			//$cap = Campaigns::model()->findByPk($this->campaigns_id)->cap;
			return $this->getSpendUSD()>=$this->getCapUSD() ? TRUE : FALSE;
		}
		else return false;
	}
	
	public function setNewFields()
	{
		$this->profit             = $this->getProfit();
		$this->profit_percent     = $this->getProfitPerc();
		$this->click_through_rate = $this->getCtr();
		$this->conversion_rate    = $this->getConvRate();
		$this->eCPM               = $this->getECPM();
		$this->eCPC               = $this->getECPC();
		$this->eCPA               = $this->getECPA();
	}

	public function getRateUSD()
	{
		$opportunitie =Campaigns::model()->findByPk($this->campaigns_id)->opportunities_id;
		$rate         = Opportunities::model()->findByPk($opportunitie)->rate;
		$io_currency  = Ios::model()->findByPk(Opportunities::model()->findByPk($opportunitie)->ios_id)->currency;

		if ($io_currency == 'USD') // if currency is USD dont apply type change
			return $rate;

		$currency = Currency::model()->findByDate($this->date);
		return $currency ? number_format($rate / $currency[$io_currency], 2) : 'Currency ERROR!';
	}

	public function getConv()
	{
		return $this->conv_adv==null ? $this->conv_api : $this->conv_adv; 
	}

	public function createByNetwork()
	{
		$this->is_from_api = 0;
		$this->conv_api    = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$this->campaigns_id, ":date"=>$this->date));
		$this->updateRevenue();
		$this->setNewFields();
			
		// Validate if record has already been entry
		$existingModel = DailyReport::model()->find('campaigns_id=:cid AND networks_id=:nid AND date=:date', array(':cid' => $this->campaigns_id, ':nid' => $this->networks_id, ':date' => $this->date));
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

	public function isFromVector()
	{
		return VectorsHasCampaigns::model()->exists('campaigns_id=:cid', array(':cid'=>$this->campaigns_id));
	}
}
