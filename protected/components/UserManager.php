<?php

/**
 * UserManager encapsulates functionality regarding user info, redirect and validation.
 */
class UserManager
{

	private static $instance = NULL;

	private function __construct() { }

	public static function model()
    {
		if (self::$instance == NULL)
			self::$instance = new self;
		return self::$instance;
    }

    /**
     * Return TRUE if $userID has assign the role specified, FALSE otherwise.
     *
     * If $userID are not specified validate using current user.
     * 
     * @param  mixed   $role 	could be an array or a string
     * @param  int     $userID
     * @return boolean
     */
    public function isUserAssignToRole($role, $userID=NULL)
    {
    	if (Yii::app()->user->id == NULL) // if user is not login return false
    		return false;

    	if ($userID == NULL) // if user is not specified, assigned current user
    		$userID = Yii::app()->user->id;

	    $roles = Yii::app()->authManager->getRoles($userID);
    	if ( is_array($role) ) {
    		foreach ($role as $r)
    			if ( in_array($r, array_keys($roles)) )
    				return true;
    		return false;
    	} else {
			return in_array($role, array_keys($roles));
    	}
    }

    /**
     * [redirectToIndex description]
     * @return [type] [description]
     */
    public function redirectToIndex()
    {
    	if ($this->isUserAssignToRole('affiliate'))
			Yii::app()->controller->redirect(Yii::app()->baseUrl.'/partners/affiliates');

		if ($this->isUserAssignToRole('advertiser'))
			Yii::app()->controller->redirect(Yii::app()->baseUrl.'/partners/advertisers');
    }

}