<?php

/**
 * This is the model class for table "daily_publishers".
 *
 * The followings are the available columns in table 'daily_publishers':
 * @property integer $id
 * @property string $date
 * @property integer $placements_id
 * @property integer $country_id
 * @property integer $devices_id
 * @property string $ad_request
 * @property string $imp_exchange
 * @property string $imp_publishers
 * @property string $imp_passback
 * @property string $imp_count
 * @property string $revenue
 * @property string $spend
 * @property string $profit
 * @property string $profit_percent
 * @property string $eCPM
 * @property string $comment
 * @property integer $exchanges_id
 * @property integer $clicks
 *
 * The followings are the available model relations:
 * @property Exchanges $exchanges
 * @property Devices $devices
 * @property GeoLocation $country
 * @property Placements $placements
 */
class DailyPublishers extends CActiveRecord
{
	public $site;
	public $publisher;
    public $csvFile;
    public $impressions;
    public $countryName;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daily_publishers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// array('date, placements_id, exchanges_id', 'required'),
			array('date, exchanges_id', 'required'),
			array('placements_id, country_id, devices_id, clicks, exchanges_id', 'numerical', 'integerOnly'=>true),
			array('ad_request, imp_exchange, imp_publishers, imp_passback, imp_count, revenue, spend, profit, profit_percent, eCPM', 'length', 'max'=>11),
			array('comment', 'length', 'max'=>255),
            array('csvFile', 'file', 'wrongType'=>'ERROR: Wrong File Type', 'types'=>'csv', 'allowEmpty'=>false, 'on'=>'dump'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site, publisher, date, placements_id, country_id, devices_id, ad_request, imp_exchange, imp_publishers, imp_passback, imp_count, impressions, clicks, revenue, spend, profit, profit_percent, eCPM, comment, exchanges_id', 'safe', 'on'=>'search'),
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
			'exchanges' => array(self::BELONGS_TO, 'Exchanges', 'exchanges_id'),
			'devices' => array(self::BELONGS_TO, 'Devices', 'devices_id'),
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'placements' => array(self::BELONGS_TO, 'Placements', 'placements_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'             => 'ID',
			'date'           => 'Date',
			'placements_id'  => 'Placements',
			'country_id'     => 'Country',
			'devices_id'     => 'Devices',
			'ad_request'     => 'Ad Request',
			'imp_exchange'   => 'Imp Exchange',
			'imp_publishers' => 'Imp Publishers',
			'imp_passback'   => 'Imp Passback',
			'imp_count'      => 'Imp Count',
			'clicks'         => 'Clicks',
			'revenue'        => 'Revenue',
			'spend'          => 'Spend',
			'profit'         => 'Profit',
			'profit_percent' => 'Profit Percent',
			'eCPM'           => 'eCPM',
			'comment'        => 'Comment',
			'exchanges_id'   => 'Exchanges',
			'csvFile'        => 'CSV File',
			'impressions'    => 'Impressions',
			'site'           => 'Site',
			'publisher'      => 'Publisher',
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
		$criteria->compare('t.date',$this->date,true);
		$criteria->compare('t.placements_id',$this->placements_id);
		$criteria->compare('t.country_id',$this->country_id);
		$criteria->compare('t.devices_id',$this->devices_id);
		$criteria->compare('t.ad_request',$this->ad_request,true);
		$criteria->compare('t.imp_exchange',$this->imp_exchange,true);
		$criteria->compare('t.imp_publishers',$this->imp_publishers,true);
		$criteria->compare('t.imp_passback',$this->imp_passback,true);
		$criteria->compare('t.imp_count',$this->imp_count,true);
		$criteria->compare('t.clicks',$this->clicks);
		$criteria->compare('t.revenue',$this->revenue,true);
		$criteria->compare('t.spend',$this->spend,true);
		$criteria->compare('t.profit',$this->profit,true);
		$criteria->compare('t.profit_percent',$this->profit_percent,true);
		$criteria->compare('t.eCPM',$this->eCPM,true);
		$criteria->compare('t.comment',$this->comment,true);
		$criteria->compare('t.impressions',$this->impressions);

		$criteria->with = array(
			'exchanges',
			'placements.sites',
			// 'placements.sites.publishersProviders.providers',
			'placements.sites.providers',
			);
		$criteria->compare('LOWER(exchanges.name)',strtolower($this->exchanges_id),true);
		$criteria->compare('LOWER(sites.name)',strtolower($this->site),true);
		$criteria->compare('LOWER(providers.name)',strtolower($this->publisher),true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		    'pagination'=>array(
		        'pageSize'=>50,
		    ),
			'sort'=>array(
				'defaultOrder' => 't.date DESC, t.placements_id ASC, t.exchanges_id ASC',
				'attributes'   =>array(
		            // Adding all the other default attributes
		            'site' => array(
						'asc'  =>'sites.name',
						'desc' =>'sites.name DESC',
		            ),
		            'publisher' => array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            '*',
		        ),
		    ),

		));
	}

