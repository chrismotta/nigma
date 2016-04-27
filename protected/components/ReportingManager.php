<?php

class ReportingManager
{
	private static function getLabel($item){
        $attributeLabels = array(
            'advertiser'      => 'Advertiser', 
            'provider'        => 'Traffic Source', 
            // geo
            'connection_type' => 'Con. Type', 
            'country'         => 'Country', 
            'carrier'         => 'Carrier',
            // user_agent
            'device_type'     => 'Dev. Type',
            'device_brand'    => 'Dev. Brand',
            'device_model'    => 'Dev. Model',
            'os_type'         => 'OS Type',
            'os_version'      => 'OS Version',
            'browser_type'    => 'Browser Type',
            'browser_version' => 'Browser Version',
            );

        if(isset($attributeLabels[$item])){
            return $attributeLabels[$item];
        }else{
            return $item;
        }
    }

    /**
     * [addFilter description]
     * @param [type]  $controller [description]
     * @param [type]  $items      [description]
     * @param [type]  $prefix     [description]
     * @param [type]  $title      [description]
     * @param string  $titleStyle [description]
     * @param [type]  $size       [description]
     * @param [type]  $color      [description]
     * @param boolean $stacked    [description]
     */
    public static function addFilter($controller, $items, $prefix, $title, $titleStyle='', $size=null, $color=null, $stacked=false){

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
            $buttons[] = array(
                'label' => self::getLabel($key), 
                'active'=> $value, 
                'htmlOptions' => array(
                    'onclick' => '
                        if($("#s2id_'.$key.'").is(":visible"))
                            $("#'.$key.'").select2().val(null).trigger("change");
                        $("#s2id_'.$key.'").toggle(500);
                        ',
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

    /**
     * [multiSelect description]
     * @param  array  $value       [description]
     * @param  array  $options     [description]
     * @param  array  $htmlOptions [description]
     * @return [type]              [description]
     */
    public static function multiSelect($options, $htmlOptions=array(), $hide=false)
    {
    	$class = $hide ? 'multi-select multi-select-hide' : 'multi-select';
        $defaultHtmlOptions = array(
			'multiple'    => 'multiple',
			'placeholder' => 'All '.self::getLabel($options['name']),
            'id' => $options['name'],
            'class'       => $class,
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions); 


        return Yii::app()->controller->widget(
                'yiibooster.widgets.TbSelect2',
                array(
                    'name'        => $options['name'],
                    'data'        => $options['data'],
                    'value'       => $options['value'],
                    'htmlOptions' => $htmlOptions,
                )
        	);
    } 

    public static function dataMultiSelect($model, $column, $value=array()){

        $criteria = new CDbCriteria;
        $criteria->distinct = true;
        $criteria->select = $column;
        $criteria->order = $column;

        $data = CHtml::listData(
            $model::model()->findAll($criteria), $column, $column);

        self::multiSelect(
            array(
                'name'        => $column,
                'data'        => $data,
                'value'       => $value,
                ), array(), true);
    }

}