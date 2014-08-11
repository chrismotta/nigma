<?php

/**
 * This is the model class for table "clicks_log".
 *
 * The followings are the available columns in table 'clicks_log':
 * @property integer $id
 * @property integer $campaigns_id
 * @property integer $networks_id
 * @property string $tid
 * @property string $date
 * @property string $server_ip
 * @property string $user_agent
 * @property string $languaje
 * @property string $referer
 * @property string $ip_forwarded
 * @property string $country
 * @property string $city
 * @property string $carrier
 * @property string $devices_id
 * @property string $os
 * @property string $app
 *
 * The followings are the available model relations:
 * @property Campaigns $campaigns
 */
class ClicksLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clicks_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaigns_id, networks_id', 'required'),
			array('campaigns_id, networks_id', 'numerical', 'integerOnly'=>true),
			array('tid, server_ip, user_agent, languaje, referer, ip_forwarded, country, city, carrier, devices_id, os, app', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaigns_id, networks_id, tid, date, server_ip, user_agent, languaje, referer, ip_forwarded, country, city, carrier, devices_id, os, app', 'safe', 'on'=>'search'),
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
			'tid' => 'Tid',
			'date' => 'Date',
			'server_ip' => 'Server Ip',
			'user_agent' => 'User Agent',
			'languaje' => 'Languaje',
			'referer' => 'Referer',
			'ip_forwarded' => 'Ip Forwarded',
			'country' => 'Country',
			'city' => 'City',
			'carrier' => 'Carrier',
			'devices_id' => 'Devices',
			'os' => 'Os',
			'app' => 'App',
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
		$criteria->compare('campaigns_id',$this->campaigns_id);
		$criteria->compare('networks_id',$this->networks_id);
		$criteria->compare('tid',$this->tid,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('server_ip',$this->server_ip,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('languaje',$this->languaje,true);
		$criteria->compare('referer',$this->referer,true);
		$criteria->compare('ip_forwarded',$this->ip_forwarded,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('carrier',$this->carrier,true);
		$criteria->compare('devices_id',$this->devices_id,true);
		$criteria->compare('os',$this->os,true);
		$criteria->compare('app',$this->app,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClicksLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
