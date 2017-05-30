<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

spl_autoload_unregister(array('YiiBase', 'autoload'));
require_once(dirname(__FILE__).'/../external/vendor/autoload.php');
require_once(dirname(__FILE__).'/../config/localConfig.php');
spl_autoload_register(array('YiiBase', 'autoload'));


use Predis;

class Etl2Controller extends Controller
{
	private $_redis;
	private $_objectLimit;
	private $_lastEtlTime;
	private $_currentEtlTime;

	public function __construct ( $id, $module, $config = [] )
	{
		parent::__construct( $id, $module, $config );

    	$this->_redis 	 	  	= new \Predis\Client( 'tcp://'.localConfig::REDIS_HOST.':6379' );

    	$this->_objectLimit 	= 100000; // how many objects to process at once

    	$lastEtlTime   			= $this->_redis->get( 'last_etl_time');
    	$this->_lastEtlTime 	= $lastEtlTime ?  $lastEtlTime : 0;
    	$this->_currentEtlTime	= time();        	

		\ini_set('memory_limit','3000M');
		\set_time_limit(0);
	}


	public function actionIndex( )
	{
		$start = time();

		self::actionDemand();
		self::actionSupply();
		self::actionImpressions();

		//$this->_redis->set( 'last_etl_time', $this->_currentEtlTime );
        
		\gc_collect_cycles();
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

	public function actionDemand(){

		$start = time();

		$query ='INSERT IGNORE INTO D_Demand (tag_id, advertiser, finance_entity, region, opportunity, campaign, tag, rate, freq_cap, country, connection_type, device_type, os_type, os_version) 
		SELECT t.id, 
			CONCAT(a.name," (",a.id,")"), 
			CONCAT(f.name," (",f.id,")"), 
			CONCAT(g.name," (",r.id,")"), 
			CONCAT(o.product," (",o.id,")"), 
			CONCAT(o.product," - ",c.name," (",c.id,")"), 
			CONCAT(t.name," (",t.id,")"), 
			o.rate, t.freq_cap, t.country, t.connection_type, t.device_type, t.os, t.os_version 
		FROM tags t 
		LEFT JOIN campaigns c        ON(t.campaigns_id        = c.id) 
		LEFT JOIN opportunities o    ON(c.opportunities_id    = o.id) 
		LEFT JOIN regions r          ON(o.regions_id          = r.id) 
		LEFT JOIN geo_location g     ON(r.country_id          = g.id_location) 
		LEFT JOIN finance_entities f ON(r.finance_entities_id = f.id) 
		LEFT JOIN advertisers a      ON(f.advertisers_id      = a.id)
		ON DUPLICATE KEY UPDATE
			advertiser     =CONCAT(a.name," (",a.id,")"), 
			finance_entity =CONCAT(f.name," (",f.id,")"), 
			region         =CONCAT(g.name," (",r.id,")"), 
			opportunity    =CONCAT(o.product," (",o.id,")"), 
			campaign       =CONCAT(o.product," - ",c.name," (",c.id,")"), 
			tag            =CONCAT(t.name," (",t.id,")"), 
			rate=o.rate, freq_cap=t.freq_cap, country=t.country, connection_type=t.connection_type, device_type=t.device_type, os_type=t.os, os_version=t.os_version
		';
		
		$return = Yii::app()->db->createCommand($query)->execute();

		$elapsed = time() - $start;

		echo 'ETL Demand: '.$return.' rows inserted - Elapsed time: '.$elapsed.' seg.<hr/>';
	}


	public function actionSupply(){
		
		$start = time();

		$query = 'INSERT IGNORE INTO D_Supply (placement_id, provider, site, placement, rate) 
		SELECT p.id, 
			CONCAT(o.name," (",o.id,")"), 
			CONCAT(s.name," (",s.id,")"), 
			CONCAT(p.name," (",p.id,")"), 
			p.rate 
		FROM placements p 
		LEFT JOIN sites s     ON(p.sites_id     = s.id) 
		LEFT JOIN providers o ON(s.providers_id = o.id)
		ON DUPLICATE KEY UPDATE
			provider  =CONCAT(o.name," (",o.id,")"), 
			site      =CONCAT(s.name," (",s.id,")"), 
			placement =CONCAT(p.name," (",p.id,")"), 
			rate=p.rate
		';
	
		$return = Yii::app()->db->createCommand($query)->execute();

		$elapsed = time() - $start;

		echo 'ETL Supply: '.$return.' rows inserted - Elapsed time: '.$elapsed.' seg.<hr/>';
	}


    public function actionImpressions ( )
    {      
    	$start 			     = time();
    	$clusterLogCount     = $this->_redis->zcount( 'sessionhashes', $this->_lastEtlTime, $this->_currentEtlTime );
    	$queries 		     = ceil( $clusterLogCount/$this->_objectLimit );
    	$startAt 		     = 0;
    	$rows   		     = 0;

    	// build separate sql queries based on $_objectLimit in order to control memory usage
    	for ( $i=0; $i<$queries; $i++ )
    	{
    		// call each query from a separated method in order to force garbage collection (and free memory)
    		$rows += $this->_buildImpressionsQuery( $startAt, $startAt+$this->_objectLimit );
			$startAt += $this->_objectLimit;
    	}

		$elapsed = time() - $start;

		echo 'Impressions: '.$rows.' rows - queries: '.$queries.' - load time: '.$elapsed.' seg.<hr/>';
    }

    private function _buildImpressionsQueryDebug ( $start_at, $end_at )
    {
        $values    = '';        

        $sessionHashes = $this->_redis->zrangebyscore( 'sessionhashes', $this->_lastEtlTime, $this->_currentEtlTime,  'LIMIT', $start_at, $end_at );

        //$start_memory = memory_get_usage();
        //echo 'query => from '. $start_at.' to: '.$end_at.'<br>';  

        if ( $sessionHashes )
        {
            $params = [];
            $c = 0;
            // add each log to sql query
            foreach ( $sessionHashes as $sessionHash )
            {
                $log = $this->_redis->hgetall( 'log:'.$sessionHash );

                if ( $log )
                {   
                    $values = '
                        INSERT INTO F_Imp_Compact (                
                            D_Demand_id,
                            D_Supply_id,
                            ad_req,
                            imps,                
                            date_time,
                            cost,
                            revenue,
                            unique_id,
                            pubid,
                            server_ip,
                            country,
                            carrier,
                            connection_type,
                            user_agent,
                            device_type,
                            device_brand, 
                            device_model,
                            os_type,
                            os_version,
                            browser_type,
                            browser_version                
                        )
                        VALUES                      
                    ';                    

                    // with params
                    /*
                    $values .= '(
                        :p01'.$c.',
                        :p02'.$c.',
                        :p03'.$c.',
                        :p04'.$c.',
                        :p05'.$c.',
                        :p06'.$c.',
                        :p07'.$c.',
                        :p08'.$c.',
                        :p09'.$c.',
                        :p10'.$c.',
                        :p11'.$c.',
                        :p12'.$c.',
                        :p13'.$c.',
                        :p14'.$c.',
                        :p15'.$c.',
                        :p16'.$c.',
                        :p17'.$c.',
                        :p18'.$c.',
                        :p19'.$c.',
                        :p20'.$c.',
                        :p11'.$c.'
                    )';

                    $params[':p01'.$c]  = $log['tag_id'];
                    $params[':p02'.$c]  = $log['placement_id'] ? $log['placement_id'] : null;
                    $params[':p03'.$c]  = $log['imps'];
                    $params[':p04'.$c]  = $log['imps'];
                    $params[':p05'.$c]  = \date( 'Y-m-d H:i:s', $log['imp_time'] );
                    $params[':p06'.$c]  = $log['cost'];
                    $params[':p07'.$c]  = $log['revenue'];
                    $params[':p08'.$c]  = $sessionHash;
                    $params[':p09'.$c]  = $log['publisher_id'] ? $log['publisher_id'] : null;
                    $params[':p10'.$c]  = $log['ip'];
                    $params[':p11'.$c]  = \strtoupper($log['country']);
                    $params[':p12'.$c]  = $log['carrier'];
                    $params[':p13'.$c]  = strtoupper($log['connection_type']);
                    $params[':p14'.$c]  = null;

                    if ( isset($log['device']) )
                        $params[':p15'.$c]  = $log['device'];
                    else
                        $params[':p15'.$c]  = null;

                    if ( isset($log['device_brand']) )
                        $params[':p16'.$c]  = $log['device_brand'];
                    else
                        $params[':p16'.$c]  = null;

                    if ( isset($log['device_model']) )
                        $params[':p17'.$c]  = $log['device_model'];
                    else
                        $params[':p17'.$c]  = null;                    

                    if ( isset($log['os']) )
                        $params[':p18'.$c]  = $log['os'];
                    else
                        $params[':p18'.$c]  = null;

                    if ( isset($log['os_version']) )
                        $params[':p19'.$c]  = $log['os_version'];
                    else
                        $params[':p19'.$c]  = null;    

                    if ( isset($log['browser']) )
                        $params[':p20'.$c]  = $log['browser'];
                    else
                        $params[':p20'.$c]  = null;

                    if ( isset($log['browser_version']) )
                        $params[':p21'.$c]  = $log['browser_version'];
                    else
                        $params[':p21'.$c]  = null;  

                    $c++;
                    */
                    // without params
                    
                    if ( $log['publisher_id'] )
                        $pubId = $log['publisher_id'];
                    else
                        $pubId = 'NULL';

                    if ( $log['placement_id'] )
                        $pid = $log['placement_id'];
                    else
                        $pid = 'NULL';

                    $values .= '( 
                        '.$log['tag_id'].',
                        '.$pid.',
                        '.$log['imps'].',  
                        '.$log['imps'].', 
                        "'.\date( 'Y-m-d H:i:s', $log['imp_time'] ).'",                 
                        '.$log['cost'].',  
                        '.$log['revenue'].',  
                        "'.$sessionHash.'",
                        '.$pubId.',
                        "'.$log['ip'].'",
                    ';

                    if ( $log['country'] )
                        $values .= '"'.strtoupper($log['country']).'",';
                    else
                        $values .= 'NULL,';

                    if ( $log['carrier'] )
                        $values .= '"'.$log['carrier'].'",';
                    else
                        $values .= 'NULL,';

                    if ( $log['connection_type'] )
                        $values .= '"'.strtoupper($log['connection_type']).'",';
                    else
                        $values .= 'NULL,';

                    if ( $log['user_agent'] )                        
                        $values .= '"'.$log['user_agent'].'",';
                    else
                        $values .= 'NULL,';

                    if ( !isset($log['device']) )
                        $log['device'] = null;
                    else if ( $log['device']=='Phablet' || $log['device']=='Smartphone' )
                        $log['device'] = 'Mobile';

                    if ( isset($log['device']) && $log['device'] )
                        $values .= '"'.$log['device'].'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['device_brand']) && $log['device_brand'] )
                        $values .= '"'.$log['device_brand'].'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['device_model']) && $log['device_model'] )
                        $values .= '"'.$log['device_model'].'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['os']) && $log['os'] )
                        $values .= '"'.$log['os'].'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['os_version']) && $log['os_version'] )
                        $values .= '"'.$log['os_version'].'",';
                    else
                        $values .= 'NULL,';   

                    if ( isset($log['browser']) && $log['browser'] )
                        $values .= '"'.$log['browser'].'",';
                    else
                        $values .= 'NULL,';  

                    if ( isset($log['browser_version']) && $log['browser_version'] )
                        $values .= '"'.$log['browser_version'].'"';
                    else
                        $values .= 'NULL';                                      

                    $values .= ') ';       
           
                }

                // free memory because there is no garbage collection until block ends
                unset ( $log );

                $values .= ' ON DUPLICATE KEY UPDATE cost=VALUES(cost), imps=VALUES(imps);';

                $return = Yii::app()->db->createCommand( $values )->execute();                
            }

            return $return;
            //$memoryUsage = (( memory_get_usage() - $start_memory )/1024);
        }  

        unset( $sessionHashes );

        return 0;
    }

    private function _buildImpressionsQuery ( $start_at, $end_at )
    {
    	$sql = '
    		INSERT INTO F_Imp_Compact (                
    			D_Demand_id,
    			D_Supply_id,
                ad_req,
    			imps,                
    			date_time,
    			cost,
    			revenue,
    			unique_id,
    			pubid,
                server_ip,
                country,
                carrier,
                connection_type,
                user_agent,
                device_type,
                device_brand, 
                device_model,
                os_type,
                os_version,
                browser_type,
                browser_version                
    		)
			VALUES  
    	';

    	$values    = '';  		
    	$geoValues = '';

		$sessionHashes = $this->_redis->zrangebyscore( 'sessionhashes', $this->_lastEtlTime, $this->_currentEtlTime,  'LIMIT', $start_at, $end_at );

        //$start_memory = memory_get_usage();
        //echo 'query => from '. $start_at.' to: '.$end_at.'<br>';  

		if ( $sessionHashes )
		{
            $params = [];
            $c = 0;
			// add each log to sql query
    		foreach ( $sessionHashes as $sessionHash )
    		{
    			$log = $this->_redis->hgetall( 'log:'.$sessionHash );

                if ( $log )
                {   
                    if ( !\filter_var($log['ip'], \FILTER_VALIDATE_IP) || !preg_match('/^[a-zA-Z]{2}$/', $log['country']) )
                    {
                        $ips = \explode( ',', $log['ip'] );
                        $log['ip'] = $ips[0];

                        $location = new IP2Location(Yii::app()->params['ipDbFile'], IP2Location::FILE_IO);
                        $ipData      = $location->lookup($log['ip'], IP2Location::ALL);

                        $log['carrier'] = $ipData->mobileCarrierName;
                        $log['country'] = $ipData->countryCode;

                        if ( $ipData->mobileCarrierName == '-' )
                            $log['connection_type'] = 'WIFI';
                        else
                            $log['connection_type'] = 'MOBILE';
                    }

                    if ( $values != '' )
                        $values .= ',';                    
                    
                    if ( $log['publisher_id'] )
                        $pubId = $log['publisher_id'];
                    else
                        $pubId = 'NULL';

                    if ( $log['placement_id'] )
                        $pid = $log['placement_id'];
                    else
                        $pid = 'NULL';

                    $values .= '( 
                        '.$log['tag_id'].',
                        '.$pid.',
                        '.$log['imps'].',  
                        '.$log['imps'].', 
                        "'.\date( 'Y-m-d H:i:s', $log['imp_time'] ).'",                 
                        '.$log['cost'].',  
                        '.$log['revenue'].',  
                        "'.$sessionHash.'",
                        '.$pubId.',
                        "'.$log['ip'].'",
                    ';

                    if ( $log['country'] )
                        $values .= '"'.strtoupper($log['country']).'",';
                    else
                        $values .= 'NULL,';

                    if ( $log['carrier'] )
                        $values .= '"'.$this->_escapeSql( $log['carrier'] ).'",';
                    else
                        $values .= 'NULL,';

                    if ( $log['connection_type'] )
                        $values .= '"'.strtoupper($log['connection_type']).'",';
                    else
                        $values .= 'NULL,';

                    if ( $log['user_agent'] )                        
                        $values .= '"'.$this->_escapeSql( $log['user_agent'] ).'",';
                    else
                        $values .= 'NULL,';

                    if ( !isset($log['device']) )
                        $log['device'] = null;
                    else if ( $log['device']=='Phablet' || $log['device']=='Smartphone' )
                        $log['device'] = 'Mobile';

                    if ( isset($log['device']) && $log['device'] )
                        $values .= '"'.$log['device'].'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['device_brand']) && $log['device_brand'] )
                        $values .= '"'.$this->_escapeSql( $log['device_brand'] ).'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['device_model']) && $log['device_model'] )
                        $values .= '"'.$this->_escapeSql( $log['device_model'] ).'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['os']) && $log['os'] )
                        $values .= '"'.$log['os'].'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['os_version']) && $log['os_version'] )
                        $values .= '"'.$this->_escapeSql( $log['os_version'] ).'",';
                    else
                        $values .= 'NULL,';   

                    if ( isset($log['browser']) && $log['browser'] )
                        $values .= '"'.$this->_escapeSql( $log['browser'] ).'",';
                    else
                        $values .= 'NULL,';  

                    if ( isset($log['browser_version']) && $log['browser_version'] )
                        $values .= '"'.$this->_escapeSql( $log['browser_version'] ).'"';
                    else
                        $values .= 'NULL';                                      

                    $values .= ')';       
           
                }

                // free memory because there is no garbage collection until block ends
                unset ( $log );
    		}

            //$memoryUsage = (( memory_get_usage() - $start_memory )/1024);

    		if ( $values != '' )
    		{
	    		$sql .= $values . ' ON DUPLICATE KEY UPDATE cost=VALUES(cost), imps=VALUES(imps);';

	    		return Yii::app()->db->createCommand( $sql )->execute($params);			
    		}
		}  

        unset( $sessionHashes );

		return 0;
    }


    private function _escapeSql( $sql )
    {
        return preg_replace(
            [
                '/(NUL)/',
                '/(BS)/',
                '/(TAB)/',
                '/(LF)/',
                '/(CR)/',
                '/(SUB)/',
                '/(")/',
                '/(%)/',
                '/(\\\')/',
                '/(\\\\)/',
                '/(_)/'
            ],
            [
                '\0',
                '\b',
                '\t',
                '\n',
                '\r',
                '\Z',
                '\"',
                '\%',
                '\\\'',
                '\\\\',
                '\\'
            ],
            $sql
        );
    }


    public function actionPopulatecache ( )
    {
        self::actionPopulatetags();
        self::actionPopulateplacements();
    }


    public function actionPopulatetags()
    {
        $start = time();

        $tags = Tags::model()->findAll();

        foreach ( $tags as $tag )
        {
            switch ( $tag->connection_type )
            {
                case 'WIFI':
                    $conn_type = 'wifi';
                break;
                case '3G':
                    $conn_type = 'mobile';
                break;
                default:
                    $conn_type = null;
                break;
            }

            switch ( $tag->country )
            {
                case null:
                case '':
                case '-':
                    $country = null;
                break;
                default:
                    $country = strtolower( $tag->country );
                break;
            }            

            $this->_redis->hmset(
                'tag:'.$tag->id,
                [
                    'code'            => $tag->code,
                    'analyze'         => $tag->analyze,
                    'frequency_cap'   => $tag->freq_cap,
                    'payout'          => $tag->campaigns->opportunities->rate,
                    'connection_type' => $conn_type,
                    'country'         => $country,
                    'os'              => $tag->os
                ]
            );
        }


        $elapsed = time() - $start;

        echo 'Tags cached: '.count($tags).' - Elapsed time: '.$elapsed.' seg.<hr/>';
    }	


    public function actionPopulateplacements()
    {
        $start = time();

        $placements = Placements::model()->findAll();

        foreach ( $placements as $placement )
        {
            $this->_redis->hmset(
                'placement:'.$placement->id,
                [
                    'payout'          => $placement->rate
                ]
            );
        }


        $elapsed = time() - $start;

        echo 'Placements cached: '.count($placements).' - Elapsed time: '.$elapsed.' seg.<hr/>';        
    }


}