<?php

/**
 * This is the model class for table "vectors_has_campaigns".
 *
 * The followings are the available columns in table 'vectors_has_campaigns':
 * @property integer $vectors_id
 * @property integer $campaigns_id
 * @property integer $freq
 */
class VectorsHasCampaigns extends CActiveRecord
{
	public $connection;
	public $carrier;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vectors_has_campaigns';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vectors_id, campaigns_id', 'required'),
			array('vectors_id, campaigns_id, freq', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, vectors_id, campaigns_id, freq', 'safe', 'on'=>'search'),
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
			'campaigns' => array(self::BELONGS_TO, 'Campaigns', 'campaigns_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vectors_id'   => 'Vectors',
			'campaigns_id' => 'Campaigns',
			'freq'         => 'Freq',
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

		$criteria->compare('vectors_id',            $this->vectors_id);
		$criteria->compare('campaigns_id',          $this->campaigns_id);
		$criteria->compare('freq',                  $this->freq);
		$criteria->compare('opportunities.wifi',    $this->connection);
		$criteria->compare('carriers.mobile_brand', $this->carrier);

		$criteria->with = array('campaigns.opportunities', 'campaigns.opportunities.carriers');
		$criteria->select = array(
			't.*',
			'opportunities.wifi AS connection',
			'carriers.mobile_brand AS carrier'
			);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> KHtml::pagination(),
			'sort'       => array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'connection'=>array(
						'asc'  =>'opportunities.wifi',
						'desc' =>'opportunities.wifi DESC',
		            ),
		            'carrier'=>array(
						'asc'  =>'carriers.mobile_brand',
						'desc' =>'carriers.mobile_brand DESC',
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
	 * @return VectorsHasCampaigns the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
