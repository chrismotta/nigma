<?php

/**
 * This is the model class for table "imp_log".
 *
 * The followings are the available columns in table 'imp_log':
 * @property integer $id
 * @property integer $tags_id
 * @property integer $placements_id
 * @property string $date
 * @property string $pubid
 * @property string $tid
 * @property string $ext_tid
 * @property string $server_ip
 * @property string $user_agent
 * @property string $languaje
 * @property string $referer
 * @property string $ip_forwarded
 * @property string $country
 * @property string $city
 * @property string $carrier
 * @property string $browser
 * @property string $browser_version
 * @property string $device_type
 * @property string $device
 * @property string $device_model
 * @property string $os
 * @property string $os_version
 * @property string $app
 *
 * The followings are the available model relations:
 * @property Placements $placements
 * @property Tags $tags
 */
class ImpLog extends CActiveRecord
{

	public $time;
	public $advertiser;
	public $traffic_source;
	public $imp;
	public $unique_usr;
	public $spend;
	public $profit;
	public $reCPM;
	public $ceCPM;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'imp_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date', 'required'),
			array('tags_id, placements_id', 'numerical', 'integerOnly'=>true),
			array('pubid, tid, ext_tid, server_ip, user_agent, languaje, ip_forwarded, country, city, carrier, browser, browser_version, device, device_model, os, os_version, app', 'length', 'max'=>255),
			array('device_type', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, advertiser, tags_id, traffic_source, placements_id, date, time, pubid, tid, ext_tid, server_ip, user_agent, languaje, referer, ip_forwarded, country, city, carrier, browser, browser_version, device_type, device, device_model, os, os_version, app, imp, unique_usr, revenue, spend, profit, reCPM, ceCPM', 'safe', 'on'=>'search'),
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
			'placements' => array(self::BELONGS_TO, 'Placements', 'placements_id'),
			'tags' => array(self::BELONGS_TO, 'Tags', 'tags_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'              => 'ID',
			'tags_id'         => 'Tag',
			'placements_id'   => 'Placement',
			'date'            => 'Date',
			'pubid'           => 'Pubid',
			'tid'             => 'Tid',
			'ext_tid'         => 'Ext Tid',
			'server_ip'       => 'Server Ip',
			'user_agent'      => 'User Agent',
			'languaje'        => 'Languaje',
			'referer'         => 'Referer',
			'ip_forwarded'    => 'Ip Forwarded',
			'country'         => 'Country',
			'city'            => 'City',
			'carrier'         => 'Carrier',
			'browser'         => 'Browser',
			'browser_version' => 'Browser Ver.',
			'device_type'     => 'Dev. Type',
			'device'          => 'Dev. Brand',
			'device_model'    => 'Dev. Model',
			'os'              => 'Os',
			'os_version'      => 'Os Ver.',
			'app'             => 'App',
			'unique_usr'	  => 'Unique Users',
			'revenue'		  => 'Revenue',
			'cost'	    	  => 'Cost',
			'reCPM'	    	  => 'Rev. eCPM',
			'ceCPM'	    	  => 'Cost eCPM',
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
		$criteria->addBetweenCondition('DATE(date)', $dateStart, $dateEnd, 'AND');

		// time
		
		$timeStart = date('H:i:00', strtotime($request['timeStart']));
		$timeEnd = date('H:i:59', strtotime($request['timeEnd']));
		$criteria->addBetweenCondition('TIME(date)', $timeStart, $timeEnd, 'AND');

		// 

		$group  = $request['group'];
		$sum    = $request['sum'];
		$filter = isset($request['filter']) ? $request['filter'] : null;

		$criteria->with = array(
			'tags',
			'tags.campaigns.opportunities.regions.financeEntities.advertisers',
			'placements',
			'placements.sites.providers',
			);
		
		// group by

		if($group['Date']){
			$select[] = 'DATE(t.date) AS date';
			$groupBy[] = 'DATE(t.date)';
			$orderBy[] = 'DATE(t.date)';
		}
		if($group['Time']){
			$select[] = 'TIME(t.date) AS time';
			$groupBy[] = 'TIME(t.date)';
			$orderBy[] = 'TIME(t.date)';
		}
		if($group['Advertiser']){
			$select[] = 'advertisers.name AS advertiser';
			$groupBy[] = 'advertisers.name';
			$orderBy[] = 'advertisers.name';
		}
		if($group['Tag']){
			$select[] = 't.tags_id';
			$groupBy[] = 't.tags_id';
			$orderBy[] = 't.tags_id';
		}
		if($group['TrafficSource']){
			$select[] = 'providers.name AS traffic_source';
			$groupBy[] = 'providers.name';
			$orderBy[] = 'providers.name';
		}
		if($group['Placement']){
			$select[] = 't.placements_id';
			$groupBy[] = 't.placements_id';
			$orderBy[] = 't.placements_id';
		}
		if($group['PubID']){
			$select[] = 't.pubid';
			$groupBy[] = 't.pubid';
			$orderBy[] = 't.pubid';
		}
		if($group['Country']){
			$select[] = 't.country';
			$groupBy[] = 't.country';
			$orderBy[] = 't.country';
		}
		if($group['Carrier']){
			$select[] = 't.carrier';
			$groupBy[] = 't.carrier';
			$orderBy[] = 't.carrier';
		}
		if($group['DeviceType']){
			$select[] = 't.device_type';
			$groupBy[] = 't.device_type';
			$orderBy[] = 't.device_type';
		}
		if($group['DeviceBrand']){
			$select[] = 't.device';
			$groupBy[] = 't.device';
			$orderBy[] = 't.device';
		}
		if($group['DeviceModel']){
			$select[] = 't.device_model';
			$groupBy[] = 't.device_model';
			$orderBy[] = 't.device_model';
		}
		if($group['OS']){
			$select[] = 't.os';
			$groupBy[] = 't.os';
			$orderBy[] = 't.os';
		}
		if($group['OSVersion']){
			$select[] = 't.os_version';
			$groupBy[] = 't.os_version';
			$orderBy[] = 't.os_version';
		}
		if($group['Browser']){
			$select[] = 't.browser';
			$groupBy[] = 't.browser';
			$orderBy[] = 't.browser';
		}
		if($group['BrowserVersion']){
			$select[] = 't.browser_version';
			$groupBy[] = 't.browser_version';
			$orderBy[] = 't.browser_version';
		}

		// sum

		if($sum['Imp']){
			$select[] = 'COUNT(t.id) AS imp';
			$orderBy[] = 'COUNT(t.id)';
		}
		if($sum['UniqueUsr']){
			$select[] = 'COUNT(distinct concat_ws(" ",server_ip,user_agent)) as unique_usr';
			$orderBy[] = 'COUNT(distinct concat_ws(" ",server_ip,user_agent))';
		}
		if($sum['Revenue']){
			$select[] = 'SUM(revenue) as revenue';
		}
		if($sum['Spend']){
			$select[] = 'SUM(cost) as spend';
			$orderBy[] = 'SUM(cost)';
		}
		if($sum['Profit']){
			$select[] = 'SUM(revenue)-SUM(cost) as profit';
			$orderBy[] = 'SUM(revenue)-SUM(cost)';
		}
		if($sum['Revenue_eCPM']){
			$select[] = 'FORMAT(SUM(revenue) * 1000 / COUNT(t.id),3) as reCPM';
			$orderBy[] = 'SUM(revenue) * 1000 / COUNT(t.id)';
		}
		if($sum['Cost_eCPM']){
			$select[] = 'FORMAT(SUM(cost) * 1000 / COUNT(t.id),3) as ceCPM';
			$orderBy[] = 'SUM(cost) * 1000 / COUNT(t.id)';
		}



		if(isset($select)) $criteria->select = $select;
		if(isset($groupBy)) $criteria->group  = implode(',', $groupBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> KHtml::pagination($request),
			'sort'=>array(
					'defaultOrder' => isset($orderBy) ? implode(',', $orderBy) : '',
					// 'route' => $this->createUrl('impLog'),
					'route' => 'impLog/index',
					'params' => $request,
					'attributes'   => array(
						'date' => array(
							'asc'  => 'DATE(date) ASC',
							'desc' => 'DATE(date) DESC',
					    ),
					    'time' => array(
							'asc'  => 'TIME(date) ASC',
							'desc' => 'TIME(date) DESC',
					    ),
					    'advertiser' => array(
							'asc'  => 'advertisers.name ASC',
							'desc' => 'advertisers.name DESC',
					    ),
					    'traffic_source' => array(
							'asc'  => 'providers.name ASC',
							'desc' => 'providers.name DESC',
					    ),
					    'imp' => array(
							'asc'  => 'COUNT(id) ASC',
							'desc' => 'COUNT(id) DESC',
					    ),
					    'unique_usr' => array(
							'asc'  => 'COUNT(distinct concat_ws(" ",server_ip,user_agent)) ASC',
							'desc' => 'COUNT(distinct concat_ws(" ",server_ip,user_agent)) DESC',
					    ),
					    '*',
					),
				),
		));
	}

	public function getFrequency(){

		$identifier = $this->server_ip . " " . $this->user_agent;

		$criteria=new CDbCriteria;
		$criteria->compare('tags_id', $this->tags_id);
		$criteria->compare('placements_id', $this->placements_id);
		$criteria->addCondition('DATE(date) = CURDATE()', 'AND');
		$criteria->addCondition('CONCAT_WS(" ",server_ip,user_agent)="'.$identifier.'"', 'AND');

		return $this->count($criteria);

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ImpLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// macros

	public function macros()
	{
		return array(
			'{pubid}' => isset($this->pubid) ? urlencode($this->pubid) : '',
			);
	}

	public function hasMacro($url)
	{
		preg_match('%\{[a-z \_]+\}%', $url, $match);
		return isset($match[0]) ? true : false;
	}
	
	public function replaceMacro($url)
	{	
		return str_replace(array_keys(self::macros()),array_values(self::macros()),$url);
	}

	public function selectDistinct($column){
		$criteria = new CDbCriteria;
		$criteria->select = 'distinct '.$column;
		$criteria->order = $column;
		return CHtml::listData(
	        ImpLog::model()->findAll($criteria), $column, $column);
	}
}
