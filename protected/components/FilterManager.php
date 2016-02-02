<?php

/**
 * FilterManager encapsulates functionality to applying filtering to CDbCriteria.
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
	 * Specify the scenario for user filtering. The scenarios indicate column to add 
	 * the search criteria, and the roles who have complete access.
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
				'advertisers',
			)),
		'daily.commercial' => array( 
			'column' => 'ios.commercial_id', 
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
				'finance',
			)),
		'campaign.commercial' => array( 
			'column' => 'ios.commercial_id', 
			'roles'  => array( 
				'admin',
				'media_manager',
				'sem',
				'finance',
			)),
		'campaign.post_data' => array(
			'column' => '',
			'roles' => array(
				'admin',
				'media_manager',
			)),
		'finance.clients' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'admin',
				'media_manager',
				'finance',
			)),
		'media' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'media',
			)),
		'affiliate' => array( 
			'column' => 'affiliates.id', 
			'roles'  => array( 
				'affiliate',
			)),
		'advertiser' => array( 
			'column' => 'advertisers.id', 
			'roles'  => array( 
				'advertiser',
			)),
		'publisher' => array( 
			'column' => 'publishers.providers_id', 
			'roles'  => array( 
				'publisher',
			)),
		'clients.validateOpportunity' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'admin',
				'media',
			)),
		'clients.invoice' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'admin',
				'finance',
			)),
		'clients.validateIo' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'admin',
				'business',
				'media_manager',
				'finance',
			)),
		'clients.count' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'admin',
				'business',
				'media_manager',
			)),
		'alert.business' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'business',
			)),
		'alert.media' => array( 
			'column' => 'opportunities.account_manager_id', 
			'roles'  => array( 
				'media',
			)),
		'ios' => array( 
			'column' => 't.commercial_id', 
			'roles'  => array( 
				'admin',
				'media_manager',
				'commercial_manager',
				'finance',
				'business',
			)),
		'opportunities' => array( 
			'column' => 'ios.commercial_id', 
			'roles'  => array( 
				'admin',
				'media_manager',
				'commercial_manager',
				'finance',
				'business',
			)),
		'advertisers' => array( 
			'column' => 'ios.commercial_id', 
			'roles'  => array( 
				'admin',
				'media_manager',
				'commercial_manager',
				'finance',
				'business',
			)),
		'alert.finance' => array( 
			'column' => 'users.id', 
			'roles'  => array( 
				'finance',
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

    /**
     * Return TRUE if current user has total access for the scenario specified, FALSE 
     * otherwise.
     *
     * The scenarios are define in class attribute $userScenarios
     * 
     * @param  string  $scenario
     * @return boolean
     */
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