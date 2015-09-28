<?php

/**
 * This is the model class for table "conv_log".
 *
 * The followings are the available columns in table 'conv_log':
 * @property integer $id
 * @property string $tid
 * @property string $date
 * @property integer $campaigns_id
 * @property integer $clicks_log_id
 *
 * The followings are the available model relations:
 * @property Campaigns $campaign
 * @property ClicksLog $clicksLog
 */
class ConvLog extends CActiveRecord
{
	public $advertiser_id;
	public $conv;
	public $rate;

	public function macros()
	{
		return array(
			'{rate}' => $this->rate ? urlencode($this->rate) : '',
		);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'conv_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tid, campaigns_id, clicks_log_id', 'required'),
			array('campaigns_id, clicks_log_id', 'numerical', 'integerOnly'=>true),
			array('tid', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tid, date, campaigns_id, advertiser_id, clicks_log_id', 'safe', 'on'=>'search'),
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
			'clicksLog' => array(self::BELONGS_TO, 'ClicksLog', 'clicks_log_id'),
			'campaign' => array(self::BELONGS_TO, 'Campaigns', 'campaigns_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'          => 'ID',
			'tid'         => 'Tid',
			'date'        => 'Conv. Date',
			'campaigns_id' => 'Campaign',
			'clicks_log_id' => 'Clicks Log',
			'advertiser_id' => 'Advertiser'
			);
	}


	public function excel()
	{
		$criteria=new CDbCriteria;

		$criteria->with = array( 'campaign', 'campaign.bannerSizes' , 'campaign.opportunities.ios.advertisers', 'clicksLog' );
		$criteria->compare('advertisers.id', $this->advertiser_id, true);
		$criteria->compare('t.date',$this->date,true);

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

		$criteria->compare('id',$this->id);
		$criteria->compare('tid',$this->tid,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('campaigns_id',$this->campaigns_id);
		$criteria->compare('clicks_log_id',$this->clicks_log_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConvLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
}
