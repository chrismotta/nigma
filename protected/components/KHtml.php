<?php

class KHtml extends CHtml
{

    private static function getLabel($item){
        $attributeLabels = array(
            'Date'           =>'Date', 
            'TrafficSource'  =>'Traffic Source', 
            'Advertiser'     =>'Advertiser', 
            'Country'        =>'Country', 
            'Campaign'       =>'Campaign',
            'Imp'            =>'Imp.', 
            'Clicks'         =>'Clicks', 
            'CTR'            =>'CTR %',
            'Conv'           =>'Conv.', 
            'CR'             =>'CR %',
            'Rate'           =>'Rate', 
            'Revenue'        =>'Revenue',
            'Spend'          =>'Spend',
            'Profit'         =>'Profit',
            'eCPM'           =>'eCPM',
            'eCPC'           =>'eCPC',
            'eCPA'           =>'eCPA',
            'DeviceType'     =>'Dev. Type',
            'DeviceBrand'    =>'Dev. Brand',
            'DeviceModel'    =>'Dev. Model',
            'OS'             =>'OS',
            'OSVersion'      =>'OS Ver.',
            'Browser'        =>'Browser',
            'BrowserVersion' =>'Browser Ver.',
            'Imp'            =>'Impressions', 
            'UniqueUsr'      =>'Unique Users', 
            'UniqueRevenue'  =>'U. Revenue', 
            'Revenue_eCPM'   =>'Revenue eCPM', 
            'Cost_eCPM'      =>'Cost eCPM', 
            );

        if(isset($attributeLabels[$item])){
            return $attributeLabels[$item];
        }else{
            return $item;
        }
    }


    public static function enumItem($model,$attribute) {
        $attr=$attribute;
        self::resolveName($model,$attr);
        preg_match('/\((.*)\)/',$model->tableSchema->columns[$attr]->dbType,$matches);
        foreach(explode(',', $matches[1]) as $value) {
            $value=str_replace("'",null,$value);
            $values[$value]=Yii::t('enumItem',$value);
        }
        asort($values);
        return $values;
    } 

