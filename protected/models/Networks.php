<?php

/**
 * This is the model class for table "networks".
 *
 * The followings are the available columns in table 'networks':
 * @property integer $providers_id
 * @property string $percent_off
 * @property string $url
 * @property integer $use_alternative_convention_name
 * @property integer $has_api
 * @property integer $use_vectors
 * @property string $query_string
 * @property string $token1
 * @property string $token2
 * @property string $token3
 *
 * The followings are the available model relations:
 * @property Providers $providers
 */
class Networks extends CActiveRecord
{

	public $providers_name;
	public $providers_has_s2s;

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
			array('providers_id', 'required'),
			array('providers_id, use_alternative_convention_name, has_api, use_vectors', 'numerical', 'integerOnly'=>true),
			array('url', 'length', 'max'=>128),
			array('query_string', 'length', 'max'=>255),
			array('token1, token2, token3', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('providers_id, percent_off, url, use_alternative_convention_name, has_api, use_vectors, query_string, token1, token2, token3', 'safe', 'on'=>'search'),
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
			'providers' => array(self::BELONGS_TO, 'Providers', 'providers_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'providers_id'                    => 'ID',
			'providers_name'                  => 'Name',
			'providers_has_s2s'               => 'Has s2s',
			'percent_off'                     => 'Percent Off',
			'url'                             => 'Url',
			'use_alternative_convention_name' => 'Use Alternative Convention Name',
			'has_api'                         => 'Has Api',
			'use_vectors'                     => 'Use Vectors',
			'query_string'                    => 'Query String',
			'token1'                          => 'Token1',
			'token2'                          => 'Token2',
			'token3'                          => 'Token3',
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

		$criteria->with = array('providers');
		$criteria->compare('providers.status','Active',true);
		$criteria->addCondition('providers.prospect>1');

		$criteria->compare('providers_id',$this->providers_id);
		$criteria->compare('percent_off',$this->percent_off,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('use_alternative_convention_name',$this->use_alternative_convention_name);
		$criteria->compare('has_api',$this->has_api);
		$criteria->compare('use_vectors',$this->use_vectors);
		$criteria->compare('query_string',$this->query_string,true);
		$criteria->compare('token1',$this->token1,true);
		$criteria->compare('token2',$this->token2,true);
		$criteria->compare('token3',$this->token3,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize'=>30,
            ),
			'sort'     => array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'providers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            'providers_has_s2s'=>array(
						'asc'  =>'providers.has_s2s',
						'desc' =>'providers.has_s2s DESC',
		            ),
		            // Adding all the other default attributes
		            '*',
		        ),
		    ),
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
			'providers.id as id',
			'providers.name as providers_name',
			'providers.currency as currency',
			'sum(t.clics) as clics',
			'sum(t.imp) as imp',
			'networks.percent_off as percent_off',
			'SUM(t.spend) as spend',
			'round(SUM(t.spend) * if(isnull(networks.percent_off),0,networks.percent_off),2) as off',
			'SUM(t.spend) - round(SUM(t.spend) * if(isnull(networks.percent_off),0,networks.percent_off),2) as total',
			 );
		$criteria->with =array('providers', 'providers.networks');
		$criteria->addCondition('month(t.date)='.$month);
		$criteria->addCondition('year(t.date)='.$year);
		$criteria->group ='providers.id';
		$data   =array();		
		$totals =array();	
			
		$dataArray =array();		
		$providers=DailyReport::model()->findAll($criteria);
		$data['dataProvider'] = new CActiveDataProvider(new DailyReport, array(
			'criteria'=>$criteria,
		));	

		$i=0;
		foreach ($providers as $provider) {
			$dataArray[$i]['id']            =$provider->id;
			$dataArray[$i]['providers_name']=$provider->providers_name;
			$dataArray[$i]['currency']      =$provider->currency;
			$dataArray[$i]['clics']         =$provider->clics;
			$dataArray[$i]['imp']           =$provider->imp;
			$dataArray[$i]['percent_off']   =$provider->percent_off;
			$dataArray[$i]['spend']         =$provider->spend;
			$dataArray[$i]['off']           =$provider->off;
			$dataArray[$i]['transaction']   =TransactionProviders::model()->getTotalTransactions($provider->id,$year.'-'.$month.'-01');
			$dataArray[$i]['total']         =$provider->total;

			isset($totals[$provider->currency]['clics']) ? : $totals[$provider->currency]['clics']             =0;
			isset($totals[$provider->currency]['imp']) ? : $totals[$provider->currency]['imp']                 =0;
			isset($totals[$provider->currency]['spend']) ? : $totals[$provider->currency]['spend']             =0;
			isset($totals[$provider->currency]['off']) ? : $totals[$provider->currency]['off']                 =0;
			isset($totals[$provider->currency]['sub_total']) ? : $totals[$provider->currency]['sub_total']     =0;
			isset($totals[$provider->currency]['total_count']) ? : $totals[$provider->currency]['total_count'] =0;
			isset($totals[$provider->currency]['total']) ? : $totals[$provider->currency]['total']             =0;

			$totals[$provider->currency]['clics']       +=$provider->clics;
			$totals[$provider->currency]['imp']         +=$provider->imp;
			$totals[$provider->currency]['spend']       +=$provider->spend;
			$totals[$provider->currency]['off']         +=$provider->off;
			$totals[$provider->currency]['sub_total']   +=$provider->total;
			$totals[$provider->currency]['total_count'] +=TransactionProviders::model()->getTotalTransactions($provider->id,$year.'-'.$month.'-01');
			$totals[$provider->currency]['total']       +=TransactionProviders::model()->getTotalTransactions($provider->id,$year.'-'.$month.'-01')+$provider->total;
			$i++;
		}

		$filtersForm =new FiltersForm;
		$data['filtersForm']=$filtersForm;
		if (isset($_GET['FiltersForm']))
		    $filtersForm->filters=$_GET['FiltersForm'];
		$filteredData=$filtersForm->filter($dataArray);

		$data['arrayProvider']=new CArrayDataProvider($filteredData, array(
		    'id'=>'clients',
		    'sort'=>array(
		        'attributes'=>array(
		             'id', 'providers_name', 'currency', 'clics', 'imp', 'percent_off', 'spend','off', 'total','transaction'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));


		$i=0;
			
		$totalsdata=array();
		foreach ($totals as $key => $value) {
			$totalsdata[$i]['id']          =$i;
			$totalsdata[$i]['currency']    =$key;
			$totalsdata[$i]['clics']       =$value['clics'];
			$totalsdata[$i]['imp']         =$value['imp'];
			$totalsdata[$i]['spend']       =$value['spend'];
			$totalsdata[$i]['off']         =$value['off'];
			$totalsdata[$i]['total']       =$value['total'];
			$totalsdata[$i]['sub_total']   =$value['sub_total'];
			$totalsdata[$i]['total_count'] =$value['total_count'];
			$i++;
		}
		
		$data['totalsDataProvider'] = new CArrayDataProvider($totalsdata, array(
		    'id'=>'totals',
		    'sort'=>array(
		        'attributes'=>array(
		             'id','currency','clics', 'imp', 'spend', 'off', 'total','total_count','sub_total'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));
		return $data;
	}
}
