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
			'opportunities' => array(self::HAS_MANY, 'Opportunities', 'account_manager_id'),
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>50,
            ),
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
        return Users::model()->findAll($criteria, array('order' => 'name'));
	}
	public function getFullName() 
	{		
        return $this->lastname.' '.$this->name;
	}
}
