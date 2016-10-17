<?php

/**
 * This is the model class for table "providers".
 *
 * The followings are the available columns in table 'providers':
 * @property integer $id
 * @property string $prefix
 * @property string $name
 * @property string $status
 * @property string $currency
 * @property integer $country_id
 * @property string $model
 * @property string $net_payment
 * @property string $deal
 * @property string $post_payment_amount
 * @property string $start_date
 * @property string $daily_cap
 * @property string $sizes
 * @property integer $has_s2s
 * @property integer $has_token
 * @property string $callback
 * @property string $placeholder
 * @property integer $has_token
 * @property string $commercial_name
 * @property string $state
 * @property string $zip_code
 * @property string $address
 * @property string $contact_com
 * @property string $email_com
 * @property string $contact_adm
 * @property string $email_adm
 * @property string $phone
 * @property string $entity
 * @property string $tax_id
 * @property integer $prospect
 * @property string $pdf_name
 * @property string $type
 * @property string $pdf_agreement
 * @property string $phone
 * @property string $foundation_date
 * @property string $foundation_place
 * @property string $bank_account_name
 * @property string $bank_account_number
 * @property string $branch
 * @property string $bank_name
 * @property string $swift_code
 * @property string $percent_off
 * @property string $url
 * @property integer $use_alternative_convention_name
 * @property integer $has_api
 * @property integer $use_vectors
 * @property string $query_string
 * @property string $token1
 * @property string $token2
 * @property string $token3
 * @property integer $publisher_percentage
 * @property string $rate
 * @property integer $users_id
 * @property integer $account_manager_id
 *
 * The followings are the available model relations:
 * @property Affiliates $affiliates
 * @property ApiCronLog[] $apiCronLogs
 * @property Campaigns[] $campaigns
 * @property ClicksLog[] $clicksLogs
 * @property DailyReport[] $dailyReports
 * @property Networks $networks
 * @property GeoLocation $country
 * @property Publishers $publishers
 * @property Vectors[] $vectors
 * @property ExternalProviderForm[] $externalProviderForms
 * @property Users $users
 * @property Users $accountManager
 * @property TransactionProviders[] $transactionProviders
 * 
 */
