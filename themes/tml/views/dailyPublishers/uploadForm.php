<?php
 
return array(
    'title' => 'Upload a CSV file',
 
    'attributes' => array(
        'enctype' => 'multipart/form-data',
    ),
 
    'elements' => array(
        'csv' => array(
            'type' => 'file',
        ),
    ),
 
    'buttons' => array(
        'reset' => array(
            'type' => 'reset',
            'label' => 'Reset',
        ),
        'submit' => array(
            'type' => 'submit',
            'label' => 'Upload',
        ),
    ),
);
