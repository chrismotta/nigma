<?php

/**
 * This is the model class for table "tags".
 *
 * The followings are the available columns in table 'tags':
 * @property integer $id
 * @property integer $campaigns_id
 * @property integer $banner_sizes_id
 * @property string $type
 * @property string $code
 * @property string $comment
 * @property integer $analyze
 *
 * The followings are the available model relations:
 * @property ImpLog[] $impLogs
 * @property BannerSizes $bannerSizes
 * @property Campaigns $campaigns
 */
class Tags extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tags';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'required'),
			array('campaigns_id, banner_sizes_id, analyze', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>10),
			array('code', 'length', 'max'=>255),
			array('comment', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaigns_id, banner_sizes_id, type, code, comment, analyze', 'safe', 'on'=>'search'),
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
			'impLogs' => array(self::HAS_MANY, 'ImpLog', 'tags_id'),
			'bannerSizes' => array(self::BELONGS_TO, 'BannerSizes', 'banner_sizes_id'),
			'campaigns' => array(self::BELONGS_TO, 'Campaigns', 'campaigns_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'campaigns_id' => 'Campaigns',
			'banner_sizes_id' => 'Banner Sizes',
			'type' => 'Type',
			'code' => 'Code',
			'comment' => 'Comment',
			'analyze' => 'Analyze',
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
		$criteria->compare('campaigns_id',$this->campaigns_id);
		$criteria->compare('banner_sizes_id',$this->banner_sizes_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('analyze',$this->analyze);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tags the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
