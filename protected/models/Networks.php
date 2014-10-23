<?php

/**
 * This is the model class for table "networks".
 *
 * The followings are the available columns in table 'networks':
 * @property integer $id
 * @property string $prefix
 * @property string $name
 * @property string $currency
 * @property string $percent_off
 * @property string $url
 * @property integer $has_api
 * @property integer $use_vectors
 * @property string $query_string
 * @property string $token1
 * @property string $token2
 * @property string $token3
 *
 * The followings are the available model relations:
 * @property ApiCronLog[] $apiCronLogs
 * @property Campaigns[] $campaigns
 * @property ClicksLog[] $clicksLogs
 * @property DailyReport[] $dailyReports
 * @property Vectors[] $vectors
 */
class Networks extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'networks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prefix, name, url', 'required'),
			array('has_api, use_vectors', 'numerical', 'integerOnly'=>true),
			array('prefix', 'length', 'max'=>45),
			array('name, url', 'length', 'max'=>128),
			array('currency', 'length', 'max'=>3),
			array('percent_off', 'length', 'max'=>5),
			array('query_string', 'length', 'max'=>255),
			array('token1, token2, token3', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prefix, name, currency, percent_off, url, has_api, use_vectors, query_string, token1, token2, token3', 'safe', 'on'=>'search'),
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
			'apiCronLogs' => array(self::HAS_MANY, 'ApiCronLog', 'networks_id'),
			'campaigns' => array(self::HAS_MANY, 'Campaigns', 'networks_id'),
			'clicksLogs' => array(self::HAS_MANY, 'ClicksLog', 'networks_id'),
			'dailyReports' => array(self::HAS_MANY, 'DailyReport', 'networks_id'),
			'vectors' => array(self::HAS_MANY, 'Vectors', 'networks_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'prefix' => 'Prefix',
			'name' => 'Name',
			'currency' => 'Currency',
			'percent_off' => 'Percent Off',
			'url' => 'Url',
			'has_api' => 'Has Api',
			'use_vectors' => 'Use Vectors',
			'query_string' => 'Query String',
			'token1' => 'Token1',
			'token2' => 'Token2',
			'token3' => 'Token3',
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
		$criteria->compare('prefix',$this->prefix,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('percent_off',$this->percent_off,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('has_api',$this->has_api);
		$criteria->compare('use_vectors',$this->use_vectors);
		$criteria->compare('query_string',$this->query_string,true);
		$criteria->compare('token1',$this->token1,true);
		$criteria->compare('token2',$this->token2,true);
		$criteria->compare('token3',$this->token3,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Networks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getProviders($month,$year)
	{
		// SELECT networks.id, 
		// networks.name,
		// networks.currency,
		// sum(daily_report.clics) as clics,
		// sum(daily_report.imp) as imp,
		// networks.percent_off, 
		// SUM(daily_report.spend) as subtotal,
		// round(SUM(daily_report.spend) * if(isnull(networks.percent_off),0,networks.percent_off),2) as off,
		// SUM(daily_report.spend) - round(SUM(daily_report.spend) * if(isnull(networks.percent_off),1,networks.percent_off),2) as total
		// FROM networks,daily_report 
		// WHERE daily_report.networks_id=networks.id
		// AND month(daily_report.date)=10
		// AND year(daily_report.date)=2014
		// group by networks.id;
		$criteria = new CDbCriteria;
		$criteria->select=array(
			'networks.id as id',
			'networks.name as network_name',
			'networks.currency as currency',
			'sum(t.clics) as clics',
			'sum(t.imp) as imp',
			'networks.percent_off as percent_off',
			'SUM(t.spend) as spend',
			'round(SUM(t.spend) * if(isnull(networks.percent_off),0,networks.percent_off),2) as off',
			'SUM(t.spend) - round(SUM(t.spend) * if(isnull(networks.percent_off),0,networks.percent_off),2) as total',
			 );
		$criteria->with =array('networks');
		$criteria->addCondition('month(t.date)='.$month);
		$criteria->addCondition('year(t.date)='.$year);
		$criteria->group ='networks.id';
		$data   =array();		
		$totals =array();		
		$providers=DailyReport::model()->findAll($criteria);
		foreach ($providers as $provider) {
			$id[]           =$provider->id;
			$network_name[] =$provider->network_name;
			$currency[]     =$provider->currency;
			$clics[]        =$provider->clics;
			$imp[]          =$provider->imp;
			$percent_off[]  =$provider->percent_off;
			$spend[]        =intval($provider->spend);
			$off[]          =$provider->off;
			$total[]        =$provider->total;

			isset($totals[$provider->currency]['clics']) ? : $totals[$provider->currency]['clics'] =0;
			isset($totals[$provider->currency]['imp']) ? : $totals[$provider->currency]['imp']     =0;
			isset($totals[$provider->currency]['spend']) ? : $totals[$provider->currency]['spend'] =0;
			isset($totals[$provider->currency]['off']) ? : $totals[$provider->currency]['off']     =0;
			isset($totals[$provider->currency]['total']) ? : $totals[$provider->currency]['total'] =0;

			$totals[$provider->currency]['clics']+=$provider->clics;
			$totals[$provider->currency]['imp']+=$provider->imp;
			$totals[$provider->currency]['spend']+=$provider->spend;
			$totals[$provider->currency]['off']+=$provider->off;
			$totals[$provider->currency]['total']+=$provider->total;
		}

		$i=0;
			
		$totalsData=array();
		foreach ($totals as $key => $value) {
			$totalsdata[$i]['id']       =$i;
			$totalsdata[$i]['currency'] =$key;
			$totalsdata[$i]['clics']    =$value['clics'];
			$totalsdata[$i]['imp']      =$value['imp'];
			$totalsdata[$i]['spend']    =$value['spend'];
			$totalsdata[$i]['off']      =$value['off'];
			$totalsdata[$i]['total']    =$value['total'];
			$i++;
		}
		
		$data['totalsDataProvider'] = new CArrayDataProvider($totalsdata, array(
		    'id'=>'totals',
		    'sort'=>array(
		        'attributes'=>array(
		             'id','currency','clics', 'imp', 'spend', 'off', 'total'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));

		$data['dataProvider'] = new CActiveDataProvider(new DailyReport, array(
			'criteria'=>$criteria,
		));		
		return $data;
	}
}
