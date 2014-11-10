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
		$criteria->compare('entity',$entity,true);
		$criteria->compare('net_payment',$this->net_payment,true);
		$criteria->compare('advertisers_id',$this->advertisers_id);
		$criteria->compare('pdf_name',$this->pdf_name,true);
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.email_validation',$this->email_validation,true);

		$criteria->with = array( 'advertisers', 'commercial', 'country');
		$criteria->compare('advertisers.name', $this->advertiser_name, true);
		$criteria->compare('advertisers.cat', $cat, true);
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



	public function getClientsNew($month,$year,$entity=null,$io=null,$accountManager=null,$opportunitie_id=null)
	{
		$data=array();	
		$ios=self::model()->findAll();
		if($entity===0)$entity=null;
		//echo "<script>alert('".$entity."')</script>";		
		if($entity || $io)
		{
			$criteria=new CDbCriteria;
			if($entity)
				$criteria->addCondition('entity="'.$entity.'"');
			if($io)
				$criteria->addCondition('id="'.$io.'"');
			$ios=self::model()->findAll($criteria);
		}
		
		$i=0;
		foreach ($ios as $io) {

			$criteria                       =new CDbCriteria;
			$criteria->addCondition('ios_id ='.$io->id);
			if($accountManager)
				$criteria->addCondition('account_manager_id='.$accountManager);				
			if($opportunitie_id)
				$criteria->addCondition('id ='.$opportunitie_id);			
			$criteria->group                ='ios_id,model_adv,rate';
			$opportunities                  =Opportunities::model()->findAll($criteria);
			foreach ($opportunities as $opportunitie) {
				$criteria                                 =new CDbCriteria;
				$criteria->addCondition('opportunities_id ='.$opportunitie->id);				
				$campaigns                                =Campaigns::model()->findAll($criteria);
				foreach ($campaigns as $campaign) {
					$criteria                             =new CDbCriteria;
					$criteria->addCondition('campaigns_id ='.$campaign->id);
					$criteria->addCondition('MONTH(date)  ='.$month);
					$criteria->addCondition('YEAR(date)   ='.$year);
					$dailys=DailyReport::model()->findAll($criteria);
					foreach ($dailys as $daily) {
						if($daily->revenue>0)
						{							
							if($opportunitie->rate && $opportunitie->carriers_id)
							{
								$opportunitiesValidation                              =new OpportunitiesValidation;
								$geoLocation                                                   =new GeoLocation;
								$carriers                                                      =new Carriers;
								$conv =0;
								switch ($opportunitie->model_adv) {
									case 'CPA':
										$conv=$daily->conv_adv==null ? $daily->conv_api : $daily->conv_adv;
										break;
									
									case 'CPM':
										$conv=$daily->imp;
										break;
									
									case 'CPC':
										$conv=$daily->clics;
										break;
								}
								$rate=number_format($daily->revenue/$conv,2);
								$data[$io->id][$opportunitie->carriers_id][$rate]['id']           =$io->id;
								$data[$io->id][$opportunitie->carriers_id][$rate]['name']         =$io->commercial_name;
								$data[$io->id][$opportunitie->carriers_id][$rate]['opportunitie'] =$opportunitie->id;								
								$data[$io->id][$opportunitie->carriers_id][$rate]['product']      =$opportunitie->product;
								$data[$io->id][$opportunitie->carriers_id][$rate]['currency']     =$io->currency;
								$data[$io->id][$opportunitie->carriers_id][$rate]['entity']       =$io->entity;
								$data[$io->id][$opportunitie->carriers_id][$rate]['model']        =$opportunitie->model_adv;
								$data[$io->id][$opportunitie->carriers_id][$rate]['carrier']      =$opportunitie->carriers_id;
								$data[$io->id][$opportunitie->carriers_id][$rate]['product']      =$opportunitie->product;
								$data[$io->id][$opportunitie->carriers_id][$rate]['country']      =$geoLocation->getNameFromId($carriers->getCountryById($opportunitie->carriers_id));
								$data[$io->id][$opportunitie->carriers_id][$rate]['mobileBrand']  =$carriers->getMobileBrandById($opportunitie->carriers_id);
								$data[$io->id][$opportunitie->carriers_id][$rate]['status']       =$opportunitiesValidation->checkValidation($opportunitie->id,$year.'-'.$month.'-01');
								isset($data[$io->id][$opportunitie->carriers_id][$rate]['conv']) ?  : $data[$io->id][$opportunitie->carriers_id][$rate]['conv']       =0;
								isset($data[$io->id][$opportunitie->carriers_id][$rate]['revenue']) ?  : $data[$io->id][$opportunitie->carriers_id][$rate]['revenue'] =0;
								//!isset($data[$i]['rev']) ? $data[$i]['rev']         =0 : ;

								$data[$io->id][$opportunitie->carriers_id][$rate]['revenue']        +=$daily->revenue;
								$data[$io->id][$opportunitie->carriers_id][$rate]['conv']+=$conv;
								// if($opportunitie->model_adv =='CPA')$data[$io->id][$opportunitie->carriers_id][$rate]['conv']+=$daily->conv_adv==null ? $daily->conv_api : $daily->conv_adv;
								// if($opportunitie->model_adv =='CPM')$data[$io->id][$opportunitie->carriers_id][$rate]['conv']+=$daily->imp;
								// if($opportunitie->model_adv =='CPC')$data[$io->id][$opportunitie->carriers_id][$rate]['conv']+=$daily->clics;
								$data[$io->id][$opportunitie->carriers_id][$rate]['rate']         =$rate;
							}
							else
							{
								$criteria                                =new CDbCriteria;
								$criteria->addCondition('daily_report_id ='.$daily->id);
								$rates                                   =MultiRate::model()->findAll($criteria);
								foreach ($rates as $rate) {
									$opportunitiesValidation                                       =new OpportunitiesValidation;
									$geoLocation                                                   =new GeoLocation;
									$carriers                                                      =new Carriers;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['id']           =$io->id;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['name']         =$io->commercial_name;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['opportunitie'] =$opportunitie->id;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['product']      =$opportunitie->product;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['currency']     =$io->currency;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['entity']       =$io->entity;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['model']        =$opportunitie->model_adv;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['rate']         =$rate->rate;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['carrier']      =$rate->carriers_id_carrier;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['product']      =$opportunitie->product;
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['country']      =$geoLocation->getNameFromId($carriers->getCountryById($rate->carriers_id_carrier));
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['mobileBrand']  =$carriers->getMobileBrandById($rate->carriers_id_carrier);
									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['status']       =$opportunitiesValidation->checkValidation($opportunitie->id,$year.'-'.$month.'-01');
									isset($data[$io->id][$rate->carriers_id_carrier][$rate->rate]['conv']) ?  : $data[$io->id][$rate->carriers_id_carrier][$rate->rate]['conv']       =0;
									isset($data[$io->id][$rate->carriers_id_carrier][$rate->rate]['revenue']) ?  : $data[$io->id][$rate->carriers_id_carrier][$rate->rate]['revenue'] =0;
									//!isset($data[$i]['rev']) ? $data[$i]['rev']         =0 : ;

									$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['revenue']        +=$rate->rate*$rate->conv;
									if($opportunitie->model_adv =='CPA')$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['conv']+=$rate->conv;
									if($opportunitie->model_adv =='CPM')$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['conv']+=$daily->imp;
									if($opportunitie->model_adv =='CPC')$data[$io->id][$rate->carriers_id_carrier][$rate->rate]['conv']+=$daily->clics;
								}
							}
							
						}
					}
				}
				$i++;
			}

			//$i++;
		}
		return $data;
	}





	public function getClients($month,$year,$entity=null,$io=null,$accountManager=null,$opportunitie_id=null,$cat=null,$status=null)
	{
		$data                    =array();	
		$opportunitiesValidation =new OpportunitiesValidation;
		$iosValidation           =new IosValidation;
		$ios=self::model()->findAll();
		if($entity===0)$entity=null;
		if($cat===0)$entity=null;
		//echo "<script>alert('".$entity."')</script>";		
		if($entity || $io || $cat)
		{
			$criteria=new CDbCriteria;
			if($entity)
				$criteria->addCondition('entity="'.$entity.'"');
			if($io)
				$criteria->addCondition('id="'.$io.'"');
			if($cat)
			{
				$criteria->with=array('advertisers');
				$criteria->addCondition('advertisers.cat="'.$cat.'"');
			}

			$ios=self::model()->findAll($criteria);
		}
		
		$i=0;
		foreach ($ios as $io) {
			if($status)
				if($iosValidation->getStatusByIo($io->id,$year.'-'.$month.'-01') != $status) continue;
			$criteria                       =new CDbCriteria;
			$criteria->addCondition('ios_id ='.$io->id);
			if($accountManager)
				$criteria->addCondition('account_manager_id='.$accountManager);				
			if($opportunitie_id)
				$criteria->addCondition('id ='.$opportunitie_id);			
			$criteria->group                ='ios_id,model_adv,rate';
			$opportunities                  =Opportunities::model()->findAll($criteria);
			foreach ($opportunities as $opportunitie) {
				$criteria                                 =new CDbCriteria;
				$criteria->addCondition('opportunities_id ='.$opportunitie->id);				
				$campaigns                                =Campaigns::model()->findAll($criteria);
				foreach ($campaigns as $campaign) {
					$criteria                             =new CDbCriteria;
					$criteria->addCondition('campaigns_id ='.$campaign->id);
					$criteria->addCondition('MONTH(date)  ='.$month);
					$criteria->addCondition('YEAR(date)   ='.$year);
					$dailys=DailyReport::model()->findAll($criteria);
					foreach ($dailys as $daily) {
						if($daily->revenue>0)
						{
							switch ($opportunitie->model_adv) {
								case 'CPA':
									$conv=$daily->conv_adv==null ? $daily->conv_api : $daily->conv_adv;
									break;
								case 'CPM':
									$conv=$daily->imp/1000;
									break;
								case 'CPC':
									$conv=$daily->clics;
									break;
								
							}
							
							!$opportunitie->rate ? $rate=$opportunitie->rate : $rate=number_format($daily->revenue/$conv,2);
							$data[$opportunitie->id][$rate]['id']                                       =$io->id;
							$data[$opportunitie->id][$rate]['name']                                     =$io->commercial_name;
							$data[$opportunitie->id][$rate]['opportunitie']                             =$opportunitie->getVirtualName();
							$data[$opportunitie->id][$rate]['opportunitie_id']                          =$opportunitie->id;
							$data[$opportunitie->id][$rate]['currency']                                 =$io->currency;
							$data[$opportunitie->id][$rate]['entity']                                   =$io->entity;
							$data[$opportunitie->id][$rate]['model']                                    =$opportunitie->model_adv;
							$data[$opportunitie->id][$rate]['carrier']                                  =$opportunitie->carriers_id;
							$data[$opportunitie->id][$rate]['status_opp']                               =$opportunitiesValidation->checkValidation($opportunitie->id,$year.'-'.$month.'-01');
							$data[$opportunitie->id][$rate]['status_io']                                =$iosValidation->getStatusByIo($io->id,$year.'-'.$month.'-01');
							isset($data[$i]['conv']) ?  : $data[$opportunitie->id][$rate]['conv']       =0;
							isset($data[$i]['revenue']) ?  : $data[$opportunitie->id][$rate]['revenue'] =0;
							//!isset($data[$i]['rev']) ? $data[$i]['rev']         =0 : ;
							$data[$opportunitie->id][$rate]['revenue'] +=$daily->revenue;
							$data[$opportunitie->id][$rate]['conv']    +=$conv;
							$data[$opportunitie->id][$rate]['rate']    =$rate;
						}
					}
				}
				$i++;
			}

			//$i++;
		}
		return $data;
	}


	public function getClientsByIo($month,$year,$io_id=null)
	{
		$data=array();	
		$criteriaI=new CDbCriteria;
		$criteriaI->addCondition('id='.$io_id);
		$ios=self::model()->findAll($criteriaI);
		$i=0;
		foreach ($ios as $io) {

			$criteria                       =new CDbCriteria;
			$criteria->addCondition('ios_id ='.$io->id);
			$criteria->group                ='ios_id,model_adv,rate';
			$opportunities                  =Opportunities::model()->findAll($criteria);
			foreach ($opportunities as $opportunitie) {
				$criteria                                 =new CDbCriteria;
				$criteria->addCondition('opportunities_id ='.$opportunitie->id);
				$campaigns                                =Campaigns::model()->findAll($criteria);
				foreach ($campaigns as $campaign) {
					$criteria                             =new CDbCriteria;
					$criteria->addCondition('campaigns_id ='.$campaign->id);
					$criteria->addCondition('MONTH(date)  ='.$month);
					$criteria->addCondition('YEAR(date)   ='.$year);
					$dailys                               =DailyReport::model()->findAll($criteria);
					foreach ($dailys as $daily) {
						$criteria                                =new CDbCriteria;
						$criteria->addCondition('daily_report_id ='.$daily->id);
						$rates                                   =MultiRate::model()->findAll($criteria);
						foreach ($rates as $rate) {
							$geoLocation                                                   =new GeoLocation;
							$carriers                                                      =new Carriers;
							if($daily->revenue>0)
							{
								// $data[$i]['id']                                       =$daily->id;
								// $data[$i]['name']                                     =$io->commercial_name;
								// $data[$i]['currency']                                 =$io->currency;
								// $data[$i]['entity']                                   =$io->entity;
								// $data[$i]['model']                                    =$opportunitie->model_adv;
								$data[$i]['rate']                                     =$rate->rate;
								$data[$i]['carrier']                                  =$opportunitie->carriers_id;
								isset($data[$i]['conv']) ?  : $data[$i]['conv']       =0;
								isset($data[$i]['revenue']) ?  : $data[$i]['revenue'] =0;
								//!isset($data[$i]['rev']) ? $data[$i]['rev']=0 : ;								
								$data[$i]['revenue']        +=$daily->revenue;
								if($opportunitie->model_adv =='CPA')$data[$i]['conv']+=$rate->conv;
								if($opportunitie->model_adv =='CPM')$data[$i]['conv']+=$daily->imp;
								if($opportunitie->model_adv =='CPC')$data[$i]['conv']+=$daily->clics;
							}

						$i++;
						}
					}
				}
			}

			//$i++;
		}
		$result=array();
		foreach ($data as $value) {
			isset($result[$value['rate']]) ?  : $result[$value['rate']]=0;
			$result[$value['rate']]+=$value['conv'];
		}
		$data=array();
		$i=0;
		foreach ($result as $rate => $conv) {
			$data[$i]['id']=$i;
			$data[$i]['rate']=$rate;
			$data[$i]['conv']=$conv;
			$data[$i]['revenue']=$rate*$conv;
			$i++;
		}
		return $data;
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

}
