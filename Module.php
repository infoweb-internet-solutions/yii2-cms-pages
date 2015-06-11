<?php

namespace infoweb\pages;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * Enable link between a page and a slider from the 'sliders' module
     * @var boolean
     */
    public $enableSliders = false;
    
    /**
     * Module specific configuration of the ckEditor
     * @var array
     */
    public $ckEditorOptions = [
        'height' => 500
    ];
    
    public function init()
    {
        parent::init();

        Yii::configure($this, require(__DIR__ . '/config.php')); 
    }
}