<?php

class CurrencyController extends Controller
{


	/**
	 * Load currency change for all currencies specified in columns of "currency" table. To add new 
	 * currency create new column in database and named it with the ISO 4217 currency code.
	 */
	public function actionIndex()
	{
		// validate if info have't been dowloaded already.
		if ( Currency::model()->exists("date=DATE(:date)", array(":date"=>date('Y-m-d', strtotime('today')))) ) {
			print "Currency: WARNING - information already downloaded.";
			Yii::app()->end(1);
		}

		$url = 'http://rate-exchange.appspot.com/currency?from=USD&to=';

		$currency = new Currency;
		$currency->date = date('Y-m-d', strtotime('today'));
		$currencies = $currency->getTableSchema()->getColumnNames();
		array_shift($currencies); // removed id column
		array_shift($currencies); // removed date column

		foreach ($currencies as $code) {
			$curl = curl_init($url . $code);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($curl);
			$result = json_decode($result);
			if ( !$result ) {
				print "Currency: ERROR - downloading currency info <hr>";
				Yii::app()->end(2);
			}
			curl_close($curl);

			if ( isset($result->err) ) {
				print "Currency: ERROR url:" . $url . $code . ", message: " . $result->err . "<hr>";
				Yii::app()->end(2);
			}
			$currency[$code] = $result->rate;
		}

		if ( ! $currency->save() ) {
			print json_encode($currency->getErrors());
			Yii::app()->end(2);
		}

		print "Currency: SUCCESS - Currency updated";
		Yii::app()->end();
	}

}