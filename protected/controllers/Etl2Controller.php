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
    CONST ALERT_FROM = 'Nigma<no-reply@tmlbox.co>';
    CONST ALERT_TO   = 'daniel@themedialab.co,chris@themedialab.co';

    private $_redis;
    private $_objectLimit;
    private $_timestamp;
    private $_limit;
    private $_parsedLogs;
    private $_executedQueries;
    private $_date;
    private $_tag;
    private $_placement;
    private $_showsql;
    private $_sqltest;
    private $_alertSubject;

    public function __construct ( $id, $module, $config = [] )
    {
        parent::__construct( $id, $module, $config );

        $this->_redis       = new \Predis\Client( 'tcp://'.localConfig::REDIS_HOST.':6379' );

        $this->_objectLimit = isset( $_GET['objectlimit'] ) ? $_GET['objectlimit'] : 50000;

        if ( !preg_match( '/^[0-9]+$/',$this->_objectLimit) || (int)$this->_objectLimit<1 )
        {
            die('invalid object limit');
        }

        $this->_limit = isset( $_GET['limit'] ) ? $_GET['limit'] : false;

        if ( $this->_limit && !preg_match( '/^[0-9]+$/',$this->_limit ) )
        {
            die('invalid limit');
        }

        $this->_timestamp       = time();
        $this->_date            = isset( $_GET['date'] ) ? $_GET['date'] : date("Y-m-d", strtotime("yesterday") );
        $this->_tag             = isset( $_GET['tag'] ) ? $_GET['tag'] : null;
        $this->_placement       = isset( $_GET['placement'] ) ? $_GET['placement'] : null;        
        $this->_showsql         = isset( $_GET['showsql'] ) ? true : false;
        $this->_sqltest         = isset( $_GET['sqltest'] ) ? true : false;

        $this->_timestamp       = time();
        $this->_parsedLogs      = 0;
        $this->_executedQueries = 0;

        $this->_alertSubject    = 'AD NIGMA - ETL2 ERROR ' . date( "Y-m-d H:i:s", $this->_timestamp );


        \ini_set('memory_limit','3000M');
        \set_time_limit(0);
    }

    public function actionIndex( )
    {
        $start = time();
        $msg   = '';

        //set_error_handler( array( $this, 'handleErrors' ) );
        try
        {
            self::actionDemand();
        }
        catch ( Exception $e )
        {
            $msg .= "ETL DEMAND ERROR: ".$e->getCode().'<hr>';
            $msg .= $e->getMessage();

            $this->_sendMail ( self::ALERT_FROM, self::ALERT_TO, $this->_alertSubject, $msg );

            die($msg);
        }
        
        try
        {
            self::actionSupply();
        }
        catch ( Exception $e )
        {
            $msg .= "ETL SUPPLY ERROR: ".$e->getCode().'<hr>';
            $msg .= $e->getMessage();

            $this->_sendMail ( self::ALERT_FROM, self::ALERT_TO, $this->_alertSubject, $msg );

            die($msg);           
        }
        
        try
        {
            self::actionImpressions();
        } 
        catch (Exception $e) {
            $msg .= "ETL IMPRESSIONS ERROR: ".$e->getCode().'<hr>';
            $msg .= $e->getMessage();

            $this->_sendMail ( self::ALERT_FROM, self::ALERT_TO, $this->_alertSubject, $msg );

            die($msg);
        }
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

        if ( $this->_showsql )
            echo 'SQL: ';

        // build separate sql queries based on $_objectLimit in order to control memory usage
        for ( $i=0; $i<$queries; $i++ )
        {
            if ( $this->_limit  &&  $this->_parsedLogs >= $this->_limit )
                break;            

            $rows += $this->_buildImpressionsQuery();
        }

        $elapsed = time() - $start;

        if ( $this->_showsql || $this->_sqltest )
            echo '<hr/>';

        echo 'Impressions: '.$rows.' rows - queries: '.$this->_executedQueries.' - load time: '.$elapsed.' seg.<hr/>';
    }


    private function _sendmail ( $from, $to, $subject, $body )
    {
        $headers = 'From:'.$from.'\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset="UTF-8"\r\n';

        if ( !mail($to, $subject, $body, $headers ) )
        {
            $data = 'To: '.$to.'\nSubject: '.$subject.'\nFrom:'.$from.'\n'.$body;

            $command = 'echo -e "'.$data.'" | sendmail -bm -t -v';
            $command = '
                export MAILTO="'.$to.'"
                export FROM="'.$from.'"
                export SUBJECT="'.$subject.'"
                export BODY="'.$body.'"
                (
                 echo "From: $FROM"
                 echo "To: $MAILTO"
                 echo "Subject: $SUBJECT"
                 echo "MIME-Version: 1.0"
                 echo "Content-Type: text/html; charset=UTF-8"
                 echo $BODY
                ) | /usr/sbin/sendmail -F $MAILTO -t -v -bm
            ';

            shell_exec( $command );             
        }           
    }


    private function _buildImpressionsQuery ( )
    {
        $sql = '
            INSERT IGNORE INTO F_Imp_Compact (                
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
                browser_version,
                ad_server_id 
            )
            VALUES  
        ';

        $values    = '';        

        $sessionHashes = $this->_redis->zrange( 'sessionhashes', 0, $this->_objectLimit-1 );

        if ( $sessionHashes )
        {
            //echo 'query => from 0 to: '.count($sessionHashes).'/'.$this->_redis->zcard('sessionhashes').'<br>';

            $hashCount = 0;
            // add each log to sql query
            foreach ( $sessionHashes as $sessionHash )
            {
                if ( $this->_limit  &&  $this->_parsedLogs >= $this->_limit )
                    break;

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
                        $pid = $this->_escapeSql( $log['placement_id'] );
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
                        "'.$pubId.'",
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
                        $values .= '"'.md5( $log['user_agent'] ).'",';
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
                        $values .= '"'.$this->_escapeSql( $log['os']).'",';
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
                        $values .= '"'.$this->_escapeSql( $log['browser_version'] ).'",';
                    else
                        $values .= 'NULL,';

                    $values .= '2';// 2=nigma2

                    $values .= ')';

                    $this->_parsedLogs++;      
                }

                $hashCount++;

                // free memory because there is no garbage collection until block ends
                unset ( $log );
            }

            if ( $values != '' )
            {
                $sql .= $values . ' ON DUPLICATE KEY UPDATE cost=VALUES(cost), imps=VALUES(imps), revenue=VALUES(revenue), ad_req=VALUES(ad_req);';              

                if ( $this->_showsql || $this->_sqltest )
                    echo '<br><br>'.$sql;

                if ( $this->_sqltest )
                    die();


                $return = Yii::app()->db->createCommand( $sql )->execute();

                $hashCount2 = 0;

                foreach ( $sessionHashes AS $sessionHash )
                {
                    if ( $hashCount2 >= $hashCount )
                        break;

                    $this->_redis->zadd( 'loadedlogs', $this->_timestamp, $sessionHash );

                    $this->_redis->zrem( 'sessionhashes', $sessionHash );

                    $hashCount2++;            
                }                    


                $this->_executedQueries++;

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
                case 'wifi':
                case 'WiFi':
                    $conn_type = 'wifi';
                break;
                case '3G':
                case '3g':
                case 'MOBILE':
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


    public function actionStats ()
    {
        $date = isset( $_GET['date'] ) ? $_GET['date'] : date("Y-m-d",strtotime("yesterday"));

        $from            = strtotime( $date.' 00:00:00' );
        $to              = strtotime( $date.' 23:59:59' );

        $loadedLogsCount = $this->_redis->zcard( 'loadedlogs' );
        $hashCount       = $this->_redis->zcount( 'sessionhashes', $from, $to );
        $queries         = ceil( $loadedLogsCount/$this->_objectLimit );
        $mysqlMatches    = 0;
        $redisMatches    = 0;
        $startAt         = 0;
        $endAt           = $this->_objectLimit;

        for ( $i=0; $i<$queries; $i++ )
        {
            $hashes = '';

            $loadedLogs = $this->_redis->zrange( 'loadedlogs', $startAt, $endAt );
            foreach ( $loadedLogs AS $hash )
            {
                $impTime = $this->_redis->hmget( 'log:'.$hash, 'imp_time' );

                if ( !$impTime  ||  (int)$impTime[0] < $from  ||  (int)$impTime[0] > $to )
                    continue;

                if ( $hashes != '' )
                    $hashes .= ',';

                $hashes .= "'".$hash."'";

                $redisMatches++;
            }

            if ( $hashes != '' )
            {
                $sql = 'SELECT count(unique_id) AS c FROM F_Imp_Compact WHERE date(date_time)="'.$date.'" AND unique_id IN ('.$hashes.')';

                $command = Yii::app()->db->createCommand( $sql );
                $command->execute();
                $r = $command->queryRow();

                $mysqlMatches += $r['c'];
            }

            $startAt += $this->_objectLimit;
            $endAt   += $this->_objectLimit;
        }

        echo 'Pending (Redis): '.$hashCount.'<hr/>'; 
        echo 'Processed (Redis): '.$redisMatches.'<hr/>';
        echo 'Inserted (MySQL): '.$mysqlMatches.'<hr/>';
    }

    public function actionReport ()
    {
        if ( $this->_tag && ( !preg_match( '/^[0-9]+$/',$this->_tag) || (int)$this->_tag<1 ) )
        {
            die('invalid tag ID');
        }

        if ( $this->_placement && ( !preg_match( '/^[0-9]+$/',$this->_placement) || (int)$this->_placement<1 ) )
        {
            die('invalid placement ID');
        }

        $from            = strtotime( $this->_date.' 00:00:00' );
        $to              = strtotime( $this->_date.' 23:59:59' );
        $loadedLogsCount = $this->_redis->zcard( 'loadedlogs' );
        $queries         = ceil( $loadedLogsCount/$this->_objectLimit );
        $loadedImps      = 0;
        $loadedCost      = 0;
        $loadedRev       = 0;                
        $startAt         = 0;
        $endAt           = $this->_objectLimit;

        for ( $i=0; $i<$queries; $i++ )
        {
            $loadedLogs = $this->_redis->zrange( 'loadedlogs', $startAt, $endAt );

            foreach ( $loadedLogs AS $hash )
            {
                $log = $this->_redis->hgetall( 'log:'.$hash );

                if ( !$log['imp_time']  ||  (int)$log['imp_time'] < $from  ||  (int)$log['imp_time'] > $to )
                    continue;

                if ( $this->_tag  &&  $log['tag_id'] != $this->_tag )
                    continue;

                if ( $this->_placement  &&  $log['placement'] != $this->_placement )
                    continue;

                $loadedImps += $log['imps'];
                $loadedCost += $log['cost'];
                $loadedRev  += $log['revenue'];

                unset($log); 
            }           

            $startAt += $this->_objectLimit;
            $endAt   += $this->_objectLimit;

            unset($loadedLogs);
        }

        echo 'Loaded Imps: '.$loadedImps.'<hr/>'; 
        echo 'Loaded Revenue: '.$loadedRev.'<hr/>'; 
        echo 'Loaded Cost: '.$loadedCost.'<hr/>'; 
    }    
}