    /**
     * Create a TbDatePicker customize for TheMediaLab adServer
     * @param  string   $name 
     * @param  Date     $initialDate
     * @param  array()  $options
     * @param  array()  $htmlOptions
     * @return html for date picker
     */
    public static function datePicker($name, $initialDate, $options = array(), $htmlOptions = array(), $append=null)
    {
        $defaultHtmlOptions = array(
            'style' => 'width: 73px',
            'class'=>'span2',
            'onchange' => 'if(presetChange==0)$("#dpp").val("9");',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $defaultOptions = array(
            'autoclose'  => true,
            'todayHighlight' => true,
            'format'     => 'dd-mm-yyyy',
            'viewformat' => 'dd-mm-yyyy',
            'placement'  => 'right',
            // 'beforeShowDay' => 'function (date){ return "ok" }',
        );
        $options = array_merge($defaultOptions, $options);

        $r = '<label><div class="input-append input-prepend">';
        if(isset($append)) $r .= '<span class=" btn btn-info disabled" style="width:35px">'.$append.'</span>';
        $r .= Yii::app()->controller->widget('bootstrap.widgets.TbDatePicker', array(
            'name'        => $name,
            'value'       => date('d-m-Y', strtotime($initialDate)),
            'htmlOptions' => $htmlOptions,
            'options'     => $options,
        ), true);
        $r .= '<span class="add-on"><i class="icon-calendar"></i></span>';
        $r .= '</div></label>';
        return $r;
    }

     public static function timePicker($name, $initialValue, $options = array(), $htmlOptions = array(), $append=null)
    {
        $defaultHtmlOptions = array(
            // 'style' => 'width: 83px',
            // 'class'=>'span2',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $defaultOptions = array(
            'autoclose'  => true,
        );
        $options = array_merge($defaultOptions, $options);

        echo '<label><div class="input-append input-prepend">';
        if(isset($append)) echo '<span class=" btn btn-info disabled" style="width:35px">'.$append.'</span>';

        Yii::app()->controller->widget(
            'bootstrap.widgets.TbTimePicker',
            array(
                'name' => $name,
                'value' => $initialValue,
                'htmlOptions' => $htmlOptions,
                'options'     => $options,
            )
        );
        
        echo '</div></label>';
        // return $r;

    }

    /**
     * Create dropdown of Advertisers
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function datePickerPresets($value, $htmlOptions = array())
    {
        
        $defaultHtmlOptions = array(
            // 'style'    => 'width: 73px',
            'id'       => 'dpp',
            'class'    => 'span2',
            'onchange' => '
                var today     = new Date();
                var dateStart = new Date();
                var dateEnd   = new Date();
                presetChange = 1;
                switch (this.value) {
                    case "1":
                        break;
                        //today
                    case "2":
                        //yesterday
                        dateStart.setDate( today.getDate()-1 );
                        //yesterday
                        dateEnd.setDate( today.getDate()-1 );
                        break;
                    case "3":
                        //last monday
                        dateStart.setDate( today.getDate()-today.getDay()+1 );
                        //yesterday
                        dateEnd.setDate( today.getDate() );
                        break;
                    case "4":
                        //previous monday
                        dateStart.setDate( today.getDate()-today.getDay()-6 );
                        //last saturday
                        dateEnd.setDate( today.getDate()-today.getDay() );
                        break;
                    case "5":
                        //a week
                        dateStart.setDate( today.getDate()-7 );
                        //yesterday
                        dateEnd.setDate( today.getDate()-1 );
                        break;
                    case "6":
                        //a month
                        dateStart.setDate( today.getDate()-30 );
                        //yesterday
                        dateEnd.setDate( today.getDate()-1 );
                        break;
                    case "7":
                        //last 1st
                        dateStart.setDate( 1 );
                        //yesterday
                        dateEnd.setDate( today.getDate()-1 );
                        break;
                    case "8":
                        //previous 1st
                        dateStart.setDate( 1 );
                        dateStart.setMonth( today.getMonth()-1 );
                        //last day of previout month
                        dateEnd.setDate( 1 );
                        dateEnd.setDate( dateEnd.getDate() -1 );
                        break;
                    
                    default:
                        break;
                };
                $("#dateStart").datepicker( "setDate", dateStart );
                $("#dateEnd").datepicker( "setDate", dateEnd );
                presetChange = 0;
                '
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);    
       
        $list = array(
            1 =>'Today',
            2 =>'Yesterday',
            3 =>'This week', //(Lunes hasta el día anterior al actual)
            4 =>'Last week', // (Lunes a Domingo de la semana anterior a la actual)
            5 =>'Last 7 days',
            6 =>'Last 30 days',
            7 =>'This month',
            8 =>'Last month',
            9 =>'Custom',
            
            );

        $return = '';
        $return.= '<label>';
        $return.= CHtml::dropDownList('dpp', $value, $list, $htmlOptions);
        $return.= '</label>';
        
        return $return;
    }

    /**
     * Create Dropdown of Opportunities filtering by accountMangerId if not NULL
     * @param  $value
     * @param  $accountManagerId 
     * @param  $accountManagerId 
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterOpportunities($value, $accountManagerId=NULL, $htmlOptions = array())
    {

        $defaultHtmlOptions = array(
            'empty' => 'All opportunities',
            'class' => 'opportunitie-dropdownlist',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $criteria = new CDbCriteria;
        $criteria->with  = array('regions', 'regions.financeEntities', 'regions.financeEntities.advertisers', 'regions.country', 'carriers');
        $criteria->compare('t.status', 'Active');
        $criteria->order = 't.id, advertisers.name, country.ISO2';

        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
            $criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));

        if( UserManager::model()->isUserAssignToRole('operation_manager') )
            $criteria->compare('advertisers.cat', array('Networks','Incent'));        

        if (FilterManager::model()->isUserTotalAccess('media'))
            $accountManagerId=Yii::app()->user->id;

        if ( $accountManagerId != NULL )
            $criteria->compare('t.account_manager_id', $accountManagerId);

        $opps = Opportunities::model()->with('regions','regions.financeEntities')->findAll($criteria);
        $list = CHtml::listData($opps, 'id', 'virtualName');
        return CHtml::dropDownList('opportunity', $value, $list, $htmlOptions);
    }

    public static function filterOpportunitiesDate($value, $accountManagerId=NULL, $htmlOptions = array(),$io_id,$startDate,$endDate)
    {
        $defaultHtmlOptions = array(
            'empty' => 'All opportunities',
            'class' => 'opportunitie-dropdownlist',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $criteria = new CDbCriteria;
        $criteria->with  = array('campaigns','campaigns.dailyReports');
        $criteria->addCondition('dailyReports.date BETWEEN "'.$startDate.'" AND "'.$endDate.'"');
        $criteria->addCondition('dailyReports.revenue>0');
        if ( $accountManagerId != NULL )
            $criteria->compare('t.account_manager_id', $accountManagerId);
        
        if ( $io_id != NULL )
            $criteria->compare('t.ios_id', $io_id);

        $opps = Opportunities::model()->findAll($criteria);
        $list   = CHtml::listData($opps, 'id', 'virtualName');
        return CHtml::dropDownList('opportunitie', $value, $list, $htmlOptions);
    }

    public static function filterCarrier($value, $accountManagerId=NULL, $htmlOptions = array(), $country=null, $name='carrier')
    {
        $defaultHtmlOptions = array(
            'empty' => 'All Carriers',
            'class' => 'carrier-dropdownlist',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        $criteria    = new CDbCriteria;
        if($country)
            $criteria->compare('id_country',$country);

        $carriers = Carriers::model()->findAll($criteria);
        $list     = CHtml::listData($carriers, 'id_carrier', 'mobile_brand');
        return CHtml::dropDownList($name, $value, $list, $htmlOptions);
    }

    public static function filterCarriersMulti($value, $htmlOptions = array(),$name, $countries = array() )
    {

        $defaultHtmlOptions = array(
            'empty' => 'All Carriers',
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 

        $criteria=new CDbCriteria;
        $criteria->with = 'idCountry';
        $criteria->order = 't.mobile_brand, idCountry.name';
        $criteria->select = '*, idCountry.name as country_name';
        $carriers = Carriers::model()->findAll( $criteria );

        $data=array();
        foreach ($carriers as $c) {
            $data[$c->id_carrier]=$c->mobile_brand . ' (' . $c->country_name .')';
        }
        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       => $value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All carriers',
                    'width'       => '20%',
                ),
            )
        );
    }     

    public static function filterProduct($value, $htmlOptions = array(), $feId=null, $optionAll=true)
    {
        $defaultHtmlOptions = array(
            'class' => 'product-dropdownlist',
        );
        if($optionAll)
            $defaultHtmlOptions = array_merge($defaultHtmlOptions, array('empty' => 'All products'));

        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        $criteria = new CDbCriteria;
        $criteria->select = array(new CDbExpression('IF( t.product="", "Without Product", t.product) as product'));
        $criteria->with  = array('regions');
        if ( $feId != NULL )
            $criteria->compare('regions.finance_entities_id', $feId);
        $criteria->group='product';
        $opps = Opportunities::model()->findAll($criteria);
        $list   = CHtml::listData($opps, 'product', 'product');
        return CHtml::dropDownList('product', $value, $list, $htmlOptions);
    }


    /**
     * Create dropdown of Account Managers
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterAccountManagers($value, $htmlOptions = array(), $name=null)
    {
        $name = isset($name) ? $name : 'accountManager';
        $defaultHtmlOptions = array(
            'empty' => 'All account managers',
            'onChange' => '
                // if ( ! this.value) {
                //   return;
                // }
                $.post(
                    "' . Yii::app()->getBaseUrl() . '/opportunities/getOpportunities/?accountManager="+this.value,
                    "",
                    function(data)
                    {
                        // alert(data);
                        $(".opportunitie-dropdownlist").html(data);
                        $("#opportunities-select").html(data);
                    }
                )'
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $medias = Users::model()->findUsersByRole(array('admin','account_manager','account_manager_admin', 'operation_manager'));
        $list   = CHtml::listData($medias, 'id', 'FullName');
        return CHtml::dropDownList($name, $value, $list, $htmlOptions);
    }

    /**
     * Create dropdown of providers
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterProviders($value, $providers=NULL, $htmlOptions = array(), $name=null)
    {
        $name = isset($name) ? $name : 'providers';
        $defaultHtmlOptions = array(
            'empty' => 'All traffic sources',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        if ( !$providers ) {

            $networks_t = array('0' => '------- Networks -------');

            $networks = Providers::model()->findAll( 
                array(
                    'order' => 'name', 
                    'condition' => 'status="Active" && type="Network"'
                    ));

            $networks = CHtml::listData($networks, 
                function($data){return strval($data->id);}, 
                'name');

            $affiliates_t = array('00'=>'------- Affiliates -------');

            $affiliates = Providers::model()->findAll( 
                array(
                    'order' => 'name', 
                    'condition' => 'status="Active" && type="Affiliate"'
                    ));
            $affiliates = CHtml::listData($affiliates, 
                function($data){return strval($data->id);}, 
                'name');                
      

            if( UserManager::model()->isUserAssignToRole('account_manager_admin') || UserManager::model()->isUserAssignToRole('account_manager_admin') ) {
                $publishers_t = array();
                $publishers = array();
            }
            else
            {

                $publishers_t = array('000'=>'------- Publishers -------');

                $publishers = Providers::model()->findAll( 
                    array(
                        'order' => 'name', 
                        'condition' => 'status="Active" && type="Publisher"'
                        ));
                $publishers = CHtml::listData($publishers, 
                    function($data){return strval($data->id);}, 
                    'name');
            }

            $providers = $networks_t + $networks + $affiliates_t + $affiliates + $publishers_t + $publishers;

        }
            
        return CHtml::dropDownList($name, $value, $providers, $htmlOptions);
    }

    /**
     * Create dropdown of Advertisers Category
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterAdvertisersCategory($value, $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            'empty' => 'All advertiser types',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') ){
            $cat = array('VAS','Affiliates','App Owners');
        }
        else if( UserManager::model()->isUserAssignToRole('operation_manager') )
        {
            $cat = array( 'Networks', 'Incent'); 
        }
        else{
            $cat = KHtml::enumItem(new Advertisers, 'cat');
        }
            

        return CHtml::dropDownList('cat', $value, $cat, $htmlOptions);
    }

    /**
     * Create dropdown of Advertisers
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterAdvertisers($value, $htmlOptions = array(), $name=null)
    {
        $name = isset($name) ? $name : 'advertiser';
        $defaultHtmlOptions = array(
            'empty' => 'All advertisers',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);    
        
        $criteria=new CDbCriteria;
        $criteria->order = 'name';
        $criteria->compare('status', 'Active');
        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
            $criteria->compare('cat', array('VAS','Affiliates','App Owners'));

        if( UserManager::model()->isUserAssignToRole('operation_manager') )
            $criteria->compare('cat', array('Networks','Incent'));

        $advs        = Advertisers::model()->findAll( $criteria );
        $list        = CHtml::listData($advs, 'id', 'name');
        return CHtml::dropDownList($name, $value, $list, $htmlOptions);
    }

    /**
     * Create dropdown of Countries
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterCountries($value, $htmlOptions=array(), $feId=null, $dropdownLoad=null, $optionAll=true)
    {
        $defaultHtmlOptions = $optionAll ? array(
            'empty' => 'All countries',            
        ) : array();

        if(!is_null($dropdownLoad)){

            $defaultHtmlOptions = array(
                'onChange' => '
                $.post(
                    "' . Yii::app()->getBaseUrl() . '/finance/getCarriers/?country="+this.value,
                    "",
                    function(data)
                    {
                        // console.log(data);
                        $("#'.$dropdownLoad.'").html(data);
                    }
                )');
            $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        
        }

        /*
        $criteria = new CDbCriteria;
        $criteria->with  = array('regions','regions.country');
        $criteria->order = 'country.name';
        if(!is_null($feId))
            $criteria->compare('regions.finance_entities_id',$feId);
        $opps            = Opportunities::model()->findAll($criteria);
        $list            = CHtml::listData($opps, 'regions.country.id_location', 'regions.country.name');
        */

        return CHtml::dropDownList('country', $value, self::getCountryByFE($feId), $htmlOptions);
    }

