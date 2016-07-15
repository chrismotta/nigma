<?php

/**
 * This is the model class for table "landing_images".
 *
 * The followings are the available columns in table 'landing_images':
 * @property integer $id
 * @property string $file_name
 * @property string $type
 *
 * The followings are the available model relations:
 * @property Landings[] $landings
 * @property Landings[] $landings1
 */
class LandingImages extends CActiveRecord
{
	public $image;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'landing_images';
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
			array('file_name', 'length', 'max'=>128),
			array('type', 'length', 'max'=>45),
			array('image', 'file','types'=>'jpg', 'allowEmpty'=>true, 'on'=>'update'), // this will allow empty field when page is update (remember here i create scenario update)
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, file_name, type', 'safe', 'on'=>'search'),
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
			'landings' => array(self::HAS_MANY, 'Landings', 'background_images_id'),
			'landings1' => array(self::HAS_MANY, 'Landings', 'byline_images_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'file_name' => 'File Name',
			'type' => 'Type',
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
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LandingImages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getImagePath($file_name){
	    $baseUrl = Yii::app()->theme->baseUrl;
		$imgPath = $baseUrl . '/lp_img/';
		return $imgPath . $file_name;
	}
}
