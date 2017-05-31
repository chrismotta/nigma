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
    private $_timestamp;

    public function __construct ( $id, $module, $config = [] )
    {
        parent::__construct( $id, $module, $config );

        $this->_redis       = new \Predis\Client( 'tcp://'.localConfig::REDIS_HOST.':6379' );

        $this->_objectLimit = isset( $_GET['objectlimit'] ) ? $_GET['objectlimit'] : 50000;

        $this->_timestamp   = time();

        if ( !preg_match( '/^[0-9]+$/',$this->_objectLimit) )
        {
            die('invalid object limit');
        }

        \ini_set('memory_limit','3000M');
        \set_time_limit(0);
    }

    public function actionIndex( )
    {
        $start = time();

        self::actionDemand();
        self::actionSupply();
        self::actionImpressions();
        
        \gc_collect_cycles();
    }


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
        $start    = time();
        $logCount = $this->_redis->zcard( 'sessionhashes' );
        $queries  = ceil( $logCount/$this->_objectLimit );
        $startAt  = 0;
        $rows     = 0;

        // build separate sql queries based on $_objectLimit in order to control memory usage
        for ( $i=0; $i<$queries; $i++ )
        {
            $rows += $this->_buildImpressionsQuery();
        }

        $elapsed = time() - $start;

        echo 'Impressions: '.$rows.' rows - queries: '.$queries.' - load time: '.$elapsed.' seg.<hr/>';
    }


    private function _buildImpressionsQuery ( )
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

        $sessionHashes = $this->_redis->zrange( 'sessionhashes', 0, $this->_objectLimit );

        if ( $sessionHashes )
        {
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
                    {
                        if ( $log['connection_type']== '3g' || $log['connection_type']== '3G' )
                            $log['connection_type']= 'MOBILE';

                        $values .= '"'.strtoupper($log['connection_type']).'",';
                    }
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

            if ( $values != '' )
            {
                $sql .= $values . ' ON DUPLICATE KEY UPDATE cost=VALUES(cost), imps=VALUES(imps), revenue=VALUES(revenue), ad_req=VALUES(ad_req);';              

                $return = Yii::app()->db->createCommand( $sql )->execute();

                foreach ( $sessionHashes AS $sessionHash )
                {
                    $this->_redis->zadd( 'loadedlogs', $this->_timestamp, $sessionHash );
                }

                $this->_redis->zremrangebyrank( 'sessionhashes', 0, $this->_objectLimit );

                return $return;
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