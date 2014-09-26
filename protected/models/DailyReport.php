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
 * @property string $spend
 * @property integer $revenue
 * @property string $date
 * @property integer $is_from_api
 *
 * The followings are the available model relations:
 * @property Networks $networks
 * @property Campaigns $campaigns
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
			array('spend, revenue', 'length', 'max'=>11),
			array('date', 'date',  'format'=>'yyyy-M-d'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaigns_id, networks_id, network_name, campaign_name, account_manager, imp, imp_adv, clics, conv_api, conv_adv, spend, revenue, date, is_from_api', 'safe', 'on'=>'search'),
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
			'networks' => array(self::BELONGS_TO, 'Networks', 'networks_id'),
			'campaigns' => array(self::BELONGS_TO, 'Campaigns', 'campaigns_id'),
			'multiRates' => array(self::HAS_MANY, 'MultiRate', 'daily_report_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'campaigns_id' => 'Campaigns',
			'networks_id' => 'Networks',
			'imp' => 'Imp',
			'imp_adv' => 'Imp Adv',
			'clics' => 'Clics',
			'conv_api' => 'Conv s2s',
			'conv_adv' => 'Conv Adv',
			'spend' => 'Spend',
			'revenue' => 'Revenue',
			'date' => 'Date',
			'is_from_api' => 'Is From Api',
			'network_name'	=>	'Network Name',
			'account_manager' => 'Account Manager',
			'campaign_name' => 'Campaign Name',
		);
	}


	public function excel($startDate=NULL, $endDate=NULL)
	{
		$criteria=new CDbCriteria;
		//$criteria->compare('t.id',$this->id);
		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
	    }

		//$criteria->with = array( 'campaigns', 'networks' );

		$criteria->with = array( 'networks', 'campaigns', 'campaigns.opportunities', 'campaigns.opportunities.ios', 'campaigns.opportunities.ios.advertisers' ,'campaigns.opportunities.accountManager' );
		$criteria->compare('networks.name',$this->network_name, true);
		$criteria->compare('networks.has_api',$this->network_hasApi, true);
		$criteria->compare('accountManager.name',$this->account_manager, true);
		$criteria->compare('campaigns.id',$this->campaign_name, true);
		
		$roles = Yii::app()->authManager->getRoles(Yii::app()->user->id);
		//Filtro por role
		$filter = true;
		foreach ($roles as $role => $value) {
			if ( $role == 'admin' or $role == 'media_manager' or $role =='bussiness') {
				$filter = false;
				break;
			}
		}
		if ( $filter )
			$criteria->compare('opportunities.account_manager_id', Yii::app()->user->id);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getTotals($startDate=null, $endDate=null) {
			
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate   = 'today';
		$startDate = date('Y-m-d', strtotime($startDate));
		$endDate = date('Y-m-d', strtotime($endDate));
		$dataTops=array();
		$spends=array();
		$revenues=array();
		$profits=array();
		$dates=array();

		foreach (Utilities::dateRange($startDate,$endDate) as $date) {
			$dataTops[$date]['spends']=0;
			$dataTops[$date]['revenues']=0;
		}
		$criteria=new CDbCriteria;
		$criteria->addCondition("DATE(date)>="."'".$startDate."'");
		$criteria->addCondition("DATE(date)<="."'".$endDate."'");
		$r         = DailyReport::model()->findAll( $criteria );
		foreach ($r as $value) {
			$dataTops[date('Y-m-d', strtotime($value->date))]['spends']+=doubleval($value->getSpendUSD());	
			$dataTops[date('Y-m-d', strtotime($value->date))]['revenues']+=doubleval($value->getRevenueUSD());
		}
		
		foreach ($dataTops as $date => $data) {
			$spends[]=$data['spends'];
			$revenues[]=$data['revenues'];
			$profits[]=$data['revenues']-$data['spends'];
			$dates[]=$date;
		}
		$result=array('spends' => $spends, 'revenues' => $revenues, 'profits' => $profits, 'dates' => $dates);
		
		return $result;
	}

	public function getDailyTotals($startDate=null, $endDate=null) {
			
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate   = 'today';
		$startDate = date('Y-m-d', strtotime($startDate));
		$endDate = date('Y-m-d', strtotime($endDate));
		$imp=0;
		$imp_adv=0;
		$clics=0;
		$conv_s2s=0;
		$conv_adv=0;
		$spend=0;
		$revenue=0;

		$criteria=new CDbCriteria;
		$criteria->addCondition("DATE(date)>="."'".$startDate."'");
		$criteria->addCondition("DATE(date)<="."'".$endDate."'");
		$r         = DailyReport::model()->findAll( $criteria );
		foreach ($r as $value) {
			$imp+=$value->imp;
			$imp_adv+=$value->imp_adv;
			$clics+=$value->clics;
			$conv_s2s+=$value->conv_api;
			$conv_adv+=$value->conv_adv;
			$spend+=doubleval($value->getSpendUSD());	
			$revenue+=doubleval($value->getRevenueUSD());
		}		
		$result=array(
				'imp'		=>$imp,
				'imp_adv'	=>$imp_adv,
				'clics'		=>$clics,
				'conv_s2s'	=>$conv_s2s,
				'conv_adv'	=>$conv_adv,
				'spend'		=>$spend,
				'revenue'	=>$revenue,
			);
		
		return $result;
	}
	// public function getTotals($startDate=null, $endDate=null) {
			
	// 	if(!$startDate)	$startDate = 'today' ;
	// 	if(!$endDate) $endDate   = 'today';
	// 	$startDate = date('Y-m-d', strtotime($startDate));
	// 	$endDate = date('Y-m-d', strtotime($endDate));
	// 	$dataTops=array();
	// 	$spends=array();
	// 	$revenues=array();
	// 	$profits=array();
	// 	$dates=array();

	// 	foreach (Utilities::dateRange($startDate,$endDate) as $date) {
	// 		$dataTops[$date]['spends']=0;
	// 		$dataTops[$date]['revenues']=0;
	// 	}
	// 	$criteria=new CDbCriteria;
	// 	$criteria->addCondition("DATE(date)>="."'".$startDate."'");
	// 	$criteria->addCondition("DATE(date)<="."'".$endDate."'");
	// 	$criteria->select='campaigns_id,networks_id, SUM(spend) as spend, date';
	// 	$criteria->order='date ASC';
	// 	$criteria->group='date,networks_id';
	// 	$r         = DailyReport::model()->findAll( $criteria );
	// 	foreach ($r as $value) {
	// 		$dataTops[date('Y-m-d', strtotime($value->date))]['spends']+=doubleval($value->getSpendUSD());	
	// 	}
	// 	$criteria=new CDbCriteria;
	// 	$criteria->addCondition("DATE(date)>="."'".$startDate."'");
	// 	$criteria->addCondition("DATE(date)<="."'".$endDate."'");
	// 	$criteria->select='campaigns_id,networks_id, SUM(revenue) as revenue, date';
	// 	$criteria->order='date ASC';
	// 	$criteria->group='campaigns_id';
	// 	$r         = DailyReport::model()->findAll( $criteria );
	// 	foreach ($r as $value) {
	// 		$dataTops[date('Y-m-d', strtotime($value->date))]['revenues']+=doubleval($value->getRevenueUSD());	
	// 	}
	// 	foreach ($dataTops as $date => $data) {
	// 		$spends[]=$data['spends'];
	// 		$revenues[]=$data['revenues'];
	// 		$profits[]=$data['revenues']-$data['spends'];
	// 		$dates[]=$date;
	// 	}
	// 	$result=array('spends' => $spends, 'revenues' => $revenues, 'profits' => $profits, 'dates' => $dates);
		
	// 	return $result;
	// }

	public function getTops($startDate=null, $endDate=null,$order) {
			
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate   = 'today';
		$startDate = date('Y-m-d', strtotime($startDate));
		$endDate = date('Y-m-d', strtotime($endDate));

		$dataTops=array();
		$spends=array();
		$revenues=array();
		$profits=array();
		$campaigns=array();	
		$campaigns_id=array();

		$criteria=new CDbCriteria;
		$criteria->addCondition("DATE(date)>="."'".$startDate."'");
		$criteria->addCondition("DATE(date)<="."'".$endDate."'");
		$criteria->select='campaigns_id,networks_id, SUM(spend) as spend, SUM(revenue) revenue, date';
		if($order=='spend')$criteria->order='spend DESC';
		if($order=='profit')$criteria->order='spend ASC, CASE revenue WHEN (revenue-spend)>0 THEN revenue END';
		$criteria->group='campaigns_id,networks_id';
		$criteria->limit=6;

		$r         = DailyReport::model()->findAll( $criteria );
		foreach ($r as $value) {
			$spends[]=doubleval($value->getSpendUSD());
			$revenues[]=doubleval($value->getRevenueUSD());
			$profits[]=doubleval($value->getProfit());
			$campaigns[]=$value->campaigns->name;		
			$campaigns_id[]=$value->campaigns->id;		
		}
		
		$result=array('spends' => $spends, 'revenues' => $revenues, 'profits' => $profits, 'campaigns' => $campaigns, 'campaigns_id' => $campaigns_id);
		$dataTops['array']= $result;
		$dataTops['dataProvider']= new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>false,
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
		//$criteria->select=array('COUNT(t.conv_adv) as conv_adv');
		//$criteria->compare('t.id',$this->id);
		////SELECT campaigns_id,
		// case SUM(conv_adv) when 0 then SUM(conv_api) else SUM(conv_adv) end as conversions
		// FROM `daily_report` 
		// WHERE DATE(date)>='2014-09-01' 
		// AND DATE(date)<='2014-09-21'
		// GROUP BY campaigns_id
		// ORDER BY conversions
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
		// if($type=='array')
		// {
			$campaigns=array();
			$conversions=array();
			$campaigns_id=array();
			$conversions_rate=array();
			$r         = self::model()->findAll($criteria);
			
			foreach ($r as $value) {
				$conversions[]=intval($value->conversions);
				$conversions_rate[]=intval($value->convrate);
				$campaigns[]=$value->campaigns->name;	
				$campaigns_id[]=$value->campaigns->id;
			}
			$result=array('conversions' => $conversions,'campaigns_id' => $campaigns_id, 'campaigns' => $campaigns, 'conversions_rate' => $conversions_rate);
			//return $result;
			$dataDash['array']=$result;
		// }
		// else{
			$dataDash['dataProvider']= new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>false,
				'sort'=>array(
					'attributes'   =>array(
			            '*',
			        ),
			    ),

			));
		//}
		return $dataDash;
	}

	public function search($startDate=NULL, $endDate=NULL, $accountManager=NULL,$opportunitie=null,$networks=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('campaigns_id',$this->campaigns_id);
		$criteria->compare('networks_id',$this->networks_id);
		$criteria->compare('imp',$this->imp);
		$criteria->compare('imp_adv',$this->imp_adv);
		$criteria->compare('clics',$this->clics);
		$criteria->compare('conv_api',$this->conv_api);
		$criteria->compare('conv_adv',$this->conv_adv);
		$criteria->compare('spend',$this->spend,true);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('is_from_api',$this->is_from_api);

		if ( $startDate != NULL && $endDate != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($startDate)));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($endDate)));
		}
		
		// Related search criteria items added (use only table.columnName)
		$criteria->with = array( 'networks', 'campaigns' ,'campaigns.opportunities.accountManager' );
		$criteria->compare('networks.name',$this->network_name, true);
		$criteria->compare('networks.has_api',$this->network_hasApi, true);
		$criteria->compare('accountManager.name',$this->account_manager, true);
		$criteria->compare('campaigns.id',$this->campaign_name, true);
		if ( $accountManager != NULL) {
			$criteria->compare('accountManager.id',$accountManager);
		}
		if ( $opportunitie != NULL) {
			$criteria->compare('opportunities.id',$opportunitie);
		}
		if ( $networks != NULL) {
			$criteria->compare('networks.id',$networks);
		}
		
		$roles = Yii::app()->authManager->getRoles(Yii::app()->user->id);
		//Filtro por role
		$filter = true;
		foreach ($roles as $role => $value) {
			if ( $role == 'admin' or $role == 'media_manager' or $role =='bussiness') {
				$filter = false;
				break;
			}
		}
		if ( $filter )
			$criteria->compare('opportunities.account_manager_id', Yii::app()->user->id);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			// Setting 'sort' property in order to add 
			// a sort tool in the related collumns
			'pagination'=>array(
                'pageSize'=>30,
            ),
			'sort'=>array(
				'defaultOrder' => 't.id DESC',
				'attributes'   =>array(
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
		            // Adding all the other default attributes
		            '*',
		        ),
		    ),
	    	// 'totalItemCount' => 50,
		    'pagination'=>array(
		        'pageSize'=>10,
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

		$spend       = array();
		$impressions = array();
		$clicks      = array();
		$conv        = array();
		$date        = array();

		foreach ($r as $value) {
			$dates[]       = date_format( new DateTime($value->date), "d-m-Y" );;
			$spend[]       = array($value->date, $value->spend);
			$impressions[] = array($value->date, $value->imp);
			$clicks[]      = array($value->date, $value->clics);
			$conv[]        = array($value->date, $value->conv_adv);
		}
		$result = array(
			'spend' => $spend, 
			'conv'  => $conv, 
			'imp'   => $impressions, 
			'click' => $clicks, 
			'date'  => $dates
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
				$this->revenue = $this->imp_adv ? $this->imp_adv * $rate / 1000 : $this->imp * $rate / 1000;
				break;
			case 'CPC':
				$this->revenue = $this->clics * $rate;
				break;
			case 'CPA':
				$this->revenue = $this->conv_adv ? $this->conv_adv * $rate : $this->conv_api * $rate;
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
		return $this->getRevenueUSD() - $this->getSpendUSD();
	}

	public function getCtr()
	{
		$imp = $this->imp_adv == 0 ? $this->imp : $this->imp_adv;
		$r = $imp == 0 ? 0 : number_format($this->clics / $imp, 2);
		return $r;
	}

	public function getConvRate()
	{
		$conv = $this->conv_adv == 0 ? $this->conv_api : $this->conv_adv;
		$r = $this->clics == 0 ? 0 : number_format( $conv / $this->clics, 2 );
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

}
