<?php

/**
 * This is the model class for table "vectors".
 *
 * The followings are the available columns in table 'vectors':
 * @property integer $id
 * @property integer $providers_id
 * @property string $name
 * @property string $status
 * @property string $rate
 *
 * The followings are the available model relations:
 * @property DailyVectors[] $dailyVectors
 * @property Providers $providers
 * @property Campaigns[] $campaigns
 */
class Vectors extends CActiveRecord
{
	public $campaigns_associated;
	public $providers_name;

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
			array('rate', 'validateRate' ), 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, providers_id, name, status, rate, campaigns_associated, providers_name', 'safe', 'on'=>'search'),
		);
	}
 
    public function validateRate($attribute, $params)
    {
        if ( $this->$attribute && ( !is_numeric($this->$attribute) || !$this->$attribute>0 ) ) {
            $this->addError($attribute, 'Must be a numeric value greater than zero.');
        }
        else if ( !$this->$attribute ) 
        {
        	$this->$attribute = null;
        }
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
			'providers_name'       => 'Traffic Source',
			'name'                 => 'Name',
			'status'               => 'Status',
			'campaigns_associated' => 'Campaigns',
			'rate'					=> 'Rate',
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
		$criteria->compare('LOWER(providers.name)',strtolower($this->providers_name),true);

		$criteria->with = array( 'campaigns', 'providers' );

		return new CActiveDataProvider($this, array(
			'criteria' =>$criteria,
			// Setting 'sort' property in order to add 
			// a sort tool in the related collumns
			'pagination'=> KHtml::pagination(),
			'sort'     =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'providers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            // Adding all the other default attributes
		            '*',
	            ),
	        )
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
		/* PENDIENTE DE CONFIRMACION
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
			else{
				$criteria = new CDbCriteria;
				$criteria->with = array('clicksLog', 'clicksLog.convLogs', 'clicksLog.campaigns.opportunities');
				$criteria->compare('t.vectors_id', $this->id);
				$criteria->compare('clicksLog.campaigns_id', $cid);

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
				}else{
					$campaignsList[$cid]['clicks'] = 0;
					$campaignsList[$cid]['conv'] = 0;
				}				
			}
		}	
		*/
		foreach ($vhc as $cmp) {

			$cid = $cmp->campaigns_id;

			$criteria = new CDbCriteria;
			$criteria->with = array('vectorsLog', 'convLogs' );
			$criteria->compare('vectorsLog.vectors_id', $this->id);
			$criteria->compare('t.campaigns_id', $cid);
			$criteria->addCondition('t.carrier != "-"');
			$criteria->addCondition('DATE(t.date) = "' . $date . '"');
			$criteria->select = array(
				'COUNT(t.id) as clicks',
				'COUNT(convLogs.id) as conv',
				);		
			
			$model = ClicksLog::model()->find($criteria);

			if($model){
				$campaignsList[$cid]['clicks'] = $model->clicks;
				$totalClicks += $campaignsList[$cid]['clicks'];
				$campaignsList[$cid]['conv'] = $model->conv;
				$totalConv += $campaignsList[$cid]['conv'];
				var_export('('.$this->id.')'.$cid. ': ' . $model->clicks.'<br><br><br>' );		
			}else{
				$campaignsList[$cid]['clicks'] = 0;
				$campaignsList[$cid]['conv'] = 0;
				var_export('('.$this->id.')'.$cid. ': no clicks log<br><br><br>' );	
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

			// if at least there are 1 click
			if($totalClicks>0){

				// cost related to clicks
				foreach ($campaignsList as $id => $cmp) {
					if($cmp['clicks'] > 0){
						$campaignsList[$id]['id'] = $id;
						$campaignsList[$id]['cost'] = $cost / $totalClicks * $cmp['clicks'];
						$return[$id] = $id . ': ' .$campaignsList[$id]['cost'];
					}
					else{
						// unset campaigns without clicks
						unset($campaignsList[$id]);
					}

				}

			}else{

				// when there are no campaigns with clicks
				//var_export('vector: ' . $this->id . ' campaign: ' . $cid . ' cost: ' . $cost . ' count: ' . count($vhc));
				foreach ($campaignsList as $id => $cmp) {
					$campaignsList[$id]['id'] = $id;
					$campaignsList[$id]['cost'] = $cost / count($vhc);
					$return[$id] = $id . ': ' .$campaignsList[$id]['cost'];
				}
			}

			// echo json_encode($campaignsList);
			// echo '<br>';
			// echo 'TOTAL: '.$totalConv.' conv - '.$vector['cost'].' us micropound';
			// echo '<hr>';
			// return $campaignsList;

			// inserting values //
			foreach ($campaignsList as $cid => $camp) {
				if ( isset($data['not_usd']) && $data['not_usd'] )
					$not_usd = true;
				else
					$not_usd = false;

				$daily = $this->createDaily($camp, $date, $not_usd);
				
				// $return['list'][] = $daily;
				$return[$cid].= ' => ' . $daily . ' | ';

				// if(isset($daily['cid']))
				// 	$return['result'] = 'OK';
				
			}

			// $return['c_id'] = $this->id;
		

		}else{
		
			$return['result'] = 'ERROR';
			$return['msg'] = 'No campaigns found';

		}

		return $return;

	}

	private function createDaily($camp, $date, $not_usd=false)
	{
		// echo 'Creating daily - date: '.$date.' cid: '.$camp.'<br>';
		$campModel = Campaigns::model()->findByPk($camp['id']);

		//
		$dailyRepCriteria = new CDbCriteria;
		$dailyRepCriteria->with = array(
			'dailyReportVectors',
		);

		/*
		$dailyRepCriteria->select = array( 
			'*',
			'dailyReportVectors.id AS daily_report_vector',
		);
		*/

		$dailyRepCriteria->compare( 'DATE(date)', $date);
		$dailyRepCriteria->compare( 'providers_id', $this->providers_id );
		$dailyRepCriteria->compare( 'campaigns_id', $camp['id'] );
		$dailyRepCriteria->compare( 'dailyReportVectors.vectors_id', $this->id );

		// if exists overwrite, else create a new
		$dailyReport = DailyReport::model()->find( $dailyRepCriteria );
		
		/*
		$dailyReport = DailyReport::model()->find(
			"providers_id=:providers AND DATE(date)=:date AND campaigns_id=:cid", 
			array(
				":providers"=>$campModel->providers_id, 
				":cid"=>$camp['id'],
				":date"=>$date, 
				)
			);
		*/
		
		if(!$dailyReport){
			
			$dailyReport = new DailyReport();
			$dailyReport->date = $date;
			$dailyReport->campaigns_id = $camp['id'];
			$dailyReport->providers_id = $this->providers_id;

			$isNew = true;
			// $return['msg'] = "New record: ";
		
		}else{
			$isNew = false;
			// $return['msg'] =  "Update record: ".$dailyReport->id;
		}
				
		if ( !$dailyReport->campaigns_id ) {
			Yii::log("Invalid external campaign name: '" . $campaign['campaign'], 'warning', 'system.model.api.adWords');
			return NULL;
		}

		$dailyReport->date = date( 'Y-m-d', strtotime($date) );
		$dailyReport->imp = 0;
		$dailyReport->clics = $camp['clicks'];
		$dailyReport->conv_api = $camp['conv'];
		
		$dailyReport->spend = number_format($camp['cost'], 2, '.', '');
		/*
		if ( $not_usd )
			$dailyReport->spend = $dailyReport->getSpendUSD();
		*/
	
		$dailyReport->updateRevenue();
		$dailyReport->setNewFields();
		
		if ( !$dailyReport->save() ) {
			Yii::log("Can't save campaign: '" . $camp['id'] . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.adWords');
			$return = json_encode($dailyReport->getErrors());
		} else {
			// $return['cid'] = $dailyReport->campaigns_id;

			if($isNew){

				$dailyReportVector = new DailyReportVectors();	
				$dailyReportVector->vectors_id = $this->id;
				$dailyReportVector->daily_report_id = $dailyReport->id;
				$dailyReportVector->save();
				
				$return = $dailyReport->id . ' saved: '. $dailyReport->spend;
			}else{
				
				$return = $dailyReport->id . ' updated: '. $dailyReport->spend;
			}
		}
		
		// return $dailyReport->spend;
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
