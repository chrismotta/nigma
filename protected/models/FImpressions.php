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
	public $advertiser;
	public $trafficSource;
	public $connectionType;
	public $country;
	public $osType;
	public $osVersion;
	public $revenue;
	public $cost;

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
			'pubid'            => 'Pubid',
			'ip_forwarded'     => 'Ip Forwarded',
			'referer_url'      => 'Referer Url',
			'referer_app'      => 'Referer App',
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
		$criteria->compare('D_Demand_id',$this->D_Demand_id);
		$criteria->compare('D_Supply_id',$this->D_Supply_id);
		$criteria->compare('D_GeoLocation_id',$this->D_GeoLocation_id);
		$criteria->compare('D_UserAgent_id',$this->D_UserAgent_id);
		$criteria->compare('date_time',$this->date_time,true);
		$criteria->compare('unique_id',$this->unique_id,true);
		$criteria->compare('pubid',$this->pubid,true);
		$criteria->compare('ip_forwarded',$this->ip_forwarded,true);
		$criteria->compare('referer_url',$this->referer_url,true);
		$criteria->compare('referer_app',$this->referer_app,true);

		$criteria->with = array(
			'dBid',
			'dDemand',
			'dGeoLocation',
			'dSupply',
			'dUserAgent',
			);

		$criteria->select = array(
			'id',
			'dDemand.advertiser as advertiser',
			'dSupply.provider as trafficSource',
			'dGeoLocation.connection_type as connectionType',
			'dGeoLocation.country as country',
			'dUserAgent.os_version as osVersion',
			'dUserAgent.os_type as osType',
			'FORMAT(dBid.revenue,4) as revenue',
			'FORMAT(dBid.cost,4) as cost',
			);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> KHtml::pagination(),
		));
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
