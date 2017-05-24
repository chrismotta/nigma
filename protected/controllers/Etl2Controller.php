<?php
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
		self::actionUseragent();
		self::actionImpressions();

		$this->_redis->set( 'last_etl_time', $this->_currentEtlTime );
        
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


	public function actionUseragent ( ){

		$start 		  = time();
		$c     		  = 0;
        $userAgentIDs = $this->_redis->zrangebyscore('uas', $this->_lastEtlTime, $this->_currentEtlTime);

        $query 		  = '
        	INSERT IGNORE INTO D_UserAgent 
        		(
        			id, 
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

        $values = '';

        foreach ( $userAgentIDs as $id )
        {
            $ua = $this->_redis->hgetall( 'ua:'.$id );

            if ( $ua )
            {
                if ( $c > 0 )
                    $values .= ',';

                $values .= '("'.$id.'",';

                if ( $ua['device']=='Phablet' || $ua['device']=='Smartphone' )
                    $ua['device'] = 'Mobile';

                if ( $ua['device'] )
                    $values .= '"'.$ua['device'].'",';
                else
                    $values .= 'NULL,';

                if ( $ua['device_brand'] )
                    $values .= '"'.$ua['device_brand'].'",';
                else
                    $values .= 'NULL,';

                if ( $ua['device_model'] )
                    $values .= '"'.$ua['device_model'].'",';
                else
                    $values .= 'NULL,';

                if ( $ua['os'] )
                    $values .= '"'.$ua['os'].'",';
                else
                    $values .= 'NULL,';

                if ( $ua['os_version'] )
                    $values .= '"'.$ua['os_version'].'",';
                else
                    $values .= 'NULL,';   

                if ( $ua['browser'] )
                    $values .= '"'.$ua['browser'].'",';
                else
                    $values .= 'NULL,';  

                if ( $ua['browser_version'] )
                    $values .= '"'.$ua['browser_version'].'"';
                else
                    $values .= 'NULL';  

                $values .= ')';

                $c++;                
            }

            // free memory cause there is no garbage collection until block ends
            unset($ua);
        }

        if ( $values != '' )
        {
            $query .= $values . ';';

            $return = Yii::app()->db->createCommand($query)->execute();            
        }
        else
        {
            $return = 0;
        }


		$elapsed = time() - $start;

		echo 'ETL UserAgent: '.$return.' rows inserted - Elapsed time: '.$elapsed.' seg.<br/>';
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


    private function _buildImpressionsQuery ( $start_at, $end_at )
    {
        $geoSql 		  = '
        	INSERT IGNORE INTO D_GeoLocation 
        		(
        			id,
        			country,
        			carrier,
        			connection_type
        		)
        	VALUES 
        ';

    	$sql = '
    		INSERT INTO F_Imp_Compact (                
    			D_Demand_id,
    			D_Supply_id,
    			D_Geolocation_id,
    			D_UserAgent_id,
                ad_req,
    			imps,                
    			date_time,
    			cost,
    			revenue,
    			unique_id,
    			pubid
    		)
			VALUES  
    	';

    	$values    = '';  		
    	$geoValues = '';

        echo 'query => '. $start_at.': '.$end_at.'<br>';

		$sessionHashes = $this->_redis->zrangebyscore( 'sessionhashes', $this->_lastEtlTime, $this->_currentEtlTime,  'LIMIT', $start_at, $end_at );

		if ( $sessionHashes )
		{
			// add each log to sql query
    		foreach ( $sessionHashes as $sessionHash )
    		{
    			$log = $this->_redis->hgetall( 'log:'.$sessionHash );

                if ( $log )
                {
                    if ( $values != '' )
                        $values .= ',';

                    if ( $log['publisher_id'] )
                        $pubId = $log['publisher_id'];
                    else
                        $pubId = 'NULL';

                    $values .= '( 
                        '.$log['tag_id'].',
                        '.$log['placement_id'].',
                        "'.$log['ip'].'",
                        "'.$log['user_agent'].'",
                        '.$log['imps'].',  
                        '.$log['imps'].', 
                        "'.\date( 'Y-m-d H:i:s', $log['imp_time'] ).'",                 
                        '.$log['cost'].',  
                        '.$log['revenue'].',  
                        "'.$sessionHash.'",
                        '.$pubId.'
                    )';

                    if ( $geoValues != '' )
                        $geoValues .= ',';

                    $geoValues .= '("'.$log['ip'].'",';

                    if ( $log['country'] )
                        $geoValues .= '"'.$log['country'].'",';
                    else
                        $geoValues .= 'NULL,';

                    if ( $log['carrier'] )
                        $geoValues .= '"'.$log['carrier'].'",';
                    else
                        $geoValues .= 'NULL,';

                    if ( $log['connection_type'] )
                        $geoValues .= '"'.strtoupper($log['connection_type']).'"';
                    else
                        $geoValues .= 'NULL';
                

                    $geoValues .= ')';                    
                }

                // free memory because there is no garbage collection until block ends
                unset ( $log );
    		}

    		if ( $geoValues != '' )
    		{
	    		$geoSql .= $geoValues . ';';

	    		$r = Yii::app()->db->createCommand( $geoSql )->execute();			

	    		echo 'ETL GeoLocation: '.$r.' rows inserted<br/>';
    		}

    		if ( $values != '' )
    		{
	    		$sql .= $values . ' ON DUPLICATE KEY UPDATE cost=VALUES(cost), imps=VALUES(imps);';

	    		return Yii::app()->db->createCommand( $sql )->execute();			
    		}
		}

        unset( $sessionHashes );

		return 0;
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
            $this->_redis->hmset(
                'tag:'.$tag->id,
                [
                    'code'            => $tag->code,
                    'analyze'         => $tag->analyze,
                    'frecuency_cap'   => $tag->freq_cap,
                    'payout'          => $tag->campaigns->opportunities->rate,
                    'connection_type' => $tag->connection_type,
                    'country'         => $tag->country,
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