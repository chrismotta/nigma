<?php

/**
 * This is the model class for table "landings".
 *
 * The followings are the available columns in table 'landings':
 * @property integer $id
 * @property string $name
 * @property integer $country_id
 * @property string $default_color
 * @property string $highlight_color
 * @property string $background_color
 * @property string $headline
 * @property string $byline
 * @property string $input_legend
 * @property string $input_label
 * @property string $input_eg
 * @property string $select_label
 * @property string $select_options
 * @property string $tyc_headline
 * @property string $tyc_body
 * @property string $checkbox_label
 * @property string $button_label
 * @property string $thankyou_msg
 * @property string $validate_msg
 * @property integer $background_images_id
 * @property integer $headline_images_id
 * @property integer $byline_images_id
 *
 * The followings are the available model relations:
 * @property GeoLocation $country
 * @property LandingImages $backgroundImages
 * @property LandingImages $headlineImages
 * @property LandingImages $bylineImages
 */
class Landings extends CActiveRecord
{
	public $country_name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'landings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tyc_body', 'required'),
			array('country_id, byline_images_id, headline_images_id, background_images_id', 'numerical', 'integerOnly'=>true),
			array('name, headline, byline, input_legend, tyc_headline, checkbox_label, thankyou_msg, validate_msg', 'length', 'max'=>128),
			array('default_color, highlight_color, background_color, input_label, input_eg, select_label, button_label', 'length', 'max'=>45),
			array('select_options', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, country_id, country_name, default_color, highlight_color, byline_images_id, background_color, background_images_id, headline_images_id, headline, byline, input_legend, input_label, input_eg, select_label, select_options, tyc_headline, tyc_body, checkbox_label, button_label, thankyou_msg, validate_msg', 'safe', 'on'=>'search'),
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
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'backgroundImages' => array(self::BELONGS_TO, 'LandingImages', 'background_images_id'),
			'bylineImages' => array(self::BELONGS_TO, 'LandingImages', 'byline_images_id'),
			'headlineImages' => array(self::BELONGS_TO, 'LandingImages', 'headline_images_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'country_id' => 'Country',
			'country_name' => 'Country',
			'default_color' => 'Default Color',
			'highlight_color' => 'Highlight Color',
			'byline_images_id' => 'Byline Images',
			'background_color' => 'Background Color',
			'background_images_id' => 'Background Images',
			'headline_images_id' => 'Headline Images',
			'headline' => 'Headline',
			'byline' => 'Byline',
			'input_legend' => 'Input Legend',
			'input_label' => 'Input Label',
			'input_eg' => 'Input Eg',
			'select_label' => 'Select Label',
			'select_options' => 'Select Options',
			'tyc_headline' => 'Tyc Headline',
			'tyc_body' => 'Tyc Body',
			'checkbox_label' => 'Checkbox Label',
			'button_label' => 'Button Label',
			'thankyou_msg' => 'Thankyou Msg',
			'validate_msg' => 'Validate Msg',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('country.name',$this->country_name);
		$criteria->compare('default_color',$this->default_color,true);
		$criteria->compare('highlight_color',$this->highlight_color,true);
		$criteria->compare('byline_images_id',$this->byline_images_id);
		$criteria->compare('headline_images_id',$this->headline_images_id);
		$criteria->compare('background_color',$this->background_color,true);
		$criteria->compare('background_images_id',$this->background_images_id);
		$criteria->compare('headline',$this->headline,true);
		$criteria->compare('byline',$this->byline,true);
		$criteria->compare('input_legend',$this->input_legend,true);
		$criteria->compare('input_label',$this->input_label,true);
		$criteria->compare('input_eg',$this->input_eg,true);
		$criteria->compare('select_label',$this->select_label,true);
		$criteria->compare('select_options',$this->select_options,true);
		$criteria->compare('tyc_headline',$this->tyc_headline,true);
		$criteria->compare('tyc_body',$this->tyc_body,true);
		$criteria->compare('checkbox_label',$this->checkbox_label,true);
		$criteria->compare('button_label',$this->button_label,true);
		$criteria->compare('thankyou_msg',$this->thankyou_msg,true);
		$criteria->compare('validate_msg',$this->validate_msg,true);

		$criteria->with = array('country');
		$criteria->select = array(
			'*',
			'country.name as country_name',
			);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'     =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'country_name'=>array(
						'asc'  =>'country.name',
						'desc' =>'country.name DESC',
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
	 * @return Landings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
