<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $name
 * @property string $lastname
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Advertisers[] $advertisers
 * @property Ios[] $ioses
 * @property Opportunities[] $opportunities
 */
class Users extends CActiveRecord
{
	// holds the password confirmation word
    public $repeat_password;
    public $partners_external_access;
    public $partners_external_access_type;
 
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, repeat_password, email, name, lastname', 'required'),
			array('username, password, email, name, lastname', 'length', 'max'=>128),
			array('status', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, email, name, lastname, status', 'safe', 'on'=>'search'),
            array('password, repeat_password', 'length', 'min'=>6, 'max'=>40),
            array('repeat_password', 'compare', 'compareAttribute'=>'password'),

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
			'advertisers' => array(self::HAS_MANY, 'Advertisers', 'commercial_id'),
			'ioses' => array(self::HAS_MANY, 'Ios', 'commercial_id'),
			'affiliates' => array(self::HAS_MANY, 'Affiliates', 'users_id'),
			'externalIoForms' => array(self::HAS_MANY, 'ExternalIoForm', 'commercial_id'),
			'financeEntities' => array(self::HAS_MANY, 'FinanceEntities', 'commercial_id'),
			'opportunities' => array(self::HAS_MANY, 'Opportunities', 'account_manager_id'),
			'publishers' => array(self::HAS_MANY, 'Publishers', 'account_manager_id'),
			'transactionCounts' => array(self::HAS_MANY, 'TransactionCount', 'users_id'),
			'transactionProviders' => array(self::HAS_MANY, 'TransactionProviders', 'users_id'),
			'visibilities' => array(self::HAS_MANY, 'Visibility', 'users_id'),
			'providers' => array(self::HAS_MANY, 'Providers', 'users_id'),
			'manager' => array(self::HAS_MANY, 'Providers', 'account_manager_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'              => 'ID',
			'username'        => 'Username',
			'password'        => 'Password',
			'repeat_password' => 'Repeat Password',
			'email'           => 'Email',
			'name'            => 'Name',
			'lastname'        => 'Lastname',
			'status'          => 'Status',
			'partners_external_access' => 'Partner',
			'partners_external_access_type' => 'Partner Type',
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
		$criteria->compare('t.username',$this->username,true);
		$criteria->compare('t.password',$this->password,true);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.lastname',$this->lastname,true);
		$criteria->compare('t.status',$this->status,true);
		$criteria->with = array('providers');

		// rol management
		$roles = array_keys(Yii::app()->authManager->getRoles(Yii::app()->user->id));
		if ( in_array('media_buyer_admin', $roles, true) ){
			$criteria->join = 'left join AuthAssignment aa on aa.userid = t.id';
			$criteria->addCondition('aa.itemname = "publisher"');
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> KHtml::pagination(),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function findUsersByRole($role) 
	{
		$users_ids = AuthAssignment::model()->getUsersIdsByRole($role);
		$criteria = new CDbCriteria;
        $criteria->addInCondition('id', $users_ids);
        $criteria->order = 'lastname';
        return Users::model()->findAll($criteria);
	}
	public function getFullName() 
	{		
        return $this->lastname.' '.$this->name;
	}

	public static function getPartnerPreview($user_id)
	{
		$roles=Yii::app()->authManager->getRoles($user_id);

		foreach ($roles as $role){
		    $currentRole = $role->name;
		    break;
		}
		if(isset($currentRole)){
			switch ($currentRole) {
				case 'advertiser':
					$return = "partners/previewAdvertisers";
					break;
				case 'publisher':
					$return = "partners/previewPublishers";
					break;
				case 'affiliate':
					$return = "partners/previewAffiliates";
					break;
				default:
					$return = "users/admin";
					break;
			}
		}else{
			$return = "users/notAssigned";
		}
			
		return $return;
	}


	public function getPartnerName($user_id, $type=false)
	{
		if($provider = Providers::model()->getExternalUser($user_id)){
			if($type){
				return $provider->type;
			}else{
				return $provider->name .' ('.$provider->id.')';
			}
		}

		if($advertiser = Advertisers::model()->getExternalUser($user_id)){
			if($type){
				return "Advertiser";
			}else{
				return $advertiser;
			}
			
		}

		return '-';
	}
}
