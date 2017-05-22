<?php

/**
 * This is the model class for table "F_Imp_Compact".
 *
 * The followings are the available columns in table 'F_Imp_Compact':
 * @property integer $id
 * @property integer $D_Demand_id
 * @property integer $D_Supply_id
 * @property string $D_GeoLocation_id
 * @property string $D_UserAgent_id
 * @property string $date_time
 * @property string $unique_id
 * @property string $pubid
 * @property string $ip_forwarded
 * @property string $referer_url
 * @property string $referer_app
 * @property integer $imps
 * @property integer $unique_imps
 * @property string $revenue
 * @property string $cost
 */
class FImpCompact extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'F_Imp_Compact';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('D_Demand_id, D_Supply_id, D_GeoLocation_id, D_UserAgent_id, date_time, unique_id', 'required'),
			array('D_Demand_id, D_Supply_id, imps, unique_imps', 'numerical', 'integerOnly'=>true),
			array('D_GeoLocation_id, D_UserAgent_id, pubid, ip_forwarded, referer_url, referer_app', 'length', 'max'=>255),
			array('unique_id', 'length', 'max'=>40),
			array('revenue, cost', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, D_Demand_id, D_Supply_id, D_GeoLocation_id, D_UserAgent_id, date_time, unique_id, pubid, ip_forwarded, referer_url, referer_app, imps, unique_imps, revenue, cost', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'D_Demand_id' => 'D Demand',
			'D_Supply_id' => 'D Supply',
			'D_GeoLocation_id' => 'D Geo Location',
			'D_UserAgent_id' => 'D User Agent',
			'date_time' => 'Date Time',
			'unique_id' => 'Unique',
			'pubid' => 'Pubid',
			'ip_forwarded' => 'Ip Forwarded',
			'referer_url' => 'Referer Url',
			'referer_app' => 'Referer App',
			'imps' => 'Imps',
			'unique_imps' => 'Unique Imps',
			'revenue' => 'Revenue',
			'cost' => 'Cost',
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
		$criteria->compare('D_Demand_id',$this->D_Demand_id);
		$criteria->compare('D_Supply_id',$this->D_Supply_id);
		$criteria->compare('D_GeoLocation_id',$this->D_GeoLocation_id,true);
		$criteria->compare('D_UserAgent_id',$this->D_UserAgent_id,true);
		$criteria->compare('date_time',$this->date_time,true);
		$criteria->compare('unique_id',$this->unique_id,true);
		$criteria->compare('pubid',$this->pubid,true);
		$criteria->compare('ip_forwarded',$this->ip_forwarded,true);
		$criteria->compare('referer_url',$this->referer_url,true);
		$criteria->compare('referer_app',$this->referer_app,true);
		$criteria->compare('imps',$this->imps);
		$criteria->compare('unique_imps',$this->unique_imps);
		$criteria->compare('revenue',$this->revenue,true);
		$criteria->compare('cost',$this->cost,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FImpCompact the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
