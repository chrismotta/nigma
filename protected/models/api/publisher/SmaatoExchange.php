<?php

class SmaatoExchange
{ 
    private $exchange_id = 2;//Smaato
    private $date;

    private function oAuthLogin(){
        
        // GET {code} //

        // $network = Exchanges::model()->findbyPk($this->exchange_id);
        // $apiURL = $network->api_url;
        $client_id = '552adc600b8a428e8223e211fe1ea418';//$network->token1;
        $oAuthURL = 'https://api.smaato.com/v1/auth/authorize/?response_type=code&client_id='.$client_id;

        // linux curl
        // curl -X GET -v "https://api.smaato.com/v1/auth/authorize/?response_type=code&client_id=552adc600b8a428e8223e211fe1ea418"
        
        $curl = curl_init($oAuthURL);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json-rpc"));
        $json_response = curl_exec($curl);
        $response = json_decode($json_response);
        
        if(!isset($response->code)) 
            return array("ERROR"=>$json_response);
        

        // GET {access_token} //

        
        $tokenURL = 'https://api.smaato.com/v1/auth/token/?grant_type=authorization_code';
        $client_secret = '05be2e86777247c28900432ac8953e361c02a821b1b14ffaa3f521e95b4490a98dbfd87f2ac54bf1986e6d77e7008865';
        $code = $response->code;
        $data = 'client_id='.$client_id.'&client_secret='.$client_secret.'&code='.$code;

        // linux curl
        // curl -X POST -v "https://api.smaato.com/v1/auth/token/?grant_type=authorization_code" -d "client_id=552adc600b8a428e8223e211fe1ea418&client_secret=05be2e86777247c28900432ac8953e361c02a821b1b14ffaa3f521e95b4490a98dbfd87f2ac54bf1986e6d77e7008865&code=<code>"

        $curl = curl_init($tokenURL);
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json-rpc"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $json_response = curl_exec($curl);
        $response = json_decode($json_response);

        if (isset($response->access_token)) 
            return $response->access_token;
        else
            return array("ERROR"=>$json_response);
        

        // first token
        // {"expires_in":86400,"token_type":"Bearer","refresh_token":"zkeKsD7yzzGrFklepX6qvp1mkjkmZ2","scope":"","access_token":"nrUQcc6pHR8jLf0YNeGPuLK7bUVVLz","state":""}
        
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
            return array('status'=>true, 'msg'=>'<hr/>===>PLACEMENT EXISTS!!<hr/>', 'model'=>$dailyPublishers);
        }

