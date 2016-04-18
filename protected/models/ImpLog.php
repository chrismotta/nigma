<?php

/**
 * This is the model class for table "imp_log".
 *
 * The followings are the available columns in table 'imp_log':
 * @property integer $id
 * @property integer $tags_id
 * @property integer $placements_id
 * @property string $date
 * @property string $pubid
 * @property string $server_ip
 * @property string $user_agent
 * @property string $referer
 * @property string $ip_forwarded
 * @property string $app
 *
 * The followings are the available model relations:
 * @property Placements $placements
 * @property Tags $tags
 */
class ImpLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'imp_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date', 'required'),
			array('tags_id, placements_id', 'numerical', 'integerOnly'=>true),
			// array('pubid, server_ip, user_agent, referer, ip_forwarded, app', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tags_id, placements_id, date, pubid, server_ip, user_agent, referer, ip_forwarded, app', 'safe', 'on'=>'search'),
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
			'placements' => array(self::BELONGS_TO, 'Placements', 'placements_id'),
			'tags' => array(self::BELONGS_TO, 'Tags', 'tags_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tags_id' => 'Tags',
			'placements_id' => 'Placements',
			'date' => 'Date',
			'pubid' => 'Pubid',
			'server_ip' => 'Server Ip',
			'user_agent' => 'User Agent',
			'referer' => 'Referer',
			'ip_forwarded' => 'Ip Forwarded',
			'app' => 'App',
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
		$criteria->compare('tags_id',$this->tags_id);
		$criteria->compare('placements_id',$this->placements_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('pubid',$this->pubid,true);
		$criteria->compare('server_ip',$this->server_ip,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('referer',$this->referer,true);
		$criteria->compare('ip_forwarded',$this->ip_forwarded,true);
		$criteria->compare('app',$this->app,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ImpLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// macros

	public function macros()
	{
		return array(
			'{pubid}' => isset($this->pubid) ? urlencode($this->pubid) : '',
			);
	}

	public function hasMacro($url)
	{
		preg_match('%\{[a-z \_]+\}%', $url, $match);
		return isset($match[0]) ? true : false;
	}
	
	public function replaceMacro($url)
	{	
		return str_replace(array_keys(self::macros()),array_values(self::macros()),$url);
	}

}
