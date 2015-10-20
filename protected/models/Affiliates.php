<?php

/**
 * This is the model class for table "affiliates".
 *
 * The followings are the available columns in table 'affiliates':
 * @property integer $providers_id
 * @property integer $users_id
 * @property string $phone
 *
 * The followings are the available model relations:
 * @property Providers $providers
 * @property Users $users
 */
class Affiliates extends CActiveRecord
{
	public $rate;
	public $conv;
	public $clics;
	public $spend;
	public $convrate;
	public $date;
	public $country_name;
	public $providers_name;
	public $commercial_name;
	public $name; // campaigns_name use for external screen with affiliates authentication
	public $contact_com;
	public $contact_adm;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'affiliates';
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
			array('providers_id, users_id', 'numerical', 'integerOnly'=>true),
			//array('prefix','unique', 'message'=>'This prefix already exists.'),
			array('phone', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('providers_id, providers_name, commercial_name, contact_com, contact_adm, country_name, users_id, phone', 'safe', 'on'=>'search'),
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
			'users'     => array(self::BELONGS_TO, 'Users', 'users_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'providers_id'    => 'ID',
			'users_id'        => 'External user login',
			'country_id'      => 'Country',
			'commercial_name' => 'Legal Name',
			'phone'           => 'Phone',
			'rate'            => 'Rate',
			'conv'            => 'Conv',
			'spend'           => 'Revenue',
			'country_name'    => 'Country',
			'providers_name'  => 'Name',
			'contact_com'  => 'Com. Contact',
			'contact_adm'  => 'Adm. Contact',
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

		$criteria->compare('providers_id',$this->providers_id);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('phone',$this->phone,true);
		
		$criteria->with = array('providers', 'providers.country');
		$criteria->compare('providers.status','Active',true);

		//$criteria->addCondition('providers.prospect>1');//not working

		$criteria->compare('LOWER(providers.name)',strtolower($this->providers_name),true);
		$criteria->compare('LOWER(providers.contact_com)',strtolower($this->contact_com),true);
		$criteria->compare('LOWER(providers.contact_adm)',strtolower($this->contact_adm),true);
		$criteria->compare('LOWER(providers.commercial_name)',strtolower($this->commercial_name),true);
		$criteria->compare('LOWER(country.name)',strtolower($this->country_name),true);

		return new CActiveDataProvider($this, array(
			'criteria' =>$criteria,
			'sort'     => array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'country_name'=>array(
						'asc'  =>'country.name',
						'desc' =>'country.name DESC',
		            ),
		            'providers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            'commercial_name'=>array(
						'asc'  =>'providers.commercial_name',
						'desc' =>'providers.commercial_name DESC',
		            ),
		            'contact_com'=>array(
						'asc'  =>'providers.contact_com',
						'desc' =>'providers.contact_com DESC',
		            ),
		            'contact_adm'=>array(
						'asc'  =>'providers.contact_adm',
						'desc' =>'providers.contact_adm DESC',
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
	 * @return Affiliates the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function findByUser($id)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('users_id='.$id);
		if($user=Self::model()->find($criteria))
			return $user;
	}

	public function getAffiliates($dateStart,$dateEnd,$affiliate_id)
	{
		$data    =array();
		$graphic =array();
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
				$data[$i]['name']     =Campaigns::getExternalName($affiliate['id'], true);

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
				$data[$i]['name']     =Campaigns::getExternalName($affiliate['id'], true);		
				
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

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Providers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Affiliates::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
