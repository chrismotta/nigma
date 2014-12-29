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
     * Create a TbDatePicker customize for KickAds adServer
     * @param  string   $name 
     * @param  Date     $initialDate
     * @param  array()  $options
     * @param  array()  $htmlOptions
     * @return html for date picker
     */
    public static function datePicker($name, $initialDate, $options = array(), $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            'style' => 'width: 80px',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $defaultOptions = array(
            'autoclose'  => true,
            'todayHighlight' => true,
            'format'     => 'dd-mm-yyyy',
            'viewformat' => 'dd-mm-yyyy',
            'placement'  => 'right',
        );
        $options = array_merge($defaultOptions, $options);

        $r = '<label><div class="input-append">';
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
        $criteria->with  = array('ios', 'ios.advertisers', 'country');
        $criteria->compare('t.status', 'Active');
        $criteria->order = 'advertisers.name, country.ISO2';

        if ( $accountManagerId != NULL )
            $criteria->compare('account_manager_id', $accountManagerId);

        $opps = Opportunities::model()->with('ios')->findAll($criteria);
        $list = CHtml::listData($opps, 'id', 'virtualName');
        return CHtml::dropDownList('opportunitie', $value, $list, $htmlOptions);
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

    /**
     * Create dropdown of Account Managers
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterAccountManagers($value, $htmlOptions = array())
    {
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

        $medias = Users::model()->findUsersByRole('media');
        $list   = CHtml::listData($medias, 'id', 'FullName');
        return CHtml::dropDownList('accountManager', $value, $list, $htmlOptions);
    }

    /**
     * Create dropdown of providers
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterProviders($value, $providers=NULL, $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            'empty' => 'All providers',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        if ( !$providers ) {
            $providers = Providers::model()->findAll( array('order' => 'name') );
            $providers = CHtml::listData($providers, 'id', 'name');
        }
            
        return CHtml::dropDownList('providers', $value, $providers, $htmlOptions);
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
            'empty' => 'All advertisers',
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
    public static function filterAdvertisers($value, $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            'empty' => 'All advertisers',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);    
        
        $advs        = Advertisers::model()->findAll( array('order' => 'name') );
        $list        = CHtml::listData($advs, 'id', 'name');
        return CHtml::dropDownList('advertiser', $value, $list, $htmlOptions);
    }


    /**
     * Create dropdown of Countries
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterCountries($value, $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            'empty' => 'All countries',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $criteria = new CDbCriteria;
        $criteria->with  = array('country');
        $criteria->order = 'country.name';
        $opps            = Opportunities::model()->findAll($criteria);
        $list            = CHtml::listData($opps, 'country.id_location', 'country.name');
        return CHtml::dropDownList('country', $value, $list, $htmlOptions);
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
            $campaigns = Campaigns::model()->findAll( array('order' => 'id') );
        else
            $campaigns = Campaigns::model()->findAll( array('order' => 'id', 'condition' => 'providers_id IN (' . join($providers_id, ', ') . ')') );

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
        $criteria->with  = array('ios', 'ios.advertisers', 'country');
        $criteria->order = 'advertisers.name, country.ISO2';


        if (FilterManager::model()->isUserTotalAccess('media'))
            $accountManagerId=Yii::app()->user->id;

        if ( $accountManagerId != NULL )
            $criteria->compare('account_manager_id', $accountManagerId);

        $opps = Opportunities::model()->with('ios.advertisers', 'carriers')->findAll($criteria);
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
            $providers = Providers::model()->findAll( array('order' => 'name') );
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
        $criteria->with  = array('ios', 'ios.advertisers','accountManager');
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

        $opps = Opportunities::model()->with('ios')->findAll($criteria);
        $data=array();
        foreach ($opps as $opp) {
            $data[$opp->ios->advertisers->id]=$opp->ios->advertisers->name;
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
        $criteria->with  = array('ios', 'ios.advertisers','country');
        $criteria->order = 'country.name';

        $opps = Opportunities::model()->with('ios')->findAll($criteria);
        $data=array();
        foreach ($opps as $opp) {
            $data[$opp->country->id_location]=$opp->country->name;
        }
        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                'name'        => $name,
                'data'        => $data,
                'value'       =>$value,
                'htmlOptions' => $htmlOptions,
                'options'     => array(
                    'placeholder' => 'All Countriess',
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

    public static function currencyTotalsClients($totals=array())
    {
        $rowTotals='<div class="row totals-bar ">';
        if(count($totals)>0)
        {
            $span = floor( 12 / count($totals) );
            $alert = array('error', 'info', 'success', 'warning', 'muted');
            $i = 0;
            foreach($totals as $total){
                $rowTotals.= '
                <div class="span'.$span.'">
                    <div class="alert alert-'.$alert[$i].'">
                        <small >TOTAL '.$total['currency'].'</small>
                        <h3 class="">Subtotal: '.number_format($total['sub_total'],2).'</h3>
                        <h5 class="">Total Count: '.number_format($total['total_count'],2).'</h5>
                        <h5 class="">Total: '.number_format($total['total'],2).'</h5>
                        <h6 class="">Total Invoiced: '.number_format($total['total_invoiced'],2).'</h6>
                        <h6 class="">Invoiced Percent: '.round(($total['total_invoiced']*100)/$total['total'],2).'%</h6>
                    </div>
                </div>
                ';
                $i++;
            }
        }
        $rowTotals.='</div>';
        return $rowTotals;
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
                $rowTotals.= '
                <div class="span'.$span.'">
                    <div class="alert alert-'.$alert[$i].'">
                        <small >TOTAL</small>
                        <h3 class="">'.$total['currency'].' '.$total['total'].'</h3>
                    </div>
                </div>
                ';
                $i++;
            }
        }
        $rowTotals.='</div>';
        return $rowTotals;
    }
}
?>