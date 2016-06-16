<?php

class OauthController extends Controller
{
	public function actionIndex(){
		echo "OAuth2 Callback:<hr/>";
		var_dump($_REQUEST);
	}

}
?>