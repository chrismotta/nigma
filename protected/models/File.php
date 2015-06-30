<?php

class File extends CFormModel
{
    public $image;
    public $csv;
 
    public function rules()
    {
        return array(
            array('image', 'file', 'wrongType'=>'ERROR: Wrong File Type', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
            array('csv', 'file', 'wrongType'=>'ERROR: Wrong File Type', 'types'=>'csv', 'allowEmpty'=>true),
        );
    }
}