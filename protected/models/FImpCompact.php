<?php

/**
 * This is the model class for table "F_Imp_Compact".
 *
 * The followings are the available columns in table 'F_Imp_Compact':
 * @property integer $id
 * @property integer $D_Demand_id
 * @property integer $D_Supply_id
 * @property string $D_GeoLocation_id
 * @property string $D_UserAgent_id
 * @property string $date_time
 * @property string $unique_id
 * @property string $pubid
 * @property string $ip_forwarded
 * @property string $referer_url
 * @property string $referer_app
 * @property integer $imps
 * @property integer $unique_imps
 * @property string $revenue
 * @property string $cost
 */
class FImpCompact extends CActiveRecord
{

	// group
	public $date;
	public $hour;
	public $advertiser;
	public $campaign;
	public $tag;
	public $provider;
	public $placement;
	public $pubid;
	public $connection_type;
	public $country;
	public $carrier;
	public $device_type;
	public $device_brand;
	public $device_model;
	public $os_type;
	public $os_version;
	public $browser_type;
	public $browser_version;
	// sum
	public $impressions;
	public $unique_imps;
	public $revenue;
	public $cost;
	public $profit;
	public $revenue_eCPM;
	public $cost_eCPM;
	public $profit_eCPM;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'F_Imp_Compact';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'dBid'         => array(self::HAS_ONE, 'DBid', 'F_Impressions_id'),
			'dGeoLocation' => array(self::BELONGS_TO, 'DGeoLocation', 'D_GeoLocation_id'),
			'dUserAgent'   => array(self::BELONGS_TO, 'DUserAgent', 'D_UserAgent_id'),
			'dDemand'      => array(self::BELONGS_TO, 'DDemand', 'D_Demand_id'),
			'dSupply'      => array(self::BELONGS_TO, 'DSupply', 'D_Supply_id'),
			'tags'         => array(self::BELONGS_TO, 'Tags', 'D_Demand_id'),
			'placements'   => array(self::BELONGS_TO, 'Placements', 'D_Supply_id'),
		);
	}	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('D_Demand_id, D_Supply_id, D_GeoLocation_id, D_UserAgent_id, date_time, unique_id', 'required'),
			array('D_Demand_id, D_Supply_id, imps, unique_imps', 'numerical', 'integerOnly'=>true),
			array('D_GeoLocation_id, D_UserAgent_id, pubid, ip_forwarded, referer_url, referer_app', 'length', 'max'=>255),
			array('unique_id', 'length', 'max'=>40),
			array('revenue, cost', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, D_Demand_id, D_Supply_id, D_GeoLocation_id, D_UserAgent_id, date_time, unique_id, pubid, ip_forwarded, referer_url, referer_app, imps, unique_imps, revenue, cost', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'               => 'ID',
			'D_Demand_id'      => 'D Demand',
			'D_Supply_id'      => 'D Supply',
			'D_GeoLocation_id' => 'D Geo Location',
			'D_UserAgent_id'   => 'D User Agent',
			'date_time'        => 'Date Time',
			'unique_id'        => 'Unique',
			'pubid'            => 'Pub ID',
			'ip_forwarded'     => 'Ip Forwarded',
			'referer_url'      => 'Referer Url',
			'referer_app'      => 'Referer App',
			// facts
            'date'            => 'Date',
            'hour'            => 'Hour',
            'pubid'           => 'Pub ID',
            // suplly demand
            'advertiser'      => 'Advertiser', 
            'provider'        => 'Publisher', 
            'campaign'        => 'Campaign', 
            'tag'             => 'Tag', 
            'placement'       => 'Placement', 
            // geo
            'connection_type' => 'Connection Type', 
            'country'         => 'Country', 
            'carrier'         => 'Carrier',
            // user_agent
            'device_type'     => 'Device Type',
            'device_brand'    => 'Device Brand',
            'device_model'    => 'Device Model',
            'os_type'         => 'OS',
            'os_version'      => 'OS Version',
            'browser_type'    => 'Browser',
            'browser_version' => 'Browser Version',
            // sums
            'impressions'     => 'Impressions',
            'unique_imps'       => 'Unique Imps', 
            'revenue'         => 'Revenue', 
            'cost'            => 'Cost', 
            'profit'          => 'Profit', 
            'revenue_eCPM'    => 'ReCPM', 
            'cost_eCPM'       => 'CeCPM', 
            'profit_eCPM'     => 'PeCPM',

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
	public function search($totals=false, $partner=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$request = $_REQUEST;
		$criteria=new CDbCriteria;

		// date  
		
		$dateStart = date('Y-m-d', strtotime($request['dateStart']));
		$dateEnd = date('Y-m-d', strtotime($request['dateEnd']));
		$criteria->addBetweenCondition('DATE(date_time)', $dateStart, $dateEnd, 'AND');

		// time
		
		$timeStart = date('H:i:00', strtotime($request['timeStart']));
		$timeEnd = date('H:i:59', strtotime($request['timeEnd']));
		$criteria->addBetweenCondition('TIME(date_time)', $timeStart, $timeEnd, 'AND');

		// 

		$group  = array_merge($request['group1'],$request['group2']);
		$sum    = $request['sum'];
		$filter = isset($request['filter']) ? $request['filter'] : null;

		// $filter = isset($request['filter']) ? $request['filter'] : null;

		if($partner)
			$criteria->compare('dSupply.provider',$partner);

		// $criteria->compare('id',$this->id);
		// $criteria->compare('D_Demand_id',$this->D_Demand_id);
		// $criteria->compare('D_Supply_id',$this->D_Supply_id);
		// $criteria->compare('D_GeoLocation_id',$this->D_GeoLocation_id);
		// $criteria->compare('D_UserAgent_id',$this->D_UserAgent_id);
		// $criteria->compare('date_time',$this->date_time,true);
		// $criteria->compare('unique_id',$this->unique_id,true);
		// $criteria->compare('pubid',$this->pubid,true);
		// $criteria->compare('ip_forwarded',$this->ip_forwarded,true);
		// $criteria->compare('referer_url',$this->referer_url,true);
		// $criteria->compare('referer_app',$this->referer_app,true);


		// querys
		$groupQuerys = array(
			// group
			'date'            => 'DATE(t.date_time)',
			'hour'            => 'HOUR(t.date_time)',
			'advertiser'      => 'dDemand.advertiser',
			'campaign'        => 'dDemand.campaign',
			'tag'             => 'dDemand.tag',
			'provider'        => 'dSupply.provider',
			'placement'       => 'dSupply.placement',
			'pubid'           => 't.pubid',
			'connection_type' => 'dGeoLocation.connection_type',
			'country'         => 'dGeoLocation.country',
			'carrier'         => 'dGeoLocation.carrier',
			'device_type'     => 'dUserAgent.device_type',
			'device_brand'    => 'dUserAgent.device_brand',
			'device_model'    => 'dUserAgent.device_model',
			'os_type'         => 'dUserAgent.os_type',
			'os_version'      => 'dUserAgent.os_version',
			'browser_type'    => 'dUserAgent.browser_type',
			'browser_version' => 'dUserAgent.browser_version',
			);
		$sumQuerys = array(
			// sum
			'impressions'     => 'SUM(t.imps)',
			//'unique_user'     => 'COUNT(distinct t.unique_id)',
			'unique_imps'     => 'SUM(t.unique_imps)',
			'revenue'         => !$partner ? 'SUM(t.revenue)' : 'SUM(t.cost)',
			'cost'            => 'SUM(t.cost)',
			'profit'          => 'SUM(t.revenue)-SUM(t.cost)',
			'revenue_eCPM'    => !$partner ? 'SUM(t.revenue) * 1000 / COUNT(t.imps)' : 'SUM(t.cost) * 1000 / COUNT(t.imps)',
			'cost_eCPM'       => 'SUM(t.cost) * 1000 / COUNT(t.imps)',
			'profit_eCPM'     => '(SUM(t.revenue)-SUM(t.cost)) * 1000 / COUNT(t.imps)',
			);
		$selectQuerys = array_merge($groupQuerys, $sumQuerys);

		$decimalColumns = array(
			'impressions'     => '0',
			'unique_imps'     => '0',
			'revenue'         => '2',
			'cost'            => '2',
			'profit'          => '2',
			'revenue_eCPM'    => '2',
			'cost_eCPM'       => '2',
			'profit_eCPM'	  => '2',
			);

		$select  = array();
		$orderBy = array();
		$sort    = array();
	

		// group columns
		if(!$totals){

			$groupBy = array();

			foreach ($group as $col => $val) {				
				if($val){
					$sel       = $selectQuerys[$col];
					
					$select[]  = $sel.' AS '.$col;
					$groupBy[] = $sel;
					$orderBy[] = $sel;

					$sort[$col] = array(
						'asc'  => $sel.' ASC',
						'desc' => $sel.' DESC',
				    );
				}			
			}

			if(isset($groupBy)) $criteria->group  = implode(',', $groupBy);

		}

		// sum columns
		foreach ($sum as $col => $val) {
			if($val){
				$sel       = $selectQuerys[$col];
				$dec       = $decimalColumns[$col];
				$select[]  = 'FORMAT('.$sel.','.$dec.') AS '.$col;
				$orderBy[] = $sel;

				$sort[$col] = array(
					'asc'  => $sel.' DESC',
					'desc' => $sel.' ASC',
			    );
			}
		}

		// filters
		if(isset($filter)){	
			foreach ($filter as $col => $values) {
				$criteria->addInCondition($selectQuerys[$col], $values);
			}
		}


		$criteria->with = array(
			'dDemand',
			'dGeoLocation',
			'dSupply',
			'dUserAgent',
			);

		if(isset($select)) $criteria->select = $select;
		

		// return array if is totals, dataProviders if not
		if($totals){
			return $this->find($criteria); 
		}else{
			return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=> KHtml::pagination($request),
				'sort'=>array(
					'route' => !$partner ? 'stats/impressions' : 'partners/previewPublishersCPM',
					'params' => $request,
					'defaultOrder' => isset($orderBy) ? implode(',', $orderBy) : '',
					'attributes'   => $sort,
				),
			));
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FImpCompact the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