class Providers extends CActiveRecord
{
	public $idname;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'providers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// array('name, model, net_payment, start_date, commercial_name, state, zip_code, entity, tax_id', 'required'),
			array('type, name, model, net_payment, currency, entity', 'required'),
			array('country_id, has_s2s, has_token, prospect, use_alternative_convention_name, has_api, use_vectors, publisher_percentage, users_id, account_manager_id', 'numerical', 'integerOnly'=>true),
			array('prefix, sizes, placeholder', 'length', 'max'=>45),
			array('name, net_payment, commercial_name, state, zip_code, address, contact_com, email_com, contact_adm, email_adm, tax_id, pdf_name, pdf_agreement, phone, foundation_place, bank_account_name, bank_account_number, branch, bank_name, swift_code, url, conversion_profile, mcc_external_id', 'length', 'max'=>128),
			array('email_adm, email_com', 'email'),
			array('currency, model, entity', 'length', 'max'=>3),
			array('status', 'length', 'max'=>8),
			array('deal', 'length', 'max'=>12),
			array('post_payment_amount, daily_cap, rate', 'length', 'max'=>11),
			array('callback', 'length', 'max'=>255),
			array('foundation_date', 'safe'),
			array('callback', 'length', 'max'=>255),
			array('prefix','unique', 'message'=>'This prefix already exists.'),
			array('name','unique', 'message'=>'This name already exists.'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, prefix, name, status, currency, country_id, model, net_payment, deal, post_payment_amount, start_date, daily_cap, sizes, has_s2s, callback, placeholder, has_token, commercial_name, state, zip_code, address, contact_com, email_com, contact_adm, email_adm, entity, tax_id, prospect, pdf_name, pdf_agreement, phone, foundation_place, foundation_date, bank_account_name, bank_account_number, branch, bank_name, swift_code, percent_off, url, use_alternative_convention_name, has_api, use_vectors, query_string, token1, token2, token3, publisher_percentage, rate, users_id, account_manager_id', 'safe', 'on'=>'search'),
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
			'affiliates'            => array(self::HAS_ONE, 'Affiliates', 'providers_id'),
			'apiCronLogs'           => array(self::HAS_MANY, 'ApiCronLog', 'providers_id'),
			'campaigns'             => array(self::HAS_MANY, 'Campaigns', 'providers_id'),
			'transactionProviders'  => array(self::HAS_MANY, 'TransactionProviders', 'providers_id'),
			'clicksLogs'            => array(self::HAS_MANY, 'ClicksLog', 'providers_id'),
			'dailyReports'          => array(self::HAS_MANY, 'DailyReport', 'providers_id'),
			'networks'              => array(self::HAS_ONE, 'Networks', 'providers_id'),
			'country'               => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'publishers'            => array(self::HAS_ONE, 'Publishers', 'providers_id'),
			'vectors'               => array(self::HAS_MANY, 'Vectors', 'providers_id'),
			'externalProviderForms' => array(self::HAS_MANY, 'ExternalProviderForm', 'providers_id'),
			'users'                 => array(self::BELONGS_TO, 'Users', 'users_id'),
			'accountManager'        => array(self::BELONGS_TO, 'Users', 'account_manager_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                              => 'ID',
			'type'                            => 'Type',
			'prefix'                          => 'Prefix',
			'name'                            => 'Fantasy Name',
			'status'                          => 'Status',
			'currency'                        => 'Currency',
			'country_id'                      => 'Country',
			'model'                           => 'Model',
			'net_payment'                     => 'Net Payment',
			'deal'                            => 'Deal',
			'post_payment_amount'             => 'Pre Payment Amount',
			'start_date'                      => 'Start Date',
			'daily_cap'                       => 'Daily Cap',
			'sizes'                           => 'Sizes',
			'has_s2s'                         => 'Has s2s',
			'has_token'                       => 'Has Placeholder',
			'callback'                        => 'Callback',
			'placeholder'                     => 'Placeholder',
			'has_token'                       => 'Has Placeholder',
			'commercial_name'                 => 'Legal Name',
			'state'                           => 'State',
			'zip_code'                        => 'Zip Code',
			'address'                         => 'Address',
			'contact_com'                     => 'Commercial Contact',
			'email_com'                       => 'Commercial Email',
			'contact_adm'                     => 'Finance Contact',
			'email_adm'                       => 'Finance Email',
			'entity'                          => 'Entity',
			'tax_id'                          => 'Tax ID',
			'prospect'                        => 'Prospect',
			'pdf_name'                        => 'Pdf Name',
			'pdf_agreement'                   => 'Pdf Agreement',
			'phone'                           => 'Phone',
			'foundation_date'                 =>'Date of Constitution',
			'foundation_place'                =>'Place of foundation',
			'bank_account_name'               =>'Bank Account Name',
			'bank_account_number'             =>'Bank Account Number',
			'branch'                          =>'Branch Identifier', 
			'bank_name'                       =>'Bank Name',
			'swift_code'                      =>'Swift Code',
			'percent_off'                     => 'Percent Off',
			'url'                             => 'Url',
			'use_alternative_convention_name' => 'Use Alternative Convention Name',
			'has_api'                         => 'Has Api',
			'use_vectors'                     => 'Use Vectors',
			'query_string'                    => 'Query String',
			'token1'                          => 'Token1',
			'token2'                          => 'Token2',
			'token3'                          => 'Token3',
			'publisher_percentage'            => 'Publisher Percentage',
			'rate'                            => 'Rate',
			'users_id'                        => 'Users',
			'account_manager_id'              => 'Account Manager',
			'conversion_profile'              => 'Conversion Profile',
			'mcc_external_ide'                => 'MCC External ID',
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
	public function search($prospect=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type);
		$criteria->compare('prefix',$this->prefix,true);
		$criteria->compare('LOWER(name)', strtolower($this->name),true);
		$criteria->compare('status','Active',true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('net_payment',$this->net_payment,true);
		$criteria->compare('deal',$this->deal,true);
		$criteria->compare('post_payment_amount',$this->post_payment_amount,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('daily_cap',$this->daily_cap,true);
		$criteria->compare('sizes',$this->sizes,true);
		$criteria->compare('has_s2s',$this->has_s2s);
		$criteria->compare('has_token',$this->has_token);
		$criteria->compare('callback',$this->callback,true);
		$criteria->compare('placeholder',$this->placeholder,true);
		$criteria->compare('has_token',$this->has_token);
		$criteria->compare('commercial_name',$this->commercial_name,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('contact_com',$this->contact_com,true);
		$criteria->compare('email_com',$this->email_com,true);
		$criteria->compare('contact_adm',$this->contact_adm,true);
		$criteria->compare('email_adm',$this->email_adm,true);
		$criteria->compare('entity',$this->entity,true);
		$criteria->compare('tax_id',$this->tax_id,true);
		$criteria->compare('pdf_name',$this->pdf_name,true);
		$criteria->compare('pdf_agreement',$this->pdf_agreement,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('foundation_date',$this->foundation_date,true);
		$criteria->compare('foundation_place',$this->foundation_place,true);
		$criteria->compare('bank_account_name',$this->bank_account_name,true);
		$criteria->compare('bank_account_number',$this->bank_account_number,true);
		$criteria->compare('branch',$this->branch,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('swift_code',$this->swift_code,true);
		$criteria->compare('percent_off',$this->percent_off,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('use_alternative_convention_name',$this->use_alternative_convention_name);
		$criteria->compare('has_api',$this->has_api);
		$criteria->compare('use_vectors',$this->use_vectors);
		$criteria->compare('query_string',$this->query_string,true);
		$criteria->compare('token1',$this->token1,true);
		$criteria->compare('token2',$this->token2,true);
		$criteria->compare('token3',$this->token3,true);
		$criteria->compare('publisher_percentage',$this->publisher_percentage);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('account_manager_id',$this->account_manager_id);
		$criteria->compare('conversion_profile',$this->conversion_profile);
		$criteria->compare('mcc_external_id',$this->mcc_external_id);
		if($prospect)
			$criteria->addCondition('t.prospect='.$prospect);
		else
			$criteria->compare('prospect',$this->prospect);


		if( UserManager::model()->isUserAssignToRole('account_manager_admin') )
			$criteria->compare('type', array('Affiliate','Network','Google AdWords'));
		if( UserManager::model()->isUserAssignToRole('operation_manager') )
			$criteria->compare('type', array('Networks','Incent'));
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> KHtml::pagination(),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Providers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function isNetwork()
	{
		return Networks::model()->exists('providers_id=:id', array(':id' => $this->id));
	}


	public function isPublisher()
	{
		return Publishers::model()->exists('providers_id=:id', array(':id' => $this->id));
	}


	public function isAffiliate()
	{
		return Affiliates::model()->exists('providers_id=:id', array(':id' => $this->id));
	}


	public function getType()
	{
		switch($this->type){
			case 'Affiliate':
			$return = 1;
			break;
			case 'Network':
			$return = 2;
			break;
			case 'Publisher':
			$return = 3;
			break;
			case 'Google Adwords':
			$return = 4;
			break;
			default:
			$return = NULL;
			break;

		}
		// if ($this->isAffiliate())
		// 	return 1;
		// if ($this->isNetwork())
		// 	return 2;
		// if ($this->isPublisher())
		// 	return 3;

		return $return;
	}

	public function getExternalUser($user_id){
		$model = self::model()->findByAttributes(array("users_id"=>$user_id));
		$name = isset($model) ? $model : null;
		return $name;
	}

	public function findByUser($id)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition('users_id='.$id);
		if($model = Self::model()->find($criteria))
			return $model;
	}

	public function findAllByType($type)
	{
		switch ($type) {
			case 1:
			$type = 'Affiliate';
				// return Affiliates::model()->with('providers')->findAll(array('order'=>'providers.name'));
			break;
			case 2:
			$type = 'Network';
				//return Networks::model()->with('providers')->findAll();
				// return Networks::model()->with(
				// 	array('providers'=>array('condition'=>'status = "Active"')))
				// 	->findAll(array('order'=>'providers.name'));
			break;
			case 3:
			$type = 'Publisher';
				// return Publishers::model()->with('providers')->findAll(array('order'=>'providers.name'));
			break;
			case 4:
			$type = 'Google Adwords';
				// return Publishers::model()->with('providers')->findAll(array('order'=>'providers.name'));
			break;
			default:
			$type = null;
			break;
		}
		
		if(isset($type)){
			$criteria = new CDbCriteria;
			$criteria->compare('type',$type);
			$criteria->compare('status','Active');
			$criteria->order = 'name';
			return Providers::model()->findAll($criteria);
		}else{
			return array();
		}
			
	}


	public function getAllTypes()
	{

		if( UserManager::model()->isUserAssignToRole('account_manager_admin') || UserManager::model()->isUserAssignToRole('account_manager_admin') ){
			return array(
				1 => 'Affiliates', 
				2 => 'Networks', 
				// 3 => 'Publishers',
				4 => 'Google Adwords',
			);
		}else{
			return array(
				1 => 'Affiliates', 
				2 => 'Networks', 
				3 => 'Publishers',
				4 => 'Google Adwords',
			);
		}
	}

	public function printType()
	{
		if(self::getType())
			return self::getAllTypes()[self::getType()];
		else
			return false;
	}

	public function getProviders($month,$year)
	{
		$query="select providers.id as id,
			providers.name as providers_name,
			providers.currency as currency,
			sum(t.clics) as clics,
			sum(t.imp) as imp,
			networks.percent_off as percent_off,
			SUM(t.spend) as spend,
			round(SUM(t.spend) * if(isnull(networks.percent_off),0,networks.percent_off),2) as off,
			SUM(t.spend) - round(SUM(t.spend) * if(isnull(networks.percent_off),0,networks.percent_off),2) as total 
			from daily_report t, providers
			left join networks on providers_id=providers.id
			left join publishers pu on pu.providers_id=providers.id
			left join affiliates a on a.providers_id=providers.id
			where t.date between '".$year."-".$month."-01' and '".$year."-".$month."-31'
			and t.providers_id=providers.id
			group by providers.id";
		$data   =array();		
		$totals =array();	
			
		$dataArray =array();		
		$providers=DailyReport::model()->findAllBySql($query);
		// $data['dataProvider'] = new CActiveDataProvider(new DailyReport, array(
		// 	'criteria'=>$criteria,
		// ));	

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


	public function getAffiliates($dateStart,$dateEnd)
	{
		$data    =array();
		$graphic =array();
		$affiliate_id = $this->id; 

		$i=0;
		if(date('Y-m-d', strtotime($dateStart))!=date('Y-m-d', strtotime('today')))
		{
			$end=date('Y-m-d', strtotime($dateEnd))==date('Y-m-d', strtotime('today'))? date('Y-m-d', strtotime('-1 day',strtotime($dateEnd))) : date('Y-m-d', strtotime($dateEnd));
			
			$sql="SELECT c.id,
				IF((d.conv_adv IS NOT NULL) OR (d.conv_api IS NOT NULL),
					ROUND(
						d.spend/
								IF(ISNULL(d.conv_adv),d.conv_api,d.conv_adv),2
					),
				c.external_rate) as rate,
				sum(
					IF(ISNULL(d.conv_adv), d.conv_api, d.conv_adv)
				) as conv,
				sum(d.clics) as clics,
				sum(IF(ISNULL(d.conv_adv), d.conv_api, d.conv_adv)) / sum(d.clics) as convrate,
				sum(d.spend) as spend,
				DATE(d.date) as date
				from daily_report d 
				inner join campaigns c on d.campaigns_id=c.id
				inner join providers p on c.providers_id=p.id 
				/* inner join affiliates a on a.providers_id=p.id */
				WHERE d.date BETWEEN :dateStart AND :dateEnd
				AND p.id = :affiliate
				group by c.id,DATE(d.date),ROUND(d.spend/IF(ISNULL(d.conv_adv),d.conv_api,d.conv_adv),2)";
			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(":dateStart", $dateStart, PDO::PARAM_STR);
			$command->bindParam(":dateEnd", $end, PDO::PARAM_STR);
			$command->bindParam(":affiliate", $affiliate_id, PDO::PARAM_INT);
			//$command->bindParam(":affiliate", $affiliate, PDO::PARAM_INT);
			
			$affiliates=$command->queryAll();
			
			foreach ($affiliates as $affiliate) {
				$data[$i]['id']       =$affiliate['id'];
				$data[$i]['rate']     =$affiliate['rate'];
				$data[$i]['conv']     =$affiliate['conv'];
				$data[$i]['spend']    =$affiliate['spend'];
				$data[$i]['clics']    =$affiliate['clics'];
				$data[$i]['convrate'] =$affiliate['convrate'];
				$data[$i]['date']     =$affiliate['date'];
				$data[$i]['name']     =Campaigns::getExternalName($affiliate['id']);

				isset($graphic[$affiliate['date']]['spend']) ? : $graphic[$affiliate['date']]['spend']=0;
				isset($graphic[$affiliate['date']]['clics']) ? : $graphic[$affiliate['date']]['clics']=0;
				isset($graphic[$affiliate['date']]['conv']) ? : $graphic[$affiliate['date']]['conv']=0;
				$graphic[$affiliate['date']]['conv']+=$affiliate['conv'];
				$graphic[$affiliate['date']]['clics']+=$affiliate['clics'];
				$graphic[$affiliate['date']]['spend']+=$affiliate['spend'];

				$i++;
			}
		}
		if(date('Y-m-d', strtotime($dateStart))==date('Y-m-d', strtotime('today')) || date('Y-m-d', strtotime($dateEnd))==date('Y-m-d', strtotime('today')))
		{
			$date=date('Y-m-d', strtotime('today'));
			
			// get general info, conv are gotten separated
			$sql="SELECT 
					c.id,
					c.external_rate as rate, 
					count(cl.id) as clics, 
					(count(l.id)*c.external_rate) as spend,
					count(l.id) / count(cl.id) as convrate,
					DATE(cl.date) as date
				from campaigns c
				left join clicks_log cl on cl.campaigns_id=c.id
				left join conv_log l on l.clicks_log_id=cl.id
				WHERE DATE(cl.date)=DATE(:date)
				AND c.providers_id = :affiliate
				group by c.id,DATE(cl.date)";
			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(":date", $date, PDO::PARAM_STR);
			$command->bindParam(":affiliate", $affiliate_id, PDO::PARAM_INT);
			
			$affiliates=$command->queryAll();

			// get conv count
			$sql="SELECT 
					c.id,
					count(l.id) as conv, 
					DATE(l.date) as date
				from campaigns c
				left join conv_log l on l.campaigns_id=c.id
				WHERE DATE(l.date)=DATE(:date)
				AND c.providers_id = :affiliate
				group by c.id,DATE(l.date)";
			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(":date", $date, PDO::PARAM_STR);
			$command->bindParam(":affiliate", $affiliate_id, PDO::PARAM_INT);
			$conv_count=$command->queryAll();

			foreach ($affiliates as $affiliate) {

				$data[$i]['conv'] = 0;
				foreach ($conv_count as $conv)
					if ($affiliate['id'] == $conv['id']) {
						$data[$i]['conv'] = $conv['conv'];
						break;
					}

				$data[$i]['id']       =$affiliate['id'];
				$data[$i]['rate']     =$affiliate['rate'];
				$data[$i]['spend']    =$affiliate['spend'];
				$data[$i]['clics']    =$affiliate['clics'];
				$data[$i]['convrate'] =$affiliate['convrate'];
				$data[$i]['date']     =$affiliate['date'];
				$data[$i]['name']     =Campaigns::getExternalName($affiliate['id']);		
				
				isset($graphic[$affiliate['date']]['spend']) ? : $graphic[$affiliate['date']]['spend']=0;
				isset($graphic[$affiliate['date']]['clics']) ? : $graphic[$affiliate['date']]['clics']=0;
				isset($graphic[$affiliate['date']]['conv']) ? : $graphic[$affiliate['date']]['conv']=0;
				$graphic[$affiliate['date']]['conv']+=$data[$i]['conv']; // FIX: use $data instead of $affiliates for setting conv
				$graphic[$affiliate['date']]['clics']+=$affiliate['clics'];
				$graphic[$affiliate['date']]['spend']+=$affiliate['spend'];

				$i++;
			}
		}
		$i=0;
		$totalGraphic=array();
		$totalGraphic['dates']=array();
		$totalGraphic['convs']=array();
		$totalGraphic['clics']=array();
		$totalGraphic['spends']=array();
		foreach ($graphic as $key => $value) {
			$totalGraphic['dates'][$i]  =$key;
			$totalGraphic['convs'][$i]  =$value['conv'];
			$totalGraphic['spends'][$i] =$value['spend'];
			$totalGraphic['clics'][$i]  =$value['clics'];
			$i++;
		}

		$filtersForm =new FiltersForm;
		if (isset($_GET['FiltersForm']))
		    $filtersForm->filters=$_GET['FiltersForm'];

		$filteredData=$filtersForm->filter($data);
		$result['dataProvider'] =  new CArrayDataProvider($filteredData, array(
		    'id'=>'affiliates',
		    'sort'=>array(
				'defaultOrder' => 'date DESC',
		        'attributes'=>array(
		             'id', 'rate', 'conv', 'spend', 'clics', 'convrate', 'date', 'name'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));
		$result['graphic'] = $totalGraphic;
		return $result;
	}
}
