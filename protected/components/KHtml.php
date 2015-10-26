<?php

class KHtml extends CHtml
{

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
            'onchange' => 'if(presetChange==0)$("#dpp").val("4");',
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
                    case "2":
                        dateStart.setDate( today.getDate()-1 );
                        dateEnd.setDate( today.getDate()-1 );
                        break;
                    case "3":
                        dateStart.setDate( today.getDate()-8 );
                        dateEnd.setDate( today.getDate()-1 );
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
            3 =>'Last 7 days',
            4 =>'Custom',
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

        $medias = Users::model()->findUsersByRole('admin');//originaly media
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
            //FIXME verificar si es necesari prospect para tml
            $providers = Providers::model()->findAll( array('order' => 'name', 'condition' => "status='Active'") );
            $providers = CHtml::listData($providers, 'id', 'name');
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

        return CHtml::dropDownList('cat', $value, KHtml::enumItem(new Advertisers, 'cat'), $htmlOptions);
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
        
        $advs        = Advertisers::model()->findAll( array('order' => 'name', "condition"=>"status='Active'") );
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

        if ( empty($providers_id) )
            $campaigns = Campaigns::model()->findAll( array('order' => 'id', 'condition' => "status='Active'") );
        else
            $campaigns = Campaigns::model()->findAll( array('order' => 'id', 'condition' => "status='Active' AND providers_id IN (" . join($providers_id, ", ") . ")") );

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


        if (FilterManager::model()->isUserTotalAccess('media'))
            $accountManagerId=Yii::app()->user->id;

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
                    'placeholder' => 'All Opportunities',
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
        
        $categories=KHtml::enumItem(new Advertisers, 'cat');

        
        return Yii::app()->controller->widget(
        'yiibooster.widgets.TbSelect2',
            array(
                'name'        => $name,
                'data'        => $categories,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All Categories',
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
    public static function filterProvidersMulti($value, $providers=NULL, $htmlOptions = array(),$name)
    {
        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 
        
        if ( !$providers ) {
            $providers = Providers::model()->findAll( array('order' => 'name', 'condition' => "status='Active' AND prospect=10") );
            $providers = CHtml::listData($providers, 'id', 'name');
        }

        
        return Yii::app()->controller->widget(
        'yiibooster.widgets.TbSelect2',
            array(
                'name'        => $name,
                'data'        => $providers,
                'value'       => $value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All Providers',
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
        
        $medias = Users::model()->findUsersByRole('media');
        $list   = CHtml::listData($medias, 'id', 'FullName');

        
        return Yii::app()->controller->widget(
        'yiibooster.widgets.TbSelect2',
            array(
                'name'        => $name,
                'data'        => $list,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All Managers',
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
    public static function filterAdvertisersMulti($value, $accountManager=NULL, $htmlOptions = array(),$name)
    {

        $defaultHtmlOptions = array(
            'multiple' => 'multiple',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 
        $criteria = new CDbCriteria;
        $criteria->with  = array('regions', 'regions.financeEntities.advertisers','accountManager');
        $criteria->order = 'advertisers.name';


        if (FilterManager::model()->isUserTotalAccess('media'))
            $accountManager=Yii::app()->user->id;

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
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All Advertisers',
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
        $criteria->order = 'country.name';

        $opps = Opportunities::model()->with('regions','regions.financeEntities')->findAll($criteria);
        $data=array();
        foreach ($opps as $opp) {
            $data[$opp->regions->country->id_location]=$opp->regions->country->name;
        }
        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All Countries',
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
                    'placeholder' => 'All Models',
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
        $criteria->compare('status','Active');
        $criteria->order = 'name';
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
        $criteria->compare('t.status','Active');
        $criteria->with = array('country');
        $criteria->order = 'name';
        $regions     = Regions::model()->findAll($criteria);
        $list        = CHtml::listData($regions, 'id', 'country.name');
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
    
}
?>