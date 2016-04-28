<?php

/**
 * This is the model class for table "F_Impressions".
 *
 * The followings are the available columns in table 'F_Impressions':
 * @property integer $id
 * @property integer $D_Demand_id
 * @property integer $D_Supply_id
 * @property integer $D_GeoLocation_id
 * @property integer $D_UserAgent_id
 * @property string $date_time
 * @property string $unique_id
 * @property string $pubid
 * @property string $ip_forwarded
 * @property string $referer_url
 * @property string $referer_app
 *
 * The followings are the available model relations:
 * @property DBid[] $dBid
 * @property DDemand $dDemand
 * @property DGeoLocation $dGeoLocation
 * @property DSupply $dSupply
 * @property DUserAgent $dUserAgent
 */
class FImpressions extends CActiveRecord
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
	public $unique_user;
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
		return 'F_Impressions';
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
			array('D_Demand_id, D_Supply_id, D_GeoLocation_id, D_UserAgent_id', 'numerical', 'integerOnly'=>true),
			array('unique_id', 'length', 'max'=>40),
			array('pubid, ip_forwarded, referer_url, referer_app', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, D_Demand_id, D_Supply_id, D_GeoLocation_id, D_UserAgent_id, date_time, unique_id, pubid, ip_forwarded, referer_url, referer_app, advertiser, trafficSource', 'safe', 'on'=>'search'),
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
			'dBid'         => array(self::HAS_ONE, 'DBid', 'F_Impressions_id'),
			'dDemand'      => array(self::BELONGS_TO, 'DDemand', 'D_Demand_id'),
			'dGeoLocation' => array(self::BELONGS_TO, 'DGeoLocation', 'D_GeoLocation_id'),
			'dSupply'      => array(self::BELONGS_TO, 'DSupply', 'D_Supply_id'),
			'dUserAgent'   => array(self::BELONGS_TO, 'DUserAgent', 'D_UserAgent_id'),
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
            'unique_user'       => 'Unique Users', 
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
	public function search()
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
		$sort   = array();

		// $filter = isset($request['filter']) ? $request['filter'] : null;

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


		// GROUP COLUMNS

		if(isset($group['date']) && $group['date']){
			$select[] = 'DATE(t.date_time) AS date';
			$groupBy[] = 'DATE(t.date_time)';
			$orderBy[] = 'DATE(t.date_time) DESC';
		}
		if(isset($group['hour']) && $group['hour']){
			$select[] = 'HOUR(t.date_time) AS hour';
			$groupBy[] = 'HOUR(t.date_time)';
			$orderBy[] = 'HOUR(t.date_time)';
		}
		if(isset($group['advertiser']) && $group['advertiser']){
			$select[] = 'dDemand.advertiser AS advertiser';
			$groupBy[] = 'dDemand.advertiser';
			$orderBy[] = 'dDemand.advertiser';
		}
		if(isset($group['campaign']) && $group['campaign']){
			$select[] = 'dDemand.campaign AS campaign';
			$groupBy[] = 'dDemand.campaign';
			$orderBy[] = 'dDemand.campaign';
		}
		if(isset($group['tag']) && $group['tag']){
			$select[] = 't.D_Demand_id AS tag';
			$groupBy[] = 't.D_Demand_id';
			$orderBy[] = 't.D_Demand_id';
		}
		if(isset($group['provider']) && $group['provider']){
			$select[] = 'dSupply.provider AS provider';
			$groupBy[] = 'dSupply.provider';
			$orderBy[] = 'dSupply.provider';
		}
		if(isset($group['placement']) && $group['placement']){
			$select[] = 't.D_Supply_id AS placement';
			$groupBy[] = 't.D_Supply_id';
			$orderBy[] = 't.D_Supply_id';
		}
		if(isset($group['pubid']) && $group['pubid']){
			$select[] = 't.pubid AS pubid';
			$groupBy[] = 't.pubid';
			$orderBy[] = 't.pubid';
		}
		if(isset($group['country']) && $group['country']){
			$select[] = 'dGeoLocation.country AS country';
			$groupBy[] = 'dGeoLocation.country';
			$orderBy[] = 'dGeoLocation.country';
		}
		if(isset($group['connection_type']) && $group['connection_type']){
			$select[] = 'dGeoLocation.connection_type AS connection_type';
			$groupBy[] = 'dGeoLocation.connection_type';
			$orderBy[] = 'dGeoLocation.connection_type';
		}
		if(isset($group['carrier']) && $group['carrier']){
			$select[] = 'dGeoLocation.carrier AS carrier';
			$groupBy[] = 'dGeoLocation.carrier';
			$orderBy[] = 'dGeoLocation.carrier';
		}
		if(isset($group['device_type']) && $group['device_type']){
			$select[] = 'dUserAgent.device_type AS device_type';
			$groupBy[] = 'dUserAgent.device_type';
			$orderBy[] = 'dUserAgent.device_type';
		}
		if(isset($group['device_brand']) && $group['device_brand']){
			$select[] = 'dUserAgent.device_brand AS device_brand';
			$groupBy[] = 'dUserAgent.device_brand';
			$orderBy[] = 'dUserAgent.device_brand';
		}
		if(isset($group['device_model']) && $group['device_model']){
			$select[] = 'dUserAgent.device_model AS device_model';
			$groupBy[] = 'dUserAgent.device_model';
			$orderBy[] = 'dUserAgent.device_model';
		}
		if(isset($group['os_type']) && $group['os_type']){
			$select[] = 'dUserAgent.os_type AS os_type';
			$groupBy[] = 'dUserAgent.os_type';
			$orderBy[] = 'dUserAgent.os_type';
		}
		if(isset($group['os_version']) && $group['os_version']){
			$select[] = 'dUserAgent.os_version as os_version';
			$groupBy[] = 'dUserAgent.os_version';
			$orderBy[] = 'dUserAgent.os_version';
		}
		if(isset($group['browser_type']) && $group['browser_type']){
			$select[] = 'dUserAgent.browser_type AS browser_type';
			$groupBy[] = 'dUserAgent.browser_type';
			$orderBy[] = 'dUserAgent.browser_type';
		}
		if(isset($group['browser_version']) && $group['browser_version']){
			$select[] = 'dUserAgent.browser_version AS browser_version';
			$groupBy[] = 'dUserAgent.browser_version';
			$orderBy[] = 'dUserAgent.browser_version';
		}


		// SUM COLUMN

		if(isset($sum['impressions']) && $sum['impressions']){
			$select[] = 'FORMAT(COUNT(t.id),0) AS impressions';
			$orderBy[] = 'COUNT(t.id)';
		}
		if(isset($sum['unique_user']) && $sum['unique_user']){
			$select[] = 'FORMAT(COUNT(distinct t.unique_id),0) as unique_user';
			$orderBy[] = 'COUNT(distinct t.unique_id)';
		}
		if(isset($sum['revenue']) && $sum['revenue']){
			$select[] = 'FORMAT(SUM(dBid.revenue),2) as revenue';
			$orderBy[] = 'SUM(dBid.revenue)';
		}
		if(isset($sum['cost']) && $sum['cost']){
			$select[] = 'FORMAT(SUM(dBid.cost),2) as cost';
			$orderBy[] = 'SUM(dBid.cost)';
		}
		if(isset($sum['profit']) && $sum['profit']){
			$select[] = 'FORMAT(SUM(dBid.revenue)-SUM(dBid.cost),2) as profit';
			$orderBy[] = 'SUM(dBid.revenue)-SUM(dBid.cost)';
		}
		if(isset($sum['revenue_eCPM']) && $sum['revenue_eCPM']){
			$select[] = 'FORMAT(SUM(dBid.revenue) * 1000 / COUNT(t.id),2) as revenue_eCPM';
			$orderBy[] = 'SUM(dBid.revenue) * 1000 / COUNT(t.id)';
		}
		if(isset($sum['cost_eCPM']) && $sum['cost_eCPM']){
			$select[] = 'FORMAT(SUM(dBid.cost) * 1000 / COUNT(t.id),2) as cost_eCPM';
			$orderBy[] = 'SUM(dBid.cost) * 1000 / COUNT(t.id)';
		}
		if(isset($sum['profit_eCPM']) && $sum['profit_eCPM']){
			$select[] = 'FORMAT((SUM(dBid.revenue)-SUM(dBid.cost)) * 1000 / COUNT(t.id),2) as profit_eCPM';
			$orderBy[] = '(SUM(dBid.revenue)-SUM(dBid.cost)) * 1000 / COUNT(t.id)';
		}

		$criteria->with = array(
			'dBid',
			'dDemand',
			'dGeoLocation',
			'dSupply',
			'dUserAgent',
			);

		
		if(isset($select)) $criteria->select = $select;
		if(isset($groupBy)) $criteria->group  = implode(',', $groupBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> KHtml::pagination($request),
			'sort'=>array(
				'route' => 'stats/impressions',
				'params' => $request,
				'defaultOrder' => isset($orderBy) ? implode(',', $orderBy) : '',
				// 'attributes'   => $sort,
				),
		));
	}

	public function selectDistinct($column){
		$criteria = new CDbCriteria;
		$criteria->select = 'distinct '.$column;
		$criteria->order = $column;
		return CHtml::listData(
	        self::model()->findAll($criteria), $column, $column);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FImpressions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
