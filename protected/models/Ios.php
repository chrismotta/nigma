<?php

/**
 * This is the model class for table "ios".
 *
 * The followings are the available columns in table 'ios':
 * @property integer $id
 * @property string $name
 * @property string $commercial_name
 * @property integer $prospect
 * @property string $address
 * @property integer $country_id
 * @property string $state
 * @property string $zip_code
 * @property string $phone
 * @property string $email$contact_com
 * @property string $email_com
 * @property string $contact_adm
 * @property string $email_adm
 * @property string $email_validation
 * @property string $currency
 * @property string $ret
 * @property string $tax_id
 * @property integer $commercial_id
 * @property string $entity
 * @property string $net_payment
 * @property integer $advertisers_id
 * @property string $pdf_name
 * @property integer $status
 * @property string $description
 *
 * The followings are the available model relations:
 * @property GeoLocation $country
 * @property Advertisers $advertisers
 * @property Users $commercial
 * @property Opportunities[] $opportunities
 */
class Ios extends CActiveRecord
{

	public $country_name;
	public $com_name;
	public $com_lastname;
	public $advertiser_name;
	public $rate;
	public $conv;
	public $revenue;
	public $model;
	public $buttons;
	public $name;
	public $email_validation;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ios';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, commercial_name, address, country_id, state, zip_code, currency, tax_id, contact_com, email_com, contact_adm, email_adm, commercial_id, entity, net_payment, advertisers_id', 'required'),
			array('prospect, country_id, commercial_id, advertisers_id', 'numerical', 'integerOnly'=>true),
			array('email_com, email_adm, email_validation','email'),
			array('name, commercial_name, address, state, zip_code, phone, contact_com, email_com, contact_adm, email_adm, pdf_name, ret, tax_id, pdf_name, net_payment', 'length', 'max'=>128),
			array('currency', 'length', 'max'=>6),
			array('entity', 'length', 'max'=>3),
			array('status', 'length', 'max'=>8),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, country_name, com_lastname, com_name, advertiser_name, name, address, status, country_id, state, zip_code, phone, contact_com, email_com, contact_adm, email_adm, currency, ret, tax_id, commercial_id, entity, net_payment, advertisers_id, pdf_name, description, prospect', 'safe', 'on'=>'search'),
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
			'country'       => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'advertisers'   => array(self::BELONGS_TO, 'Advertisers', 'advertisers_id'),
			'commercial'    => array(self::BELONGS_TO, 'Users', 'commercial_id'),
			'opportunities' => array(self::HAS_MANY, 'Opportunities', 'ios_id'),
			'iosValidation' => array(self::HAS_MANY, 'IosValidation', 'ios_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'               => 'ID',
			'name'             => 'Name',
			'commercial_name'  => 'Legal Name',
			'address'          => 'Address',
			'prospect'         => 'Prospect',
			'country_id'       => 'Country',
			'state'            => 'State',
			'zip_code'         => 'Zip Code',
			'phone'            => 'Phone',
			'contact_com'      => 'Com Contact Name',
			'email_com'        => 'Com Contact Email',
			'contact_adm'      => 'Adm Contact Name',
			'email_adm'        => 'Adm Contact Email',
			'currency'         => 'Currency',
			'ret'              => 'Retentions',
			'tax_id'           => 'Tax ID',
			'commercial_id'    => 'Commercial',
			'entity'           => 'Entity',
			'net_payment'      => 'Net Payment',
			'advertisers_id'   => 'Advertisers',
			'pdf_name'         => 'Pdf Name',
			// Custom attributes
			'country_name'     => 'Country',
			'com_name'         => 'Commercial',
			'com_lastname'     => 'Commercial',
			'advertiser_name'  => 'Advertiser',
			'rate'             => 'Rate',
			'conv'             => 'Conv.',
			'revenue'          => 'Revenue',
			'model'            => 'Model',
			'name'             => 'Name',
			'status'           => 'Status',
			'description'      => 'Description',
			'email_validation' => 'Email Validation',
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
	public function search($entity=NULL, $cat=NULL, $country_id=NULL)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('prospect',$this->prospect);
		$criteria->compare('t.commercial_name',$this->commercial_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('country_id',$country_id);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('contact_com',$this->contact_com,true);
		$criteria->compare('email_com',$this->email_com,true);
		$criteria->compare('contact_adm',$this->contact_adm,true);
		$criteria->compare('email_adm',$this->email_adm,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('ret',$this->ret,true);
		$criteria->compare('tax_id',$this->tax_id,true);
		$criteria->compare('commercial_id',$this->commercial_id);
		$criteria->compare('entity',$entity);
		$criteria->compare('net_payment',$this->net_payment,true);
		$criteria->compare('advertisers_id',$this->advertisers_id);
		$criteria->compare('pdf_name',$this->pdf_name,true);
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.email_validation',$this->email_validation,true);

		$criteria->with = array( 'advertisers', 'commercial', 'country');
		$criteria->compare('advertisers.name', $this->advertiser_name, true);
		$criteria->compare('advertisers.cat', $cat);
		$criteria->compare('commercial.name', $this->com_name, true);
		$criteria->compare('commercial.lastname', $this->com_lastname, true);
		$criteria->compare('country.name', $this->country_name, true);

		FilterManager::model()->addUserFilter($criteria, 'ios');

		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			'pagination' => array(
                'pageSize' => 30,
            ),
			'sort'       => array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'advertiser_name'=>array(
						'asc'  =>'advertisers.name',
						'desc' =>'advertisers.name DESC',
		            ),
		            'com_name'=>array(
						'asc'  =>'commercial.name',
						'desc' =>'commercial.name DESC',
		            ),
		            'com_lastname'=>array(
						'asc'  =>'commercial.lastname',
						'desc' =>'commercial.lastname DESC',
		            ),
					'country_name'=>array(
						'asc'  =>'country.name',
						'desc' =>'country.name DESC',
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
	 * @return Ios the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * [getClientsMulti description]
	 * @param  [type] $filters [description]
	 * @return [type]          [description]
	 */
	public function getClientsMulti($filters)
	{
		$month       = isset($filters['month']) ? $filters['month'] : null;
		$year        = isset($filters['year']) ? $filters['year'] : null;
		$status      = isset($filters['status']) ? $filters['status'] : null;
		$multi       = isset($filters['multi']) ? $filters['multi'] : false;
		$closed_deal = isset($filters['closed_deal']) ? $filters['closed_deal'] : false;

		#Instance of models to use
		$opportunitiesValidation =new OpportunitiesValidation;
		$iosValidation           =new IosValidation;
		$geoLocation             =new GeoLocation;
		$carriers                =new Carriers;
		$opportunities           =new Opportunities;

		#Declare arrays to use
		$totals_io =array();
		$totals    =array();
		$data      =array();

		// if($multi==true)
		// {
		// 	#Query to find clients with multi rate
		// 	$query=$this->makeClientsQuery($filters);
		// }
		// else // multirate = true
		// {
			$query=$this->makeClientsQuery($filters);
		// }
		$i=0;
		#If query find results
		if($dailys=DailyReport::model()->findAll($query)){
			#Save results to array group by io,carrier and date
			foreach ($dailys as $daily) {
				$opportunitie=Opportunities::model()->findByPk($daily->opp_id);
				if($status)
				{
					if($closed_deal)
					{
						if($status==='invoiced')
						{							
							if(!$opportunitiesValidation->checkValidation($daily->opp_id,$year.'-'.$month.'-01'))
							{
								continue;
							}
						}

					}
					else
					{
						if($status=='ok')
						{
							if($iosValidation->getStatusByIo($daily->io_id,$year.'-'.$month.'-01') !='Approved')
							{
								if($iosValidation->getStatusByIo($daily->io_id,$year.'-'.$month.'-01') !='Expired')
									continue;
							}
						}
						elseif ($status=="toinvoice") 
						{
							if(!$opportunitie->checkIsAbleInvoice() || $opportunitiesValidation->checkValidation($daily->opp_id,$year.'-'.$month.'-01'))
							{
								continue;
							}								
								
						}
						elseif ($status=='not_invoiced')
						{
							if($iosValidation->getStatusByIo($daily->io_id,$year.'-'.$month.'-01') =='Invoiced')
							{
								continue;
							}
						}
						else
						{
							if($iosValidation->getStatusByIo($daily->io_id,$year.'-'.$month.'-01') != $status)
							{
								continue;
							}
						}
					}
				}				
				$data[$i]['id']              =$daily->io_id;
				$data[$i]['name']            =$daily->commercial_name;
				$data[$i]['opportunitie']    =$opportunities->findByPk($daily->opp_id)->getVirtualName();
				$data[$i]['opportunitie_id'] =$daily->opp_id;						
				$data[$i]['product']         =$daily->product;
				$data[$i]['currency']        =$daily->currency;
				$data[$i]['entity']          =$daily->entity;
				$data[$i]['model']           =$daily->model;
				$data[$i]['carrier']         =$daily->carrier;				
				$data[$i]['mobileBrand']     =$carriers->getMobileBrandById($daily->carrier);
				$data[$i]['status_opp']      =$opportunitiesValidation->checkValidation($daily->opp_id,$year.'-'.$month.'-01');
				$data[$i]['country']         =$geoLocation->getNameFromId(Opportunities::model()->findByPk($daily->opp_id)->country_id);//acá está el country
				$data[$i]['status_io']       =$opportunitie->checkIsAbleInvoice();				
				$data[$i]['comment']         =$iosValidation->getCommentByIo($daily->io_id,$year.'-'.$month.'-01');				
				$data[$i]['date']            =$iosValidation->getDateByIo($daily->io_id,$year.'-'.$month.'-01');				
				$data[$i]['revenue']         =floatval($daily->revenue);
				$data[$i]['conv']            =round($daily->conversions,2);
				$data[$i]['rate']            =$daily->rate;		
				$data[$i]['multi']           =$multi==true ? 1 : 0;		
				$i++;		
			}

		}
		return $data;
	}
	
	/**
	 * [getClients description]
	 * @param  [type]  $month           [description]
	 * @param  [type]  $year            [description]
	 * @param  [type]  $entity          [description]
	 * @param  [type]  $io              [description]
	 * @param  [type]  $accountManager  [description]
	 * @param  [type]  $opportunitie_id [description]
	 * @param  [type]  $cat             [description]
	 * @param  [type]  $status          [description]
	 * @param  [type]  $group           [description]
	 * @param  boolean $closed_deal     [description]
	 * @return [type]                   [description]
	 */
	public function getClients($month,$year,$entity=null,$io=null,$accountManager=null,$opportunitie_id=null,$cat=null,$status=null,$group,$closed_deal=false,$advertiser=null)
	{
		$filters = array(
			'month'           =>$month,
			'year'            =>$year,
			'entity'          =>$entity,
			'io'              =>$io,
			'accountManager'  =>$accountManager,
			'opportunitie_id' =>$opportunitie_id,
			'categorie'       =>$cat,
			'status'          =>$status,	
			'multi'           =>true,		
			'closed_deal'     =>$closed_deal,		
			'advertiser'      =>$advertiser,		
			// 'country'         =>$country,
			// 'product'         =>$product,
			// 'model'           =>$model,
			);
		#Declare arrays to use
		$dailysNoMulti   =Ios::model()->getClientsMulti($filters);
		$filters['multi']=false;
		$dailysMulti     =Ios::model()->getClientsMulti($filters);
		$dailys          =array_merge($dailysNoMulti,$dailysMulti);
		
		if($group=='profile')
		{
			#Save results to array group by io,carrier and date
			$consolidated=$this->groupClientsByProfile($dailys);
		}
		else
		{
			$consolidated=$this->groupClientsByRate($dailys);
		}	

		$totals_consolidated =$this->getTotalsClients($dailys,$filters);
		$graphic             =$this->getGraphicClients($dailys,$filters);
		$totals_invoiced     =$totals_consolidated['totals_invoiced'];
		$totals_io           =$totals_consolidated['totals_io'];
		$totals              =$totals_consolidated['totals'];

		#Return clients, totals by io and totals
		$result = array(
			'data'            => $consolidated, 
			'totals_io'       => $totals_io, 
			'totals'          => $totals, 
			'totals_invoiced' => $totals_invoiced,
			'graphic'         => $graphic,
		);
		return $result;
	}

	/**
	 * [findByAdvertisers description]
	 * @param  [type] $advertiser [description]
	 * @return [type]             [description]
	 */
	public function findByAdvertisers($advertiser)
	{		
		$criteria = new CDbCriteria;
		$criteria->addCondition("advertisers_id=".$advertiser."");
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>false,
				'sort'=>array(
					'attributes'   =>array(
			            '*',
			        ),
			    ),

			));
	}

	/**
	 * Get Opportunities associated with this IO.
	 * @return [type] [description]
	 */
	public function getOpportunities()
	{
		return Opportunities::model()->findAll('ios_id=:iosid', array(':iosid' => $this->id));
	}

	/**
	 * [makeClientsQuery description]
	 * @param  [type] $filters [description]
	 * @return [type]          [description]
	 */
	private function makeClientsQuery($filters)
	{
		$month           = isset($filters['month']) ? $filters['month'] : NULL;
		$year            = isset($filters['year']) ? $filters['year'] : NULL;
		$entity          = isset($filters['entity']) ? $filters['entity'] : NULL;
		$io              = isset($filters['io']) ? $filters['io'] : NULL;
		$accountManager  = isset($filters['accountManager']) ? $filters['accountManager'] : NULL;
		$opportunitie_id = isset($filters['opportunitie_id']) ? $filters['opportunitie_id'] : NULL;
		$cat             = isset($filters['categorie']) ? $filters['categorie'] : NULL;
		$closed_deal     = isset($filters['closed_deal']) ? $filters['closed_deal'] : false;
		$isMulti     	 = isset($filters['multi']) ? $filters['multi'] : false;
		$advertiser   	 = isset($filters['advertiser']) ? $filters['advertiser'] : NULL;


		$criteria = new CDbCriteria;

		//
		// Define columns being selected
		//
		$criteria->select = array( // common columns for all opportunities
			'ios.id AS io_id',
			'ios.entity AS entity',
			'ios.currency AS currency',
			'ios.commercial_name AS commercial_name',
			'country.name AS country',
			'opportunities.id AS opp_id',
			'opportunities.model_adv AS model',
			'opportunities.product AS product',
		);

		// subquery that get the rate according with period's date
		$rate = "SELECT ov.rate 
				FROM opportunities_version ov
				WHERE ov.created_time <= '".$year."-".$month."-31' 
					AND ov.id = opportunities.id
				ORDER BY ov.created_time DESC
				LIMIT 0,1";

		if ($isMulti) { // add columns for multi opportunities
			$criteria->select = array_merge($criteria->select, array(
				'multiRates.carriers_id_carrier AS carrier',
				'multiRates.rate AS rate', 
				'SUM(multiRates.conv) AS conversions', 
				'SUM(multiRates.rate * multiRates.conv) AS revenue'
			));
		} else { // add columns for NO multi opportunities
			$criteria->select = array_merge($criteria->select, array(
				'opportunities.carriers_id AS carrier',
				'ROUND(
					IF( 
						ISNULL(('. $rate .')),
						('. $rate .'),
						t.revenue/ (
							CASE opportunities.model_adv
								WHEN \'CPA\' THEN IF(ISNULL(t.conv_adv),t.conv_api,t.conv_adv)
								WHEN \'CPM\' THEN IF(ISNULL(t.imp_adv),t.imp/1000,t.imp_adv/1000)
								WHEN \'CPC\' THEN t.clics
							END )
				), 2) AS rate',
				'SUM(
					CASE opportunities.model_adv
						WHEN \'CPA\' THEN IF(ISNULL(t.conv_adv),t.conv_api,t.conv_adv)
						WHEN \'CPM\' THEN IF(ISNULL(t.imp_adv),t.imp,t.imp_adv)
						WHEN \'CPC\' THEN t.clics
					END 
				) AS conversions',
				'SUM(t.revenue) AS revenue'
			));
		}
	

		//
		// Define relational query criterias
		//
		$criteria->join = '
				INNER JOIN campaigns ON campaigns.id=t.campaigns_id
				INNER JOIN opportunities ON opportunities.id=campaigns.opportunities_id
				INNER JOIN ios ON ios.id=opportunities.ios_id
				INNER JOIN advertisers ON advertisers.id=ios.advertisers_id
				';

		if ($isMulti) { // add relation for multi opportunities
			$criteria->join .= '
				INNER JOIN multi_rate multiRates ON multiRates.daily_report_id=t.id
				INNER JOIN carriers ON multiRates.carriers_id_carrier=carriers.id_carrier
				INNER JOIN geo_location country ON country.id_location=carriers.id_country
			';
		} else {
			$criteria->join .= '
				LEFT JOIN carriers ON opportunities.carriers_id=carriers.id_carrier
				LEFT JOIN geo_location country ON opportunities.country_id=country.id_location
			';
		}


		//
		// Define conditions
		//
		// FIXME hacer validacion de ternaria en scope superior
		$criteria->compare('ios.entity', $entity ? $entity : NULL);
		$criteria->compare('ios.id', $io ? $io : NULL);
		$criteria->compare('ios.account_manager_id', $accountManager ? $accountManager : NULL);
		$criteria->compare('opportunities.id', $opportunitie_id ? $opportunitie_id : NULL);
		$criteria->compare('advertisers.cat', $cat ? $cat : NULL);
		$criteria->compare('advertisers.id', $advertiser ? $advertiser : NULL);
		

		if($closed_deal) {
			$criteria->compare('opportunities.closed_deal',1);
			$criteria->addCondition("DATE(opportunities.endDate) BETWEEN '".$year."-".$month."-01' AND '".$year."-".$month."-31'");
			$criteria->addCondition("DATE(t.date) BETWEEN DATE(opportunities.startDate) AND DATE(opportunities.endDate)");
		} else {
			$criteria->compare('opportunities.closed_deal',0);
			$criteria->addCondition("DATE(t.date) BETWEEN '".$year."-".$month."-01' AND '".$year."-".$month."-31'");			
		}

		if ($isMulti) {
			// $criteria->addCondition('multiRates.id IS NOT NULL');
			$criteria->compare('multiRates.conv', '>0');
		}
		$criteria->compare('t.revenue', '>0');
		$criteria->addCondition( // condition for filter only multi or NO multi opportunities
			($isMulti ? NULL : "NOT(") . 
				"ISNULL((". $rate . "))". 
			($isMulti ? NULL : ")")
		);


		//
		// Define how to group results
		//
		$criteria->group = 'ios.id, opportunities.id';
		if($isMulti) {
			$criteria->group .= ', multiRates.carriers_id_carrier, multiRates.rate';
		} else {
			$criteria->group .= ', opportunities.carriers_id,
					ROUND(
						IF(
							ISNULL(opportunities.rate),
							opportunities.rate,
							t.revenue/
							(
								CASE opportunities.model_adv
									when \'CPA\' THEN IF(ISNULL(t.conv_adv),t.conv_api,t.conv_adv)
									when \'CPM\' THEN IF(ISNULL(t.imp_adv),t.imp/1000,t.imp_adv/1000)
									when \'CPC\' THEN t.clics
								END 
							)
						),
					2)';
		}

		return $criteria;
	}

	/**
	 * [groupClientsByProfile description]
	 * @param  [type] $clients [description]
	 * @return [type]          [description]
	 */
	public function groupClientsByProfile($clients)
	{
		$data=array();
		$consolidated=array();
		foreach ($clients as $daily) {	
			$id      = $daily['id'];
			$carrier = $daily['carrier'];
			$product = $daily['product'];
			$rate    = $daily['rate'];
			$revenue = $daily['model']=='CPM' ? ($daily['conv']*$daily['rate'])/1000 : $daily['conv']*$daily['rate'];

			$data[$id][$carrier][$product][$rate]['id']              =$daily['id'];
			$data[$id][$carrier][$product][$rate]['name']            =$daily['name'];
			$data[$id][$carrier][$product][$rate]['opportunitie']    =$daily['opportunitie'];
			$data[$id][$carrier][$product][$rate]['opportunitie_id'] =$daily['opportunitie_id'];						
			$data[$id][$carrier][$product][$rate]['product']         =$daily['product'];
			$data[$id][$carrier][$product][$rate]['currency']        =$daily['currency'];
			$data[$id][$carrier][$product][$rate]['entity']          =$daily['entity'];
			$data[$id][$carrier][$product][$rate]['model']           =$daily['model'];
			$data[$id][$carrier][$product][$rate]['carrier']         =$daily['carrier'];				
			$data[$id][$carrier][$product][$rate]['mobileBrand']     =$daily['mobileBrand'];
			$data[$id][$carrier][$product][$rate]['status_opp']      =$daily['status_opp'];
			$data[$id][$carrier][$product][$rate]['country']         =$daily['country'];//aca esta el country
			$data[$id][$carrier][$product][$rate]['status_io']       =$daily['status_io'];				
			$data[$id][$carrier][$product][$rate]['comment']         =$daily['comment'];				
			#If isset, set arrays (conv,revenue) and sum
			isset($data[$id][$carrier][$product][$rate]['revenue']) ? : $data[$id][$carrier][$product][$rate]['revenue']=0;
			isset($data[$id][$carrier][$product][$rate]['conv']) ? : $data[$id][$carrier][$product][$rate]['conv']=0;
			$data[$id][$carrier][$product][$rate]['revenue']         +=$revenue;
			$data[$id][$carrier][$product][$rate]['conv']            +=$daily['conv'];
			$data[$id][$carrier][$product][$rate]['rate']            =$daily['rate'];

			#This array have totals
			isset($totals['revenue']) ?  : $totals['revenue'] =0;
			isset($totals['conv']) ?  : $totals['conv'] =0;
			$totals['revenue']+=$revenue;
			$totals['conv']+=$daily['conv'];
		}

		#Make array like CArrayDataProvider
		$consolidated=array();
		foreach ($data as $ios) {
			foreach ($ios as $products) {
				foreach ($products as $rates) {
					foreach ($rates as $rate) {
						$consolidated[]=$rate;
					}
				}
			}
		}

		return $consolidated;

	}

	/**
	 * [groupClientsByRate description]
	 * @param  [type] $clients [description]
	 * @return [type]          [description]
	 */
	public function groupClientsByRate($clients)
	{
		$data=array();
		$consolidated=array();
		foreach ($clients as $daily) {
			$id              = $daily['id'];
			$carrier         = $daily['carrier'];
			$product         = $daily['product'];
			$rate            = $daily['multi']==false ? $daily['rate'] : 'multi';
			$revenue         = $daily['model']=='CPM' ? ($daily['conv']*$daily['rate'])/1000 : $daily['conv']*$daily['rate'];
			$opportunitie_id =$daily['opportunitie_id'];

			$data[$id][$opportunitie_id][$rate]['id']              =$daily['id'];
			$data[$id][$opportunitie_id][$rate]['name']            =$daily['name'];
			$data[$id][$opportunitie_id][$rate]['opportunitie']    =$daily['opportunitie'];
			$data[$id][$opportunitie_id][$rate]['opportunitie_id'] =$daily['opportunitie_id'];						
			$data[$id][$opportunitie_id][$rate]['product']         =$daily['product'];
			$data[$id][$opportunitie_id][$rate]['currency']        =$daily['currency'];
			$data[$id][$opportunitie_id][$rate]['entity']          =$daily['entity'];
			$data[$id][$opportunitie_id][$rate]['model']           =$daily['model'];
			$data[$id][$opportunitie_id][$rate]['carrier']         =$daily['carrier'];				
			$data[$id][$opportunitie_id][$rate]['mobileBrand']     =$daily['mobileBrand'];
			$data[$id][$opportunitie_id][$rate]['status_opp']      =$daily['status_opp'];
			$data[$id][$opportunitie_id][$rate]['country']         =$daily['country'];
			$data[$id][$opportunitie_id][$rate]['status_io']       =$daily['status_io'];				
			$data[$id][$opportunitie_id][$rate]['comment']         =$daily['comment'];				
			$data[$id][$opportunitie_id][$rate]['date']            =$daily['date'];				
			$data[$id][$opportunitie_id][$rate]['multi']           =$daily['multi'];				
			#If isset, set arrays (conv,revenue) and sum
			isset($data[$id][$opportunitie_id][$rate]['revenue']) ? : $data[$id][$opportunitie_id][$rate]['revenue']=0;
			isset($data[$id][$opportunitie_id][$rate]['conv']) ? : $data[$id][$opportunitie_id][$rate]['conv']=0;
			$data[$id][$opportunitie_id][$rate]['revenue']         +=$revenue;
			$data[$id][$opportunitie_id][$rate]['conv']            +=$daily['conv'];
			$data[$id][$opportunitie_id][$rate]['rate']            =$daily['rate'];
			#Make array like CArrayDataProvider
			$consolidated=array();
			foreach ($data as $ios) {
				foreach ($ios as $opportunities) {
					foreach ($opportunities as $rate) {
						$consolidated[]=$rate;
					
					}
					
				}
			}
		}
		return $consolidated;
	}
	
	/**
	 * [getTotalsClients description]
	 * @param  [type] $clients [description]
	 * @return [type]          [description]
	 */
	private function getTotalsClients($clients,$filters)
	{
		$totals_io       =array();
		$totals_invoiced =array();
		$totals          =array();
		foreach ($clients as $daily) {

			$opportunitie=Opportunities::model()->findByPk($daily['opportunitie_id']);
			$revenue = $daily['model']=='CPM' ? ($daily['conv']*$daily['rate'])/1000 : $daily['conv']*$daily['rate'];

			isset($totals_invoiced[$daily['currency']]) ?  : $totals_invoiced[$daily['currency']] =0;
				if($opportunitie->closed_deal)
				{
					if($daily['status_opp'])
					$totals_invoiced[$daily['currency']]+=$opportunitie->getTotalCloseDeal();
				}
				else
				{
					if($daily['status_io']=='Invoiced')
					$totals_invoiced[$daily['currency']]+=$revenue;		
				}

			#This array have totals
			if(!isset($totals[$daily['currency']])){
				$totals[$daily['currency']]['revenue']           =0;
				$totals[$daily['currency']]['agency_commission'] =0;
			}
				if($opportunitie->closed_deal){
					$totals[$daily['currency']]['revenue']           +=$opportunitie->close_amount;
					$totals[$daily['currency']]['agency_commission'] +=$opportunitie->getTotalAgencyCommission();
				}
				else
					$totals[$daily['currency']]['revenue']+=$revenue;
				

			isset($totals_io[$daily['id']]) ?  : $totals_io[$daily['id']] =0;
				$totals_io[$daily['id']]+=$revenue;
		}
		$consolidated = array(
			'totals_io'       => $totals_io,
			'totals_invoiced' => $totals_invoiced,
			'totals'          => $totals,
			);

		return $consolidated;
	}	

	public function getGraphicClients($data,$filters)
	{
		
	}
	
}
