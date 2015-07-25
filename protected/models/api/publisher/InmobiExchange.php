<?php

class InmobiExchange
{ 
    private $exchange_id = 2;//Smaato

    public function downloadInfo()
    {
        $return = '';

        // Get json from InMobi API.
        $network = Networks::model()->findbyPk($this->provider_id);
        $apikey  = $network->token3;
        $user    = $network->token1;
        $pass    = $network->token2;
        $apiurl  = $network->url;

        // Create Session
        $ch = curl_init() or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi')); 
        curl_setopt($ch, CURLOPT_URL,"https://api.inmobi.com/v1.0/generatesession/generate");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array ('secretKey:'.$apikey,'userName:'.$user,'password:'.$pass));
        $response = curl_exec($ch) or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi'));

        // Json to array
        $newresponse = json_decode($response); 
        $sessionId   = $newresponse->respList[0]->sessionId; // guardo  el id de la session
        $accountId   = $newresponse->respList[0]->accountId; // guardo  el id de la cuenta

        curl_close($ch);

        
        return $return;



        // Client Key: 552adc600b8a428e8223e211fe1ea418
        // Client Secret: 05be2e86777247c28900432ac8953e361c02a821b1b14ffaa3f521e95b4490a98dbfd87f2ac54bf1986e6d77e7008865

        $return = $this->getResponse(null);

        // https://api.smaato.com/v1/auth/authorize/?response_type=code&client_id=552adc600b8a428e8223e211fe1ea418
        // {"code":"9movwiWm7MzSpa8HGX81w3O5kMPPI1"}
        return $return;
    }

    private function getResponse($method, $params = array() ) {
        // Get json from Ajillion API.
        $return = '';

        // GET {code} //

        // $network = Exchanges::model()->findbyPk($this->exchange_id);
        // $apiURL = $network->api_url;
        $oAuthURL = 'https://api.smaato.com/v1/auth/authorize/?response_type=code&client_id=552adc600b8a428e8223e211fe1ea418';

        // $curl = curl_init($oAuthURL);
        // curl_setopt($curl, CURLOPT_HEADER, false);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json-rpc"));
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        // $json_response = curl_exec($curl);
        // $response = json_decode($json_response);

        // return $response->code;
        


        // GET {access_token} //

        /*
        $tokenURL = 'https://api.smaato.com/v1/auth/token/?grant_type=authorization_code';
        $client_id = '552adc600b8a428e8223e211fe1ea418';//$network->token1;
        $client_secret = '05be2e86777247c28900432ac8953e361c02a821b1b14ffaa3f521e95b4490a98dbfd87f2ac54bf1986e6d77e7008865';
        $code = '9YN7qNxMJhidO1vmDXIXE6SYis1d5z';
        $data = array(
             "client_id"     => $client_id,
             "client_secret" => $client_secret,
             "code"          => $code,
         );
        $data = 'client_id='.$client_id.'&client_secret='.$client_secret.'&code='.$code;

        $curl = curl_init($tokenURL);
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json-rpc"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $json_response = curl_exec($curl);
        return $json_response;//levantar post
        */

        // first token
        // {"expires_in":86400,"token_type":"Bearer","refresh_token":"zkeKsD7yzzGrFklepX6qvp1mkjkmZ2","scope":"","access_token":"nrUQcc6pHR8jLf0YNeGPuLK7bUVVLz","state":""}
        

        set_time_limit(0);

        // GET REPORT //

        $data_json = '{
            "criteria": {
                "dimension": "CountryCode",
                "child": null
            },
            "period": {
                "period_type": "fixed",
                "start_date": "2015-07-10",
                "end_date": "2015-07-10"
            }
        }';

        $data = array(
            'criteria'=>array(
                'dimension'=>'CountryCode',
                'child'=>null
                ),
            'period'=>array(
                'period_type'=>'fixed',
                'start_date'=>'2015-07-10',
                'end_date'=>'2015-07-11'
                )
            );
        $data_json = json_encode($data);
        $data_json_len = strlen($data_json);
        // $data_post = http_build_query($data);

        $return.=$data_json;
        $return.="<hr/>";
        $return.=$data_json_len;
        $return.="<hr/>";

        $apiURL = 'http://api.smaato.com/v1/reporting';
        $access_token = 'D84yuZwPWKXVnsLfSTwvtWM4hM0nHW';
        $headers = array(
            'Content-type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$access_token,
            // 'Content-Length: '.$data_json_len,
        );


        $curl = curl_init($apiURL);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);

        $json_response = curl_exec($curl);
        
        $return.= $json_response;
        return $return;
    }
}