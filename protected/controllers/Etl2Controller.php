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
    private $_startDate;
    private $_endDate;
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

        $this->_startDate       = isset( $_GET['from'] ) ? $_GET['from'] : date("Y-m-d", strtotime("yesterday") );

        $this->_endDate         = isset( $_GET['to'] ) ? $_GET['to'] : $this->_startDate;

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
        $this->_redis->select( $this->_getCurrentDatabase() );

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
                    
                    if ( $log['publisher_id'] && $log['publisher_id']!='' )
                        $pubId = $this->_escapeSql( substr( $log['publisher_id'], 0, 254 ) );
                    else
                        $pubId = 'NULL';

                    if ( $log['placement_id'] && $log['placement_id']!='' && preg_match( '/^[0-9]+$/',$log['placement_id'] ) )
                        $pid =  $log['placement_id'];
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

                    if ( $log['country'] && $log['country']!='' )
                        $values .= '"'.strtoupper($log['country']).'",';
                    else
                        $values .= 'NULL,';

                    if ( $log['carrier'] && $log['carrier']!='' )
                        $values .= '"'.$this->_escapeSql( $log['carrier'] ).'",';
                    else
                        $values .= 'NULL,';

                    if ( $log['connection_type'] && $log['connection_type']!='' )
                    {
                        if ( $log['connection_type']== '3g' || $log['connection_type']== '3G' )
                            $log['connection_type']= 'MOBILE';

                        $values .= '"'.strtoupper($log['connection_type']).'",';
                    }
                    else
                        $values .= 'NULL,';

                    if ( $log['user_agent'] && $log['user_agent']!='' )                        
                        $values .= '"'.md5( $log['user_agent'] ).'",';
                    else
                        $values .= 'NULL,';

                    if ( !isset($log['device']) )
                        $log['device'] = null;
                    else if ( $log['device']=='Phablet' || $log['device']=='Smartphone' )
                        $log['device'] = 'Mobile';

                    if ( isset($log['device']) && $log['device'] && $log['device']!='' )
                        $values .= '"'.$log['device'].'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['device_brand']) && $log['device_brand'] && $log['device_brand']!='' )
                        $values .= '"'.$this->_escapeSql( $log['device_brand'] ).'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['device_model']) && $log['device_model'] && $log['device_model']!='' )
                        $values .= '"'.$this->_escapeSql( $log['device_model'] ).'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['os']) && $log['os'] && $log['os']!='' )
                        $values .= '"'.$this->_escapeSql( $log['os']).'",';
                    else
                        $values .= 'NULL,';

                    if ( isset($log['os_version']) && $log['os_version'] && $log['os_version']!='' )
                        $values .= '"'.$this->_escapeSql( $log['os_version'] ).'",';
                    else
                        $values .= 'NULL,';   

                    if ( isset($log['browser']) && $log['browser'] && $log['browser']!='' )
                        $values .= '"'.$this->_escapeSql( $log['browser'] ).'",';
                    else
                        $values .= 'NULL,';  

                    if ( isset($log['browser_version']) && $log['browser_version'] && $log['browser_version']!='' )
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
                '/(\\\\)/',
                '/(NUL)/',
                '/(BS)/',
                '/(TAB)/',
                '/(LF)/',
                '/(CR)/',
                '/(SUB)/',
                '/(%)/',                
                "/(')/",
                '/(")/',
                '/(_)/'
            ],
            [
                '\\\\\\',
                '\0',
                '\b',
                '\t',
                '\n',
                '\r',
                '\Z',
                '\%',                
                "\\'",
                '\"',
                '\\_'
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
        $this->_redis->select( 0 );

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
        $this->_redis->select( 0 );

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


    public function actionDailymaintenance ( )
    {
        $this->_redis->select( $this->_getCurrentDatabase() );

        $dates     = $this->_redis->smembers( 'dates' );
        $html      = '';

        foreach ( $dates as $date )
        {   
            $miniDate  = date( 'Ymd', strtotime($date) );
            $logCount  = $this->_redis->zcard( 'tags:'.$miniDate );
            $queries   = (int)ceil( $logCount/($this->_objectLimit/2) );

            for ( $i=0; $i<$queries; $i++ )
            {
                $html .= $this->_maintenanceQuery( 
                    $date,
                    $miniDate 
                );
            }

            unset( $logCount );
        }

        if ( $html != '' )
        {
            $html     = '
                <html>
                    <head>
                    </head>
                    <body>
                        <table>
                            <thead>
                                <td>DATE</td>
                                <td>TAG ID</td>
                                <td>REDIS IMPS</td>
                                <td>MYSQL IMPS</td>
                                <td>REDIS COST</td>
                                <td>MYSQL COST</td>
                                <td>REDIS REVENUE</td>
                                <td>MYSQL REVENUE</td>
                            </thead>
                            <tbody>'.$html.'</tbody>
                        </table>
                    </body>
                </html>
            ';
            
            echo $html;

            $this->_sendMail ( 
                self::ALERT_FROM, 
                self::ALERT_TO, 
                'AD NIGMA - TRAFFIC COMPARE ERROR ('.$date.')', 
                $html 
            );
        }
        else
        {        
            echo ( 'todo bien piola' );
        }
    }


    private function _maintenanceQuery ( $date, $miniDate )
    {
        $limit     = $this->_objectLimit/2;
        $redisTags = $this->_redis->zrange( 'tags:'.$miniDate, 0, $limit );

        $sql       = 'SELECT DISTINCT D_Demand_id AS id, sum(imps) AS imps, sum(cost) AS cost, sum(revenue) AS revenue FROM F_Imp_Compact WHERE date(date_time)="'.$date.'" GROUP BY D_Demand_id LIMIT '. $limit;

        $tmpSqlTags   = Yii::app()->db->createCommand( $sql )->queryAll();        
        $sqlTags = [];

        foreach ( $tmpSqlTags as $tmpSqlTag )
        {
            $sqlTagId           = $tmpSqlTag['id'];
            $sqlTags[$sqlTagId] = [
                'imps'     => $tmpSqlTag['imps'],
                'cost'     => $tmpSqlTag['cost'],
                'revenue'  => $tmpSqlTag['revenue']
            ];
        }

        unset ( $tmpSqlTags );

        foreach ( $redisTags as $tagId )
        {
            $redisTag = $this->_redis->hgetall( 'tagsum:'.$tagId.':'.$miniDate );

            if ( !isset( $sqlTags[$tagId] ) )
            {
                $sqlTags[$tagId] = [
                    'imps'      => 0,
                    'cost'      => 0.00,
                    'revenue'   => 0.00
                ];
            }

            if ( 
                $redisTag['imps']       != $sqlTags[$tagId]['imps'] 
                || $redisTag['cost']    != $sqlTags[$tagId]['cost'] 
                || $redisTag['revenue'] != $sqlTags[$tagId]['revenue'] 
            )
            {
                $html .= '
                    <tr>
                        <td>'.$tagId.'</td>
                        <td>'.$redisTag['imps'].'</td>
                        <td>'.$sqlTags[$tagId]['imps'].'</td>
                        <td>'.$redisTag['cost'].'</td>
                        <td>'.$sqlTags[$tagId]['cost'].'</td>
                        <td>'.$redisTag['revenue'].'</td>
                        <td>'.$sqlTags[$tagId]['revenue'].'</td>
                    </tr>
                ';
            }

            unset( $redisTag );
        }

        return $html;
    }


    private function _getCurrentDatabase (  )
    {
        switch ( floor(($this->_timestamp/60/60/24))%2+1 )
        {
            case 1:
                return 2;
            break;
            case 2:
                return 1;
            break;
        }
    }    

}