<?php

/**
 * 
 */
class FilterManager
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
	 * Specify the scenario for user filtering. The scenarios indicate column to add the search criteria,
	 * and the roles have complete access.
	 */
	private $userScenarios = array(
		'daily' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'admin',
				'media_manager',
				'bussiness',
				'finance',
				'sem',
			)),
		'campaign.account' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'admin',
				'media_manager',
				'sem',
			)),
		'campaign.commercial' => array( 
			'column' => 'opportunities.ios.commercial_id', 
			'roles'  => array( 
				'admin',
				'media_manager',
			)),
		);

    /**
     * 
     * @param CDbCriteria $criteria	criteria object.
     * @param string $scenario context to apply the filter
     */
    public function addUserFilter($criteria, $scenario)
    {
    	if ( ! array_key_exists($scenario, $this->userScenarios) )
    		return;

    	$currentUserId = Yii::app()->user->id;
    	$roles = Yii::app()->authManager->getRoles($currentUserId);
		$filter = true;
		foreach ( $roles as $role => $value) {
			if ( in_array($role, $this->userScenarios[$scenario]['roles']) )
				return;
		}

		$criteria->compare( $this->userScenarios[$scenario]['column'], $currentUserId );
    }

    public function isUserTotalAccess($scenario)
    {
    	if ( ! array_key_exists($scenario, $this->userScenarios) )
    		return false;

    	$currentUserId = Yii::app()->user->id;
    	$roles = Yii::app()->authManager->getRoles($currentUserId);
		foreach ( $roles as $role => $value) {
			if ( in_array($role, $this->userScenarios[$scenario]['roles']) )
				return true;
		}
		return false;
    }

}