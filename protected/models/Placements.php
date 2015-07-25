<?php

/**
 * This is the model class for table "placements".
 *
 * The followings are the available columns in table 'placements':
 * @property integer $id
 * @property integer $sites_id
 * @property integer $sizes_id
 * @property string $name
 * @property string $product
 * @property string $status
 * @property integer $ext_id
 * @property string $model
 * @property integer $publisher_percentage
 * @property string $rate
 *
 * The followings are the available model relations:
 * @property DailyPublishers[] $dailyPublishers
 * @property Publishers $publishers
 * @property BannerSizes $sizes
 * @property Sites $sites
 * @property Exchanges[] $exchanges
 */
class Placements extends CActiveRecord
{
	public $publishers_name;
	public $sites_name;
	public $exchanges_name;
	public $size;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'placements';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sites_id, sizes_id, name', 'required'),
			array('sites_id, sizes_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>8),
			array('name', 'length', 'max'=>128),
			array('model', 'length', 'max'=>3),
			array('rate', 'length', 'max'=>11),
			array('product', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sites_id, sizes_id, name, product, status, ext_id, model, publisher_percentage, rate, publishers_name, sites_name', 'safe', 'on'=>'search'),
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
			'dailyPublishers' => array(self::HAS_MANY, 'DailyPublishers', 'placements_id'),
			'sites'           => array(self::BELONGS_TO, 'Sites', 'sites_id'),
			// 'exchanges'       => array(self::BELONGS_TO, 'Exchanges', 'exchanges_id'),
			'sizes'           => array(self::BELONGS_TO, 'BannerSizes', 'sizes_id'),
			'exchanges'       => array(self::MANY_MANY, 'Exchanges','placements_has_exchanges(placements_id, exchanges_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                   => 'ID',
			// 'exchanges_id'      => 'Exchanges',
			'sites_id'             => 'Sites',
			'sizes_id'             => 'Sizes',
			'name'                 => 'Name',
			'product'              => 'Product',
			'publishers_name'      => 'Publishers',
			'exchanges_name'       => 'Exchanges',
			'size'                 => 'Size',
			'status'               => 'Status',
			'ext_id'               => 'Ext',
			'model'                => 'Model',
			'publisher_percentage' => 'RS Perc.',
			'rate'                 => 'Rate',
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
		// $criteria->compare('t.exchanges_id',$this->exchanges_id);
		$criteria->compare('t.sites_id',$this->sites_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sizes_id',$this->sizes_id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.product',$this->product,true);
		$criteria->compare('t.ext_id',$this->ext_id);
		$criteria->compare('t.model',$this->model,true);
		$criteria->compare('t.publisher_percentage',$this->publisher_percentage);
		$criteria->compare('t.rate',$this->rate,true);

		$criteria->with = array('sites', 'sites.publishersProviders', 'sites.publishersProviders.providers','exchanges', 'sizes');
		$criteria->compare('LOWER(providers.name)',strtolower($this->publishers_name),true);
		$criteria->compare('LOWER(sites.name)',strtolower($this->sites_name),true);
		// $criteria->compare('exchanges.name',$this->exchanges_name,true);
		$criteria->compare('sizes.size',$this->size,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' =>array(
                'pageSize' => 50,
            ),
			'sort'     	 =>array(
		        'attributes'=>array(
					// Adding custom sort attributes
		            'publishers_name'=>array(
						'asc'  =>'providers.name',
						'desc' =>'providers.name DESC',
		            ),
		            'sites_name'=>array(
						'asc'  =>'sites.name',
						'desc' =>'sites.name DESC',
		            ),
		    		// 'exchanges_name'=>array(
						// 'asc'  =>'exchanges.name',
						// 'desc' =>'exchanges.name DESC',
		    		// ),
		            'size'=>array(
						'asc'  =>'sizes.size',
						'desc' =>'sizes.size DESC',
		            ),
		            '*',
		        ),
		    ),
		));
	}

	public function findBySitesId($id)
	{
		$criteria = new CDbCriteria;
		$criteria->compare("t.sites_id", $id);
		
		return new CActiveDataProvider($this, array(
			'criteria'   =>$criteria,
			'pagination' =>false,
		));
	
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Placements the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getExternalName()
	{
		$return = $this->id . '-';
		$return.= $this->sites->publishersProviders->providers->prefix . '-';
		$return.= $this->sites->name . '-';
		$return.= $this->name;
		return $return;
	}
}
