<?php

/**
 * This is the model class for table "tags".
 *
 * The followings are the available columns in table 'tags':
 * @property integer $id
 * @property integer $campaigns_id
 * @property integer $banner_sizes_id
 * @property string $code
 * @property string $comment
 * @property integer $analyze
 * @property integer $freq_cap
 * @property string $country
 * @property string $connection_type
 * @property string $device_type
 * @property string $os
 * @property string $os_version
 *
 * The followings are the available model relations:
 * @property ImpLog[] $impLogs
 * @property BannerSizes $bannerSizes
 * @property Campaigns $campaigns
 */
class Tags extends CActiveRecord
{
	public $size;

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
			array('campaigns_id, banner_sizes_id, analyze, freq_cap', 'numerical', 'integerOnly'=>true),
			array('banner_sizes_id', 'required'),
			array('code, passback_tag', 'safe'),
			array('url', 'length', 'max'=>255),
			array('name, comment', 'length', 'max'=>128),
			array('country', 'length', 'max'=>2),
			// array('connection_type', 'length', 'max'=>4),
			// array('device_type', 'length', 'max'=>7),
			array('os', 'length', 'max'=>10),
			array('os_version', 'length', 'max'=>45),
			array('country, connection_type, device_type, os, os_version', 'default', 'setOnEmpty' => true, 'value' => null),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, campaigns_id, banner_sizes_id, url, code, passback_tag, comment, analyze, freq_cap, size, country, connection_type, device_type, os, os_version, status', 'safe', 'on'=>'search'),
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
			'id'              => 'ID',
			'campaigns_id'    => 'Campaigns',
			'banner_sizes_id' => 'Banner Sizes',
			'code'            => 'Code',
			'passback_tag'    => 'Passback Tag',
			'comment'         => 'Comment',
			'analyze'         => 'Analyze',
			'freq_cap'        => 'Frequency Cap',
			'country'         => 'Country (ISO2)',
			'connection_type'  => 'Connection Type',
			'device_type'     => 'Device Type',
			'os'              => 'OS',
			'os_version'      => 'Min. OS Version',
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
		$criteria->compare('name',$this->name);
		$criteria->compare('campaigns_id',$this->campaigns_id);
		$criteria->compare('banner_sizes_id',$this->banner_sizes_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('passback_tag',$this->passback_tag,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('analyze',$this->analyze);
		$criteria->compare('freq_cap',$this->freq_cap);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('connection_type',$this->connection_type,true);
		$criteria->compare('device_type',$this->device_type,true);
		$criteria->compare('os',$this->os,true);
		$criteria->compare('os_version',$this->os_version,true);
		$criteria->compare('status',$this->status,true);

		$criteria->with = array('bannerSizes');
		$criteria->select = array(
			't.*',
			'bannerSizes.size as size'
			);
		$criteria->compare('bannerSizes.size',$this->size);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>100,
            ),
			'sort'     =>array(
		        'attributes'=>array(
		            'size'=>array(
						'asc'  =>'bannerSizes.size',
						'desc' =>'bannerSizes.size DESC',
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
	 * @return Tags the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
