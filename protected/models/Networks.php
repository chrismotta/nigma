<?php

/**
 * This is the model class for table "networks".
 *
 * The followings are the available columns in table 'networks':
 * @property integer $id
 * @property integer $rec
 * @property string $name
 * @property string $url
 * @property string $query_string
 * @property string $token1
 * @property string $token2
 * @property string $token3
 */
class Networks extends CActiveRecord
{
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
			array('name, url, query_string', 'required'),
			array('rec', 'numerical', 'integerOnly'=>true),
			array('name, url, query_string, token1, token2, token3', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rec, name, url, query_string, token1, token2, token3', 'safe', 'on'=>'search'),
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
			'rec' => 'Rec',
			'name' => 'Name',
			'url' => 'Url',
			'query_string' => 'Query String',
			'token1' => 'Token1',
			'token2' => 'Token2',
			'token3' => 'Token3',
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
		$criteria->compare('rec',$this->rec);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('query_string',$this->query_string,true);
		$criteria->compare('token1',$this->token1,true);
		$criteria->compare('token2',$this->token2,true);
		$criteria->compare('token3',$this->token3,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