        return array('status'=>true, 'msg'=>'<hr/>===>PLACEMENT IS NEW!!<hr/>', );
    }

    private function setDaily($data){
        $model->date          = $this->date;
        $model->placements_id = $placementID;
        $model->exchanges_id  = $this->exchange_id;
        $model->country_id    = $data['countri_id'];
        $model->ad_request    = $data['ad_request'];
        $model->imp_exchange  = $data['imp_exchange'];
        $model->revenue       = $data['revenue'];
        return $model;
    }

    public function downloadInfo($offset)
    {
        date_default_timezone_set('UTC');
        $return = '';

        if ( isset( $_GET['date']) ) {
        
            $date = $_GET['date'];
            $return.= $this->downloadDateInfo($date);
        
        } else {

            if(date('G')<=$offset){
                $return.= '<hr/>yesterday<hr/>';
                $date = date('Y-m-d', strtotime('yesterday'));
                $return.= $this->downloadDateInfo($date);
            }
            //default
            $return.= '<hr/>today<hr/>';
            $date = date('Y-m-d', strtotime('today'));
            $return.= $this->downloadDateInfo($date);
        
        }

        

        return $return;
    }

    public function downloadDateInfo($date)
    {
        $return = '';

        $access_token = $this->oAuthLogin();
        // $access_token = 'KNCDc32tEwo8DEKSQQNb3ks4kXNlOo';
        if(is_array($access_token)) 
            return $access_token['ERROR'];

        $return.= 'access_token: '.$access_token;
        $return.= '<hr/>';
        // return $return;


        // GET REPORT //

        $data = array(
            'criteria'=>array(
                'dimension' => 'AdspaceId',
                'fields'    => array('name'),  
                'child'     => array(
                    'dimension' => 'CountryCode',
                    'child'     => null
                    )
                ),
            'kpi' => array(
                'incomingAdRequests' => true,
                'impressions'        => true,
                'grossRevenue'       => true,
                ),
            'period'=>array(
                'period_type' => 'fixed',
                'start_date'  => $this->date,
                'end_date'    => $this->date
                )
            );
        $data_json = json_encode($data);


        $apiURL = 'https://api.smaato.com/v1/reporting/';
        $headers = array(
            'Content-type: application/json',
            'Authorization: Bearer '.$access_token,
        );


        $curl = curl_init($apiURL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);
        $json_response = curl_exec($curl);

        
        $return.=$data_json;
        $return.="<hr/>";
        $return.= $json_response;
        $return.="<hr/>";

        $response = json_decode($json_response);
        $lastPlacementID = null;
// return $return;
        foreach ($response as $adspace) {
            $return.= json_encode($adspace);
            $return.="<br/>";

            // parsing criterias
            foreach ($adspace->criteria as $value) {
                // $return.=" -> "; 
                // $return.=$value->name; 
                $metadata[$value->name] = $value;
            }

            if(isset($metadata['AdspaceId']->meta->name)){
                $placementName = $metadata['AdspaceId']->meta->name;
                $placementID   = substr($placementName, 0, strpos($placementName, '-'));
            }else{
                $return.=' -> {"error":"no meta data available"}'; 
                // match by ext_id
                $extID              = $metadata['AdspaceId']->value;
                $notMachedPlacement = Placements::model()->findByAttributes(array('ext_id'=>$extID));
                if(isset($notMachedPlacement)){
                    $placementID        = $notMachedPlacement->id;
                    $return.=' -> MATCHED WITH EXT_ID: '.$placementID; 
                }else{
                    $return.=' -> NOT MATCHED WITH EXT_ID'; 
                    continue;
                }
                $return.='<br/>';

            }
                
            $countryCode   = $metadata['CountryCode']->value;
            $countryModel  = GeoLocation::model()->findByAttributes(array('ISO2'=>$countryCode));
            
            // continue;//del
            if(isset($countryModel)){
                
                // Country exists    
                $countryID = $countryModel->id_location;
            
                $return.= $placementID.': '.$placementName.' - CountryCode: '.$countryID.'('.$countryCode.')';
                $return.="<br/>";
                
                // verify placement
                $verified = $this->verifyPlacement($placementID, $countryID);
                // $return.= json_encode($verified);
                $return.= $verified['msg'];
                
                if(!$verified['status']){
                    continue;
                }else{
                    if(isset($verified['model'])){
                        $daily = $verified['model'];
                    }else{
                        $daily = new DailyPublishers();
                    }
                }

                $daily->date          = $this->date;
                $daily->placements_id = $placementID;
                $daily->exchanges_id  = $this->exchange_id;
                $daily->country_id    = $countryID;
                $daily->ad_request    = $adspace->kpi->incomingAdRequests;
                $daily->imp_exchange  = $adspace->kpi->impressions;
                $daily->revenue       = $adspace->kpi->grossRevenue * 0.8;
                
                if($daily->save()) {
                    $return.= '-->Saved<br>';
                } else {
                    $return.= '-->NOT Saved<br>';
                    $return.= var_export($daily->getErrors(), true);
                    $return.="<br/>";
                }

            }else{
                
                // Country is unknown
                $return.='<hr/>----------->>>>>> country unknown-p: '.$placementID.'<hr/>';

                // new placement
                if($lastPlacementID == null || $lastPlacementID != $placementID){
                    $return.='<hr/>----------->>>>>> new placement<hr/>';

                    // save last placement
                    if(isset($unknown)){
                        $return.='<hr/>----------->>>>>> save last<hr/>';
                        
                        // $return.='saved!! - ';
                        // $return.=$lastPlacementID."-".$placementID."- imp:".$unknown->ad_request;
                        // $return.="<br/>";
                        // $return.='----------->>>>>> unknown - ';
                        // // return $return;
                        
                        // verify placement
                        $verified = $this->verifyPlacement($unknown->placements_id, null);
                        if(!$verified['status']){
                            $return.= '<hr/>----------->>>>>> '.$verified['msg'].'<hr/>';
                        }else{
                            if($unknown->save()) {
                                $return.= '<hr/>----------->>>>>> Saved (unknown): '.
                                $unknown->placements_id.'-'.
                                $unknown->exchanges_id.'-'.
                                $unknown->country_id.
                                '<hr/>';
                                // $return.= '-->Saved (unknown)<br>';
                            } else {
                                $return.= '-->NOT Saved (unknown)<br>';
                                $return.= var_export($unknown->getErrors(), true);
                                $return.="<br/>";
                            }
                        }

                        // $return.= $placementID.': '.$placementName.' - CountryCode: Unknown - Imp: '.$unknown->imp_exchange;
                        // $return.="<br/>";
                        // $return.=  "<hr/>===>UNKNOWN!!<hr/>";
                    }
                    
                    // create new unknown
                    $return.= '<hr/>----------->>>>>> New (unknown)<hr/>';
                    $unknown = new DailyPublishers();
                    $unknown->date            = $this->date;
                    $unknown->placements_id = $placementID;
                    $unknown->exchanges_id  = $this->exchange_id;
                    $unknown->country_id    = null;
                    $unknown->ad_request    = $adspace->kpi->incomingAdRequests;
                    $unknown->imp_exchange  = $adspace->kpi->impressions;
                    $unknown->revenue       = $adspace->kpi->grossRevenue * 0.8;
        
                    // $return.='new - ';
                    // $return.=$lastPlacementID."-".$placementID."- imp:".$unknown->ad_request;
                    // $return.="<br/>";
                    // // return $return;
                
                }else{
                    // Same Placement
                    $return.= '<hr/>----------->>>>>> same (unknown)<hr/>';
                    $unknown->ad_request   += $adspace->kpi->incomingAdRequests;
                    $unknown->imp_exchange += $adspace->kpi->impressions;
                    $unknown->revenue      += $adspace->kpi->grossRevenue * 0.8;
        
                    // $return.='same - ';
                    // $return.=$lastPlacementID."-".$placementID."- imp:".$unknown->ad_request;
                    // $return.="<br/>";
                    // // return $return;
                    
                }
                
                $lastPlacementID = $placementID;
            }


        }
        
        // last unknown
        if(isset($unknown)){

            // verify placement
            $verified = $this->verifyPlacement($placementID, null);
            if(!$verified['status']){
                $return.= $verified['msg'];
            }else{
                $return.= '<hr/>----------->>>>>> save last (unknown)<hr/>';
                // $unknown->save();
            }

            // $return.='saved!! - ';
            // $return.=$lastPlacementID."-".$placementID."- imp:".$unknown->ad_request;
            // $return.="<br/>";
            
            // return $return;
            // $return.= $placementID.': '.$placementName.' - CountryCode: Unknown - Imp: '.$unknown->imp_exchange;
            // $return.="<br/>";
            // $return.=  "<hr/>===>UNKNOWN!!<hr/>";
        }
        
        return $return;
    }
}