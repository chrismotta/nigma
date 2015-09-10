<?php

/**
 * This is the model class for table "regions".
 *
 * The followings are the available columns in table 'regions':
 * @property integer $id
 * @property integer $finance_entities_id
 * @property integer $country_id
 * @property string $region
 *
 * The followings are the available model relations:
 * @property Opportunities[] $opportunities
 * @property FinanceEntities $financeEntities
 * @property GeoLocation $country
 */
class Regions extends CActiveRecord
{
	public $finance_entities_name;
	public $country_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'regions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('finance_entities_id, country_id', 'required'),
			array('finance_entities_id, country_id', 'numerical', 'integerOnly'=>true),
			array('region', 'length', 'max'=>255),
			array('status', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, finance_entities_id, country_id, region, finance_entities_name, country_name', 'safe', 'on'=>'search'),
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
			'opportunities' => array(self::HAS_MANY, 'Opportunities', 'regions_id'),
			'financeEntities' => array(self::BELONGS_TO, 'FinanceEntities', 'finance_entities_id'),
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'finance_entities_id' => 'Finance Entities',
			'country_id' => 'Region',
			'region' => 'Comment',
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
	public function search($financeEntity=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		if(isset($financeEntity))
			$criteria->compare('finance_entities_id',$financeEntity);
		else
			$criteria->compare('finance_entities_id',$this->finance_entities_id);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('region',$this->region,true);
		$criteria->with=array('financeEntities','country');
		$criteria->compare('country.name',$this->country_name,true);
		$criteria->compare('financeEntities.name',$this->finance_entities_name,true);
		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			'pagination' => array(
                'pageSize' => 30,
            ),
			'sort'       => array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'country_name'=>array(
						'asc'  =>'country.name',
						'desc' =>'country.name DESC',
		            ),
		            'finance_entities_name'=>array(
						'asc'  =>'financeEntities.name',
						'desc' =>'financeEntities.name DESC',
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
	 * @return Regions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function findByAdvertisers($advertiser)
	{		
		$criteria = new CDbCriteria;
		$criteria->compare("financeEntities.advertisers_id",$advertiser);
		$criteria->join='INNER JOIN finance_entities financeEntities ON( t.finance_entities_id=financeEntities.id )';
		$criteria->group='t.id';
		$criteria->order='t.region';
		return $this->model()->findAll($criteria);
	}
	public function findByFinanceEntity($fen)
	{		
		$criteria = new CDbCriteria;
		$criteria->compare('t.finance_entities_id', $fen);
		$criteria->order='t.region';
		return $this->model()->findAll($criteria);
	}

	public function findByCommercialId($commercial_id)
	{		
		$criteria = new CDbCriteria;
		$criteria->with = array('country');
		$criteria->compare("financeEntities.commercial_id",$commercial_id);
		$criteria->join='INNER JOIN finance_entities financeEntities ON( t.finance_entities_id=financeEntities.id )';
		$criteria->group='t.id';
		$criteria->order='country.name';
		return $this->model()->findAll($criteria);
	}

	/**
	 * Get Opportunities associated with this IO.
	 * @return [type] [description]
	 */
	public function getOpportunities()
	{
		return Opportunities::model()->findAll('regions_id=:regions_id', array(':regions_id' => $this->id));
	}
}
