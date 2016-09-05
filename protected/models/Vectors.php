<?php

/**
 * This is the model class for table "vectors".
 *
 * The followings are the available columns in table 'vectors':
 * @property integer $id
 * @property integer $providers_id
 * @property string $name
 * @property string $status
 *
 * The followings are the available model relations:
 * @property DailyVectors[] $dailyVectors
 * @property Providers $providers
 * @property Campaigns[] $campaigns
 */
class Vectors extends CActiveRecord
{
	public $campaigns_associated;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vectors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('providers_id, name', 'required'),
			array('providers_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('status', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, providers_id, name, status, campaigns_associated', 'safe', 'on'=>'search'),
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
			'dailyVectors' => array(self::HAS_MANY, 'DailyVectors', 'vectors_id'),
			'providers' => array(self::BELONGS_TO, 'Providers', 'providers_id'),
			'campaigns' => array(self::MANY_MANY, 'Campaigns', 'vectors_has_campaigns(vectors_id, campaigns_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                   => 'ID',
			'providers_id'         => 'Traffic Source',
			'name'                 => 'Name',
			'status'               => 'Status',
			'campaigns_associated' => 'Campaigns',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.status',$this->status,true);

		$criteria->with = array( 'campaigns' );

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize'=>50,
            ),
		));
	}

	public function isValidId($id)
	{
		$isValid = $this->find('id=:id', array(':id' => $id));
		return $isValid ? true : false;
	}

	/**
	 * Retrieves a list of models for specified provider and date. Ignore the vectors that had already info entry.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function searchByProviderAndDate($provider_id, $date)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array('campaigns');

		if ( $provider_id ) {
			$criteria->compare('t.providers_id', $provider_id);
			$criteria->addCondition("t.id NOT IN (
				SELECT v.id FROM vectors v WHERE v.providers_id=". $provider_id . " AND EXISTS (
					SELECT d.campaigns_id FROM daily_report d WHERE d.providers_id=". $provider_id . " AND d.date='" . date('Y-m-d', strtotime($date)) . "' AND d.campaigns_id IN (
						SELECT vhc.campaigns_id FROM vectors_has_campaigns vhc WHERE vhc.vectors_id = v.id
						)
					)
				)");
		} else {
			$criteria->compare('t.providers_id', -1); // Select none
		}

		$criteria->compare('t.status', 'Active');
		
		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			// Setting 'sort' property in order to add 
			// a sort tool in the related collumns
			'pagination' => false,
			'sort'       => array(
		        'attributes'=>array(
		        	'name'=>array(
		        		'asc'  =>'t.id',
						'desc' =>'t.id DESC',
		        	),
		        	// Adding all the other default attributes
		            '*',
		        ),
		    ),
		));
	}

	public function getExternalName($id)
	{
		$model = Vectors::model()->findByPk($id);
		return "v" . $model->id . "-" . $model->name . "-VEC";
	}


	//

	public function explodeVector($data){
		
		$cost = $data['spend'];
		$date = $data['date'];

		$vhc = VectorsHasCampaigns::model()->findAll('vectors_id=:vid', 
			array(':vid'=>$this->id));
		
		$totalClicks = 0;
		$totalConv = 0;

		foreach ($vhc as $cmp) {

			$cid = $cmp->campaigns_id;

			$criteria = new CDbCriteria;
			$criteria->with = array('clicksLog', 'clicksLog.convLogs', 'clicksLog.campaigns.opportunities');
			$criteria->compare('t.vectors_id', $this->id);
			$criteria->compare('clicksLog.campaigns_id', $cid);
			$criteria->addCondition('clicksLog.carrier != "-"');
			// $criteria->compare('opportunities.wifi', 'Specific Carrier');
			$criteria->addCondition('DATE(clicksLog.date) = "' . $date . '"');
			$criteria->select = array(
				'COUNT(t.id) AS clicks',
				'COUNT(convLogs.id) AS conv',
				);

			$model = VectorsLog::model()->find($criteria);
			
			if($model){

				$campaignsList[$cid]['clicks'] = $model->clicks;
				$totalClicks += $campaignsList[$cid]['clicks'];
				$campaignsList[$cid]['conv'] = $model->conv;
				$totalConv += $campaignsList[$cid]['conv'];

			}

		}

		if(isset($campaignsList)){

			/* deprecated 
			// cost related to conversions
			foreach ($campaignsList as $id => $cmp) {
				if($totalConv * $cmp['conv'] > 0)
					$campaignsList[$id]['cost'] = $vector['cost'] / $totalConv * $cmp['conv'];
				else
					$campaignsList[$id]['cost'] = 0;
			}
			*/

			// cost related to clicks
			foreach ($campaignsList as $id => $cmp) {
				$campaignsList[$id]['id'] = $id;
				if($totalClicks * $cmp['clicks'] > 0)
					$campaignsList[$id]['cost'] = $cost / $totalClicks * $cmp['clicks'];
				else
					$campaignsList[$id]['cost'] = 0;
			}
			
			// echo json_encode($campaignsList);
			// echo '<br>';
			// echo 'TOTAL: '.$totalConv.' conv - '.$vector['cost'].' us micropound';
			// echo '<hr>';
			// return $campaignsList;

			// inserting values //
			foreach ($campaignsList as $cid => $camp) {
				$daily = $this->createDaily($camp, $date);
				$return['list'][] = $daily;

				if(isset($daily['cid']))
					$return['result'] = 'OK';
				
			}

			$return['c_id'] = $this->id;
		
		}else{
		
			$return['result'] = 'ERROR';
			$return['msg'] = 'No campaigns found';

		}

		return $return;

	}

	private function createDaily($camp, $date)
	{
		// echo 'Creating daily - date: '.$date.' cid: '.$camp.'<br>';
		$campModel = Campaigns::model()->findByPk($camp['id']);
		
		// if exists overwrite, else create a new
		$dailyReport = DailyReport::model()->find(
			"providers_id=:providers AND DATE(date)=:date AND campaigns_id=:cid", 
			array(
				":providers"=>$campModel->providers_id, 
				":cid"=>$camp['id'],
				":date"=>$date, 
				)
			);

		if(!$dailyReport){
			$dailyReport = new DailyReport();
			$dailyReport->date = $date;
			$dailyReport->campaigns_id = $camp['id'];
			$dailyReport->providers_id = $campModel->providers_id;
			$return['msg'] = "New record: ";
		}else{
			$return['msg'] =  "Update record: ".$dailyReport->id;
		}
				
		if ( !$dailyReport->campaigns_id ) {
			Yii::log("Invalid external campaign name: '" . $campaign['campaign'], 'warning', 'system.model.api.adWords');
			return NULL;
		}

		$dailyReport->date = date( 'Y-m-d', strtotime($date) );
		$dailyReport->imp = 0;
		$dailyReport->clics = $camp['clicks'];
		$dailyReport->conv_api = $camp['conv'];
		
		// cost is return in micropound, why? google, why? 
		$dailyReport->spend = number_format($camp['cost'], 2, '.', '');

		$dailyReport->updateRevenue();
		$dailyReport->setNewFields();
		
		if ( !$dailyReport->save() ) {
			Yii::log("Can't save campaign: '" . $camp['id'] . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.adWords');
			$return['msg'] .= ' => '.json_encode($dailyReport->getErrors());
		} else {
			$return['msg'] .= ' => saved';
			$return['cid'] = $dailyReport->campaigns_id;
		}
		
		return $return;
	}

	//


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vectors the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
