<?php

/**
 * This is the model class for table "placements_has_exchanges".
 *
 * The followings are the available columns in table 'placements_has_exchanges':
 * @property integer $placements_id
 * @property integer $exchanges_id
 * @property integer $step
 */
class PlacementsHasExchanges extends CActiveRecord
{
	public $exchanges_name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'placements_has_exchanges';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('placements_id, exchanges_id', 'required'),
			array('placements_id, exchanges_id, step', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('placements_id, exchanges_id, step, exchanges_name', 'safe', 'on'=>'search'),
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
			'exchanges' => array(self::BELONGS_TO, 'Exchanges', 'exchanges_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'placements_id'  => 'Placements',
			'exchanges_id'   => 'Exchanges',
			'step'           => 'Step',
			'exchanges_name' => 'Exchange',
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

		$criteria->compare('placements_id',$this->placements_id);
		$criteria->compare('exchanges_id',$this->exchanges_id);
		$criteria->compare('step',$this->step);

		$criteria->with = array('exchanges');
		$criteria->compare('exchanges.name',$this->exchanges_name);
		
		$criteria->order = 'step ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'     	 =>array(
		        'attributes'=>array(
		            'exchanges_name'=>array(
						'asc'  =>'exchanges.name',
						'desc' =>'exchanges.name DESC',
		            ),
		            '*',
		        ),
		    ),
		));
	}

	public static function reSort($id){

		$criteria = new CDbCriteria;
		$criteria->compare("t.placements_id", $id);
		$criteria->order = "step ASC";

		$query = PlacementsHasExchanges::model()->findAll($criteria);
		$list = CHtml::listData($query, 'exchanges_id', 'exchanges_id');		

		$i = 1;
		foreach ($list as $value) {
			$model = PlacementsHasExchanges::model()->findByAttributes(
				array(
					'placements_id' => $id,
					'exchanges_id'  => $value,
					));
			$model->step = $i;
			$model->save();
			$i++;
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlacementsHasExchanges the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
