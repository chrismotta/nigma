<?php

/**
 * This is the model class for table "affiliates".
 *
 * The followings are the available columns in table 'affiliates':
 * @property integer $id
 * @property integer $networks_id
 * @property string $rate
 * @property integer $users_id
 *
 * The followings are the available model relations:
 * @property Users $users
 * @property Networks $networks
 */
class Affiliates extends CActiveRecord
{
	public $date;
	public $name;
	public $conv;
	public $spend;
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
			array('networks_id', 'required'),
			array('networks_id, users_id', 'numerical', 'integerOnly'=>true),
			array('rate', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, networks_id, rate, users_id', 'safe', 'on'=>'search'),
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
			'users'    => array(self::BELONGS_TO, 'Users', 'users_id'),
			'networks' => array(self::BELONGS_TO, 'Networks', 'networks_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'          => 'ID',
			'networks_id' => 'Networks',
			'rate'        => 'Rate',
			'users_id'    => 'Users',
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
		$criteria->compare('networks_id',$this->networks_id);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('users_id',$this->users_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
				IF(ISNULL(d.conv_adv) and ISNULL(d.conv_api),
					ROUND(
						d.spend/
								IF(ISNULL(d.conv_adv),d.conv_api,d.conv_adv),2
					),
				a.rate) as rate,
				sum(
					IF(ISNULL(d.conv_adv), d.conv_api, d.conv_adv)
				) as conv,
				sum(d.spend) as spend,
				DATE(d.date) as date
				from daily_report d 
				inner join campaigns c on d.campaigns_id=c.id
				inner join networks n on c.networks_id=n.providers_id 
				inner join affiliates a on a.networks_id=n.providers_id
				WHERE d.date BETWEEN :dateStart AND :dateEnd
				AND n.providers_id = :affiliate
				group by c.id,DATE(d.date),ROUND(d.spend/IF(ISNULL(d.conv_adv),d.conv_api,d.conv_adv),2)";
			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(":dateStart", $dateStart, PDO::PARAM_STR);
			$command->bindParam(":dateEnd", $end, PDO::PARAM_STR);
			$command->bindParam(":affiliate", $affiliate_id, PDO::PARAM_INT);
			//$command->bindParam(":affiliate", $affiliate, PDO::PARAM_INT);
			$affiliates=$command->queryAll();
			foreach ($affiliates as $affiliate) {
				$data[$i]['id']    =$affiliate['id'];
				$data[$i]['rate']  =$affiliate['rate'];
				$data[$i]['conv']  =$affiliate['conv'];
				$data[$i]['spend'] =$affiliate['spend'];
				$data[$i]['date']  =$affiliate['date'];
				$data[$i]['name']  =Campaigns::getExternalName($affiliate['id']);

				isset($graphic[$affiliate['date']]['spend']) ? : $graphic[$affiliate['date']]['spend']=0;
				isset($graphic[$affiliate['date']]['conv']) ? : $graphic[$affiliate['date']]['conv']=0;
				$graphic[$affiliate['date']]['conv']+=$affiliate['conv'];
				$graphic[$affiliate['date']]['spend']+=$affiliate['spend'];

				$i++;
			}
		}
		if(date('Y-m-d', strtotime($dateStart))==date('Y-m-d', strtotime('today')) || date('Y-m-d', strtotime($dateEnd))==date('Y-m-d', strtotime('today')))
		{
			$date=date('Y-m-d', strtotime('today'));
			$sql="select c.id,count(l.id) as conv, a.rate as rate, (count(l.id)*a.rate) as spend, DATE(l.date) as date
				from campaigns c
				inner join networks n on c.networks_id=n.providers_id 
				inner join conv_log l on l.campaign_id=c.id
				inner join affiliates a on a.networks_id=n.providers_id
				WHERE DATE(l.date)=DATE(:date)
				AND n.providers_id = :affiliate
				group by c.id,DATE(l.date)";
			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(":date", $date, PDO::PARAM_STR);
			$command->bindParam(":affiliate", $affiliate_id, PDO::PARAM_INT);
			$affiliates=$command->queryAll();
			foreach ($affiliates as $affiliate) {
				$data[$i]['id']    =$affiliate['id'];
				$data[$i]['rate']  =$affiliate['rate'];
				$data[$i]['conv']  =$affiliate['conv'];
				$data[$i]['spend'] =$affiliate['spend'];
				$data[$i]['date']  =$affiliate['date'];
				$data[$i]['name']  =Campaigns::getExternalName($affiliate['id']);		
				
				isset($graphic[$affiliate['date']]['spend']) ? : $graphic[$affiliate['date']]['spend']=0;
				isset($graphic[$affiliate['date']]['conv']) ? : $graphic[$affiliate['date']]['conv']=0;
				$graphic[$affiliate['date']]['conv']+=$affiliate['conv'];
				$graphic[$affiliate['date']]['spend']+=$affiliate['spend'];

				$i++;
			}
		}
		$i=0;
		$totalGraphic=array();
		$totalGraphic['dates']=array();
		$totalGraphic['convs']=array();
		$totalGraphic['spends']=array();
		foreach ($graphic as $key => $value) {
			$totalGraphic['dates'][$i]  =$key;
			$totalGraphic['convs'][$i]  =$value['conv'];
			$totalGraphic['spends'][$i] =$value['spend'];
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
		             'id', 'rate', 'conv', 'spend', 'date','name'
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
