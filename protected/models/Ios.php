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
	public function getClientsMulti($filters)
	{
		$month           = isset($filters['month']) ? $filters['month'] : null;
		$year            = isset($filters['year']) ? $filters['year'] : null;
		$entity          = isset($filters['entity']) ? $filters['entity'] : null;
		$io              = isset($filters['io']) ? $filters['io'] : null;
		$accountManager  = isset($filters['accountManager']) ? $filters['accountManager'] : null;
		$opportunitie_id = isset($filters['opportunitie_id']) ? $filters['opportunitie_id'] : null;
		$cat             = isset($filters['categorie']) ? $filters['categorie'] : null;
		$status          = isset($filters['status']) ? $filters['status'] : null;
		$multi           = isset($filters['multi']) ? $filters['multi'] : false;

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

		if($multi==true)
		{
			#Query to find clients with multi rate
			$query=
				"SELECT 
					i.id AS io_id,
					o.id AS opp_id,
					o.model_adv AS model,
					i.entity AS entity,
					i.currency AS currency,
					m.carriers_id_carrier AS carrier, 
					i.commercial_name AS commercial_name,
					g.name, 
					o.product AS product,
					m.rate as rate, 
					SUM(m.conv) AS conversions, 
					SUM(m.rate*m.conv) AS revenue
				
				FROM daily_report d 
				INNER JOIN campaigns c ON d.campaigns_id=c.id
				INNER JOIN opportunities o ON c.opportunities_id=o.id
				INNER JOIN ios i ON o.ios_id=i.id
				INNER JOIN advertisers a ON i.advertisers_id=a.id
				INNER JOIN multi_rate m ON d.id=m.daily_report_id
				INNER JOIN carriers ca ON m.carriers_id_carrier=ca.id_carrier
				INNER JOIN geo_location g ON ca.id_country=g.id_location

				WHERE d.date BETWEEN '".$year."-".$month."-01' AND '".$year."-".$month."-31'
					AND d.revenue>0
					AND m.conv>0
					AND ISNULL((
							SELECT ov.rate 
							FROM opportunities_version ov
							WHERE ov.created_time <= '".$year."-".$month."-31'
								AND ov.id = o.id
							ORDER BY ov.created_time DESC
							LIMIT 0,1 )) ";
			if($entity)	
				$query .= "AND i.entity='".$entity."' ";
			
			if($io)	
				$query .= "AND i.id=".$io." ";										
			
			if($accountManager)	
				$query .= "AND o.account_manager_id='".$accountManager."' ";										
			if($opportunitie_id)	
				$query .= "AND o.id=".$opportunitie_id." ";										
			if($cat)	
				$query .= "AND a.cat='".$cat."' ";										
			
			$query.= "GROUP BY i.id,o.id,m.carriers_id_carrier,m.rate";
		}
		else // multirate = true
		{
			$query=
				"SELECT 
					i.id as io_id,
					o.id AS opp_id,
					o.model_adv AS model,
					i.entity AS entity,
					i.currency AS currency,
					o.carriers_id AS carrier, 
					i.commercial_name AS commercial_name,
					g.name AS country,
					o.product AS product,
					ROUND(
						IF(
							ISNULL((
								SELECT ov.rate 
								FROM opportunities_version ov
								WHERE ov.created_time <= '".$year."-".$month."-31' 
									AND ov.id = o.id
								ORDER BY ov.created_time DESC
								LIMIT 0,1  
							)),
							(
								SELECT ov.rate 
								FROM opportunities_version ov
								WHERE ov.created_time <= '".$year."-".$month."-31'
									AND ov.id = o.id
								ORDER BY ov.created_time DESC
								LIMIT 0,1  
							),
							d.revenue/
							(
								CASE o.model_adv
									WHEN 'CPA' THEN IF(ISNULL(d.conv_adv),d.conv_api,d.conv_adv)
									WHEN 'CPM' THEN IF(ISNULL(d.imp_adv),d.imp/1000,d.imp_adv/1000)
									WHEN 'CPC' THEN d.clics
								END 
							)
						), 2) AS rate,
					SUM(
						CASE o.model_adv
							WHEN 'CPA' THEN IF(ISNULL(d.conv_adv),d.conv_api,d.conv_adv)
							WHEN 'CPM' THEN IF(ISNULL(d.imp_adv),d.imp,d.imp_adv)
							WHEN 'CPC' THEN d.clics
						END 
					) as conversions,
					SUM(d.revenue) AS revenue
				
				FROM daily_report d 
				INNER JOIN campaigns c ON d.campaigns_id=c.id
				INNER JOIN opportunities o ON c.opportunities_id=o.id
				INNER JOIN ios i ON o.ios_id=i.id
				INNER JOIN advertisers a ON i.advertisers_id=a.id
				LEFT JOIN carriers ca ON o.carriers_id=ca.id_carrier
				LEFT JOIN geo_location g ON o.country_id=g.id_location												
				WHERE d.date BETWEEN '".$year."-".$month."-01' AND '".$year."-".$month."-31'
					AND d.revenue>0
					AND NOT(ISNULL((
							SELECT ov.rate 
							FROM opportunities_version ov
							WHERE ov.created_time <= '".$year."-".$month."-31'
								AND ov.id = o.id
							ORDER BY ov.created_time DESC
							LIMIT 0,1  ))) ";
			#Add filters to query
			if($entity)	
				$query .= "AND i.entity='".$entity."' ";										
			if($io)	
				$query .= "AND i.id='".$io."' ";

			if($accountManager)	
				$query .= "AND o.account_manager_id='".$accountManager."' ";						
			if($opportunitie_id)	
				$query .= "AND o.id=".$opportunitie_id." ";

			if($cat)	
				$query .= "AND a.cat='".$cat."' ";

			$query .= "group by i.id,o.id,o.carriers_id,
					ROUND(
						IF(
							ISNULL(o.rate),
							o.rate,
							d.revenue/
							(
								CASE o.model_adv
									when 'CPA' THEN IF(ISNULL(d.conv_adv),d.conv_api,d.conv_adv)
									when 'CPM' THEN IF(ISNULL(d.imp_adv),d.imp/1000,d.imp_adv/1000)
									when 'CPC' THEN d.clics
								END 
							)
						),
					2)";
		}
		$i=0;
		#If query find results
		if($dailys=DailyReport::model()->findAllBySql($query)){
			#Save results to array group by io,carrier and date
			foreach ($dailys as $daily) {
				if($status)
				{
					if($status=='ok')
					{
						if($iosValidation->getStatusByIo($daily->io_id,$year.'-'.$month.'-01') !='Approved')
						{
							if($iosValidation->getStatusByIo($daily->io_id,$year.'-'.$month.'-01') !='Expired')
								continue;
						}
					}
					else
						if($iosValidation->getStatusByIo($daily->io_id,$year.'-'.$month.'-01') != $status) continue;					
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
				$data[$i]['status_io']       =$iosValidation->getStatusByIo($daily->io_id,$year.'-'.$month.'-01');				
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
	
	public function getClients($month,$year,$entity=null,$io=null,$accountManager=null,$opportunitie_id=null,$cat=null,$status=null,$group)
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
			);
		#Declare arrays to use
		$totals_io       =array();
		$totals          =array();
		$data            =array();
		$totals_invoiced =array();
		$dailysNoMulti   =Ios::model()->getClientsMulti($filters);
		$filters['multi']=false;
		$dailysMulti     =Ios::model()->getClientsMulti($filters);
		$dailys          =array_merge($dailysNoMulti,$dailysMulti);
		
		if($group=='profile')
		{
			#Save results to array group by io,carrier and date
			foreach ($dailys as $daily) {	
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
		}
		else
		{
			foreach ($dailys as $daily) {
				$id      = $daily['id'];
				$carrier = $daily['carrier'];
				$product = $daily['product'];
				$rate    = $daily['multi']==false ? $daily['rate'] : 'multi';
				$revenue = $daily['model']=='CPM' ? ($daily['conv']*$daily['rate'])/1000 : $daily['conv']*$daily['rate'];

				$opportunitie=$daily['opportunitie_id'];
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

				isset($totals_invoiced[$daily['currency']]) ?  : $totals_invoiced[$daily['currency']] =0;
					if($daily['status_io']=='Invoiced')
					$totals_invoiced[$daily['currency']]+=$revenue;


				#This array have totals
				isset($totals[$daily['currency']]) ?  : $totals[$daily['currency']]['revenue'] =0;
					$totals[$daily['currency']]['revenue']+=$revenue;

				isset($totals_io[$daily['id']]) ?  : $totals_io[$daily['id']] =0;
					$totals_io[$daily['id']]+=$revenue;
			}
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
		#Return clients, totals by io and totals
		$result = array(
			'data'            => $consolidated, 
			'totals_io'       => $totals_io, 
			'totals'          => $totals, 
			'totals_invoiced' => $totals_invoiced
		);
		return $result;
	}

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

}
