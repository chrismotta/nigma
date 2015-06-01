<?php

/**
 * This is the model class for table "sites".
 *
 * The followings are the available columns in table 'sites':
 * @property integer $id
 * @property string $name
 * @property integer $publishers_providers_id
 *
 * The followings are the available model relations:
 * @property Placements[] $placements
 * @property Publishers $publishersProviders
 */
class Sites extends CActiveRecord
{
	public $publishers_name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// array('id', 'required'),
			array('id, publishers_providers_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, publishers_providers_id, publishers_name', 'safe', 'on'=>'search'),
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
			'placements'          => array(self::HAS_MANY, 'Placements', 'sites_id'),
			'publishersProviders' => array(self::BELONGS_TO, 'Publishers', 'publishers_providers_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                      => 'ID',
			'name'                    => 'Name',
			'publishers_providers_id' => 'Publishers Providers',
			'publishers_name'         => 'Publishers',
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
		$criteria->compare('publishers_providers_id',$this->publishers_providers_id);
		$criteria->with = array('publishersProviders', 'publishersProviders.providers');
		$criteria->compare('LOWER(providers.name)', strtolower($this->publishers_name), true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
                'pageSize' => 50,
            ),
			'sort'     =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'publishers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            '*',
	            ),
	        ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sites the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function findByPublishersId($id)
	{
		$criteria = new CDbCriteria;
		$criteria->compare("publishers_providers_id", $id);
		
		return new CActiveDataProvider($this, array(
			'criteria'   =>$criteria,
			'pagination' =>false,
		));
	}
}
