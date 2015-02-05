<?php

/**
 * This is the model class for table "networks".
 *
 * The followings are the available columns in table 'networks':
 * @property integer $providers_id
 * @property string $percent_off
 * @property string $url
 * @property integer $use_alternative_convention_name
 * @property integer $has_api
 * @property integer $use_vectors
 * @property string $query_string
 * @property string $token1
 * @property string $token2
 * @property string $token3
 *
 * The followings are the available model relations:
 * @property Providers $providers
 */
class Networks extends CActiveRecord
{

	public $providers_name;
	public $providers_has_s2s;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'networks';
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
			array('providers_id, use_alternative_convention_name, has_api, use_vectors', 'numerical', 'integerOnly'=>true),
			array('url', 'length', 'max'=>128),
			array('query_string', 'length', 'max'=>255),
			array('prefix','unique', 'message'=>'This prefix already exists.'),
			array('token1, token2, token3', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('providers_id, percent_off, url, use_alternative_convention_name, has_api, use_vectors, query_string, token1, token2, token3', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'providers_id'                    => 'ID',
			'providers_name'                  => 'Name',
			'providers_has_s2s'               => 'Has s2s',
			'percent_off'                     => 'Percent Off',
			'url'                             => 'Url',
			'use_alternative_convention_name' => 'Use Alternative Convention Name',
			'has_api'                         => 'Has Api',
			'use_vectors'                     => 'Use Vectors',
			'query_string'                    => 'Query String',
			'token1'                          => 'Token1',
			'token2'                          => 'Token2',
			'token3'                          => 'Token3',
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

		$criteria->with = array('providers');
		$criteria->compare('providers.status','Active',true);
		$criteria->addCondition('providers.prospect>1');

		$criteria->compare('providers_id',$this->providers_id);
		$criteria->compare('percent_off',$this->percent_off,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('use_alternative_convention_name',$this->use_alternative_convention_name);
		$criteria->compare('has_api',$this->has_api);
		$criteria->compare('use_vectors',$this->use_vectors);
		$criteria->compare('query_string',$this->query_string,true);
		$criteria->compare('token1',$this->token1,true);
		$criteria->compare('token2',$this->token2,true);
		$criteria->compare('token3',$this->token3,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize'=>30,
            ),
			'sort'     => array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'providers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            'providers_has_s2s'=>array(
						'asc'  =>'providers.has_s2s',
						'desc' =>'providers.has_s2s DESC',
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
	 * @return Networks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