	public function publisherSearch($modelData, $totals=false, $report=false){

		$criteria = new CDbCriteria;
		// Related search criteria items added (use only table.columnName)
		$criteria->with = array( 
			'placements',
			'placements.sites',
			'placements.sites.providers',
			'placements.sizes',
			'country',
			// 'placements.sites.publishersProviders',
		);

		if($modelData['publisherID']=='all'){
			$criteria->compare('exchanges_id', 2);//smaato
		}else{
			$criteria->compare('sites.providers_id', $modelData['publisherID']);
		}
		
		if ( $modelData['dateStart'] != NULL && $modelData['dateEnd'] != NULL ) {
			$criteria->compare('date','>=' . date('Y-m-d', strtotime($modelData['dateStart'])));
			$criteria->compare('date','<=' . date('Y-m-d', strtotime($modelData['dateEnd'])));
		}

		$rs_perc  = '(SELECT publisher_percentage FROM placements_has_exchanges WHERE placements_id = t.placements_id AND exchanges_id = t.exchanges_id)';
		$croupier = '(SELECT exchanges_id FROM placements_has_exchanges WHERE placements_id = t.placements_id AND step = 1)';
		$ajillion_id = 1;//FIX THIS HARDCODE!!!!!

		if($report)
			$sel_ad_request  = 'SUM( ad_request )';
		else
			$sel_ad_request  = 'SUM( IF(exchanges_id='.$croupier.',ad_request,0) )';
	
		$sel_impressions = 'SUM(imp_exchange) + SUM(imp_publishers)';
		$sel_revenue     = 'SUM( revenue )';
		$sel_profit      = 'SUM( IF(exchanges_id='.$ajillion_id.',revenue, revenue * '.$rs_perc.' /100) )';
		// $sel_revenue     = 'SUM( revenue * '.$rs_perc.' /100 )';
		
		$select = array(
			't.date',
			't.placements_id',
			't.country_id',
			$sel_ad_request . ' AS ad_request', 
			$sel_impressions . ' AS impressions', 
			$sel_revenue . ' AS revenue',
			$sel_profit . ' AS profit',
			// 'placements.sites.publishersProviders.name'
			);

		if(!$totals){
			if($modelData['publisherID']=='all'){
				$groupBy[] = 'providers.id';
				$orderBy[] = 'providers.name ASC';
			}else{
				// if(!$sum) $select[] = '';
				if($modelData['g_date']){
					$groupBy[] = 'date(t.date)';
					$orderBy[] = 't.date DESC';
				}
				if($modelData['g_placement']){
					$groupBy[] = 'placements_id';
					$orderBy[] = 'placements.name ASC';
				}
				if($modelData['g_country']){
					$groupBy[] = 't.country_id';
					$orderBy[] = 'country.name ASC';
				}
			}

			if(isset($groupBy)) $criteria->group  = implode(',', $groupBy);

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
					'defaultOrder' => isset($orderBy) ? implode(',', $orderBy) : '',
					'attributes'   => array(
						'placements.sites.name' => array(
							'asc'  => 'sites.name ASC, date ASC',
							'desc' => 'sites.name DESC, date ASC',
						        ),
						'placements.name' => array(
							'asc'  => 'placements.name ASC, date ASC',
							'desc' => 'placements.name DESC, date ASC',
						        ),
						'placements.sizes.size' => array(
							'asc'  => 'sizes.size ASC, date ASC',
							'desc' => 'sizes.size DESC, date ASC',
						        ),
						'country.name' => array(
							'asc'  => 'country.name ASC, date ASC',
							'desc' => 'country.name DESC, date ASC',
						        ),
						'ad_request' => array(
							'asc'  =>$sel_ad_request. ' ASC',
							'desc' =>$sel_ad_request. ' DESC',
						        ),
						'impressions' => array(
							'asc'  =>$sel_impressions. ' ASC',
							'desc' =>$sel_impressions. ' DESC',
						        ),
						'revenue' => array(
							'asc'  =>$sel_revenue. ' ASC',
							'desc' =>$sel_revenue. ' DESC',
						        ),
						'profit' => array(
							'asc'  =>$sel_profit. ' ASC',
							'desc' =>$sel_profit. ' DESC',
						        ),
			            // Adding all the other default attributes
			            'date',
					),
			    ),
			));
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DailyPublishers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
