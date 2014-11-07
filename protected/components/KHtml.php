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
        $criteria->order = 'advertisers.name, country.ISO2';

        if ( $accountManagerId != NULL )
            $criteria->compare('account_manager_id', $accountManagerId);

        $opps = Opportunities::model()->with('ios')->findAll($criteria);
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
                    "' . Yii::app()->getBaseUrl() . '/dailyReport/getOpportunities/"+this.value,
                    "",
                    function(data)
                    {
                        // alert(data);
                        $(".opportunitie-dropdownlist").html(data);
                    }
                )'
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $medias = Users::model()->findUsersByRole('media');
        $list   = CHtml::listData($medias, 'id', 'FullName');
        return CHtml::dropDownList('accountManager', $value, $list, $htmlOptions);
    }

    /**
     * Create dropdown of networks
     * @param  $value
     * @param  $htmlOptions
     * @return html for dropdown
     */
    public static function filterNetworks($value, $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            'empty' => 'All networks',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        $networks = Networks::model()->findAll( array('order' => 'name') );
        $list     = CHtml::listData($networks, 'id', 'name');
        return CHtml::dropDownList('networks', $value, $list, $htmlOptions);
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

        $advs = Advertisers::model()->findAll( array('order' => 'name') );
        $list   = CHtml::listData($advs, 'id', 'name');
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
        $entities = KHtml::enumItem(new Ios, 'entity');
        return CHtml::dropDownList('entity', $value, $entities, $htmlOptions);
    }

    /**
     * Create autocomplete input of campaigns
     * @param  $value
     * @param  $networks_id
     * @param  $htmlOptions
     * @return html for autocomplete
     */
    public static function filterCampaigns($value, $networks_id = array(), $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            'placeholder' => 'All campaigns',
            'class'       => 'span4',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);

        if ( empty($networks_id) )
            $campaigns = Campaigns::model()->findAll( array('order' => 'id') );
        else
            $campaigns = Campaigns::model()->findAll( array('order' => 'id', 'condition' => 'networks_id IN (' . join($networks_id, ', ') . ')') );

        $list = array_values(CHtml::listData($campaigns, 'id', function($c) { return Campaigns::model()->getExternalName($c->id); } ));

        return Yii::app()->controller->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'name'        =>'campaign',
            'source'      =>$list,
            'value'       =>$value,
            // additional javascript options for the autocomplete plugin
            'htmlOptions' =>$htmlOptions,
            'options'     =>array(
                'minLength' => '1',
            ),
        ), true);
    }

}

?>