    public static function filterCountriesMulti($value, $providers_id = array(), $htmlOptions = array(),$name)
    {

        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 


        $countries = GeoLocation::model()->findAll( array('order' => 'name', 'condition' => 'type="Country"') );

        $data=array();
        foreach ($countries as $c) {
            $data[$c->id_location]=$c->getNameFromId($c->id_location);
        }
        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All countries',
                    'width'       => '20%',
                ),
            )
        );
    }         

    public static function getCountryByFE($feId=null){
        $criteria = new CDbCriteria;
        $criteria->with  = array('regions','regions.country');
        $criteria->order = 'country.name';
        if(!is_null($feId))
            $criteria->compare('regions.finance_entities_id',$feId);
        $opps            = Opportunities::model()->findAll($criteria);
        return CHtml::listData($opps, 'regions.country.id_location', 'regions.country.name');
    }

    /**
     * Create dropdown of Entities
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterEntity($value, $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            'empty' => 'All entities',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        $entities    = KHtml::enumItem(new Ios, 'entity');
        return CHtml::dropDownList('entity', $value, $entities, $htmlOptions);
    }

    /**
     * Create autocomplete input of campaigns
     * @param  $value
     * @param  $providers_id
     * @param  $htmlOptions
     * @return html for autocomplete
     */
    public static function filterCampaigns($value, $providers_id = array(), $name = 'campaign', $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            'placeholder' => 'All campaigns',
            'class'       => 'span4',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $criteria = new CDbCriteria;
        $criteria->order = 't.id';
        $criteria->with = array(
                'opportunities.regions.financeEntities.advertisers',
        );

        //check
        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
            $criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));

        if( UserManager::model()->isUserAssignToRole('operation_manager') )
            $criteria->compare('advertisers.cat', array('Networks','Incent'));   

        if ( !empty($providers_id) )
             $criteria->addCondition( 'providers_id', $providers_id );

        $campaigns = Campaigns::model()->findAll( $criteria );

        $list = array_values(CHtml::listData($campaigns, 'id', function($c) { return Campaigns::model()->getExternalName($c->id); } ));

        return Yii::app()->controller->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'name'        =>$name,
            'source'      =>$list,
            'value'       =>$value,
            // additional javascript options for the autocomplete plugin
            'htmlOptions' =>$htmlOptions,
            'options'     =>array(
                'minLength' => '1',
            ),
        ), true);
    }


    public static function filterCampaignsMulti($value, $providers_id = array(), $htmlOptions = array(),$name)
    {

        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 

        $criteria = new CDbCriteria;
        $criteria->order = 't.id';
        $criteria->with = array(
                'opportunities.regions.financeEntities.advertisers',
        );

        //check
        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
            $criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));

        if( UserManager::model()->isUserAssignToRole('operation_manager') )
            $criteria->compare('advertisers.cat', array('Networks','Incent'));   

        if ( !empty($providers_id) )
             $criteria->addCondition( 'providers_id', $providers_id );

        $campaigns = Campaigns::model()->findAll( $criteria );
        $data=array();
        foreach ($campaigns as $c) {
            $data[$c->id]=$c->getExternalName($c->id);
        }
        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All campaigns',
                    'width'       => '20%',
                ),
            )
        );
    }     

    public static function filterVectorsMulti($value, $provider_id = array(), $htmlOptions = array(),$name)
    {

        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 

        $criteria = new CDbCriteria;
        $criteria->order = 't.id';
        $criteria->with = array(
                'campaigns.opportunities.regions.financeEntities.advertisers',
        );

        //check
        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
            $criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));

        if( UserManager::model()->isUserAssignToRole('operation_manager') )
            $criteria->compare('advertisers.cat', array('Networks','Incent'));   
        /*
        if ( !empty($providers_id) )
             $criteria->addCondition( 'providers_id', $providers_id );
        */
        /*
        if( UserManager::model()->isUserAssignToRole('account_manager_admin') || UserManager::model()->isUserAssignToRole('account_manager') )
            $criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));
        */

        //if ( empty($providers_id) )
            $vectors = Vectors::model()->findAll( $criteria );
        /*      
        else
            $vectors = Vectors::model()->findAll( array('order' => 'id', 'condition' => "status='Active' AND providers_id IN (" . join($providers_id, ", ") . ")") );
        */


        $data=array();
        foreach ($vectors as $c) {
            $data[$c->id]=$c->getExternalName($c->id);
        }
        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All vectors',
                    'width'       => '20%',
                ),
            )
        );
    }     

