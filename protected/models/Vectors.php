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
			'providers_id'         => 'Provider',
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
		return $model->id . "-" . $model->name . "-VEC";
	}

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
