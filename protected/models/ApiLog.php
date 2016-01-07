<?php

/**
 * This is the model class for table "api_log".
 *
 * The followings are the available columns in table 'api_log':
 * @property integer $id
 * @property integer $providers_id
 * @property integer $exchanges_id
 * @property string $status
 * @property string $start_time
 * @property string $end_time
 * @property string $data_date
 * @property string $message
 *
 * The followings are the available model relations:
 * @property Exchanges $exchanges
 * @property Providers $providers
 */
class ApiLog extends CActiveRecord
{
	public $providers_name;
	public $exchanges_name;
	public $elapsed_time;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_time', 'required'),
			array('providers_id, exchanges_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>10),
			array('end_time, data_date, message', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, providers_id, providers_name, exchanges_id, exchanges_name, status, start_time, end_time, elapsed_time, data_date, message', 'safe', 'on'=>'search'),
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
			'providers' => array(self::BELONGS_TO, 'Providers', 'providers_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'providers_id' => 'Providers',
			'providers_name' => 'Provider',
			'exchanges_id' => 'Exchanges',
			'exchanges_name' => 'Exchange',
			'status' => 'Status',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'data_date' => 'Data Date',
			'elapsed_time' => 'Elapsed',
			'message' => 'Message',
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
		$criteria->compare('t.providers_id',$this->providers_id);
		$criteria->compare('providers_name',$this->providers_name);
		$criteria->compare('t.exchanges_id',$this->exchanges_id);
		$criteria->compare('exchanges_name',$this->exchanges_name);
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('t.start_time',$this->start_time,true);
		$criteria->compare('t.end_time',$this->end_time,true);
		$criteria->compare('t.data_date',$this->data_date,true);
		$criteria->compare('t.message',$this->message,true);
		$criteria->compare('t.end_time - t.start_time', $this->elapsed_time);

		$criteria->with = array('providers', 'exchanges');
		$criteria->select = array(
			't.*',
			'providers.name AS providers_name',
			'exchanges.name AS exchanges_name',
			'IF(t.end_time, concat_ws(" ", t.end_time - t.start_time, "s."), NULL) AS elapsed_time',
			);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> KHtml::pagination(),
			'sort'     =>array(
				'defaultOrder' => 't.id DESC',
		        'attributes'=>array(
					// Adding custom sort attributes
		            'providers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            'exchanges_name'=>array(
						'asc'  =>'exchanges.name',
						'desc' =>'exchanges.name DESC',
		            ),
		            'elapsed_time'=>array(
						'asc'  =>'t.end_time - t.start_time',
						'desc' =>'t.end_time - t.start_time DESC',
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
	 * @return ApiLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function initLog($data_date, $providers_id=null, $exchanges_id=null){
		$model = new ApiLog();
		$model->data_date    = $data_date;
		$model->providers_id = $providers_id;
		$model->exchanges_id = $exchanges_id;
		$model->start_time   = date("Y-m-d H:i:s");
		$model->status       = 'Started';
		$model->message      = 'Starting API connection';

		if($model->save())
			return $model;
		else
			return null;
	}

	public function updateLog($status, $message=null){
		$this->status = $status;
		$this->message = $message;

		if($status == 'Completed')
			$this->end_time = date("Y-m-d H:i:s");

		if($this->save())
			return true;
		else
			return false;
	}
}