//Filters select2

   /**
     * Create Dropdown of Opportunities filtering by accountMangerId if not NULL
     * @param  $value
     * @param  $accountManagerId 
     * @param  $accountManagerId 
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterOpportunitiesMulti($value, $accountManagerId=NULL, $htmlOptions = array(),$name)
    {

        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 
        $criteria = new CDbCriteria;
        $criteria->with  = array('regions','regions.financeEntities','regions.financeEntities.advertisers', 'regions.country');
        $criteria->order = 't.id, advertisers.name, country.ISO2';

        //check
        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
            $criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));

        if( UserManager::model()->isUserAssignToRole('operation_manager') )
            $criteria->compare('advertisers.cat', array('Networks','Incent'));        

        // if (FilterManager::model()->isUserTotalAccess('media'))
        //     $accountManagerId=Yii::app()->user->id;

        if ( $accountManagerId != NULL )
            $criteria->compare('t.account_manager_id', $accountManagerId);

        //$opps = Opportunities::model()->with('regions','regions.financeEntities','regions.financeEntities.advertisers', 'carriers')->findAll($criteria);
        $opps = Opportunities::model()->findAll($criteria);
        $data=array();
        foreach ($opps as $opp) {
            $data[$opp->id]=$opp->getVirtualName();
        }
        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All opportunities',
                    'width'       => '20%',
                ),
            )
        );
    }    

    /**
     * Create dropdown of Advertisers Category
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterAdvertisersCategoryMulti($value, $htmlOptions = array(),$name)
    {
        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 
        $categories = array('VAS','Affiliates','App Owners');
        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') ){
            $categories = array('VAS','Affiliates','App Owners');
        } 
        else if( UserManager::model()->isUserAssignToRole('operation_manager') ){
            $categories = array('Networks','Incent');
        }
        else{
            $categories = KHtml::enumItem(new Advertisers, 'cat');
        }
        
        return Yii::app()->controller->widget(
        'yiibooster.widgets.TbSelect2',
            array(
                'name'        => $name,
                'data'        => $categories,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All categories',
                    'width' => '20%',
                ),
            )
        );
    }

    /**
     * Create dropdown of providers
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterProvidersMulti($value, $providers=NULL, $htmlOptions = array(), $name)
    {
        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 
        
        if ( !$providers ) {


            $networks_t = array('0' => '------- Networks -------');

            $networks = Providers::model()->findAll( 
                array(
                    'order' => 'name', 
                    'condition' => 'status="Active" && type="Network"'
                    ));
            $networks = CHtml::listData($networks, 
                function($data){return strval($data->id);}, 
                'name');


            $affiliates_t = array('00'=>'------- Affiliates -------');

            $affiliates = Providers::model()->findAll( 
                array(
                    'order' => 'name', 
                    'condition' => 'status="Active" && type="Affiliate"'
                    ));
            $affiliates = CHtml::listData($affiliates, 
                function($data){return strval($data->id);}, 
                'name');               

            

            
             if( UserManager::model()->isUserAssignToRole('account_manager_admin') || UserManager::model()->isUserAssignToRole('account_manager_admin') ){
                $publishers_t = array();
                $publishers = array();
            }else{

                $publishers_t = array('000'=>'------- Publishers -------');

                $publishers = Providers::model()->findAll( 
                    array(
                        'order' => 'name', 
                        'condition' => 'status="Active" && type="Publisher"'
                        ));
                $publishers = CHtml::listData($publishers, 
                    function($data){return strval($data->id);}, 
                    'name');
            }

            $providers = $networks_t + $networks + $affiliates_t + $affiliates + $publishers_t + $publishers;

        }

        
        return Yii::app()->controller->widget(
        'yiibooster.widgets.TbSelect2',
            array(
                'name'        => $name,
                'data'        => $providers,
                'value'       => $value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All traffic sources',
                    'width' => '20%',
                ),
            )
        );
    }

    /**
     * Create dropdown of Account Managers
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterAccountManagersMulti($value, $htmlOptions = array(), $dropdownLoad,$name,$onChange=null)
    {
        $defaultHtmlOptions = array(
            'multiple' => 'multiple'
        );
        if($onChange=='opportunities')      
            $defaultHtmlOptions = array_merge($defaultHtmlOptions, 
                array(                    
                'onChange' => '
                    $.post(
                        "' . Yii::app()->getBaseUrl() . '/opportunities/getOpportunities/?"+$("#accountManager-select").serialize(),
                        "",
                        function(data)
                        {
                            $("#'.$dropdownLoad.'").html(data);
                        }
                    )'
                    )
                );
        if($onChange=='advertisers')      
            $defaultHtmlOptions = array_merge($defaultHtmlOptions, 
                array(                    
                'onChange' => '
                    $.post(
                        "' . Yii::app()->getBaseUrl() . '/advertisers/getAdvertisers/?"+$("#accountManager-select").serialize(),
                        "",
                        function(data)
                        {
                            $("#'.$dropdownLoad.'").html(data);
                        }
                    )'
                    )
                );



        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 
        
        $medias = Users::model()->findUsersByRole(array('admin','account_manager','account_manager_admin'));
        $list   = CHtml::listData($medias, 'id', 'FullName');

        
        return Yii::app()->controller->widget(
        'yiibooster.widgets.TbSelect2',
            array(
                'name'        => $name,
                'data'        => $list,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All account managers',
                    'width' => '20%',
                ),
            )
        );
    }
   /**
     * Create Dropdown of Opportunities filtering by accountMangerId if not NULL
     * @param  $value
     * @param  $accountManagerId 
     * @param  $accountManagerId 
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterAdvertisersMulti($value, $accountManager=NULL, $htmlOptions = array(), $name)
    {

        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 
        $criteria = new CDbCriteria;
        $criteria->with  = array('regions', 'regions.financeEntities.advertisers','accountManager');
        $criteria->order = 'advertisers.name';

        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
            $criteria->compare('cat', array('VAS','Affiliates','App Owners'));
        
        if( UserManager::model()->isUserAssignToRole('operation_manager') )
            $criteria->compare('cat', array('Networks','Incent'));        

        // if (FilterManager::model()->isUserTotalAccess('media'))
        //     $accountManager=Yii::app()->user->id;

        if ( $accountManager != NULL) {
            if(is_array($accountManager))
            {
                $query="(";
                $i=0;
                foreach ($accountManager as $id) {  
                    if($i==0) 
                        $query.="accountManager.id=".$id;
                    else
                        $query.=" OR accountManager.id=".$id;
                    $i++;
                }
                $query.=")";
                $criteria->addCondition($query);                
            }
            else
            {
                $criteria->compare('accountManager.id',$accountManager);
            }
        }

        $opps = Opportunities::model()->with('regions','regions.financeEntities')->findAll($criteria);
        $data=array();
        foreach ($opps as $opp) {
            $data[$opp->regions->financeEntities->advertisers->id]=$opp->regions->financeEntities->advertisers->name;
        }
        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       => $value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All advertisers',
                    'width'       => '20%',
                ),
            )
        );
    }    

   /**
     * Create Dropdown of Opportunities filtering by accountMangerId if not NULL
     * @param  $value
     * @param  $accountManagerId 
     * @param  $accountManagerId 
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterAdvertisersCountryMulti($value, $htmlOptions = array(),$name)
    {

        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 

        $criteria = new CDbCriteria;

        $criteria->with  = array('regions', 'regions.financeEntities', 'regions.financeEntities.advertisers','country');
        // $criteria->order = 'country.name';
        // 
    
        if( UserManager::model()->isUserAssignToRole('account_manager_admin') || UserManager::model()->isUserAssignToRole('account_manager') )
            $criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));

        if( UserManager::model()->isUserAssignToRole('operation_manager') )
            $criteria->compare('advertisers.cat', array('Networks','Incent'));

        $opps = Opportunities::model()->findAll($criteria);
        $data=array();

        foreach ($opps as $opp) {
            $data[$opp->regions->country->id_location]=$opp->regions->country->name;
        }
        asort($data);

        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All countries',
                    'width'       => '20%',
                ),
            )
        );
    } 
   /**
     * Create Dropdown of Opportunities filtering by accountMangerId if not NULL
     * @param  $value
     * @param  $accountManagerId 
     * @param  $accountManagerId 
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterModelAdvertisersMulti($value, $htmlOptions = array(),$name)
    {

        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 
        $criteria = new CDbCriteria;
        $criteria->order = 'model_adv';

        $opps = Opportunities::model()->findAll($criteria);
        $data=array();
        foreach ($opps as $opp) {
            $data[$opp->model_adv]=$opp->model_adv;
        }
        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All models',
                    'width'       => '20%',
                ),
            )
        );
    } 

    public static function currencyTotals($totals=array())
    {
        $rowTotals='<div class="row totals-bar ">';
        if(count($totals)>0)
        {
            $span = floor( 12 / count($totals) );
            $alert = array('error', 'info', 'success', 'warning', 'muted');
            $i = 0;
            foreach($totals as $total){
                $invoice_percent=(isset($total['total_invoiced']) && isset($total['total']) && $total['total']>0) ? round(($total['total_invoiced']*100)/$total['total'],3) : 0;
                $rowTotals.= '
                <div class="span'.$span.'">
                    <div class="alert alert-'.$alert[$i].'">';
                        $rowTotals.=isset($total['currency']) ? '<small >TOTAL '.$total['currency'].'</small>':'';
                        $rowTotals.=isset($total['sub_total']) ? '<h4 class="">Subtotal: '.number_format($total['sub_total'],2).'</h4>' : '';
                        $rowTotals.=isset($total['total_count']) ? '<h5 class="">Total Count: '.number_format($total['total_count'],2).'</h5>' : '';
                        $rowTotals.=isset($total['total_commission']) ? '<h5 class="">Total Commission: '.number_format($total['total_commission'],2).'</h5>' : '';
                        $rowTotals.=isset($total['total_deal']) ? '<h5 class="">Total Closed Deal: '.number_format($total['total_deal'],2).'</h5>' : '';
                        $rowTotals.=isset($total['total_clients']) ?'<h5 class="">Total Clients: '.number_format($total['total_clients'],2).'</h5>' : '';
                        $rowTotals.=isset($total['total_branding']) ?'<h5 class="">Total Branding: '.number_format($total['total_branding'],2).'</h5>' : '';
                        $rowTotals.=isset($total['total']) ?'<h5 class="">Total: '.number_format($total['total'],2).'</h5>' : '';
                        $rowTotals.=isset($total['total_clients_invoice']) ? '<h6 class="">Total Clients Invoiced: '.number_format($total['total_clients_invoice'],2).'</h6>' : '';
                        $rowTotals.=isset($total['total_branding_invoice']) ? '<h6 class="">Total Branding Invoiced: '.number_format($total['total_branding_invoice'],2).'</h6>' : '';
                        $rowTotals.=isset($total['total_invoiced']) ? '<h6 class="">Total Invoiced: '.number_format($total['total_invoiced'],2).'</h6>' : '';
                        $rowTotals.=isset($total['total_invoiced']) && isset($total['total']) ? '<h6 class="">Invoiced Percent: '.$invoice_percent.'%</h6>' : '';
                    $rowTotals.= '</div>';
                $rowTotals.='</div>
                ';
                $i++;
            }
        }
        $rowTotals.='</div>';
        return $rowTotals;
    }

    public static function printAlerts()
    {
        $mainVar          =array();
        $mainVar['count'] =0;
        $mainVar['date']  = strtotime ( '-1 month' , strtotime ( date('Y-m-d',strtotime('NOW')) ) ) ;
        $mainVar['year']  =date('Y', $mainVar['date']);
        $mainVar['month'] =date('m', $mainVar['date']);
        if (FilterManager::model()->isUserTotalAccess('alert.business')) 
        {
            $mainVar['date']   = Utilities::weekDaysSum(date('Y-m-01'),4);
            $mainVar['option'] ='ios';
            foreach(IosValidation::model()->findAllByAttributes(array('status'=>'Validated','period'=>$mainVar['year'].'-'.$mainVar['month'].'-01')) as $value)
            {
                $mainVar['count']++;
            }
            if($mainVar['count']>0)
                $message = 'You have '.$mainVar['count'].' non-verificated '.$mainVar['option'].'. You must validate them before '.$mainVar['date'];
        }elseif (FilterManager::model()->isUserTotalAccess('alert.media'))
        {
            $mainVar['date']   = Utilities::weekDaysSum(date('Y-m-01'),2);
            $mainVar['option'] ='opportunities';
            foreach(FinanceEntities::model()->getClients($mainVar['month'],$mainVar['year'],null,null,Yii::App()->user->getId(),null,null,null,null)['data'] as $opportunitie)
            {
                if(!$opportunitie['status_opp'])$mainVar['count']++;
            }
            if($mainVar['count']>0)
                $message = 'You have '.$mainVar['count'].' non-verificated '.$mainVar['option'].'. You must validate them before '.$mainVar['date'];
        }elseif (FilterManager::model()->isUserTotalAccess('alert.finance'))
        {
            $mainVar['date']  =strtotime(date('Y-m-d',strtotime('NOW'))) ;
            $mainVar['year']  =date('Y', $mainVar['date']);
            $mainVar['month'] =date('m', $mainVar['date']);
            $mainVar['option'] ='opportunities closed deal';
            foreach(FinanceEntities::model()->getClients($mainVar['month'],$mainVar['year'],null,null,null,null,null,null,null,true)['data'] as $opportunitie)
            {
                $opp=Opportunities::model()->findByPk($opportunitie['opportunitie_id']);
                if($opp->checkIsAbleInvoice() && !OpportunitiesValidation::model()->checkValidation($opp->id, $mainVar['year'].'-'.$mainVar['month'].'-01'))$mainVar['count']++;
            }
            if($mainVar['count']>0)
                $message = 'You have '.$mainVar['count'].' available '.$mainVar['option'].' to invoice.';
        }
        if(isset($message))
            echo '<div class="alert alert-now">'.$message.'</div>';
    }

    /**
     * Create dropdown of Account Managers
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterFinanceEntities($value, $htmlOptions = array(),$format='check',$name='financeEntities')
    {
        $defaultHtmlOptions = array(
            'empty' => 'All finance entities',
            'onChange' => '
                // if ( ! this.value) {
                //   return;
                // }
                $.post(
                    "' . Yii::app()->getBaseUrl() . '/financeEntities/getOpportunities/"+this.value+"?format=check",
                    "",
                    function(data)
                    {
                        // alert(data);
                        $("#opp_ids").html(data);
                    }
                )'
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        $criteria=new CDbCriteria;
        $criteria->compare('t.status','Active');
        $criteria->order = 't.name';
        $criteria->with = array('advertisers');
        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
            $criteria->compare('advertisers.cat', array('VAS','Affiliates','App Owners'));
        if( UserManager::model()->isUserAssignToRole('operation_manager') )
            $criteria->compare('advertisers.cat', array('Networks','Incent'));        
        $financeentities = FinanceEntities::model()->findAll($criteria);
        $list   = CHtml::listData($financeentities, 'id', 'name');
        return CHtml::dropDownList($name, $value, $list, $htmlOptions);
    }

    public static function filterRegions($value, $htmlOptions = array(),$format='check',$name='region')
    {
        $defaultHtmlOptions = array(
            'empty' => 'All regions',
            // 'onChange' => '
            //     // if ( ! this.value) {
            //     //   return;
            //     // }
            //     $.post(
            //         "' . Yii::app()->getBaseUrl() . '/financeEntities/getOpportunities/"+this.value+"?format=check",
            //         "",
            //         function(data)
            //         {
            //             // alert(data);
            //             $("#opp_ids").html(data);
            //         }
            //     )'
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        $criteria    = new CDbCriteria;
        $select = 'DISTINCT country.name';
        $criteria->compare('t.status','Active');
        $criteria->with = array('country');
        $criteria->order = 'name';
        $regions     = Regions::model()->findAll($criteria);
        $list        = CHtml::listData($regions, 'country.id_location', 'country.name');
        return CHtml::dropDownList($name, $value, $list, $htmlOptions);
    }

    public static function filterPublishers($value, $htmlOptions = array(),$format='check',$name='publisher')
    {
        $defaultHtmlOptions = array(
            'empty' => 'All publishers',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        $criteria    = new CDbCriteria;
        $criteria->order = 'name';
        $criteria->compare('t.status','Active');
        $criteria->join = 'INNER JOIN publishers on(t.id = publishers.providers_id)';
        $publishers  = Providers::model()->findAll($criteria);
        $list        = CHtml::listData($publishers, 'id', 'name');
        return CHtml::dropDownList($name, $value, $list, $htmlOptions);
    }
    public static function filterSites($value, $htmlOptions = array(),$format='check',$name='site')
    {
        $defaultHtmlOptions = array(
            'empty' => 'All sites',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        // $criteria    = new CDbCriteria;
        // $criteria->compare('t.status','Active');
        $sites = Sites::model()->findAll(array('order'=>'name'));
        $list  = CHtml::listData($sites, 'id', 'name');
        return CHtml::dropDownList($name, $value, $list, $htmlOptions);
    }

    // custom filters
    public static function groupFilter($controller, $items, $prefix, $title, $titleStyle='', $size=null, $color=null, $stacked=false){

        if(isset($title)){
            $buttons = array(
                array(
                    'label' => $title, 
                    'disabled' => 'disabled', 
                    'type' => 'info',
                    'htmlOptions' => array(
                        'style' => $titleStyle,
                        ),
                    ),
                );
        }

        foreach ($items as $key => $value) {
            echo CHtml::hiddenField($prefix.'['.$key.']', $value, array('id'=>''.$prefix.'-'.$key));
            $buttons[] = array(
                'label' => self::getLabel($key), 
                'active'=> $value, 
                'htmlOptions' => array(
                    'onclick' => '$("#'.$prefix.'-'.$key.'").val( 1 - $("#'.$prefix.'-'.$key.'").val() );',
                    ),
                );
        }

        $controller->widget(
            'bootstrap.widgets.TbButtonGroup',
            array(
                'stacked' => $stacked,
                'toggle' => 'checkbox',
                'type' => $color,
                'size' => $size,
                'buttons' => $buttons,
            )
        );
    }

    public static function pageSizeSelector($gridID){
        $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']); 
        echo '<ul class="yiipager page-size">';
        echo '<li><div>Results</div></li>';
        echo '<li>';
        echo CHtml::dropDownList(
            'pageSize',
            $pageSize,
            array(50=>50,100=>100,500=>500),
            array(
                'onchange'=>'$.fn.yiiGridView.update("'.$gridID.'",{ data:{pageSize: $(this).val() }})',
            ));
        echo '</li>';
        echo '</ul>';

    }
    public static function pagination($params=null){
        return array(
            'pageSize'=> Yii::app()->user->getState(
                'pageSize',
                Yii::app()->params['defaultPageSize']
                ),
            'params'=> $params,
            );
    }
    public static function paginationController(){
        // page size drop down changed
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // would interfere with pager and repetitive page size change
        }
    }
}
?>