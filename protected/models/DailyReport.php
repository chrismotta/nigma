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
 */
class DailyReport extends CActiveRecord
{

	public $network_name;
	public $account_manager;
	public $campaign_name;

	public $profit;
	public $click_rate;
	public $conv_rate;
	public $profit_perc;
	public $eCPM;
	public $eCPC;
	public $eCPA;

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
			array('campaigns_id, networks_id, imp, clics, conv_api, spend, date', 'required'),
			array('campaigns_id, networks_id, imp, imp_adv, clics, conv_api, conv_adv, is_from_api', 'numerical', 'integerOnly'=>true),
			array('spend, revenue', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaigns_id, networks_id, network_name, campaign_name, account_manager, profit, click_rate, conv_rate, profit_perc, eCPM, eCPC, eCPA, imp, imp_adv, clics, conv_api, conv_adv, spend, revenue, date, is_from_api', 'safe', 'on'=>'search'),
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
			'conv_api' => 'Conv Api',
			'conv_adv' => 'Conv Adv',
			'spend' => 'Spend',
			'revenue' => 'Revenue',
			'date' => 'Date',
			'is_from_api' => 'Is From Api',
			'network_name'	=>	'Network Name',
			'account_manager' => 'Account Manager',
			'campaign_name' => 'Campaign Name',

			'profit' => 'Profit',
			'click_rate' => 'CTR',
			'conv_rate' => 'Conv. Rate',
			'profit_perc'=>'Profit %',
			'eCPM' => 'eCPM',
			'eCPC' => 'eCPC',
			'eCPA' => 'eCPA',
		);
	}


	public function excel()
	{
		$criteria=new CDbCriteria;

		$profit = 'revenue-spend';
		$ctr = 'ROUND((CASE WHEN imp_adv = 0 THEN clics/imp ELSE clics/imp_adv END), 2)';
		$conv_rate = 'ROUND((CASE WHEN conv_adv = 0 THEN conv_api/clics ELSE conv_adv/clics END), 2)';
		$profit_perc = 'ROUND( (' . $profit . ') /revenue, 2)';
		$eCPM = 'ROUND((CASE WHEN imp_adv = 0 THEN spend*1000/imp ELSE spend*1000/imp_adv END), 2)';
		$eCPC = 'ROUND(t.spend/t.clics, 2)';
		$eCPA = 'ROUND((CASE WHEN conv_adv = 0 THEN spend/conv_api ELSE spend/conv_adv END), 2)';

		$criteria->select=array(
			'*', 
			$profit . ' as profit', 
			$ctr . ' as click_rate',
			$conv_rate . ' as conv_rate',
			$profit_perc . ' as profit_perc',
			$eCPM . ' as eCPM',
			$eCPC . ' as eCPC',
			$eCPA . ' as eCPA',
		);

		$criteria->with = array( 'campaigns', 'networks' );

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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

		$profit = 'revenue-spend';
		$ctr = 'ROUND((CASE WHEN imp_adv = 0 THEN clics/imp ELSE clics/imp_adv END), 2)';
		$conv_rate = 'ROUND((CASE WHEN conv_adv = 0 THEN conv_api/clics ELSE conv_adv/clics END), 2)';
		$profit_perc = 'ROUND( (' . $profit . ') /revenue, 2)';
		$eCPM = 'ROUND((CASE WHEN imp_adv = 0 THEN spend*1000/imp ELSE spend*1000/imp_adv END), 2)';
		$eCPC = 'ROUND(t.spend/t.clics, 2)';
		$eCPA = 'ROUND((CASE WHEN conv_adv = 0 THEN spend/conv_api ELSE spend/conv_adv END), 2)';

		$criteria->select=array(
			'*', 
			$profit . ' as profit', 
			$ctr . ' as click_rate',
			$conv_rate . ' as conv_rate',
			$profit_perc . ' as profit_perc',
			$eCPM . ' as eCPM',
			$eCPC . ' as eCPC',
			$eCPA . ' as eCPA',
		);

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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('is_from_api',$this->is_from_api);

		$criteria->compare($profit,$this->profit,true);
		$criteria->compare($ctr,$this->click_rate,true);
		$criteria->compare($conv_rate,$this->conv_rate,true);
		$criteria->compare($profit_perc,$this->profit_perc,true);
		$criteria->compare($eCPM,$this->eCPM,true);
		$criteria->compare($eCPC,$this->eCPC,true);
		$criteria->compare($eCPA,$this->eCPA,true);

		// Related search criteria items added (use only table.columnName)
		$criteria->with = array( 'networks', 'campaigns' ,'campaigns.opportunities.accountManager' );
		$criteria->compare('networks.name',$this->network_name, true);
		$criteria->compare('accountManager.name',$this->account_manager, true);
		$criteria->compare('campaigns.id',$this->campaign_name, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			// Setting 'sort' property in order to add 
			// a sort tool in the related collumns
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
		            'profit'=>array(
		            	'asc'  =>'profit',
						'desc' =>'profit DESC',	
		            ),
		            'click_rate'=>array(
		            	'asc'  =>'click_rate',
						'desc' =>'click_rate DESC',
		            ),
		            'conv_rate'=>array(
		            	'asc'  =>'conv_rate',
						'desc' =>'conv_rate DESC',
		            ),
		            'profit_perc'=>array(
		            	'asc'  =>'profit_perc',
						'desc' =>'profit_perc DESC',
		            ),
		            'eCPM'=>array(
		            	'asc'  =>'eCPM',
						'desc' =>'eCPM DESC',
		            ),
		            'eCPC'=>array(
		            	'asc'  =>'eCPC',
						'desc' =>'eCPC DESC',
		            ),
		            'eCPA'=>array(
		            	'asc'  =>'eCPA',
						'desc' =>'eCPA DESC',
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

	public function getGraphicDateRangeInfo($c_id, $net_id, $startDate, $endDate) {
		$attributes = array('campaigns_id', 'netowrks_id', 'date');
		$condition  = 'campaigns_id=:campaignid AND networks_id=:networkid AND DATE(date) >= :startDate and DATE(date) <= :endDate ORDER BY date';
		$params     = array(":campaignid"=>$c_id, ":networkid"=>$net_id, ":startDate"=>$startDate, ":endDate"=>$endDate);
		$r = DailyReport::model()->findAll( $condition, $params );

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
		$result = array($spend, $conv, $impressions, $clicks, $dates);
		return $result;
	}

	public function updateRevenue()
	{
		$c    = Campaigns::model()->findByPk($this->campaigns_id);
		$opp  = Opportunities::model()->findByPk($c->opportunities_id);
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
}
