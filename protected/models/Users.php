<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property integer $rec
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $name
 * @property string $lastname
 * @property integer $status
 * @property integer $role
 * @property integer $permissions
 *
 * The followings are the available model relations:
 * @property Ios[] $ioses
 * @property Opportunities[] $opportunities
 */
class Users extends CActiveRecord
{
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
			array('username, password, email, name, lastname', 'required'),
			array('rec, status, role, permissions', 'numerical', 'integerOnly'=>true),
			array('username, password, email, name, lastname', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rec, username, password, email, name, lastname, status, role, permissions', 'safe', 'on'=>'search'),
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
			'ioses' => array(self::HAS_MANY, 'Ios', 'user_id'),
			'opportunities' => array(self::HAS_MANY, 'Opportunities', 'manager_id'),
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
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'name' => 'Name',
			'lastname' => 'Lastname',
			'status' => 'Status',
			'role' => 'Role',
			'permissions' => 'Permissions',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('role',$this->role);
		$criteria->compare('permissions',$this->permissions);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
        return Users::model()->findAll($criteria);
	}
}
