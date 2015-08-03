<?php

class InmobiExchange
{ 
    private $exchange_id = 3;//inmobi
    private $date;

    private function oAuthLogin(){
        // Get json from InMobi API.
        $network = Exchanges::model()->findbyPk($this->exchange_id);
        $apikey  = $network->token3;
        $user    = $network->token1;
        $pass    = $network->token2;
        $apiurl  = $network->api_url;

        // Create Session
        $ch = curl_init() or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi')); 
        curl_setopt($ch, CURLOPT_URL,"https://api.inmobi.com/v1.0/generatesession/generate");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array ('secretKey:'.$apikey,'userName:'.$user,'password:'.$pass));
        $response = curl_exec($ch) or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi'));

        // Json to array
        $newresponse = json_decode($response); 

        curl_close($ch);

        $loginData['sessionId'] = $newresponse->respList[0]->sessionId; // guardo  el id de la session
        $loginData['accountId'] = $newresponse->respList[0]->accountId; // guardo  el id de la cuenta
        $loginData['apiurl']    = $apiurl;
        $loginData['apikey']    = $apikey;

        return $loginData;
    }

    private function getCountryID($countryCode){

        $countryModel  = GeoLocation::model()->findByAttributes(array('ISO2'=>$countryCode));
        if(isset($countryModel))
            $countryID = $countryModel->id_location;
        else
            $countryID = null;

        return $countryID;
    }

    private function verifyPlacement($placementID, $countryID){

        if(!$placementID){
            return array('status'=>false,'msg'=>'<hr/>===>WRONG PLACEMENT NAME!!<hr/>');
        }

        // validate placement
        $placementModel = Placements::model()->findByPk($placementID);
        if(!isset($placementModel)){
            return array('status'=>false,'msg'=>'<hr/>===>PLACEMENT NOT FOUND!!<hr/>');
        }

        // check for duplicates
        $dailyPublishers = DailyPublishers::model()->findByAttributes(array(
                            'placements_id' => $placementID,
                            'country_id'    => $countryID,
                            'exchanges_id'  => $this->exchange_id,
                            'date'          => $this->date,
                            ));
        if(isset($dailyPublishers)){
            return array('status'=>false,'msg'=>'<hr/>===>EXISTS!!<hr/>');
        }

        return array('status'=>true);
    }

    public function downloadInfo()
    {
        $return = '';
        $loginData = $this->oAuthLogin();
        // return json_encode($login);

        // get countries //
        $ch = curl_init() or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi')); 
        curl_setopt($ch, CURLOPT_URL, 'https://api.inmobi.com/v1.0/metadata/country.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'accountId:'.$loginData['accountId'],
            'secretKey:'.$loginData['apikey'],
            'sessionId:'.$loginData['sessionId'],
            'Content-Type:application/json'
            ));
        // curl_setopt($ch, CURLOPT_POST,true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $getReportJson);
        $response = curl_exec($ch) or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi'));
        curl_close($ch);

        $countryData = json_decode($response);
        if (!$countryData) {
            Yii::log("InMobi: ERROR - decoding json. ".curl_error(), 'error', 'system.model.api.inmobi');
            return 1;
        }

        // $return.= var_export($countryData,true);
        // $return.= '<hr/>';
        $countryCodeList = array();
        foreach ($countryData->data->country as $line) {
            if($line->id != -1){
                // $return.= var_export($line, true);
                // $return.= '<hr/>';
                $countryCodeList[$line->name] = strtoupper($line->isoCode);
            }
        }
        // return json_encode($countryCodeList);


        // stats //

        if ( isset( $_GET['date']) ) {
            $this->date = $_GET['date'];
        } else {
            $this->date = date('Y-m-d', strtotime('yesterday'));
        }

        // get data Json
        $getReportJson = '{
            "reportRequest": {
                "metrics": [
                    "adRequests","adImpressions","clicks","earnings"
                ],
                "groupBy": [
                    "site","country"
                ],
                "orderBy": [
                    "site","country"
                    ],
                "orderType": "asc",
                "timeFrame":"'.$this->date.':'.$this->date.'",
            }
        }';

        $ch = curl_init() or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi')); 
        curl_setopt($ch, CURLOPT_URL, $loginData['apiurl']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'accountId:'.$loginData['accountId'],
            'secretKey:'.$loginData['apikey'],
            'sessionId:'.$loginData['sessionId'],
            'Content-Type:application/json'
            ));
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $getReportJson);
        $response = curl_exec($ch) or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi'));
        
        $newresponse = json_decode($response);
        if (!$newresponse) {
            Yii::log("InMobi: ERROR - decoding json. ".curl_error(), 'error', 'system.model.api.inmobi');
            return 1;
        }
        curl_close($ch);


        /*
        if($newresponse->error=='true')
        {
            if($newresponse->errorList[0]->code==5001){
                Yii::log("Empty daily report ",'info', 'system.model.api.inmobi');
                return 0;
            }
            else {
                Yii::log($newresponse->errorList[0]->message,'error', 'system.model.api.inmobi');
                return 0;
            }
        }

        if ( !isset($newresponse->respList) ) { // validation add after initial implementation
            Yii::log("Empty daily report ",'info', 'system.model.api.inmobi');
            return 0;
        }
        */

        foreach ($newresponse->respList as $line) {

            $return.= json_encode($line);
            $return.= '<br/>';

            $placementName = $line->siteName;
            $placementID   = substr($placementName, 0, strpos($placementName, '-'));

            $countryID = $this->getCountryID($countryCodeList[$line->country]);

            // verify placement
            $verified = $this->verifyPlacement($placementID, $countryID);
            $return.= json_encode($verified);

            // $return.= $placementID ? $placementID : 'Unknown';
            // $return.= ' - ';
            // $return.= $line->country;
            // $return.= ' - ';
            // $return.= $countryID ? $countryID : 'Unknown';
            $return.= '<br/>';

            if($verified['status']){
                $daily = new DailyPublishers();
                $daily->date          = $this->date;
                $daily->placements_id = $placementID;
                $daily->exchanges_id  = $this->exchange_id;
                $daily->country_id    = $countryID;
                $daily->ad_request    = $line->adRequests;
                $daily->imp_exchange  = $line->adImpressions;
                $daily->clicks        = $line->clicks;
                $daily->revenue       = $line->earnings;
            
                if($daily->save()) {
                    $return.= '-->Saved<br>';
                } else {
                    $return.= '-->NOT Saved<br>';
                    $return.= var_export($daily->getErrors(), true);
                }
            }else{
                $return.= '-->NOT Valid<br>';
            }

            $return.= '<hr/>';
        }

        return $return;

    }

}
