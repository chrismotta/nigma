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
 * @property string $end_date
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
 */
class Providers extends CActiveRecord
{
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
			array('name, model, net_payment, currency, entity', 'required'),
			array('country_id, has_s2s, has_token, prospect', 'numerical', 'integerOnly'=>true),
			array('prefix, sizes, placeholder', 'length', 'max'=>45),
			array('name, net_payment, commercial_name, state, zip_code, address, contact_com, email_com, contact_adm, email_adm, tax_id, pdf_name, pdf_agreement, phone, foundation_place, bank_account_name, bank_account_number, branch, bank_name, swift_code', 'length', 'max'=>128),
			array('email_adm, email_com', 'email'),
			array('currency, model, entity', 'length', 'max'=>3),
			array('status', 'length', 'max'=>8),
			array('deal', 'length', 'max'=>12),
			array('post_payment_amount, daily_cap', 'length', 'max'=>11),
			array('callback', 'length', 'max'=>255),
			array('end_date,foundation_date', 'safe'),
			array('callback', 'length', 'max'=>255),
			array('prefix','unique', 'message'=>'This prefix already exists.'),
			array('name','unique', 'message'=>'This name already exists.'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prefix, name, status, currency, country_id, model, net_payment, deal, post_payment_amount, start_date, end_date, daily_cap, sizes, has_s2s, callback, placeholder, has_token, commercial_name, state, zip_code, address, contact_com, email_com, contact_adm, email_adm, entity, tax_id, prospect, pdf_name, pdf_agreement, phone, foundation_place, foundation_date, bank_account_name, bank_account_number, branch, bank_name, swift_code', 'safe', 'on'=>'search'),
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
			'affiliates'   => array(self::HAS_ONE, 'Affiliates', 'providers_id'),
			'apiCronLogs'  => array(self::HAS_MANY, 'ApiCronLog', 'providers_id'),
			'campaigns'    => array(self::HAS_MANY, 'Campaigns', 'providers_id'),
			'transactionProviders'    => array(self::HAS_MANY, 'TransactionProviders', 'providers_id'),
			'clicksLogs'   => array(self::HAS_MANY, 'ClicksLog', 'providers_id'),
			'dailyReports' => array(self::HAS_MANY, 'DailyReport', 'providers_id'),
			'networks'     => array(self::HAS_ONE, 'Networks', 'providers_id'),
			'country'      => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'publishers'   => array(self::HAS_ONE, 'Publishers', 'providers_id'),
			'vectors'      => array(self::HAS_MANY, 'Vectors', 'providers_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                  => 'ID',
			'prefix'              => 'Prefix',
			'name'                => 'Fantasy Name',
			'status'              => 'Status',
			'currency'            => 'Currency',
			'country_id'          => 'Country',
			'model'               => 'Model',
			'net_payment'         => 'Net Payment',
			'deal'                => 'Deal',
			'post_payment_amount' => 'Pre Payment Amount',
			'start_date'          => 'Start Date',
			'end_date'            => 'End Date',
			'daily_cap'           => 'Daily Cap',
			'sizes'               => 'Sizes',
			'has_s2s'             => 'Has s2s',
			'has_token'           => 'Has Placeholder',
			'callback'            => 'Callback',
			'placeholder'         => 'Placeholder',
			'has_token'           => 'Has Placeholder',
			'commercial_name'     => 'Legal Name',
			'state'               => 'State',
			'zip_code'            => 'Zip Code',
			'address'             => 'Address',
			'contact_com'         => 'Commercial Contact',
			'email_com'           => 'Commercial Email',
			'contact_adm'         => 'Finance Contact',
			'email_adm'           => 'Finance Email',
			'entity'              => 'Entity',
			'tax_id'              => 'Tax ID',
			'prospect'            => 'Prospect',
			'pdf_name'            => 'Pdf Name',
			'pdf_agreement'       => 'Pdf Agreement',
			'phone'               => 'Phone',
			'foundation_date'     =>'Date of Constitution',
			'foundation_place'    =>'Place of foundation',
			'bank_account_name'   =>'Bank Account Name',
			'bank_account_number' =>'Bank Account Number',
			'branch'              =>'Branch Identifier', 
			'bank_name'           =>'Bank Name',
			'swift_code'          =>'Swift Code',
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
		$criteria->compare('prefix',$this->prefix,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('status','Active',true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('net_payment',$this->net_payment,true);
		$criteria->compare('deal',$this->deal,true);
		$criteria->compare('post_payment_amount',$this->post_payment_amount,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
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
		if($prospect)
			$criteria->addCondition('t.prospect='.$prospect);
		else
			$criteria->compare('prospect',$this->prospect);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
		if ($this->isAffiliate())
			return 1;
		if ($this->isNetwork())
			return 2;
		if ($this->isPublisher())
			return 3;
		return NULL;
	}


	public function findAllByType($type)
	{
		switch ($type) {
			case 1:
				return Affiliates::model()->with('providers')->findAll(array('order'=>'providers.name'));
			case 2:
				//return Networks::model()->with('providers')->findAll();
				return Networks::model()->with(
					array('providers'=>array('condition'=>'status = "Active"')))
					->findAll(array('order'=>'providers.name'));
			case 3:
				return Publishers::model()->with('providers')->findAll(array('order'=>'providers.name'));
		}
		return array();
	}


	public function getAllTypes()
	{
		return array(
			1 => 'Affiliates', 
			2 => 'Networks', 
			3 => 'Publishers',
		);
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
}
