<?php

/**
 * This is the model class for table "landings".
 *
 * The followings are the available columns in table 'landings':
 * @property integer $id
 * @property string $default_color
 * @property string $highlight_color
 * @property string $background_color
 * @property integer $background_images_id
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
 *
 * The followings are the available model relations:
 * @property LandingImages $backgroundImages
 */
class Landings extends CActiveRecord
{
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
			array('background_images_id', 'numerical', 'integerOnly'=>true),
			array('default_color, highlight_color, background_color, input_label, input_eg, select_label, button_label', 'length', 'max'=>45),
			array('headline, byline, input_legend, tyc_headline, checkbox_label', 'length', 'max'=>128),
			array('select_options, tyc_body', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, default_color, highlight_color, background_color, background_images_id, headline, byline, input_legend, input_label, input_eg, select_label, select_options, tyc_headline, tyc_body, checkbox_label, button_label', 'safe', 'on'=>'search'),
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
			'backgroundImages' => array(self::BELONGS_TO, 'LandingImages', 'background_images_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'default_color' => 'Default Color',
			'highlight_color' => 'Highlight Color',
			'background_color' => 'Background Color',
			'background_images_id' => 'Background Images',
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
		$criteria->compare('default_color',$this->default_color,true);
		$criteria->compare('highlight_color',$this->highlight_color,true);
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
