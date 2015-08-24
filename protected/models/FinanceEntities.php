<?php

/**
 * This is the model class for table "finance_entities".
 *
 * The followings are the available columns in table 'finance_entities':
 * @property integer $id
 * @property integer $advertisers_id
 * @property integer $commercial_id
 * @property string $name
 * @property string $commercial_name
 * @property integer $prospect
 * @property string $status
 * @property string $address
 * @property string $state
 * @property string $zip_code
 * @property string $phone
 * @property string $contact_com
 * @property string $email_com
 * @property string $contact_adm
 * @property string $email_adm
 * @property string $email_validation
 * @property string $currency
 * @property string $ret
 * @property string $tax_id
 * @property string $entity
 * @property string $net_payment
 * @property string $pdf_name
 * @property string $description
 * @property integer $country_id
 *
 * The followings are the available model relations:
 * @property Advertisers $advertisers
 * @property GeoLocation $country
 * @property Users $commercial
 * @property IosValidation[] $iosValidations
 * @property Regions[] $regions
 * @property TransactionCount[] $transactionCounts
 */
class FinanceEntities extends CActiveRecord
{
	public $country_name;
	public $com_name;
	public $com_lastname;
	public $advertiser_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'finance_entities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('advertisers_id, name, commercial_name, status, address, state, zip_code, currency, tax_id, entity, net_payment', 'required'),
			array('advertisers_id, commercial_id, prospect, country_id', 'numerical', 'integerOnly'=>true),
			array('name, commercial_name, address, state, zip_code, phone, contact_com, email_com, contact_adm, email_adm, email_validation, ret, tax_id, net_payment, pdf_name', 'length', 'max'=>128),
			array('status', 'length', 'max'=>8),
			array('currency, entity', 'length', 'max'=>3),
			array('description', 'length', 'max'=>255),
			array('email_adm, email_com, email_validation', 'email'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, advertisers_id, commercial_id, name, commercial_name, prospect, status, address, state, zip_code, phone, contact_com, email_com, contact_adm, email_adm, email_validation, currency, ret, tax_id, entity, net_payment, pdf_name, description, country_id, advertiser_name, country_name, com_name', 'safe', 'on'=>'search'),
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
			'advertisers' => array(self::BELONGS_TO, 'Advertisers', 'advertisers_id'),
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'commercial' => array(self::BELONGS_TO, 'Users', 'commercial_id'),
			'iosValidations' => array(self::HAS_MANY, 'IosValidation', 'finance_entities_id'),
			'regions' => array(self::HAS_MANY, 'Regions', 'finance_entities_id'),
			'transactionCounts' => array(self::HAS_MANY, 'TransactionCount', 'finance_entities_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'advertisers_id' => 'Advertisers',
			'commercial_id' => 'Commercial',
			'name' => 'Fantasy Name',
			'commercial_name' => 'Legal Name',
			'prospect' => 'Prospect',
			'status' => 'Status',
			'address' => 'Address',
			'state' => 'State',
			'zip_code' => 'Zip Code',
			'phone' => 'Phone',
			'contact_com' => 'Commercial Contact',
			'email_com' => 'Commercial Email',
			'contact_adm' => 'Finance Contact',
			'email_adm' => 'Finance Email',
			'email_validation' => 'Email Validation',
			'currency' => 'Currency',
			'ret' => 'WHT',
			'tax_id' => 'Tax ID',
			'entity' => 'Entity',
			'net_payment' => 'Net Payment',
			'pdf_name' => 'Pdf Name',
			'description' => 'Description',
			'country_id' => 'Country',
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
	public function search($advertisersId=null, $commercialId=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		if(isset($advertisersId))
			$criteria->compare('advertisers_id',$advertisersId);
		else
			$criteria->compare('advertisers_id',$this->advertisers_id);
		
		if(isset($commercialId))
			$criteria->compare('t.commercial_id',$commercialId);
		else
			$criteria->compare('t.commercial_id',$this->commercial_id);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('commercial_name',$this->commercial_name,true);
		$criteria->compare('prospect',$this->prospect);
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('LOWER(contact_com)',strtolower($this->contact_com),true);
		$criteria->compare('email_com',$this->email_com,true);
		$criteria->compare('LOWER(contact_adm)',strtolower($this->contact_adm),true);
		$criteria->compare('email_adm',$this->email_adm,true);
		$criteria->compare('email_validation',$this->email_validation,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('ret',$this->ret,true);
		$criteria->compare('tax_id',$this->tax_id,true);
		$criteria->compare('entity',$this->entity,true);
		$criteria->compare('net_payment',$this->net_payment,true);
		$criteria->compare('pdf_name',$this->pdf_name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('country_id',$this->country_id);

		$criteria->with=array('advertisers','country','commercial');
		$criteria->compare('advertisers.name', $this->advertiser_name, true);
		//$criteria->compare('advertisers.cat', $cat);
		$criteria->compare('commercial.name', $this->com_name, true);
		$criteria->compare('commercial.lastname', $this->com_name, true, 'OR');
		$criteria->compare('country.name', $this->country_name, true);
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
	 * @return FinanceEntities the static model class
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
			
				// FIXME se puede agregar este filtro a la query??? 
				// NO, tiene mayor logica que solo consultar una columna
				// 
				// FIXME refactorizar a una funcion aparte, donde se cree un nuevo array
				// de CModel dejando solo los CModel que cumplan con la condicion del status
				// requerida por el filtro
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
						elseif ($status=="toinvoice") 
						{
							if(!$opportunitie->checkIsAbleInvoice() || $opportunitiesValidation->checkValidation($daily->opp_id,$year.'-'.$month.'-01'))
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

				// Carga el status del IO dependiendo si es close deal o no.
				if($closed_deal)					
					$status_io=$opportunitie->checkIsAbleInvoice();
				else
					$status_io=$iosValidation->getStatusByIo($daily->io_id,$year.'-'.$month.'-01');

				// FIXME se puede modificar el CModel q esta guardado en $daily para agregar
				// los campos o modificar los necesarios??
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
				$data[$i]['status_io']       =$status_io;		
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
			);
		#Declare arrays to use
		// Obtiene un array asociativo (con id incremental) que corresponde a un CModel 
		// custom proveniente de la query de makeClientsQuery y modificado dentro de la
		// logica de getClientsMulti
		$dailysNoMulti   =FinanceEntities::model()->getClientsMulti($filters);
		$filters['multi']=false;
		$dailysMulti     =FinanceEntities::model()->getClientsMulti($filters);
		$dailys          =array_merge($dailysNoMulti,$dailysMulti);

		if($group=='profile')
		{
			$groupBy=array('io','carrier','product','rate');
		}
		if($group=='io')
		{
			$groupBy=array('io');
		}
		else
		{
			$groupBy=array('io','opportunitie','multi');
		}	

		$consolidated=$this->groupClients($dailys,$groupBy);


		$totals_consolidated =$this->getTotalsClients($dailys,$filters);
		// $totals_invoiced     =$totals_consolidated['totals_invoiced'];
		$totals_io           =$totals_consolidated['totals_io'];
		$totals              =$totals_consolidated['totals'];

		#Return clients, totals by io and totals
		$result = array(
			'data'            => $consolidated, 
			'totals_io'       => $totals_io, 
			'totals'          => $totals, 
			// 'totals_invoiced' => $totals_invoiced
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
		return Opportunities::model()->findAll('regions.finance_entities_id=:iosid', array(':iosid' => $this->id));
	}

	/**
	 * Create CDbCriteria base on @param $filters
	 * 
	 * @param  array() 		$filters
	 * @return DcbCriteria
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
			'finance_entities.id AS io_id',
			'finance_entities.entity AS entity',
			'finance_entities.currency AS currency',
			'finance_entities.commercial_name AS commercial_name',
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
				INNER JOIN regions ON regions.id=opportunities.regions_id
				INNER JOIN finance_entities ON finance_entities.id=regions.finance_entities_id
				INNER JOIN advertisers ON advertisers.id=finance_entities.advertisers_id
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
		$criteria->compare('finance_entities.entity', $entity ? $entity : NULL);
		$criteria->compare('finance_entities.id', $io ? $io : NULL);
		$criteria->compare('opportunities.account_manager_id', $accountManager ? $accountManager : NULL);
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
		$criteria->group = 'finance_entities.id, opportunities.id';
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
	 * [groupClientsByRate description]
	 * @param  [type] $clients [description]
	 * @param  array  $group   possible element for the array: 
	 *                         		'io',
	 *                           	'carrier',
	 *                            	'product',
	 *                             	'rate',
	 *                             	'multi',
	 *                             	'revenue',
	 *                             	'opportunitie',
	 *                              'country',
	 * @return [type]          [description]
	 */
	public function groupClients($clients,$group)
	{
		$data=array();
		$consolidated=array();
		foreach ($clients as $daily) {
			$io           = $daily['id'];
			$carrier      = $daily['carrier'];
			$product      = $daily['product'];
			$rate         = $daily['rate'];
			$multi        = $daily['multi'] == false ? $daily['rate'] : 'multi';
			$revenue      = $daily['model']=='CPM' ? ($daily['conv']*$daily['rate'])/1000 : $daily['conv']*$daily['rate'];
			$opportunitie = $daily['opportunitie_id'];
			$country 	  = $daily['country'];


			$groupBy='';
			foreach($group as $var)
			{
				// double $ evaluate the content of $var. 
				// 		Ej: 
				// 			$rate = 10
				// 			$var = 'rate'
				// 			$$var = $rate = 10
			 	$groupBy.='['.$$var.']';
			}


			$data[$groupBy]['id']              =$daily['id'];
			$data[$groupBy]['name']            =$daily['name'];
			$data[$groupBy]['opportunitie']    =$daily['opportunitie'];
			$data[$groupBy]['opportunitie_id'] =$daily['opportunitie_id'];						
			$data[$groupBy]['product']         =$daily['product'];
			$data[$groupBy]['currency']        =$daily['currency'];
			$data[$groupBy]['entity']          =$daily['entity'];
			$data[$groupBy]['model']           =$daily['model'];
			$data[$groupBy]['carrier']         =$daily['carrier'];				
			$data[$groupBy]['mobileBrand']     =$daily['mobileBrand'];
			$data[$groupBy]['status_opp']      =$daily['status_opp'];
			$data[$groupBy]['country']         =$daily['country'];
			$data[$groupBy]['status_io']       =$daily['status_io'];				
			$data[$groupBy]['comment']         =$daily['comment'];				
			$data[$groupBy]['date']            =$daily['date'];				
			$data[$groupBy]['multi']           =$daily['multi'];				
			#If isset, set arrays (conv,revenue) and sum
			isset($data[$groupBy]['revenue']) ? : $data[$groupBy]['revenue']=0;
			isset($data[$groupBy]['conv']) ? : $data[$groupBy]['conv']=0;
			$data[$groupBy]['revenue']         +=$revenue;
			$data[$groupBy]['conv']            +=$daily['conv'];
			$data[$groupBy]['rate']            =$daily['rate'];
			#Make array like CArrayDataProvider
			$consolidated=array();
			foreach ($data as $financeEntity) {				
				$consolidated[]=$financeEntity;					
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
		$totals_io          =array();
		$totals_invoiced    =array();
		$totals             =array();
		$totalCountCurrency =array();
		$totalCount         =array();
		$totalCountInvoice         =array();
		$closed_deal        = isset($filters['closed_deal']) ? $filters['closed_deal'] : false;
		$month              = isset($filters['month']) ? $filters['month'] : NULL;
		$year               = isset($filters['year']) ? $filters['year'] : NULL;
		$transactions       =new TransactionCount;
		foreach ($clients as $daily) 
		{

			$opportunitie=Opportunities::model()->findByPk($daily['opportunitie_id']);
			$revenue = $daily['model']=='CPM' ? ($daily['conv']*$daily['rate'])/1000 : $daily['conv']*$daily['rate'];
				
			isset($totalCount[$daily['id']]) ? : $totalCount[$daily['id']]=0;

			
			$totalCount[$daily['id']]=$transactions->getTotalTransactions($daily['id'],$year.'-'.$month.'-01');			
			foreach ($totalCount as $key => $value) {
				$currency=FinanceEntities::model()->findByPk($key)->currency;
				isset($totalCountCurrency[$currency]) ? : $totalCountCurrency[$currency]=0;
				$totalCountCurrency[$currency]+=$value;

				isset($totalCountInvoice[$currency]) ? : $totalCountInvoice[$currency]=0;
				if($daily['status_io']=='Invoiced'){
					$totalCountInvoice[$currency]+=$value;
					
				}
			}



			#This array have totals
			if(!isset($totals[$daily['currency']]))
			{
				$totals[$daily['currency']]['revenue']           =0;
				$totals[$daily['currency']]['invoiced']          =0;
				$totals[$daily['currency']]['agency_commission'] =0;
				$totals[$daily['currency']]['transaction']       =0;
				$totals[$daily['currency']]['transaction_invoiced']       =0;
			}
			if($closed_deal)
			{
				$totals[$daily['currency']]['revenue']           +=$opportunitie->close_amount;
				$totals[$daily['currency']]['agency_commission'] +=$opportunitie->getTotalAgencyCommission();

				if($daily['status_opp'])
					$totals[$daily['currency']]['invoiced']+=$opportunitie->getTotalCloseDeal();
			}
			else
			{
				$totals[$daily['currency']]['revenue']     +=$revenue;
				$totals[$daily['currency']]['transaction'] +=$totalCountCurrency[$daily['currency']];
				$totals[$daily['currency']]['transaction_invoiced'] +=$totalCountInvoice[$daily['currency']];
				
				if($daily['status_io']=='Invoiced')
					$totals[$daily['currency']]['invoiced']+=$revenue;
			}



			isset($totals_io[$daily['id']]) ?  : $totals_io[$daily['id']] =0;
			$totals_io[$daily['id']]+=$revenue;
		}
		$consolidated = array(
			'totals_io'          => $totals_io,
			// 'totals_invoiced'    => $totals_invoiced,
			'totals'             => $totals,
			);

		return $consolidated;
	}	

}
