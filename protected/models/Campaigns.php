<?php

/**
 * This is the model class for table "campaigns".
 *
 * The followings are the available columns in table 'campaigns':
 * @property integer $id
 * @property integer $rec
 * @property integer $opportunities_id
 * @property string $name
 * @property string $url
 * @property integer $campaign_categories_id
 * @property integer $offer_type
 * @property integer $currency
 * @property integer $budget_type
 * @property string $budget
 * @property string $cap
 * @property integer $model
 * @property string $bid
 * @property string $comment
 * @property integer $status
 * @property string $date_start
 * @property string $date_end
 * @property string $gc_id
 * @property string $gc_language
 * @property integer $gc_format
 * @property string $gc_color
 * @property string $gc_label
 * @property string $gr_only
 *
 * The followings are the available model relations:
 * @property Opportunities $opportunities
 * @property CampaignCategories $campaignCategories
 */
class Campaigns extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'campaigns';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('opportunities_id, name, campaign_categories_id, budget, cap, bid, comment, date_start, date_end', 'required'),
			array('rec, opportunities_id, campaign_categories_id, offer_type, currency, budget_type, model, status, gc_format', 'numerical', 'integerOnly'=>true),
			array('name, comment', 'length', 'max'=>128),
			array('url', 'length', 'max'=>256),
			array('budget, cap, bid', 'length', 'max'=>11),
			array('gc_id, gc_language, gc_color, gr_only', 'length', 'max'=>10),
			array('gc_label', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rec, opportunities_id, name, url, campaign_categories_id, offer_type, currency, budget_type, budget, cap, model, bid, comment, status, date_start, date_end, gc_id, gc_language, gc_format, gc_color, gc_label, gr_only', 'safe', 'on'=>'search'),
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
			'opportunities' => array(self::BELONGS_TO, 'Opportunities', 'opportunities_id'),
			'campaignCategories' => array(self::BELONGS_TO, 'CampaignCategories', 'campaign_categories_id'),
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
			'opportunities_id' => 'Opportunities',
			'name' => 'Name',
			'url' => 'Url',
			'campaign_categories_id' => 'Campaign Categories',
			'offer_type' => 'Offer Type',
			'currency' => 'Currency',
			'budget_type' => 'Budget Type',
			'budget' => 'Budget',
			'cap' => 'Cap',
			'model' => 'Model',
			'bid' => 'Bid',
			'comment' => 'Comment',
			'status' => 'Status',
			'date_start' => 'Date Start',
			'date_end' => 'Date End',
			'gc_id' => 'Gc',
			'gc_language' => 'Gc Language',
			'gc_format' => 'Gc Format',
			'gc_color' => 'Gc Color',
			'gc_label' => 'Gc Label',
			'gr_only' => 'Gr Only',
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
		$criteria->compare('opportunities_id',$this->opportunities_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('campaign_categories_id',$this->campaign_categories_id);
		$criteria->compare('offer_type',$this->offer_type);
		$criteria->compare('currency',$this->currency);
		$criteria->compare('budget_type',$this->budget_type);
		$criteria->compare('budget',$this->budget,true);
		$criteria->compare('cap',$this->cap,true);
		$criteria->compare('model',$this->model);
		$criteria->compare('bid',$this->bid,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_end',$this->date_end,true);
		$criteria->compare('gc_id',$this->gc_id,true);
		$criteria->compare('gc_language',$this->gc_language,true);
		$criteria->compare('gc_format',$this->gc_format);
		$criteria->compare('gc_color',$this->gc_color,true);
		$criteria->compare('gc_label',$this->gc_label,true);
		$criteria->compare('gr_only',$this->gr_only,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Campaigns the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
