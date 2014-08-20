<?php

/**
 * This is the model class for table "daily_report".
 *
 * The followings are the available columns in table 'daily_report':
 * @property integer $id
 * @property integer $campaigns_id
 * @property integer $networks_id
 * @property integer $imp
 * @property integer $clics
 * @property integer $conv_api
 * @property integer $conv_adv
 * @property string $spend
 * @property integer $model
 * @property integer $value
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
			array('campaigns_id, networks_id, imp, clics, conv_api, spend, model, value, date', 'required'),
			array('campaigns_id, networks_id, imp, clics, conv_api, conv_adv, model, value, is_from_api', 'numerical', 'integerOnly'=>true),
			array('spend', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaigns_id, networks_id, network_name, campaign_name, account_manager, imp, clics, conv_api, conv_adv, spend, model, value, date, is_from_api', 'safe', 'on'=>'search'),
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
			'imp' => 'Impressions',
			'clics' => 'Clics',
			'conv_api' => 'Conv Api',
			'conv_adv' => 'Conv Adv',
			'spend' => 'Spend',
			'model' => 'Model',
			'value' => 'Value',
			'date' => 'Date',
			'is_from_api' => 'Is From Api',
			'network_name'	=>	'Network Name',
			'account_manager' => 'Account Manager',
			'campaign_name' => 'Campaign Name'
		);
	}


	public function excel()
	{
		$criteria=new CDbCriteria;

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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('campaigns_id',$this->campaigns_id);
		$criteria->compare('networks_id',$this->networks_id);
		$criteria->compare('imp',$this->imp);
		$criteria->compare('clics',$this->clics);
		$criteria->compare('conv_api',$this->conv_api);
		$criteria->compare('conv_adv',$this->conv_adv);
		$criteria->compare('spend',$this->spend,true);
		$criteria->compare('model',$this->model);
		$criteria->compare('value',$this->value);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('is_from_api',$this->is_from_api);

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
}
