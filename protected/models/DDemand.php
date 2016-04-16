<?php

/**
 * This is the model class for table "D_Demand".
 *
 * The followings are the available columns in table 'D_Demand':
 * @property integer $tag_id
 * @property string $advertiser
 * @property string $finance_entity
 * @property string $region
 * @property string $opportunity
 * @property string $campaign
 * @property string $tag
 * @property string $rate
 * @property integer $freq_cap
 * @property string $country
 * @property string $carrier
 * @property string $connection_type
 * @property string $device_type
 * @property string $device_brand
 * @property string $device_model
 * @property string $os_type
 * @property string $os_version
 *
 * The followings are the available model relations:
 * @property FImpressions[] $fImpressions
 */
class DDemand extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'D_Demand';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tag_id', 'required'),
			array('tag_id, freq_cap', 'numerical', 'integerOnly'=>true),
			array('advertiser, finance_entity, region, opportunity, campaign, tag', 'length', 'max'=>255),
			array('rate', 'length', 'max'=>11),
			array('country', 'length', 'max'=>2),
			array('carrier, device_brand, device_model, os_type, os_version', 'length', 'max'=>45),
			array('connection_type', 'length', 'max'=>4),
			array('device_type', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tag_id, advertiser, finance_entity, region, opportunity, campaign, tag, rate, freq_cap, country, carrier, connection_type, device_type, device_brand, device_model, os_type, os_version', 'safe', 'on'=>'search'),
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
			'fImpressions' => array(self::HAS_MANY, 'FImpressions', 'D_Demand_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tag_id' => 'Tag',
			'advertiser' => 'Advertiser',
			'finance_entity' => 'Finance Entity',
			'region' => 'Region',
			'opportunity' => 'Opportunity',
			'campaign' => 'Campaign',
			'tag' => 'Tag',
			'rate' => 'Rate',
			'freq_cap' => 'Freq Cap',
			'country' => 'Country',
			'carrier' => 'Carrier',
			'connection_type' => 'Connection Type',
			'device_type' => 'Device Type',
			'device_brand' => 'Device Brand',
			'device_model' => 'Device Model',
			'os_type' => 'Os Type',
			'os_version' => 'Os Version',
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

		$criteria->compare('tag_id',$this->tag_id);
		$criteria->compare('advertiser',$this->advertiser,true);
		$criteria->compare('finance_entity',$this->finance_entity,true);
		$criteria->compare('region',$this->region,true);
		$criteria->compare('opportunity',$this->opportunity,true);
		$criteria->compare('campaign',$this->campaign,true);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('freq_cap',$this->freq_cap);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('carrier',$this->carrier,true);
		$criteria->compare('connection_type',$this->connection_type,true);
		$criteria->compare('device_type',$this->device_type,true);
		$criteria->compare('device_brand',$this->device_brand,true);
		$criteria->compare('device_model',$this->device_model,true);
		$criteria->compare('os_type',$this->os_type,true);
		$criteria->compare('os_version',$this->os_version,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DDemand the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
